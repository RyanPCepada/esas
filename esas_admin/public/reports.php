<?php 
session_start();
require_once "../../config.php";  // Assuming this file holds your PDO connection

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

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
            <div class="col-12 col-md-2 pt-3 border-end">

            <div class="d-flex flex-column flex-shrink-0 px-2 bg-body-tertiary">
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
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <select id="reportType" class="form-control">
                                            <option value="">Select Report Type</option>
                                            <option value="all_clubs">All Clubs Records</option>
                                            <option value="all_moderators">All Moderators Records</option>
                                            <option value="student_profiles">Student Profiles</option>
                                            <option value="moderators_and_clubs_overview">Overview of Moderators and Clubs</option>
                                            <option value="students_and_clubs_overview">Overview of Students and Clubs</option>
                                            <option value="student_club_requests">Student Club Requests</option>
                                            <option value="student_registration_status">Student Registration Status</option>
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
        case 'all_clubs':
            reportTitle = "All Clubs Records";
            reportDescription = "This report provides a comprehensive overview of all registered clubs, including their names, cover photos, and dates of establishment.";
            break;
        case 'all_moderators':
            reportTitle = "All Moderators Records";
            reportDescription = "This report lists all moderators along with their personal details such as full name, gender, contact information, department, and the date they were assigned to their respective clubs.";
            break;
        case 'student_profiles':
            reportTitle = "Student Profiles";
            reportDescription = "This report contains detailed profiles of all students, including their student ID, full name, contact information, department, course, and academic year.";
            break;
        case 'moderators_and_clubs_overview':
            reportTitle = "Overview of Moderators and Clubs";
            reportDescription = "This report provides a summary of all moderators and their respective clubs, showcasing which moderators are assigned to which clubs.";
            break;
        case 'students_and_clubs_overview':
            reportTitle = "Overview of Students and Clubs";
            reportDescription = "This report gives an overview of club memberships, showing the number of students participating in each club along with relevant student information.";
            break;
        case 'student_club_requests':
            reportTitle = "Student Club Requests";
            reportDescription = "This report lists the clubs proposed by students, including their goals, objectives, and activities they plan to undertake.";
            break;
        case 'student_registration_status':
            reportTitle = "Student Registration Status";
            reportDescription = "This report shows the current registration status of students for different clubs, including whether they have been approved, disapproved, or are still pending.";
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