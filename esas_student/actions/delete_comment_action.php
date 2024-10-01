<?php
// Include config file
require_once "../../config.php";

// Start the session
session_start();

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Ensure student_id is set in the session
if (!isset($_SESSION['student_id'])) {
    echo json_encode(['success' => false, 'message' => 'Student not logged in.']);
    exit();
}

// Retrieve student_id from the session
$student_id = $_SESSION['student_id'];

// Define variables and initialize with empty values
$comment_id = "";
$club_id = "";
$comment_id_err = "";
$club_id_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate comment_id
    if (empty($_POST["comment_id"])) {
        $comment_id_err = "Comment ID is required.";
    } else {
        $comment_id = trim($_POST["comment_id"]);
    }

    // Validate club_id
    if (empty($_POST["club_id"])) {
        $club_id_err = "Club ID is required.";
    } else {
        $club_id = trim($_POST["club_id"]);
    }

    // Check input errors before deleting from the database
    if (empty($comment_id_err) && empty($club_id_err)) {
        // Prepare a delete statement
        $sql = "DELETE FROM tbl_comments WHERE comment_id = :comment_id AND student_id = :student_id";

        $stmt = $pdo->prepare($sql);

        // Bind variables to the prepared statement as parameters
        $stmt->bindParam(":comment_id", $comment_id, PDO::PARAM_INT);
        $stmt->bindParam(":student_id", $student_id, PDO::PARAM_INT);

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Correct the redirect URL to include the full path
            $redirect_url = '/esas/esas_student/home.php?club_id=' . urlencode($club_id);
            echo json_encode(['success' => true, 'message' => 'Comment deleted successfully.', 'redirect_url' => $redirect_url]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Oops! Something went wrong. Please try again later.']);
        }
    } else {
        // Display errors if any
        echo json_encode(['success' => false, 'message' => 'Errors: ' . $comment_id_err . ' ' . $club_id_err]);
    }

    // Close statement
    unset($stmt);
}

// Close connection (if not using persistent connection)
// unset($pdo); // Uncomment if $pdo is not a persistent connection
?>
