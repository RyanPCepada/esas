<?php
// Include config file
require_once "../../config.php";

// Start the session
session_start();

// Check if the moderator is logged in
if (!isset($_SESSION['moderator_id'])) {
    die("You are not logged in.");
}

$moderator_id = $_SESSION['moderator_id']; // Store moderator_id for later use

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Get the POST data
$data = json_decode(file_get_contents("php://input"), true);
$post_id = $data['post_id'] ?? null; // Get the post ID from the request

$response = ['success' => false, 'message' => 'Something went wrong.'];

// Validate post ID
if ($post_id) {
    try {
        // Begin transaction
        $pdo->beginTransaction();

        // Retrieve post content and club name before deletion
        $getPostQuery = "SELECT post, club_id FROM tbl_posts WHERE post_id = :post_id";
        $stmt = $pdo->prepare($getPostQuery);
        $stmt->bindParam(':post_id', $post_id);
        $stmt->execute();
        $postDetails = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($postDetails) {
            $postContent = $postDetails['post'];
            $club_id = $postDetails['club_id'];

            // Limit post content to 70 characters and append "..." if necessary
            if (strlen($postContent) > 70) {
                $postContent = substr($postContent, 0, 70) . '...';
            }

            // Get the club name using club_id
            $getClubNameQuery = "SELECT clubName FROM tbl_clubs WHERE club_id = :club_id";
            $stmt = $pdo->prepare($getClubNameQuery);
            $stmt->bindParam(':club_id', $club_id);
            $stmt->execute();
            $clubDetails = $stmt->fetch(PDO::FETCH_ASSOC);
            $clubName = $clubDetails ? $clubDetails['clubName'] : 'Unknown Club';

            // Delete notifications associated with the post
            $stmt = $pdo->prepare("DELETE FROM tbl_notifications WHERE post_id = :post_id");
            $stmt->bindParam(':post_id', $post_id);
            $stmt->execute(); // No need to check for success here since it's a delete operation

            // Delete comments associated with the post
            $deleteCommentsQuery = "DELETE FROM tbl_comments WHERE post_id = :post_id";
            $stmt = $pdo->prepare($deleteCommentsQuery);
            $stmt->bindParam(':post_id', $post_id);
            $stmt->execute(); // No need to check for success here

            // Delete the post
            $deletePostQuery = "DELETE FROM tbl_posts WHERE post_id = :post_id";
            $stmt = $pdo->prepare($deletePostQuery);
            $stmt->bindParam(':post_id', $post_id);
            if (!$stmt->execute()) {
                throw new Exception("Failed to delete the post.");
            }

            // Log the activity in tbl_activity_logs using the moderator ID from session
            $activity = "You deleted your post '{$postContent}' in {$clubName}";
            $logSQL = "INSERT INTO tbl_activity_logs (activity, dateAdded, moderator_id) 
                       VALUES (:activity, NOW(), :moderator_id)";
            $logStmt = $pdo->prepare($logSQL);
            $logStmt->bindParam(":activity", $activity);
            $logStmt->bindParam(":moderator_id", $moderator_id); // Using the stored moderator_id
            if (!$logStmt->execute()) {
                throw new Exception("Failed to log the activity.");
            }

            // Commit transaction
            $pdo->commit();

            $response['success'] = true;
            $response['message'] = 'Post deleted successfully.';
        } else {
            // If post not found, rollback and set response message
            $pdo->rollBack();
            $response['message'] = 'Post not found.';
        }
    } catch (Exception $e) {
        // Rollback transaction in case of error
        $pdo->rollBack();
        $response['message'] = 'Error: ' . $e->getMessage();
    }
} else {
    $response['message'] = 'Invalid post ID.';
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
