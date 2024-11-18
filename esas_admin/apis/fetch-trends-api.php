<?php
// Include the database connection configuration file
require_once "../../config.php"; 
session_start();

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Get the selected school year from the query parameter
$selectedSchoolYear = isset($_GET['school_year']) ? $_GET['school_year'] : null;

// Prepare the query to fetch club data with cumulative member counts
$query = "
    SELECT 
        c.clubName,
        c.slots,
        c.club_id,
        c.coverPhoto,
        c.dateAdded,  -- When the club was created
        COUNT(CASE WHEN a.status = 'active' AND a.dateDecided <= :endDate THEN 1 END) AS activeMembers,
        COUNT(CASE WHEN a.status = 'departed' AND a.dateDecided <= :endDate THEN 1 END) AS departedMembers
    FROM 
        tbl_clubs c
    LEFT JOIN 
        tbl_application a 
    ON 
        c.club_id = a.club_id
";

// If a school year is selected, add the cumulative date filter
if ($selectedSchoolYear) {
    $yearRange = explode('-', $selectedSchoolYear);
    $startYear = $yearRange[0];

    // Define the start and end date for the school year
    $startDate = $startYear . "-08-01"; // Start from August 1st of the start year
    $endDate = $yearRange[1] . "-07-31"; // End on July 31st of the end year

    $query .= " WHERE c.dateAdded <= :endDate";  // Filter based on club creation date
}

// Continue with the rest of the query
$query .= "
    GROUP BY 
        c.club_id
";

// Execute the query and fetch data
try {
    // Create a PDO instance and prepare the query
    $stmt = $pdo->prepare($query);

    // Bind parameters for start and end date
    if ($selectedSchoolYear) {
        $stmt->bindParam(':endDate', $endDate, PDO::PARAM_STR);
    }

    $stmt->execute();

    // Fetch all results as an associative array
    $clubs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Process the fetched clubs data
    $clubTrends = array_map(function ($club) {
        if ($club['slots'] == 0) {
            $club['percentage'] = -1;
            $club['percentageText'] = '<span class="text-danger">Unli</span>';
        } else {
            // Calculate the club percentage based on active members
            $club['percentage'] = round(($club['activeMembers'] / $club['slots']) * 100, 2);
            $club['percentageText'] = $club['percentage'] . '%';
        }
        
        $club['newlyActive'] = $club['activeMembers'];
        $club['newlyDeparted'] = $club['departedMembers'];
    
        return $club;
    }, $clubs);

    // Sort clubs based on percentage (descending order)
    usort($clubTrends, function ($a, $b) {
        if ($a['percentage'] == -1) return 1;
        if ($b['percentage'] == -1) return -1;
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
