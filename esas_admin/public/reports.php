<?php 
session_start();
require_once "../../config.php";  // Assuming this file holds your PDO connection

if (!isset($_SESSION['admin_id'])) {
    echo "Admin ID is not set in the session.";
    exit;
}

$admin_id = $_SESSION['admin_id']; // Get admin ID from session

try {
    // Use the existing PDO instance from config.php
    global $pdo;

    // Prepare and execute the SQL statement
    $sql = "SELECT email FROM tbl_admin WHERE admin_id = :admin_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':admin_id', $admin_id, PDO::PARAM_INT);
    $stmt->execute();
    
    // Fetch the result
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if a result was found
    if ($result) {
        $email = strtoupper($result['email']);
    } else {
        // Handle the case where no data is found
        $email = "UNKNOWN";
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
    <title>eSAS - Dashboard</title>
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
        

        @media (max-width: 768px) {
            .col-auto {
                width: auto;
            }
            .icon-style {
                width: 6% !important;
            }
        }

    </style>
</head>
<body>
    
    <div class="container-fluid">
        <div class="row g-0 h-100">
            <nav class="navbar navbar-expand-lg navbar-dark bg-primary px-2">
                <a class="navbar-brand ps-2" href="#">
                    <img src="../../assets/img/SAS_LOGO.png" style="height: 0.3in;">
                    eSAS - Admin</a>
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
                        <a href="../../esas_admin/public/dashboard.php" class="nav-link left-sidebar text-dark" id="dashboard">
                            <i class="fas fa-chart-line"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="../../esas_admin/public/all_clubs.php" class="nav-link left-sidebar text-dark" id="all-clubs">
                            <i class="fas fa-university"></i> All Clubs
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="../../esas_admin/public/moderators.php" class="nav-link left-sidebar text-dark" id="moderators">
                            <i class="fa fa-user-shield"></i> Moderators
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="../../esas_admin/public/students.php" class="nav-link left-sidebar text-dark" id="students">
                            <i class="fas fa-users"></i> Students
                        </a>
                    </li>
                    <li>
                        <a href="../../esas_admin/public/club_requests.php" class="nav-link left-sidebar text-dark" id="club-requests">
                            <i class="fas fa-envelope"></i> Club Requests
                        </a>
                    </li>
                    <li>
                        <a href="../../esas_admin/public/reports.php" class="nav-link left-sidebar text-dark active" aria-current="page" id="reports">
                            <i class="fas fa-file-alt"></i> Reports
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

            <!-- THE MAIN PAGE START -->
            <div class="card p-2">

                <!-- ALL STUDENT TABLE START -->
                <div class="row card-row1 col-md-12 mb-1" style="border: 1px solid transparent; margin: 0;">
                    
                    <div class="mt-1 mb-3 d-flex justify-content-between align-items-center">
                        <h4 class="text-muted mb-0">Reports</h4>
                    </div>

                    <!-- SAMPLE REPORTS HERE -->


                    <!-- Report Filters -->
<div class="row mb-3">
    <div class="col-md-4">
        <select id="statusFilter" class="form-control">
            <option value="">All Statuses</option>
            <option value="approved">Approved</option>
            <option value="pending">Pending</option>
            <option value="disapproved">Disapproved</option>
        </select>
    </div>
    <div class="col-md-4">
        <input type="text" id="startDate" class="form-control" placeholder="Start Date" onfocus="(this.type='date')">
    </div>
    <div class="col-md-4">
        <input type="text" id="endDate" class="form-control" placeholder="End Date" onfocus="(this.type='date')">
    </div>
</div>
<button id="generateReport" class="btn btn-primary">Generate Report</button>

<!-- Report Table -->
<table class="table table-bordered" id="reportTable">
    <thead>
        <tr>
            <th>Request ID</th>
            <th>Club Name</th>
            <th>Description</th>
            <th>Activities</th>
            <th>Status</th>
            <th>Date Requested</th>
        </tr>
    </thead>
    <tbody>
        <!-- Dynamically populated report data goes here -->
    </tbody>
</table>

<script>
document.getElementById('generateReport').onclick = function() {
    const status = document.getElementById('statusFilter').value;
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;

    // Fetch report data based on filters
    fetch(`fetch_report.php?status=${status}&startDate=${startDate}&endDate=${endDate}`)
        .then(response => response.json())
        .then(data => {
            const tbody = document.querySelector('#reportTable tbody');
            tbody.innerHTML = ''; // Clear previous results
            data.forEach(item => {
                const row = `<tr>
                    <td>${item.request_id}</td>
                    <td>${item.clubName}</td>
                    <td>${item.description}</td>
                    <td>${item.activities}</td>
                    <td>${item.status}</td>
                    <td>${item.dateRequested}</td>
                </tr>`;
                tbody.innerHTML += row;
            });
        });
};
</script>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Request ID</th>
                                <th>Club Name</th>
                                <th>Description</th>
                                <th>Activities</th>
                                <th>Status</th>
                                <th>Cover Photo</th>
                                <th>Date Requested</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                            // Fetch data from tbl_club_requests
                            $sql = "SELECT request_id, clubName, description, activities, status, coverPhoto, dateRequested FROM tbl_club_requests";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute();
                            $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            if (count($requests) > 0):
                                foreach ($requests as $request): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($request['request_id']); ?></td>
                                        <td><?php echo htmlspecialchars($request['clubName']); ?></td>
                                        <td><?php echo htmlspecialchars($request['description']); ?></td>
                                        <td><?php echo htmlspecialchars($request['activities']); ?></td>
                                        <td><?php echo htmlspecialchars($request['status']); ?></td>
                                        <td>
                                            <?php if (!empty($request['coverPhoto'])): ?>
                                                <img src="/esas/esas_student/images/<?php echo htmlspecialchars($request['coverPhoto']); ?>" alt="Cover Photo" style="width: 100px;">
                                            <?php else: ?>
                                                No Image
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($request['dateRequested']); ?></td>
                                    </tr>
                                <?php endforeach; 
                            else: ?>
                                <tr>
                                    <td colspan="7" class="text-center">No club requests found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    <!-- END SAMPLE REPORTS -->

                </div>
                <!-- ALL STUDENT TABLE END -->

                <div id="noResultsMessage" class="alert alert-danger p-2 ps-3" style="display: none;">
                    <em>No results found.</em>
                </div>

            </div>
            <!-- THE MAIN PAGE END -->

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

        // Fetch clubs data from API
        fetch('/esas/esas_moderator/apis/clubs-api.php')
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
                                    <a href="/esas/esas_moderator/club_info.php?club_id=${club.club_id}&club_name=${encodeURIComponent(club.clubName)}">
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


        // JavaScript to Animate Cards
        document.addEventListener('DOMContentLoaded', function() {
            function animateCards(cards) { 
                cards.forEach((card, index) => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(5px) scale(0.95)'; // Adjusted Y translation
                    card.style.transition = 'none'; // Disable transition for reset

                    void card.offsetWidth; // Trigger reflow

                    card.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';
                    
                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0) scale(1)';
                        card.style.animation = `waveIn 0.6s ease-out forwards`;
                    }, index * 100); // Staggered delay
                });
            }

            // Select only the upper cards
            const upperCards = document.querySelectorAll('.card-row1 .card');
            animateCards(upperCards);
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