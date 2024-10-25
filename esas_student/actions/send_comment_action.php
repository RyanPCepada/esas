<?php
session_start();
require_once "../../config.php";

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Handle comment form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_id = isset($_POST['post_id']) ? $_POST['post_id'] : '';
    $comment = isset($_POST['comment']) ? $_POST['comment'] : '';
    $club_id = isset($_POST['club_id']) ? $_POST['club_id'] : ''; // Fetch club_id from POST data

    // Fetch the current student's ID
    $student_id = $_SESSION['student_id'];

    if (!empty($club_id)) {
        if (!empty($post_id) && !empty($comment)) {
            // Insert comment into database
            $sql = "INSERT INTO tbl_comments (comment, dateAdded, post_id, club_id, student_id) VALUES (:comment, NOW(), :post_id, :club_id, :student_id)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":comment", $comment, PDO::PARAM_STR);
            $stmt->bindParam(":post_id", $post_id, PDO::PARAM_INT);
            $stmt->bindParam(":club_id", $club_id, PDO::PARAM_INT);
            $stmt->bindParam(":student_id", $student_id, PDO::PARAM_INT);
            
            if ($stmt->execute()) {
                // Log the activity
                // Retrieve the club name for logging
                $clubSql = "SELECT clubName FROM tbl_clubs WHERE club_id = :club_id";
                $clubStmt = $pdo->prepare($clubSql);
                $clubStmt->bindParam(":club_id", $club_id, PDO::PARAM_INT);
                $clubStmt->execute();

                // Fetch the club name
                $club = $clubStmt->fetch(PDO::FETCH_ASSOC);
                $clubName = $club['clubName'] ?? 'Unknown Club'; // Default value if club not found

                // Prepare the activity log entry
                $activity = "You commented in a post in $clubName";
                $logSQL = "INSERT INTO tbl_activity_logs (activity, dateAdded, student_id) VALUES (:activity, NOW(), :student_id)";
                $logStmt = $pdo->prepare($logSQL);
                $logStmt->bindParam(":activity", $activity);
                $logStmt->bindParam(":student_id", $student_id);
                $logStmt->execute(); // Log the activity

                // Comment inserted successfully
                echo '<script>
                    // alert("Comment added successfully.");
                    window.location.href = "../home.php?club_id=' . urlencode($club_id) . '";
                </script>';
            } else {
                echo '<script>
                    alert("Failed to add comment. Please try again.");
                    window.location.href = "../home.php?club_id=' . urlencode($club_id) . '";
                </script>';
            }
        } else {
            echo '<script>alert("Post ID or comment is missing.");</script>';
        }
    } else {
        echo '<script>alert("Club ID is missing.");</script>';
    }
}
?>
