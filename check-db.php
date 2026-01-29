<?php
require 'config/db.php';

echo "<h2>Database Diagnostic</h2>";

// Total count
$result = $pdo->query("SELECT COUNT(*) as total FROM questions")->fetch();
echo "<p><strong>Total questions in DB:</strong> " . $result['total'] . "</p>";

if ($result['total'] === 0) {
  echo "<p style='color: red;'><strong>⚠️ Database is EMPTY! Need to seed questions.</strong></p>";
  echo "<p><a href='seed-questions.php'>Click here to seed the database</a></p>";
} else {
  echo "<h3>Questions by Category and Difficulty:</h3>";
  $breakdown = $pdo->query("
    SELECT category, difficulty, COUNT(*) as count 
    FROM questions 
    GROUP BY category, difficulty 
    ORDER BY category, difficulty
  ")->fetchAll();
  
  echo "<table border='1' cellpadding='10'>";
  echo "<tr><th>Category</th><th>Difficulty</th><th>Count</th></tr>";
  foreach ($breakdown as $row) {
    echo "<tr><td>" . $row['category'] . "</td><td>" . $row['difficulty'] . "</td><td>" . $row['count'] . "</td></tr>";
  }
  echo "</table>";
  
  echo "<h3>Sample question (first one):</h3>";
  $sample = $pdo->query("SELECT * FROM questions LIMIT 1")->fetch();
  echo "<pre>";
  print_r($sample);
  echo "</pre>";
  
  echo "<h3>Test query:</h3>";
  $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM questions WHERE category = ? AND LOWER(difficulty) = ?");
  $stmt->execute(['HTML', 'easy']);
  $test = $stmt->fetch();
  echo "Query for Category=HTML, Difficulty=easy: <strong>" . $test['count'] . " results</strong>";
}
?>
