<?php
// Include the database connection configuration file
require_once "../../config.php"; 
session_start();

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Prepare the query to fetch club data with member percentage
$query = "
    SELECT 
        c.clubName,
        c.slots,
        c.club_id,
        COUNT(a.student_id) AS activeMembers
    FROM 
        tbl_clubs c
    LEFT JOIN 
        tbl_application a 
    ON 
        c.club_id = a.club_id AND a.status = 'active'
    GROUP BY 
        c.club_id
";

try {
    // Create a PDO instance and prepare the query
    $stmt = $pdo->prepare($query);
    $stmt->execute();

    // Fetch all results as an associative array
    $clubs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Add percentage progress to each club
    $clubTrends = array_map(function ($club) {
        $club['percentage'] = ($club['slots'] > 0) ? round(($club['activeMembers'] / $club['slots']) * 100, 2) : 0;
        return $club;
    }, $clubs);

    // Return the data as JSON
    header('Content-Type: application/json');
    echo json_encode($clubTrends);

} catch (PDOException $e) {
    // Handle errors
    header('Content-Type: application/json');
    echo json_encode(["error" => "Database query failed: " . $e->getMessage()]);
}
