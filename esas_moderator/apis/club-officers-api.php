<?php
require_once '../../config.php';

// Set the content type to JSON
header('Content-Type: application/json');

// Check if club_id is provided in the URL
if (isset($_GET['club_id'])) {
    $club_id = intval($_GET['club_id']); // Ensure club_id is an integer

    try {
        // Prepare the SQL query to fetch officers whose application status is active
        $sql = "
        SELECT 
            o.officer_id,
            CONCAT(s.firstName, ' ', LEFT(s.middleName, 1), '. ', s.lastName) AS fullName, -- Only the first letter of middle name followed by a period
            s.profilePic,
            CASE 
                WHEN o.president = s.student_id THEN 'President'
                WHEN o.vicePresident = s.student_id THEN 'Vice President'
                WHEN o.secretary = s.student_id THEN 'Secretary'
                WHEN o.treasurer = s.student_id THEN 'Treasurer'
                WHEN o.pio = s.student_id THEN 'P.I.O.'
                WHEN o.srgtAtArms = s.student_id THEN 'Sergeant at Arms'
            END AS position
        FROM 
            tbl_club_officers o
        JOIN 
            tbl_students s ON s.student_id IN (o.president, o.vicePresident, o.secretary, o.treasurer, o.pio, o.srgtAtArms)
        JOIN 
            tbl_application r ON r.student_id = s.student_id AND r.club_id = o.club_id
        WHERE 
            o.club_id = :club_id
            AND r.status = 'active'"; // Only fetch officers with active status

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['club_id' => $club_id]);
        $officers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($officers) {
            echo json_encode(['success' => true, 'officers' => $officers]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No active officers found.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Club ID is required.']);
}

?>