<?php
require 'config/db.php';

// Check what values are actually in the database
$result = $pdo->query("SELECT DISTINCT difficulty FROM questions")->fetchAll();
echo "<h3>Difficulty values in database:</h3>";
echo "<pre>";
foreach ($result as $row) {
  echo "'" . $row['difficulty'] . "'\n";
}
echo "</pre>";

// Also check categories
$result = $pdo->query("SELECT DISTINCT category FROM questions")->fetchAll();
echo "<h3>Category values in database:</h3>";
echo "<pre>";
foreach ($result as $row) {
  echo "'" . $row['category'] . "'\n";
}
echo "</pre>";

// Test a query
echo "<h3>Test query with 'Easy':</h3>";
$stmt = $pdo->prepare("SELECT COUNT(*) as count FROM questions WHERE difficulty = ? AND category = ?");
$stmt->execute(['Easy', 'HTML']);
$result = $stmt->fetch();
echo "Count: " . $result['count'];

echo "<h3>Test query with 'easy':</h3>";
$stmt = $pdo->prepare("SELECT COUNT(*) as count FROM questions WHERE difficulty = ? AND category = ?");
$stmt->execute(['easy', 'HTML']);
$result = $stmt->fetch();
echo "Count: " . $result['count'];
?>
