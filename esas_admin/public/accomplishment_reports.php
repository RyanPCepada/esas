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

// Fetch club name
$sql_club = "SELECT clubName FROM tbl_clubs";
$stmt_club = $pdo->prepare($sql_club);
$club = $stmt_club->fetch(PDO::FETCH_ASSOC); 

// Fetch accomplishment reports for the student in this club
$sql = "SELECT * FROM tbl_accomplishment_reports ORDER BY dateAdded DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$reports = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
            padding: 15px;
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

        .report-item h5 {
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
                                    <a href="../public/crud/students/student_create.php" class="btn btn-danger disabled" style="visibility: hidden;">
                                        <i class="fa fa-plus"></i> Add New Student
                                    </a>
                                </div>

                                

                                
    <div class="container-fluid container mb-5 auto-scroll">
        <?php if (!empty($groupedReports)): ?> 
            <?php foreach ($groupedReports as $label => $reports): ?>
                <h5 class="mt-4 mb-4"><?php echo htmlspecialchars($label); ?></h5>
                
                <!-- <div class="row mt-5 m-0">
                    <div class="col-md-5 p-0"><hr></div>
                    <div class="col-md-2 text-center"><label>or</label></div>
                    <div class="col-md-5 p-0"><hr></div>
                </div> -->
                
                <div class="reports-list">
                    <?php foreach ($reports as $report): ?>
                        <div class="report-item" 
                            title="<?php echo htmlspecialchars($report['originalFileName']) . "\n" . date('m/d/Y h:i A', strtotime($report['dateAdded'])); ?>" 
                            onclick="window.open('/esas/esas_student/accomplishment_reports/<?php echo urlencode($report['accReportFile']); ?>', '_blank')">
                            <img src="/esas/esas_student/icons/ICON_PDF.png" alt="PDF Icon">
                            <h5 id="original-filename"><?php echo htmlspecialchars($report['originalFileName']); ?></h5>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-report">
                <p>No accomplishment reports found.</p>
                <i class="fas fa-file-pdf"></i>
            </div>
        <?php endif; ?>
                            
                            

                            </div>
                            <!-- ALL STUDENT TABLE END -->

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
        // Function to open report in a new tab
        function openTab(fileName) {
            window.open(fileName, '_blank');
        }
    </script>

</body>
</html>