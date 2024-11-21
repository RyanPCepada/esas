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
                            <div class="col-md-9">
                                <h3 class="text-muted mb-0"><?php echo $fullName; ?></h3>
                                <div style="position: relative; margin-top: -15px;">
                                    <span class="status-dot" style="margin-top: 10px; left: 16px; color: red; font-size: 2em;">&#8226;<small class="text-dark ml-1" style="position: absolute; font-size: 14px; margin-top: 16px;"> <?php echo $clubStatus; ?></small></span>
                                </div>
                                <hr>
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
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <a href="moderator_update.php?moderator_id=<?php echo $moderator_id; ?>" class="btn btn-warning">Update</a>
                        <a href="../../moderators.php" class="btn btn-secondary">Go Back</a>
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



<!-- <tr class="moderator-row">
    <td class="text-center">
        <img class="moderator-profile-pic" src="/esas/esas_moderator/images/' . $profilePic . '" 
            alt="' . $fullName . ' profile picture" 
            style="width: 50px; height: 50px; border-radius: 50%;">
    </td>
    <td class="moderator-name"><?php echo $fullName?></td>
    <td class="moderator-club"><?php echo $clubNames?></td>
    <td class="moderator-email"><?php echo $email?></td>
    <td class="moderator-phone"><?php echo $phoneNumber?></td>
    <td class="moderator-department"><?php echo $department?></td>
    <td class="text-center">
        <a href="../public/crud/moderators/moderator_read.php?moderator_id=' . htmlspecialchars($row['moderator_id']) . '" class="mr-2" title="View Record" data-toggle="tooltip"><span class="fa fa-eye"></span></a>
        <a href="../public/crud/moderators/moderator_update.php?moderator_id=' . htmlspecialchars($row['moderator_id']) . '" class="mr-2" title="Update Record" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>
        <a href="../public/crud/moderators/moderator_delete.php?moderator_id=' . htmlspecialchars($row['moderator_id']) . '" title="Delete Record" data-toggle="tooltip"><span class="fa fa-trash"></span></a>
    </td>
</tr> -->