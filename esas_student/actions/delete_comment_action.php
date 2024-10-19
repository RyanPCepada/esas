<?php
// Include config file
require_once "../../config.php";

// Start the session
session_start();

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
            // Retrieve the club name for logging
            $clubSql = "SELECT clubName FROM tbl_clubs WHERE club_id = :club_id";
            $clubStmt = $pdo->prepare($clubSql);
            $clubStmt->bindParam(":club_id", $club_id, PDO::PARAM_INT);
            $clubStmt->execute();

            // Fetch the club name
            $club = $clubStmt->fetch(PDO::FETCH_ASSOC);
            $clubName = $club['clubName'] ?? 'Unknown Club'; // Default value if club not found

            // Log the deletion activity
            $activity = "You deleted your comment in a post in $clubName";
            $logSQL = "INSERT INTO tbl_activity_logs (activity, dateAdded, admin_id, moderator_id, student_id) 
                        VALUES (:activity, NOW(), :admin_id, NULL, :student_id)";
            $logStmt = $pdo->prepare($logSQL);
            $logStmt->bindParam(":activity", $activity);
            $logStmt->bindParam(":admin_id", $adminId); // Ensure $adminId is defined
            $logStmt->bindParam(":student_id", $student_id);
            $logStmt->execute();

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
    unset($clubStmt); // Unset club statement
    unset($logStmt); // Unset log statement
}

// Close connection (if not using persistent connection)
// unset($pdo); // Uncomment if $pdo is not a persistent connection
?>
