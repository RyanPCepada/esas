<?php
// Include config file
require_once "../../config.php";

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Define variables and initialize with empty values
$post_content = "";
$post_content_err = "";
$post_id = ""; // Handle post ID
$club_id = ""; // Handle club ID

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate post_content
    $input_post_content = trim($_POST["post_content"]);
    if (empty($input_post_content)) {
        $post_content_err = "Post content cannot be empty.";
    } else {
        $post_content = $input_post_content;
    }

    // Validate post_id
    if (empty($_POST["post_id"])) {
        $post_id_err = "Post ID is required.";
    } else {
        $post_id = trim($_POST["post_id"]);
    }

    // Validate club_id
    if (empty($_POST["club_id"])) {
        $club_id_err = "Club ID is required.";
    } else {
        $club_id = trim($_POST["club_id"]);
    }

    // Check input errors before updating the database
    if (empty($post_content_err) && empty($post_id_err) && empty($club_id_err)) {
        try {
            // Update post in the database using PDO
            $stmt = $pdo->prepare("UPDATE tbl_posts SET post = :post_content, dateModified = NOW() WHERE post_id = :post_id");
            $stmt->bindParam(':post_content', $post_content);
            $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                // Prepare the redirect URL
                $redirect_url = '/esas/esas_moderator/public/home.php?club_id=' . urlencode($club_id);
                
                // Send success response with redirect URL
                echo json_encode(['success' => true, 'message' => 'Comment updated successfully.', 'redirect_url' => $redirect_url]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Oops! Something went wrong. Please try again later.']);
            }
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    } else {
        // Display errors if any
        echo json_encode(['success' => false, 'message' => 'Errors: ' . $post_content_err . ' ' . $post_id_err . ' ' . $club_id_err]);
    }
}
?>
