<?php
// Initialize the session
session_start();
include '../config.php'; // Include your database connection

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Check if the moderator is logged in
if (isset($_SESSION['moderator_id'])) {
    // Function to insert activity log
    function insertActivityLog($pdo, $moderator_id) {
        $query = "INSERT INTO tbl_activity_logs (activity, dateAdded, moderator_id) VALUES (:activity, :dateAdded, :moderator_id)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'activity' => 'You logged out of your account',
            'dateAdded' => date('Y-m-d H:i:s'), // current timestamp
            'moderator_id' => $moderator_id
        ]);
    }

    // Insert the activity log for logout
    insertActivityLog($pdo, $_SESSION['moderator_id']);
}

// Unset all of the session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to login page
header("Location: ../esas_moderator/login.php");
exit;
?>
