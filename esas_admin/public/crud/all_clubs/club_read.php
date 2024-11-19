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

    if ($seconds <= 60) {
        return "few seconds ago";
    } elseif ($minutes <= 60) {
        return ($minutes == 1) ? "1 min ago" : "$minutes mins ago";
    } elseif ($hours <= 24) {
        return ($hours == 1) ? "1 hr ago" : "$hours hrs ago";
    } elseif ($days <= 7) {
        return ($days == 1) ? "yesterday" : "$days days ago";
    } elseif ($weeks <= 4.3) {
        return ($weeks == 1) ? "1 week ago" : "$weeks weeks ago";
    } elseif ($months <= 12) {
        return ($months == 1) ? "1 month ago" : "$months months ago";
    } else {
        return ($years == 1) ? "1 year ago" : "$years years ago";
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
                GROUP_CONCAT(DISTINCT m.firstName, ' ', m.middleName, ' ', m.lastName ORDER BY m.lastName ASC SEPARATOR ', ') AS moderatorNames
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

                // Determine the last activity
                $logSql = "SELECT MAX(dateAdded) AS lastActivity FROM tbl_activity_logs WHERE club_id = :club_id";
                if ($logStmt = $pdo->prepare($logSql)) {
                    $logStmt->bindParam(":club_id", $club_id, PDO::PARAM_INT);
                    if ($logStmt->execute()) {
                        $logRow = $logStmt->fetch(PDO::FETCH_ASSOC);
                        $lastActivity = !empty($logRow["lastActivity"]) ? $logRow["lastActivity"] : $dateAdded; // Use club dateAdded if no activity found
                        $clubStatus = timeAgo($lastActivity);
                    }
                    unset($logStmt);
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
unset($newMembersStmt, $departedMembersStmt, $totalMembersThisYearCountStmt, $totalMembersLastYearStmt);

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
                                    <p class="m-0 p-0"><strong><?php echo $moderatorLabel; ?><br></strong><?php echo $moderatorNames; ?></p>
                                </div>
                            </div>
                            <div class="row col-md-8">
                                <div class="col-9">
                                    <h3 class="text-muted mb-0"><?php echo $clubName; ?></h3>
                                    <span class="status-dot" style="position: absolute; top: 18px; left: 16px; color: red; font-size: 2em;">&#8226;</span><small class="ml-3"> Active <?php echo $clubStatus; ?></small>
                                    <!-- <hr> -->
                                    <div class="row col-12 m-0 mt-2 p-0">
                                        <!-- MEMBERS CARD START -->
                                        <div class="col-4 p-1">
                                            <div class="card card-members p-2">
                                                <p class="m-0 p-0"><i class="fas fa-user text-info"></i><strong> Members</strong></p>

                                                <h5 class="text-dark m-0 p-0"><strong><?php echo $totalMembersThisYearCount; ?></strong></h5>
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
                                        <div class="col-4 p-1">
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
                                        <div class="col-4 p-1">
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
                                <div class="col-3 d-flex align-items-center justify-content-center m-0 p-0"> 
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
                                            <small style="line-height: 15px; display: block; font-size: 11px;">(<?php echo $activeCount ?>/<?php echo $slots ?>)</small>
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
                        <div class="row p-2 mt-4">
                            <div class="card col-md-12 p-3 bg-light">
                                <h5>Description:</h5>
                                <p style="text-align: justify; text-indent: 30px;"><?php echo $description; ?></p>
                                <h5>Mission:</h5>
                                <p style="text-align: justify; text-indent: 30px;"><?php echo $mission; ?></p>
                                <h5>Vision:</h5>
                                <p style="text-align: justify; text-indent: 30px;"><?php echo $vision; ?></p>
                                <h5>History:</h5>
                                <p style="text-align: justify; text-indent: 30px;"><?php echo $history; ?></p>
                                <h5>Founder:</h5>
                                <p style="text-align: justify; text-indent: 30px;"><?php echo $founder; ?></p>
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

    <div id="clubTrendsList" class="row align-items-start justify-content-start p-1"></div>

    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


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
