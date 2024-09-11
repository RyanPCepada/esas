<?php
// Start the session
session_start();

// Include the configuration file
require_once '../config.php';

// Initialize variables for club information
$clubName = '';
$information = '';
$coverPhoto = '';
$dateAdded = '';
$moderators = '';
$membersCount = '';

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
            SELECT c.clubName, c.information, c.coverPhoto, c.dateAdded,
                GROUP_CONCAT(DISTINCT CONCAT(m.firstName, ' ', COALESCE(m.middleName, ''), ' ', m.lastName) ORDER BY m.firstName SEPARATOR '|') AS moderatorNames,
                GROUP_CONCAT(DISTINCT m.profilePic ORDER BY m.firstName SEPARATOR '|') AS moderatorPics,
                COUNT(DISTINCT r.student_id) AS membersCount,  -- Use DISTINCT to avoid duplicate counting
                COUNT(DISTINCT m.moderator_id) AS numModerators
            FROM tbl_clubs c
            LEFT JOIN tbl_moderators m ON c.club_id = m.club_id
            LEFT JOIN tbl_registration r ON c.club_id = r.club_id
            WHERE c.club_id = ?
            GROUP BY c.club_id
        ");
        $stmt->execute([$club_id]);
        $club = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($club) {
            $clubName = htmlspecialchars($club['clubName']);
            $information = nl2br(htmlspecialchars($club['information']));
            $coverPhoto = htmlspecialchars($club['coverPhoto']);
            $dateAdded = htmlspecialchars($club['dateAdded']);
            $membersCount = htmlspecialchars($club['membersCount']);
            
            // Process moderators' names and profile pictures
            $moderatorNames = explode('|', $club['moderatorNames']);
            $moderatorPics = explode('|', $club['moderatorPics']);
            $numModerators = $club['numModerators'];

            // Generate moderators HTML
            $moderators = '';
            foreach ($moderatorNames as $index => $name) {
                $pic = isset($moderatorPics[$index]) ? htmlspecialchars($moderatorPics[$index]) : '';
                $moderators .= '<p><img src="/esas/esas_moderator/images/' . $pic . '" alt="Profile Pic" style="width: 50px; height: 50px; border-radius: 50%;"> ' . htmlspecialchars($name) . '</p>';
            }

            // Set the correct label for moderators
            $moderatorsLabel = ($numModerators === 1) ? 'Moderator:' : 'Moderators:';

        } else {
            $clubName = 'Club Not Found';
            $information = 'No information available for this club.';
        }

        // Check if the student is already registered in the club
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM tbl_registration WHERE student_id = :student_id AND club_id = :club_id");
        $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
        $stmt->bindParam(':club_id', $club_id, PDO::PARAM_INT);
        $stmt->execute();
        $isRegistered = $stmt->fetchColumn() > 0;

        // Check if the student is already registered in two clubs
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM tbl_registration WHERE student_id = :student_id AND status = 'active'");
        $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
        $stmt->execute();
        $clubsCount = $stmt->fetchColumn();

    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }

} else {
    $clubName = 'Invalid Club ID';
    $information = 'Please provide a valid club ID.';
}

// Encode clubName for JavaScript use
$encodedClubName = addslashes($clubName);
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
            max-width: 800px;
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
    </style>
</head>

<body class="sb-nav-fixed">
    
    <!-- <nav class="navbar navbar-expand-lg navbar-dark bg-primary px-2">
        <a class="navbar-brand ps-2" href="#">
            <img src="../assets/img/nbsclogo.png" style="height: 0.3in;">
            NBSC SIS</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#main_nav" aria-expanded="true">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-collapse collapse hide" id="main_nav">
            <div class="navbar-collapse flex-grow-1 text-right" id="sampleid" style="padding-left: 20px">
                <php include 'nav/nav_main.php' ?>
            </div>
        </div>
    </nav> -->
    <!-- <nav class="navbar navbar-expand-lg navbar-darkblue">
        <img src="icons/SAS_LOGO.png" height="50" class="d-inline-block align-top" alt="SAS Logo">
        <h5 class="ml-2 mb-0 text-light" id="nbsc_sas_name">Student Organization Club Membership and Information System</h5>
        <button class="navbar-toggler mt-2" type="button" data-toggle="collapse" data-target="#dashboard_navigations" aria-controls="dashboard_navigations" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="dashboard_navigations">
            <ul class="navbar-nav mr-auto"></ul>
            <form class="form-inline my-2 my-lg-0">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-light my-2 my-sm-0" type="submit">Search</button>
            </form>
            <!-- <a href="logout.php" class="btn btn-danger ml-3">Log out</a> --
            <a href="../logout.php" class="btn btn-danger ml-3">Log out</a>
        </div>
    </nav> -->

    <div class="wrapper">
    <div class="container-fluid">
        <!-- <div class="mt-2 mb-2">
            <a href="javascript:history.go(-1)" class="btn btn-secondary"><i class="fa fa-arrow-left"></i></a>
        </div> -->
        <div class="clubname-and-coverphoto">
            <div class="row">
                <div class="col-12 col-md-4">
                    <h2 class="mt-4" style="max-width: 100%;"><?php echo $clubName; ?></h2>
                    <p>Created: <?php echo $dateAdded; ?></p>
                    <hr>
                    <h5><?php echo $moderatorsLabel; ?></h5>
                    <?php echo $moderators; ?>
                    <hr>
                    <h5>Members: <?php echo $membersCount; ?></h5>
                    <!-- <hr> -->
                </div>
                <div class="col-12 col-md-8">
                    <img class="mt-4" src="/esas/esas_admin/images/<?php echo $coverPhoto; ?>" alt="Cover Photo" style="max-width: 100%; border-radius: 20px;">
                </div>
            </div>
        </div>



        <div class="club-info">
            <p><?php echo htmlspecialchars($information); ?></p>
        </div>
        <div class="club-register-now mt-4 text-center align-items-center justify-content-center">
            <h4 class="mb-3">Join Us Now!</h4>
            <p class="lead">If you want to be a part of us, register now and become a member of <?php echo htmlspecialchars($clubName); ?>.</p>
            <button class="btn btn-primary btn-lg mt-3" onclick="registerNow(<?php echo $club_id; ?>, &quot;<?php echo htmlspecialchars($clubName, ENT_QUOTES); ?>&quot;, <?php echo $isRegistered ? 'true' : 'false'; ?>, <?php echo $clubsCount; ?>)">Register Now</button>
            <div class="mt-1">
                <a href="javascript:history.go(-1)" class="btn btn-transparent">Go Back</a>
            </div>
        </div>
    </div>
    </div>

    <!-- <?php include 'assets/components/modals.php' ?> -->
    <script src="../assets/js/jquery.dataTables.min.js"></script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/global_script.js"></script>

    <script>
        function registerNow(clubId, clubName, isRegistered, clubsCount) {
            if (isRegistered) {
                alert("You are already registered in this club.");
            } else if (clubsCount >= 2) {
                alert("You can only register for up to 2 clubs.");
            } else {
                const encodedClubName = encodeURIComponent(clubName);
                const url = `/esas/esas_student/registration.php?club_id=${clubId}&club_name=${encodedClubName}`;
                window.location.href = url;
            }
        }
    </script>

</body>


<!-- <footer class="navbar-darkblue text-white mt-1 p-4 text-center">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>Contact Us</h5>
                    <ul class="list-unstyled">
                        <li>Email: sas@nbsc.edu.ph</li>
                        <li>Phone: 0927 669 0090</li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Follow Us</h5>
                    <ul class="list-unstyled">
                        <li><a href="https://www.facebook.com/nbscstudentaffairsandservices" class="text-white"><i class="fa fa-facebook-square"></i> Facebook</a></li>
                        <li><a href="#" class="text-white"><i class="fa fa-twitter-square"></i> Twitter</a></li>
                        <li><a href="#" class="text-white"><i class="fa fa-instagram"></i> Instagram</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="http://nbsc.edu.ph" class="text-white">NBSC Website</a></li>
                        <li><a href="https://nbsc.edu.ph/student-affairs-services/" class="text-white">SAS Website</a></li>
                        <li><a href="#" class="text-white">About Us</a></li>
                        <li><a href="#" class="text-white">Privacy Policy</a></li>
                        <li><a href="#" class="text-white">Terms of Service</a></li>
                    </ul>
                </div>
            </div>
            <hr>
            <p class="mb-0">© 2024 Student Organization Club Membership and Information System. All rights reserved.</p>
        </div>
    </footer> -->

</html>
