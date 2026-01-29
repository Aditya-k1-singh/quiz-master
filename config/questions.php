<?php
// Database-based question management functions

function fetchQuestionsFromDatabase($pdo, $category, $difficulty, $count) {
  try {
    // Normalize difficulty to lowercase for matching
    $normalizedDifficulty = strtolower($difficulty);
    
    // Debug: Log what we're searching for
    error_log("DEBUG: Searching for Category=$category, Difficulty=$normalizedDifficulty (original: $difficulty), Count=$count");
    
    // First, check what difficulties exist in the database
    $diffStmt = $pdo->query("SELECT DISTINCT difficulty FROM questions");
    $diffs = $diffStmt->fetchAll(PDO::FETCH_COLUMN);
    error_log("DEBUG: Available difficulties in DB: " . json_encode($diffs));
    
    // Check what categories exist
    $catStmt = $pdo->query("SELECT DISTINCT category FROM questions");
    $cats = $catStmt->fetchAll(PDO::FETCH_COLUMN);
    error_log("DEBUG: Available categories in DB: " . json_encode($cats));
    
    $stmt = $pdo->prepare("
      SELECT id, question, option_a, option_b, option_c, option_d, correct_answer
      FROM questions
      WHERE category = ? AND LOWER(difficulty) = ?
      ORDER BY RAND()
      LIMIT " . intval($count) . "
    ");
    
    $stmt->execute([$category, $normalizedDifficulty]);
    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    error_log("DEBUG: Query returned " . count($questions) . " questions");
    
    if (empty($questions)) {
      // Try a test query without LOWER
      $testStmt = $pdo->prepare("SELECT COUNT(*) as cnt FROM questions WHERE category = ? AND difficulty = ?");
      $testStmt->execute([$category, $difficulty]);
      $testResult = $testStmt->fetch();
      error_log("DEBUG: Test query (no LOWER): " . $testResult['cnt'] . " results");
      
      return null;
    }
    
    // Format questions for session storage
    $formatted = [];
    foreach ($questions as $q) {
      $formatted[] = [
        'id' => $q['id'],
        'question' => $q['question'],
        'options' => [
          'A' => $q['option_a'],
          'B' => $q['option_b'],
          'C' => $q['option_c'],
          'D' => $q['option_d']
        ],
        'correct' => $q['correct_answer']
      ];
    }
    
    return $formatted;
  } catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    return null;
  }
}

function cacheQuestionsInSession($questions) {
  $_SESSION['quiz_questions'] = $questions;
  $_SESSION['quiz_current_index'] = 0;
  $_SESSION['quiz_answers'] = [];
}

function getCurrentQuestion() {
  if (!isset($_SESSION['quiz_questions'][$_SESSION['quiz_current_index']])) {
    return null;
  }
  return $_SESSION['quiz_questions'][$_SESSION['quiz_current_index']];
}

function getQuestionIndex() {
  return $_SESSION['quiz_current_index'];
}

function getTotalQuestions() {
  return count($_SESSION['quiz_questions'] ?? []);
}

function nextQuestion() {
  if (isset($_SESSION['quiz_current_index'])) {
    $_SESSION['quiz_current_index']++;
  }
}

function hasMoreQuestions() {
  return isset($_SESSION['quiz_questions']) && $_SESSION['quiz_current_index'] < count($_SESSION['quiz_questions']);
}

function storeAnswer($answer) {
  $_SESSION['quiz_answers'][] = $answer;
}

function calculateFinalScore() {
  if (!isset($_SESSION['quiz_questions']) || !isset($_SESSION['quiz_answers'])) {
    return 0;
  }
  
  $correct = 0;
  foreach ($_SESSION['quiz_answers'] as $index => $answer) {
    if ($answer === $_SESSION['quiz_questions'][$index]['correct']) {
      $correct++;
    }
  }
  
  return $correct;
}

function getCorrectCount() {
  return calculateFinalScore();
}

function getTotalScore() {
  return getTotalQuestions();
}

function getPercentage() {
  $total = getTotalScore();
  if ($total === 0) return 0;
  return round((getCorrectCount() / $total) * 100);
}
?>
