<?php
session_start();

// Protect dashboard (no direct access)
if (!isset($_SESSION['user'])) {
  header("Location: /index.php");
  exit;
}

require '../config/db.php';

// Fetch user stats
$stmt = $pdo->prepare(
  "SELECT COUNT(*) AS total_quizzes, 
          COALESCE(AVG(score / total * 100), 0) AS avg_score
   FROM results
   WHERE user_id = ?"
);
$stmt->execute([$_SESSION['user']['id']]);
$stats = $stmt->fetch();

// Fetch all quiz results for progress display
$resultStmt = $pdo->prepare(
  "SELECT id, score, total, created_at 
   FROM results
   WHERE user_id = ?
   ORDER BY created_at DESC
   LIMIT 10"
);
$resultStmt->execute([$_SESSION['user']['id']]);
$quizResults = $resultStmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<!-- NAVBAR -->
<div class="nav">
  <div class="logo">
    <span>🧠</span>
    <span>Dynamic Quiz Master</span>
  </div>
  <a href="../auth/logout.php" class="btn secondary">Logout</a>
</div>

<!-- MAIN -->
<div class="container">

  <!-- WELCOME CARD -->
  <div class="card" style="margin-bottom:30px">
    <h2>Welcome back, <?= htmlspecialchars($_SESSION['user']['username']) ?> 👋</h2>
    <p style="color:#64748b;margin-top:6px">
      Ready to challenge yourself today?
    </p>
  </div>

  <!-- STATS -->
  <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:20px">

    <div class="card">
      <h3>Total Quizzes</h3>
      <p style="font-size:32px;font-weight:700;margin-top:10px">
        <?= (int)$stats['total_quizzes'] ?>
      </p>
    </div>

    <div class="card">
      <h3>Average Score</h3>
      <p style="font-size:32px;font-weight:700;margin-top:10px">
        <?= round($stats['avg_score']) ?>%
      </p>
    </div>

  </div>

  <!-- QUIZ PROGRESS -->
  <?php if (!empty($quizResults)): ?>
  <div class="card" style="margin-top:30px">
    <h3>📊 Recent Quiz Results</h3>
    <div style="margin-top:20px;display:flex;flex-direction:column;gap:12px">
      <?php foreach ($quizResults as $quiz): ?>
      <div style="display:flex;justify-content:space-between;align-items:center;padding:14px;background:#f8fafc;border-radius:8px;border-left:4px solid #4f46e5">
        <div>
          <p style="font-weight:600;color:#0f172a">Quiz Completed</p>
          <p style="font-size:13px;color:#64748b;margin-top:3px"><?= date('M d, Y • g:i A', strtotime($quiz['created_at'])) ?></p>
        </div>
        <div style="text-align:right">
          <p style="font-size:26px;font-weight:700;color:#4f46e5"><?= $quiz['score'] ?>/<?= $quiz['total'] ?></p>
          <p style="font-size:12px;color:#94a3b8;margin-top:2px"><?= round(($quiz['score'] / $quiz['total']) * 100) ?>% accuracy</p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>

  <!-- ACTIONS -->
  <div class="card" style="margin-top:30px">
    <h3>Quick Actions</h3>
    <div style="margin-top:15px;display:flex;gap:15px;flex-wrap:wrap">
      <a href="../quiz/setup.php" class="btn">Start New Quiz</a>
      <a href="../leaderboard/index.php" class="btn secondary">View Leaderboard</a>
    </div>
  </div>

</div>

</body>
</html>
