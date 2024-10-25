<?php
// Initialize the session
session_start();
require_once '../config.php';
 
// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Check if the admin is logged in
if (isset($_SESSION['admin_id'])) {
    // Function to insert activity log
    function insertActivityLog($pdo, $admin_id) {
        $query = "INSERT INTO tbl_activity_logs (activity, dateAdded, admin_id) VALUES (:activity, :dateAdded, :admin_id)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'activity' => 'You logged out of your account',
            'dateAdded' => date('Y-m-d H:i:s'), // current timestamp
            'admin_id' => $admin_id
        ]);
    }

    // Insert the activity log for logout
    insertActivityLog($pdo, $_SESSION['admin_id']);
}

// Unset all of the session variables
$_SESSION = array();
 
// Destroy the session.
session_destroy();
 
// Redirect to login page
header("Location: ../esas_admin/login.php");
exit;
?>