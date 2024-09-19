<?php 
session_start();
require_once "../../config.php";  // Assuming this file holds your PDO connection

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
    <title>eSAS - My Clubs</title>
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
        
    </style>
</head>

<!--HERE-->

<body>
    
    <div class="container-fluid">
        <div class="row g-0 h-100">
            <nav class="navbar navbar-expand-lg navbar-dark bg-primary px-2">
                <a class="navbar-brand ps-2" href="#">
                    <img src="../../assets/img/SAS_LOGO.png" style="height: 0.3in;">
                    eSAS - Moderator</a>
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
            <div class="col-12 col-md-2 ps-0 pt-3 border-end">

                <div class="d-flex flex-column flex-shrink-0 p-3 bg-body-tertiary">
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
                                        <div class="d-flex align-items-center justify-content-end pb-3 mt-2 mb-3">
                                            <!-- <h2 class="text-muted mt-0 mb-0">My Clubs</h2> -->
                                            <!-- <button type="button" class="btn btn-primary" id="request-club-btn" data-bs-toggle="modal" data-bs-target="#requestClubModal" style="width: 210px; border-radius: 5px;">
                                                Request for a Club
                                            </button> -->
                                        </div>
                                        <nav>
                                            <div class="nav nav-tabs n" role="tablist">
                                                <button title="Registered Clubs" class="ms-2 px-2 nav-link active" id="nav-activeclubs-tab" data-bs-toggle="tab" data-bs-target="#nav-activeclubs" type="button" role="tab" aria-controls="nav-activeclubs" aria-selected="true" onclick="updateLabel('Registered Clubs')">
                                                    My Clubs
                                                </button>
                                                <button title="Pending Approval" class="px-2 nav-link" id="nav-pendingclubs-tab" data-bs-toggle="tab" data-bs-target="#nav-pendingclubs" type="button" role="tab" aria-controls="nav-pendingclubs" aria-selected="false" onclick="updateLabel('Pending Approval')">
                                                    Pending
                                                </button>
                                                <button title="Disapproved" class="px-2 nav-link" id="nav-disapprovedclubs-tab" data-bs-toggle="tab" data-bs-target="#nav-disapprovedclubs" type="button" role="tab" aria-controls="nav-disapprovedclubs" aria-selected="false" onclick="updateLabel('Disapproved')">
                                                    Disapproved
                                                </button>
                                                <!-- <button title="Filter" class="px-1 btn ms-auto" tabindex="-1" type="button" style="box-shadow: none !important;">
                                                    <i class="fa-solid fa-sliders"></i>
                                                </button> -->
                                            </div>
                                        </nav>
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
                    url: `/esas/esas_student/apis/student-clubs-${tab}-api.php`, // Adjust the URL for each tab
                    type: "GET",
                    success: function(response) {
                        const clubsContainer = document.getElementById(containerId);
                        if (response && response.length > 0) {
                            clubsContainer.innerHTML = response.map(club => `
                                <div class="col-md-4 mb-4 card-container">
                                    <div class="card card-img-only">
                                        <!-- <a href="${tab === 'active' ? '/esas/esas_student/home.php' : `/esas/esas_student/club_info.php?club_id=${club.club_id}&club_name=${encodeURIComponent(club.clubName)}`}">-->
                                        <a href="${tab === 'active' ? `/esas/esas_student/home.php?club_id=${club.club_id}` : `/esas/esas_student/club_info.php?club_id=${club.club_id}&club_name=${encodeURIComponent(club.clubName)}`}">
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