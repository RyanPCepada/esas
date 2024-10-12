<?php
require_once '../../config.php';
session_start();

date_default_timezone_set('Asia/Manila');
header('Content-Type: application/json');

// Check if moderator is logged in
if (!isset($_SESSION['moderator_id'])) {
    echo json_encode(['error' => 'Moderator not logged in.']);
    exit;
}

$moderator_id = $_SESSION['moderator_id'];
$club_id = isset($_GET['club_id']) ? intval($_GET['club_id']) : 0;

if ($club_id === 0) {
    echo json_encode(['error' => 'Invalid club ID.']);
    exit;
}

try {
    // Fetch all messages in the club between the moderator and students
    $stmt = $pdo->prepare("
        SELECT 
            s.student_id, 
            s.firstName, 
            s.middleName, 
            s.lastName, 
            s.department,
            s.profilePic,
            c.message, 
            c.dateAdded AS messageDate, 
            c.sender_id,
            c.recipient_id
        FROM 
            tbl_students s
        JOIN 
            tbl_registration r ON s.student_id = r.student_id 
        LEFT JOIN 
            tbl_chats c ON (s.student_id = c.sender_id OR s.student_id = c.recipient_id)
        WHERE 
            r.club_id = :club_id 
            AND r.status = 'active'
            AND c.club_id = :club_id
        ORDER BY 
            c.dateAdded DESC
    ");
    
    $stmt->execute(['club_id' => $club_id]);
    $chats = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Check if any messages were found
    if (!$chats) {
        echo json_encode(['message' => 'No chat messages found for this club.']);
        exit;
    }

    // Format the date for each chat
    foreach ($chats as &$chat) {
        $dateTime = new DateTime($chat['messageDate']);
        $now = new DateTime();
        $yesterday = (clone $now)->modify('-1 day');

        if ($dateTime->format('Y-m-d') === $now->format('Y-m-d')) {
            $chat['messageDate'] = 'Today ' . $dateTime->format('g:i A');
        } elseif ($dateTime->format('Y-m-d') === $yesterday->format('Y-m-d')) {
            $chat['messageDate'] = 'Yesterday ' . $dateTime->format('g:i A');
        } else {
            $chat['messageDate'] = $dateTime->format('M j, Y g:i A');
        }
    }

    // Return all chat messages as JSON
    echo json_encode($chats);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
