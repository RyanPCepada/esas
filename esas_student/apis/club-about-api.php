<?php
require_once '../../config.php';

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Set the content type to JSON
header('Content-Type: application/json');

// Check if club_id is provided in the URL
if (isset($_GET['club_id'])) {
    $club_id = intval($_GET['club_id']); // Ensure club_id is an integer

    try {
        // Prepare the SQL query to fetch all relevant information
        $sql = "
            SELECT 
                c.club_id,
                c.clubName,
                c.information,
                c.coverPhoto,
                c.dateAdded AS clubDateAdded,
                c.dateModified AS clubDateModified,
                f.firstName AS founderFirstName,
                f.middleName AS founderMiddleName,
                f.lastName AS founderLastName,
                f.profilePic AS founderProfilePic,
                GROUP_CONCAT(m.firstName, ' ', m.middleName, ' ', m.lastName SEPARATOR ', ') AS moderators,
                GROUP_CONCAT(s.firstName, ' ', s.middleName, ' ', s.lastName SEPARATOR ', ') AS members,
                GROUP_CONCAT(DISTINCT e.title ORDER BY e.dateAdded DESC SEPARATOR ', ') AS events,
                GROUP_CONCAT(DISTINCT a.attachmentFileName ORDER BY a.dateUploaded DESC SEPARATOR ', ') AS attachments,
                GROUP_CONCAT(DISTINCT r.goal ORDER BY r.dateDecided DESC SEPARATOR ', ') AS goals,
                GROUP_CONCAT(DISTINCT r.misssion ORDER BY r.dateDecided DESC SEPARATOR ', ') AS missions,
                GROUP_CONCAT(DISTINCT r.vision ORDER BY r.dateDecided DESC SEPARATOR ', ') AS visions,
                GROUP_CONCAT(DISTINCT r.activities ORDER BY r.dateDecided DESC SEPARATOR ', ') AS activities
            FROM tbl_clubs c
            LEFT JOIN tbl_moderators f ON c.founder_id = f.moderator_id
            LEFT JOIN tbl_clubs_and_moderators cm ON c.club_id = cm.club_id
            LEFT JOIN tbl_moderators m ON cm.moderator_id = m.moderator_id
            LEFT JOIN tbl_registration rg ON c.club_id = rg.club_id
            LEFT JOIN tbl_students s ON rg.student_id = s.student_id
            LEFT JOIN tbl_events e ON c.club_id = e.club_id
            LEFT JOIN tbl_posts_attachments a ON c.club_id = a.club_id
            LEFT JOIN tbl_club_requests r ON c.club_id = r.club_id
            WHERE c.club_id = :club_id
            GROUP BY c.club_id, f.firstName, f.middleName, f.lastName
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":club_id", $club_id, PDO::PARAM_INT);
        $stmt->execute();
        
        // Fetch the results
        $clubInfo = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($clubInfo) {
            echo json_encode([
                "success" => true,
                "club" => $clubInfo
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "No club found."
            ]);
        }
    } catch (Exception $e) {
        echo json_encode([
            "success" => false,
            "message" => "An error occurred: " . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "Club ID is required."
    ]);
}

// Close connection
unset($pdo);
?>
