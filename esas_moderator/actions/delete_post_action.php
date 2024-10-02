<?php
// Include config file
require_once "../../config.php";

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Initialize variables
$post_id = "";

// Check if the post ID is provided
if (isset($_GET['post_id'])) {
    $post_id = trim($_GET['post_id']);

    // Begin a transaction
    $pdo->beginTransaction();
    try {
        // Delete comments associated with the post
        $deleteCommentsStmt = $pdo->prepare("DELETE FROM tbl_comments WHERE post_id = :post_id");
        $deleteCommentsStmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
        $deleteCommentsStmt->execute();

        // Delete the post
        $deletePostStmt = $pdo->prepare("DELETE FROM tbl_posts WHERE post_id = :post_id");
        $deletePostStmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
        $deletePostStmt->execute();

        // Commit the transaction
        $pdo->commit();

        // Return success response
        echo json_encode(['success' => true, 'message' => 'Post and associated comments deleted successfully.']);
    } catch (PDOException $e) {
        // Rollback the transaction in case of an error
        $pdo->rollBack();
        // Log the error message
        error_log($e->getMessage()); // Log error to server log
        echo json_encode(['success' => false, 'message' => 'Error deleting post.']); // Keep the message generic
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Post ID is required.']);
}
?>
