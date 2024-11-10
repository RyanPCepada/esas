<?php 
session_start();
require_once "../../config.php";  // Assuming this file holds your PDO connection

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

if (!isset($_SESSION['moderator_id'])) {
    echo "Moderator ID is not set in the session.";
    exit;
}

$moderator_id = $_SESSION['moderator_id']; // Get moderator ID from session

try {
    // Use the existing PDO instance from config.php
    global $pdo;

    // Prepare and execute the SQL statement
    $sql = "SELECT firstName, middleName, lastName FROM tbl_moderators WHERE moderator_id = :moderator_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':moderator_id', $moderator_id, PDO::PARAM_INT);
    $stmt->execute();
    
    // Fetch the result
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if a result was found
    if ($result) {
        $firstName = strtoupper($result['firstName']);
        $middleName = strtoupper($result['middleName']);
        $lastName = strtoupper($result['lastName']);
    } else {
        // Handle the case where no data is found
        $firstName = $middleName = $lastName = "UNKNOWN";
    }

} catch (PDOException $e) {
    // Handle database connection or query error
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>ESAS - My Clubs</title>
    <link href="../../assets/css/jquery.dataTables.min.css" rel="stylesheet" />
    <script src="../../assets/js/all.js" crossorigin="anonymous"></script>
    <script src="../../assets/js/jquery-3.6.0.js"></script>
    <link href="../../assets/css/styles.css" rel="stylesheet" />
    <link href="../../assets/img/nbsclogo.png" rel="icon">
    <style>
        .nav-link.active {
          color: white !important;
          background-color: black;
        }
        .left-sidebar {
            font-size: 16px;
            text-align: start;
        }
        .mainbar h2 {
            margin-left: 15px;
            margin-bottom: 32px;
        }
        .card-body {
            width: 100%;
            /* max-width: 600px; */
            margin: 0 auto;
            padding: 50px;
        }

        
        .card-img-only {
            position: relative;
            width: 295px;
            height: 166px;
            /* width: 330px;
            height: 188px; */
            border: solid 3px transparent;
            border: none;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 10px rgba(0, 0, 0, .5);
            margin: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card-img-only:hover {
            transform: scale(1.01);
            border: solid 3px white;
            border: none;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.6);
        }

        .card-img-only img {
            max-width: 100%; /* Image can scale down to fit container width */
            max-height: 100%; /* Image can scale down to fit container height */
            display: block;
            margin: auto; /* Center the image within the container */
            background: linear-gradient(to top right, rgba(0,0,0,0.6), rgba(255,255,255,0.2));
        }


        .card small {
            position: absolute;
            top: 5px;
            right: 5px;
            color: white;
            padding: 8px;
            font-size: 14px;
            cursor: pointer;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.6); /* Shadow effect */
        }
        .overlay-text {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 10px;
            background: linear-gradient(to top, rgba(0, 0, 0, .7), rgba(0, 0, 0, 0));
            color: white;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.6); /* Shadow effect */
            text-align: left;
            line-height: 1.2; /* Adjust line height for closer spacing */
        }
        .overlay-text h4 {
            margin: 7px;
            font-size: 25px;
            line-height: 1.1;
        }
        .overlay-text p {
            margin: 5px 0 0;
            font-size: 14px;
        }
        .date {
            margin-left: 12px;
        }


        @media (max-width: 768px) {
            .card-body {
                padding: 10px; 
                max-width: 100%; 
            }
            .card-img-only {
                margin: 10px auto;
            }
            .date {
                margin-left: 20px;
            }
        }



        
        .card-container {
            opacity: 0;
            transform: translateY(20px); /* Start from below */
            animation: slideIn 0.6s forwards; /* Apply animation */
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(20px); /* Start from below */
            }
            to {
                opacity: 1;
                transform: translateY(0); /* End at normal position */
            }
        }

        /* Optional: Adjust the delay for each card */
        .card-container:nth-child(1) {
            animation-delay: 0s;
        }
        .card-container:nth-child(2) {
            animation-delay: 0.1s;
        }
        .card-container:nth-child(3) {
            animation-delay: 0.2s;
        }
        
    </style>
</head>

<!--HERE-->

<body>
    
    <div class="container-fluid">
        <div class="row g-0 h-100">
            <nav class="navbar navbar-expand-lg navbar-dark bg-primary px-2">
                <a class="navbar-brand ps-2" href="#">
                    <img src="../../assets/img/SAS_LOGO.png" style="height: 0.3in;">
                    ESAS - Moderator</a>
                </button>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#main_nav" aria-expanded="true">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="navbar-collapse collapse hide" id="main_nav">
                    <div class="navbar-collapse flex-grow-1 text-right" id="sampleid" style="padding-left: 20px">
                        <?php include '../nav/nav_main.php' ?>
                    </div>
                </div>
            </nav>
            
            <!-- LEFT SIDEBAR -->
            <div class="col-12 col-md-2 pt-3 border-end">

                <div class="d-flex flex-column flex-shrink-0 px-2 bg-body-tertiary">
                    <ul class="nav nav-pills flex-column mb-auto">
                        <li>
                            <a href="../../esas_moderator/public/dashboard.php" class="nav-link left-sidebar text-dark" id="dashboard">
                                <i class="fas fa-chart-line"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../../esas_moderator/public/my_clubs.php" class="nav-link left-sidebar text-dark active" aria-current="page" id="my-clubs">
                                <i class="fas fa-university"></i> My Clubs
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../../esas_moderator/public/students.php" class="nav-link left-sidebar text-dark" id="students">
                                <i class="fas fa-users"></i> Students
                            </a>
                        </li>
                        <li>
                            <a href="../../esas_moderator/public/pending_approvals.php" class="nav-link left-sidebar text-dark" id="pending-approvals">
                                <i class="fas fa-hourglass-half"></i> Pending Approvals
                            </a>
                        </li>
                        <li>
                            <a href="../../esas_moderator/public/departure_requests.php" class="nav-link left-sidebar text-dark" id="departure-requests">
                                <i class="fas fa-door-open"></i> Departure Requests
                            </a>
                        </li>
                        <li>
                            <a href="../../esas_moderator/public/reports.php" class="nav-link left-sidebar text-dark" id="reports">
                                <i class="fas fa-file-alt"></i> Reports
                            </a>
                        </li>
                        Others
                        <li>
                            <a href="../../esas_moderator/public/accomplishment_reports.php" class="nav-link left-sidebar text-dark" id="accomplishment_reports" 
                                style="display: flex; gap: 7px; align-items: flex-start;">
                            
                                <span class="icon-column" style="flex-shrink: 0;">
                                    <i class="fas fa-file-alt"></i>
                                </span>
                                    
                                <span class="text-column" style="line-height: 1.2;">
                                    Accomplishment Reports
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>

            </div>
            <!-- LEFT SIDEBAR END -->

            
            <!-- MAINPAGE BAR -->
            <div class="col-12 col-md-10 bg-lgrey auto-scroll">
                <div class="row g-0 h-100">
                    <div id="divpr_requesdetails" class="table-responsive px-0">
                        <div class="row g-0 p-4 px-2 pt-3 h-100">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row g-0">
                                        <div class="d-flex align-items-center pb-3 mt-2 mb-3">
                                            <h3 class="text-muted ms-3 mt-0 mb-0">Clubs You Are Moderating</h3>
                                            <!-- <button type="button" class="btn btn-primary" id="request-club-btn" data-bs-toggle="modal" data-bs-target="#requestClubModal" style="width: 210px; border-radius: 5px;">
                                                Request for a Club
                                            </button> -->
                                        </div>
                                        <!-- <nav>
                                            <!-- <div class="nav nav-tabs n" role="tablist">
                                                <!-- <button title="Registered Clubs" class="ms-2 px-2 nav-link active" id="nav-activeclubs-tab" data-bs-toggle="tab" data-bs-target="#nav-activeclubs" type="button" role="tab" aria-controls="nav-activeclubs" aria-selected="true" onclick="updateLabel('Registered Clubs')">
                                                    My Clubs
                                                </button> -->
                                                <!-- <button title="Pending Approval" class="px-2 nav-link" id="nav-pendingclubs-tab" data-bs-toggle="tab" data-bs-target="#nav-pendingclubs" type="button" role="tab" aria-controls="nav-pendingclubs" aria-selected="false" onclick="updateLabel('Pending Approval')">
                                                    Pending
                                                </button>
                                                <button title="Disapproved" class="px-2 nav-link" id="nav-disapprovedclubs-tab" data-bs-toggle="tab" data-bs-target="#nav-disapprovedclubs" type="button" role="tab" aria-controls="nav-disapprovedclubs" aria-selected="false" onclick="updateLabel('Disapproved')">
                                                    Disapproved
                                                </button> -->
                                                <!-- <button title="Filter" class="px-1 btn ms-auto" tabindex="-1" type="button" style="box-shadow: none !important;">
                                                    <i class="fa-solid fa-sliders"></i>
                                                </button> --
                                            </div> --
                                        </nav> -->
                                        <div class="tab-content">
                                            <!-- Registered Clubs Tab -->
                                            <div class="tab-pane fade show active" id="nav-activeclubs" role="tabpanel" aria-labelledby="nav-activeclubs-tab">
                                                <div class="row g-2 mt-0" id="activeClubsContainer">
                                                    <!-- All student clubs cards will be dynamically added here -->
                                                </div>
                                            </div>

                                            <!-- Pending Approval Clubs Tab -->
                                            <div class="tab-pane fade" id="nav-pendingclubs" role="tabpanel" aria-labelledby="nav-pendingclubs-tab">
                                                <div class="row g-2 mt-0" id="pendingClubsContainer">
                                                    <!-- All student pending clubs cards will be dynamically added here -->
                                                </div>
                                            </div>

                                            <!-- Disapproved Clubs Tab -->
                                            <div class="tab-pane fade" id="nav-disapprovedclubs" role="tabpanel" aria-labelledby="nav-disapprovedclubs-tab">
                                                <div class="row g-2 mt-0" id="disapprovedClubsContainer">
                                                    <!-- All student disapproved clubs cards will be dynamically added here -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
            <!-- MAINPAGE BAR END -->

        </div>
    </div>


    <!-- <?php include 'assets/components/modals.php' ?> -->
    <script src="../../assets/js/jquery.dataTables.min.js"></script>
    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/js/global_script.js"></script>



    <script>
        function submitClubRequest() {
            document.getElementById('clubRequestForm').submit();
        }

        function updateLabel(label) {
            document.getElementById("tabLabel").innerText = label;
        }

        $(document).ready(function() {
            function loadClubs(tab, containerId, dateLabel) {
                $.ajax({
                    url: `/esas/esas_moderator/apis/moderator-clubs-${tab}-api.php`, // Adjust the URL for each tab
                    type: "GET",
                    success: function(response) {
                        const clubsContainer = document.getElementById(containerId);
                        if (response && response.length > 0) {
                            clubsContainer.innerHTML = response.map(club => `
                                <div class="col-md-4 card-container">
                                    <div class="card card-img-only">
                                        <a href="/esas/esas_moderator/public/home.php?club_id=${club.club_id}">
                                            <img src="/esas/esas_admin/images/${club.coverPhoto}" alt="Cover Photo">
                                            <div class="overlay-text">
                                                <h4>${club.clubName}</h4>
                                            </div>
                                        </a>
                                    </div>
                                    <!--<div class="date text-muted">
                                        ${dateLabel}: ${club.dateModified}
                                    </div>-->
                                </div>
                            `).join('');
                        } else {
                            clubsContainer.innerHTML = '<p>No clubs found.</p>';
                        }
                    },
                    error: function() {
                        const clubsContainer = document.getElementById(containerId);
                        clubsContainer.innerHTML = '<p>Failed to fetch clubs. Please try again later.</p>';
                    }
                });
            }

            // Load initial content for All Clubs tab
            loadClubs('active', 'activeClubsContainer', 'Member since');

            // Event listeners for each tab
            $('#nav-activeclubs-tab').on('click', function() {
                loadClubs('active', 'activeClubsContainer', 'Member since');
            });

            $('#nav-pendingclubs-tab').on('click', function() {
                loadClubs('pending', 'pendingClubsContainer', 'Application date');
            });

            $('#nav-disapprovedclubs-tab').on('click', function() {
                loadClubs('disapproved', 'disapprovedClubsContainer', 'Date disapproved');
            });
        });

    </script>

    <script>
        $(document).ready(function() {
            $('.delprreq').click(function(e) {
                e.stopPropagation();
            });
            // let value= $("classname").val()
        });
    </script>
</body>
</html>