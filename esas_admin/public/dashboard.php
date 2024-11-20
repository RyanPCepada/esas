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
    <title>ESAS - Admin Dashboard</title>
    <link href="../../assets/css/jquery.dataTables.min.css" rel="stylesheet" />
    <script src="../../assets/js/all.js" crossorigin="anonymous"></script>
    <script src="../../assets/js/jquery-3.6.0.js"></script>
    <link href="../../assets/css/styles.css" rel="stylesheet" />
    <link href="../../assets/img/NBSC_LOGO.png" rel="icon">
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
        
        

        @keyframes waveIn {
            0% {
                opacity: 0;
                transform: translateY(5px) scale(0.95); /* Adjusted Y translation */
            }
            50% {
                opacity: 0.5;
                transform: translateY(-2px) scale(1.05); /* Peak of the wave, adjusted */
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .main-page {
            background-color: white;
            padding: 0px !important;
        }

        

        .icon-style {
            position: absolute;
            font-size: 18px;
            width: 11%;
            background-color: #4682B4; /*steel blue*/
            background-color: #89CFF0; /* baby blue */
            background-color: #70B9E6; /*darker baby blue*/
            color: white;
            /* color: #6A5ACD; slate blue */
            border-radius: 50%;
            align-self: end;
        }

        @media (max-width: 768px) {
            .col-auto {
                width: auto;
            }
            .icon-style {
                width: 7% !important;
            }
        }

        #pieChart {
            width: 90% !important;
            height: auto !important;
        }

        
        .card .auto-scroll {
            overflow-y: none; /* Enable vertical scrolling */
        }

        .card .auto-scroll::-webkit-scrollbar {
            width: 8px; /* Width of the scrollbar */
        }

        .card .auto-scroll::-webkit-scrollbar-thumb {
            /* background-color: rgba(211, 211, 211, 0.5); */
            background-color: rgba(211, 211, 211, 0.0);
            border-radius: 10px; /* Rounded edges */
        }

        
        .auto-scroll {
            overflow-y: none; /* Enable vertical scrolling */
        }

        .auto-scroll::-webkit-scrollbar {
            width: 8px; /* Width of the scrollbar */
        }

        .auto-scroll::-webkit-scrollbar-thumb {
            /* background-color: rgba(211, 211, 211, 0.5); */
            background-color: rgba(211, 211, 211, 0.0);
            border-radius: 10px; /* Rounded edges */
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
                        <a href="../../esas_admin/public/dashboard.php" class="nav-link left-sidebar text-dark active" aria-current="page" id="all-clubs">
                            <i class="fas fa-chart-line"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="../../esas_admin/public/all_clubs.php" class="nav-link left-sidebar text-dark" id="my-clubs">
                            <i class="fas fa-university"></i> All Clubs
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="../../esas_admin/public/moderators.php" class="nav-link left-sidebar text-dark" id="my-clubs">
                            <i class="fas fa-user-shield"></i> Moderators
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="../../esas_admin/public/students.php" class="nav-link left-sidebar text-dark" id="my-clubs">
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
                <div class="row g-0">
                    
                    <div class="row g-0 p-2 justify-content-center">

                        <!-- DROPDOWN START -->
                        <div class="row align-items-center mb-2">
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
                                        $latestYear = $years[0]['year'];
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
                                </select>
                            </div>

                            <script>
                                function filterDashboard() {
                                    var school_year = document.getElementById('schoolYearDropdown').value;
                                    var queryParams = new URLSearchParams(window.location.search);

                                    // Update the school_year parameter in the URL
                                    queryParams.set('school_year', school_year);

                                    // Navigate to the updated URL to refresh the page with new school year
                                    window.location.search = queryParams.toString();
                                    
                                    // Make sure the API call is also updated to include the school year
                                    fetchClubTrends(school_year);
                                }
                            </script>

                        </div>
                        <!-- DROPDOWN END -->

                        <!-- THE MAIN PAGE START -->
                        <div class="row main-page p-0">
                            
                            <!-- DASHBOARD COL-MD-9 START -->
                            <div class="row cards-charts-graphs col-md-9 m-0 p-0 pt-2" style="position: relative; z-index: 1;">
                                <!-- UPPER CARDS START -->
                                <div class="row card-row1 col-md-12 mb-1" style="border: 1px solid transparent; margin: 0;">
                                    <!-- Card for TOTAL CLUBS -->
                                    <div class="col-md-3 p-1" style="border: 1px solid transparent; padding: 0;">
                                        <div class="card p-2" style="margin: 0; box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);">
                                            <?php
                                            try {
                                                // Get the selected school year from the URL or default to the latest
                                                $selectedSchoolYear = isset($_GET['school_year']) ? $_GET['school_year'] : $defaultSchoolYear;

                                                // Base SQL query to count total clubs up to the end of the selected school year
                                                $sql = "SELECT COUNT(*) AS total_clubs FROM tbl_clubs";

                                                // If a school year is selected, adjust the SQL to count clubs cumulatively up to July of the end year
                                                if ($selectedSchoolYear) {
                                                    list($startYear, $endYear) = explode('-', $selectedSchoolYear);

                                                    // Define the end date for the school year (July 31 of the end year)
                                                    $endDate = "$endYear-07-31";

                                                    // Add the WHERE condition to count clubs added up to the end of the school year
                                                    $sql .= " WHERE dateAdded <= :endDate";
                                                }

                                                // Prepare and execute the query
                                                $stmt_clubs = $pdo->prepare($sql);

                                                // Bind the end date parameter if filtering by school year
                                                if ($selectedSchoolYear) {
                                                    $stmt_clubs->bindParam(':endDate', $endDate);
                                                }

                                                $stmt_clubs->execute();

                                                // Fetch and display the total number of clubs
                                                $total_clubs = $stmt_clubs->fetchColumn();
                                                echo "<h3>" . htmlspecialchars($total_clubs) . "</h3>";
                                            } catch (PDOException $e) {
                                                echo "Error: " . htmlspecialchars($e->getMessage());
                                            }
                                            ?>

                                            <i class="fas fa-university mt-2 me-2 p-2 icon-style"></i>
                                            <p class="mb-2 p-0">Total Clubs</p>
                                        </div>
                                    </div>

                                    <!-- Card for TOTAL MODERATORS -->
                                    <div class="col-md-3 p-1" style="border: 1px solid transparent; padding: 0;">
                                        <div class="card p-2" style="margin: 0; box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);">
                                            <?php
                                            try {
                                                // Get the selected school year from the URL
                                                $selectedSchoolYear = isset($_GET['school_year']) ? $_GET['school_year'] : null;

                                                // Base SQL query to count total moderators
                                                $sql = "SELECT COUNT(*) AS total_moderators FROM tbl_moderators";

                                                // If a school year is selected, count moderators up to the end of that school year (July 31 of the end year)
                                                if ($selectedSchoolYear) {
                                                    list($startYear, $endYear) = explode('-', $selectedSchoolYear);

                                                    // Define end date as July 31 of the end year
                                                    $endDate = "$endYear-07-31";

                                                    // Add the WHERE condition for dateAdded up to the end of the school year
                                                    $sql .= " WHERE dateAdded <= :endDate";
                                                }

                                                // Prepare and execute the query
                                                $stmt_moderators = $pdo->prepare($sql);

                                                // Bind the endDate parameter if filtering by school year
                                                if ($selectedSchoolYear) {
                                                    $stmt_moderators->bindParam(':endDate', $endDate);
                                                }

                                                $stmt_moderators->execute();

                                                // Fetch the total number of moderators
                                                $total_moderators = $stmt_moderators->fetchColumn();
                                                echo "<h3>" . htmlspecialchars($total_moderators) . "</h3>";
                                            } catch (PDOException $e) {
                                                echo "Error: " . htmlspecialchars($e->getMessage());
                                            }
                                            ?>
                                            <i class="fas fa-user-shield mt-2 me-2 p-2 icon-style"></i>
                                            <p class="mb-2 p-0">Total Moderators</p>
                                        </div>
                                    </div>


                                    <!-- Card for TOTAL STUDENTS -->
                                    <div class="col-md-3 p-1" style="border: 1px solid transparent; padding: 0;">
                                        <div class="card p-2" style="margin: 0; box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);">
                                            <?php
                                            try {
                                                // Get the selected school year from the URL
                                                $selectedSchoolYear = isset($_GET['school_year']) ? $_GET['school_year'] : null;

                                                // Base SQL query to count total active students
                                                $sql = "SELECT COUNT(DISTINCT student_id) AS total_students 
                                                        FROM tbl_application 
                                                        WHERE status = 'active'";

                                                // If a school year is selected, count students up to the end of that school year (July 31 of the end year)
                                                if ($selectedSchoolYear) {
                                                    list($startYear, $endYear) = explode('-', $selectedSchoolYear);

                                                    // Define end date as July 31 of the end year
                                                    $endDate = "$endYear-07-31";

                                                    // Add the WHERE condition for students active up to the end of the selected school year
                                                    $sql .= " AND dateDecided <= :endDate";
                                                }

                                                // Prepare and execute the query
                                                $stmt_students = $pdo->prepare($sql);

                                                // Bind the endDate parameter if filtering by school year
                                                if ($selectedSchoolYear) {
                                                    $stmt_students->bindParam(':endDate', $endDate);
                                                }

                                                $stmt_students->execute();

                                                // Fetch the total number of students
                                                $total_students = $stmt_students->fetchColumn();
                                                echo "<h3>" . htmlspecialchars($total_students) . "</h3>";
                                            } catch (PDOException $e) {
                                                echo "Error: " . htmlspecialchars($e->getMessage());
                                            }
                                            ?>
                                            <i class="fas fa-users mt-2 me-2 p-2 icon-style"></i>
                                            <p class="mb-2 p-0">Total Students</p>
                                        </div>
                                    </div>


                                    <!-- Card for CLUB REQUESTS -->
                                    <div class="col-md-3 p-1" style="border: 1px solid transparent; padding: 0;">
                                        <div class="card p-2" style="margin: 0; box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);">
                                            <?php
                                            try {
                                                // Get the selected school year from the URL
                                                $selectedSchoolYear = isset($_GET['school_year']) ? $_GET['school_year'] : null;

                                                // Base SQL query to count total club requests
                                                $sql = "SELECT COUNT(request_id) AS total_requests 
                                                        FROM tbl_club_requests";

                                                // If a school year is selected, count requests up to the end of that school year (July 31 of the end year)
                                                if ($selectedSchoolYear) {
                                                    list($startYear, $endYear) = explode('-', $selectedSchoolYear);

                                                    // Define end date as July 31 of the end year
                                                    $endDate = "$endYear-07-31";

                                                    // Add the WHERE condition for requests made up to the end of the selected school year
                                                    $sql .= " WHERE dateRequested <= :endDate";
                                                }

                                                // Prepare and execute the query
                                                $stmt_requests = $pdo->prepare($sql);

                                                // Bind the endDate parameter if filtering by school year
                                                if ($selectedSchoolYear) {
                                                    $stmt_requests->bindParam(':endDate', $endDate);
                                                }

                                                $stmt_requests->execute();

                                                // Fetch the total number of requests
                                                $total_requests = $stmt_requests->fetchColumn();
                                                echo "<h3>" . htmlspecialchars($total_requests) . "</h3>";
                                            } catch (PDOException $e) {
                                                echo "Error: " . htmlspecialchars($e->getMessage());
                                            }
                                            ?>
                                            <i class="fas fa-envelope mt-2 me-2 p-2 icon-style"></i>
                                            <p class="mb-2 p-0">Total Club Requests</p>
                                        </div>
                                    </div>


                                </div>
                                <!-- UPPER CARDS END -->

                                <!-- CHARTS AND DIAGRAMS START -->
                                <div class="row card-row2 col-md-12" style="border: 1px solid transparent; margin: 0;">

                                    <!-- PIE CHART -->
                                    <div class="col-md-6 p-1" style="border: 1px solid transparent; padding: 0;">
                                        <div class="card p-2 text-center" style="margin: 0; box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);">
                                            <p>Total Students per Club</p>
                                            <div style="height: auto; background-color: transparent;">
                                                <?php
                                                try {
                                                    // Your original PHP code for fetching the club data...
                                                    $selectedYear = isset($_GET['school_year']) ? intval($_GET['school_year']) : null;
                                                    $selectedMonth = isset($_GET['month']) ? intval($_GET['month']) : null;

                                                    $sql = "
                                                        SELECT tc.clubName, COUNT(tr.student_id) AS member_count
                                                        FROM tbl_application tr
                                                        JOIN tbl_clubs tc ON tr.club_id = tc.club_id
                                                        WHERE tr.status = 'active'
                                                    ";

                                                    if ($selectedYear) {
                                                        $endDate = ($selectedYear + 1) . "-05-31";
                                                        $sql .= " AND tr.dateDecided <= :endDate";
                                                    }

                                                    if ($selectedMonth) {
                                                        $sql .= " AND MONTH(tr.dateDecided) <= :month";
                                                    }

                                                    $sql .= " GROUP BY tc.club_id ORDER BY member_count DESC";

                                                    $stmt = $pdo->prepare($sql);

                                                    if ($selectedYear) {
                                                        $stmt->bindParam(':endDate', $endDate);
                                                    }

                                                    if ($selectedMonth) {
                                                        $stmt->bindParam(':month', $selectedMonth, PDO::PARAM_INT);
                                                    }

                                                    $stmt->execute();
                                                    $club_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                                } catch (PDOException $e) {
                                                    echo "Error: " . htmlspecialchars($e->getMessage());
                                                }

                                                $clubs = [];
                                                $counts = [];
                                                $total_students = 0;

                                                foreach ($club_data as $row) {
                                                    $clubs[] = $row['clubName'];
                                                    $counts[] = $row['member_count'];
                                                    $total_students += $row['member_count'];
                                                }

                                                $percentages = [];
                                                foreach ($counts as $count) {
                                                    $percentages[] = $total_students > 0 ? round(($count / $total_students) * 100) : 0;
                                                }

                                                $labels_with_percentages = [];
                                                foreach ($clubs as $index => $club) {
                                                    $labels_with_percentages[] = $percentages[$index] . '% ' . $club;
                                                }
                                                ?>
                                                <!-- Canvas for the pie chart -->
                                                <div style="display: flex; justify-content: center;">
                                                    <canvas id="pieChart"></canvas>
                                                </div>
                                                <p id="noDataMessage" style="display: none; text-align: center; font-size: 16px; color: red; margin-top: 35%;"><em>No students.</em></p>
                                                <!-- Custom Legends -->
                                                <div class="auto-scroll" id="customLegend" style="height: 245px; margin-top: 15px; text-align: center;"></div>

                                                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                                                <script>
                                                    const labelsWithPercentages = <?php echo json_encode($labels_with_percentages); ?>;
                                                    const counts = <?php echo json_encode($counts); ?>;
                                                    const ctx = document.getElementById('pieChart').getContext('2d');
                                                    const noDataMessage = document.getElementById('noDataMessage');
                                                    const customLegend = document.getElementById('customLegend');

                                                    // Generate 50 distinct colors for clubs
                                                    const backgroundColors = [
                                                        'rgba(65, 105, 225, 0.8)', 'rgba(255, 105, 180, 0.8)', 'rgba(255, 215, 0, 0.8)', 'rgba(0, 255, 255, 0.8)',
                                                        'rgba(255, 165, 0, 0.8)', 'rgba(0, 255, 0, 0.8)', 'rgba(138, 43, 226, 0.8)', 'rgba(255, 69, 0, 0.8)',
                                                        'rgba(139, 0, 0, 0.8)', 'rgba(0, 0, 139, 0.8)', 'rgba(144, 238, 144, 0.8)', 'rgba(32, 178, 170, 0.8)',
                                                        'rgba(218, 112, 214, 0.8)', 'rgba(255, 20, 147, 0.8)', 'rgba(255, 182, 193, 0.8)', 'rgba(210, 105, 30, 0.8)',
                                                        'rgba(255, 160, 122, 0.8)', 'rgba(70, 130, 180, 0.8)', 'rgba(189, 183, 107, 0.8)', 'rgba(0, 191, 255, 0.8)',
                                                        'rgba(255, 228, 181, 0.8)', 'rgba(152, 251, 152, 0.8)', 'rgba(100, 149, 237, 0.8)', 'rgba(233, 150, 122, 0.8)',
                                                        'rgba(255, 215, 0, 0.8)', 'rgba(186, 85, 211, 0.8)', 'rgba(128, 0, 128, 0.8)', 'rgba(255, 99, 71, 0.8)',
                                                        'rgba(250, 128, 114, 0.8)', 'rgba(95, 158, 160, 0.8)', 'rgba(123, 104, 238, 0.8)', 'rgba(255, 250, 205, 0.8)',
                                                        'rgba(72, 209, 204, 0.8)', 'rgba(175, 238, 238, 0.8)', 'rgba(173, 255, 47, 0.8)', 'rgba(124, 252, 0, 0.8)',
                                                        'rgba(240, 128, 128, 0.8)', 'rgba(255, 99, 132, 0.8)', 'rgba(54, 162, 235, 0.8)', 'rgba(255, 206, 86, 0.8)',
                                                        'rgba(75, 192, 192, 0.8)', 'rgba(153, 102, 255, 0.8)', 'rgba(255, 159, 64, 0.8)', 'rgba(199, 21, 133, 0.8)',
                                                        'rgba(255, 140, 0, 0.8)', 'rgba(255, 69, 0, 0.8)', 'rgba(34, 139, 34, 0.8)', 'rgba(106, 90, 205, 0.8)',
                                                        'rgba(127, 255, 212, 0.8)', 'rgba(240, 230, 140, 0.8)', 'rgba(189, 183, 107, 0.8)', 'rgba(255, 228, 225, 0.8)'
                                                    ];

                                                    // If there's no data, display the "No students" message
                                                    if (counts.length === 0 || labelsWithPercentages.length === 0) {
                                                        document.getElementById('pieChart').style.display = 'none';
                                                        noDataMessage.style.display = 'block';
                                                    } else {
                                                        // Initialize the pie chart
                                                        const pieChart = new Chart(ctx, {
                                                            type: 'pie',
                                                            data: {
                                                                labels: labelsWithPercentages,
                                                                datasets: [{
                                                                    data: counts,
                                                                    backgroundColor: backgroundColors.slice(0, counts.length),
                                                                    borderColor: backgroundColors.map(color => color.replace('0.8', '1')),
                                                                    borderWidth: 1
                                                                }]
                                                            },
                                                            options: {
                                                                responsive: true,
                                                                plugins: {
                                                                    legend: {
                                                                        display: false // Disable the default legend
                                                                    },
                                                                    tooltip: {
                                                                        callbacks: {
                                                                            label: function(tooltipItem) {
                                                                                return labelsWithPercentages[tooltipItem.dataIndex] + ': ' + counts[tooltipItem.dataIndex];
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        });

                                                        // Generate custom legends manually
                                                        let legendHtml = '';
                                                        labelsWithPercentages.forEach((label, index) => {
                                                            legendHtml += `
                                                                <span style="display: inline-block; margin-right: 10px;">
                                                                    <span style="display: inline-block; width: 12px; height: 12px; background-color: ${backgroundColors[index]}; margin-right: 5px;"></span>
                                                                    ${label}
                                                                </span>`;
                                                        });

                                                        customLegend.innerHTML = legendHtml;
                                                    }
                                                </script>

                                            </div>
                                        </div>
                                    </div>
                                    <!-- PIE CHART END -->

                                    <!-- OTHER CHARTS -->
                                    <div class="col-md-6" style="border: 1px solid transparent; padding: 0;">

                                        <div class="row" style="border: 1px solid transparent; margin: 0;">
                                            
                                            <!-- Application per SY -->
                                            <div class="col-md-12 p-1" style="border: 1px solid transparent; padding: 0;">
                                                <div class="card p-2 text-center" style="margin: 0; box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);">
                                                    <p>Total Application per SY</p>
                                                    <div style="height: 100%; width: 100%; background-color: transparent;">
                                                        <canvas id="applicationPerSYChart"></canvas>
                                                    </div>
                                                    <p id="noDataMessageSY" style="display: none; text-align: center; font-size: 16px; color: red; margin-top: 7%; margin-bottom: 14%;"><em>No students.</em></p>

                                                    <?php
                                                        try {
                                                            // Find the earliest and latest years with data in tbl_application
                                                            $sql = "SELECT 
                                                                        MIN(YEAR(dateDecided)) AS earliestYear, 
                                                                        MAX(YEAR(dateDecided)) AS latestYear 
                                                                    FROM tbl_application";
                                                            $stmt = $pdo->prepare($sql);
                                                            $stmt->execute();
                                                            $yearRange = $stmt->fetch(PDO::FETCH_ASSOC);

                                                            $earliestYear = $yearRange['earliestYear'];
                                                            $latestYear = $yearRange['latestYear'];

                                                            // Generate an array of all school years from earliest to latest
                                                            $allAcademicYears = [];
                                                            for ($year = $earliestYear; $year <= $latestYear; $year++) {
                                                                $allAcademicYears[] = $year . '-' . ($year + 1);
                                                            }

                                                            // Fetch actual data of active applications per academic year
                                                            $sql = "
                                                                SELECT 
                                                                    CONCAT(
                                                                        CASE 
                                                                            WHEN MONTH(r.dateDecided) >= 8 
                                                                            THEN YEAR(r.dateDecided) 
                                                                            ELSE YEAR(r.dateDecided) - 1 
                                                                        END, 
                                                                        '-', 
                                                                        CASE 
                                                                            WHEN MONTH(r.dateDecided) >= 8 
                                                                            THEN YEAR(r.dateDecided) + 1 
                                                                            ELSE YEAR(r.dateDecided) 
                                                                        END
                                                                    ) AS academicYear,
                                                                    COUNT(s.student_id) AS memberCount
                                                                FROM tbl_students s
                                                                JOIN tbl_application r ON s.student_id = r.student_id
                                                                WHERE r.status = 'active'
                                                                GROUP BY academicYear
                                                                ORDER BY academicYear ASC;
                                                            ";
                                                            $stmt = $pdo->prepare($sql);
                                                            $stmt->execute();
                                                            $applicationData = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                                            // Prepare data for chart by merging with all academic years
                                                            $academicYears = $allAcademicYears;
                                                            $memberCountsPerSY = array_fill(0, count($academicYears), 0);

                                                            // Map actual data to academic years, filling in 0 where no data
                                                            foreach ($applicationData as $row) {
                                                                $index = array_search($row['academicYear'], $academicYears);
                                                                if ($index !== false) {
                                                                    $memberCountsPerSY[$index] = $row['memberCount'];
                                                                }
                                                            }

                                                            // Get total number of students for chart scaling
                                                            $totalStudentsStmt = $pdo->prepare("SELECT COUNT(*) AS totalCount FROM tbl_students");
                                                            $totalStudentsStmt->execute();
                                                            $totalStudentsData = $totalStudentsStmt->fetch(PDO::FETCH_ASSOC);
                                                            $totalStudentCount = $totalStudentsData ? (int)$totalStudentsData['totalCount'] : 0;

                                                            function roundUpToEven($number) {
                                                                return $number % 2 === 0 ? $number : $number + 1;
                                                            }
                                                            // Fetch the maximum member count from the actual application data
                                                            $maxMemberCountFromData = max($memberCountsPerSY);

                                                            // Calculate buffered max member count for the chart (based on the highest actual data point)
                                                            $buffer = 10; // buffer percentage (you can adjust this if needed)
                                                            $maxMemberCountWithBuffer = roundUpToEven(ceil($maxMemberCountFromData * (1 + $buffer / 100)));

                                                        } catch (PDOException $e) {
                                                            echo "Error: " . htmlspecialchars($e->getMessage());
                                                        }
                                                    ?>


                                                    <!-- Include Chart.js -->
                                                    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

                                                    <script>
                                                        const academicYears = <?php echo json_encode($academicYears); ?>;
                                                        const memberCountsPerSY = <?php echo json_encode($memberCountsPerSY); ?>;
                                                        const maxMemberCountWithBuffer = <?php echo $maxMemberCountWithBuffer; ?>;

                                                        const hasDataSY = memberCountsPerSY.some(count => count > 0);
                                                        const applicationPerSYChartElement = document.getElementById('applicationPerSYChart');
                                                        const noDataMessageSY = document.getElementById('noDataMessageSY');

                                                        if (!hasDataSY) {
                                                            applicationPerSYChartElement.style.display = 'none';
                                                            noDataMessageSY.style.display = 'block';
                                                        } else {
                                                            const applicationPerSYData = {
                                                                labels: academicYears,
                                                                datasets: [{
                                                                    label: 'Application per SY',
                                                                    data: memberCountsPerSY,
                                                                    fill: true,
                                                                    borderColor: 'rgba(75, 192, 192, 1)',
                                                                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                                                    tension: 0.1
                                                                }]
                                                            };

                                                            const applicationPerSYConfig = {
                                                                type: 'line',
                                                                data: applicationPerSYData,
                                                                options: {
                                                                    scales: {
                                                                        x: {
                                                                            ticks: {
                                                                                maxRotation: 45,
                                                                                autoSkip: true
                                                                            }
                                                                        },
                                                                        y: {
                                                                            beginAtZero: true,
                                                                            max: maxMemberCountWithBuffer // Dynamically set the max value based on actual data
                                                                        }
                                                                    },
                                                                    plugins: {
                                                                        legend: {
                                                                            display: false
                                                                        }
                                                                    },
                                                                    responsive: true,
                                                                    maintainAspectRatio: false
                                                                }
                                                            };

                                                            const applicationPerSYChart = new Chart(applicationPerSYChartElement, applicationPerSYConfig);
                                                        }

                                                    </script>

                                                </div>
                                            </div>
                                            <!-- Application per SY END-->

                                        </div>

                                            
                                        <div class="row" style="border: 1px solid transparent; margin: 0;">
                                            <!-- Year Level Count -->
                                            <div class="col-md-12 p-1" style="border: 1px solid transparent; padding: 0;">
                                                <div class="card p-2 text-center" style="margin: 0; box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);">
                                                    <p>Total Year Level Count</p>
                                                    <div style="height: auto; background-color: transparent;">
                                                        <?php
                                                        try {
                                                            // Get the selected school year from the URL parameter or default to the current school year
                                                            $currentYear = date('Y');
                                                            $selectedSchoolYear = isset($_GET['school_year']) ? intval($_GET['school_year']) : $currentYear;
                                                            $nextSchoolYear = $selectedSchoolYear + 1;

                                                            // Define end date as July 31 of the next school year
                                                            $endDate = "$nextSchoolYear-07-31";

                                                            // SQL Query to fetch year level counts by school year
                                                            $sql = "
                                                                SELECT s.year, COUNT(DISTINCT s.student_id) AS count
                                                                FROM tbl_students s
                                                                JOIN tbl_application r ON s.student_id = r.student_id
                                                                WHERE r.status = 'active'
                                                                AND r.dateDecided <= :endDate
                                                                GROUP BY s.year
                                                            ";

                                                            $stmt = $pdo->prepare($sql);
                                                            $stmt->bindParam(':endDate', $endDate);
                                                            $stmt->execute();
                                                            
                                                            // Fetch the results into an associative array
                                                            $yearData = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                                            // Initialize arrays for years and counts
                                                            $years = ['1', '2', '3', '4']; // Ensure all years are included
                                                            $counts = [0, 0, 0, 0]; // Initialize with zeros

                                                            // Populate the counts array based on fetched data
                                                            foreach ($yearData as $row) {
                                                                $year = (int)$row['year']; // Ensure $year is an integer
                                                                $count = (int)$row['count']; // Ensure $count is an integer

                                                                // Check if $year is within the valid range
                                                                if ($year >= 1 && $year <= 4) {
                                                                    $counts[$year - 1] += $count; // Sum counts for each year
                                                                }
                                                            }

                                                            // SQL Query to get the total number of students
                                                            $sqlTotalStudents = "SELECT COUNT(DISTINCT student_id) AS total_student_count FROM tbl_students";
                                                            $stmtTotalStudents = $pdo->prepare($sqlTotalStudents);
                                                            $stmtTotalStudents->execute();
                                                            $totalStudentCount = (int)$stmtTotalStudents->fetchColumn();

                                                        } catch (PDOException $e) {
                                                            echo "Error: " . $e->getMessage();
                                                        }
                                                        ?>

                                                        <!-- Include Chart.js -->
                                                        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

                                                        <!-- Canvas for the bar chart -->
                                                        <div style="max-width: 100%; overflow: hidden;">
                                                            <canvas id="studentBarChart" style="max-width: 100%; height: 150px;"></canvas>
                                                        </div>
                                                        
                                                        <p id="noDataMessageYearLevels" style="display: none; text-align: center; font-size: 16px; color: red; margin-top: 14%; margin-bottom: 7%;"><em>No students.</em></p>

                                                        <script>
                                                            // PHP arrays passed into JavaScript
                                                            const labels = ['1st Year', '2nd Year', '3rd Year', '4th Year'];
                                                            const dataCounts = <?php echo json_encode($counts); ?>;
                                                            const totalStudentCount = <?php echo $totalStudentCount; ?>; // Get the total student count

                                                            // Check if there is no data to display
                                                            const hasDataYearLevels = dataCounts.some(count => count > 0);

                                                            // Show or hide the "No Data" message based on data availability
                                                            const studentBarChartElement = document.getElementById('studentBarChart');
                                                            const noDataMessageYearLevels = document.getElementById('noDataMessageYearLevels');

                                                            if (!hasDataYearLevels) {
                                                                studentBarChartElement.style.display = 'none';
                                                                noDataMessageYearLevels.style.display = 'block';
                                                            } else {
                                                                // Data for the chart
                                                                const data = {
                                                                    labels: labels,
                                                                    datasets: [{
                                                                        data: dataCounts, // Dynamic data from PHP
                                                                        backgroundColor: ['blue', 'orange', 'green', 'red'], // Colors for bars
                                                                    }]
                                                                };

                                                                const maxDataCount = Math.max(...dataCounts); // Get the highest count from dataCounts
                                                                const buffer = Math.ceil(maxDataCount * 0.1); // Calculate 10% buffer space

                                                                const config = {
                                                                    type: 'bar',
                                                                    data: data,
                                                                    options: {
                                                                        responsive: true,
                                                                        maintainAspectRatio: false,
                                                                        scales: {
                                                                            y: {
                                                                                beginAtZero: true,
                                                                                suggestedMax: maxDataCount + buffer, // Set max to highest data value plus buffer
                                                                                ticks: {
                                                                                    callback: function(value) {
                                                                                        return Number.isInteger(value) ? value : Math.floor(value); // Ensure no decimal points
                                                                                    },
                                                                                    stepSize: 1 // Force y-axis steps of 1 to avoid decimals
                                                                                }
                                                                            }
                                                                        },
                                                                        plugins: {
                                                                            legend: {
                                                                                display: false // Remove the legend
                                                                            }
                                                                        }
                                                                    }
                                                                };

                                                                // Render the chart
                                                                new Chart(studentBarChartElement, config);
                                                            }
                                                        </script>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- YEAR LEVEL COUNT END -->
                                        </div>




                                        <div class="row" style="border: 1px solid transparent; margin: 0;">
                                            <!-- Student Gender -->
                                            <div class="col-md-12 p-1" style="border: 1px solid transparent; padding: 0;">
                                                <div class="card p-2 text-center" style="margin: 0; box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);">
                                                    <p>Total Student Gender</p>
                                                    <div style="height: 150px; position: relative;">
                                                        <?php
                                                        try {
                                                            // Get the selected school year from the URL or default to the latest year
                                                            $selectedSchoolYear = isset($_GET['school_year']) ? $_GET['school_year'] : $defaultSchoolYear;

                                                            // Parse the selected school year to get the end year
                                                            list($startYear, $endYear) = explode('-', $selectedSchoolYear);
                                                            $endDate = "$endYear-07-31"; // End date is July 31 of the end year

                                                            // SQL Query to fetch cumulative gender counts, filtered by the selected school year
                                                            $sqlCounts = "
                                                                SELECT s.gender, COUNT(DISTINCT s.student_id) AS count
                                                                FROM tbl_students s
                                                                JOIN tbl_application r ON s.student_id = r.student_id
                                                                WHERE r.status = 'active' 
                                                                AND r.dateDecided <= :endDate
                                                                GROUP BY s.gender
                                                            ";

                                                            $stmtCounts = $pdo->prepare($sqlCounts);
                                                            $stmtCounts->bindParam(':endDate', $endDate);
                                                            $stmtCounts->execute();
                                                            $counts = $stmtCounts->fetchAll(PDO::FETCH_ASSOC);

                                                            // Initialize arrays for gender counts
                                                            $genderCounts = [
                                                                'Male' => 0,
                                                                'Female' => 0
                                                            ];

                                                            // Populate the counts array based on fetched data
                                                            foreach ($counts as $row) {
                                                                if (array_key_exists($row['gender'], $genderCounts)) {
                                                                    $genderCounts[$row['gender']] = $row['count'];
                                                                }
                                                            }

                                                            // Prepare the SQL statement to get the total number of students
                                                            $sqlTotal = "SELECT COUNT(DISTINCT student_id) AS total_count FROM tbl_students";
                                                            $stmtTotal = $pdo->prepare($sqlTotal);
                                                            $stmtTotal->execute();
                                                            $totalCount = $stmtTotal->fetchColumn();

                                                            // Prepare the data for JavaScript
                                                            $labels = array_keys($genderCounts);
                                                            $data = array_values($genderCounts);

                                                        } catch (PDOException $e) {
                                                            // Handle query error
                                                            echo 'Error: ' . htmlspecialchars($e->getMessage());
                                                        }
                                                        ?>
                                                        <canvas id="studentGenderChart" style="width: 100%; height: 100%;"></canvas>
                                                        <p id="noDataMessageGender" style="display: none; text-align: center; font-size: 16px; color: red; margin-top: 14%; margin-bottom: 7%;"><em>No students.</em></p>
                                                        <script>
                                                            document.addEventListener('DOMContentLoaded', function() {
                                                                const ctx = document.getElementById('studentGenderChart').getContext('2d');
                                                                const noDataMessageGender = document.getElementById('noDataMessageGender');

                                                                // Data from PHP
                                                                const labels = <?php echo json_encode($labels); ?>;
                                                                const dataCounts = <?php echo json_encode($data); ?>;
                                                                const totalCount = <?php echo $totalCount; ?>;

                                                                // Check if there is no data to display
                                                                const hasDataGender = dataCounts.some(count => count > 0);

                                                                if (!hasDataGender) {
                                                                    // Hide the chart and show the "No Data" message
                                                                    ctx.canvas.style.display = 'none';
                                                                    noDataMessageGender.style.display = 'block';
                                                                } else {
                                                                    // Data for the chart
                                                                    const data = {
                                                                        labels: labels,
                                                                        datasets: [{
                                                                            data: dataCounts,
                                                                            backgroundColor: ['#3498db', '#e74c3c'], // Male: blue, Female: red
                                                                            borderColor: ['#2980b9', '#c0392b'],
                                                                            borderWidth: 1
                                                                        }]
                                                                    };

                                                                    const maxDataCount = Math.max(...dataCounts); // Get the highest count from dataCounts
                                                                    const buffer = Math.ceil(maxDataCount * 0.1); // Calculate 10% buffer space

                                                                    const config = {
                                                                        type: 'bar',
                                                                        data: data,
                                                                        options: {
                                                                            responsive: true,
                                                                            maintainAspectRatio: false,
                                                                            scales: {
                                                                                y: {
                                                                                    beginAtZero: true,
                                                                                    suggestedMax: maxDataCount + buffer, // Set max to highest data value plus buffer
                                                                                    ticks: {
                                                                                        callback: function(value) {
                                                                                            return Number.isInteger(value) ? value : Math.floor(value); // Ensure no decimal points
                                                                                        },
                                                                                        stepSize: 1 // Force y-axis steps of 1 to avoid decimals
                                                                                    }
                                                                                }
                                                                            },
                                                                            plugins: {
                                                                                legend: {
                                                                                    display: false // Remove the legend
                                                                                }
                                                                            }
                                                                        }
                                                                    };

                                                                    // Render the chart
                                                                    new Chart(ctx, config);
                                                                }
                                                            });
                                                        </script>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Student Gender END -->


                                        </div>




                                        <!-- Vertically divided Year Level Count and Members per School Year -->
                                        <div class="row" style="border: 1px solid transparent; margin: 0;">

                                            <!-- (OLD ROW FOR THE YEAR LEVEL COUNT AND STUDENT GENDER) -->
                                        
                                        </div>
                                    </div>

                                </div>
                                <!-- CHARTS AND DIAGRAMS END -->

                            </div>
                            <!-- DASHBOARD COL-MD-9 END -->
                             
                            <!-- DASHBOARD COL-MD-4 START --> 
                            <div class="row trends-section col-md-3 m-0 p-0 pt-2 auto-scroll align-items-start justify-content-start" style="height: 785px; z-index: 2; position: relative;">
                                <!-- <h5 class="text-muted mt-1">Club Trends & Metrics</h5> -->
                                <div id="clubTrendsList" class="row align-items-start justify-content-start p-1" style="position: absolute; margin-top: 0px; left: 0; width: 100%;"></div>
                            </div>
                            <!-- DASHBOARD COL-MD-4 END -->



                        </div>
                        <!-- THE MAIN PAGE END -->


                        
                        <!-- THE MAIN PAGE 2 START -->
                        <div class="row main-page mt-3 p-0">

                            <!-- COL-MD-3 MOST ACTIVE CLUB START -->
                            <div class="most-active-club-section col-md-3 m-0 p-3" style="position: relative; z-index: 1;">
                                <p class="text-muted"><strong>Most Active</strong> <i class="fas fa-fire text-warning"></i></p>
                                <div class="auto-scroll" style="max-height: 500px;">
                                    <table class="table table-sm">
                                        <tbody>
                                            <?php
                                            $query = "SELECT c.clubName, COUNT(a.activity_id) AS activity_count
                                                    FROM tbl_activity_logs a
                                                    INNER JOIN tbl_clubs c ON a.club_id = c.club_id
                                                    WHERE a.club_id IS NOT NULL
                                                    GROUP BY c.clubName
                                                    ORDER BY activity_count DESC";
                                            $stmt = $pdo->query($query);
                                            $rank = 1;

                                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                echo "<tr>
                                                        <td>{$rank}</td>
                                                        <td>{$row['clubName']}</td>
                                                    </tr>";
                                                $rank++;
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- COL-MD-3 MOST ACTIVE CLUB END -->

                            <!-- COL-MD-3 MOST APPLIED CLUB START -->
                            <div class="most-applied-club-section col-md-3 m-0 p-3" style="position: relative; z-index: 1;">
                                <p class="text-muted"><strong>Most Applied</strong> <i class="fas fa-file-text text-secondary"></i></p>
                                <div class="auto-scroll" style="max-height: 500px;">
                                    <table class="table table-sm">
                                        <tbody>
                                            <?php
                                            $query = "SELECT c.clubName, COUNT(a.application_id) AS application_count
                                                    FROM tbl_application a
                                                    INNER JOIN tbl_clubs c ON a.club_id = c.club_id
                                                    GROUP BY c.clubName
                                                    ORDER BY application_count DESC";
                                            $stmt = $pdo->query($query);
                                            $rank = 1;

                                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                echo "<tr>
                                                        <td>{$rank}</td>
                                                        <td>{$row['clubName']}</td>
                                                    </tr>";
                                                $rank++;
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- COL-MD-3 MOST APPLIED CLUB END -->

                            <!-- COL-MD-3 HIGHEST MEMBERS START -->
                            <div class="highest-in-members-section col-md-3 m-0 p-3 auto-scroll" style="position: relative; z-index: 1;">
                                <p class="text-muted"><strong>Highest Members</strong> <i class="fas fa-users text-primary"></i></p>
                                <div class="auto-scroll" style="max-height: 500px;">
                                    <table class="table table-sm">
                                        <tbody>
                                            <?php
                                            $query = "SELECT c.clubName, COUNT(a.application_id) AS active_member_count
                                                    FROM tbl_application a
                                                    INNER JOIN tbl_clubs c ON a.club_id = c.club_id
                                                    WHERE a.status = 'active'
                                                    GROUP BY c.clubName
                                                    ORDER BY active_member_count DESC";
                                            $stmt = $pdo->query($query);
                                            $rank = 1;

                                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                echo "<tr>
                                                        <td>{$rank}</td>
                                                        <td>{$row['clubName']}</td>
                                                    </tr>";
                                                $rank++;
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- COL-MD-3 HIGHEST MEMBERS END -->

                            <!-- COL-MD-3 FASTEST GROWING CLUB START -->
                            <div class="fastest-growing-club-section col-md-3 m-0 p-3" style="position: relative; z-index: 1;">
                                <p class="text-muted"><strong>Fastest Growing</strong> <i class="fas fa-bolt text-warning"></i></p>
                                <div class="auto-scroll" style="max-height: 500px;">
                                    <table class="table table-sm">
                                        <tbody>
                                            <?php
                                            $query = "SELECT c.clubName,
                                                            (SELECT COUNT(a.application_id)
                                                            FROM tbl_application a
                                                            WHERE a.club_id = c.club_id AND a.status = 'active' AND YEAR(a.dateApplied) = YEAR(CURDATE())) AS current_year_members,
                                                            (SELECT COUNT(a.application_id)
                                                            FROM tbl_application a
                                                            WHERE a.club_id = c.club_id AND a.status = 'active' AND YEAR(a.dateApplied) = YEAR(CURDATE()) - 1) AS previous_year_members,
                                                            (SELECT COUNT(p.post_id)
                                                            FROM tbl_posts p
                                                            WHERE p.club_id = c.club_id AND YEAR(p.dateAdded) = YEAR(CURDATE())) AS current_year_posts,
                                                            (SELECT COUNT(p.post_id)
                                                            FROM tbl_posts p
                                                            WHERE p.club_id = c.club_id AND YEAR(p.dateAdded) = YEAR(CURDATE()) - 1) AS previous_year_posts
                                                    FROM tbl_clubs c
                                                    ORDER BY (current_year_members - previous_year_members) + 
                                                            (current_year_posts - previous_year_posts) DESC";
                                            $stmt = $pdo->query($query);
                                            $rank = 1;

                                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                echo "<tr>
                                                        <td>{$rank}</td>
                                                        <td>{$row['clubName']}</td>
                                                    </tr>";
                                                $rank++;
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- COL-MD-3 FASTEST GROWING CLUB END -->

                        </div>
                        <!-- THE MAIN PAGE 2 END -->




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

        // // Fetch clubs data from API
        // fetch('/esas/esas_moderator/apis/clubs-api.php')
        //     .then(response => {
        //         if (!response.ok) {
        //             throw new Error('Network response was not ok');
        //         }
        //         return response.json();
        //     })
        //     .then(data => {
        //         // $('#post-26').html(data)
        //         const allClubsContainer = document.getElementById('allClubsContainer');
        //         if (data && data.length > 0) {
        //             data.forEach(club => {
        //                 const memberText = club.membersCount === 1 ? '1 member' : `${club.membersCount} members`;
        //                 const cardHTML = `
        //                     <div class="col-md-4 card-container">
        //                         <div class="card card-img-only-all">
        //                             <small data-toggle="tooltip" title="${memberText}">
        //                                 <i class="fa fa-user mr-1"></i>${club.membersCount}
        //                             </small>
        //                             <a href="/esas/esas_moderator/club_info.php?club_id=${club.club_id}&club_name=${encodeURIComponent(club.clubName)}">
        //                                 <img src="/esas/esas_admin/images/${club.coverPhoto}" alt="Cover Photo">
        //                                 <div class="overlay-text">
        //                                     <h4>${club.clubName}</h4>
        //                                     <!--<div class="moderators-container">
        //                                         ${club.formattedModerators}
        //                                     </div>-->
        //                                 </div>
        //                             </a>
        //                         </div>
        //                     </div>
        //                 `;
        //                 allClubsContainer.innerHTML += cardHTML;
        //             });
        //         } else {
        //             allClubsContainer.innerHTML = '<p>No clubs found.</p>';
        //         }
        //     })
        //     .catch(error => {
        //         console.error('Error fetching clubs:', error);
        //         const allClubsContainer = document.getElementById('allClubsContainer');
        //         allClubsContainer.innerHTML = '<p>Failed to fetch clubs. Please try again later.</p>';
        //     });


    </script>




<script>


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

    // Wait until the club trends are fetched and rendered, then animate those cards
    const clubTrendsList = document.getElementById('clubTrendsList');
    
    // Ensure animateCards runs on .trends-card after data is added
    const trendsCards = clubTrendsList.querySelectorAll('.trends-card');
    animateCards(trendsCards);
});
</script>




<script>
document.addEventListener('DOMContentLoaded', function () {
    fetchClubTrends();
});

function fetchClubTrends() {
    const schoolYear = document.getElementById('schoolYearDropdown').value; // Get the selected school year
    fetch(`/esas/esas_admin/apis/fetch-all-trends-api.php?school_year=${encodeURIComponent(schoolYear)}`)
        .then(response => response.json())
        .then(data => {
            const clubTrendsList = document.getElementById('clubTrendsList');

            if (data.length > 0) {
                let html = '';
                data.forEach(club => {
                    html += `
                        <div class="card trends-card">
                            <div class="row trends-card-body">
                                <div class="col-3 d-flex justify-content-center align-items-start p-0 ps-2">
                                    <div class="circle-bar" title="Slot occupancy"> 
                                        <svg viewBox="0 0 36 36" class="circular-chart">
                                            <!-- Background circle -->
                                            <path class="circle-bg"
                                                d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" 
                                                fill="none" stroke="#FFFFFF" stroke-width="4"></path>

                                            <!-- Progress circle based on percentage -->
                                            ${club.percentage !== -1 ? `
                                                <path class="circle"
                                                    d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" 
                                                    fill="none" stroke="#007bff" stroke-width="4"
                                                    stroke-dasharray="${club.percentage}, 100"></path>
                                            ` : ''}
                                        </svg>
                                        <div class="circle-label">
                                            ${club.percentage !== -1 ? club.percentageText : club.percentageText}
                                        </div>
                                    </div> 
                                </div>
                                <div class="col-9">
                                    <div class="row ml-1">
                                        <strong><span class="card-title club-name mb-0 text-muted" title="${club.clubName}">${club.clubName}</span></strong>
                                    </div>
                                    <!-- <div class="row mt-1 px-2">
                                        <div class="card card-members col-md-4" title="Active and Departed Members This School Year">
                                            <div class="col-5 div-user-icon text-center">
                                                <i class="fas fa-user text-info"></i>
                                            </div>
                                            <div class="col-7 div-user-data text-center">
                                                <!-- <strong><p class="m-0" style="font-size: 10px; color: black;">
                                                    ${club.newlyActive !== 0 ? `+${club.newlyActive}` : club.newlyActive}
                                                </p></strong> -- >
                                                <strong><p class="" style="font-size: 10px; color: red; margin: -5px;">
                                                    <!-- ${club.newlyDeparted !== 0 ? `-${club.newlyDeparted}` : club.newlyDeparted} -- >
                                                    ${club.newlyDeparted !== 0 ? `-${club.newlyDeparted}` : club.newlyDeparted}
                                                </p></strong>
                                            </div>
                                        </div>
                                        <div class="card card-posts col-md-4" title="Posts per Week Compared to Previous School Year">
                                            <div class="col-5 div-user-icon text-center">
                                                <i class="fas fa-bullhorn text-info"></i>
                                            </div>
                                            <div class="col-7 div-user-data text-center">
                                                <strong>
                                                    <p class="m-0" style="font-size: 10px; color: black;">
                                                        ${club.postPerWeek !== 0 ? `${club.postPerWeek}` : club.postPerWeek}
                                                    </p>
                                                </strong>
                                                <strong>
                                                    <p class="" style="font-size: 10px; 
                                                        color: ${club.postsChanges === 0 ? 'black' : (club.postsChanges > 0 ? 'blue' : 'red')}; 
                                                        margin: -5px;">
                                                        ${club.postsChanges !== 0 ? `${club.postsChanges}` : club.postsChanges}
                                                    </p>
                                                </strong>
                                            </div>
                                        </div>
                                        <div class="card card-events col-md-4" title="Events per Month Compared to Previous School Year">
                                            <div class="col-5 div-user-icon text-center">
                                                <i class="fas fa-calendar text-info"></i>
                                            </div>
                                            <div class="col-7 div-user-data text-center">
                                                <strong>
                                                    <p class="m-0" style="font-size: 10px; color: black;">
                                                        ${club.eventPerMonth !== 0 ? `${club.eventPerMonth}` : club.eventPerMonth}
                                                    </p>
                                                </strong>
                                                <strong>
                                                    <p class="" style="font-size: 10px; 
                                                        color: ${club.eventsChanges === 0 ? 'black' : (club.eventsChanges > 0 ? 'blue' : 'red')}; 
                                                        margin: -5px;">
                                                        ${club.eventsChanges !== 0 ? `${club.eventsChanges}` : club.eventsChanges}
                                                    </p>
                                                </strong>
                                            </div>
                                        </div>
                                    </div> -->
                                    <div class="row ml-1 mb-1">
                                        <div class="club-rating col-7" data-rating="${club.rating}" title="Club Rating">
                                            ${generateStars(club.rating)}
                                            <!-- rate: ${club.rating}/10 -->
                                        </div>
                                        <div class="club-status col-5" data-status="${club.status}" title="Active Status">
                                            <!-- <span class="status-dot" style="position: absolute; top: -10px; left: 0; color: red; font-size: 2em;">&#8226;</span>
                                            <span style="font-size: .9em;">${club.status}</span> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });
                clubTrendsList.innerHTML = html;
            } else {
                clubTrendsList.innerHTML = 'No clubs found.';
            }
        })
        .catch(error => {
            console.error('Error fetching club trends:', error);
            document.getElementById('clubTrendsList').innerHTML = 'Failed to load clubs.';
        });
}


function generateStars(rating) {
    // Convert rating to a number (ensure no string issues)
    const numRating = parseFloat(rating);
    
    // Create full stars, half stars, and empty stars
    let fullStars = Math.floor(numRating); // Full stars
    let halfStar = numRating % 1 >= 0.5 ? 1 : 0; // Half star if rating has decimal >= 0.5
    let emptyStars = 5 - (fullStars + halfStar); // Remaining are empty stars

    let starsHTML = '';

    // Check if the rating is 0, and if so, only display one empty star
    if (numRating === 0) {
        starsHTML += '<i class="far fa-star star-rating text-warning"></i>'; // One empty star
    } else {
        // Add full stars
        for (let i = 0; i < fullStars; i++) {
            starsHTML += '<i class="fas fa-star star-rating text-warning"></i>'; // Full star
        }

        // Add half star (if any)
        if (halfStar) {
            starsHTML += '<i class="fas fa-star-half-alt star-rating text-warning"></i>'; // Half star
        }

        // Add empty stars
        for (let i = 0; i < emptyStars; i++) {
            starsHTML += '<i class="far fa-star star-rating text-warning"></i>'; // Empty star
        }
    }

    return starsHTML;
}

</script>


<style>
    /* .trends-card {
        border-radius: 8px;
        overflow: hidden;
        color: white;
        text-shadow: 0 0 5px rgba(0, 0, 0, 0.7);
    } */

    .trends-card {
        margin-left: 10px;
        margin-bottom: 10px;
        padding: 8px;
        padding-bottom: 0px;
        background-color: #E9ECEF;
        background-color: #DEE2E6;
        background-color: white;
        background-color: #F1F3F5;
        border: none;
        border: #DEE2E6;
        border-radius: 10px;
        z-index: 2000 !important;
        box-shadow: 0 3px 3px rgba(0, 0, 0, 0.1);
        /* box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2), 0 6px 20px rgba(0, 0, 0, 0.1); */
    }

    .circle-bar {
        position: relative;
        width: 100%; /* Fixed width */
        /* height: 100%; */
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 8px;
        cursor: pointer;
        border-radius: 50%;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2), 0 6px 20px rgba(0, 0, 0, 0.1);
    }

    .circular-chart {
        width: 100%;
        height: 100%;
    }

    .circle-label {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 12px;
        font-weight: bold;
        text-align: center;
    }

    .circle-bg {
        stroke: #FFFFFF;
        /* stroke: lightblue; */
        stroke-width: 4;
    }
    .circle {
        stroke: #007bff;
        stroke-width: 4;
        stroke-linecap: round;
        animation: progress 1s ease-out forwards;
    }
    @keyframes progress {
        from {
            stroke-dasharray: 0 100;
        }
    }


    .club-name {
        white-space: nowrap;     /* Prevents text from wrapping to the next line */
        overflow: hidden;        /* Hides any text that overflows the container */
        text-overflow: ellipsis; /* Adds ellipsis (...) for overflow text */
        display: block;          /* Ensures the element is treated as a block-level element */
        width: 100%;             /* Ensures it respects the width of the column */
        cursor: pointer;
    }

    .card-members, .card-posts, .card-events {
        background-color: rgba(0, 0, 0, 0.8);
        background-color: white;
        margin: 0px 2px;
        padding: 0px;
        padding-bottom: 2px;
        width: 30%;
    }

    .div-user-data {
        position: absolute;
        right: 2px;
        cursor: pointer;
    }
    .club-rating {
        margin-right: 0px;
        padding-top: 2px;
        padding-right: 0px;
        padding-bottom: 2px;
        cursor: pointer;
    }
    .club-status {
        position: relative;
        padding-top: 2px;
        padding-left: 15px;
        padding-right: 0px;
        cursor: pointer;
    }
    .star-rating {
        font-size: 14px;
        font-size: 16px;
    }
</style>





</body>
</html>