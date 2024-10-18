<?php
require_once "../config.php"; // Database config file
session_start();

if (!isset($_SESSION['moderator_id'])) {
    echo "Moderator ID is not set in the session.";
    exit;
}

$moderator_id = $_SESSION['moderator_id']; // Get moderator ID from session

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Fetch moderator's data
$sql = "SELECT * FROM tbl_moderators WHERE moderator_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$moderator_id]);
$moderator = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch as associative array

// Check if the moderator was found
if (!$moderator) {
    echo "Moderator not found.";
    exit; // Exit if the moderator is not found
}

// Fetch associated club's data using the tbl_clubs_and_moderators table
$clubSql = "
    SELECT c.* 
    FROM tbl_clubs AS c
    JOIN tbl_clubs_and_moderators AS cm ON c.club_id = cm.club_id
    WHERE cm.moderator_id = ?";
$clubStmt = $pdo->prepare($clubSql);
$clubStmt->execute([$moderator_id]);
$club = $clubStmt->fetch(PDO::FETCH_ASSOC); // Fetch as associative array

// Check if club was found
if (!$club) {
    echo "No associated club for the moderator.";
    exit; // Exit if the club is not found
}

// Proceed with using $moderator and $club as needed

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eSAS - Moderator Settings</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
    <link href="../assets/css/jquery.dataTables.min.css" rel="stylesheet" />
    <script src="../assets/js/all.js" crossorigin="anonymous"></script>
    <script src="../assets/js/jquery-3.6.0.js"></script>
    <link href="../assets/css/styles.css" rel="stylesheet" />
    <link href="../assets/img/nbsclogo.png" rel="icon">
    <style>
        body {
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .wrapper {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 15px;
        }
        .container {
            background-color: white;
        }
        .sidebar {
            background-color: #f8f9fa;
            padding: 15px;
        }
        .sidebar a {
            display: block;
            padding: 10px;
            color: #007bff;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.2s ease, color 0.2s ease;
        }

        .sidebar a:hover {
            background-color: #AFEEEE;
            color: #0056b3;
        }

        .sidebar a.active {
            background-color: #007bff;
            color: white;
        }

        .main-content {
            padding: 30px 40px;
        }
    </style>
    <script src="../assets/js/jquery-3.6.0.js"></script>
    <script>
       $(document).ready(function() {
            $(".main-content").load("../esas_moderator/public/crud/settings/update_profile.php"); // Load profile update by default

            // Set active class on the default link
            $(".sidebar a").first().addClass("active");

            $(".sidebar a").on("click", function(e) {
                e.preventDefault();
                let page = $(this).attr("href");

                // Remove active class from all links and add it to the clicked link
                $(".sidebar a").removeClass("active");
                $(this).addClass("active");

                $(".main-content").load(page);
            });
        });

        $(document).ready(function() {
            // Check if there's an active tab stored in localStorage
            const activeTab = localStorage.getItem("activeTab");
            
            if (activeTab) {
                // Load the saved tab content
                $(".main-content").load(activeTab);
                // Set the active class on the saved link
                $(".sidebar a").removeClass("active");
                $(".sidebar a[href='" + activeTab + "']").addClass("active");
            } else {
                // Load profile update by default
                $(".main-content").load("../esas_moderator/public/crud/settings/update_profile.php");
                // Set active class on the default link
                $(".sidebar a").first().addClass("active");
            }

            $(".sidebar a").on("click", function(e) {
                e.preventDefault();
                let page = $(this).attr("href");

                // Remove active class from all links and add it to the clicked link
                $(".sidebar a").removeClass("active");
                $(this).addClass("active");

                // Load the content of the selected tab
                $(".main-content").load(page);
                
                // Store the active tab in localStorage
                localStorage.setItem("activeTab", page);
            });
        });

    </script>
</head>
<body>
    
    <div class="wrapper">
        <h2 class="mt-5">Account Settings</h2>
        <p class="text-muted">Change your profile, account, and club settings</p>

        <div class="container-fluid container">
            <div class="row">
                <!-- Sidebar -->
                <div class="col-md-3 sidebar">
                    <a href="../esas_moderator/public/crud/settings/update_profile.php"><i class="fas fa-user-edit"></i> Profile</a>
                    <a href="../esas_moderator/public/crud/settings/update_password.php"><i class="fas fa-lock"></i> Password</a>
                    <a href="../esas_moderator/public/crud/settings/update_club_info.php"><i class="fas fa-university"></i> Club Information</a>
                    <a href="../esas_moderator/public/crud/settings/update_club_officers.php"><i class="fas fa-user-tie"></i> Club Officers</a>
                </div>
                <!-- Main Content Area -->
                <div class="col-md-9 main-content">
                    <!-- Content will be loaded here based on sidebar click -->
                </div>
            </div>
        </div>
    </div>
</body>
</html>
