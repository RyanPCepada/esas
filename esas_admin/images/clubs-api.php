<?php
require_once '../config.php';

// Set the content type to JSON
header('Content-Type: application/json');

// Handle HTTP methods
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Check if an ID parameter is provided
        if(isset($_GET['club_id'])) {
            // Read operation (fetch a single club by club_id)
            $club_id = $_GET['club_id'];
            $stmt = $pdo->prepare('SELECT * FROM clubs WHERE club_id = ?');
            $stmt->execute([$club_id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if($result) {
                // Fetch count of students in this club
                $stmt_count = $pdo->prepare('SELECT COUNT(*) as member_count FROM registered_students WHERE club_id = ?');
                $stmt_count->execute([$club_id]);
                $member_count = $stmt_count->fetch(PDO::FETCH_ASSOC)['member_count'];

                // Append member count to result
                $result['membersCount'] = $member_count;

                echo json_encode($result);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Club not found']);
            }
        } else {
            // Read operation (fetch all clubs)
            $stmt = $pdo->query('SELECT * FROM clubs');
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Fetch counts of students for all clubs
            foreach ($result as &$club) {
                $stmt_count = $pdo->prepare('SELECT COUNT(*) as member_count FROM registered_students WHERE club_id = ?');
                $stmt_count->execute([$club['club_id']]);
                $club['membersCount'] = $stmt_count->fetch(PDO::FETCH_ASSOC)['member_count'];
            }

            echo json_encode($result);
        }
        break;
        
    default:
        // Invalid method
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}
?>
