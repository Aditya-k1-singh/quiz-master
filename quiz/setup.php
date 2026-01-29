<?php
session_start();

if (!isset($_SESSION['user'])) {
  header("Location: /index.php");
  exit;
}

if ($_POST) {
  $_SESSION['quiz_settings'] = [
    'category' => $_POST['category'],
    'difficulty' => $_POST['difficulty'],
    'question_count' => (int)($_POST['question_count'] ?? 5)
  ];
  error_log("[DEBUG setup] SID=".session_id()." quiz_settings=".json_encode($_SESSION['quiz_settings']));
  header("Location: play.php");
  exit;
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Quiz Setup</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<div class="container">
  <div class="card" style="max-width: 600px; margin: auto;">
    <h2 style="margin-bottom: 30px;">Quiz Setup</h2>

    <form method="POST" id="setupForm">
      <!-- CATEGORY SELECTION -->
      <div style="margin-bottom: 40px;">
        <h3 style="margin-bottom: 15px; font-size: 16px; font-weight: 600;">Select Category</h3>
        <div class="selection-group">
          <button type="button" class="selection-btn" data-target="category" data-value="HTML">
            📝 HTML
          </button>
          <button type="button" class="selection-btn" data-target="category" data-value="CSS">
            🎨 CSS
          </button>
        </div>
        <input type="hidden" name="category" id="category" value="HTML" required>
      </div>

      <!-- DIFFICULTY SELECTION -->
      <div style="margin-bottom: 40px;">
        <h3 style="margin-bottom: 15px; font-size: 16px; font-weight: 600;">Select Difficulty</h3>
        <div class="selection-group">
          <button type="button" class="selection-btn" data-target="difficulty" data-value="easy">
            🟢 Easy
          </button>
          <button type="button" class="selection-btn" data-target="difficulty" data-value="medium">
            🟡 Medium
          </button>
          <button type="button" class="selection-btn" data-target="difficulty" data-value="hard">
            🔴 Hard
          </button>
        </div>
        <input type="hidden" name="difficulty" id="difficulty" value="easy" required>
      </div>

      <!-- QUESTION COUNT SELECTION -->
      <div style="margin-bottom: 40px;">
        <h3 style="margin-bottom: 15px; font-size: 16px; font-weight: 600;">Number of Questions</h3>
        <input type="range" name="question_count" id="question_count" min="1" max="10" value="5" 
          style="width: 100%; height: 8px; border-radius: 5px; background: #e5e7eb; outline: none; -webkit-appearance: slider-horizontal; cursor: pointer;"
          oninput="updateCountDisplay(this.value)">
        <div style="text-align: center; margin-top: 12px;">
          <span id="count-display" style="font-size: 20px; font-weight: 700; color: #4f46e5;">5</span>
          <span style="font-size: 14px; color: #64748b; margin-left: 8px;">questions</span>
        </div>
      </div>

      <button type="submit" class="btn" style="width: 100%; padding: 14px; font-size: 16px;">
        Start Quiz
      </button>
    </form>
  </div>
</div>

<script>
function updateCountDisplay(value) {
  document.getElementById('count-display').textContent = value;
}

document.querySelectorAll('.selection-btn').forEach(btn => {
  btn.addEventListener('click', function(e) {
    e.preventDefault();
    const target = this.dataset.target;
    const value = this.dataset.value;
    
    // Update hidden input
    document.getElementById(target).value = value;
    
    // Remove active class from all buttons in this group
    document.querySelectorAll(`.selection-btn[data-target="${target}"]`).forEach(b => {
      b.classList.remove('active');
    });
    
    // Add active class to clicked button
    this.classList.add('active');
  });
});

// Set initial active state
document.querySelector('.selection-btn[data-value="HTML"]').classList.add('active');
document.querySelector('.selection-btn[data-value="easy"]').classList.add('active');
</script>

</body>
</html>