<?php
require 'config/db.php';

echo "<h2>Checking Database Content</h2>";

// Show distinct difficulty values
echo "<h3>Difficulty values in database:</h3>";
$result = $pdo->query("SELECT DISTINCT difficulty FROM questions")->fetchAll();
echo "<pre>";
foreach ($result as $row) {
  echo "'" . $row['difficulty'] . "' (length: " . strlen($row['difficulty']) . ")\n";
}
echo "</pre>";

// Test queries
echo "<h3>Test Queries:</h3>";

$tests = [
  ['HTML', 'easy'],
  ['HTML', 'medium'],
  ['HTML', 'hard'],
  ['CSS', 'easy'],
];

foreach ($tests as $test) {
  $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM questions WHERE category = ? AND LOWER(difficulty) = ?");
  $stmt->execute([$test[0], strtolower($test[1])]);
  $result = $stmt->fetch();
  echo "Category={$test[0]}, Difficulty={$test[1]}: <strong>" . $result['count'] . " questions</strong><br>";
}

// Show a sample question
echo "<h3>Sample Question:</h3>";
$sample = $pdo->query("SELECT * FROM questions LIMIT 1")->fetch();
if ($sample) {
  echo "<pre>";
  echo "Category: '" . $sample['category'] . "'\n";
  echo "Difficulty: '" . $sample['difficulty'] . "'\n";
  echo "Question: " . substr($sample['question'], 0, 50) . "...\n";
  echo "</pre>";
} else {
  echo "<p style='color: red;'><strong>No questions found!</strong> Database is empty.</p>";
}
?>
