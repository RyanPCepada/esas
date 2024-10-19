<?php
// Include config file
require_once "../../config.php";

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Define variables and initialize with empty values
$new_comment = "";
$new_comment_err = "";
$comment_id = ""; // Handle comment ID
$club_id = "";

// Start the session
session_start();

// Ensure student_id is set in the session
if (!isset($_SESSION['student_id'])) {
    echo json_encode(['success' => false, 'message' => 'Student not logged in.']);
    exit();
}

// Retrieve student_id from the session
$student_id = $_SESSION['student_id'];

// Optionally retrieve adminId if applicable, for logging
$adminId = $_SESSION['admin_id'] ?? null; // Adjust as necessary based on your session management

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate new_comment
    $input_new_comment = trim($_POST["new_comment"]);
    if (empty($input_new_comment)) {
        $new_comment_err = "Comment cannot be empty.";
    } else {
        $new_comment = $input_new_comment;
    }

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

    // Check input errors before updating the database
    if (empty($new_comment_err) && empty($comment_id_err) && empty($club_id_err)) {
        // Prepare an update statement
        $sql = "UPDATE tbl_comments
                SET comment = :new_comment
                WHERE comment_id = :comment_id AND student_id = :student_id";

        $stmt = $pdo->prepare($sql);

        // Bind variables to the prepared statement as parameters
        $stmt->bindParam(":new_comment", $new_comment);
        $stmt->bindParam(":comment_id", $comment_id, PDO::PARAM_INT);
        $stmt->bindParam(":student_id", $student_id, PDO::PARAM_INT);

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Log the update activity
            $clubSql = "SELECT clubName FROM tbl_clubs WHERE club_id = :club_id";
            $clubStmt = $pdo->prepare($clubSql);
            $clubStmt->bindParam(":club_id", $club_id, PDO::PARAM_INT);
            $clubStmt->execute();

            // Fetch the club name
            $club = $clubStmt->fetch(PDO::FETCH_ASSOC);
            $clubName = $club['clubName'] ?? 'Unknown Club'; // Default value if club not found

            // Prepare the activity log entry
            $activity = "You edited your comment in a post in $clubName";
            $logSQL = "INSERT INTO tbl_activity_logs (activity, dateAdded, admin_id, moderator_id, student_id) 
                        VALUES (:activity, NOW(), :admin_id, NULL, :student_id)";
            $logStmt = $pdo->prepare($logSQL);
            $logStmt->bindParam(":activity", $activity);
            $logStmt->bindParam(":admin_id", $adminId); // Ensure $adminId is defined
            $logStmt->bindParam(":student_id", $student_id);
            $logStmt->execute();

            // Comment updated successfully. Return a success response
            $redirect_url = '/esas/esas_student/home.php?club_id=' . urlencode($club_id);
            echo json_encode(['success' => true, 'message' => 'Comment updated successfully.', 'redirect_url' => $redirect_url]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Oops! Something went wrong. Please try again later.']);
        }

        // Close statements
        unset($stmt);
        unset($clubStmt);
        unset($logStmt);
    } else {
        // Display errors if any
        echo json_encode(['success' => false, 'message' => 'Errors: ' . $new_comment_err . ' ' . $comment_id_err . ' ' . $club_id_err]);
    }
}

// Close connection (if not using persistent connection)
// unset($pdo); // Uncomment if $pdo is not a persistent connection
?>
