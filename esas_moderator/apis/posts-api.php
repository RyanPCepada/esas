<?php
require_once '../../config.php';

// Set the content type to JSON
header('Content-Type: application/json');

// Handle HTTP methods
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        session_start();

        // Ensure the moderator is logged in
        if (!isset($_SESSION['moderator_id'])) {
            http_response_code(403);
            echo json_encode(['error' => 'Unauthorized']);
            exit();
        }

        $moderator_id = $_SESSION['moderator_id'];

        // Fetch the club_id for the logged-in moderator from the tbl_clubs_and_moderators table
        $stmt = $pdo->prepare('SELECT club_id FROM tbl_clubs_and_moderators WHERE moderator_id = ?');
        $stmt->execute([$moderator_id]);
        $moderator = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$moderator) {
            http_response_code(404);
            echo json_encode(['error' => 'Moderator not found']);
            exit();
        }

        $club_id = $moderator['club_id'];

        // Check if an ID parameter is provided
        if (isset($_GET['post_id'])) {
            // Fetch a single post by post_id
            $post_id = $_GET['post_id'];
            $stmt = $pdo->prepare('
                SELECT tbl_posts.*, tbl_moderators.firstName, tbl_moderators.middleName, tbl_moderators.lastName, tbl_moderators.profilePic
                FROM tbl_posts
                JOIN tbl_moderators ON tbl_posts.moderator_id = tbl_moderators.moderator_id
                WHERE tbl_posts.post_id = ? AND tbl_posts.club_id = ?
            ');
            $stmt->execute([$post_id, $club_id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                // Construct the full name
                $result['fullName'] = trim("{$result['firstName']} {$result['middleName']} {$result['lastName']}");
                echo json_encode($result);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Post not found']);
            }
        } else {
            // Fetch all posts for the current club
            $stmt = $pdo->prepare('
                SELECT tbl_posts.*, tbl_moderators.firstName, tbl_moderators.middleName, tbl_moderators.lastName, tbl_moderators.profilePic
                FROM tbl_posts
                JOIN tbl_moderators ON tbl_posts.moderator_id = tbl_moderators.moderator_id
                WHERE tbl_posts.club_id = ?
                ORDER BY tbl_posts.dateAdded DESC
            ');
            $stmt->execute([$club_id]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Add full name to each post
            foreach ($result as &$post) {
                $post['fullName'] = trim("{$post['firstName']} {$post['middleName']} {$post['lastName']}");
            }

            echo json_encode($result);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}
?>
