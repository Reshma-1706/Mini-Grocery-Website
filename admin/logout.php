<?php
session_start();

// Only destroy admin session
unset($_SESSION['admin']); // remove the admin login session

session_destroy(); // optionally destroy all sessions

// Redirect to admin login
header("Location: login.php");
exit;
?>
