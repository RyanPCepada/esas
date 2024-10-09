<?php 
session_start();
require_once "../../config.php";  // Assuming this file holds your PDO connection

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

if (!isset($_SESSION['moderator_id'])) {
    echo "Admin ID is not set in the session.";
    exit;
}

$moderator_id = $_SESSION['moderator_id']; // Get admin ID from session

try {
    // Use the existing PDO instance from config.php
    global $pdo;

    // Prepare and execute the SQL statement
    $sql = "SELECT email FROM tbl_moderators WHERE moderator_id = :moderator_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':moderator_id', $moderator_id, PDO::PARAM_INT);
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
                                    <div class="col-md-4">
                                        <select id="reportType" class="form-control">
                                            <option value="">Select Report Type</option>
                                            <option value="moderator_clubs_summary">Summary of Clubs You Manage</option>
                                            <option value="club_student_counts">Number of Students per Club</option>
                                            <option value="pending_approvals">Pending Club Requests</option>
                                            <option value="approved_disapproved_requests">Approved and Disapproved Requests</option>
                                            <option value="moderator_events">Events You Manage</option>
                                            <option value="pending_departure_requests">Pending Departure Requests</option>
                                            <option value="club_notifications">Club Notifications</option>
                                        </select>
                                    </div>

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

                                <div class="mt-1" id="reportContent">
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
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;

    if (!reportType) {
        alert('Please select a report type.');
        return;
    }

    // Dynamically generate title and description
    generateTitleAndDescription(reportType);

    // Fetch and display report data
    fetchReportData(reportType, startDate, endDate);
});

function generateTitleAndDescription(reportType) {
    let reportTitle = '';
    let reportDescription = '';

    switch (reportType) {
        case 'moderator_clubs_summary':
            reportTitle = "Summary of Clubs You Manage";
            reportDescription = "This report provides a summary of all clubs managed by you, including the total number of clubs and the students registered in each.";
            break;
        case 'club_student_counts':
            reportTitle = "Number of Students per Club";
            reportDescription = "This report shows the total number of students registered in each of your clubs.";
            break;
        case 'pending_approvals':
            reportTitle = "Pending Club Requests";
            reportDescription = "This report lists all the pending student registration requests in the clubs you manage.";
            break;
        case 'approved_disapproved_requests':
            reportTitle = "Approved and Disapproved Requests";
            reportDescription = "This report provides a summary of the approved and disapproved registration requests for your clubs.";
            break;
        case 'moderator_events':
            reportTitle = "Events Managed by You";
            reportDescription = "This report lists all the events managed by you.";
            break;
        case 'pending_departure_requests':
            reportTitle = "Pending Departure Requests";
            reportDescription = "This report lists all pending requests from students wanting to leave your clubs.";
            break;
        case 'club_notifications':
            reportTitle = "Club Notifications";
            reportDescription = "This report provides a summary of notifications related to your clubs.";
            break;
        default:
            reportTitle = "Report";
            reportDescription = "This is a dynamically generated report.";
    }

    document.getElementById('reportTitle').innerText = reportTitle;
    document.getElementById('reportDescription').innerText = reportDescription;
}
function fetchReportData(reportType, startDate, endDate) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../apis/fetch-report-api.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        if (this.status === 200) {
            document.getElementById('reportContent').innerHTML = this.responseText;
        }
    };

    xhr.send(`reportType=${reportType}&startDate=${startDate}&endDate=${endDate}`);
}
document.getElementById('generateReport').addEventListener('click', function () {
    const reportType = document.getElementById('reportType').value;
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;

    generateTitleAndDescription(reportType);
    fetchReportData(reportType, startDate, endDate);
});

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