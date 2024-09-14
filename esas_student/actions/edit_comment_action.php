<?php
// Include config file
require_once "../../config.php";

// Define variables and initialize with empty values
$new_comment = "";
$new_comment_err = "";
$comment_id = ""; // Handle comment ID
$club_id = "";

// Start the session
session_start();

// Ensure student_id is set in the session
if (!isset($_SESSION['student_id'])) {
    echo "<script>alert('Student not logged in.'); window.history.back();</script>";
    exit();
}

// Retrieve student_id from the session
$student_id = $_SESSION['student_id'];

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
    if (empty($new_comment_err) && empty($comment_id_err)) {
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
            // Comment updated successfully. Redirect to comments page
            echo "<script>
                // alert('Comment updated successfully!\\nNew Comment: " . addslashes($new_comment) . "\\nComment ID: " . htmlspecialchars($comment_id) . "');
                window.location.href = '../home.php?club_id=" . urlencode($club_id) . "';
            </script>";
            exit();
        } else {
            echo "Oops! Something went wrong. Please try again later.";
            // Debug: echo $stmt->errorInfo(); // Uncomment for debugging
        }
    }

    // Close statement
    unset($stmt);
}

// Close connection (if not using persistent connection)
// unset($pdo); // Uncomment if $pdo is not a persistent connection
?>
