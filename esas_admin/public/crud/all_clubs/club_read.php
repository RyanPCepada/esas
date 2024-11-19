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
                            <div class="col-md-4 text-center">
                                <img src="/esas/esas_admin/images/<?php echo $coverPhoto; ?>" 
                                     alt="<?php echo $clubName; ?> Cover Photo" 
                                     class="img-fluid" style="width: 300px; height: auto; border-radius: 5px; object-fit: cover;">
                                     <!-- class="img-fluid" style="width: 300px; height: 171px; border-radius: 5px; object-fit: cover;"> -->
                            </div>
                            <div class="row col-md-8">
                                <div class="col-8">
                                    <h3 class="text-muted mb-0"><?php echo $clubName; ?></h3>
                                    <span class="status-dot" style="position: absolute; top: 18px; left: 16px; color: red; font-size: 2em;">&#8226;</span><small class="ml-3"> Active <?php echo $clubStatus; ?></small>
                                    <hr>
                                    <p><strong>Date Created: </strong><?php echo date("F j, Y", strtotime($dateAdded)); ?></p>
                                    <p><strong><?php echo $moderatorLabel; ?> </strong><?php echo $moderatorNames; ?></p>
                                </div>
                                <div class="col-4"> 
                                    <div class="circle-bar" title="Slot occupancy"> 
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
                                        <div class="circle-label">
                                            <?= $slotsPercentage ?>%
                                        </div>
                                    </div> 
                                </div>

                                <div class="row col-12 m-0 p-0">
                                    <div class="col-4 p-1">
                                        <div class="card card-members">
                                            MEMBERS
                                        </div>
                                    </div>
                                    <div class="col-4 p-1">
                                        <div class="card card-posts">
                                            POSTS
                                        </div>
                                    </div>
                                    <div class="col-4 p-1">
                                        <div class="card card-events">
                                            EVENTS
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row p-2 mt-4">
                            <div class="col-md-12">
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
        width: 80%; /* Fixed width */
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

    
    </style>


    <script>
document.addEventListener('DOMContentLoaded', function () {
    fetchClubTrends();
});

function fetchClubTrends() {
    const schoolYear = document.getElementById('schoolYearDropdown').value; // Get the selected school year
    const clubId = document.getElementById('clubId').value; // Assuming club_id is in a hidden input with ID `clubId`

    // Fetch the trends with school_year and club_id as query parameters
    fetch(`/esas/esas_admin/apis/fetch-club-trends-api.php?school_year=${encodeURIComponent(schoolYear)}&club_id=${encodeURIComponent(clubId)}`)
        .then(response => response.json())
        .then(data => {
            const clubTrendsList = document.getElementById('clubTrendsList');

            if (data.length > 0) {
                let html = '';
                data.forEach(club => {
                    html += `
                        <div class="card trends-card">
                            <div class="row trends-card-body">
                                <div class="col-3 d-flex justify-content-center align-items-start p-0 ps-2">
                                    <div class="circle-bar" title="Slot occupancy"> 
                                        <svg viewBox="0 0 36 36" class="circular-chart">
                                            <!-- Background circle -->
                                            <path class="circle-bg"
                                                d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" 
                                                fill="none" stroke="#FFFFFF" stroke-width="4"></path>

                                            <!-- Progress circle based on percentage -->
                                            ${club.percentage !== -1 ? `
                                                <path class="circle"
                                                    d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" 
                                                    fill="none" stroke="#007bff" stroke-width="4"
                                                    stroke-dasharray="${club.percentage}, 100"></path>
                                            ` : ''}
                                        </svg>
                                        <div class="circle-label">
                                            ${club.percentage !== -1 ? club.percentageText : club.percentageText}
                                        </div>
                                    </div> 
                                </div>
                                <div class="col-9">
                                    <div class="row ml-1">
                                        <strong><span class="card-title club-name mb-0 text-muted" title="${club.clubName}">${club.clubName}</span></strong>
                                    </div>
                                    <div class="row mt-1 px-2">
                                        <div class="card card-members col-md-4" title="Active and Departed Members This School Year">
                                            <div class="col-5 div-user-icon text-center">
                                                <i class="fas fa-user text-info"></i>
                                            </div>
                                            <div class="col-7 div-user-data text-center">
                                                <strong><p class="m-0" style="font-size: 10px; color: black;">
                                                    ${club.newlyActive !== 0 ? `+${club.newlyActive}` : club.newlyActive}
                                                </p></strong>
                                                <strong><p class="" style="font-size: 10px; color: red; margin: -5px;">
                                                    ${club.newlyDeparted !== 0 ? `-${club.newlyDeparted}` : club.newlyDeparted}
                                                </p></strong>
                                            </div>
                                        </div>
                                        <div class="card card-posts col-md-4" title="Posts per Week Compared to Previous School Year">
                                            <div class="col-5 div-user-icon text-center">
                                                <i class="fas fa-bullhorn text-info"></i>
                                            </div>
                                            <div class="col-7 div-user-data text-center">
                                                <strong>
                                                    <p class="m-0" style="font-size: 10px; color: black;">
                                                        ${club.postPerWeek !== 0 ? `${club.postPerWeek}` : club.postPerWeek}
                                                    </p>
                                                </strong>
                                                <strong>
                                                    <p class="" style="font-size: 10px; 
                                                        color: ${club.postChanges === 0 ? 'black' : (club.postChanges > 0 ? 'blue' : 'red')}; 
                                                        margin: -5px;">
                                                        ${club.postChanges !== 0 ? `${club.postChanges}` : club.postChanges}
                                                    </p>
                                                </strong>
                                            </div>
                                        </div>
                                        <div class="card card-events col-md-4" title="Events per Month Compared to Previous School Year">
                                            <div class="col-5 div-user-icon text-center">
                                                <i class="fas fa-calendar text-info"></i>
                                            </div>
                                            <div class="col-7 div-user-data text-center">
                                                <strong>
                                                    <p class="m-0" style="font-size: 10px; color: black;">
                                                        ${club.eventPerMonth !== 0 ? `${club.eventPerMonth}` : club.eventPerMonth}
                                                    </p>
                                                </strong>
                                                <strong>
                                                    <p class="" style="font-size: 10px; 
                                                        color: ${club.eventChanges === 0 ? 'black' : (club.eventChanges > 0 ? 'blue' : 'red')}; 
                                                        margin: -5px;">
                                                        ${club.eventChanges !== 0 ? `${club.eventChanges}` : club.eventChanges}
                                                    </p>
                                                </strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row ml-1 mb-1">
                                        <div class="club-rating col-6" data-rating="${club.rating}" title="Club Rating">
                                            ${generateStars(club.rating)}
                                            <!-- ${club.rating} -->
                                        </div>
                                        <div class="club-status col-6" data-status="${club.status}" title="Active Status">
                                            <span class="status-dot" style="position: absolute; top: -10px; left: 0; color: red; font-size: 2em;">&#8226;</span>
                                            <span style="font-size: .9em;">${club.status}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });
                clubTrendsList.innerHTML = html;
            } else {
                clubTrendsList.innerHTML = 'No clubs found.';
            }
        })
        .catch(error => {
            console.error('Error fetching club trends:', error);
            document.getElementById('clubTrendsList').innerHTML = 'Failed to load clubs.';
        });
}
</script>
</body>
</html>
