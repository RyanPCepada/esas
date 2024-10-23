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
        // Prepare the SQL query to fetch all relevant description
        $sql = "
            SELECT 
                c.club_id, 
                c.clubName, 
                c.description, 
                c.mission, 
                c.vision, 
                c.history, 
                c.coverPhoto, 
                c.dateAdded AS clubDateAdded, 
                c.dateModified AS clubDateModified,
                f.firstName AS founderFirstName,
                f.middleName AS founderMiddleName,
                f.lastName AS founderLastName,
                f.profilePic AS founderProfilePic,
                GROUP_CONCAT(DISTINCT CONCAT(m.firstName, ' ', m.middleName, ' ', m.lastName, '|', m.profilePic, '|', m.department, '|', cm.dateAdded) ORDER BY m.firstName SEPARATOR ', ') AS moderators,
                GROUP_CONCAT(DISTINCT CONCAT(s.firstName, ' ', s.middleName, ' ', s.lastName, '|', s.profilePic, '|', s.department, '|', s.year, '|', r.dateApproved) 
                            ORDER BY s.firstName SEPARATOR ', ') AS members,
                r.dateApplied AS registrationDate,
                r.status AS registrationStatus,
                r.question1,
                r.question2,
                r.question3
            FROM 
                tbl_clubs c
            LEFT JOIN 
                tbl_registration r ON r.club_id = c.club_id AND r.status = 'active'  -- Only fetch active registrations
            LEFT JOIN 
                tbl_students s ON s.student_id = r.student_id -- Fetch only the active students based on the registration table
            LEFT JOIN 
                tbl_clubs_and_moderators cm ON cm.club_id = c.club_id
            LEFT JOIN 
                tbl_moderators m ON m.moderator_id = cm.moderator_id
            LEFT JOIN 
                tbl_students f ON f.student_id = c.founder_id
            WHERE 
                c.club_id = :club_id
            GROUP BY 
                c.club_id
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
