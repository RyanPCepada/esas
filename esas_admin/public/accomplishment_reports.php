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

// Fetch all clubs
$sql_club = "SELECT club_id, clubName FROM tbl_clubs";
$stmt_club = $pdo->prepare($sql_club);
$stmt_club->execute();
$clubs = $stmt_club->fetchAll(PDO::FETCH_ASSOC);

// Fetch all accomplishment reports
$sql_reports = "SELECT * FROM tbl_accomplishment_reports ORDER BY dateAdded DESC";
$stmt_reports = $pdo->prepare($sql_reports);
$stmt_reports->execute();
$reports = $stmt_reports->fetchAll(PDO::FETCH_ASSOC);

// Function to label reports by date
function getDateLabel($date) {
    $today = new DateTime('today');
    $yesterday = new DateTime('yesterday');
    $dateObj = new DateTime($date);

    if ($dateObj >= $today) {
        return "Today";
    } elseif ($dateObj >= $yesterday) {
        return "Yesterday";
    } elseif ($dateObj >= new DateTime('last monday')) {
        return "Earlier This Week";
    } elseif ($dateObj >= new DateTime('last sunday -1 week')) {
        return "Last Week";
    } elseif ($dateObj->format('Y-m') === $today->format('Y-m')) {
        return "Earlier This Month";
    } elseif ($dateObj->format('Y-m') === $today->modify('-1 month')->format('Y-m')) {
        return "Last Month";
    } elseif ($dateObj->format('Y') === $today->format('Y')) {
        return $dateObj->format('F');
    } else {
        return "Last Year";
    }
}

// Group reports by their date label
$groupedReports = [];
foreach ($reports as $report) {
    $label = getDateLabel($report['dateAdded']);
    $groupedReports[$label][] = $report;
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
    <title>ESAS - Students List</title>
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

        
        body {
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        .wrapper {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 15px;
        }

        .container {
            height: auto;
            background-color: white;
            padding: 25px;
            padding-top: 0px;
        }

        .no-report {
            text-align: center;
            padding: 50px 0;
        }

        .no-report i {
            font-size: 50px;
            color: #ccc;
        }

        .btn-plus {
            font-size: 60px;
            color: #007bff;
            background-color: transparent;
            border: none;
            cursor: pointer;
        }

        .btn-plus:hover {
            color: #0056b3;
        }

        #fileIconPreview {
            width: 100px;
            height: auto;
            display: none;
        }

        .reports-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); /* Automatically adjust column count */
            gap: 10px;
            justify-content: center;
            /* Ensure reports will wrap to the next row if needed */
        }

        .report-item {
            background-color: #f9f9f9;
            padding: 10px;
            border: solid 1px lightgrey;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            text-align: center;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            width: 100%; /* Ensure full width within grid item */
        }
        
        .report-item:hover {
            background-color: #f1f1f1;
            border: solid 1px grey;
            cursor: pointer;
        }

        .report-item img {
            width: 100%;
            height: auto;
        }

        .report-item h6 {
            display: -webkit-box;
            -webkit-line-clamp: 3; /* Limit to 3 lines */
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: normal;
            line-height: 1.2em; /* Adjust line height for better fit */
            max-height: 3.6em; /* Ensure three full lines fit */
            overflow-wrap: break-word; /* Allows words to break cleanly */
        }

        .report-item .date {
            display: none; /* Hide date visually but keep in title */
        }

        h2 {
            margin-top: 48px;
        }

        /* Add slide-in animation for report-item */
        .report-item {
            opacity: 0;
            transform: translateX(20px); /* Start from the right */
            animation: slideInReport 0.6s forwards;
        }

        @keyframes slideInReport {
            from {
                opacity: 0;
                transform: translateX(20px); /* Start from the right */
            }
            to {
                opacity: 1;
                transform: translateX(0); /* End at normal position */
            }
        }

        @media (max-width: 767px) {
            h2 {
                margin-top: 5px;
            }

            .report-item {
                width: 140px;
                background-color: #f9f9f9;
                padding: 15px;
                border: solid 1px lightgrey;
                border-radius: 8px;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
                text-align: center;
                overflow: hidden;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
            }
            .reports-list {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
            }
            #original-filename {
                font-size: 14px;
            }
            h4 {
                font-size: 18px;
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
                        <a href="../../esas_admin/public/reports.php" class="nav-link left-sidebar text-dark" id="reports">
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
                        <a href="../../esas_admin/public/accomplishment_reports.php" class="nav-link left-sidebar text-dark active" aria-current="page" id="accomplishment_reports" 
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
                <!-- ALL STUDENT TABLE START -->
                <div class="row card-row1 col-md-12 mb-1" style="border: 1px solid transparent; margin: 0;">
                    <div class="mt-1 mb-3 d-flex justify-content-between align-items-center">
                        <h4 class="text-muted mb-0">Accomplishment Reports</h4>
                    </div>

                    <!-- Dropdown for clubs and search input -->
                    <table class="table table-bordered table-striped" style="background-color: #f9f9f9;">
                        <thead>
                            <tr>
                                <th colspan="9">
                                    <div class="row">
                                        <div class="col-12 col-md-8 d-flex align-items-center">
                                            <select id="clubSelect" class="form-select me-2" style="width: 20%;">
                                                <optgroup label="Select Club">
                                                    <option value="" selected>All</option>
                                                    <?php foreach ($clubs as $club): ?>
                                                        <option value="<?php echo htmlspecialchars($club['club_id']); ?>"><?php echo htmlspecialchars($club['clubName']); ?></option>
                                                    <?php endforeach; ?>
                                                </optgroup>
                                            </select>
                                            <input id="studentSearch" class="form-control" type="search" placeholder="Search reports here..." aria-label="Search">
                                        </div>
                                        <div class="col-12 col-md-4 d-flex align-items-center justify-content-center mt-2">
                                            <h6 id="rowCountDisplay">Showing 0 / 0 Records</h6> <!-- Updated row count display -->
                                        </div>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                    </table>

                    <!-- Display filtered reports -->
<div id="reportsDisplay">
    <?php if (!empty($groupedReports)): ?> 
        <?php foreach ($groupedReports as $label => $reports): ?>
            <div class="report-group">
                <h5 class="date-label mt-4 mb-4"><?php echo htmlspecialchars($label); ?> (<?php echo count($reports); ?> reports)</h5>
                <div class="reports-list">
                    <?php foreach ($reports as $report): ?>
                        <div class="report-item" data-club-id="<?php echo htmlspecialchars($report['club_id']); ?>" 
                             title="<?php echo htmlspecialchars($report['originalFileName']) . "\n" . date('m/d/Y h:i A', strtotime($report['dateAdded'])); ?>" 
                             onclick="window.open('/esas/esas_student/accomplishment_reports/<?php echo urlencode($report['accReportFile']); ?>', '_blank')">
                            <img src="/esas/esas_student/icons/ICON_PDF.png" alt="PDF Icon">
                            <h6 class="original-filename"><?php echo htmlspecialchars($report['originalFileName']); ?></h6>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="no-report">
            <p>No accomplishment reports found.</p>
            <i class="fas fa-file-pdf"></i>
        </div>
    <?php endif; ?>
</div>
                </div>
                <!-- ALL STUDENT TABLE END -->

                <div id="noResultsMessage" class="alert alert-danger p-2 ps-3" style="display: none;">
                    <em>No reports found for the selected club.</em>
                </div>
                
            </div>
            <!-- THE MAIN PAGE END -->
        </div>
    </div>
</div>
<!-- MAINPAGE BAR END -->

<script>
// Function to update the record count and hide labels with no matching reports
function updateReportsDisplay() {
    const searchQuery = document.getElementById('studentSearch').value.toLowerCase();
    const clubId = document.getElementById('clubSelect').value;
    const reportGroups = document.querySelectorAll('.report-group');
    let matchedCount = 0;
    let totalReportItems = 0;

    reportGroups.forEach(group => {
        const reportItemsInGroup = group.querySelectorAll('.report-item');
        let groupMatchedCount = 0; // Reset matched count per group for selected club
        let groupHasMatch = false;

        reportItemsInGroup.forEach(item => {
            const filename = item.querySelector('.original-filename').textContent.toLowerCase();
            const itemClubId = item.dataset.clubId;
            const isMatch = filename.includes(searchQuery) && (clubId === '' || itemClubId === clubId);

            if (isMatch) {
                item.style.display = 'block';
                matchedCount++;
                groupMatchedCount++;
                groupHasMatch = true;

                // Highlight search term in the filename
                const regex = new RegExp(searchQuery, 'gi');
                const highlightedText = filename.replace(regex, match => `<span class="highlight">${match}</span>`);
                item.querySelector('.original-filename').innerHTML = highlightedText;
            } else {
                item.style.display = 'none';
            }
        });

        // Update group label with the matched count of reports for the selected club only if there's a match
        const groupLabel = group.querySelector('.date-label');
        if (groupLabel) {
            const originalText = groupLabel.dataset.originalText || groupLabel.textContent.split(" (")[0];
            groupLabel.dataset.originalText = originalText; // Store original label text if not already set
            groupLabel.innerHTML = `${originalText} (${groupMatchedCount})`;
        }

        // Hide or show the label and report list for this group based on matching items
        group.style.display = groupHasMatch ? 'block' : 'none';
        totalReportItems += reportItemsInGroup.length;
    });

    // Update the record count
    document.getElementById('rowCountDisplay').textContent = `Showing ${matchedCount} / ${totalReportItems} Records`;

    // Show or hide the "No reports found" message
    const noResultsMessage = document.getElementById('noResultsMessage');
    noResultsMessage.style.display = matchedCount === 0 ? 'block' : 'none';
}

// Event listeners for dropdown and search input
document.getElementById('clubSelect').addEventListener('change', updateReportsDisplay);
document.getElementById('studentSearch').addEventListener('input', updateReportsDisplay);

// Store the original label text on page load
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.date-label').forEach(label => {
        label.dataset.originalText = label.textContent.split(" (")[0]; // Save original text without count
    });
    updateReportsDisplay(); // Initialize reports display
});

</script>

<style>
    .highlight {
        background-color: yellow;
        font-weight: bold;
    }
</style>


        </div>
    </div>

    <!-- <?php include 'assets/components/modals.php' ?> -->
    <script src="../../assets/js/jquery.dataTables.min.js"></script>
    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/js/global_script.js"></script>



    <script>

        // Function to animate report items
        function animateReportItems() {
            const reportItems = document.querySelectorAll('.report-item');
            reportItems.forEach((item, index) => {
                // Set a delay for each item based on its index
                item.style.animationDelay = `${index * 100}ms`; // Add delay for wave effect
            });
        }

        // Trigger animation when the page has fully loaded
        document.addEventListener('DOMContentLoaded', function () {
            animateReportItems(); // Animate all report items with delay
        });


        // Function to open report in a new tab
        function openTab(fileName) {
            window.open(fileName, '_blank');
        }
    </script>

</body>
</html>