<?php
session_start();
require_once "../config.php";  // Assuming this file holds your PDO connection

// Check if moderator is logged in
if (!isset($_SESSION['moderator_id'])) {
    die("Moderator session not found.");
}

$moderator_id = $_SESSION['moderator_id'];

try {
    // Fetch total clubs associated with the moderator
    $stmt_clubs = $pdo->prepare("SELECT COUNT(club_id) AS total_clubs FROM tbl_clubs WHERE moderator_id = :moderator_id");
    $stmt_clubs->bindParam(':moderator_id', $moderator_id, PDO::PARAM_INT);
    $stmt_clubs->execute();
    $total_clubs = $stmt_clubs->fetchColumn();

    // Fetch total distinct students who registered under clubs managed by this moderator
    $stmt_students = $pdo->prepare("
        SELECT COUNT(DISTINCT tr.student_id) AS total_students 
        FROM tbl_registration tr 
        JOIN tbl_clubs tc ON tr.club_id = tc.club_id 
        WHERE tc.moderator_id = :moderator_id
    ");
    $stmt_students->bindParam(':moderator_id', $moderator_id, PDO::PARAM_INT);
    $stmt_students->execute();
    $total_students = $stmt_students->fetchColumn();

    // Fetch total pending requests for clubs managed by this moderator
    $stmt_pending = $pdo->prepare("
        SELECT COUNT(tcr.request_id) AS total_pending 
        FROM tbl_club_requests tcr 
        JOIN tbl_clubs tc ON tcr.club_id = tc.club_id 
        WHERE tcr.status = 'pending' AND tc.moderator_id = :moderator_id
    ");
    $stmt_pending->bindParam(':moderator_id', $moderator_id, PDO::PARAM_INT);
    $stmt_pending->execute();
    $total_pending = $stmt_pending->fetchColumn();

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
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
    <title>eSAS - Dashboard</title>
    <link href="../assets/css/jquery.dataTables.min.css" rel="stylesheet" />
    <script src="../assets/js/all.js" crossorigin="anonymous"></script>
    <script src="../assets/js/jquery-3.6.0.js"></script>
    <link href="../assets/css/styles.css" rel="stylesheet" />
    <link href="../assets/img/nbsclogo.png" rel="icon">
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
<body>
    
    <div class="container-fluid">
        <div class="row g-0 h-100">
            <nav class="navbar navbar-expand-lg navbar-dark bg-primary px-2">
                <a class="navbar-brand ps-2" href="#">
                    <img src="../assets/img/SAS_LOGO.png" style="height: 0.3in;">
                    eSAS</a>
                </button>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#main_nav" aria-expanded="true">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="navbar-collapse collapse hide" id="main_nav">
                    <div class="navbar-collapse flex-grow-1 text-right" id="sampleid" style="padding-left: 20px">
                        <?php include 'nav/nav_main.php' ?>
                    </div>
                </div>
            </nav>
            <!-- LEFT SIDEBAR -->
            <div class="col-12 col-md-2 ps-0 pt-3 border-end">

            <div class="d-flex flex-column flex-shrink-0 p-3 bg-body-tertiary">
                <ul class="nav nav-pills flex-column mb-auto">
                    <li>
                        <a href="../esas_student/all_clubs.php" class="nav-link left-sidebar text-dark active" id="all-clubs">
                            <i class="fas fa-chart-line"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="../esas_student/my_clubs.php" class="nav-link left-sidebar text-dark" aria-current="page" id="my-clubs">
                            <i class="fas fa-users"></i> My Clubs
                        </a>
                    </li>
                    <li>
                        <a href="../esas_student/pending_approvals.php" class="nav-link left-sidebar text-dark" id="pending-approvals">
                            <i class="fas fa-hourglass-half"></i> Pending Approvals
                        </a>
                    </li>
                    <li>
                        <a href="../esas_student/club_requests.php" class="nav-link left-sidebar text-dark" id="club-requests">
                            <i class="fas fa-envelope"></i> Club Requests
                        </a>
                    </li>
                </ul>
            </div>

            </div>
            <!-- LEFT SIDEBAR END -->


            
            
            <!-- MAINPAGE BAR -->
<div class="col-12 col-md-10 bg-lgrey auto-scroll">
    <div class="row g-0 h-100">
        <div class="row g-0 p-4 px-2 pt-2 h-100">
            <div class="row align-items-center mb-3">
                <label for="schoolYearDropdown" class="col-auto col-form-label">School Year:</label>
                <div class="col-auto">
                    <select id="schoolYearDropdown" class="form-select form-select-sm" style="width: 150px;" onchange="filterDashboard()">
                        <option value="2024-2025">2024-2025</option>
                        <option value="2023-2024">2023-2024</option>
                        <option value="2022-2023">2022-2023</option>
                        <option value="2021-2022">2021-2022</option>
                    </select>
                </div>
            </div>

            <!-- THE MAIN PAGE START -->
            <div class="card p-4">
                <!-- UPPER CARDS START -->
                <div class="row card-row1 col-md-12 mb-3" style="border: 1px solid black; margin: 0;">
                    <!-- Card for TOTAL CLUBS -->
                    <div class="col-md-3 p-1" style="border: 1px solid red; padding: 0;">
                        <div class="card p-2" style="margin: 0;">
                            <h3>0</h3>
                            <p>Total Clubs</p>
                        </div>
                    </div>

                    <!-- Card for TOTAL STUDENTS -->
                    <div class="col-md-3 p-1" style="border: 1px solid red; padding: 0;">
                        <div class="card p-2" style="margin: 0;">
                            <h3>0</h3>
                            <p>Total Students</p>
                        </div>
                    </div>

                    <!-- Card for TOTAL PENDING -->
                    <div class="col-md-3 p-1" style="border: 1px solid red; padding: 0;">
                        <div class="card p-2" style="margin: 0;">
                            <h3>0</h3>
                            <p>Total Pending Requests</p>
                        </div>
                    </div>

                    <!-- Card for LEAVE REQUESTS (you can modify this query as needed) -->
                    <div class="col-md-3 p-1" style="border: 1px solid red; padding: 0;">
                        <div class="card p-2" style="margin: 0;">
                            <h3>0</h3>
                            <p>Leave Requests</p>
                        </div>
                    </div>
                </div>
                <!-- UPPER CARDS END -->

                <!-- CHARTS AND DIAGRAMS START -->
                <div class="row card-row2 col-12" style="border: 1px solid black; margin: 0;">
                    <!-- PIE CHART -->
                    <div class="col-md-5 p-1" style="border: 1px solid blue; padding: 0;">
                        <div class="card p-2" style="margin: 0;">
                            <h5>Students per Department</h5>
                            <div style="height: 365px; background-color: lightgray;">
                                <!-- PIECHART -->
                            </div>
                        </div>
                    </div>

                    <!-- OTHER CHARTS -->
                    <div class="col-md-7" style="border: 1px solid blue; padding: 0;">
                        <div class="row" style="border: 1px solid yellow; margin: 0;">
                            <!-- Student Gender -->
                            <div class="col-md-12 p-1" style="border: 1px solid orange; padding: 0;">
                                <div class="card p-2" style="margin: 0;">
                                    <h5>Student Gender</h5>
                                    <div style="height: 150px; background-color: lightgray;">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Vertically divided Year Level Count and Members per School Year -->
                        <div class="row" style="border: 1px solid yellow; margin: 0;">
                            <!-- Year Level Count -->
                            <div class="col-md-6 p-1" style="border: 1px solid orange; padding: 0;">
                                <div class="card p-2" style="margin: 0;">
                                    <h5>Year Level Count</h5>
                                    <div style="height: 150px; background-color: lightgray;">
                                        <!-- BAR GRAPH FOR NUMBERS OF YEAR LEVEL -->
                                    </div>
                                </div>
                            </div>
                            <!-- Members per School Year -->
                            <div class="col-md-6 p-1" style="border: 1px solid orange; padding: 0;">
                                <div class="card p-2" style="margin: 0;">
                                    <h5>Members per School Year</h5>
                                    <div style="height: 150px; background-color: lightgray;">
                                        <!-- BAR GRAPH FOR NUMBERS OF YEAR LEVEL -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
                <!-- CHARTS AND DIAGRAMS END -->
            </div>
            <!-- THE MAIN PAGE END -->
        </div>
    </div>
</div>
<!-- MAINPAGE BAR END -->


        </div>
    </div>

    <!-- <?php include 'assets/components/modals.php' ?> -->
    <script src="../assets/js/jquery.dataTables.min.js"></script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/global_script.js"></script>



    <script>
        function submitClubRequest() {
            document.getElementById('clubRequestForm').submit();
        }

        function updateLabel(label) {
            document.getElementById("tabLabel").innerText = label;
        }

        // Fetch clubs data from API
        fetch('/esas/esas_student/apis/clubs-api.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // $('#post-26').html(data)
                const allClubsContainer = document.getElementById('allClubsContainer');
                if (data && data.length > 0) {
                    data.forEach(club => {
                        const memberText = club.membersCount === 1 ? '1 member' : `${club.membersCount} members`;
                        const cardHTML = `
                            <div class="col-md-4 card-container">
                                <div class="card card-img-only-all">
                                    <small data-toggle="tooltip" title="${memberText}">
                                        <i class="fa fa-user mr-1"></i>${club.membersCount}
                                    </small>
                                    <a href="/esas/esas_student/club_info.php?club_id=${club.club_id}&club_name=${encodeURIComponent(club.clubName)}">
                                        <img src="/esas/esas_admin/images/${club.coverPhoto}" alt="Cover Photo">
                                        <div class="overlay-text">
                                            <h4>${club.clubName}</h4>
                                            <!--<div class="moderators-container">
                                                ${club.formattedModerators}
                                            </div>-->
                                        </div>
                                    </a>
                                </div>
                            </div>
                        `;
                        allClubsContainer.innerHTML += cardHTML;
                    });
                } else {
                    allClubsContainer.innerHTML = '<p>No clubs found.</p>';
                }
            })
            .catch(error => {
                console.error('Error fetching clubs:', error);
                const allClubsContainer = document.getElementById('allClubsContainer');
                allClubsContainer.innerHTML = '<p>Failed to fetch clubs. Please try again later.</p>';
            });


    </script>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Cache the elements
        const allClubsLink = document.getElementById('all-clubs');
        const myClubsLink = document.getElementById('my-clubs');
        const clubRequestsLink = document.getElementById('club-requests');
        const officersDiv = document.querySelector('.officers-div');
        const csgCards = document.querySelectorAll('.card-csg-officer'); // CSG officer cards
        const sboCards = document.querySelectorAll('.card-sbo-officer'); // SBO officer cards

        function animateCards(cards) {
            // Apply the animation waveIn dynamically for a group of cards
            cards.forEach((card, index) => {
                // Reset styles
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px) scale(0.95)';
                card.style.transition = 'none'; // Disable transition for reset

                // Trigger a reflow to apply reset styles
                void card.offsetWidth;

                // Re-enable transitions
                card.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';

                // Apply animation with a delay (wave effect)
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0) scale(1)';
                    card.style.animation = `waveIn 0.6s ease-out forwards`;
                }, index * 100); // Delay per card to create the wave effect
            });
        }

        function updateVisibility() {
            if (allClubsLink.classList.contains('active')) {
                officersDiv.style.display = 'block'; // Show officers div

                // Trigger animations for CSG and SBO cards at the same time but separately
                animateCards(csgCards);  // Animate CSG officers
                animateCards(sboCards);  // Animate SBO officers
            } else {
                officersDiv.style.display = 'none'; // Hide officers div
            }
        }

        // Add keyframes dynamically
        const styleSheet = document.createElement('style');
        styleSheet.type = 'text/css';
        styleSheet.innerHTML = `
            @keyframes waveIn {
                0% {
                    opacity: 0;
                    transform: translateY(20px) scale(0.95);
                }
                50% {
                    opacity: 0.5;
                    transform: translateY(-10px) scale(1.05); /* Peak of the wave */
                }
                100% {
                    opacity: 1;
                    transform: translateY(0) scale(1);
                }
            }
        `;
        document.head.appendChild(styleSheet);

        // Initial visibility setup
        updateVisibility();
    });


    $(document).ready(function() {
        $('.delprreq').click(function(e) {
            e.stopPropagation();
        });
        // let value= $("classname").val()
    });
</script>

</body>
</html>