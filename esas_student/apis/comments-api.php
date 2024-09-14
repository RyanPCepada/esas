<?php
// Include database configuration file
require_once '../../config.php';

// Check if post_id and club_id are provided
if (isset($_GET['post_id']) && isset($_GET['club_id'])) {
    $post_id = $_GET['post_id'];
    $club_id = $_GET['club_id'];

    try {
        // Prepare the SQL query to fetch comments based on post_id and club_id
        $stmt = $pdo->prepare("
            SELECT c.comment_id, c.comment, c.dateAdded, s.firstName, s.lastName, s.profilePic
            FROM tbl_comments c
            JOIN tbl_students s ON c.student_id = s.student_id
            WHERE c.post_id = :post_id AND c.club_id = :club_id
            ORDER BY c.dateAdded ASC
        ");
        
        $stmt->bindParam(':post_id', $post_id);
        $stmt->bindParam(':club_id', $club_id);
        $stmt->execute();
        $comments = [];

    if (!empty($post_id)) {
        try {
            // Fetch comments, student profile pictures, comment_id, and student_id
            $sql = "SELECT c.comment_id, c.comment, c.dateAdded, c.student_id, CONCAT(s.firstName, ' ', s.lastName) as student_name, s.profilePic
                    FROM tbl_comments c
                    JOIN tbl_students s ON c.student_id = s.student_id
                    WHERE c.post_id = :post_id
                    ORDER BY c.dateAdded ASC";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":post_id", $post_id, PDO::PARAM_INT);
            $stmt->execute();
            $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $comments[] = [
                'id' => $row['comment_id'], // Fetch the comment_id
                'comment' => $row['comment'],
                'dateAdded' => $row['dateAdded'],
                'student_name' => $row['firstName'] . ' ' . $row['lastName'],
                'profilePic' => $row['profilePic']
            ];
        }

        if (!empty($comments)) {
            echo json_encode(['success' => true, 'comments' => $comments]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No comments found']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid parameters']);
}
?>
