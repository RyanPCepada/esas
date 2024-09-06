<?php
session_start();
require_once "../../config.php";

// Handle comment form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_id = isset($_POST['post_id']) ? $_POST['post_id'] : '';
    $comment = isset($_POST['comment']) ? $_POST['comment'] : '';

    // Fetch the current student's ID
    $student_id = $_SESSION['student_id'];
    
    // Fetch club_id from tbl_registration
    $sql = "SELECT club_id FROM tbl_registration WHERE student_id = :student_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":student_id", $student_id, PDO::PARAM_INT);
    $stmt->execute();
    $student = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($student) {
        $club_id = $student['club_id']; // Set club_id from student
        
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
                    window.location.href = "../home.php";
                </script>';
            } else {
                echo '<script>
                    alert("Failed to add comment. Please try again.");
                    window.location.href = "../home.php";
                </script>';
            }
        }
    } else {
        echo '<script>alert("Student not found.");</script>';
    }
}

// Close connection (if not using a persistent connection)
// unset($pdo); // Uncomment if $pdo is not a persistent connection
?>
