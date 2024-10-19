<?php 
// Start the session
session_start();

// Include the configuration file
require_once '../config.php';

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Initialize variables for club information
$clubName = '';
$information = '';
$coverPhoto = '';
$dateAdded = '';
$moderators = '';
$membersCount = '';
$slots = ''; // Initialize slots variable
$availableSlots = ''; // Initialize available slots variable

// Fetch the current student's ID from the session
if (isset($_SESSION['student_id'])) {
    $student_id = $_SESSION['student_id'];

    try {
        // Prepare and execute the SQL statement for student information
        $sql = "SELECT firstName, middleName, lastName FROM tbl_students WHERE student_id = :student_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
        $stmt->execute();
        
        // Fetch the result
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if a result was found
        if ($result) {
            $firstName = strtoupper($result['firstName']);
            $middleName = strtoupper($result['middleName']);
            $lastName = strtoupper($result['lastName']);
        }

    } catch (PDOException $e) {
        // Handle database connection or query error
        die("Database error: " . $e->getMessage());
    }
}

// Check if club_id is set and valid
if (isset($_GET['club_id']) && is_numeric($_GET['club_id'])) {
    $club_id = $_GET['club_id'];

    try {
        // Prepare SQL query to fetch club information and moderators' profile pictures
        $stmt = $pdo->prepare("
            SELECT c.clubName, c.information, c.coverPhoto, c.dateAdded, c.slots, 
                m.firstName, m.middleName, m.lastName, m.profilePic,
                COUNT(DISTINCT CASE WHEN r.status = 'active' THEN r.student_id END) AS membersCount,
                COUNT(DISTINCT m.moderator_id) AS numModerators
            FROM tbl_clubs c
            LEFT JOIN tbl_clubs_and_moderators cm ON c.club_id = cm.club_id
            LEFT JOIN tbl_moderators m ON cm.moderator_id = m.moderator_id
            LEFT JOIN tbl_registration r ON c.club_id = r.club_id
            WHERE c.club_id = ? 
            GROUP BY c.club_id
        ");
        $stmt->execute([$club_id]);
        $club = $stmt->fetch(PDO::FETCH_ASSOC);

        // Fetch the club information
        if ($club) {
            $clubName = htmlspecialchars($club['clubName']);
            $information = htmlspecialchars_decode($club['information']); // Decode HTML entities
            $coverPhoto = htmlspecialchars($club['coverPhoto']);
            $dateAdded = htmlspecialchars($club['dateAdded']);
            $membersCount = htmlspecialchars($club['membersCount']);
            $slots = htmlspecialchars($club['slots']); // Fetch and sanitize slots

            // Calculate available slots
            $activeMembersCount = (int)$membersCount; // Convert to integer for calculation
            $availableSlots = max(0, $slots - $activeMembersCount); // Ensure no negative slots

            // Generate moderators HTML
            $moderators = '';
            if ($club['numModerators'] > 0) {
                $stmt = $pdo->prepare("SELECT m.firstName, m.middleName, m.lastName, m.profilePic FROM tbl_moderators m INNER JOIN tbl_clubs_and_moderators cm ON m.moderator_id = cm.moderator_id WHERE cm.club_id = ?");
                $stmt->execute([$club_id]);
                $moderatorsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($moderatorsData as $moderator) {
                    $name = htmlspecialchars($moderator['firstName'] . ' ' . ($moderator['middleName'] ? $moderator['middleName'] . ' ' : '') . htmlspecialchars($moderator['lastName']));
                    $pic = htmlspecialchars($moderator['profilePic']);
                    $moderators .= '
                    <div class="moderator-item">
                        <img src="/esas/esas_moderator/images/' . $pic . '" alt="Profile Pic" class="moderator-pic">
                        <p class="moderator-name">' . $name . '</p>
                    </div>';
                }
            } else {
                // If there are no moderators, show "None"
                $moderators = '<h4 class="text-muted">None</h4>';
            }

            // Set the correct label for moderators
            $numModerators = $club['numModerators'];
            $moderatorsLabel = ($numModerators <= 1) ? 'Moderator:' : 'Moderators:';

            // Format the date into "Month Year"
            try {
                $date = new DateTime($club['dateAdded']);
                $formattedDate = $date->format('F j, Y'); // "Month Year" format
            } catch (Exception $e) {
                $formattedDate = 'Invalid date'; // Fallback if date parsing fails
            }

        } else {
            $clubName = 'Club Not Found';
            $information = 'No information available for this club.';
        }

        // Fetch the student's registration status for the current club
        $stmt = $pdo->prepare("SELECT status, registration_id FROM tbl_registration WHERE student_id = :student_id AND club_id = :club_id ORDER BY registration_id DESC LIMIT 1"); // Fetch the latest registration
        $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
        $stmt->bindParam(':club_id', $club_id, PDO::PARAM_INT);
        $stmt->execute();
        $registration = $stmt->fetch(PDO::FETCH_ASSOC);

        $status = $registration['status'] ?? null;  // Use null if no registration found
        $registration_id = $registration['registration_id'] ?? null; // Fetch the corresponding registration_id

        // Count how many clubs the student is currently "active" in
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM tbl_registration WHERE student_id = :student_id AND status = 'active'");
        $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
        $stmt->execute();
        $clubsCount = $stmt->fetchColumn();

        // Count how many times the student has been "disapproved" for this club
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM tbl_registration WHERE student_id = :student_id AND club_id = :club_id AND status = 'disapproved'");
        $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
        $stmt->bindParam(':club_id', $club_id, PDO::PARAM_INT);
        $stmt->execute();
        $disapprovedCount = $stmt->fetchColumn();

    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }

} else {
    $clubName = 'Invalid Club ID';
    $information = 'Please provide a valid club ID.';
}

// Encode clubName for JavaScript use
$encodedClubName = addslashes($clubName);

$information = nl2br(htmlspecialchars($information)); // Convert newlines to <br>
$information = '<p>' . str_replace('<br />', '</p><p>', $information) . '</p>'; // Wrap paragraphs
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eSAS - Club Information</title>
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> -->
    
    
    <link href="../assets/css/jquery.dataTables.min.css" rel="stylesheet" />
    <script src="../assets/js/all.js" crossorigin="anonymous"></script>
    <script src="../assets/js/jquery-3.6.0.js"></script>
    <link href="../assets/css/styles.css" rel="stylesheet" />
    <link href="../assets/img/nbsclogo.png" rel="icon">

    <style>
        /* body {
            font: 14px Helvetica;
        } */
        .wrapper {
            width: 100%;
            max-width: 750px;
            margin: 0 auto;
            padding: 0px;
            min-height: 500px;
        }
        .container-fluid {
            padding: 20px;
        }
        .navbar-darkblue {
            background-color: #003366;
        }
        .navbar-darkblue .navbar-toggler {
            border-color: rgba(255, 255, 255, 0.1);
        }
        .navbar-darkblue .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 30 30' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath stroke='rgba(255, 255, 255, 1)' stroke-width='2' stroke-linecap='round' stroke-miterlimit='10' d='M4 7h22M4 15h22M4 23h22'/%3E%3C/svg%3E");
        }
        #dashboard_navigations {
            float: flex-end;
        }
        .club-info {
            margin-top: 20px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f8f9fa;
        }
        .custom-alert {
            display: inline-block;
            max-width: 100%;
            text-align: center;
            margin: 0 auto;
        }
        .club-info p {
            text-indent: 1em;
            margin: 0 0 1em 0;
            line-height: 1.6;
            text-align: justify;
        }
        .moderator-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .moderator-pic {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .moderator-name {
            font-size: 16px;
            margin: 0;
            line-height: 1.2;
        }




        .club-info-coverphoto {
            opacity: 0;
            transform: translateY(20px); /* Start from below */
            animation: slideIn 0.6s forwards; /* Apply animation */
            box-shadow: 0 5px 10px rgba(0, 0, 0, .5);
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px); /* Start from below */
            }
            to {
                opacity: 1;
                transform: translateY(0); /* End at normal position */
            }
        }

        /* Optional: Adjust the delay for each card */
        .club-info-coverphoto:nth-child(1) {
            animation-delay: 0s;
        }
        .club-info-coverphoto:nth-child(2) {
            animation-delay: 0.1s;
        }
        .club-info-coverphoto:nth-child(3) {
            animation-delay: 0.2s;
        }






















        .clubname-and-coverphoto {
    margin-top: 0px;
}

.club-info-coverphoto {
    width: 100%;
    height: auto;
    border-radius: 10px;
    object-fit: cover;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.club-name {
    font-size: 1.5rem;
    font-weight: bold;
}

.creation-date {
    font-size: 0.9rem;
    color: #666;
}

.divider {
    margin: 10px 0;
    border-color: #ccc;
}

.moderators-label {
    font-size: 1.1rem;
    font-weight: 600;
    color: #003366; /* Dark blue for the label */
}

.moderators {
    display: flex;
    flex-wrap: wrap;
    gap: 0px; /* Space between moderator elements */
}

.moderator-item {
    width: 100%;
    display: flex;
    align-items: center;
    padding: 5px;
    border: 1px solid #ccc;
    border-radius: 50px;
    background-color: #f8f9fa;
}

.moderator-pic {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    margin-right: 5px;
}




        .members-info {
            margin-top: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .members-info h5 {
            font-size: 1.2rem;
            color: #333;
            margin: 0;
        }

        .members-count, .slots-count {
            font-weight: bold;
            color: #003366;
            margin-left: 5px;
        }







        .club-register-now .alert {
            padding: 10px 20px;
            max-width: 70%;
        }
        .club-register-now p {
            font-size: 16px;
        }

        
        @media (max-width: 768px) {
            .club-register-now .alert {
                max-width: 90%;
            }
            .members-info {
                flex-direction: column;
                align-items: flex-start;
            }
        }
        
    </style>
</head>

<body class="sb-nav-fixed">
    
    <div class="wrapper">
        <div class="container-fluid">
            <!-- <div class="mt-2 mb-2">
                <a href="javascript:history.go(-1)" class="btn btn-secondary"><i class="fa fa-arrow-left"></i></a>
            </div> -->
            <div class="clubname-and-coverphoto"> 
                <div class="row">
                    <div class="col-12 col-md-8">
                        <img class="club-info-coverphoto mt-4" src="/esas/esas_admin/images/<?php echo $coverPhoto; ?>" alt="Cover Photo">
                    </div>
                    <div class="col-12 col-md-4">
                        <h2 class="club-name text-muted mt-4"><?php echo $clubName; ?></h2>
                        <p class="creation-date">Created: <?php echo $formattedDate; ?></p>
                        <hr class="divider">
                        <h5 class="moderators-label mb-3"><?php echo $moderatorsLabel; ?></h5>
                        <div class="moderators"><?php echo $moderators; ?></div>
                    </div>
                </div>
            </div>

            <div class="members-info d-flex justify-content-between align-items-center">
                <h5>Members: <span class="members-count"><?php echo $membersCount; ?></span></h5>
                <h5>Available Slots: <span class="slots-count"><?php echo $availableSlots; ?></span></h5>
            </div>
            <div class="club-info">
                <?php echo $information; ?>
            </div>


            <div class="club-register-now mt-4 text-center align-items-center justify-content-center">
                <?php if ($availableSlots <= 0 && $status === 'active'): ?>
                    <div class="alert alert-info custom-alert" role="alert">
                        <p class="lead mb-0">You are already a member of this club.
                            <a href="/esas/esas_student/home.php?club_id=<?php echo $club_id; ?>"> Go to Home</a>
                        </p>
                    </div>
                <?php elseif ($availableSlots <= 0): ?>
                    <div class="alert alert-danger custom-alert" role="alert">
                        <p class="lead mb-0">This club is full.</p>
                    </div>
                <?php elseif ($status === 'active'): ?>
                    <div class="alert alert-info custom-alert" role="alert">
                        <p class="lead mb-0">You are already a member of this club.
                            <a href="/esas/esas_student/home.php?club_id=<?php echo $club_id; ?>"> Go to Home</a>
                        </p>
                    </div>
                <?php elseif ($status === 'pending' && $clubsCount >= 2): ?>
                    <div class="alert alert-danger custom-alert" role="alert">
                        <p class="lead mb-0">You are no longer qualified for this club. You can only register for up to 2 clubs.</p>
                    </div>
                <?php elseif ($clubsCount >= 2 && $disapprovedCount >= 3): ?>
                    <div class="alert alert-danger custom-alert" role="alert">
                        <p class="lead mb-0">You are already registered in 2 clubs and reached the maximum number of registrations allowed for this club.</p>
                    </div>
                <?php elseif ($clubsCount >= 2): ?>
                    <div class="alert alert-danger custom-alert" role="alert">
                        <p class="lead mb-0">You can only register for up to 2 clubs.</p>
                    </div>
                <?php elseif ($status === 'pending'): ?>
                    <div class="alert alert-warning custom-alert" role="alert">
                        <p class="lead mb-0">You have already applied to this club. Please wait for the Moderator's approval.</p>
                    </div>
                <?php elseif ($disapprovedCount >= 3): ?>
                    <div class="alert alert-danger custom-alert" role="alert">
                        <p class="lead mb-0">You have reached the maximum number of registrations allowed for this club.</p>
                    </div>
                <?php else: ?>
                    <h4 class="mb-3">Join Us Now!</h4>
                    <p class="lead">If you want to be a part of us, register now and become a member of <?php echo $clubName; ?>.</p>
                    <button class="btn btn-primary btn-lg mt-3" onclick="registerNow(<?php echo $club_id; ?>, '<?php echo htmlspecialchars($clubName, ENT_QUOTES); ?>', '<?php echo $status; ?>', <?php echo $clubsCount; ?>, <?php echo $disapprovedCount; ?>)">Register Now</button>
                <?php endif; ?>
                <div class="mt-3">
                    <a href="javascript:history.go(-1)" class="btn btn-secondary">Go Back</a>
                </div>
            </div>



            <!-- REGISTRATION ID IS THE PARAMETER
            <div class="club-register-now mt-4 text-center align-items-center justify-content-center">
                <?php if ($status === 'active'): ?>
                    <div class="alert alert-info custom-alert" role="alert">
                        <p class="lead mb-0">You are already a member of this club.
                            <a href="/esas/esas_student/home.php?club_id=<?php echo $club_id; ?>&registration_id=<?php echo $registration_id; ?>"> Go to Home</a>
                        </p>
                    </div>
                <?php elseif ($status === 'pending' && $clubsCount >= 2): ?>
                    <div class="alert alert-danger custom-alert" role="alert">
                        <p class="lead mb-0">You are no longer qualified for this club. You can only register for up to 2 clubs.</p>
                    </div>
                <?php elseif ($clubsCount >= 2 && $disapprovedCount >= 3): ?>
                    <div class="alert alert-danger custom-alert" role="alert">
                        <p class="lead mb-0">You are already registered in 2 clubs and reached the maximum number of registrations allowed for this club.</p>
                    </div>
                <?php elseif ($clubsCount >= 2): ?>
                    <div class="alert alert-danger custom-alert" role="alert">
                        <p class="lead mb-0">You can only register for up to 2 clubs.</p>
                    </div>
                <?php elseif ($status === 'pending'): ?>
                    <div class="alert alert-warning custom-alert" role="alert">
                        <p class="lead mb-0">You have already applied to this club. Please wait for the Moderator's approval.</p>
                    </div>
                <?php elseif ($disapprovedCount >= 3): ?>
                    <div class="alert alert-danger custom-alert" role="alert">
                        <p class="lead mb-0">You have reached the maximum number of registrations allowed for this club.</p>
                    </div>
                <?php else: ?>
                    <h4 class="mb-3">Join Us Now!</h4>
                    <p class="lead">If you want to be a part of us, register now and become a member of <?php echo $clubName; ?>.</p>
                    <button class="btn btn-primary btn-lg mt-3" onclick="registerNow(<?php echo $club_id; ?>, '<?php echo htmlspecialchars($clubName, ENT_QUOTES); ?>', '<?php echo $status; ?>', <?php echo $clubsCount; ?>, <?php echo $disapprovedCount; ?>, <?php echo $registration_id; ?>)">Register Now</button>
                <?php endif; ?>
                <div class="mt-3">
                    <a href="javascript:history.go(-1)" class="btn btn-secondary">Go Back</a>
                </div>
            </div> -->

        </div>
    </div>

    <!-- <?php include 'assets/components/modals.php' ?> -->
    <script src="../assets/js/jquery.dataTables.min.js"></script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/global_script.js"></script>

    <script>
        function registerNow(clubId, clubName, status, clubsCount, disapprovedCount) {
            if (status === 'active') {
                alert("You are already a member of this club.");
            } else if (status === 'pending') {
                alert("You already applied to this club. Please wait for the Moderator's approval.");
            } else if (clubsCount >= 2) {
                alert("You can only register for up to 2 clubs.");
            } else if (disapprovedCount >= 3) {
                alert("Sorry. You have reached the maximum limit of requests for this club.");
            } else {
                const encodedClubName = encodeURIComponent(clubName);
                const url = `/esas/esas_student/registration.php?club_id=${clubId}&club_name=${encodedClubName}`;
                window.location.href = url;
            }
        }
        

    </script>


</body>

</html>
