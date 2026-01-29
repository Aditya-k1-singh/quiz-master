<?php
// Display PHP error logs
echo "<h2>PHP Error Logs</h2>";

$logFile = ini_get('error_log');
echo "<p><strong>Log file location:</strong> " . $logFile . "</p>";

if (file_exists($logFile)) {
  $content = file_get_contents($logFile);
  $lines = array_reverse(explode("\n", $content));
  echo "<pre style='background: #f0f0f0; padding: 10px; max-height: 500px; overflow-y: auto;'>";
  for ($i = 0; $i < min(50, count($lines)); $i++) {
    if (trim($lines[$i])) {
      echo htmlspecialchars($lines[$i]) . "\n";
    }
  }
  echo "</pre>";
} else {
  echo "<p style='color: red;'>Log file not found</p>";
}
?>
