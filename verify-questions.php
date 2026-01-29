<?php
require 'config/db.php';

$result = $pdo->query("SELECT COUNT(*) as total FROM questions")->fetch();
echo "<h2>Total Questions: " . $result['total'] . "</h2>";

$breakdown = $pdo->query("SELECT category, difficulty, COUNT(*) as count FROM questions GROUP BY category, difficulty ORDER BY category, difficulty")->fetchAll();
echo "<table border='1' cellpadding='10'>";
echo "<tr><th>Category</th><th>Difficulty</th><th>Count</th></tr>";
foreach ($breakdown as $row) {
  echo "<tr><td>" . $row['category'] . "</td><td>" . $row['difficulty'] . "</td><td>" . $row['count'] . "</td></tr>";
}
echo "</table>";
?>
