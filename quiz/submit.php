<?php
session_start();
require '../config/db.php';
require '../config/questions.php';

if (!isset($_SESSION['quiz_questions'])) {
  header("Location: setup.php");
  exit;
}

$userAnswer = $_POST['answer'] ?? null;
$currentIndex = $_SESSION['quiz_current_index'] ?? 0;
$totalQuestions = count($_SESSION['quiz_questions']);

// Store current answer
storeAnswer($userAnswer);

// Check if there are more questions
if (hasMoreQuestions()) {
  // Move to next question
  nextQuestion();
  header("Location: play.php");
  exit;
} else {
  // Quiz finished - calculate final score
  $correct = getCorrectCount();
  $total = getTotalScore();
  
  // Save to database
  $stmt = $pdo->prepare(
    "INSERT INTO results (user_id, score, total) VALUES (?, ?, ?)"
  );
  $stmt->execute([
    $_SESSION['user']['id'],
    $correct,
    $total
  ]);

  // Store result in session
  $_SESSION['result'] = [
    'score' => $correct,
    'total' => $total,
    'percentage' => getPercentage()
  ];

  // Clear quiz session
  unset($_SESSION['quiz_questions']);
  unset($_SESSION['quiz_current_index']);
  unset($_SESSION['quiz_answers']);

  header("Location: result.php");
  exit;
}