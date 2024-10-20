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

// Check if the delete request is for multiple activities or a single activity
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check for Clear All action
    if (isset($_POST['clear_all'])) {
        // Prepare the SQL statement to delete all activities for the current admin
        $sql = "DELETE FROM tbl_activity_logs WHERE admin_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$admin_id]);
    
        // Provide feedback
        if ($stmt->rowCount() > 0) {
            $_SESSION['message'] = "History is cleared.";
        } else {
            $_SESSION['message'] = "No activities to clear.";
        }
    } elseif (isset($_POST['delete'])) {
        // Handle multiple deletions
        if (isset($_POST['activity_ids']) && is_array($_POST['activity_ids'])) {
            $activity_ids = $_POST['activity_ids'];
            
            // Prepare the SQL statement for deletion
            $placeholders = rtrim(str_repeat('?,', count($activity_ids)), ','); // Create placeholders
            $sql = "DELETE FROM tbl_activity_logs WHERE activity_id IN ($placeholders) AND admin_id = ?";
            $stmt = $pdo->prepare($sql);
            
            // Execute the statement with the activity IDs and the admin ID
            $stmt->execute(array_merge($activity_ids, [$admin_id]));
            
            // Provide feedback
            if ($stmt->rowCount() > 0) {
                $_SESSION['message'] = "Selected activities have been deleted successfully.";
            } else {
                $_SESSION['message'] = "No activities were deleted. Please check your selection.";
            }
        } else {
            $_SESSION['message'] = "No activities to delete.";
        }
    } else {
        $_SESSION['message'] = "Invalid action.";
    }
} elseif (isset($_GET['id'])) {
    // Handle individual deletion when clicking the "X" icon
    $activity_id = $_GET['id'];
    
    // Prepare the SQL statement for deletion
    $sql = "DELETE FROM tbl_activity_logs WHERE activity_id = ? AND admin_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$activity_id, $admin_id]);
    
    // Provide feedback
    if ($stmt->rowCount() > 0) {
        $_SESSION['message'] = "The activity has been deleted successfully.";
    } else {
        $_SESSION['message'] = "The activity could not be deleted. Please try again.";
    }
} else {
    $_SESSION['message'] = "Invalid request.";
}

// Redirect back to the history page after the operation
header("Location: /esas/esas_admin/history.php");
exit;
//HERE
?>
