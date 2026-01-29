<?php
session_start();

// If user already logged in, go to dashboard
if (isset($_SESSION['user'])) {
  header("Location: dashboard/index.php");
  exit;
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<div class="auth-box card">
  <h2>Login</h2>

  <form method="POST" action="auth/login.php">
    <input type="text" name="username" placeholder="Username" required>
    <br><br>
    <input type="password" name="password" placeholder="Password" required>
    <br><br>
    <button class="btn">Login</button>
  </form>

  <p style="margin-top:15px">
    Don’t have an account?
    <a href="auth/register.php">Register</a>
  </p>
</div>

</body>
</html>