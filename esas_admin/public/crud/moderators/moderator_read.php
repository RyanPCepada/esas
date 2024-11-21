<?php
// Include config file
require_once "../../../../config.php";

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Check if the moderator_id parameter exists
if (isset($_GET["moderator_id"]) && !empty(trim($_GET["moderator_id"]))) {
    // Get the moderator_id from the query string
    $moderator_id = trim($_GET["moderator_id"]);

    // Prepare the SQL query to fetch moderator details and associated clubs
    $sql = "SELECT 
                m.moderator_id,
                m.firstName,
                m.middleName,
                m.lastName,
                m.age,
                m.birthday,
                m.gender,
                m.email,
                m.phoneNumber,
                m.department,
                m.profession,
                m.profilePic,
                c.club_id, 
                c.clubName
            FROM tbl_moderators m
            LEFT JOIN tbl_clubs_and_moderators cm ON m.moderator_id = cm.moderator_id
            LEFT JOIN tbl_clubs c ON cm.club_id = c.club_id
            WHERE m.moderator_id = :moderator_id";

    // Prepare the statement
    if ($stmt = $pdo->prepare($sql)) {
        // Bind the parameter
        $stmt->bindParam(":moderator_id", $moderator_id, PDO::PARAM_INT);

        // Execute the prepared statement
        if ($stmt->execute()) {
            // Check if the moderator was found
            if ($stmt->rowCount() > 0) {
                // Fetch the row data
                $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Retrieve moderator's basic details
                $fullName = htmlspecialchars($row[0]["firstName"] . " " . $row[0]["middleName"] . " " . $row[0]["lastName"]);
                $age = !empty($row[0]["age"]) ? htmlspecialchars($row[0]["age"]) : 'None';
                $birthday = !empty($row[0]["birthday"]) ? htmlspecialchars($row[0]["birthday"]) : 'None';
                $gender = !empty($row[0]["gender"]) ? htmlspecialchars($row[0]["gender"]) : 'None';
                $email = !empty($row[0]["email"]) ? htmlspecialchars($row[0]["email"]) : 'None';
                $phoneNumber = !empty($row[0]["phoneNumber"]) ? htmlspecialchars($row[0]["phoneNumber"]) : 'None';
                $department = !empty($row[0]["department"]) ? htmlspecialchars($row[0]["department"]) : 'None';
                $profession = !empty($row[0]["profession"]) ? htmlspecialchars($row[0]["profession"]) : 'None';
                $profilePic = !empty($row[0]["profilePic"]) ? htmlspecialchars($row[0]["profilePic"]) : "default-profile.jpg";

                // Function to convert timestamp to human-readable format
                function timeAgo($timestamp) {
                    $timeDifference = time() - strtotime($timestamp);
                    $seconds = $timeDifference;
                    $minutes = round($seconds / 60);
                    $hours = round($seconds / 3600);
                    $days = round($seconds / 86400);
                    $weeks = round($seconds / 604800);
                    $months = round($seconds / 2629440);
                    $years = round($seconds / 31553280);

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

                // Determine the club status based on the last activity
                $logSql = "SELECT activity, dateAdded 
                        FROM tbl_activity_logs 
                        WHERE moderator_id = :moderator_id 
                        ORDER BY dateAdded DESC LIMIT 1";

                if ($logStmt = $pdo->prepare($logSql)) {
                    $logStmt->bindParam(":moderator_id", $moderator_id, PDO::PARAM_INT);
                    if ($logStmt->execute()) {
                        $logRow = $logStmt->fetch(PDO::FETCH_ASSOC);

                        if (!empty($logRow)) {
                            $lastActivity = $logRow["activity"];
                            $lastActivityDate = $logRow["dateAdded"];

                            if ($lastActivity === "You logged out of your account") {
                                $clubStatus = timeAgo($lastActivityDate);
                            } elseif (!empty($lastActivity) && $lastActivity !== "You logged out of your account") {
                                $clubStatus = "Active now";
                            } elseif (empty($lastActivity)) {
                                $clubStatus = "Inactive";
                            }
                        } else {
                            $clubStatus = "Inactive";
                        }
                    } else {
                        $clubStatus = "Error fetching activity logs";
                    }
                    unset($logStmt);
                } else {
                    $clubStatus = "Error preparing statement";
                }
            } else {
                header("location: error.php");
                exit();
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
    }

    unset($stmt);
} else {
    header("location: error.php");
    exit();
}




// Current year and week
$currentYear = date('Y');
$currentWeek = date('W');
$currentMonth = date('m');

// Count of posts this year
$postThisYearQuery = "SELECT COUNT(*) as postCountThisYear FROM tbl_posts 
                      WHERE moderator_id = :moderator_id AND YEAR(dateAdded) = :currentYear";
$stmtPostThisYear = $pdo->prepare($postThisYearQuery);
$stmtPostThisYear->bindParam(':moderator_id', $moderator_id, PDO::PARAM_INT);
$stmtPostThisYear->bindParam(':currentYear', $currentYear, PDO::PARAM_INT);
$stmtPostThisYear->execute();
$postThisYearData = $stmtPostThisYear->fetch(PDO::FETCH_ASSOC);
$moderatorPostsThisYearCount = $postThisYearData['postCountThisYear'];

// Count of posts this week
$postThisWeekQuery = "SELECT COUNT(*) as postCountThisWeek FROM tbl_posts 
                      WHERE moderator_id = :moderator_id AND YEAR(dateAdded) = :currentYear 
                      AND WEEK(dateAdded, 1) = :currentWeek";
$stmtPostThisWeek = $pdo->prepare($postThisWeekQuery);
$stmtPostThisWeek->bindParam(':moderator_id', $moderator_id, PDO::PARAM_INT);
$stmtPostThisWeek->bindParam(':currentYear', $currentYear, PDO::PARAM_INT);
$stmtPostThisWeek->bindParam(':currentWeek', $currentWeek, PDO::PARAM_INT);
$stmtPostThisWeek->execute();
$postThisWeekData = $stmtPostThisWeek->fetch(PDO::FETCH_ASSOC);
$moderatorNewPostsCount = $postThisWeekData['postCountThisWeek'];

// Average posts per week
$postAveragePerWeekQuery = "SELECT COUNT(*) as postCount FROM tbl_posts 
                            WHERE moderator_id = :moderator_id AND YEAR(dateAdded) = :currentYear";
$stmtPostAverage = $pdo->prepare($postAveragePerWeekQuery);
$stmtPostAverage->bindParam(':moderator_id', $moderator_id, PDO::PARAM_INT);
$stmtPostAverage->bindParam(':currentYear', $currentYear, PDO::PARAM_INT);
$stmtPostAverage->execute();
$postAverageData = $stmtPostAverage->fetch(PDO::FETCH_ASSOC);
$moderatorPostAveragePerWeek = round($postAverageData['postCount'] / 52, 2);  // Assume 52 weeks in a year

// Performance change (compared to previous year)
$postChangeQuery = "SELECT COUNT(*) as postCountPreviousYear FROM tbl_posts 
                    WHERE moderator_id = :moderator_id AND YEAR(dateAdded) = :previousYear";
$previousYear = $currentYear - 1;
$stmtPostChange = $pdo->prepare($postChangeQuery);
$stmtPostChange->bindParam(':moderator_id', $moderator_id, PDO::PARAM_INT);
$stmtPostChange->bindParam(':previousYear', $previousYear, PDO::PARAM_INT);
$stmtPostChange->execute();
$postChangeData = $stmtPostChange->fetch(PDO::FETCH_ASSOC);
$moderatorPostsChanges = $moderatorPostsThisYearCount - $postChangeData['postCountPreviousYear'];




// Count of events this year
$eventThisYearQuery = "SELECT COUNT(*) as eventCountThisYear FROM tbl_events 
                       WHERE moderator_id = :moderator_id AND YEAR(dateAdded) = :currentYear";
$stmtEventThisYear = $pdo->prepare($eventThisYearQuery);
$stmtEventThisYear->bindParam(':moderator_id', $moderator_id, PDO::PARAM_INT);
$stmtEventThisYear->bindParam(':currentYear', $currentYear, PDO::PARAM_INT);
$stmtEventThisYear->execute();
$eventThisYearData = $stmtEventThisYear->fetch(PDO::FETCH_ASSOC);
$moderatorEventsThisYearCount = $eventThisYearData['eventCountThisYear'];

// Count of events this month
$eventThisMonthQuery = "SELECT COUNT(*) as eventCountThisMonth FROM tbl_events 
                        WHERE moderator_id = :moderator_id AND YEAR(dateAdded) = :currentYear 
                        AND MONTH(dateAdded) = :currentMonth";
$stmtEventThisMonth = $pdo->prepare($eventThisMonthQuery);
$stmtEventThisMonth->bindParam(':moderator_id', $moderator_id, PDO::PARAM_INT);
$stmtEventThisMonth->bindParam(':currentYear', $currentYear, PDO::PARAM_INT);
$stmtEventThisMonth->bindParam(':currentMonth', $currentMonth, PDO::PARAM_INT);
$stmtEventThisMonth->execute();
$eventThisMonthData = $stmtEventThisMonth->fetch(PDO::FETCH_ASSOC);
$moderatorNewEventsCount = $eventThisMonthData['eventCountThisMonth'];

// Average events per month
$eventAveragePerMonthQuery = "SELECT COUNT(*) as eventCount FROM tbl_events 
                              WHERE moderator_id = :moderator_id AND YEAR(dateAdded) = :currentYear";
$stmtEventAverage = $pdo->prepare($eventAveragePerMonthQuery);
$stmtEventAverage->bindParam(':moderator_id', $moderator_id, PDO::PARAM_INT);
$stmtEventAverage->bindParam(':currentYear', $currentYear, PDO::PARAM_INT);
$stmtEventAverage->execute();
$eventAverageData = $stmtEventAverage->fetch(PDO::FETCH_ASSOC);
$moderatorEventsAveragePerMonth = round($eventAverageData['eventCount'] / 12, 2);  // Assume 12 months in a year

// Performance change (compared to previous year)
$eventChangeQuery = "SELECT COUNT(*) as eventCountPreviousYear FROM tbl_events 
                     WHERE moderator_id = :moderator_id AND YEAR(dateAdded) = :previousYear";
$stmtEventChange = $pdo->prepare($eventChangeQuery);
$stmtEventChange->bindParam(':moderator_id', $moderator_id, PDO::PARAM_INT);
$stmtEventChange->bindParam(':previousYear', $previousYear, PDO::PARAM_INT);
$stmtEventChange->execute();
$eventChangeData = $stmtEventChange->fetch(PDO::FETCH_ASSOC);
$moderatorEventsChanges = $moderatorEventsThisYearCount - $eventChangeData['eventCountPreviousYear'];



unset($pdo);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ESAS - Moderator Details</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link href="../../../assets/css/jquery.dataTables.min.css" rel="stylesheet" />
    <script src="../../../assets/js/all.js" crossorigin="anonymous"></script>
    <script src="../../../assets/js/jquery-3.6.0.js"></script>
    <link href="../../../assets/css/styles.css" rel="stylesheet" />
    <link href="../../../assets/img/NBSC_LOGO.png" rel="icon">
</head>
<body>
<div class="container">
        <div class="row mt-5">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3>Moderator Profile</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 text-center">
                                <img src="/esas/esas_moderator/images/<?php echo $profilePic; ?>" 
                                     alt="<?php echo $fullName; ?> Profile Picture" 
                                     class="img-fluid rounded-circle" style="width: 150px; height: 150px;">
                            </div>
                            <div class="col-md-9 m-0 p-0">
                                <h3 class="text-muted mb-0"><?php echo $fullName; ?></h3>
                                <div style="position: relative; margin-top: -15px;">
                                    <span class="status-dot" style="margin-top: 10px; left: 16px; color: red; font-size: 2em;">&#8226;<small class="text-dark ml-1" style="position: absolute; font-size: 14px; margin-top: 16px;"> <?php echo $clubStatus; ?></small></span>
                                </div>
                                <hr>
                                <div class="row m-0 p-0">
                                    <div class="col-md-5 moderator-details-section m-0 p-0">
                                        <p><strong>Email: </strong><?php echo $email; ?></p>
                                        <p><strong>Phone Number: </strong><?php echo $phoneNumber; ?></p>
                                        <p><strong>Department: </strong><?php echo $department; ?></p>
                                        <p><strong>Profession: </strong><?php echo $profession; ?></p>
                                        <p><strong>Gender: </strong><?php echo $gender; ?></p>
                                        <p><strong>Age: </strong><?php echo $age; ?></p>
                                        <p><strong>Birthday: </strong><?php echo $birthday; ?></p>
                                        <p><strong>Clubs: </strong><br>
                                            <?php 
                                                if (!empty($row)) {
                                                    foreach ($row as $club) {
                                                        $clubId = $club["club_id"];
                                                        $clubName = htmlspecialchars($club["clubName"]);
                                                        echo '<strong><a href="/esas/esas_admin/public/crud/all_clubs/club_read.php?club_id=' . $clubId . '" class="text-decoration-underline">' . $clubName . '</a></strong>';
                                                        echo "<br>"; 
                                                    }
                                                } else {
                                                    echo "None";
                                                }
                                            ?>
                                        </p>
                                    </div>

                                    <div class="row col-md-7 moderator-trends-section m-0 p-0">
                                    <select id="club-dropdown" class="form-control" onchange="filterClubData()">
    <option value="all">All Clubs</option>
    <?php
    // Assuming $row contains the moderator's associated clubs
    if (!empty($row)) {
        foreach ($row as $club) {
            $clubId = $club["club_id"];
            $clubName = htmlspecialchars($club["clubName"]);
            echo '<option value="' . $clubId . '"' . (isset($_GET['club_id']) && $_GET['club_id'] == $clubId ? ' selected' : '') . '>' . $clubName . '</option>';
        }
    } else {
        echo '<option value="none">No clubs associated</option>';
    }
    ?>
</select>

<script>
function filterClubData() {
    var clubId = document.getElementById("club-dropdown").value;
    // Reload the page with the selected club_id
    window.location.href = "moderator_read.php?moderator_id=<?php echo $moderator_id; ?>&club_id=" + clubId;
}
</script>


    <!-- POSTS CARD START -->
    <div class="col-md-6 p-1">
        <div class="card card-posts p-2">
            <p class="m-0 p-0"><i class="fas fa-bullhorn text-info"></i><strong> Posts</strong></p>
            <h5 class="text-dark m-0 p-0"><strong><?php echo $moderatorPostsThisYearCount; ?></strong></h5>
            <p class="text-dark mb-1 p-0" style="margin-top: -6px !important;">posts this year</p>
            <h5 class="text-dark m-0 p-0"><strong><?php echo $moderatorNewPostsCount; ?></strong></h5>
            <p class="text-dark mb-1 p-0" style="margin-top: -6px !important;">posts this week</p>
            <h5 class="text-dark m-0 p-0"><strong><?php echo $moderatorPostAveragePerWeek; ?></strong></h5>
            <p class="text-dark mb-1 p-0" style="margin-top: -6px !important;">posts per week</p>
            <!-- Performance arrow icon and changes -->
            <div class="member-changes text-light text-center d-flex align-items-center justify-content-center bg-info" style="position: absolute; width: 30px; height: 30px; border-radius: 50%; right: 5px;">
                <?php if ($moderatorPostsChanges > 0): ?>
                    <i class="fas fa-arrow-up text-light" title="Performance Increasing" style="cursor: pointer;"></i>
                <?php elseif ($moderatorPostsChanges < 0): ?>
                    <i class="fas fa-arrow-down text-light" title="Performance Decreasing" style="cursor: pointer;"></i>
                <?php else: ?>
                    <h4 style="cursor: pointer;" title="Performance Unchanging">-</h4>
                <?php endif; ?>
            </div>
            <?php if ($moderatorPostsChanges > 0): ?>
                <small class="text-muted m-0 p-0"><small>+</small><?php echo $moderatorPostsChanges; ?> Compared to previous S.Y.</small>
            <?php elseif ($moderatorPostsChanges < 0): ?>
                <small class="text-muted m-0 p-0"><?php echo $moderatorPostsChanges; ?> Compared to previous S.Y.</small>
            <?php else: ?>
                <small class="text-muted m-0 p-0"><?php echo $moderatorPostsChanges; ?> Compared to previous S.Y.</small>
            <?php endif; ?>
        </div>
    </div>
    <!-- POSTS CARD END -->

    <!-- EVENTS CARD START -->
    <div class="col-md-6 p-1">
        <div class="card card-events p-2">
            <p class="m-0 p-0"><i class="fas fa-calendar-day text-warning"></i><strong> Events</strong></p>
            <h5 class="text-dark m-0 p-0"><strong><?php echo $moderatorEventsThisYearCount; ?></strong></h5>
            <p class="text-dark mb-1 p-0" style="margin-top: -6px !important;">events this year</p>
            <h5 class="text-dark m-0 p-0"><strong><?php echo $moderatorNewEventsCount; ?></strong></h5>
            <p class="text-dark mb-1 p-0" style="margin-top: -6px !important;">events this month</p>
            <h5 class="text-dark m-0 p-0"><strong><?php echo $moderatorEventsAveragePerMonth; ?></strong></h5>
            <p class="text-dark mb-1 p-0" style="margin-top: -6px !important;">events per month</p>
            <!-- Performance arrow icon and changes -->
            <div class="member-changes text-light text-center d-flex align-items-center justify-content-center bg-warning" style="position: absolute; width: 30px; height: 30px; border-radius: 50%; right: 5px;">
                <?php if ($moderatorEventsChanges > 0): ?>
                    <i class="fas fa-arrow-up text-light" title="Performance Increasing" style="cursor: pointer;"></i>
                <?php elseif ($moderatorEventsChanges < 0): ?>
                    <i class="fas fa-arrow-down text-light" title="Performance Decreasing" style="cursor: pointer;"></i>
                <?php else: ?>
                    <h4 style="cursor: pointer;" title="Performance Unchanging">-</h4>
                <?php endif; ?>
            </div>
            <?php if ($moderatorEventsChanges > 0): ?>
                <small class="text-muted m-0 p-0"><small>+</small><?php echo $moderatorEventsChanges; ?> Compared to previous S.Y.</small>
            <?php elseif ($moderatorEventsChanges < 0): ?>
                <small class="text-muted m-0 p-0"><?php echo $moderatorEventsChanges; ?> Compared to previous S.Y.</small>
            <?php else: ?>
                <small class="text-muted m-0 p-0"><?php echo $moderatorEventsChanges; ?> Compared to previous S.Y.</small>
            <?php endif; ?>
        </div>
    </div>
    <!-- EVENTS CARD END -->
</div>




                                    
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <a href="moderator_update.php?moderator_id=<?php echo $moderator_id; ?>" class="btn btn-warning">Update</a>
                        <!-- <a href="../../moderators.php" class="btn btn-secondary">Go Back</a> -->
                        <a href="javascript:history.back()" class="btn btn-secondary">Go Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>