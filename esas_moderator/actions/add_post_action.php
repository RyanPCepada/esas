<?php
session_start();
require_once "../../config.php";

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Fetch the current moderator's ID
$moderator_id = $_SESSION['moderator_id'];

// Initialize variables and error messages
$postContent = "";
$postContent_err = "";
$club_id = ""; 

// Check if club_id is provided in the POST request
if (isset($_POST['club_id'])) {
    $club_id = intval($_POST['club_id']); // Use intval to ensure it's an integer

    // Validate post content
    $input_postContent = trim($_POST["postContent"]);
    if (empty($input_postContent)) {
        $postContent_err = "Please enter the post content.";
    } else {
        $postContent = $input_postContent;
    }

    // Check for errors before inserting into the database
    if (empty($postContent_err)) {
        try {
            // Begin transaction
            $pdo->beginTransaction();

            // Prepare an insert statement for the post
            $sql = "INSERT INTO tbl_posts (post, dateAdded, club_id, moderator_id) VALUES (:post, NOW(), :club_id, :moderator_id)";
            $stmt = $pdo->prepare($sql);

            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":post", $postContent);
            $stmt->bindParam(":club_id", $club_id, PDO::PARAM_INT); 
            $stmt->bindParam(":moderator_id", $moderator_id, PDO::PARAM_INT);

            // Execute the statement
            if ($stmt->execute()) {
                // Get the ID of the inserted post
                $post_id = $pdo->lastInsertId();

                // Notify all students registered in the club
                $sql = "SELECT student_id FROM tbl_application WHERE club_id = :club_id AND status = 'active'";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(":club_id", $club_id, PDO::PARAM_INT);
                $stmt->execute();
                $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Check if any students were found
                if (!empty($students)) {
                    foreach ($students as $student) {
                        // Insert notification for each student
                        $sql = "INSERT INTO tbl_notifications (notification, student_id, club_id, post_id, is_read, dateAdded)
                                VALUES ('Posted an announcement', :student_id, :club_id, :post_id, 0, NOW())";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(":student_id", $student['student_id'], PDO::PARAM_INT);
                        $stmt->bindParam(":club_id", $club_id, PDO::PARAM_INT);
                        $stmt->bindParam(":post_id", $post_id, PDO::PARAM_INT);
                        $stmt->execute();
                    }
                }

                // Log the post creation activity in tbl_activity_logs
                $activity = "You created a post in the club with ID {$club_id}";
                $sql = "INSERT INTO tbl_activity_logs (activity, dateAdded, admin_id, moderator_id, student_id) 
                        VALUES (:activity, NOW(), NULL, :moderator_id, NULL)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(":activity", $activity);
                $stmt->bindParam(":moderator_id", $moderator_id, PDO::PARAM_INT);
                $stmt->execute();

                // Commit transaction
                $pdo->commit();

                // Return a JSON response
                echo json_encode([
                    "success" => true,
                    "message" => "Post created successfully!",
                    "redirect_url" => "/esas/esas_moderator/public/home.php?club_id={$club_id}"
                ]);
            } else {
                throw new Exception("Post insertion failed.");
            }

        } catch (Exception $e) {
            // Rollback transaction on error
            $pdo->rollBack();
            echo json_encode(["success" => false, "message" => "Oops! Something went wrong. Please try again later."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => $postContent_err]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Club ID is required."]);
}

// Close connection
unset($pdo);
?>
