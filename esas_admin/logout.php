<?php
// Initialize the session
session_start();
 
// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Unset all of the session variables
$_SESSION = array();
 
// Destroy the session.
session_destroy();
 
// Redirect to login page
header("Location: ../esas_admin/login.php");
exit;
?>