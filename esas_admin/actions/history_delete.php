<?php
// Include config file
require_once "../../config.php";

// Start the session
session_start();

// Check if the admin is logged in 
if (!isset($_SESSION['admin_id'])) { 
    die("You are not logged in.");
}

$admin_id = $_SESSION['admin_id'];

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Handle "X" icon (single activity deletion)
if (isset($_GET['id'])) {
    $activity_id = htmlspecialchars($_GET['id']);
    
    // Prepare and execute the delete statement for a single activity using PDO
    $stmt = $pdo->prepare("DELETE FROM tbl_activity_logs WHERE activity_id = :activity_id");
    $stmt->bindParam(':activity_id', $activity_id, PDO::PARAM_INT);
    
    if ($stmt->execute()) {
        // Redirect after successful deletion
        header("Location: /esas/esas_admin/history.php");
        exit;
    } else {
        echo "Error deleting activity.";
    }
}

// Handle bulk deletion (checkbox selection)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete']) && isset($_POST['activity_ids'])) {
    $activity_ids = $_POST['activity_ids']; // Array of selected activity IDs
    
    // Use a prepared statement to delete multiple activities
    $stmt = $pdo->prepare("DELETE FROM tbl_activity_logs WHERE activity_id = :activity_id");
    
    foreach ($activity_ids as $id) {
        $id = htmlspecialchars($id);
        $stmt->bindParam(':activity_id', $id, PDO::PARAM_INT);
        
        if (!$stmt->execute()) {
            // Log an error message if the deletion fails
            echo "Error deleting activity with ID: $id<br>";
        }
    }
    
    // Redirect after successful bulk deletion
    header("Location: /esas/esas_admin/history.php");
    exit;
} else {
    echo "No activities selected for deletion.";
}
?>
