<?php
session_start();
require '../config/db.php';

$error = null;

if ($_POST) {
  $username = trim($_POST['username']);
  $password = $_POST['password'];

  // Basic validation
  if (strlen($username) < 3 || strlen($password) < 6) {
    $error = "Username must be 3+ chars and password 6+ chars.";
  } else {

    // Check if username already exists
    $check = $pdo->prepare("SELECT id FROM users WHERE username=?");
    $check->execute([$username]);

    if ($check->rowCount() > 0) {
      $error = "Username already taken.";
    } else {

      // Hash password
      $hashed = password_hash($password, PASSWORD_DEFAULT);

      // Insert user
      $stmt = $pdo->prepare(
        "INSERT INTO users (username, password) VALUES (?, ?)"
      );
      $stmt->execute([$username, $hashed]);

      // Auto-login after register
      $_SESSION['user'] = [
        'id' => $pdo->lastInsertId(),
        'username' => $username
      ];

      header("Location: ../dashboard/index.php");
      exit;
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Register</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<div class="auth-box card">
  <h2>Create Account</h2>

  <?php if ($error): ?>
    <p style="color:red"><?= $error ?></p>
  <?php endif; ?>

  <form method="POST">
    <input type="text" name="username" placeholder="Username" required>
    <br><br>
    <input type="password" name="password" placeholder="Password" required>
    <br><br>
    <button class="btn">Register</button>
  </form>

  <p style="margin-top:15px">
    Already have an account?
    <a href="../index.php">Login</a>
  </p>
</div>

</body>
</html>