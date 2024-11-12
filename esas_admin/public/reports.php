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
    <title>ESAS - Reports</title>
    <link href="../../assets/css/jquery.dataTables.min.css" rel="stylesheet" />
    <script src="../../assets/js/all.js" crossorigin="anonymous"></script>
    <script src="../../assets/js/jquery-3.6.0.js"></script>
    <link href="../../assets/css/styles.css" rel="stylesheet" />
    <link href="../../assets/img/nbsclogo.png" rel="icon"> <!-- TAB LOGO -->
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
                    ESAS - Admin</a>
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
                    <br>
                    Others
                    <li>
                        <a href="../../esas_admin/public/officers_charts.php" class="nav-link left-sidebar text-dark" id="officers_charts">
                            <i class="fas fa-user-tie"></i> CSG & SBO Officers
                        </a>
                    </li>
                    <li>
                        <a href="../../esas_admin/public/accomplishment_reports.php" class="nav-link left-sidebar text-dark" id="accomplishment_reports" 
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
        <div class="row g-0 p-4 px-2 pt-2 h-100">

            <!-- THE MAIN PAGE START -->
            <div class="card p-2">

                <div class="row mb-2 p-2">
                    <!-- Report Type Dropdown -->
                    <label for="reportType" class="col-auto col-form-label">Report Type:</label>
                    <div class="col-md-2">
                        <select id="reportType" class="form-select form-select-sm">
                            <option value="">-- Select Report Type --</option>
                            <option value="all_clubs">All Clubs Record</option>
                            <option value="all_moderators">All Moderators Record</option>
                            <option value="student_profiles">Student Profiles</option>
                            <option value="moderators_and_clubs_overview">Overview of Moderators and Clubs</option>
                            <option value="students_and_clubs_overview">Overview of Students and Clubs</option>
                            <option value="student_club_requests">Student Club Requests</option>
                            <option value="student_application_status">Student Application Status</option>
                        </select>
                    </div>


                    <!-- School Year Dropdown -->
                    <label for="schoolYearDropdown" class="col-auto col-form-label">School Year:</label>
                    <div class="col-auto">
                        <select id="schoolYearDropdown" class="form-select form-select-sm" style="width: 150px;" onchange="filterDashboard()">
                            <?php
                            try {
                                // Fetch distinct years where clubs were added
                                $sql = "SELECT DISTINCT YEAR(dateAdded) as year FROM tbl_clubs ORDER BY year DESC";
                                $stmt = $pdo->prepare($sql);
                                $stmt->execute();
                                $years = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                // Latest school year by default (if not set via URL)
                                $latestYear = $years[0]['year'] ?? null;
                                $defaultSchoolYear = $latestYear . '-' . ($latestYear + 1);

                                // Selected school year from the URL, or default to the latest school year
                                $selectedSchoolYear = isset($_GET['school_year']) ? $_GET['school_year'] : $defaultSchoolYear;

                                // Create school year ranges starting from August and ending in July next year
                                foreach ($years as $year) {
                                    $startYear = $year['year'];
                                    $endYear = $startYear + 1;
                                    $schoolYear = $startYear . '-' . $endYear;

                                    echo "<option value=\"" . htmlspecialchars($schoolYear) . "\"";
                                    if ($selectedSchoolYear === $schoolYear) {
                                        echo " selected";
                                    }
                                    echo ">" . htmlspecialchars($schoolYear) . "</option>";
                                }
                            } catch (PDOException $e) {
                                echo "Error: " . htmlspecialchars($e->getMessage());
                            }
                            ?>
                            <option value="all">All</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                    </div>

                    <!-- Buttons -->
                    <div class="text-end col-md-3">
                        <button id="generateReport" class="btn btn-primary">Generate Report</button>
                        <button id="printReport" class="btn btn-secondary"><i class="fas fa-print"></i> Print</button>
                    </div>
                </div>

                <!-- Report Content Table -->
                <div class="row card-row1 col-md-12 mb-1" style="border: 1px solid transparent; margin: 0;">
                    <!-- Report Title and Description -->
                    <table>
                        <tr>
                            <h3 class="label"><strong></strong></h3>
                            <h3 id="reportTitle"></h3>
                        </tr>
                        <tr>
                            <td class="label"><strong></strong></td>
                            <p id="reportDescription"></p>
                        </tr>
                    </table>

                    <div class="mt-3" id="reportContent">
                        <!-- Dynamically generated table will be inserted here -->
                    </div>
                </div>

                <!-- No Results Message -->
                <div id="noResultsMessage" class="alert alert-danger p-2 ps-3" style="display: none;">
                    <em>No results found.</em>
                </div>

            </div>
            <!-- THE MAIN PAGE END -->

        </div>
    </div>
</div>
<!-- MAINPAGE BAR END -->

<script src="../../assets/js/jquery.dataTables.min.js"></script>
<script src="../../assets/js/bootstrap.bundle.min.js"></script>
<script src="../../assets/js/global_script.js"></script>

<script>
document.getElementById('generateReport').addEventListener('click', function () {
    const reportType = document.getElementById('reportType').value;
    const schoolYear = document.getElementById('schoolYearDropdown').value;

    // Validate that a report type, and school year are selected
    if (!reportType || !schoolYear) {
        alert('Please select a report type and school year.');
        return;
    }

    // Dynamically generate title and description
    generateTitleAndDescription(reportType);

    // Fetch and display report data, pass school year
    fetchReportData(reportType, schoolYear); // Updated to pass 'schoolYear'
});

// HERE

function fetchReportData(reportType, schoolYear) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../apis/fetch-report-api.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        if (this.status === 200) {
            document.getElementById('reportContent').innerHTML = this.responseText;
        } else {
            document.getElementById('reportContent').innerHTML = "<div class='alert alert-danger'>Failed to load report data.</div>";
        }
    };

    // If 'All' is selected, send an empty string to the backend for school year
    if (schoolYear === 'all') {
        schoolYear = ''; // Send empty to fetch all records
    }

    // Send reportType, and schoolYear in the request
    xhr.send(`reportType=${reportType}&schoolYear=${schoolYear}`);
}

function generateTitleAndDescription(reportType) {
    let reportTitle = '';
    let reportDescription = '';

    // Get selected school year
    const schoolYear = document.getElementById('schoolYearDropdown').value;

    // Check if 'All' is selected for the school year
    const schoolYearText = schoolYear === 'all' ? 'in All School Years' : `SY ${schoolYear}`;

    switch (reportType) {
        case 'all_clubs':
            reportTitle = `All Clubs Record ${schoolYearText}`;
            reportDescription = "This report provides a comprehensive overview of all registered clubs, including their names and dates of establishment.";
            break;
        case 'all_moderators':
            reportTitle = "All Moderators Record";
            reportDescription = "This report lists all moderators along with their personal details such as full name, gender, contact information, department, and the date they were assigned to their respective clubs.";
            break;
        case 'student_profiles':
            reportTitle = "Student Profiles";
            reportDescription = "This report contains detailed profiles of all students, including their student ID, full name, contact information, department, course, and clubs.";
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
        case 'student_application_status':
            reportTitle = "Student Application Status";
            reportDescription = "This report shows the current application status of students for different clubs, including whether they have been approved, disapproved, or are still pending.";
            break;
        default:
            reportTitle = "Report";
            reportDescription = "This is a dynamically generated report.";
    }

    document.getElementById('reportTitle').innerText = reportTitle;
    document.getElementById('reportDescription').innerText = reportDescription;
}


// Print report functionality
document.getElementById('printReport').addEventListener('click', function () {
    // Select the report sections you want to print
    const reportTitle = document.getElementById('reportTitle').outerHTML;
    const reportDescription = document.getElementById('reportDescription').outerHTML;
    const reportContent = document.getElementById('reportContent').outerHTML;

    // Combine all sections into one printable format
    const printContent = `
        <div>
            <h2>${reportTitle}</h2>
            <p>${reportDescription}</p>
            ${reportContent}
        </div>
    `;

    const originalContent = document.body.innerHTML;

    // Set the body content to only the printable content
    document.body.innerHTML = printContent;

    // Trigger the print dialog
    window.print();

    // Restore the original content after printing
    document.body.innerHTML = originalContent;
});


</script>


</body>
</html>