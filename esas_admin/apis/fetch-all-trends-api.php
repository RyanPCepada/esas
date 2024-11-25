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
    $clubTrends = array_map(function ($club) use ($pdo, $startDate, $endDate, $startYear) {
        // Get the club name (you can adjust based on your database)
        $clubNameQuery = "SELECT clubName FROM tbl_clubs WHERE club_id = :club_id";
        $clubNameStmt = $pdo->prepare($clubNameQuery);
        $clubNameStmt->bindParam(':club_id', $club['club_id'], PDO::PARAM_INT);
        $clubNameStmt->execute();
        $clubNameResult = $clubNameStmt->fetch(PDO::FETCH_ASSOC);
        $club['clubName'] = $clubNameResult['clubName']; // Assign the club name

        // Calculate club membership percentage
        if ($club['slots'] == 0) {
            $club['percentage'] = -1;
            $club['percentageText'] = '<span class="text-danger">Unli</span>';
        } else {
            // Calculate the club percentage based on active members and limit to 2 decimal places
            $club['percentage'] = number_format(($club['activeMembers'] / $club['slots']) * 100, 2);
            $club['percentageText'] = $club['percentage'] . '%';
        }

        // Calculate the rating for this club (same logic as in club_read.php)
        $club_id = $club['club_id'];

        // Number of Applications (filtered by the school year date range)
        $appCountSql = "SELECT COUNT(*) AS appCount FROM tbl_application 
                WHERE club_id = :club_id AND dateDecided <= :endDate";
        $appCountStmt = $pdo->prepare($appCountSql);
        $appCountStmt->bindParam(":club_id", $club_id, PDO::PARAM_INT);
        $appCountStmt->bindParam(":endDate", $endDate, PDO::PARAM_STR);
        $appCountStmt->execute();
        $appCountRow = $appCountStmt->fetch(PDO::FETCH_ASSOC);
        $appCount = $appCountRow['appCount'];

        // Active Members (filtered by the school year date range)
        $activeMembersSql = "SELECT COUNT(*) AS activeMembers FROM tbl_application 
                WHERE club_id = :club_id AND status = 'active' 
                AND dateDecided <= :endDate";
        $activeMembersStmt = $pdo->prepare($activeMembersSql);
        $activeMembersStmt->bindParam(":club_id", $club_id, PDO::PARAM_INT);
        $activeMembersStmt->bindParam(":endDate", $endDate, PDO::PARAM_STR);
        $activeMembersStmt->execute();
        $activeMembersRow = $activeMembersStmt->fetch(PDO::FETCH_ASSOC);
        $activeMembersCount = $activeMembersRow['activeMembers'];

        // Posts per Week (filtered by the school year date range)
        $postsSql = "SELECT COUNT(*) AS totalPosts FROM tbl_posts 
        WHERE club_id = :club_id AND dateAdded <= :endDate";
        $postsStmt = $pdo->prepare($postsSql);
        $postsStmt->bindParam(":club_id", $club_id, PDO::PARAM_INT);
        $postsStmt->bindParam(":endDate", $endDate, PDO::PARAM_STR);
        $postsStmt->execute();
        $postsRow = $postsStmt->fetch(PDO::FETCH_ASSOC);
        $totalPosts = $postsRow['totalPosts'];
        $postsPerWeek = $totalPosts / 4.345; // Assuming 4 weeks per month

        // Events per Month (filtered by the school year date range)
        $eventsSql = "SELECT COUNT(*) AS totalEvents FROM tbl_events 
        WHERE club_id = :club_id AND dateAdded <= :endDate";
        $eventsStmt = $pdo->prepare($eventsSql);
        $eventsStmt->bindParam(":club_id", $club_id, PDO::PARAM_INT);
        $eventsStmt->bindParam(":endDate", $endDate, PDO::PARAM_STR);
        $eventsStmt->execute();
        $eventsRow = $eventsStmt->fetch(PDO::FETCH_ASSOC);
        $totalEvents = $eventsRow['totalEvents'];
        $eventsPerMonth = $totalEvents / 12; // Assuming 12 months per year

        // Accomplishment Reports (filtered by the school year date range)
        $accReportsSql = "SELECT COUNT(*) AS accReportCount FROM tbl_accomplishment_reports 
            WHERE club_id = :club_id AND dateAdded <= :endDate";
        $accReportsStmt = $pdo->prepare($accReportsSql);
        $accReportsStmt->bindParam(":club_id", $club_id, PDO::PARAM_INT);
        $accReportsStmt->bindParam(":endDate", $endDate, PDO::PARAM_STR);
        $accReportsStmt->execute();
        $accReportsRow = $accReportsStmt->fetch(PDO::FETCH_ASSOC);
        $accReportCount = $accReportsRow['accReportCount'];

        // Recommendations (filtered by the school year date range)
        $recSql = "SELECT COUNT(*) AS recCount FROM tbl_club_recommendations 
        WHERE club_id = :club_id AND dateAdded <= :endDate";
        $recStmt = $pdo->prepare($recSql);
        $recStmt->bindParam(":club_id", $club_id, PDO::PARAM_INT);
        $recStmt->bindParam(":endDate", $endDate, PDO::PARAM_STR);
        $recStmt->execute();
        $recRow = $recStmt->fetch(PDO::FETCH_ASSOC);
        $recCount = $recRow['recCount'];

        // Calculate the club rating for this year based on the filtered data
        $ratingThisYear = (
        ($appCount * 0.15) +        // Number of Applications
        ($activeMembersCount * 0.15) + // Active Members
        ($postsPerWeek * 0.15) +       // Posts per Week
        ($eventsPerMonth * 0.20) +     // Events per Month
        ($accReportCount * 0.25) +     // Accomplishment Reports
        ($recCount * 0.10)            // Club Recommendations
        );

        // Ensure rating is within 10
        $ratingThisYear = min(10, round($ratingThisYear / 6, 2)); // Divide by 6 to normalize the formula

        $club['rating'] = $ratingThisYear; // Add the rating to the club data

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
