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

//FOR VAV_MAIN FULL NAME PURPOSES
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
    <title>ESAS - Reports</title>
    <link href="../../assets/css/jquery.dataTables.min.css" rel="stylesheet" />
    <script src="../../assets/js/all.js" crossorigin="anonymous"></script>
    <script src="../../assets/js/jquery-3.6.0.js"></script>
    <link href="../../assets/css/styles.css" rel="stylesheet" />
    <link href="../../assets/img/NBSC_LOGO.png" rel="icon"> <!-- TAB LOGO -->
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
            width: auto;
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
                    <br>
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
                    <div class="row g-0 p-4 px-2 pt-2 h-100">

                        <!-- THE MAIN PAGE START --> 
                        <div class="card p-2">

                            <div class="row mb-2 p-2">
                                    <!-- Report Type Dropdown -->
                                    <label for="clubDropdown" class="col-auto col-form-label">Report Type:</label>
                                    <div class="col-md-2">
                                        <select id="reportType" class="form-select form-select-sm">
                                            <option value="">-- Select Report Type --</option>
                                            <option value="club_students_list">List of Students in Club</option>
                                            <option value="pending_approvals">Pending Club Application Approvals</option>
                                            <option value="disapproved_applications">Disapproved Student Applications</option>
                                            <option value="pending_departure_requests">Pending Departure Requests</option>
                                            <option value="upcoming_events">Upcoming Events</option>
                                        </select>
                                    </div>

                                    <!-- Club Dropdown -->
                                    <label for="clubDropdown" class="col-auto col-form-label">Club:</label>
                                    <div class="col-auto">
                                        <select id="clubDropdown" class="form-select form-select-sm" style="width: 150px;" onchange="filterDashboard()">
                                            <?php
                                            // Prepare the SQL query to fetch clubs managed by the current moderator
                                            $sql = "
                                                SELECT c.club_id, c.clubName 
                                                FROM tbl_clubs c
                                                JOIN tbl_clubs_and_moderators cm ON c.club_id = cm.club_id
                                                WHERE cm.moderator_id = :moderator_id
                                            ";

                                            // Execute the query
                                            $stmt = $pdo->prepare($sql);
                                            $stmt->execute(['moderator_id' => $moderator_id]);

                                            // Fetch the results
                                            $clubs = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                            // Change: Fetch the first club for default value
                                            $defaultClubId = $clubs[0]['club_id'] ?? null; // Use the first club ID if available

                                            // Generate the dropdown options
                                            foreach ($clubs as $club): ?>
                                                <option value="<?php echo htmlspecialchars($club['club_id']); ?>"
                                                    <?php if (isset($_GET['club_id']) && $_GET['club_id'] == $club['club_id']) echo 'selected'; ?>>
                                                    <?php echo htmlspecialchars($club['clubName']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <!-- Date Inputs and Buttons -->
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


                                    <div class="text-end col-md-3">
                                        <button id="generateReport" class="btn btn-primary">Generate Report</button>
                                        <button id="printReport" class="btn btn-secondary"><i class="fas fa-print"></i> Print</button>
                                    </div>

                            </div>


                            <!-- ALL STUDENT TABLE START -->
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

                                <div class="mt-3">
                                    <!-- Date will be dynamically added here -->
                                    <div id="currentDate"></div>
                                </div>

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
    const clubId = document.getElementById('clubDropdown').value; // Updated to use 'clubDropdown'
    const schoolYear = document.getElementById('schoolYearDropdown').value;

    // Validate that a report type, club, and school year are selected
    if (!reportType || !clubId || !schoolYear) {
        alert('Please select a report type, club, and school year.');
        return;
    }

    // Dynamically generate title and description
    generateTitleAndDescription(reportType);

    // Display the current date when report is generated
    const today = new Date();
    const currentDate = today.toLocaleDateString(); // Format the date as needed (e.g., 'MM/DD/YYYY')
    document.getElementById('currentDate').innerText = `Date: ${currentDate}`;

    // Fetch and display report data, pass school year
    fetchReportData(reportType, clubId, schoolYear); // Updated to pass 'schoolYear'
});

function fetchReportData(reportType, clubId, schoolYear) {
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

    // Send reportType, clubId, and schoolYear in the request
    xhr.send(`reportType=${reportType}&club_id=${clubId}&schoolYear=${schoolYear}`);
}


function generateTitleAndDescription(reportType) {
    let reportTitle = '';
    let reportDescription = '';

    // Get selected club name and school year
    const clubDropdown = document.getElementById('clubDropdown');
    const clubName = clubDropdown.options[clubDropdown.selectedIndex].text;
    const schoolYear = document.getElementById('schoolYearDropdown').value;

    // Check if 'All' is selected for the school year
    const schoolYearText = schoolYear === 'all' ? 'all School Years' : `School Year ${schoolYear}`;

    switch (reportType) {
        case 'club_students_list':
            reportTitle = `List of Students - ${clubName}`;
            reportDescription = `This report shows the basic information of all students registered in ${clubName} in ${schoolYearText}`;
            break;
        case 'pending_approvals':
            reportTitle = `Pending Club Application Approvals - ${clubName}`;
            reportDescription = `This report lists all basic information of students whose applications are pending for ${clubName} in ${schoolYearText}`;
            break;
        case 'disapproved_applications':
            reportTitle = `Disapproved Student Applications - ${clubName}`;
            reportDescription = `This report provides a summary of basic information of students whose applications were disapproved for ${clubName} in ${schoolYearText}`;
            break;
        case 'pending_departure_requests':
            reportTitle = `Pending Departure Requests - ${clubName}`;
            reportDescription = `This report lists all pending requests from students wanting to leave ${clubName} in ${schoolYearText}`;
            break;
        case 'upcoming_events':
            reportTitle = `Upcoming Events - ${clubName}`;
            reportDescription = `This report displays all upcoming events organized by ${clubName} in ${schoolYearText}`;
            break;
        default:
            reportTitle = `Report for ${clubName}`;
            reportDescription = `This is a dynamically generated report for ${clubName} in ${schoolYearText}`;
    }

    document.getElementById('reportTitle').innerText = reportTitle;
    document.getElementById('reportDescription').innerText = reportDescription;
}


// Print report functionality
document.getElementById('printReport').addEventListener('click', function () {
    // Preload the images
    const NBSCLogoPath = "../../assets/img/NBSC_LOGO.png"; // Update the path if necessary
    const SASLogoPath = "../../assets/img/SAS_LOGO.png"; // Update the path if necessary
    
    // Check if images are loaded before printing
    const preloadLogo = new Image();
    preloadLogo.src = NBSCLogoPath;

    const preloadSasLogo = new Image();
    preloadSasLogo.src = SASLogoPath;

    // Wait until both images are loaded
    Promise.all([imageLoad(preloadLogo), imageLoad(preloadSasLogo)]).then(function () {
        // Get the current date dynamically
        const today = new Date();
        const currentDate = today.toLocaleDateString();

        // Generate print content after images are loaded
        const reportTitle = document.getElementById('reportTitle').outerHTML;
        const reportDescription = document.getElementById('reportDescription').outerHTML;
        const reportContent = document.getElementById('reportContent').outerHTML;

        // Combine all sections into one printable format
        const printContent = `
            <div>
                <div style="display: flex; justify-content: center; align-items: center; width: 100%; margin-bottom: 50px;">
                    <img src="${NBSCLogoPath}" style="height: 1in; margin-right: 10px;">
                    <div style="text-align: center; line-height: 1.2; padding: 0;">
                        <div style="margin: 0;">Republic of the Philippines</div>
                        <div style="margin: 0;"><strong>NORTHERN BUKIDNON STATE COLLEGE</strong></div>
                        <div style="margin: 0;"><em>(Formerly Northern Bukidnon Community College)</em> R.A.11284</div>
                        <div style="margin: 0;">Manolo Fortich, 8703 Bukidnon • 535-3873 • <span class="text-primary">nbscadmin@nbsc.edu.ph</span></div>
                        <div style="margin: 0;">Creando futura, Transformationis vitae, Ductae a Deo</div>
                    </div>
                    <img src="${SASLogoPath}" style="height: .75in; margin-left: 10px;">
                </div>

                <!-- Add the current date to the printable content -->
                <div style="text-align: center; margin-bottom: 20px;">Date: ${currentDate}</div>

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
});

// Helper function to check if an image is loaded
function imageLoad(img) {
    return new Promise(function (resolve, reject) {
        img.onload = resolve;
        img.onerror = reject;
    });
}

</script>



</body>
</html>