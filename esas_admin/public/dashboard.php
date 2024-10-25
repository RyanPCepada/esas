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
    <title>eSAS - Admin Dashboard</title>
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

        .card {
            /* Ensure your card styles are here */
        }

        

        .icon-style {
            position: absolute;
            font-size: 18px;
            width: 8%;
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
                            <i class="fa fa-user-shield"></i> Moderators
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
                </ul>
            </div>

            </div>
            <!-- LEFT SIDEBAR END -->


            
            
            <!-- MAINPAGE BAR -->
            <div class="col-12 col-md-10 bg-lgrey auto-scroll">
                <div class="row g-0">
                    <div class="row g-0 p-4 px-2 pt-2">
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

                                    // Navigate to the updated URL
                                    window.location.search = queryParams.toString();
                                }
                            </script>

                        </div>

                        <!-- THE MAIN PAGE START -->
                        <div class="card p-2">
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
                                        <p>Total Clubs</p>
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
                                        <p>Total Moderators</p>
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
                                                    FROM tbl_registration 
                                                    WHERE status = 'active'";

                                            // If a school year is selected, count students up to the end of that school year (July 31 of the end year)
                                            if ($selectedSchoolYear) {
                                                list($startYear, $endYear) = explode('-', $selectedSchoolYear);

                                                // Define end date as July 31 of the end year
                                                $endDate = "$endYear-07-31";

                                                // Add the WHERE condition for students active up to the end of the selected school year
                                                $sql .= " AND dateApproved <= :endDate";
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
                                        <p>Total Students</p>
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
                                        <p>Total Club Requests</p>
                                    </div>
                                </div>


                            </div>
                            <!-- UPPER CARDS END -->

                            <!-- CHARTS AND DIAGRAMS START -->
                            <div class="row card-row2 col-12" style="border: 1px solid transparent; margin: 0;">

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
                                                    FROM tbl_registration tr
                                                    JOIN tbl_clubs tc ON tr.club_id = tc.club_id
                                                    WHERE tr.status = 'active'
                                                ";

                                                if ($selectedYear) {
                                                    $endDate = ($selectedYear + 1) . "-05-31";
                                                    $sql .= " AND tr.dateApproved <= :endDate";
                                                }

                                                if ($selectedMonth) {
                                                    $sql .= " AND MONTH(tr.dateApproved) <= :month";
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
                                            <div id="customLegend" style="margin-top: 15px; text-align: center;"></div>

                                            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                                            <script>
                                                const labelsWithPercentages = <?php echo json_encode($labels_with_percentages); ?>;
                                                const counts = <?php echo json_encode($counts); ?>;
                                                const ctx = document.getElementById('pieChart').getContext('2d');
                                                const noDataMessage = document.getElementById('noDataMessage');
                                                const customLegend = document.getElementById('customLegend');

                                                const backgroundColors = [
                                                    'rgba(65, 105, 225, 0.8)',   // Bright Royal Blue
                                                    'rgba(255, 105, 180, 0.8)',  // Hot Pink
                                                    'rgba(255, 215, 0, 0.8)',    // Gold
                                                    'rgba(0, 255, 255, 0.8)',    // Cyan
                                                    'rgba(255, 165, 0, 0.8)',    // Orange
                                                    'rgba(0, 255, 0, 0.8)'       // Lime Green
                                                ];

                                                if (counts.length === 0 || labelsWithPercentages.length === 0) {
                                                    document.getElementById('pieChart').style.display = 'none';
                                                    noDataMessage.style.display = 'block';
                                                } else {
                                                    const pieChart = new Chart(ctx, {
                                                        type: 'pie',
                                                        data: {
                                                            labels: labelsWithPercentages,
                                                            datasets: [{
                                                                data: counts,
                                                                backgroundColor: backgroundColors,
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
                                        <!-- Registry per SY -->
                                        <div class="col-md-12 p-1" style="border: 1px solid transparent; padding: 0;">
                                            <div class="card p-2 text-center" style="margin: 0; box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);">
                                                <p>Total Registry per SY</p>
                                                <div style="height: 100%; width: 100%; background-color: transparent;">
                                                    <canvas id="registryPerSYChart"></canvas>
                                                </div>
                                                <p id="noDataMessageSY" style="display: none; text-align: center; font-size: 16px; color: red; margin-top: 7%; margin-bottom: 14%;"><em>No students.</em></p>

                                                <?php
try {
    // Fetch the count of members per academic year
    $sql = "
        SELECT 
            CONCAT(
                CASE 
                    WHEN MONTH(r.dateApproved) >= 8 
                    THEN YEAR(r.dateApproved) 
                    ELSE YEAR(r.dateApproved) - 1 
                END, 
                '-', 
                CASE 
                    WHEN MONTH(r.dateApproved) >= 8 
                    THEN YEAR(r.dateApproved) + 1 
                    ELSE YEAR(r.dateApproved) 
                END
            ) AS academicYear,
            COUNT(s.student_id) AS memberCount -- Count the number of students for each academic year
        FROM tbl_students s
        JOIN tbl_registration r ON s.student_id = r.student_id
        WHERE r.status = 'active'
        GROUP BY academicYear -- Group by academic year
        ORDER BY academicYear ASC;
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $registryPerSYData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Initialize arrays for chart data
    $academicYears = [];
    $memberCountsPerSY = [];

    // Populate the arrays with data
    foreach ($registryPerSYData as $row) {
        $academicYears[] = $row['academicYear'];  // Just the academic year
        $memberCountsPerSY[] = $row['memberCount'];  // Number of members for each academic year
    }

    // Fetch the total number of students for max value
    $totalStudentsStmt = $pdo->prepare("SELECT COUNT(*) AS totalCount FROM tbl_students");
    $totalStudentsStmt->execute();
    $totalStudentsData = $totalStudentsStmt->fetch(PDO::FETCH_ASSOC);
    $totalStudentCount = $totalStudentsData ? (int)$totalStudentsData['totalCount'] : 0;

    // Function to round up to the nearest even number
    function roundUpToEven($number) {
        return $number % 2 === 0 ? $number : $number + 1;
    }

    // Adjust max value to nearest even number
    $maxMemberCount = roundUpToEven($totalStudentCount);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

                                                <!-- Include Chart.js -->
                                                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

                                                <script>
                                                    // PHP arrays passed into JavaScript
                                                    const academicYears = <?php echo json_encode($academicYears); ?>;
                                                    const memberCountsPerSY = <?php echo json_encode($memberCountsPerSY); ?>;
                                                    const maxMemberCount = <?php echo $maxMemberCount; ?>;

                                                    // Check if there is no data to display
                                                    const hasDataSY = memberCountsPerSY.some(count => count > 0);

                                                    // Show or hide the "No Data" message based on data availability
                                                    const registryPerSYChartElement = document.getElementById('registryPerSYChart');
                                                    const noDataMessageSY = document.getElementById('noDataMessageSY');

                                                    if (!hasDataSY) {
                                                        registryPerSYChartElement.style.display = 'none';
                                                        noDataMessageSY.style.display = 'block';
                                                    } else {
                                                        // Data for the chart
                                                        const registryPerSYData = {
                                                            labels: academicYears,
                                                            datasets: [{
                                                                label: 'Registry per SY',
                                                                data: memberCountsPerSY,
                                                                fill: true,
                                                                borderColor: 'rgba(75, 192, 192, 1)',
                                                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                                                tension: 0.1
                                                            }]
                                                        };

                                                        // Configuration for the chart
                                                        const registryPerSYConfig = {
                                                            type: 'line',
                                                            data: registryPerSYData,
                                                            options: {
                                                                scales: {
                                                                    x: {
                                                                        ticks: {
                                                                            maxRotation: 45, // Rotate x-axis labels for better readability
                                                                            autoSkip: true // Automatically skip labels if too many
                                                                        }
                                                                    },
                                                                    y: {
                                                                        beginAtZero: true,
                                                                        max: maxMemberCount // Adjust max count dynamically to even number
                                                                    }
                                                                },
                                                                plugins: {
                                                                    legend: {
                                                                        display: false // Disable the legend to remove label
                                                                    }
                                                                },
                                                                responsive: true,
                                                                maintainAspectRatio: false // Allow chart to fill container width
                                                            }
                                                        };

                                                        // Render the chart
                                                        const registryPerSYChart = new Chart(registryPerSYChartElement, registryPerSYConfig);
                                                    }
                                                </script>
                                            </div>
                                        </div>
                                        <!-- Registry per SY END-->
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
                                                            JOIN tbl_registration r ON s.student_id = r.student_id
                                                            WHERE r.status = 'active'
                                                            AND r.dateApproved <= :endDate
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

                                                            // Configurations for the chart with total student count as the peak limit
                                                            const config = {
                                                                type: 'bar',
                                                                data: data,
                                                                options: {
                                                                    responsive: true, // Ensure the chart resizes with the container
                                                                    maintainAspectRatio: false, // Allow chart to adjust height and width dynamically
                                                                    scales: {
                                                                        y: {
                                                                            beginAtZero: true,
                                                                            suggestedMax: totalStudentCount // Set the total student count as the maximum y-axis value
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
                                                            JOIN tbl_registration r ON s.student_id = r.student_id
                                                            WHERE r.status = 'active' 
                                                            AND r.dateApproved <= :endDate
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

                                                                // Configurations for the chart
                                                                const config = {
                                                                    type: 'bar',
                                                                    data: data,
                                                                    options: {
                                                                        indexAxis: 'y', // Horizontal bar chart
                                                                        scales: {
                                                                            x: {
                                                                                beginAtZero: true,
                                                                                suggestedMax: totalCount // Set the maximum value to the total student count
                                                                            },
                                                                            y: {
                                                                                // Automatically handled by Chart.js for labels
                                                                            }
                                                                        },
                                                                        plugins: {
                                                                            legend: {
                                                                                display: false // Hide the legend
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