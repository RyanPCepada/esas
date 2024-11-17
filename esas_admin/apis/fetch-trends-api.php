<?php
// Include the database connection configuration file
require_once "../../config.php"; 
session_start();

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Get the selected school year from the query parameter
$selectedSchoolYear = isset($_GET['school_year']) ? $_GET['school_year'] : null;

// Prepare the query to fetch club data with member percentage based on the selected school year
$query = "
    SELECT 
        c.clubName,
        c.slots,
        c.club_id,
        a.dateDecided,
        COUNT(a.student_id) AS activeMembers
    FROM 
        tbl_clubs c
    LEFT JOIN 
        tbl_application a 
    ON 
        c.club_id = a.club_id AND a.status = 'active'";

// If a school year is selected, add the date filter
if ($selectedSchoolYear) {
    $yearRange = explode('-', $selectedSchoolYear);
    $startYear = $yearRange[0];
    $endYear = $yearRange[1];

    // Define the start and end dates for the school year (August to July)
    $startDate = $startYear . '-08-01'; // August 1st of the start year
    $endDate = $endYear . '-07-31'; // July 31st of the end year

    // Modify the query to fetch only records within the school year's date range
    $query .= " AND (a.dateDecided BETWEEN :startDate AND :endDate OR a.student_id IS NULL)";
}

$query .= "
    GROUP BY 
        c.club_id
";

try {
    // Create a PDO instance and prepare the query
    $stmt = $pdo->prepare($query);

    // Bind parameters for the selected school year (if selected)
    if ($selectedSchoolYear) {
        $stmt->bindParam(':startDate', $startDate, PDO::PARAM_STR);
        $stmt->bindParam(':endDate', $endDate, PDO::PARAM_STR);
    }

    $stmt->execute();

    // Fetch all results as an associative array
    $clubs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Add percentage progress to each club
    $clubTrends = array_map(function ($club) {
        $club['percentage'] = ($club['slots'] > 0) ? round(($club['activeMembers'] / $club['slots']) * 100, 2) : 0;
        return $club;
    }, $clubs);

    // Sort the clubs by percentage in descending order
    usort($clubTrends, function ($a, $b) {
        return $b['percentage'] <=> $a['percentage'];
    });

    // Return the data as JSON
    header('Content-Type: application/json');
    echo json_encode($clubTrends);

} catch (PDOException $e) {
    // Handle errors
    header('Content-Type: application/json');
    echo json_encode(["error" => "Database query failed: " . $e->getMessage()]);
}
?>
