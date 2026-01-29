<?php
require 'config/db.php';

echo "<h2>Database Debug</h2>";

// Check total count
$total = $pdo->query("SELECT COUNT(*) as cnt FROM questions")->fetch();
echo "<p><strong>Total questions:</strong> " . $total['cnt'] . "</p>";

if ($total['cnt'] == 0) {
  echo "<p style='color: red;'><strong>⚠️ DATABASE IS EMPTY!</strong></p>";
  echo "<p><a href='final-seed.php'>Click here to seed</a></p>";
  exit;
}

// Show all distinct difficulties
echo "<h3>Distinct Difficulties:</h3>";
$diffs = $pdo->query("SELECT DISTINCT difficulty FROM questions ORDER BY difficulty")->fetchAll(PDO::FETCH_COLUMN);
echo "<pre>";
var_dump($diffs);
echo "</pre>";

// Show count by category and difficulty
echo "<h3>Count by Category & Difficulty:</h3>";
$breakdown = $pdo->query("
  SELECT category, difficulty, COUNT(*) as cnt 
  FROM questions 
  GROUP BY category, difficulty 
  ORDER BY category, difficulty
")->fetchAll();

echo "<table border='1' cellpadding='10'>";
echo "<tr style='background: #f0f0f0;'><th>Category</th><th>Difficulty</th><th>Count</th></tr>";
foreach ($breakdown as $row) {
  echo "<tr><td>" . htmlspecialchars($row['category']) . "</td>";
  echo "<td>" . htmlspecialchars($row['difficulty']) . " (len: " . strlen($row['difficulty']) . ")</td>";
  echo "<td>" . $row['cnt'] . "</td></tr>";
}
echo "</table>";

// Test query
echo "<h3>Test Queries:</h3>";
$tests = [
  ['HTML', 'easy'],
  ['HTML', 'medium'],
  ['HTML', 'hard'],
  ['CSS', 'easy'],
];

foreach ($tests as $test) {
  $stmt = $pdo->prepare("SELECT COUNT(*) as cnt FROM questions WHERE category = ? AND LOWER(difficulty) = ?");
  $stmt->execute([$test[0], strtolower($test[1])]);
  $result = $stmt->fetch();
  echo "<p>Category={$test[0]}, Difficulty={$test[1]}: <strong>" . $result['cnt'] . "</strong></p>";
}

// Show actual sample data
echo "<h3>Sample Data:</h3>";
$sample = $pdo->query("SELECT * FROM questions LIMIT 3")->fetchAll();
foreach ($sample as $q) {
  echo "<p><strong>Category:</strong> '" . $q['category'] . "'</p>";
  echo "<strong>Difficulty:</strong> '" . $q['difficulty'] . "' (bytes: " . strlen($q['difficulty']) . ")<br>";
  echo "<strong>Q:</strong> " . substr($q['question'], 0, 80) . "...<br>";
  echo "<hr>";
}
?>
