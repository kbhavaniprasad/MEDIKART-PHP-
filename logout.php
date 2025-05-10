<?php
session_start();

// Unset all session variables
$_SESSION = [];

// Destroy the session
session_destroy();

// Clear cookies
setcookie('user_details', '', time() - 3600, '/');
setcookie('just_registered', '', time() - 3600, '/');
setcookie('details', '', time() - 3600, '/');

// Redirect to login page
echo "<script>alert('You have been logged out.'); window.location.href = 'loginpage.php';</script>";
exit;
?>