<?php
session_start();
require_once "../../config.php";

$response = array('success' => false, 'message' => '', 'comments' => array());

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $post_id = isset($_GET['post_id']) ? $_GET['post_id'] : '';

    if (!empty($post_id)) {
        try {
            // Fetch comments along with student or moderator profile pictures and names
            $sql = "SELECT c.comment, c.dateAdded, 
                           CASE 
                               WHEN c.student_id IS NOT NULL THEN CONCAT(s.firstName, ' ', s.lastName)
                               WHEN c.moderator_id IS NOT NULL THEN CONCAT(m.firstName, ' ', m.lastName)
                           END as commenter_name,
                           CASE 
                               WHEN c.student_id IS NOT NULL THEN s.profilePic
                               WHEN c.moderator_id IS NOT NULL THEN m.profilePic
                           END as profilePic
                    FROM comments c
                    LEFT JOIN tbl_students s ON c.student_id = s.student_id
                    LEFT JOIN tbl_moderators m ON c.moderator_id = m.moderator_id
                    WHERE c.post_id = :post_id
                    ORDER BY c.dateAdded ASC";

            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":post_id", $post_id, PDO::PARAM_INT);
            $stmt->execute();
            $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($comments) {
                $response['success'] = true;
                $response['comments'] = $comments;
            } else {
                $response['message'] = 'No comments found.';
            }
        } catch (PDOException $e) {
            $response['message'] = 'Database error: ' . $e->getMessage();
        }
    } else {
        $response['message'] = 'Post ID is required.';
    }
} else {
    $response['message'] = 'Invalid request method.';
}

echo json_encode($response);
?>
