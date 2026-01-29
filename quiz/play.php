<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require '../config/db.php';
require '../config/questions.php';

// Protect route
if (!isset($_SESSION['user'])) {
  header("Location: ../index.php");
  exit;
}

if (!isset($_SESSION['quiz_settings'])) {
  header("Location: setup.php");
  exit;
}

// Get settings
$category = $_SESSION['quiz_settings']['category'];
$difficulty = $_SESSION['quiz_settings']['difficulty'];
$questionCount = $_SESSION['quiz_settings']['question_count'] ?? 5;

// Fetch questions from database if not already cached
if (!isset($_SESSION['quiz_questions']) || empty($_SESSION['quiz_questions'])) {
  $questions = fetchQuestionsFromDatabase($pdo, $category, $difficulty, $questionCount);
  
  if (!$questions) {
    die("ERROR: No questions found for Category=$category, Difficulty=$difficulty, Count=$questionCount");
  }
  
  cacheQuestionsInSession($questions);
}

// Get current question
$currentQuestion = getCurrentQuestion();
if (!$currentQuestion) {
  header("Location: result.php");
  exit;
}

$currentIndex = $_SESSION['quiz_current_index'] ?? 0;
$totalQuestions = count($_SESSION['quiz_questions']);
$progressPercent = (($currentIndex + 1) / $totalQuestions) * 100;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Quiz</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<!-- NAVBAR -->
<div class="nav">
  <div class="logo">🧠 Quiz in Progress</div>
  <div style="display: flex; gap: 20px; align-items: center;">
    <span style="font-size: 14px; color: #64748b;">
      Question <strong><?= $currentIndex + 1 ?></strong> of <strong><?= $totalQuestions ?></strong>
    </span>
    <div class="timer">
      Time Left: <span id="time">10</span>s
    </div>
  </div>
</div>

<!-- QUIZ CONTAINER -->
<div class="container">

  <!-- PROGRESS -->
  <div class="progress" style="margin-bottom:20px">
    <div id="progress-bar" style="width:<?= $progressPercent ?>%"></div>
  </div>

  <!-- QUESTION CARD -->
  <div class="card">
    <h2><?= htmlspecialchars($currentQuestion['question'], ENT_QUOTES, 'UTF-8') ?></h2>

    <form id="quizForm" method="POST" action="submit.php" style="margin-top:20px">
      <input type="hidden" name="answer" id="answer">

      <?php foreach ($currentQuestion['options'] as $key => $value): ?>
        <div class="option" data-value="<?= $key ?>">
          <strong><?= $key ?>.</strong> <?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?>
        </div>
      <?php endforeach; ?>

      <button class="btn" style="margin-top:20px; width: 100%;">
        <?= ($currentIndex + 1 < $totalQuestions) ? 'Next Question' : 'Submit Quiz' ?>
      </button>
    </form>
  </div>

</div>

<script src="../assets/index.js"></script>
</body>
</html>