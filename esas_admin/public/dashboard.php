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
                </ul>
            </div>

            </div>
            <!-- LEFT SIDEBAR END -->


            
            
            <!-- MAINPAGE BAR -->
            <div class="col-12 col-md-10 bg-lgrey auto-scroll">
                <div class="row g-0 h-100">
                    <div class="row g-0 p-4 px-2 pt-2 h-100">
                        <div class="row align-items-center mb-2">
                            <label for="schoolYearDropdown" class="col-auto col-form-label">Month:</label>
                            <div class="col-auto">
                                <select id="schoolYearDropdown" class="form-select form-select-sm" style="width: 150px;" onchange="filterDashboard()">
                                    <?php
                                    // Define months
                                    $months = [
                                        '01' => 'January',
                                        '02' => 'February',
                                        '03' => 'March',
                                        '04' => 'April',
                                        '05' => 'May',
                                        '06' => 'June',
                                        '07' => 'July',
                                        '08' => 'August',
                                        '09' => 'September',
                                        '10' => 'October',
                                        '11' => 'November',
                                        '12' => 'December'
                                    ];

                                    // Get the current month
                                    $currentMonth = date('m');

                                    // Output month options
                                    foreach ($months as $key => $month) {
                                        echo "<option value=\"" . htmlspecialchars($key) . "\"";
                                        // Check if $_GET['school_year'] is set, otherwise default to the current month
                                        if ((isset($_GET['school_year']) && $_GET['school_year'] == $key) || (!isset($_GET['school_year']) && $key == $currentMonth)) {
                                            echo " selected";
                                        }
                                        echo ">" . htmlspecialchars($month) . "</option>";
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
                                            // Get the selected month from the URL
                                            $selectedMonth = isset($_GET['school_year']) ? intval($_GET['school_year']) : null;

                                            // Base SQL query to count total clubs
                                            $sql = "SELECT COUNT(*) AS total_clubs FROM tbl_clubs";

                                            // Add condition for the selected month if applicable
                                            if ($selectedMonth) {
                                                // Ensure clubs are counted if they were created or approved up to the selected month
                                                $sql .= " WHERE MONTH(dateAdded) <= :month";
                                            }

                                            // Prepare and execute the query
                                            $stmt_clubs = $pdo->prepare($sql);

                                            if ($selectedMonth) {
                                                $stmt_clubs->bindParam(':month', $selectedMonth, PDO::PARAM_INT);
                                            }

                                            $stmt_clubs->execute();

                                            // Fetch the total number of clubs
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
                                            // Get the selected month from the URL
                                            $selectedMonth = isset($_GET['school_year']) ? intval($_GET['school_year']) : null;

                                            // Base SQL query to count total moderators
                                            $sql = "SELECT COUNT(*) AS total_moderators FROM tbl_moderators";

                                            // Add condition for the selected month, if applicable
                                            if ($selectedMonth) {
                                                $sql .= " WHERE MONTH(dateAdded) <= :month";
                                            }

                                            // Prepare and execute the query
                                            $stmt_moderators = $pdo->prepare($sql);

                                            if ($selectedMonth) {
                                                $stmt_moderators->bindParam(':month', $selectedMonth, PDO::PARAM_INT);
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
                                            // Get the selected month from the URL
                                            $selectedMonth = isset($_GET['school_year']) ? intval($_GET['school_year']) : null;

                                            // Base SQL query to count total active students up to the selected month
                                            $sql = "
                                                SELECT COUNT(DISTINCT student_id) AS total_students 
                                                FROM tbl_registration 
                                                WHERE status = 'active'
                                                AND MONTH(dateApproved) <= :month
                                                AND YEAR(dateApproved) = YEAR(CURDATE())"; // Assuming we're filtering within the current year

                                            // Prepare and execute the query
                                            $stmt_students = $pdo->prepare($sql);
                                            
                                            if ($selectedMonth) {
                                                $stmt_students->bindParam(':month', $selectedMonth, PDO::PARAM_INT);
                                            } else {
                                                // Default to current month if no month is selected
                                                $currentMonth = date('m');
                                                $stmt_students->bindParam(':month', $currentMonth, PDO::PARAM_INT);
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
                                            // Get the selected month from the URL
                                            $selectedMonth = isset($_GET['school_year']) ? intval($_GET['school_year']) : null;

                                            // Base SQL query to count total club requests
                                            $sql = "
                                                SELECT COUNT(request_id) AS total_requests 
                                                FROM tbl_club_requests
                                            ";

                                            // Add condition for the selected month, if applicable
                                            if ($selectedMonth) {
                                                $sql .= " WHERE MONTH(dateRequested) <= :month";
                                            }

                                            // Prepare and execute the query
                                            $stmt_requests = $pdo->prepare($sql);

                                            if ($selectedMonth) {
                                                $stmt_requests->bindParam(':month', $selectedMonth, PDO::PARAM_INT);
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
                                <div class="col-md-5 p-1" style="border: 1px solid transparent; padding: 0;">
                                    <div class="card p-2 text-center" style="margin: 0; box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);">
                                        <p>Registered Students per Club</p>
                                        <div style="height: 365px; background-color: transparent;">
                                            <?php
                                            try {
                                                // Get the selected month from the URL
                                                $selectedMonth = isset($_GET['school_year']) ? intval($_GET['school_year']) : null;

                                                // Prepare the SQL query
                                                $sql = "
                                                    SELECT tc.clubName, COUNT(DISTINCT tr.student_id) AS member_count
                                                    FROM tbl_registration tr
                                                    JOIN tbl_clubs tc ON tr.club_id = tc.club_id
                                                    WHERE tr.status = 'active'
                                                ";

                                                // Add condition for the selected month, if applicable
                                                if ($selectedMonth) {
                                                    $sql .= " AND MONTH(tr.dateApproved) <= :month";
                                                }

                                                $sql .= " GROUP BY tc.club_id";

                                                // Prepare and execute the query
                                                $stmt = $pdo->prepare($sql);

                                                if ($selectedMonth) {
                                                    $stmt->bindParam(':month', $selectedMonth, PDO::PARAM_INT);
                                                }

                                                $stmt->execute();
                                                $club_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                            } catch (PDOException $e) {
                                                echo "Error: " . htmlspecialchars($e->getMessage());
                                            }

                                            // Prepare data for the pie chart
                                            $clubs = [];
                                            $counts = [];
                                            $total_students = 0;

                                            foreach ($club_data as $row) {
                                                $clubs[] = $row['clubName'];
                                                $counts[] = $row['member_count'];
                                                $total_students += $row['member_count'];
                                            }

                                            // Calculate percentage for each club
                                            $percentages = [];
                                            foreach ($counts as $count) {
                                                $percentages[] = $total_students > 0 ? round(($count / $total_students) * 100) : 0;
                                            }

                                            // Combine percentages and club names
                                            $labels_with_percentages = [];
                                            foreach ($clubs as $index => $club) {
                                                $labels_with_percentages[] = $percentages[$index] . '% ' . $club;
                                            }
                                            ?>
                                            <!-- Canvas for the pie chart -->
                                            <canvas id="pieChart" style="height: 100%;"></canvas>
                                            <p id="noDataMessage" style="display: none; text-align: center; font-size: 16px; color: red; margin-top: 35%;"><em>No students.</em></p>

                                            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                                            <script>
                                                // Fetching data from PHP arrays
                                                const labelsWithPercentages = <?php echo json_encode($labels_with_percentages); ?>;
                                                const counts = <?php echo json_encode($counts); ?>;

                                                const ctx = document.getElementById('pieChart').getContext('2d');
                                                const noDataMessage = document.getElementById('noDataMessage');
                                                
                                                // Check if there's any data to display
                                                if (counts.length === 0 || labelsWithPercentages.length === 0) {
                                                    // No data, hide the canvas and show the message
                                                    document.getElementById('pieChart').style.display = 'none';
                                                    noDataMessage.style.display = 'block';
                                                } else {
                                                    // Render Pie Chart using Chart.js
                                                    const pieChart = new Chart(ctx, {
                                                        type: 'pie',
                                                        data: {
                                                            labels: labelsWithPercentages,  // Club names with percentages
                                                            datasets: [{
                                                                label: 'Registered Students per Club',
                                                                data: counts,  // Member count per club
                                                                backgroundColor: [
                                                                    'rgba(65, 105, 225, 0.8)',   // Bright Royal Blue
                                                                    'rgba(255, 105, 180, 0.8)',   // Hot Pink
                                                                    'rgba(255, 215, 0, 0.8)',     // Gold
                                                                    'rgba(0, 255, 255, 0.8)',     // Cyan
                                                                    'rgba(255, 165, 0, 0.8)',     // Orange
                                                                    'rgba(0, 255, 0, 0.8)'        // Lime Green
                                                                ],
                                                                borderColor: [
                                                                    'rgba(65, 105, 225, 1)',     // Royal Blue border
                                                                    'rgba(255, 105, 180, 1)',     // Hot Pink border
                                                                    'rgba(255, 215, 0, 1)',       // Gold border
                                                                    'rgba(0, 255, 255, 1)',       // Cyan border
                                                                    'rgba(255, 165, 0, 1)',       // Orange border
                                                                    'rgba(0, 255, 0, 1)'          // Lime Green border
                                                                ],
                                                                borderWidth: 1
                                                            }]
                                                        },
                                                        options: {
                                                            responsive: true,
                                                            plugins: {
                                                                legend: {
                                                                    position: 'top',
                                                                    labels: {
                                                                        usePointStyle: true, // Use custom point style
                                                                        pointStyle: 'rect',  // Set point style to square
                                                                    }
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
                                                }
                                            </script>
                                        </div>
                                    </div>
                                </div>
                                <!-- PIE CHART END -->



                                <!-- OTHER CHARTS -->
                                <div class="col-md-7" style="border: 1px solid transparent; padding: 0;">
                                    <div class="row" style="border: 1px solid transparent; margin: 0;">

                                    
                                        <!-- Registry per SY -->
                                        <div class="col-md-12 p-1" style="border: 1px solid transparent; padding: 0;">
                                            <div class="card p-2 text-center" style="margin: 0; box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);">
                                                <p>Registry per SY</p>
                                                <div style="height: 100%; width: 100%; background-color: transparent;">
                                                    <canvas id="registryPerSYChart"></canvas>
                                                </div>
                                                <p id="noDataMessageSY" style="display: none; text-align: center; font-size: 16px; color: red; margin-top: 7%; margin-bottom: 14%;"><em>No students.</em></p>

                                                <?php
                                                try {
                                                    // Get the current academic year based on today's date
                                                    $currentYear = date('Y');
                                                    $currentSY = $currentYear . '-' . ($currentYear + 1);

                                                    // Get the selected club_id and month from the URL, if available
                                                    $club_id = isset($_GET['club_id']) ? intval($_GET['club_id']) : null;
                                                    $selectedMonth = isset($_GET['school_year']) ? intval($_GET['school_year']) : null; // Assuming 'school_year' is used for month

                                                    // SQL query to fetch the count of members per academic year
                                                    $sql = "
                                                        SELECT academicYear, COALESCE(memberCount, 0) AS memberCount
                                                        FROM (
                                                            SELECT CONCAT(YEAR(r.dateApproved), '-', YEAR(r.dateApproved) + 1) AS academicYear, COUNT(DISTINCT s.student_id) AS memberCount
                                                            FROM tbl_students s
                                                            JOIN tbl_registration r ON s.student_id = r.student_id
                                                            JOIN tbl_clubs c ON r.club_id = c.club_id
                                                            WHERE r.status = 'active'
                                                    ";

                                                    // Add condition for club_id if it's set
                                                    if ($club_id) {
                                                        $sql .= " AND r.club_id = :club_id";
                                                    }

                                                    // Add condition for month if it's set
                                                    if ($selectedMonth) {
                                                        $sql .= " AND MONTH(r.dateApproved) = :month";
                                                    }

                                                    $sql .= "
                                                            GROUP BY academicYear
                                                            UNION ALL
                                                            SELECT '2023-2024' AS academicYear, 0 AS memberCount
                                                            WHERE NOT EXISTS (
                                                                SELECT 1 FROM tbl_registration
                                                                WHERE CONCAT(YEAR(dateApproved), '-', YEAR(dateApproved) + 1) = '2023-2024'
                                                            )
                                                        ) AS yearlyData
                                                        WHERE academicYear = '2023-2024' OR academicYear = '$currentSY'
                                                        ORDER BY academicYear
                                                    ";

                                                    // Prepare the statement
                                                    $stmt = $pdo->prepare($sql);

                                                    // Bind club_id parameter if it's set
                                                    if ($club_id) {
                                                        $stmt->bindParam(':club_id', $club_id, PDO::PARAM_INT);
                                                    }

                                                    // Bind month parameter if it's set
                                                    if ($selectedMonth) {
                                                        $stmt->bindParam(':month', $selectedMonth, PDO::PARAM_INT);
                                                    }

                                                    $stmt->execute();
                                                    $registryPerSYData = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                                    // Initialize arrays for chart data
                                                    $academicYears = [];
                                                    $memberCountsPerSY = [];

                                                    // Populate the arrays with data
                                                    foreach ($registryPerSYData as $row) {
                                                        $academicYears[] = $row['academicYear'];  // Academic year
                                                        $memberCountsPerSY[] = $row['memberCount'];  // Number of members for each year
                                                    }

                                                    // Fetch the total number of students for the max value
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


                                    </div>




                                    <!-- Vertically divided Year Level Count and Members per School Year -->
                                    <div class="row" style="border: 1px solid transparent; margin: 0;">


                                        <!-- Year Level Count -->
                                        <div class="col-md-6 p-1" style="border: 1px solid transparent; padding: 0;">
                                            <div class="card p-2 text-center" style="margin: 0; box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);">
                                                <p>Year Level Count</p>
                                                <div style="height: 150px; background-color: transparent;">
                                                    <div>
                                                        <canvas id="studentBarChart"></canvas>
                                                    </div>
                                                    <p id="noDataMessageYearLevels" style="display: none; text-align: center; font-size: 16px; color: red; margin-top: 14%; margin-bottom: 7%;"><em>No students.</em></p>
                                                    
                                                    <?php
                                                    try {
                                                        // Get the selected month from the URL parameter or default to the current month
                                                        $selectedMonth = isset($_GET['school_year']) ? intval($_GET['school_year']) : date('m');

                                                        // SQL Query to fetch year level counts, aggregated up to the selected month
                                                        $sql = "
                                                            SELECT s.year, COUNT(DISTINCT s.student_id) AS count
                                                            FROM tbl_students s
                                                            JOIN tbl_registration r ON s.student_id = r.student_id
                                                            WHERE r.status = 'active'
                                                            AND MONTH(r.dateApproved) <= :selectedMonth
                                                            GROUP BY s.year
                                                        ";

                                                        $stmt = $pdo->prepare($sql);
                                                        $stmt->bindParam(':selectedMonth', $selectedMonth, PDO::PARAM_INT);
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

                                        <!-- Student Gender -->
                                        <div class="col-md-6 p-1" style="border: 1px solid transparent; padding: 0;">
                                            <div class="card p-2 text-center" style="margin: 0; box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);">
                                                <p>Student Gender</p>
                                                <div style="height: 150px; position: relative;">
                                                    <?php
                                                    try {
                                                        // Get the selected month from the URL parameter or default to the current month
                                                        $selectedMonth = isset($_GET['school_year']) ? intval($_GET['school_year']) : date('m');

                                                        // SQL Query to fetch gender counts, aggregated up to the selected month
                                                        $sqlCounts = "
                                                            SELECT s.gender, COUNT(DISTINCT s.student_id) AS count
                                                            FROM tbl_students s
                                                            JOIN tbl_registration r ON s.student_id = r.student_id
                                                            WHERE r.status = 'active' 
                                                            AND MONTH(r.dateApproved) <= :selectedMonth
                                                            AND YEAR(r.dateApproved) = YEAR(CURDATE()) 
                                                            GROUP BY s.gender
                                                        ";

                                                        $stmtCounts = $pdo->prepare($sqlCounts);
                                                        $stmtCounts->bindParam(':selectedMonth', $selectedMonth, PDO::PARAM_INT);
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
                                                        $sqlTotal = "SELECT COUNT(*) AS total_count FROM tbl_students";
                                                        $stmtTotal = $pdo->query($sqlTotal);
                                                        $totalCount = $stmtTotal->fetchColumn();

                                                        // Prepare the data for JavaScript
                                                        $labels = array_keys($genderCounts);
                                                        $data = array_values($genderCounts);

                                                    } catch (PDOException $e) {
                                                        // Handle query error
                                                        echo 'Error: ' . $e->getMessage();
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