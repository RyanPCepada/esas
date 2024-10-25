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

    // Fetch moderator email
    $sql = "SELECT email FROM tbl_moderators WHERE moderator_id = :moderator_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':moderator_id', $moderator_id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $email = strtoupper($result['email']);
    } else {
        $email = "UNKNOWN";
    }

    // Fetch clubs handled by the moderator using tbl_clubs_and_moderators table
    $clubSql = "SELECT c.club_id, c.clubName 
                FROM tbl_clubs c
                INNER JOIN tbl_clubs_and_moderators cm ON c.club_id = cm.club_id
                WHERE cm.moderator_id = :moderator_id";
    $clubStmt = $pdo->prepare($clubSql);
    $clubStmt->bindParam(':moderator_id', $moderator_id, PDO::PARAM_INT);
    $clubStmt->execute();
    $clubs = $clubStmt->fetchAll(PDO::FETCH_ASSOC);

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
    <title>eSAS - Reports</title>
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
        

        .label {
            width: 110px;
            text-align: left;
            padding-left: 15px;
            vertical-align: top;
        }

        @media (max-width: 768px) {
            .col-auto {
                width: auto;
            }
            .icon-style {
                width: 6% !important;
            }
        }

        @media print {
            /* Ensure all elements are visible when printing */
            #reportTitle, #reportDescription, #reportContent {
                display: block; /* Ensure these elements are displayed */
            }

            /* Hide the buttons during printing */
            #generateReport, #printReport {
                display: none;
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
            <div class="col-12 col-md-2 pt-3 border-end">

            <div class="d-flex flex-column flex-shrink-0 px-2 bg-body-tertiary">
                <ul class="nav nav-pills flex-column mb-auto">
                        <li>
                            <a href="../../esas_moderator/public/dashboard.php" class="nav-link left-sidebar text-dark" id="dashboard">
                                <i class="fas fa-chart-line"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../../esas_moderator/public/my_clubs.php" class="nav-link left-sidebar text-dark" id="my-clubs">
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
                        <a href="../../esas_moderator/public/reports.php" class="nav-link left-sidebar text-dark active" aria-current="page" id="reports">
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
                                <div class="row mb-3">
                                    <!-- Report Type Dropdown -->
                                    <div class="col-md-2">
                                        <select id="reportType" class="form-control">
                                            <option value="">-- Select Report Type --</option>
                                            <option value="club_students_list">List of Students in Club</option>
                                            <option value="pending_approvals">Pending Club Application Approvals</option>
                                            <option value="disapproved_applications">Disapproved Student Applications</option>
                                            <option value="pending_departure_requests">Pending Departure Requests</option>
                                            <option value="upcoming_events">Upcoming Events</option>
                                        </select>
                                    </div>

                                    <!-- Club Dropdown -->
                                    <div class="col-md-2">
                                        <select id="clubSelect" class="form-control">
                                            <option value="">-- Select Club --</option>
                                            <?php foreach ($clubs as $club): ?>
                                                <option value="<?= $club['club_id']; ?>"><?= $club['clubName']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <!-- Date Inputs and Buttons -->
                                    <div class="col-md-2">
                                        <input type="text" id="startDate" class="form-control" placeholder="Start Date" onfocus="(this.type='date')">
                                    </div>
                                    <div class="col-md-2">
                                        <input type="text" id="endDate" class="form-control" placeholder="End Date" onfocus="(this.type='date')">
                                    </div>
                                    <div class="text-end col-md-4">
                                        <button id="generateReport" class="btn btn-primary">Generate Report</button>
                                        <button id="printReport" class="btn btn-secondary"><i class="fas fa-print"></i> Print Report</button>
                                    </div>
                                </div>

                                <!-- Report Title and Description -->
                                <table>
                                    <tr>
                                        <td class="label"><strong>Report Title:</strong></td>
                                        <td id="reportTitle"></td>
                                    </tr>
                                    <tr>
                                        <td class="label"><strong>Description:</strong></td>
                                        <td id="reportDescription"></td>
                                    </tr>
                                </table>

                                <div class="mt-3" id="reportContent">
                                    <!-- Dynamically generated table will be inserted here -->
                                </div>
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
document.getElementById('generateReport').addEventListener('click', function () {
    const reportType = document.getElementById('reportType').value;
    const clubId = document.getElementById('clubSelect').value; // Get the selected club ID
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;

    // Validate that a report type, club, and date range are selected
    if (!reportType || !clubId) {
        alert('Please select a report type and a club.');
        return;
    }
    
    if (startDate && endDate && startDate > endDate) {
        alert('Start Date cannot be after End Date.');
        return;
    }

    // Dynamically generate title and description
    generateTitleAndDescription(reportType);

    // Fetch and display report data, pass start and end dates
    fetchReportData(reportType, clubId, startDate, endDate); // Pass the dates as well
});

function generateTitleAndDescription(reportType) {
    let reportTitle = '';
    let reportDescription = '';

    switch (reportType) {
        case 'club_students_list':
            reportTitle = "List of Students in Club";
            reportDescription = "This report shows the basic information of all students registered in this club.";
            break;
        case 'pending_approvals':
            reportTitle = "Pending Club Application Approvals";
            reportDescription = "This report lists all the basic information of students whose applications are pending for this club.";
            break;
        case 'disapproved_applications':
            reportTitle = "Disapproved Student Applications";
            reportDescription = "This report provides a summary of the basic information of students whose applications were disapproved for this club.";
            break;
        case 'pending_departure_requests':
            reportTitle = "Pending Departure Requests";
            reportDescription = "This report lists all pending requests from students wanting to leave this club.";
            break;
        case 'upcoming_events':
            reportTitle = "Upcoming Events";
            reportDescription = "This report displays all upcoming events organized by this club.";
            break;
        default:
            reportTitle = "Report";
            reportDescription = "This is a dynamically generated report for this club.";
    }

    document.getElementById('reportTitle').innerText = reportTitle;
    document.getElementById('reportDescription').innerText = reportDescription;
}


function fetchReportData(reportType, clubId, startDate, endDate) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../apis/fetch-report-api.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        if (this.status === 200) {
            document.getElementById('reportContent').innerHTML = this.responseText;
        }
    };

    // Send reportType, clubId, startDate, and endDate in the request
    xhr.send(`reportType=${reportType}&club_id=${clubId}&startDate=${startDate}&endDate=${endDate}`);
}

// Print report functionality
document.getElementById('printReport').addEventListener('click', function () {
    const printContent = document.getElementById('reportContent').innerHTML;
    const originalContent = document.body.innerHTML;

    document.body.innerHTML = printContent;
    window.print();
    document.body.innerHTML = originalContent;
});
</script>



</body>
</html>