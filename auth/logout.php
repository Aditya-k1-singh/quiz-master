<?php
// Start the session
session_start();

// Remove all session variables
$_SESSION = [];

// Destroy the session
session_destroy();

// Redirect user to login page
header("Location: /index.php");
exit;