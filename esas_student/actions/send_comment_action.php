<?php
session_start();
require_once "../../config.php";

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
                // Comment inserted successfully
                echo '<script>
                    alert("Comment added successfully.");
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
