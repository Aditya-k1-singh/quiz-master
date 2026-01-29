<?php
session_start();

if (!isset($_SESSION['result'])) {
  header("Location: ../dashboard/index.php");
  exit;
}

$result = $_SESSION['result'];
?>
<!DOCTYPE html>
<html>
<head>
  <title>Quiz Result</title>
  <link rel="stylesheet" href="../assets/style.css">
  <style>
    .result-container {
      max-width: 900px;
      margin: auto;
      padding: 40px 20px;
    }

    .result-main {
      background: white;
      border-radius: 16px;
      padding: 40px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.08);
      margin-bottom: 30px;
      display: flex;
      gap: 40px;
      align-items: center;
    }

    .score-circle {
      flex-shrink: 0;
      width: 200px;
      height: 200px;
      border-radius: 50%;
      background: linear-gradient(135deg, #f0f4ff 0%, #eef2ff 100%);
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      border: 4px solid #e0e7ff;
    }

    .score-percentage {
      font-size: 56px;
      font-weight: 700;
      color: #4f46e5;
      line-height: 1;
    }

    .score-label {
      font-size: 12px;
      text-transform: uppercase;
      letter-spacing: 1px;
      color: #94a3b8;
      margin-top: 8px;
      font-weight: 600;
    }

    .result-content {
      flex: 1;
    }

    .result-header {
      display: flex;
      gap: 10px;
      margin-bottom: 12px;
      align-items: center;
    }

    .badge {
      display: inline-block;
      padding: 6px 12px;
      border-radius: 6px;
      font-size: 12px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .badge-success {
      background: #dcfce7;
      color: #16a34a;
    }

    .result-title {
      font-size: 28px;
      font-weight: 700;
      margin-bottom: 8px;
      color: #0f172a;
    }

    .result-subtitle {
      font-size: 15px;
      color: #64748b;
      margin-bottom: 20px;
    }

    .action-buttons {
      display: flex;
      gap: 12px;
      flex-wrap: wrap;
    }

    .btn-secondary-outline {
      background: #f1f5f9;
      color: #0f172a;
      border: 2px solid #e2e8f0;
      padding: 12px 20px;
      border-radius: 10px;
      cursor: pointer;
      font-weight: 600;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      transition: all 0.2s ease;
      font-size: 14px;
    }

    .btn-secondary-outline:hover {
      border-color: #cbd5e1;
      background: #e2e8f0;
    }

    .metrics-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 20px;
      margin-bottom: 30px;
    }

    .metric-card {
      background: white;
      border-radius: 12px;
      padding: 20px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.05);
      border: 1px solid #e5e7eb;
    }

    .metric-label {
      font-size: 11px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      color: #94a3b8;
      margin-bottom: 8px;
      font-weight: 600;
    }

    .metric-value {
      font-size: 28px;
      font-weight: 700;
      color: #0f172a;
    }

    .metric-sublabel {
      font-size: 13px;
      color: #64748b;
      margin-top: 4px;
    }
  </style>
</head>
<body>

<!-- NAVBAR -->
<div class="nav">
  <div class="logo">🧠 Dynamic Quiz Master</div>
  <a href="../dashboard/index.php" class="btn secondary" style="font-size: 14px; padding: 10px 16px;">Back to Dashboard</a>
</div>

<div class="result-container">
  <!-- MAIN RESULT SECTION -->
  <div class="result-main">
    <!-- CIRCULAR SCORE -->
    <div class="score-circle">
      <div class="score-percentage"><?= $result['score'] ?>/<?= $result['total'] ?></div>
      <div class="score-label">Correct Answers</div>
    </div>

    <!-- PERFORMANCE INFO -->
    <div class="result-content">
      <div class="result-header">
        <span class="badge badge-success">✓ QUIZ COMPLETE</span>
      </div>
      <h1 class="result-title">Performance Breakdown</h1>
      <p class="result-subtitle">
        <?= ($result['percentage'] ?? 0) >= 80 
          ? '🎉 Excellent effort! You performed very well!' 
          : '💪 Great try! Keep practicing to improve your score.' 
        ?>
      </p>

      <div class="action-buttons">
        <button class="btn-secondary-outline" onclick="shareResult()">
          📤 Share
        </button>
        <a href="setup.php" class="btn-secondary-outline" style="text-decoration: none;">
          🔄 Retry Quiz
        </a>
        <a href="../dashboard/index.php" class="btn" style="text-decoration: none; padding: 12px 24px; display: inline-flex; align-items: center; gap: 8px;">
          ▶ Continue
        </a>
      </div>
    </div>
  </div>

  <!-- METRICS GRID -->
  <div class="metrics-grid">
    <div class="metric-card">
      <div class="metric-label">Accuracy</div>
      <div class="metric-value"><?= $result['percentage'] ?? 0 ?>%</div>
      <div class="metric-sublabel">Overall accuracy</div>
    </div>

    <div class="metric-card">
      <div class="metric-label">Score</div>
      <div class="metric-value"><?= $result['score'] ?>/<?= $result['total'] ?></div>
      <div class="metric-sublabel">Correct answers</div>
    </div>

    <div class="metric-card">
      <div class="metric-label">Performance</div>
      <div class="metric-value" style="color: <?= ($result['percentage'] ?? 0) >= 80 ? '#22c55e' : '#f97316' ?>">
        <?= ($result['percentage'] ?? 0) >= 80 ? '⭐ Excellent' : '👍 Good' ?>
      </div>
      <div class="metric-sublabel">Rating</div>
    </div>
  </div>
</div>

<script>
function shareResult() {
  const score = document.querySelector('.score-percentage').textContent;
  const text = `I scored ${score} on the Dynamic Quiz Master! 🎯`;
  if (navigator.share) {
    navigator.share({ title: 'Quiz Result', text: text });
  } else {
    alert(text);
  }
}
</script>

</body>
</html>
