<?php
require_once '../../config.php';
session_start();

date_default_timezone_set('Asia/Manila');
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

try {
    $club_id = isset($_GET['club_id']) ? intval($_GET['club_id']) : 0; 

    if ($club_id === 0) {
        echo json_encode(['error' => 'Invalid club ID']);
        exit;
    }

    $current_moderator_id = isset($_SESSION['moderator_id']) ? intval($_SESSION['moderator_id']) : 0;

    // Fetch all participants (moderators and students) with their latest messages
    $participantsQuery = "
        SELECT 
            m.moderator_id AS id, m.firstName AS moderator_firstName, m.lastName AS moderator_lastName, 
            m.profilePic AS moderator_profilePic, 'moderator' AS role, c.message, c.dateAdded
        FROM tbl_moderators m
        LEFT JOIN tbl_chats c ON c.moderator_id = m.moderator_id AND c.club_id = :club_id
        WHERE m.moderator_id != :current_moderator_id AND m.moderator_id IN (SELECT moderator_id FROM tbl_clubs_and_moderators WHERE club_id = :club_id)

        UNION ALL

        SELECT 
            s.student_id AS id, s.firstName AS student_firstName, s.lastName AS student_lastName, 
            s.profilePic AS student_profilePic, 'student' AS role, c.message, c.dateAdded
        FROM tbl_students s
        LEFT JOIN tbl_chats c ON c.student_id = s.student_id AND c.club_id = :club_id
        WHERE s.student_id IN (SELECT student_id FROM tbl_registration WHERE club_id = :club_id AND status = 'active')
    ";

    $stmt = $pdo->prepare($participantsQuery);
    $stmt->bindParam(':club_id', $club_id, PDO::PARAM_INT);
    $stmt->bindParam(':current_moderator_id', $current_moderator_id, PDO::PARAM_INT);
    $stmt->execute();
    $participants = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Format dateAdded field for all participants
    foreach ($participants as &$participant) {
        if ($participant['dateAdded']) {
            $date = new DateTime($participant['dateAdded']);
            $participant['dateAdded'] = $date->format('M d, Y | h:i A');
        } else {
            $participant['dateAdded'] = null;
        }
    }

    echo json_encode(['participants' => $participants]);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
