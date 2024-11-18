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

    // Function to convert timestamp to human-readable format
    function timeAgo($timestamp) {
        $timeDifference = time() - strtotime($timestamp);
        $seconds = $timeDifference;
        $minutes      = round($seconds / 60);           // value 60 is seconds
        $hours        = round($seconds / 3600);         // value 3600 is 60 minutes * 60 sec
        $days         = round($seconds / 86400);        // value 86400 is 24 hours * 60 minutes * 60 sec
        $weeks        = round($seconds / 604800);       // value 604800 is 7 days * 24 hours * 60 minutes * 60 sec
        $months       = round($seconds / 2629440);      // value 2629440 is ((365+365+365+365)/4/12) * 24 * 60 * 60
        $years        = round($seconds / 31553280);     // value 31553280 is (365+365+365+365)/4 * 24 * 60 * 60

        if ($seconds <= 60) {
            return "Just Now";
        } else if ($minutes <= 60) {
            if ($minutes == 1) {
                return "1 min ago";
            } else {
                return "$minutes mins ago";
            }
        } else if ($hours <= 24) {
            if ($hours == 1) {
                return "1 hr ago";
            } else {
                return "$hours hrs ago";
            }
        } else if ($days <= 7) {
            if ($days == 1) {
                return "yesterday";
            } else {
                return "$days days ago";
            }
        } else if ($weeks <= 4.3) { // 4.3 == 30/7
            if ($weeks == 1) {
                return "1 week ago";
            } else {
                return "$weeks weeks ago";
            }
        } else if ($months <= 12) {
            if ($months == 1) {
                return "1 month ago";
            } else {
                return "$months months ago";
            }
        } else {
            if ($years == 1) {
                return "1 year ago";
            } else {
                return "$years years ago";
            }
        }
    }

    // Process the fetched clubs data
    $clubTrends = array_map(function ($club) use ($pdo, $startDate, $endDate, $startYear) {
        // Get latest activity date for the club
        $activityQuery = "
            SELECT dateAdded
            FROM tbl_activity_logs
            WHERE club_id = :club_id
            ORDER BY dateAdded DESC
            LIMIT 1
        ";

        $activityStmt = $pdo->prepare($activityQuery);
        $activityStmt->bindParam(':club_id', $club['club_id'], PDO::PARAM_INT);
        $activityStmt->execute();
        $activityResult = $activityStmt->fetch(PDO::FETCH_ASSOC);

        // If there's an activity, calculate the time ago
        if ($activityResult) {
            $club['status'] = timeAgo($activityResult['dateAdded']);
        } else {
            $club['status'] = 'Not active';
        }

        // Calculate posts per week for this school year
        $postQuery = "
            SELECT COUNT(*) AS postCount
            FROM tbl_posts
            WHERE club_id = :club_id
            AND dateAdded BETWEEN :startDate AND :endDate
        ";
    
        $postStmt = $pdo->prepare($postQuery);
        $postStmt->bindParam(':club_id', $club['club_id'], PDO::PARAM_INT);
        $postStmt->bindParam(':startDate', $startDate, PDO::PARAM_STR);
        $postStmt->bindParam(':endDate', $endDate, PDO::PARAM_STR);
        $postStmt->execute();
        $postCount = $postStmt->fetch(PDO::FETCH_ASSOC)['postCount'];
    
        // Calculate the number of weeks in the school year
        $start = new DateTime($startDate);
        $end = new DateTime($endDate);
        $interval = $start->diff($end);
        $weeks = ceil($interval->days / 7);  // Round up to ensure a full week is counted
    
        // Calculate posts per week for this year
        $club['postPerWeek'] = $weeks > 0 ? number_format($postCount / $weeks, 2) : 0;
    
        // Get last school year's data for comparison (same range, previous year)
        $lastYearStartDate = ($startYear - 1) . "-08-01"; // Last year starts on August 1st
        $lastYearEndDate = ($startYear) . "-07-31"; // Last year ends on July 31st
    
        $lastYearPostStmt = $pdo->prepare($postQuery);
        $lastYearPostStmt->bindParam(':club_id', $club['club_id'], PDO::PARAM_INT);
        $lastYearPostStmt->bindParam(':startDate', $lastYearStartDate, PDO::PARAM_STR);
        $lastYearPostStmt->bindParam(':endDate', $lastYearEndDate, PDO::PARAM_STR);
        $lastYearPostStmt->execute();
        $lastYearPostCount = $lastYearPostStmt->fetch(PDO::FETCH_ASSOC)['postCount'];
    
        // Calculate posts per week for last year
        $lastYearStart = new DateTime($lastYearStartDate);
        $lastYearEnd = new DateTime($lastYearEndDate);
        $lastYearInterval = $lastYearStart->diff($lastYearEnd);
        $lastYearWeeks = ceil($lastYearInterval->days / 7);  // Round up weeks
    
        $lastYearPostsPerWeek = $lastYearWeeks > 0 ? $lastYearPostCount / $lastYearWeeks : 0;
    
        // Calculate the difference in posts per week (this year - last year)
        $postDifference = $club['postPerWeek'] - $lastYearPostsPerWeek;
    
        // Add the "+" sign if the difference is positive
        $club['postChanges'] = $postDifference > 0 
            ? '+' . number_format($postDifference, 2)  // Add "+" if positive
            : number_format($postDifference, 2);       // Otherwise, just show the number
    
        // Calculate events per month for this school year
        $eventQuery = "
            SELECT COUNT(*) AS eventCount
            FROM tbl_events
            WHERE club_id = :club_id
            AND dateAdded BETWEEN :startDate AND :endDate
        ";
    
        $eventStmt = $pdo->prepare($eventQuery);
        $eventStmt->bindParam(':club_id', $club['club_id'], PDO::PARAM_INT);
        $eventStmt->bindParam(':startDate', $startDate, PDO::PARAM_STR);
        $eventStmt->bindParam(':endDate', $endDate, PDO::PARAM_STR);
        $eventStmt->execute();
        $eventCount = $eventStmt->fetch(PDO::FETCH_ASSOC)['eventCount'];
    
        // Calculate the number of months in the school year
        $months = ceil($interval->days / 30); // Approximate month count
    
        // Calculate events per month for this year
        $club['eventPerMonth'] = $months > 0 ? number_format($eventCount / $months, 2) : 0;
    
        // Get last school year's data for comparison (same range, previous year)
        $lastYearEventStmt = $pdo->prepare($eventQuery);
        $lastYearEventStmt->bindParam(':club_id', $club['club_id'], PDO::PARAM_INT);
        $lastYearEventStmt->bindParam(':startDate', $lastYearStartDate, PDO::PARAM_STR);
        $lastYearEventStmt->bindParam(':endDate', $lastYearEndDate, PDO::PARAM_STR);
        $lastYearEventStmt->execute();
        $lastYearEventCount = $lastYearEventStmt->fetch(PDO::FETCH_ASSOC)['eventCount'];
    
        // Calculate events per month for last year
        $lastYearStart = new DateTime($lastYearStartDate);
        $lastYearEnd = new DateTime($lastYearEndDate);
        $lastYearInterval = $lastYearStart->diff($lastYearEnd);
        $lastYearMonths = ceil($lastYearInterval->days / 30);  // Approximate month count
    
        $lastYearEventsPerMonth = $lastYearMonths > 0 ? $lastYearEventCount / $lastYearMonths : 0;
    
        // Calculate the difference in events per month (this year - last year)
        $eventDifference = $club['eventPerMonth'] - $lastYearEventsPerMonth;
    
        // Add the "+" sign if the difference is positive
        $club['eventChanges'] = $eventDifference > 0 
            ? '+' . number_format($eventDifference, 2)  // Add "+" if positive
            : number_format($eventDifference, 2);       // Otherwise, just show the number
    
        // Calculate club membership percentage
        if ($club['slots'] == 0) {
            $club['percentage'] = -1;
            $club['percentageText'] = '<span class="text-danger">Unli</span>';
        } else {
            // Calculate the club percentage based on active members and limit to 2 decimal places
            $club['percentage'] = number_format(($club['activeMembers'] / $club['slots']) * 100, 2);
            $club['percentageText'] = $club['percentage'] . '%';
        }
    
        // Add newly active and departed members
        $club['newlyActive'] = $club['activeMembers'];
        $club['newlyDeparted'] = $club['departedMembers'];
    
        // Rating calculation based on club metrics
        $appQuery = "SELECT COUNT(*) AS appCount FROM tbl_application WHERE club_id = :club_id";
        $appStmt = $pdo->prepare($appQuery);
        $appStmt->bindParam(':club_id', $club['club_id'], PDO::PARAM_INT);
        $appStmt->execute();
        $appCount = $appStmt->fetch(PDO::FETCH_ASSOC)['appCount'];
    
        $accReportQuery = "SELECT COUNT(*) AS accReportCount FROM tbl_accomplishment_reports WHERE club_id = :club_id";
        $accReportStmt = $pdo->prepare($accReportQuery);
        $accReportStmt->bindParam(':club_id', $club['club_id'], PDO::PARAM_INT);
        $accReportStmt->execute();
        $accReportCount = $accReportStmt->fetch(PDO::FETCH_ASSOC)['accReportCount'];
    
        $recQuery = "SELECT COUNT(*) AS recCount FROM tbl_club_recommendations WHERE club_id = :club_id";
        $recStmt = $pdo->prepare($recQuery);
        $recStmt->bindParam(':club_id', $club['club_id'], PDO::PARAM_INT);
        $recStmt->execute();
        $recCount = $recStmt->fetch(PDO::FETCH_ASSOC)['recCount'];
    
        // Calculate the total rating based on the weighted formula
        $rating = (
            ($appCount * 0.20) + // Number of Applications
            ($club['activeMembers'] * 0.20) + // Number of Active Members
            ($club['postPerWeek'] * 0.20) + // Posts (adjusted per week)
            ($club['eventPerMonth'] * 0.20) + // Events (adjusted per month)
            ($accReportCount * 0.10) + // Accomplishment Reports
            ($recCount * 0.10) // Club Recommendations
        );
    
        // Round the rating to 2 decimal places
        $club['rating'] = number_format($rating/6, 2);
    
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
