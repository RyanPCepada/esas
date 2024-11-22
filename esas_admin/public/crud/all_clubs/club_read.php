<?php
// Include config file
require_once "../../../../config.php";

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Function to convert timestamp to human-readable format
function timeAgo($timestamp) {
    $timeDifference = time() - strtotime($timestamp);
    $seconds = $timeDifference;
    $minutes = round($seconds / 60);           // value 60 is seconds
    $hours = round($seconds / 3600);           // value 3600 is 60 minutes * 60 sec
    $days = round($seconds / 86400);           // value 86400 is 24 hours * 60 minutes * 60 sec
    $weeks = round($seconds / 604800);         // value 604800 is 7 days * 24 hours * 60 minutes * 60 sec
    $months = round($seconds / 2629440);       // value 2629440 is ((365+365+365+365)/4/12) * 24 * 60 * 60
    $years = round($seconds / 31553280);       // value 31553280 is (365+365+365+365)/4 * 24 * 60 * 60

    if ($seconds < 60) {
        return "Active few seconds ago";
    } elseif ($minutes < 60) {
        return ($minutes == 1) ? "Active 1 min ago" : "Active $minutes mins ago";
    } elseif ($hours < 24) {
        return ($hours == 1) ? "Active 1 hr ago" : "Active $hours hrs ago";
    } elseif ($days < 7) {
        return ($days == 1) ? "Active yesterday" : "Active $days days ago";
    } elseif ($weeks < 4.3) {
        return ($weeks == 1) ? "Active 1 week ago" : "Active $weeks weeks ago";
    } elseif ($months < 12) {
        return ($months == 1) ? "Active 1 month ago" : "Active $months months ago";
    } else {
        return ($years == 1) ? "Active 1 year ago" : "Active $years years ago";
    }
}

// Check if the club_id parameter exists
if (isset($_GET["club_id"]) && !empty(trim($_GET["club_id"]))) {
    // Get the club_id from the query string
    $club_id = trim($_GET["club_id"]);

    // Prepare the SQL query to fetch club details and associated moderators
    $sql = "SELECT 
                c.club_id,
                c.clubName,
                c.description,
                c.mission,
                c.vision,
                c.history,
                c.founder,
                c.dateAdded,
                c.coverPhoto,
                c.slots,
                GROUP_CONCAT(DISTINCT m.moderator_id, ':', m.firstName, ' ', m.middleName, ' ', m.lastName ORDER BY m.lastName ASC SEPARATOR ', ') AS moderatorNames
            FROM tbl_clubs c
            LEFT JOIN tbl_clubs_and_moderators cm ON c.club_id = cm.club_id
            LEFT JOIN tbl_moderators m ON cm.moderator_id = m.moderator_id
            WHERE c.club_id = :club_id
            GROUP BY c.club_id";

    // Prepare the statement
    if ($stmt = $pdo->prepare($sql)) {
        // Bind the parameter
        $stmt->bindParam(":club_id", $club_id, PDO::PARAM_INT);

        // Execute the prepared statement
        if ($stmt->execute()) {
            // Check if the club was found
            if ($stmt->rowCount() == 1) {
                // Fetch the row data
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                // Retrieve the details
                $clubName = htmlspecialchars($row["clubName"]);
                $description = !empty($row["description"]) ? htmlspecialchars($row["description"]) : 'No description available.';
                $mission = !empty($row["mission"]) ? htmlspecialchars($row["mission"]) : 'No mission available.';
                $vision = !empty($row["vision"]) ? htmlspecialchars($row["vision"]) : 'No vision available.';
                $history = !empty($row["history"]) ? htmlspecialchars($row["history"]) : 'No history available.';
                $founder = !empty($row["founder"]) ? htmlspecialchars($row["founder"]) : 'No founder available.';
                $dateAdded = !empty($row["dateAdded"]) ? htmlspecialchars($row["dateAdded"]) : 'None';
                $moderatorNames = !empty($row["moderatorNames"]) ? htmlspecialchars($row["moderatorNames"]) : 'None';
                $coverPhoto = !empty($row["coverPhoto"]) ? htmlspecialchars($row["coverPhoto"]) : "default-cover.jpg";
                $slots = !empty($row["slots"]) ? $row["slots"] : 0; // Default to 0 if no slots available

                // Fetch the count of active students for this club
                $activeSql = "SELECT COUNT(*) AS activeCount FROM tbl_application WHERE club_id = :club_id AND status = 'active'";
                if ($activeStmt = $pdo->prepare($activeSql)) {
                    $activeStmt->bindParam(":club_id", $club_id, PDO::PARAM_INT);
                    if ($activeStmt->execute()) {
                        $activeRow = $activeStmt->fetch(PDO::FETCH_ASSOC);
                        $activeCount = $activeRow['activeCount'];

                        // Calculate the percentage of slots occupied
                        $slotsPercentage = ($slots > 0) ? round(($activeCount / $slots) * 100, 2) : 0;
                    }
                    unset($activeStmt);
                }

                // Determine the club status based on the last activity
                $logSql = "SELECT activity, dateAdded 
                        FROM tbl_activity_logs 
                        WHERE club_id = :club_id 
                        ORDER BY dateAdded DESC LIMIT 1";

                if ($logStmt = $pdo->prepare($logSql)) {
                    $logStmt->bindParam(":club_id", $club_id, PDO::PARAM_INT);
                    if ($logStmt->execute()) {
                        $logRow = $logStmt->fetch(PDO::FETCH_ASSOC);

                        if (!empty($logRow)) {
                            $lastActivity = $logRow["activity"];
                            $lastActivityDate = $logRow["dateAdded"];

                            if ($lastActivity === "You logged out of your account") {
                                // Use timeAgo for logout activity
                                $clubStatus = timeAgo($lastActivityDate);
                            } elseif (!empty($lastActivity) && $lastActivity !== "You logged out of your account") {
                                // If there's an activity and it's not logout, show "Active now"
                                $clubStatus = "Active now";
                            } elseif (empty($lastActivity)) {
                                // If no activity is found, set as inactive
                                $clubStatus = "Inactive";
                            }
                        } else {
                            // No activity found in the logs
                            $clubStatus = "Inactive";
                        }
                    } else {
                        // Handle execution failure
                        $clubStatus = "Error fetching activity logs";
                    }
                    unset($logStmt);
                } else {
                    // Handle statement preparation failure
                    $clubStatus = "Error preparing statement";
                }

                // Determine if the label should be "Moderator" or "Moderators"
                $moderatorCount = substr_count($moderatorNames, ',') + 1; // Count commas and add 1 for total moderators
                $moderatorLabel = ($moderatorCount > 1) ? "Moderators:" : "Moderator:";

            } else {
                // Redirect if no record is found
                header("location: error.php");
                exit();
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
    }

    // Close statement
    unset($stmt);
} else {
    // Redirect if the club_id parameter is missing
    header("location: error.php");
    exit();
}


// MEMBERS

// Get the current year and next year
$currentYear = date('Y');
$nextYear = $currentYear + 1;
$previousYear = $currentYear - 1;

// Define the start and end dates of the current school year (Aug to July)
$startDate = "$currentYear-08-01";
$endDate = "$nextYear-07-31";

// Define the start and end dates of the previous school year (Aug to July)
$prevStartDate = "$previousYear-08-01";
$prevEndDate = "$currentYear-07-31";

// SQL for new members this year
$newMembersSql = "SELECT COUNT(*) AS newMembers FROM tbl_application 
                  WHERE club_id = :club_id AND status = 'active' 
                  AND dateDecided BETWEEN :startDate AND :endDate";
$newMembersStmt = $pdo->prepare($newMembersSql);
$newMembersStmt->bindParam(":club_id", $club_id, PDO::PARAM_INT);
$newMembersStmt->bindParam(":startDate", $startDate, PDO::PARAM_STR);
$newMembersStmt->bindParam(":endDate", $endDate, PDO::PARAM_STR);
$newMembersStmt->execute();
$newMembersRow = $newMembersStmt->fetch(PDO::FETCH_ASSOC);
$newMembersCount = $newMembersRow['newMembers'];

// SQL for departed members this year
$departedMembersSql = "SELECT COUNT(*) AS departedMembers FROM tbl_application 
                       WHERE club_id = :club_id AND status = 'departed' 
                       AND dateDecided BETWEEN :startDate AND :endDate";
$departedMembersStmt = $pdo->prepare($departedMembersSql);
$departedMembersStmt->bindParam(":club_id", $club_id, PDO::PARAM_INT);
$departedMembersStmt->bindParam(":startDate", $startDate, PDO::PARAM_STR);
$departedMembersStmt->bindParam(":endDate", $endDate, PDO::PARAM_STR);
$departedMembersStmt->execute();
$departedMembersRow = $departedMembersStmt->fetch(PDO::FETCH_ASSOC);
$departedMembersCount = $departedMembersRow['departedMembers'];

// SQL for total active members overall
$totalMembersOverallCountSql = "SELECT COUNT(*) AS totalMembers FROM tbl_application 
                            WHERE club_id = :club_id AND status = 'active'";
$totalMembersOverallCountStmt = $pdo->prepare($totalMembersOverallCountSql);
$totalMembersOverallCountStmt->bindParam(":club_id", $club_id, PDO::PARAM_INT);
$totalMembersOverallCountStmt->execute();
$totalMembersOverallCountRow = $totalMembersOverallCountStmt->fetch(PDO::FETCH_ASSOC);
$totalMembersOverallCount = $totalMembersOverallCountRow['totalMembers'];

// SQL for total active members this year
$totalMembersThisYearCountSql = "SELECT COUNT(*) AS totalMembers FROM tbl_application 
                            WHERE club_id = :club_id AND status = 'active' 
                            AND dateDecided BETWEEN :startDate AND :endDate";
$totalMembersThisYearCountStmt = $pdo->prepare($totalMembersThisYearCountSql);
$totalMembersThisYearCountStmt->bindParam(":club_id", $club_id, PDO::PARAM_INT);
$totalMembersThisYearCountStmt->bindParam(":startDate", $startDate, PDO::PARAM_STR);
$totalMembersThisYearCountStmt->bindParam(":endDate", $endDate, PDO::PARAM_STR);
$totalMembersThisYearCountStmt->execute();
$totalMembersThisYearCountRow = $totalMembersThisYearCountStmt->fetch(PDO::FETCH_ASSOC);
$totalMembersThisYearCount = $totalMembersThisYearCountRow['totalMembers'];

// SQL for total active members last year
$totalMembersLastYearSql = "SELECT COUNT(*) AS totalMembers FROM tbl_application 
                            WHERE club_id = :club_id AND status = 'active' 
                            AND dateDecided BETWEEN :prevStartDate AND :prevEndDate";
$totalMembersLastYearStmt = $pdo->prepare($totalMembersLastYearSql);
$totalMembersLastYearStmt->bindParam(":club_id", $club_id, PDO::PARAM_INT);
$totalMembersLastYearStmt->bindParam(":prevStartDate", $prevStartDate, PDO::PARAM_STR);
$totalMembersLastYearStmt->bindParam(":prevEndDate", $prevEndDate, PDO::PARAM_STR);
$totalMembersLastYearStmt->execute();
$totalMembersLastYearRow = $totalMembersLastYearStmt->fetch(PDO::FETCH_ASSOC);
$totalMembersLastYear = $totalMembersLastYearRow['totalMembers'];

// Calculate the difference in total members
$membersChanges = $totalMembersThisYearCount - $totalMembersLastYear - $departedMembersCount;

// Close statements
unset($newMembersStmt, $departedMembersStmt, $totalMembersOverallCountStmt, $totalMembersThisYearCountStmt, $totalMembersLastYearStmt);

//POSTS

// Define the start and end dates for the current school year
$startDate = "$currentYear-08-01";
$endDate = "$nextYear-07-31";
$prevStartDate = "$previousYear-08-01";
$prevEndDate = "$currentYear-07-31";

// SQL for posts created this week
$thisWeekStartDate = date('Y-m-d', strtotime('monday this week'));
$thisWeekEndDate = date('Y-m-d', strtotime('sunday this week'));
$newPostsSql = "SELECT COUNT(*) AS newPosts FROM tbl_posts 
                WHERE club_id = :club_id 
                AND dateAdded BETWEEN :thisWeekStartDate AND :thisWeekEndDate";
$newPostsStmt = $pdo->prepare($newPostsSql);
$newPostsStmt->bindParam(":club_id", $club_id, PDO::PARAM_INT);
$newPostsStmt->bindParam(":thisWeekStartDate", $thisWeekStartDate, PDO::PARAM_STR);
$newPostsStmt->bindParam(":thisWeekEndDate", $thisWeekEndDate, PDO::PARAM_STR);
$newPostsStmt->execute();
$newPostsRow = $newPostsStmt->fetch(PDO::FETCH_ASSOC);
$newPostsCount = $newPostsRow['newPosts'];

// SQL for average posts per week for this school year
$averagePostsSql = "SELECT COUNT(*) / TIMESTAMPDIFF(WEEK, :startDate, :endDate) AS averagePosts 
                    FROM tbl_posts 
                    WHERE club_id = :club_id 
                    AND dateAdded BETWEEN :startDate AND :endDate";
$averagePostsStmt = $pdo->prepare($averagePostsSql);
$averagePostsStmt->bindParam(":club_id", $club_id, PDO::PARAM_INT);
$averagePostsStmt->bindParam(":startDate", $startDate, PDO::PARAM_STR);
$averagePostsStmt->bindParam(":endDate", $endDate, PDO::PARAM_STR);
$averagePostsStmt->execute();
$averagePostsRow = $averagePostsStmt->fetch(PDO::FETCH_ASSOC);
$postAveragePerWeek = round($averagePostsRow['averagePosts'], 2);
$averagePostsLastYearSql = "SELECT COUNT(*) / TIMESTAMPDIFF(WEEK, :prevStartDate, :prevEndDate) AS averagePosts 
                            FROM tbl_posts 
                            WHERE club_id = :club_id 
                            AND dateAdded BETWEEN :prevStartDate AND :prevEndDate";
$averagePostsLastYearStmt = $pdo->prepare($averagePostsLastYearSql);
$averagePostsLastYearStmt->bindParam(":club_id", $club_id, PDO::PARAM_INT);
$averagePostsLastYearStmt->bindParam(":prevStartDate", $prevStartDate, PDO::PARAM_STR);
$averagePostsLastYearStmt->bindParam(":prevEndDate", $prevEndDate, PDO::PARAM_STR);
$averagePostsLastYearStmt->execute();
$averagePostsLastYearRow = $averagePostsLastYearStmt->fetch(PDO::FETCH_ASSOC);
$averagePostsLastYear = round($averagePostsLastYearRow['averagePosts'], 2);

// SQL for total posts last year
$totalPostsLastYearSql = "SELECT COUNT(*) AS totalPosts FROM tbl_posts 
                          WHERE club_id = :club_id 
                          AND dateAdded BETWEEN :prevStartDate AND :prevEndDate";
$totalPostsLastYearStmt = $pdo->prepare($totalPostsLastYearSql);
$totalPostsLastYearStmt->bindParam(":club_id", $club_id, PDO::PARAM_INT);
$totalPostsLastYearStmt->bindParam(":prevStartDate", $prevStartDate, PDO::PARAM_STR);
$totalPostsLastYearStmt->bindParam(":prevEndDate", $prevEndDate, PDO::PARAM_STR);
$totalPostsLastYearStmt->execute();
$totalPostsLastYearRow = $totalPostsLastYearStmt->fetch(PDO::FETCH_ASSOC);
$totalPostsLastYear = $totalPostsLastYearRow['totalPosts'];

// SQL for total posts this year
$totalPostsThisYearCountSql = "SELECT COUNT(*) AS totalPosts FROM tbl_posts 
                          WHERE club_id = :club_id 
                          AND dateAdded BETWEEN :startDate AND :endDate";
$totalPostsThisYearCountStmt = $pdo->prepare($totalPostsThisYearCountSql);
$totalPostsThisYearCountStmt->bindParam(":club_id", $club_id, PDO::PARAM_INT);
$totalPostsThisYearCountStmt->bindParam(":startDate", $startDate, PDO::PARAM_STR);
$totalPostsThisYearCountStmt->bindParam(":endDate", $endDate, PDO::PARAM_STR);
$totalPostsThisYearCountStmt->execute();
$totalPostsThisYearCountRow = $totalPostsThisYearCountStmt->fetch(PDO::FETCH_ASSOC);
$totalPostsThisYearCount = $totalPostsThisYearCountRow['totalPosts'];

// Calculate the change in posts compared to last school year
$postsChanges = $postAveragePerWeek - $averagePostsLastYear;

// Close statements
unset($newPostsStmt, $averagePostsStmt, $totalPostsLastYearStmt, $totalPostsThisYearCountStmt);

// EVENTS

// Determine current school year range
$currentMonth = date('n'); // Numeric representation of the current month (1 to 12)
$currentYear = date('Y'); // Current year

if ($currentMonth >= 8) {
    // School year starts in August of this year and ends in July of the next year
    $schoolYearStartDate = "$currentYear-08-01";
    $schoolYearEndDate = ($currentYear + 1) . "-07-31";
} else {
    // School year starts in August of the previous year and ends in July of this year
    $schoolYearStartDate = ($currentYear - 1) . "-08-01";
    $schoolYearEndDate = "$currentYear-07-31";
}

// SQL for total events this school year
$totalEventsSql = "SELECT COUNT(*) AS totalEventsThisYear 
                   FROM tbl_events 
                   WHERE club_id = :club_id 
                   AND dateAdded BETWEEN :schoolYearStartDate AND :schoolYearEndDate";
$totalEventsStmt = $pdo->prepare($totalEventsSql);
$totalEventsStmt->bindParam(":club_id", $club_id, PDO::PARAM_INT);
$totalEventsStmt->bindParam(":schoolYearStartDate", $schoolYearStartDate, PDO::PARAM_STR);
$totalEventsStmt->bindParam(":schoolYearEndDate", $schoolYearEndDate, PDO::PARAM_STR);
$totalEventsStmt->execute();
$totalEventsRow = $totalEventsStmt->fetch(PDO::FETCH_ASSOC);
$totalEventsThisYearCount = $totalEventsRow['totalEventsThisYear'];

// Close statement
unset($totalEventsStmt);

// SQL for events created this month
$thisMonthStartDate = date('Y-m-01'); // First day of the current month
$thisMonthEndDate = date('Y-m-t');   // Last day of the current month
$newEventsSql = "SELECT COUNT(*) AS newEvents FROM tbl_events 
                 WHERE club_id = :club_id 
                 AND dateAdded BETWEEN :thisMonthStartDate AND :thisMonthEndDate";
$newEventsStmt = $pdo->prepare($newEventsSql);
$newEventsStmt->bindParam(":club_id", $club_id, PDO::PARAM_INT);
$newEventsStmt->bindParam(":thisMonthStartDate", $thisMonthStartDate, PDO::PARAM_STR);
$newEventsStmt->bindParam(":thisMonthEndDate", $thisMonthEndDate, PDO::PARAM_STR);
$newEventsStmt->execute();
$newEventsRow = $newEventsStmt->fetch(PDO::FETCH_ASSOC);
$newEventsCount = $newEventsRow['newEvents'];

// SQL for average events per month for this school year
$averageEventsSql = "SELECT COUNT(*) / TIMESTAMPDIFF(MONTH, :startDate, :endDate) AS averageEvents 
                     FROM tbl_events 
                     WHERE club_id = :club_id 
                     AND dateAdded BETWEEN :startDate AND :endDate";
$averageEventsStmt = $pdo->prepare($averageEventsSql);
$averageEventsStmt->bindParam(":club_id", $club_id, PDO::PARAM_INT);
$averageEventsStmt->bindParam(":startDate", $startDate, PDO::PARAM_STR);
$averageEventsStmt->bindParam(":endDate", $endDate, PDO::PARAM_STR);
$averageEventsStmt->execute();
$averageEventsRow = $averageEventsStmt->fetch(PDO::FETCH_ASSOC);
$eventsAveragePerMonth = round($averageEventsRow['averageEvents'], 2);

// SQL for average events per month for the previous school year
$averageEventsLastYearSql = "SELECT COUNT(*) / TIMESTAMPDIFF(MONTH, :prevStartDate, :prevEndDate) AS averageEvents 
                             FROM tbl_events 
                             WHERE club_id = :club_id 
                             AND dateAdded BETWEEN :prevStartDate AND :prevEndDate";
$averageEventsLastYearStmt = $pdo->prepare($averageEventsLastYearSql);
$averageEventsLastYearStmt->bindParam(":club_id", $club_id, PDO::PARAM_INT);
$averageEventsLastYearStmt->bindParam(":prevStartDate", $prevStartDate, PDO::PARAM_STR);
$averageEventsLastYearStmt->bindParam(":prevEndDate", $prevEndDate, PDO::PARAM_STR);
$averageEventsLastYearStmt->execute();
$averageEventsLastYearRow = $averageEventsLastYearStmt->fetch(PDO::FETCH_ASSOC);
$eventsAverageLastYear = round($averageEventsLastYearRow['averageEvents'], 2);

// Calculate the change in events compared to last school year
$eventsChanges = $eventsAveragePerMonth - $eventsAverageLastYear;

// Close statements
unset($newEventsStmt, $averageEventsStmt, $averageEventsLastYearStmt);


// RATING

// Fetch active members count for this year
$activeMembersSql = "SELECT COUNT(*) AS activeMembers FROM tbl_application 
                     WHERE club_id = :club_id AND status = 'active' 
                     AND YEAR(dateDecided) = YEAR(CURRENT_DATE)";
$activeMembersStmt = $pdo->prepare($activeMembersSql);
$activeMembersStmt->bindParam(":club_id", $club_id, PDO::PARAM_INT);
$activeMembersStmt->execute();
$activeMembersRow = $activeMembersStmt->fetch(PDO::FETCH_ASSOC);
$activeMembersCount = $activeMembersRow['activeMembers'];

// Fetch number of applications for this year
$appCountSql = "SELECT COUNT(*) AS appCount FROM tbl_application 
                WHERE club_id = :club_id AND YEAR(dateDecided) = YEAR(CURRENT_DATE)";
$appCountStmt = $pdo->prepare($appCountSql);
$appCountStmt->bindParam(":club_id", $club_id, PDO::PARAM_INT);
$appCountStmt->execute();
$appCountRow = $appCountStmt->fetch(PDO::FETCH_ASSOC);
$appCount = $appCountRow['appCount'];

// Fetch accomplishment reports count for this year
$accReportsSql = "SELECT COUNT(*) AS accReportCount FROM tbl_accomplishment_reports 
                  WHERE club_id = :club_id AND YEAR(dateAdded) = YEAR(CURRENT_DATE)";
$accReportsStmt = $pdo->prepare($accReportsSql);
$accReportsStmt->bindParam(":club_id", $club_id, PDO::PARAM_INT);
$accReportsStmt->execute();
$accReportsRow = $accReportsStmt->fetch(PDO::FETCH_ASSOC);
$accReportCount = $accReportsRow['accReportCount'];

// Fetch recommendations count for this year
$recSql = "SELECT COUNT(*) AS recCount FROM tbl_club_recommendations 
           WHERE club_id = :club_id AND YEAR(dateAdded) = YEAR(CURRENT_DATE)";
$recStmt = $pdo->prepare($recSql);
$recStmt->bindParam(":club_id", $club_id, PDO::PARAM_INT);
$recStmt->execute();
$recRow = $recStmt->fetch(PDO::FETCH_ASSOC);
$recCount = $recRow['recCount'];

// Fetch posts per week for this year
$postsSql = "SELECT COUNT(*) AS totalPosts FROM tbl_posts 
             WHERE club_id = :club_id AND YEAR(dateAdded) = YEAR(CURRENT_DATE)";
$postsStmt = $pdo->prepare($postsSql);
$postsStmt->bindParam(":club_id", $club_id, PDO::PARAM_INT);
$postsStmt->execute();
$postsRow = $postsStmt->fetch(PDO::FETCH_ASSOC);
$totalPosts = $postsRow['totalPosts'];
$postsPerWeek = $totalPosts / 4; // Assuming 4 weeks per month

// Fetch events per month for this year
$eventsSql = "SELECT COUNT(*) AS totalEvents FROM tbl_events 
              WHERE club_id = :club_id AND YEAR(dateAdded) = YEAR(CURRENT_DATE)";
$eventsStmt = $pdo->prepare($eventsSql);
$eventsStmt->bindParam(":club_id", $club_id, PDO::PARAM_INT);
$eventsStmt->execute();
$eventsRow = $eventsStmt->fetch(PDO::FETCH_ASSOC);
$totalEvents = $eventsRow['totalEvents'];
$eventsPerMonth = $totalEvents / 12; // Assuming 12 months per year

// Calculate the rating for this year
$ratingThisYear = (
    ($appCount * 0.20) +        // Number of Applications
    ($activeMembersCount * 0.20) + // Active Members
    ($postsPerWeek * 0.20) +       // Posts per Week
    ($eventsPerMonth * 0.20) +     // Events per Month
    ($accReportCount * 0.10) +     // Accomplishment Reports
    ($recCount * 0.10)            // Club Recommendations
);

// Ensure rating is within 10
$ratingThisYear = min(10, round($ratingThisYear / 6, 2)); // Divide by 6 to normalize the formula

// Calculate the rating for all years (total accumulated values)
$activeMembersSqlAllYears = "SELECT COUNT(*) AS activeMembers FROM tbl_application 
                             WHERE club_id = :club_id AND status = 'active'";
$activeMembersStmtAllYears = $pdo->prepare($activeMembersSqlAllYears);
$activeMembersStmtAllYears->bindParam(":club_id", $club_id, PDO::PARAM_INT);
$activeMembersStmtAllYears->execute();
$activeMembersRowAllYears = $activeMembersStmtAllYears->fetch(PDO::FETCH_ASSOC);
$activeMembersCountAllYears = $activeMembersRowAllYears['activeMembers'];

// Fetch number of applications for all years
$appCountSqlAllYears = "SELECT COUNT(*) AS appCount FROM tbl_application 
                        WHERE club_id = :club_id";
$appCountStmtAllYears = $pdo->prepare($appCountSqlAllYears);
$appCountStmtAllYears->bindParam(":club_id", $club_id, PDO::PARAM_INT);
$appCountStmtAllYears->execute();
$appCountRowAllYears = $appCountStmtAllYears->fetch(PDO::FETCH_ASSOC);
$appCountAllYears = $appCountRowAllYears['appCount'];

// Fetch accomplishment reports count for all years
$accReportsSqlAllYears = "SELECT COUNT(*) AS accReportCount FROM tbl_accomplishment_reports 
                           WHERE club_id = :club_id";
$accReportsStmtAllYears = $pdo->prepare($accReportsSqlAllYears);
$accReportsStmtAllYears->bindParam(":club_id", $club_id, PDO::PARAM_INT);
$accReportsStmtAllYears->execute();
$accReportsRowAllYears = $accReportsStmtAllYears->fetch(PDO::FETCH_ASSOC);
$accReportCountAllYears = $accReportsRowAllYears['accReportCount'];

// Fetch recommendations count for all years
$recSqlAllYears = "SELECT COUNT(*) AS recCount FROM tbl_club_recommendations 
                   WHERE club_id = :club_id";
$recStmtAllYears = $pdo->prepare($recSqlAllYears);
$recStmtAllYears->bindParam(":club_id", $club_id, PDO::PARAM_INT);
$recStmtAllYears->execute();
$recRowAllYears = $recStmtAllYears->fetch(PDO::FETCH_ASSOC);
$recCountAllYears = $recRowAllYears['recCount'];

// Fetch total posts for all years
$postsSqlAllYears = "SELECT COUNT(*) AS totalPosts FROM tbl_posts 
                     WHERE club_id = :club_id";
$postsStmtAllYears = $pdo->prepare($postsSqlAllYears);
$postsStmtAllYears->bindParam(":club_id", $club_id, PDO::PARAM_INT);
$postsStmtAllYears->execute();
$postsRowAllYears = $postsStmtAllYears->fetch(PDO::FETCH_ASSOC);
$totalPostsAllYears = $postsRowAllYears['totalPosts'];
$postsPerWeekAllYears = $totalPostsAllYears / 4; // Assuming 4 weeks per month

// Fetch total events for all years
$eventsSqlAllYears = "SELECT COUNT(*) AS totalEvents FROM tbl_events 
                      WHERE club_id = :club_id";
$eventsStmtAllYears = $pdo->prepare($eventsSqlAllYears);
$eventsStmtAllYears->bindParam(":club_id", $club_id, PDO::PARAM_INT);
$eventsStmtAllYears->execute();
$eventsRowAllYears = $eventsStmtAllYears->fetch(PDO::FETCH_ASSOC);
$totalEventsAllYears = $eventsRowAllYears['totalEvents'];
$eventsPerMonthAllYears = $totalEventsAllYears / 12; // Assuming 12 months per year

// Calculate the rating for all school years
$ratingAllYears = (
    ($appCountAllYears * 0.20) +        // Number of Applications
    ($activeMembersCountAllYears * 0.20) + // Active Members
    ($postsPerWeekAllYears * 0.20) +       // Posts per Week
    ($eventsPerMonthAllYears * 0.20) +     // Events per Month
    ($accReportCountAllYears * 0.10) +     // Accomplishment Reports
    ($recCountAllYears * 0.10)            // Club Recommendations
);

// Ensure rating is within 10
$ratingAllYears = min(10, round($ratingAllYears / 6, 2)); // Divide by 6 to normalize the formula

// Output or use the ratings
// echo "Rating: " . $ratingAllYears . " (All School Years) <br>";
// echo "Rating This Year: " . $ratingThisYear . " (This Year)";




// AWARDS RANKS

// Helper function to determine the correct suffix for a rank
function getRankWithSuffix($rank) {
    if (!in_array(($rank % 100), [11, 12, 13])) { // Handle special cases for 11th, 12th, 13th
        switch ($rank % 10) {
            case 1: return $rank . "st";
            case 2: return $rank . "nd";
            case 3: return $rank . "rd";
        }
    }
    return $rank . "th";
}

// Get the club_id from the query string
$club_id = trim($_GET["club_id"]);

// Initialize rank variables
$mostAppliedClubRank = "";
$highestInMembersRank = "";
$mostActiveClubRank = "";
$fastestGrowingClubRank = "";

// Rank for Most Active Club
try {
    $stmt = $pdo->prepare("
        SELECT c.club_id, c.clubName, COUNT(a.activity_id) AS activity_count
        FROM tbl_activity_logs a
        INNER JOIN tbl_clubs c ON a.club_id = c.club_id
        WHERE a.club_id IS NOT NULL
        GROUP BY c.club_id, c.clubName
        ORDER BY activity_count DESC
    ");
    $stmt->execute();
    $clubs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $rank = 1;
    foreach ($clubs as $club) {
        if ($club['club_id'] == $club_id) {
            $mostActiveClubRank = getRankWithSuffix($rank);
            break;
        }
        $rank++;
    }
} catch (PDOException $e) {
    echo "Error fetching most active club rank: " . $e->getMessage();
}
// Default rank value if no rank is fetched
$mostActiveClubRank = $mostActiveClubRank ?: "<small>Unqualified</small>";


// Rank for Most Applied Club
try {
    $stmt = $pdo->prepare("
        SELECT tbl_clubs.club_id, tbl_clubs.clubName, COUNT(tbl_application.application_id) AS application_count 
        FROM tbl_application 
        INNER JOIN tbl_clubs ON tbl_clubs.club_id = tbl_application.club_id
        GROUP BY tbl_clubs.club_id 
        ORDER BY application_count DESC
    ");
    $stmt->execute();
    $clubs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $rank = 1;
    foreach ($clubs as $club) {
        if ($club['club_id'] == $club_id) {
            $mostAppliedClubRank = getRankWithSuffix($rank);
            break;
        }
        $rank++;
    }
} catch (PDOException $e) {
    echo "Error fetching most applied club rank: " . $e->getMessage();
}
$mostAppliedClubRank = $mostAppliedClubRank ?: "<small>Unqualified</small>";


// Rank for Highest in Members
try {
    $stmt = $pdo->prepare("
        SELECT tbl_clubs.clubName, tbl_clubs.club_id, COUNT(tbl_application.application_id) AS active_member_count 
            FROM tbl_application 
            INNER JOIN tbl_clubs ON tbl_clubs.club_id = tbl_application.club_id
            WHERE tbl_application.status = 'active' 
            GROUP BY tbl_clubs.club_id 
            ORDER BY active_member_count DESC
    ");
    $stmt->execute();
    $clubs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $rank = 1;
    foreach ($clubs as $club) {
        if ($club['club_id'] == $club_id) {
            $highestInMembersRank = getRankWithSuffix($rank);
            break;
        }
        $rank++;
    }
} catch (PDOException $e) {
    echo "Error fetching highest members rank: " . $e->getMessage();
}
// Default rank value if no rank is fetched
$highestInMembersRank = $highestInMembersRank ?: "<small>Unqualified</small>";


// Rank for Fastest Growing Club considering members, posts, and events growth
try {
    $stmt = $pdo->prepare("
        SELECT 
            club_id, 
            -- Current year's active members
            (SELECT COUNT(application_id) 
                FROM tbl_application 
                WHERE club_id = tbl_clubs.club_id 
                AND status = 'active' 
                AND YEAR(dateApplied) = YEAR(CURDATE())) AS current_year_members,
            
            -- Previous year's active members
            (SELECT COUNT(application_id) 
                FROM tbl_application 
                WHERE club_id = tbl_clubs.club_id 
                AND status = 'active' 
                AND YEAR(dateApplied) = YEAR(CURDATE()) - 1) AS previous_year_members,

            -- Current year's posts
            (SELECT COUNT(post_id) 
                FROM tbl_posts 
                WHERE club_id = tbl_clubs.club_id 
                AND YEAR(dateAdded) = YEAR(CURDATE())) AS current_year_posts,

            -- Previous year's posts
            (SELECT COUNT(post_id) 
                FROM tbl_posts 
                WHERE club_id = tbl_clubs.club_id 
                AND YEAR(dateAdded) = YEAR(CURDATE()) - 1) AS previous_year_posts,

            -- Current year's events
            (SELECT COUNT(event_id) 
                FROM tbl_events 
                WHERE club_id = tbl_clubs.club_id 
                AND YEAR(dateAdded) = YEAR(CURDATE())) AS current_year_events,

            -- Previous year's events
            (SELECT COUNT(event_id) 
                FROM tbl_events 
                WHERE club_id = tbl_clubs.club_id 
                AND YEAR(dateAdded) = YEAR(CURDATE()) - 1) AS previous_year_events
        FROM tbl_clubs
        ORDER BY 
            -- Prioritize based on the overall growth (in any category)
            GREATEST(current_year_members - previous_year_members, current_year_posts - previous_year_posts, current_year_events - previous_year_events) DESC
    ");
    $stmt->execute();
    $clubs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $rank = 1;
    foreach ($clubs as $club) {
        if ($club['club_id'] == $club_id) {
            $fastestGrowingClubRank = getRankWithSuffix($rank);
            break;
        }
        $rank++;
    }
} catch (PDOException $e) {
    echo "Error fetching fastest growing club rank: " . $e->getMessage();
}
// Default rank value if no rank is fetched
$fastestGrowingClubRank = $fastestGrowingClubRank ?: "<small>Unqualified</small>";




// Close connection
unset($pdo);

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ESAS - Club Details</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link href="../../../assets/css/jquery.dataTables.min.css" rel="stylesheet" />
    <script src="../../../assets/js/all.js" crossorigin="anonymous"></script>
    <script src="../../../assets/js/jquery-3.6.0.js"></script>
    <link href="../../../assets/css/styles.css" rel="stylesheet" />
    <link href="../../../assets/img/NBSC_LOGO.png" rel="icon">
</head>
    <style>
    
        .col-md-7 {
            text-align: justify;
            text-indent: 30px;
        }
    </style>
<body>
    <div class="container">
        <div class="row mt-5">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3>Club Profile</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 text-start">
                                <img src="/esas/esas_admin/images/<?php echo $coverPhoto; ?>" 
                                     alt="<?php echo $clubName; ?> Cover Photo" 
                                     class="img-fluid" style="width: 300px; height: auto; border-radius: 5px; object-fit: cover;">
                                     <!-- class="img-fluid" style="width: 300px; height: 171px; border-radius: 5px; object-fit: cover;"> -->
                                <div>
                                    <p class="m-0 mt-3 p-0"><strong>Date Created:<br></strong><?php echo date("F j, Y", strtotime($dateAdded)); ?></p>
                                    <p class="m-0 mt-0 p-0"><strong>Founder:<br></strong><?php echo $founder; ?></p>
                                </div>
                            </div>
                            <div class="row col-md-8">
                                <div class="col-md-9">
                                    <h3 class="text-muted mb-0"><?php echo $clubName; ?></h3>
                                    <span class="status-dot" style="position: absolute; top: 18px; left: 16px; color: red; font-size: 2em;">&#8226;</span><small class="ml-3"> <?php echo $clubStatus; ?></small>

                                    <!-- <hr> -->
                                    <div class="row col-md-12 m-0 mt-2 p-0">
                                        <!-- MEMBERS CARD START -->
                                        <div class="col-md-4 p-1">
                                            <div class="card card-members p-2">
                                                <p class="m-0 p-0"><i class="fas fa-user text-info"></i><strong> Members</strong></p>

                                                <!-- <h5 class="text-dark m-0 p-0"><strong><?php echo $totalMembersThisYearCount; ?></strong></h5> -->
                                                <h5 class="text-dark m-0 p-0"><strong><?php echo $totalMembersOverallCount; ?></strong></h5>
                                                <p class="text-dark mb-1 p-0" style="margin-top: -6px !important;">members this year</p>

                                                <h5 class="text-dark m-0 p-0"><strong><?php echo $newMembersCount; ?></strong></h5>
                                                <p class="text-dark mb-1 p-0" style="margin-top: -6px !important;">new members</p>

                                                <h5 class="text-dark m-0 p-0"><strong><?php echo $departedMembersCount; ?></strong></h5>
                                                <p class="text-dark mb-1 p-0" style="margin-top: -6px !important;"><?php echo $departedMembersCount == 1 ? 'departure' : 'departures'; ?></p>

                                                <div class="member-changes text-light text-center d-flex align-items-center justify-content-center bg-info" 
                                                    style="position: absolute; width: 30px; height: 30px; border-radius: 50%; right: 5px;">
                                                    <?php if ($membersChanges > 0): ?>
                                                        <i class="fas fa-arrow-up text-light" title="Performance Increasing" style="cursor: pointer;"></i>
                                                    <?php elseif ($membersChanges < 0): ?>
                                                        <i class="fas fa-arrow-down text-light" title="Performance Decreasing" style="cursor: pointer;"></i>
                                                    <?php else: ?>
                                                        <h4 style="cursor: pointer;" title="Performance Unchanging">-</h4>
                                                    <?php endif; ?>
                                                </div>

                                                <?php if ($membersChanges > 0): ?>
                                                    <small class="text-muted m-0 p-0"><small>+</small><?php echo $membersChanges; ?> Compared to previous S.Y.</small>
                                                <?php elseif ($membersChanges < 0): ?>
                                                    <small class="text-muted m-0 p-0"><?php echo $membersChanges; ?> Compared to previous S.Y.</small>
                                                <?php else: ?>
                                                    <small class="text-muted m-0 p-0">+-0 Compared to previous S.Y.</small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <!-- MEMBERS CARD END -->
                                        <!-- POSTS CARD START -->
                                        <div class="col-md-4 p-1">
                                            <div class="card card-posts p-2">
                                                <p class="m-0 p-0"><i class="fas fa-bullhorn text-info"></i><strong> Posts</strong></p>

                                                <h5 class="text-dark m-0 p-0"><strong><?php echo $totalPostsThisYearCount; ?></strong></h5>
                                                <p class="text-dark mb-1 p-0" style="margin-top: -6px !important;">posts this year</p>

                                                <h5 class="text-dark m-0 p-0"><strong><?php echo $newPostsCount; ?></strong></h5>
                                                <p class="text-dark mb-1 p-0" style="margin-top: -6px !important;">posts this week</p>

                                                <h5 class="text-dark m-0 p-0"><strong><?php echo $postAveragePerWeek; ?></strong></h5>
                                                <p class="text-dark mb-1 p-0" style="margin-top: -6px !important;">posts per week</p>
                                                
                                                <div class="member-changes text-light text-center d-flex align-items-center justify-content-center bg-info" 
                                                    style="position: absolute; width: 30px; height: 30px; border-radius: 50%; right: 5px;">
                                                    <?php if ($postsChanges > 0): ?>
                                                        <i class="fas fa-arrow-up text-light" title="Performance Increasing" style="cursor: pointer;"></i>
                                                    <?php elseif ($postsChanges < 0): ?>
                                                        <i class="fas fa-arrow-down text-light" title="Performance Decreasing" style="cursor: pointer;"></i>
                                                    <?php else: ?>
                                                        <h4 style="cursor: pointer;" title="Performance Unchanging">-</h4>
                                                    <?php endif; ?>
                                                </div>

                                                <?php if ($postsChanges > 0): ?>
                                                    <small class="text-muted m-0 p-0"><small>+</small><?php echo $postsChanges; ?> Compared to previous S.Y.</small>
                                                <?php elseif ($postsChanges < 0): ?>
                                                    <small class="text-muted m-0 p-0"><?php echo $postsChanges; ?> Compared to previous S.Y.</small>
                                                <?php else: ?>
                                                    <small class="text-muted m-0 p-0">+-0 Compared to previous S.Y.</small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <!-- POSTS CARD END -->
                                        <!-- EVENTS CARD START -->
                                        <div class="col-md-4 p-1">
                                            <div class="card card-events p-2">
                                                <p class="m-0 p-0"><i class="fas fa-calendar text-info"></i><strong> Events</strong></p>

                                                <h5 class="text-dark m-0 p-0"><strong><?php echo $totalEventsThisYearCount; ?></strong></h5>
                                                <p class="text-dark mb-1 p-0" style="margin-top: -6px !important;">events this year</p>

                                                <h5 class="text-dark m-0 p-0"><strong><?php echo $newEventsCount; ?></strong></h5>
                                                <p class="text-dark mb-1 p-0" style="margin-top: -6px !important;">events this month</p>

                                                <h5 class="text-dark m-0 p-0"><strong><?php echo $eventsAveragePerMonth; ?></strong></h5>
                                                <p class="text-dark mb-1 p-0" style="margin-top: -6px !important;">events per month</p>
                                                
                                                <div class="member-changes text-light text-center d-flex align-items-center justify-content-center bg-info" style="position: absolute; width: 30px; height: 30px; border-radius: 50%; right: 5px;">
                                                    <?php if ($eventsChanges > 0): ?>
                                                        <i class="fas fa-arrow-up text-light" title="Performance Increasing" style="cursor: pointer;"></i>
                                                    <?php elseif ($eventsChanges < 0): ?>
                                                        <i class="fas fa-arrow-down text-light" title="Performance Decreasing" style="cursor: pointer;"></i>
                                                    <?php else: ?>
                                                        <h4 style="cursor: pointer;" title="Performance Unchanging">-</h4>
                                                    <?php endif; ?>
                                                </div>

                                                <?php if ($eventsChanges > 0): ?>
                                                    <small class="text-muted m-0 p-0"><small>+</small><?php echo round($eventsChanges, 2); ?> Compared to previous S.Y.</small>
                                                <?php elseif ($eventsChanges < 0): ?>
                                                    <small class="text-muted m-0 p-0"><?php echo round($eventsChanges, 2); ?> Compared to previous S.Y.</small>
                                                <?php else: ?>
                                                    <small class="text-muted m-0 p-0">+-0 Compared to previous S.Y.</small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <!-- EVENTS CARD END -->
                                    </div>
                                </div>
                                <div class="col-md-3 d-flex flex-column align-items-center justify-content-start m-0 p-0">
                                    <!-- Circle Bar Section -->
                                    <div class="w-100 d-flex justify-content-center align-items-center">
                                        <div class="circle-bar" title="Slot occupancy - <?php echo $activeCount ?> students, <?php echo $slots ?> slots">
                                            <svg viewBox="0 0 36 36" class="circular-chart">
                                                <!-- Background circle -->
                                                <path class="circle-bg"
                                                    d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" 
                                                    fill="none" stroke="#FFFFFF" stroke-width="4"></path>

                                                <!-- Progress circle based on percentage -->
                                                <path class="circle"
                                                    d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" 
                                                    fill="none" stroke="#007bff" stroke-width="4"
                                                    stroke-dasharray="<?= $slotsPercentage ?>, 100"></path>
                                            </svg>
                                            <div class="circle-label" style="text-align: center; line-height: 1.5; font-size: 24px;">
                                                <?= number_format($slotsPercentage, 2) ?>%
                                                <br>
                                                <small style="line-height: 15px; display: block; font-size: 14px;">Slots<br>Occupancy</small>
                                                
                                                <?php if ($slots > 0): ?>
                                                    <small style="line-height: 15px; display: block; font-size: 11px;">
                                                        (<?php echo $activeCount ?>/<?php echo $slots ?>)
                                                    </small>
                                                <?php else: ?>
                                                    <small style="line-height: 15px; display: block; font-size: 11px;">
                                                        (Unlimited)
                                                    </small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Ratings Section -->
                                    <div class="w-100 text-center p-3">
                                        <p class="m-0 mt-2 p-0"><i class="fas fa-star text-warning"></i><strong> Rating</strong></p>
                                        <div class="club-rating" data-rating="<?php echo $ratingAllYears; ?>" title="Club Rating">
                                            <p class="text-dark mb-0 p-0">Overall: <strong class="text-info"><?php echo $ratingAllYears; ?>/10</strong></p>
                                        </div>
                                        <div class="club-rating" data-rating="<?php echo $ratingThisYear; ?>" title="Club Rating This Year">
                                            <p class="text-dark mb-0 p-0">This Year: <strong class="text-info"><?php echo $ratingThisYear; ?>/10</strong></p>
                                        </div>
                                    </div>
                                    
                                </div>


                                <!-- <div class="row col-12 m-0 mt-2 p-0">
                                    <div class="col-4 p-1">
                                        <div class="card card-members p-2">
                                            <p class="m-0 p-0"><strong>Members</strong></p>
                                            <h3 class="m-0 p-0"><strong><?php echo $newMembersCount; ?><small> new</small></strong></h3>
                                            <p><strong><?php echo $departedMembersCount; ?> Departures</strong></p>

                                            <?php if ($membersChanges > 0): ?>
                                                <p><strong>+<?php echo $membersChanges; ?> compared to the previous school year</strong></p>
                                            <?php elseif ($membersChanges < 0): ?>
                                                <p><strong><?php echo $membersChanges; ?> compared to the previous school year</strong></p>
                                            <?php else: ?>
                                                <p><strong>No change</strong> compared to the previous school year</p>
                                            <?php endif; ?>
                                        </div> 
                                    </div>
                                    <div class="col-4 p-1">
                                        <div class="card card-posts p-2">
                                            <p><strong>Posts</strong></p>
                                        </div>
                                    </div>
                                    <div class="col-4 p-1">
                                        <div class="card card-events p-2">
                                            <p><strong>Events</strong></p>
                                        </div>
                                    </div>
                                </div> -->
                            </div>
                        </div>
                        <!-- <hr> -->
                        
                        <div class="row p-3 mt-2">
                            <!-- Left Column -->
                            <div class="col-md-4 p-0 pr-3">

                                <!-- Most Active Club -->
                                <div class="card text-center mb-3 p-5 bg-dark" style="border-radius: 15px;">
                                    <div class="row">
                                        <div class="col-5 m-0 p-0">
                                            <img src="/esas/esas_admin/icons/ICON_TROPHEE.png" style="width: 100px; height: 100px;">
                                        </div>
                                        <div class="col-7 text-center d-flex flex-column align-items-start justify-content-center m-0 p-0">
                                            <h1 class="m-0 p-0"><strong class="text-info"><?php echo $mostActiveClubRank; ?></strong></h1>
                                            <p class="m-0 p-0"><strong class="text-info">Most active club</strong></p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Most Applied Club -->
                                <div class="card text-center mb-3 p-5 bg-dark" style="border-radius: 15px;">
                                    <div class="row">
                                        <div class="col-5 m-0 p-0">
                                            <img src="/esas/esas_admin/icons/ICON_TROPHEE.png" style="width: 100px; height: 100px;">
                                        </div>
                                        <div class="col-7 text-center d-flex flex-column align-items-start justify-content-center m-0 p-0">
                                            <h1 class="m-0 p-0"><strong class="text-info"><?php echo $mostAppliedClubRank; ?></strong></h1>
                                            <p class="m-0 p-0"><strong class="text-info">Most applied club</strong></p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Highest Number of Members -->
                                <div class="card text-center mb-3 p-5 bg-dark" style="border-radius: 15px;">
                                    <div class="row">
                                        <div class="col-5 m-0 p-0">
                                            <img src="/esas/esas_admin/icons/ICON_TROPHEE.png" style="width: 100px; height: 100px;">
                                        </div>
                                        <div class="col-7 text-center d-flex flex-column align-items-start justify-content-center m-0 p-0">
                                            <h1 class="m-0 p-0"><strong class="text-info"><?php echo $highestInMembersRank; ?></strong></h1>
                                            <p class="m-0 p-0"><strong class="text-info">Highest in members</strong></p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Fastest Growing Club -->
                                <div class="card text-center mb-3 p-5 bg-dark" style="border-radius: 15px;">
                                    <div class="row">
                                        <div class="col-5 m-0 p-0">
                                            <img src="/esas/esas_admin/icons/ICON_TROPHEE.png" style="width: 100px; height: 100px;">
                                        </div>
                                        <div class="col-7 text-center d-flex flex-column align-items-start justify-content-center m-0 p-0">
                                            <h1 class="m-0 p-0"><strong class="text-info"><?php echo $fastestGrowingClubRank; ?></strong></h1>
                                            <p class="m-0 p-0"><strong class="text-info">Fastest growing club</strong></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Right Column -->
                            <div class="card col-md-8 p-3 bg-light">
                                <div class="card text-center mt-1 mb-3 p-2">
                                    <p class="m-0 p-0"><strong><?php echo $moderatorLabel; ?><br></strong>
                                        <?php
                                            if (!empty($moderatorNames)) {
                                                // Split the moderator details into an array (moderator_id:name)
                                                $moderatorArray = explode(', ', $moderatorNames);

                                                // Loop through each moderator's details
                                                foreach ($moderatorArray as $moderatorDetail) {
                                                    // Split the moderator_id and name
                                                    list($moderator_id, $moderator) = explode(':', $moderatorDetail);

                                                    // Create a link for each moderator using the moderator_id, and wrap the moderator name in <strong> tag to make it bold
                                                    echo '<a href="/esas/esas_admin/public/crud/moderators/moderator_read.php?moderator_id=' . urlencode($moderator_id) . '" class="text-decoration-underline"><strong>';
                                                    echo $moderator . '</strong></a><br>';
                                                }
                                            } else {
                                                echo "None";
                                            }
                                        ?>
                                    </p>
                                </div>
                                <h5>Description:</h5>
                                <p style="text-align: justify; text-indent: 30px;"><?php echo $description; ?></p>
                                <h5>Mission:</h5>
                                <p style="text-align: justify; text-indent: 30px;"><?php echo $mission; ?></p>
                                <h5>Vision:</h5>
                                <p style="text-align: justify; text-indent: 30px;"><?php echo $vision; ?></p>
                                <h5>History:</h5>
                                <p style="text-align: justify; text-indent: 30px;"><?php echo $history; ?></p>
                                <!-- <h5>Founder:</h5>
                                <p style="text-align: justify; text-indent: 30px;"><?php echo $founder; ?></p> -->
                            </div>
                        </div>


                    </div>
                    <div class="card-footer text-center">
                        <a href="club_update.php?club_id=<?php echo $club_id; ?>" class="btn btn-warning">Update</a>
                        <a href="club_delete.php?club_id=<?php echo $club_id; ?>" class="btn btn-danger">Delete</a>
                        <a href="javascript:window.history.back();" class="btn btn-secondary">Go Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
    fetchAwards();
});

function fetchAwards() {
    const schoolYear = document.getElementById('schoolYearDropdown').value; // Get the selected school year
    fetch(`/esas/esas_admin/apis/fetch-all-awards-api.php?school_year=${encodeURIComponent(schoolYear)}`)
        .then(response => response.json())
        .then(data => {
            // Handle the awards data
            const awardsSection = document.getElementById('awardsSection'); // Replace with your awards section container ID

            if (data.length > 0) {
                let html = '';
                data.forEach(award => {
                    html += `
                        <div class="card text-center mb-3 p-5 bg-light" style="border-radius: 15px;">
                            <div class="row">
                                <div class="col-5 m-0 p-0">
                                    <img src="/esas/esas_admin/icons/${award.icon}" style="width: 100px; height: 100px;">
                                </div>
                                <div class="col-7 text-center d-flex flex-column align-items-start justify-content-center m-0 p-0">
                                    <h1 class="m-0 p-0"><strong class="text-info">${award.rank}</strong></h1>
                                    <p class="m-0 p-0"><strong class="text-info">${award.title}</strong></p>
                                </div>
                            </div>
                        </div>
                    `;
                });
                awardsSection.innerHTML = html;
            } else {
                awardsSection.innerHTML = 'No awards found.';
            }
        })
        .catch(error => {
            console.error('Error fetching awards:', error);
            document.getElementById('awardsSection').innerHTML = 'Failed to load awards.';
        });
}
</script>

    <style>
        .circle-bar {
        position: relative;
        width: 100%; /* Fixed width */
        /* height: 100%; */
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        border-radius: 50%;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2), 0 6px 20px rgba(0, 0, 0, 0.1);
    }

    .circular-chart {
        width: 100%;
        height: 100%;
    }

    .circle-label {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 18px;
        font-weight: bold;
        text-align: center;
    }

    .circle-bg {
        stroke: #FFFFFF;
        stroke: lightblue;
        stroke: #eee;
        stroke-width: 4;
    }
    .circle {
        stroke: #007bff;
        stroke-width: 4;
        stroke-linecap: round;
        animation: progress 1s ease-out forwards;
    }
    @keyframes progress {
        from {
            stroke-dasharray: 0 100;
        }
    }

    .card-members, .card-posts, .card-events {
        position: relative;
        /* background-color: #F1F3F5; */
        border: none;
        border: solid 1px lightblue;
        border-radius: 10px;
        box-shadow: 0 3px 3px rgba(0, 0, 0, 0.1);
    }

    </style>

</body>
</html>
