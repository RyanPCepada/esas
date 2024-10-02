<?php
// Include config file
require_once "../../config.php";

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

        // Delete notifications associated with the post
        $stmt = $pdo->prepare("DELETE FROM tbl_notifications WHERE post_id = :post_id");
        $stmt->bindParam(':post_id', $post_id); // Use $post_id instead of $postId
        $stmt->execute();

        // Delete comments associated with the post
        $deleteCommentsQuery = "DELETE FROM tbl_comments WHERE post_id = :post_id"; // Adjust this to your actual comments table
        $stmt = $pdo->prepare($deleteCommentsQuery);
        $stmt->bindParam(':post_id', $post_id); // Use $post_id here
        $stmt->execute();

        // Delete the post
        $deletePostQuery = "DELETE FROM tbl_posts WHERE post_id = :post_id"; // Adjust this to your actual posts table
        $stmt = $pdo->prepare($deletePostQuery);
        $stmt->bindParam(':post_id', $post_id); // Use $post_id here
        $stmt->execute();

        // Commit transaction
        $pdo->commit();

        $response['success'] = true;
        $response['message'] = 'Post deleted successfully.';
    } catch (Exception $e) {
        // Rollback transaction in case of error
        $pdo->rollBack();
        $response['message'] = 'Error: ' . $e->getMessage(); // Log or handle the error message appropriately
    }
} else {
    $response['message'] = 'Invalid post ID.';
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
