<?php
session_start();
require '../config/db.php';

if ($_POST) {
  $stmt = $pdo->prepare("SELECT * FROM users WHERE username=?");
  $stmt->execute([$_POST['username']]);
  $user = $stmt->fetch();

  if ($user && password_verify($_POST['password'], $user['password'])) {
    $_SESSION['user'] = $user;
    header("Location: ../dashboard/index.php");
    exit;
  }
}
?>