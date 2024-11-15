<?php 
// Start the session
session_start();

// Include the configuration file
require_once '../config.php';

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Initialize variables for club description
$clubName = '';
$description = '';
$mission = '';
$vision = '';
$history = '';
$founder = '';
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
        // Prepare and execute the SQL statement for student description
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
        // Prepare SQL query to fetch club description and moderators' profile pictures
        $stmt = $pdo->prepare("
            SELECT c.clubName, c.description, c.mission, c.vision, c.history, c.founder, c.coverPhoto, c.dateAdded, c.slots, 
                m.firstName, m.middleName, m.lastName, m.profilePic,
                COUNT(DISTINCT CASE WHEN r.status = 'active' THEN r.student_id END) AS membersCount,
                COUNT(DISTINCT m.moderator_id) AS numModerators
            FROM tbl_clubs c
            LEFT JOIN tbl_clubs_and_moderators cm ON c.club_id = cm.club_id
            LEFT JOIN tbl_moderators m ON cm.moderator_id = m.moderator_id
            LEFT JOIN tbl_application r ON c.club_id = r.club_id
            WHERE c.club_id = ? 
            GROUP BY c.club_id
        ");
        $stmt->execute([$club_id]);
        $club = $stmt->fetch(PDO::FETCH_ASSOC);

        // Fetch the club description
        if ($club) {
            $clubName = htmlspecialchars($club['clubName']);
            $description = htmlspecialchars_decode($club['description']); // Decode HTML entities
            $mission = htmlspecialchars_decode($club['mission']); // Decode HTML entities
            $vision = htmlspecialchars_decode($club['vision']); // Decode HTML entities
            $history = htmlspecialchars_decode($club['history']); // Decode HTML entities
            $founder = htmlspecialchars_decode($club['founder']);
            $coverPhoto = htmlspecialchars($club['coverPhoto']);
            $dateAdded = htmlspecialchars($club['dateAdded']);
            $membersCount = htmlspecialchars($club['membersCount']);
            $slots = htmlspecialchars($club['slots']); // Fetch and sanitize slots

            // Calculate available slots and handle "Unlimited" and "Full" cases
            if ($slots === null || (int)$slots === 0) {
                $availableSlots = 'Unlimited'; // When slots are NULL or 0
            } else {
                $activeMembersCount = (int)$membersCount; // Convert to integer for calculation
                $remainingSlots = (int)$slots - $activeMembersCount; // Calculate remaining slots
                
                if ($remainingSlots <= 0) {
                    $availableSlots = 'Full'; // Club is full
                } else {
                    $availableSlots = $remainingSlots; // Display available slots
                }
            }

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
                    <div class="moderator-item text-start">
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
            $moderatorsLabel = ($numModerators <= 1) ? 'Moderator <i class="fas fa-shield-alt text-danger"></i>' : 'Moderators <i class="fas fa-shield-alt text-danger"></i>';

            // Format the date into "Month Year"
            try {
                $date = new DateTime($club['dateAdded']);
                $formattedDate = $date->format('F j, Y'); // "Month Year" format
            } catch (Exception $e) {
                $formattedDate = 'Invalid date'; // Fallback if date parsing fails
            }

        } else {
            $clubName = 'Club Not Found';
            $description = 'No description available for this club.';
            $mission = 'No mission available for this club.';
            $vision = 'No vision available for this club.';
            $history = 'No history available for this club.';
            $founder = 'No founder available for this club.';
        }

        // Fetch the student's application status for the current club
        $stmt = $pdo->prepare("SELECT status, application_id FROM tbl_application WHERE student_id = :student_id AND club_id = :club_id ORDER BY application_id DESC LIMIT 1"); // Fetch the latest application
        $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
        $stmt->bindParam(':club_id', $club_id, PDO::PARAM_INT);
        $stmt->execute();
        $application = $stmt->fetch(PDO::FETCH_ASSOC);

        $status = $application['status'] ?? null;  // Use null if no application found
        $application_id = $application['application_id'] ?? null; // Fetch the corresponding application_id

        // Count how many clubs the student is currently "active" in
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM tbl_application WHERE student_id = :student_id AND status = 'active'");
        $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
        $stmt->execute();
        $clubsCount = $stmt->fetchColumn();

        // Count how many times the student has been "disapproved" for this club
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM tbl_application WHERE student_id = :student_id AND club_id = :club_id AND status = 'disapproved'");
        $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
        $stmt->bindParam(':club_id', $club_id, PDO::PARAM_INT);
        $stmt->execute();
        $disapprovedCount = $stmt->fetchColumn();

    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }

} else {
    $clubName = 'Invalid Club ID';
    $description = 'Please provide a valid club ID.';
    $mission = 'Please provide a valid club ID.';
    $vision = 'Please provide a valid club ID.';
    $history = 'Please provide a valid club ID.';
    $founder = 'Please provide a valid club ID.';
}

// Encode clubName for JavaScript use
$encodedClubName = addslashes($clubName);


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ESAS - Club Information</title>
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> -->
    
    
    <link href="../assets/css/jquery.dataTables.min.css" rel="stylesheet" />
    <script src="../assets/js/all.js" crossorigin="anonymous"></script>
    <script src="../assets/js/jquery-3.6.0.js"></script>
    <link href="../assets/css/styles.css" rel="stylesheet" />
    <link href="../assets/img/NBSC_LOGO.png" rel="icon">

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
            padding: 0px;
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
            margin-top: 10px;
            border-radius: 10px;
            object-fit: cover;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .club-name {
            font-weight: bold;
        }

        .divider {
            margin-top: 40px;
            border-color: #ccc;
        }

        .moderators-label {
            font-size: 1.1rem;
            font-weight: 600;
            color: #003366; /* Dark blue for the label */
        }

        .moderators {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
            gap: 0px; /* Space between moderator elements */
        }

        .moderator-item {
            width: 30%;
            display: flex;
            align-items: center;
            margin: 5px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #f8f9fa;
            /* background-color: white; */
            /* box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); */
        }

        .moderator-pic {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 5px;
        }

        .moderator-name {
            font-size: 16px;
            margin: 0;
            line-height: 1.2;
        }





        .numbers-section {
            margin-top: 10px;
            line-height: 10px
        }

        .members-count{
            font-weight: bold;
            color: #003366;
            margin-left: 5px;
            margin-top: 0px;
        }







        .club-apply-now .alert {
            padding: 10px 20px;
            max-width: 70%;
        }
        .club-apply-now p {
            font-size: 16px;
        }

        
        @media (max-width: 768px) {
            .club-info-coverphoto {
                margin-top: 0px;
            }
            .club-apply-now .alert {
                max-width: 90%;
            }
            .numbers-section {
                flex-direction: column;
                align-items: flex-start;
            }
            .moderator-item {
                width: 100%;
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
                        <img class="club-info-coverphoto" src="/esas/esas_admin/images/<?php echo $coverPhoto; ?>" alt="Cover Photo">
                    </div>
                    <div class="col-12 col-md-4">
                        <h3 class="club-name text-muted mt-2"><?php echo $clubName; ?></h3>
                        <p class="creation-date">Created: <?php echo $formattedDate; ?></p>
                        <!-- <hr class="divider"> -->

                        <p class="members-info text-light mb-1">
                            <i class="fas fa-users text-light"></i>Members: <span class="members-count"><?php echo $membersCount; ?></span>
                        </p>

                        <p class="slots-info text-light">
                            <i class="fas fa-check-circle text-light"></i> Slots: <span class="slots-count"><?php echo $availableSlots; ?></span>
                        </p>

                       

                    </div>
                </div>
            </div>
            
            

            <style>
                body {
                    font-size: 18px;
                    line-height: 1.6;
                    color: #444;
                    margin: 10px 0;
                    position: relative;
                    z-index: 1;
                    background: #f9f9f9;
                    padding: 15px;
                    border-radius: 10px;
                }

                .club-profile-container {
                    background-color: #fff;
                    border-radius: 15px;
                    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
                    padding: 10px 25px;
                    margin-top: 20px;
                    position: relative;
                    overflow: hidden;
                    border: 1px solid #ccc;
                }

                .club-profile-container::before {
                    content: "";
                    position: absolute;
                    top: -30%;
                    left: -20%;
                    width: 120%;
                    height: 120%;
                    background: rgba(0, 98, 204, 0.1);
                    border-radius: 50%;
                    z-index: 0;
                    transform: rotate(-30deg);
                }

                .mission-section, .vision-section {
                    margin-bottom: 20px; /* Space between sections */
                    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
                }

                .profile-section-title {
                    font-weight: bold;
                    color: #0062cc;
                    margin-top: 10px;
                    position: relative;
                    z-index: 1;
                    padding-bottom: 10px;
                }

                .profile-section-content {
                    text-align: justify; /* Ensures text aligns to both sides */
                    text-indent: 2em;    /* Indent the first line */
                    margin-bottom: 1em;  /* Adds space between paragraphs */
                }


                /* Common styling for both members and slots */
                .members-info, .slots-info {
                    background-color: rgba(65, 105, 225, 0.8); /* Royal Blue with 80% opacity */
                    border-radius: 10px;
                    padding: 5px 10px;
                    display: inline-block;
                    font-size: 1.2em;
                    color: #00796b; /* Dark teal text color */
                    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.6); /* Shadow effect */
                }

                .members-info {
                    background-color: rgba(34, 139, 34, 0.7); /* Forest Green with 80% opacity */
                }

                .members-info .fa-users, .slots-info .fa-check-circle {
                    margin-right: 8px;
                }

                .members-count, .slots-count {
                    font-weight: bold;
                    color: white;
                }
                
                .dashed-border {
                    height: 2px; /* Thickness of the border */
                    border-top: 2px dashed #ccc; /* Dashed line */
                    margin: 30px 0; /* Space above and below */
                }

                /* Responsive Design */
                @media (max-width: 768px) {
                    .club-profile-container {
                        padding: 15px;
                    }

                    .profile-section-content {
                        /* font-size: 16px; */
                    }
                }
            </style>


            <div class="club-profile-container">
                <div class="description-section">
                    <h5 class="profile-section-title"><i class="fas fa-lightbulb text-warning" style="font-size: 35px;"></i>
                    Why <?php echo $clubName; ?>?</h5>
                    <?php 
                        $descriptionLines = explode("\n", $description);
                        foreach ($descriptionLines as $line) {
                            echo '<p class="profile-section-content">' . htmlspecialchars($line) . '</p>';
                        }
                    ?>
                </div>
                <div class="row" style="margin-top: 30px; margin-bottom: 30px;">
                    <div class="col-md-6">
                        <div class="mission-section text-center p-3 mb-3 border rounded bg-light">
                            <em><h5 class="profile-section-title">Mission</h5></em>
                            <?php 
                                $missionLines = explode("\n", $mission);
                                foreach ($missionLines as $line) {
                                    echo '<em><p class="profile-section-content">' . htmlspecialchars($line) . '</p></em>';
                                }
                            ?>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="vision-section text-center p-3 mb-3 border rounded bg-light">
                            <em><h5 class="profile-section-title">Vision</h5></em>
                            <?php 
                                $visionLines = explode("\n", $vision);
                                foreach ($visionLines as $line) {
                                    echo '<em><p class="profile-section-content">' . htmlspecialchars($line) . '</p></em>';
                                }
                            ?>
                        </div>
                    </div>
                </div>

                
                <div class="history-section">
                    <h5 class="profile-section-title"><i class="fas fa-history text-warning" style="font-size: 30px;"></i>
                    History</h5>
                    <?php 
                        $historyLines = explode("\n", $history);
                        foreach ($historyLines as $line) {
                            echo '<p class="profile-section-content">' . htmlspecialchars($line) . '</p>';
                        }
                    ?>
                </div>
                
                <div class="founder-section">
                    <h5 class="profile-section-title"><i class="fas fa-user text-warning" style="font-size: 30px;"></i>
                    Founder</h5>
                    <?php 
                        $founderLines = explode("\n", $founder);
                        foreach ($founderLines as $line) {
                            echo '<p class="profile-section-content">' . htmlspecialchars($line) . '</p>';
                        }
                    ?>
                </div>

                <!-- <hr class="divider"> -->
                <div class="dashed-border"></div>

                <div class="mb-3 text-center">
                    <h5 class="moderators-label mt-5 mb-3"><?php echo $moderatorsLabel; ?></h5>
                    <div class="moderators"><?php echo $moderators; ?></div>
                </div>

            </div>



                        









            <div class="club-apply-now mt-5 text-center align-items-center justify-content-center">
                <?php if ($availableSlots <= 0 && $status === 'active'): ?>
                    <div class="alert alert-info custom-alert" role="alert">
                        <p class="lead mb-0">You are already a member of this club.
                            <a href="/esas/esas_student/home.php?club_id=<?php echo $club_id; ?>"> Go to Home</a>
                        </p>
                    </div>
                <!-- <php elseif ($membersCount === $slots && ($slots !== 0 || $slots !== NULL)): ?> -->
                <?php elseif ($membersCount === $slots && $slots > 0): ?>
                    <div class="alert alert-danger custom-alert" role="alert">
                        <p class="lead mb-0">This club is full.</p>
                    </div>
                <?php elseif ($status === 'active'): ?>
                    <div class="alert alert-info custom-alert" role="alert">
                        <p class="lead mb-0">You are already a member of this club.
                            <br><a href="/esas/esas_student/home.php?application_id=<?php echo $application_id; ?>&club_id=<?php echo $club_id; ?>"> Go to Home</a>
                        </p>
                    </div>
                <?php elseif ($status === 'pending' && $clubsCount >= 2): ?>
                    <div class="alert alert-danger custom-alert" role="alert">
                        <p class="lead mb-0">You are no longer qualified for this club. You can only apply for up to 2 clubs.</p>
                    </div>
                <?php elseif ($clubsCount >= 2 && $disapprovedCount >= 3): ?>
                    <div class="alert alert-danger custom-alert" role="alert">
                        <p class="lead mb-0">You already have 2 club memberships and reached the maximum number of applications allowed for this club.</p>
                    </div>
                <?php elseif ($clubsCount >= 2): ?>
                    <div class="alert alert-danger custom-alert" role="alert">
                        <p class="lead mb-0">You can only apply for up to 2 clubs.</p>
                    </div>
                <?php elseif ($status === 'pending'): ?>
                    <div class="alert alert-warning custom-alert" role="alert">
                        <p class="lead mb-0">You have already applied to this club. Please wait for the Moderator's approval.
                            <br><a href="/esas/esas_student/ellipsis/application_details.php?application_id=<?php echo $application_id; ?>&club_id=<?php echo $club_id; ?>"> See Application Details</a>
                        </p>
                    </div>
                <?php elseif ($disapprovedCount >= 3): ?>
                    <div class="alert alert-danger custom-alert" role="alert">
                        <p class="lead mb-0">You have reached the maximum number of applications allowed for this club.</p>
                    </div>
                <?php else: ?>
                    <h4 class="mb-3">Join Us Now!</h4>
                    <p class="lead">If you want to be a part of us, apply now and become a member of <?php echo $clubName; ?>.</p>
                    <button class="btn btn-primary btn-lg mt-3" onclick="applyNow(<?php echo $club_id; ?>, '<?php echo htmlspecialchars($clubName, ENT_QUOTES); ?>', '<?php echo $status; ?>', <?php echo $clubsCount; ?>, <?php echo $disapprovedCount; ?>)">Apply Now</button>
                <?php endif; ?>
                <div class="mt-3">
                    <a href="all_clubs.php" class="btn btn-secondary mb-5">Back to Clubs List</a>
                </div>
            </div>



            <!-- REGISTRATION ID IS THE PARAMETER
            <div class="club-apply-now mt-4 text-center align-items-center justify-content-center">
                <?php if ($status === 'active'): ?>
                    <div class="alert alert-info custom-alert" role="alert">
                        <p class="lead mb-0">You are already a member of this club.
                            <a href="/esas/esas_student/home.php?club_id=<?php echo $club_id; ?>&application_id=<?php echo $application_id; ?>"> Go to Home</a>
                        </p>
                    </div>
                <?php elseif ($status === 'pending' && $clubsCount >= 2): ?>
                    <div class="alert alert-danger custom-alert" role="alert">
                        <p class="lead mb-0">You are no longer qualified for this club. You can only apply for up to 2 clubs.</p>
                    </div>
                <?php elseif ($clubsCount >= 2 && $disapprovedCount >= 3): ?>
                    <div class="alert alert-danger custom-alert" role="alert">
                        <p class="lead mb-0">You are already applyed in 2 clubs and reached the maximum number of applications allowed for this club.</p>
                    </div>
                <?php elseif ($clubsCount >= 2): ?>
                    <div class="alert alert-danger custom-alert" role="alert">
                        <p class="lead mb-0">You can only apply for up to 2 clubs.</p>
                    </div>
                <?php elseif ($status === 'pending'): ?>
                    <div class="alert alert-warning custom-alert" role="alert">
                        <p class="lead mb-0">You have already applied to this club. Please wait for the Moderator's approval.</p>
                    </div>
                <?php elseif ($disapprovedCount >= 3): ?>
                    <div class="alert alert-danger custom-alert" role="alert">
                        <p class="lead mb-0">You have reached the maximum number of applications allowed for this club.</p>
                    </div>
                <?php else: ?>
                    <h4 class="mb-3">Join Us Now!</h4>
                    <p class="lead">If you want to be a part of us, apply now and become a member of <?php echo $clubName; ?>.</p>
                    <button class="btn btn-primary btn-lg mt-3" onclick="applyNow(<?php echo $club_id; ?>, '<?php echo htmlspecialchars($clubName, ENT_QUOTES); ?>', '<?php echo $status; ?>', <?php echo $clubsCount; ?>, <?php echo $disapprovedCount; ?>, <?php echo $application_id; ?>)">Apply Now</button>
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
        function applyNow(clubId, clubName, status, clubsCount, disapprovedCount) {
            if (status === 'active') {
                alert("You are already a member of this club.");
            } else if (status === 'pending') {
                alert("You already applied to this club. Please wait for the Moderator's approval.");
            } else if (clubsCount >= 2) {
                alert("You can only apply for up to 2 clubs.");
            } else if (disapprovedCount >= 3) {
                alert("Sorry. You have reached the maximum limit of requests for this club.");
            } else {
                const encodedClubName = encodeURIComponent(clubName);
                const url = `/esas/esas_student/application.php?club_id=${clubId}&club_name=${encodedClubName}`;
                window.location.href = url;
            }
        }
        

    </script>


</body>

</html>
