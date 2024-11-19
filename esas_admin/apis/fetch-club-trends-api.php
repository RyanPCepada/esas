<?php
// Include the database connection configuration file
require_once "../../config.php";
session_start();

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Get the club ID from the query parameter
$clubId = isset($_GET['club_id']) ? intval($_GET['club_id']) : null;

// Check if club ID is provided
if (!$clubId) {
    header('Content-Type: application/json');
    echo json_encode(["error" => "Club ID is required"]);
    exit;
}

// Prepare the query to fetch data for the specific club
$query = "
    SELECT 
        c.clubName,
        c.slots,
        c.club_id,
        c.dateAdded,
        COUNT(CASE WHEN a.status = 'active' THEN 1 END) AS activeMembers,
        COUNT(CASE WHEN a.status = 'departed' THEN 1 END) AS departedMembers
    FROM 
        tbl_clubs c
    LEFT JOIN 
        tbl_application a 
    ON 
        c.club_id = a.club_id
    WHERE 
        c.club_id = :club_id
    GROUP BY 
        c.club_id
";

try {
    // Create a PDO instance and prepare the query
    $stmt = $pdo->prepare($query);

    // Bind the club ID parameter
    $stmt->bindParam(':club_id', $clubId, PDO::PARAM_INT);
    $stmt->execute();

    // Fetch the club data
    $club = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if the club exists
    if (!$club) {
        header('Content-Type: application/json');
        echo json_encode(["error" => "Club not found"]);
        exit;
    }

    // Calculate trends and other metrics for the specific club
    // (reuse your metrics logic but adapt it for the single club)
    $clubTrends = (function ($club) use ($pdo) {
        // Replace startDate and endDate with null since there's no school year filtering
        $startDate = null;
        $endDate = null;

        // Calculate posts per week
        $postQuery = "
            SELECT COUNT(*) AS postCount
            FROM tbl_posts
            WHERE club_id = :club_id
        ";

        $postStmt = $pdo->prepare($postQuery);
        $postStmt->bindParam(':club_id', $club['club_id'], PDO::PARAM_INT);
        $postStmt->execute();
        $postCount = $postStmt->fetch(PDO::FETCH_ASSOC)['postCount'];

        // Use default date ranges for weeks (if necessary)
        $weeks = 52; // Assume a full year for simplicity
        $club['postPerWeek'] = $weeks > 0 ? number_format($postCount / $weeks, 2) : 0;

        // Calculate events per month
        $eventQuery = "
            SELECT COUNT(*) AS eventCount
            FROM tbl_events
            WHERE club_id = :club_id
        ";

        $eventStmt = $pdo->prepare($eventQuery);
        $eventStmt->bindParam(':club_id', $club['club_id'], PDO::PARAM_INT);
        $eventStmt->execute();
        $eventCount = $eventStmt->fetch(PDO::FETCH_ASSOC)['eventCount'];

        // Approximate months in a year
        $months = 12;
        $club['eventPerMonth'] = $months > 0 ? number_format($eventCount / $months, 2) : 0;

        // Calculate club membership percentage
        if ($club['slots'] == 0) {
            $club['percentage'] = -1;
            $club['percentageText'] = '<span class="text-danger">Unli</span>';
        } else {
            $club['percentage'] = number_format(($club['activeMembers'] / $club['slots']) * 100, 2);
            $club['percentageText'] = $club['percentage'] . '%';
        }

        // Calculate newly active and departed members
        $club['newlyActive'] = $club['activeMembers'];
        $club['newlyDeparted'] = $club['departedMembers'];

        // Calculate a simplified rating for the club
        $club['rating'] = number_format(($club['activeMembers'] + $club['postPerWeek'] + $club['eventPerMonth']) / 3, 2);

        return $club;
    })($club);

    // Return the data as JSON
    header('Content-Type: application/json');
    echo json_encode($clubTrends);

} catch (PDOException $e) {
    // Handle errors
    header('Content-Type: application/json');
    echo json_encode(["error" => "Database query failed: " . $e->getMessage()]);
}

?>
