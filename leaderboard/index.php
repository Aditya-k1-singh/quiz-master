<?php
session_start();
require '../config/db.php';

// Fetch top scores
$stmt = $pdo->query(
  "SELECT u.username, r.score, r.total, r.created_at
   FROM results r
   JOIN users u ON u.id = r.user_id
   ORDER BY r.score DESC, r.created_at DESC
   LIMIT 10"
);
$leaders = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Leaderboard</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<div class="nav">
  <div class="logo">🏆 Leaderboard</div>
  <a href="../dashboard/index.php" class="btn secondary">Back</a>
</div>

<div class="container">
  <div class="card">
    <table width="100%" cellpadding="10">
      <thead>
        <tr>
          <th align="left">Rank</th>
          <th align="left">Username</th>
          <th align="left">Score</th>
          <th align="left">Date</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($leaders as $i => $row): ?>
          <tr>
            <td><?= $i + 1 ?></td>
            <td><?= htmlspecialchars($row['username']) ?></td>
            <td><?= $row['score'] ?>/<?= $row['total'] ?></td>
            <td><?= date("d M Y", strtotime($row['created_at'])) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

</body>
</html>