<?php
session_start();
require_once "../config.php";

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

$student_id = $_SESSION['student_id'];

try {
    // Use the existing PDO instance from config.php
    global $pdo;

    // Prepare and execute the SQL statement
    $sql = "SELECT firstName, middleName, lastName FROM tbl_students WHERE student_id = :student_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
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

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>ESAS - Student Dashboard</title>
    <link href="../assets/css/jquery.dataTables.min.css" rel="stylesheet" />
    <script src="../assets/js/all.js" crossorigin="anonymous"></script>
    <script src="../assets/js/jquery-3.6.0.js"></script>
    <link href="../assets/css/styles.css" rel="stylesheet" />
    <link href="../assets/img/NBSC_LOGO.png" rel="icon">
    <style>
        .left-sidebar {
            font-size: 16px;
            text-align: start;
        }
        /* .nav-link:hover {
          background-color: #cce4ff !important;
        } */

        .nav-link.active {
          color: white !important;
          background-color: black;
        }
        .mainbar h2 {
            margin-left: 15px;
            margin-bottom: 32px;
        }
        .card-body {
            width: 100%;
            /* max-width: 600px; */
            margin: 0 auto;
            padding: 30px;
        }
        
        .card-img-only-all {
            position: relative;
            width: 295px;
            height: 166px;
            /* width: 330px;
            height: 188px; */
            border: solid 3px transparent;
            border: none;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 10px rgba(0, 0, 0, .5);
            margin: 10px auto;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card-img-only-all:hover {
            transform: scale(1.01);
            border: solid 3px transparent;
            border: none;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.6);
        }

        .card-img-only-all img {
            max-width: 100%; /* Image can scale down to fit container width */
            max-height: 100%; /* Image can scale down to fit container height */
            display: block;
            margin: auto; /* Center the image within the container */
            background: linear-gradient(to top right, rgba(0,0,0,0.6), rgba(255,255,255,0.2));
        }

        .card small {
            position: absolute;
            top: 5px;
            right: 5px;
            color: white;
            padding: 8px;
            font-size: 14px;
            cursor: pointer;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.6); /* Shadow effect */
        }
        .overlay-text {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 10px;
            background: linear-gradient(to top, rgba(0, 0, 0, .7), rgba(0, 0, 0, 0));
            color: white;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.6); /* Shadow effect */
            text-align: left;
            line-height: 1.2; /* Adjust line height for closer spacing */
        }
        .overlay-text h4 {
            margin: 7px;
            font-size: 25px;
            line-height: 1.1;
        }
        .overlay-text p {
            margin: 5px 0 0;
            font-size: 14px;
        }


        @media (max-width: 768px) {
            
            .mainbar {
                padding: 16px;
            }
            .mainbar h2 {
                margin-bottom: 16px;
                margin-left: 0px;
            }
            .card-body {
                padding: 5px !important; 
                max-width: 100%; 
            }
            .card-img-only-all {
                width: 315px;
                height: 177px;
                margin: 5px auto;
            }
            #departmentSelect {
                width: 43% !important;
            }
            .table-striped {
                margin-top: 10px;
            }
            #allClubsContainer {
                margin-top: -20px !important;
            }
        }






        .card-container {
            opacity: 0;
            transform: translateY(20px); /* Start from below */
            animation: slideIn 0.6s forwards; /* Apply animation */
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(20px); /* Start from below */
            }
            to {
                opacity: 1;
                transform: translateY(0); /* End at normal position */
            }
        }

        /* Optional: Adjust the delay for each card */
        .card-container:nth-child(1) {
            animation-delay: 0s;
        }
        .card-container:nth-child(2) {
            animation-delay: 0.1s;
        }
        .card-container:nth-child(3) {
            animation-delay: 0.2s;
        }




        /* Default for larger screens (6 cards per row) */
        .sbo-officers-row, .csg-officers-row {
            flex: 0 0 16.66%; /* 100% / 6 = 16.66% width per card */
            max-width: 16.66%;
        }

        /* Media query for mobile screens (2 cards per row) */
        @media (max-width: 768px) {
            .officers-link {
                margin-left: 0px !important;
            }
        }



        .myclubs-notification-badge {
            position: absolute;
            min-width: 20px;
            height: auto;
            top: 5px;
            right: 5px;
            background-color: red;
            color: white;
            border-radius: 50%;
            padding: 4px 4px;
            font-size: 12px;
            display: inline-block;
            text-align: center;
            line-height: 1;
            z-index: 1000;
        }


        .nav-link {
            position: relative; /* This makes the span position relative to the button */
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

        

        .card-row1 .icon-style {
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


        
    </style>
</head>
<body>
    
    <div class="container-fluid">
        <div class="row g-0 h-100">
            <nav class="navbar navbar-expand-lg navbar-dark bg-primary px-2">
                <a class="navbar-brand ps-2" href="#">
                    <img src="../assets/img/NBSC_LOGO.png" style="height: 0.3in;">
                    NBSC SIS</a>
                </button>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#main_nav" aria-expanded="true">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="navbar-collapse collapse hide" id="main_nav">
                    <div class="navbar-collapse flex-grow-1 text-right" id="sampleid" style="padding-left: 20px">
                        <?php include 'nav/nav_main.php' ?>
                    </div>
                </div>
            </nav>
            
            <!-- LEFT SIDEBAR -->
            <div class="col-12 col-md-2 pt-3 border-end">

                <div class="d-flex flex-column flex-shrink-0 px-2 bg-body-tertiary">
                    <ul class="nav nav-pills flex-column mb-auto">
                        <li>
                            <a href="../esas_student/dashboard.php" class="nav-link left-sidebar text-dark active" aria-current="page" id="dashboard">
                                <i class="fas fa-chart-line"></i> Dashboard
                            </a>
                        </li>
                        <li>
                            <a href="../esas_student/all_clubs.php" class="nav-link left-sidebar text-dark" id="all-clubs">
                                <i class="fas fa-university"></i> All Clubs
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../esas_student/my_clubs.php" class="nav-link left-sidebar text-dark" id="my-clubs">
                                <i class="fas fa-user"></i> My Clubs
                                <span id="myclubs-notification-count" class="myclubs-notification-badge" style="display:none;"></span>
                            </a>
                        </li>

                        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                        <script>
                            // Fetch and display my clubs notification count
                            function fetchMyClubsNotificationCount() {
                                $.ajax({
                                    url: '/esas/esas_student/apis/notifications/myclubs-notifications-api.php',
                                    method: 'GET',
                                    success: function(response) {
                                        const data = JSON.parse(response);
                                        if (data.unread_count > 0) {
                                            $('#myclubs-notification-count').text(data.unread_count).show();
                                        } else {
                                            $('#myclubs-notification-count').hide();
                                        }
                                    }
                                });
                            }

                            // Fetch my clubs notifications every 10 seconds
                            setInterval(fetchMyClubsNotificationCount, 10000);
                            fetchMyClubsNotificationCount();


                            // // Mark notifications as read when "My Clubs" is clicked
                            // $('#my-clubs').click(function() {
                            //     $.ajax({
                            //         url: '/esas/esas_student/apis/notifications/notifications-mark-read.php',
                            //         method: 'POST',
                            //         data: { student_id: <?php echo $_SESSION['student_id']; ?> },
                            //         success: function() {
                            //             $('#notification-count').hide(); // Hide the count after marking notifications as read
                            //         }
                            //     });
                            // });
                        </script>

                        <li>
                            <a href="../esas_student/club_requests.php" class="nav-link left-sidebar text-dark" id="club-requests">
                                <i class="fas fa-file-alt"></i> My Club Requests
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

                            <!-- <div class="row mb-2 p-2">
                                <label for="schoolYearDropdown" class="col-auto col-form-label">School Year:</label>
                                <div class="col-auto">
                                    <select id="schoolYearDropdown" class="form-select form-select-sm" style="width: 150px;" onchange="filterDashboard()">
                                        <php
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
                                    </select>
                                </div>

                                <div class="schoolyear-display text-center" style="width: 540px;">
                                    <h5 class='text-muted mt-1 ms-3'>Selected School Year: <php echo htmlspecialchars($selectedSchoolYear); ?></h5>
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
                            </div>       -->




                            <!-- ROW-1 CARDS START -->
                            <div class="row card-row1 col-md-12 mb-1" style="border: 1px solid transparent; margin: 0;">
                                
                                <div class="mt-3 mb-3 d-flex justify-content-between align-items-center">
                                    <!-- <h4 class="text-muted mb-0">Applications Overview</h4> -->
                                    <h4 class="text-muted mb-0">Application Status Summary</h4>
                                </div>

        <div class="col-md-6 p-1">
                                <!-- Card for STUDENT TOTAL REGISTRATIONS -->

                    <div class="row px-2">

                                
                                <div class="col-md-6 p-1" style="border: 1px solid transparent; padding: 0;">
                                    <div class="card p-2" style="margin: 0; box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);">
                                        <?php
                                        try {
                                            // Base SQL query to count total active clubs for the student
                                            $sql = "
                                                SELECT COUNT(r.club_id) AS total_applications 
                                                FROM tbl_application r
                                                WHERE r.student_id = :student_id
                                            ";

                                            // Parameters for the query
                                            $params = ['student_id' => $student_id];

                                            // Prepare and execute the query
                                            $stmt_applications = $pdo->prepare($sql);
                                            $stmt_applications->execute($params);

                                            // Fetch the total number of applications
                                            $total_applications = $stmt_applications->fetchColumn();
                                            echo "<h3>$total_applications</h3>";
                                        } catch (PDOException $e) {
                                            echo "Error: " . $e->getMessage();
                                        }
                                        ?>
                                        <i class="fas fa-file-signature mt-2 me-2 p-2 icon-style"></i>
                                        <p>Total Application</p>
                                    </div>
                                </div>
                                
                                <!-- Card for STUDENT TOTAL ACTIVE CLUBS -->
                                <div class="col-md-6 p-1" style="border: 1px solid transparent; padding: 0;">
                                    <div class="card p-2" style="margin: 0; box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);">
                                        <?php
                                        try {
                                            // Base SQL query to count total active clubs for the student
                                            $sql = "
                                                SELECT COUNT(DISTINCT r.club_id) AS total_active_clubs 
                                                FROM tbl_application r
                                                WHERE r.student_id = :student_id
                                                AND r.status = 'active'
                                            ";

                                            // Parameters for the query
                                            $params = ['student_id' => $student_id];

                                            // Prepare and execute the query
                                            $stmt_active_clubs = $pdo->prepare($sql);
                                            $stmt_active_clubs->execute($params);

                                            // Fetch the total number of active clubs
                                            $total_active_clubs = $stmt_active_clubs->fetchColumn();
                                            echo "<h3>$total_active_clubs</h3>";
                                        } catch (PDOException $e) {
                                            echo "Error: " . $e->getMessage();
                                        }
                                        ?>
                                        <i class="fas fa-university mt-2 me-2 p-2 icon-style"></i>
                                        <p>Total Active Club</p>
                                    </div>
                                </div>
                                
                    </div>
                    <div class="row px-2">


                                <!-- Card for STUDENT TOTAL PENDING APPROVAL -->
                                <div class="col-md-6 p-1" style="border: 1px solid transparent; padding: 0;">
                                    <div class="card p-2" style="margin: 0; box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);">
                                        <?php
                                        try {
                                            // Base SQL query to count total pending approval students for the logged-in student
                                            $sql = "
                                                SELECT COUNT(*) AS total_pending_students 
                                                FROM tbl_application tr 
                                                WHERE tr.status = 'pending' 
                                                AND tr.student_id = :student_id
                                            ";

                                            // Prepare and execute the query
                                            $stmt_pending_students = $pdo->prepare($sql);
                                            $stmt_pending_students->execute(['student_id' => $student_id]);

                                            // Fetch the total number of pending students
                                            $total_pending_students = $stmt_pending_students->fetchColumn();
                                            echo "<h3>$total_pending_students</h3>";
                                        } catch (PDOException $e) {
                                            echo "Error: " . $e->getMessage();
                                        }
                                        ?>
                                        <i class="fas fa-hourglass-half mt-2 me-2 p-2 icon-style"></i>
                                        <p>Total Pending Approval</p>
                                    </div>
                                </div>


                                <!-- Card for STUDENT TOTAL DISAPPROVAL -->
                                <div class="col-md-6 p-1" style="border: 1px solid transparent; padding: 0;">
                                    <div class="card p-2" style="margin: 0; box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);">
                                        <?php
                                        try {
                                            // Base SQL query to count total pending approval students for the logged-in student
                                            $sql = "
                                                SELECT COUNT(*) AS total_disapproved_students 
                                                FROM tbl_application tr 
                                                WHERE tr.status = 'disapproved' 
                                                AND tr.student_id = :student_id
                                            ";

                                            // Prepare and execute the query
                                            $stmt_disapproved_students = $pdo->prepare($sql);
                                            $stmt_disapproved_students->execute(['student_id' => $student_id]);

                                            // Fetch the total number of disapproved students
                                            $total_disapproved_students = $stmt_disapproved_students->fetchColumn();
                                            echo "<h3>$total_disapproved_students</h3>";
                                        } catch (PDOException $e) {
                                            echo "Error: " . $e->getMessage();
                                        }
                                        ?>
                                        <i class="fas fa-times mt-2 me-2 p-2 icon-style"></i>
                                        <p>Total Disapproval</p>
                                    </div>
                                </div>
                                
                    </div>
        </div>
        <div class="col-md-6">
    <!-- Engagement Line Chart -->
    <div class="card p-2 text-center" style="margin: 0; box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);">
        <p>Club Engagement</p>
        <div style="height: 200px; width: 100%; overflow-x: auto;">
            <canvas id="engagementChart"></canvas>
        </div>
        <p id="noDataMessageEngagement" style="display: none; text-align: center; font-size: 16px; color: red; margin-top: 7%; margin-bottom: 14%;"><em>No engagement.</em></p>

        <?php
        try {
            $student_id = $_SESSION['student_id'] ?? null;

            if ($student_id) {
                // Query to get all engagement dates and counts
                $sql = "
                    SELECT DATE(dateAdded) as engagementDate, COUNT(*) as loginCount
                    FROM tbl_activity_logs
                    WHERE activity = 'You logged in to your account' AND student_id = :student_id
                    GROUP BY DATE(dateAdded)
                    ORDER BY engagementDate;
                ";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
                $stmt->execute();
                $engagementData = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Fetch the earliest and latest engagement dates
                $startDateQuery = "
                    SELECT MIN(DATE(dateAdded)) AS startDate, MAX(DATE(dateAdded)) AS endDate
                    FROM tbl_activity_logs
                    WHERE student_id = :student_id AND activity = 'You logged in to your account';
                ";
                $dateStmt = $pdo->prepare($startDateQuery);
                $dateStmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
                $dateStmt->execute();
                $dateRange = $dateStmt->fetch(PDO::FETCH_ASSOC);

                // Set start and end dates
                $startDate = new DateTime($dateRange['startDate']);
                $endDate = new DateTime($dateRange['endDate']);

                // Initialize dates and counts with zeros
                $engagementDates = [];
                $loginCounts = [];
                $currentDate = clone $startDate;

                while ($currentDate <= $endDate) {
                    $formattedDate = $currentDate->format('m-d-y'); // Change to MM-DD-YY format
                    $engagementDates[] = $formattedDate;
                    $loginCounts[$formattedDate] = 0;  // Default to zero if no engagement
                    $currentDate->modify('+1 day');
                }

                // Populate counts from the engagement data
                foreach ($engagementData as $row) {
                    $loginCounts[date('m-d-y', strtotime($row['engagementDate']))] = (int)$row['loginCount'];
                }

                // Separate keys and values for the chart
                $engagementDates = array_keys($loginCounts);
                $loginCounts = array_values($loginCounts);

                // Max login count for y-axis scale
                $maxLoginCount = max($loginCounts);
            } else {
                $engagementDates = [];
                $loginCounts = [];
                $maxLoginCount = 1;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        ?>

        <!-- Include Chart.js and the Zoom Plugin -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-zoom"></script>

        <script>
            const engagementDates = <?php echo json_encode($engagementDates); ?>;
            const loginCounts = <?php echo json_encode($loginCounts); ?>;
            const hasDataEngagement = loginCounts.some(count => count > 0);
            const maxLoginCount = <?php echo $maxLoginCount; ?>;

            const engagementChartElement = document.getElementById('engagementChart');
            const noDataMessageEngagement = document.getElementById('noDataMessageEngagement');

            if (!hasDataEngagement) {
                engagementChartElement.style.display = 'none';
                noDataMessageEngagement.style.display = 'block';
            } else {
                // Chart.js data and configuration without tension for straight lines
                const engagementData = {
                    labels: engagementDates,
                    datasets: [{
                        label: 'Logins',
                        data: loginCounts,
                        fill: true,
                        borderColor: 'rgba(54, 162, 235, 1)',
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        tension: 0 // No tension for straight lines
                    }]
                };

                const engagementConfig = {
                    type: 'line',
                    data: engagementData,
                    options: {
                        scales: {
                            x: {
                                beginAtZero: true,
                                max: 10 // Show only 10 dates initially
                            },
                            y: {
                                beginAtZero: true,
                                max: maxLoginCount
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            zoom: {
                                pan: {
                                    enabled: true,
                                    mode: 'x',
                                    onPanComplete: function() {
                                        engagementChart.update();
                                    }
                                },
                                zoom: {
                                    wheel: {
                                        enabled: true
                                    },
                                    pinch: {
                                        enabled: true
                                    },
                                    mode: 'x'
                                }
                            }
                        },
                        responsive: true,
                        maintainAspectRatio: false
                    }
                };

                const engagementChart = new Chart(engagementChartElement, engagementConfig);
            }
        </script>
    </div>
</div>


                            </div>
                            <!-- ROW-1 CARDS END -->


                            <!-- ROW-2 CARDS START -->
                            <div class="row card-row1 col-md-12 mb-1" style="border: 1px solid transparent; margin: 0;">
                                
                                <div class="mt-3 mb-3 d-flex justify-content-between align-items-center">
                                    <!-- <h4 class="text-muted mb-0">Applications Overview</h4> -->
                                    <h4 class="text-muted mb-0">Club Requests Overview</h4>
                                </div>

                                <!-- Card for TOTAL CLUB REQUESTS -->
                                <div class="col-md-3 p-1" style="border: 1px solid transparent; padding: 0;">
                                    <div class="card p-2" style="margin: 0; box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);">
                                        <?php
                                        try {
                                            // SQL query to count total club requests for the student
                                            $sql = "
                                                SELECT COUNT(request_id) AS total_club_requests 
                                                FROM tbl_club_requests
                                                WHERE student_id = :student_id
                                            ";

                                            // Parameters for the query
                                            $params = ['student_id' => $student_id];

                                            // Prepare and execute the query
                                            $stmt_requests = $pdo->prepare($sql);
                                            $stmt_requests->execute($params);

                                            // Fetch the total number of club requests
                                            $total_club_requests = $stmt_requests->fetchColumn();
                                            echo "<h3>$total_club_requests</h3>";
                                        } catch (PDOException $e) {
                                            echo "Error: " . $e->getMessage();
                                        }
                                        ?>
                                        <i class="fas fa-clipboard-list mt-2 me-2 p-2 icon-style"></i>
                                        <p>Total Club Requests</p>
                                    </div>
                                </div>

                                
                                <!-- Card for TOTAL PENDING REQUESTS -->
                                <div class="col-md-3 p-1" style="border: 1px solid transparent; padding: 0;">
                                    <div class="card p-2" style="margin: 0; box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);">
                                        <?php
                                        try {
                                            // SQL query to count total pending club requests
                                            $sql = "
                                                SELECT COUNT(request_id) AS total_pending_requests 
                                                FROM tbl_club_requests 
                                                WHERE status = 'pending' 
                                                AND student_id = :student_id
                                            ";

                                            // Prepare and execute the query
                                            $stmt_pending_requests = $pdo->prepare($sql);
                                            $stmt_pending_requests->execute(['student_id' => $student_id]);

                                            // Fetch the total number of pending requests
                                            $total_pending_requests = $stmt_pending_requests->fetchColumn();
                                            echo "<h3>$total_pending_requests</h3>";
                                        } catch (PDOException $e) {
                                            echo "Error: " . $e->getMessage();
                                        }
                                        ?>
                                        <i class="fas fa-hourglass-half mt-2 me-2 p-2 icon-style"></i>
                                        <p>Total Pending Requests</p>
                                    </div>
                                </div>



                                <!-- Card for TOTAL APPROVED REQUESTS -->
                                <div class="col-md-3 p-1" style="border: 1px solid transparent; padding: 0;">
                                    <div class="card p-2" style="margin: 0; box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);">
                                        <?php
                                        try {
                                            // Base SQL query to count total approved club requests for the logged-in student
                                            $sql = "
                                                SELECT COUNT(*) AS total_approved_requests
                                                FROM tbl_club_requests 
                                                WHERE status = 'approved'
                                                AND student_id = :student_id
                                            ";

                                            // Prepare and execute the query
                                            $stmt_approved_requests = $pdo->prepare($sql);
                                            $stmt_approved_requests->execute(['student_id' => $student_id]);

                                            // Fetch the total number of approved requests
                                            $total_approved_requests = $stmt_approved_requests->fetchColumn();
                                            echo "<h3>$total_approved_requests</h3>";
                                        } catch (PDOException $e) {
                                            echo "Error: " . $e->getMessage();
                                        }
                                        ?>
                                        <i class="fas fa-check mt-2 me-2 p-2 icon-style"></i>
                                        <p>Total Approved Request</p>
                                    </div>
                                </div>



                                <!-- Card for TOTAL DISAPPROVED REQUESTS -->
                                <div class="col-md-3 p-1" style="border: 1px solid transparent; padding: 0;">
                                    <div class="card p-2" style="margin: 0; box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);">
                                        <?php
                                        try {
                                            // Base SQL query to count total disapproved club requests for the logged-in student
                                            $sql = "
                                                SELECT COUNT(*) AS total_disapproved_requests
                                                FROM tbl_club_requests 
                                                WHERE status = 'disapproved'
                                                AND student_id = :student_id
                                            ";

                                            // Prepare and execute the query
                                            $stmt_disapproved_requests = $pdo->prepare($sql);
                                            $stmt_disapproved_requests->execute(['student_id' => $student_id]);

                                            // Fetch the total number of disapproved requests
                                            $total_disapproved_requests = $stmt_disapproved_requests->fetchColumn();
                                            echo "<h3>$total_disapproved_requests</h3>";
                                        } catch (PDOException $e) {
                                            echo "Error: " . $e->getMessage();
                                        }
                                        ?>
                                        <i class="fas fa-times mt-2 me-2 p-2 icon-style"></i>
                                        <p>Total Disapproved Request</p>
                                    </div>
                                </div>


                                <!-- Card for TOTAL ADDED REQUESTS -->
                                <div class="col-md-3 p-1" style="border: 1px solid transparent; padding: 0;">
                                    <div class="card p-2" style="margin: 0; box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);">
                                        <?php
                                        try {
                                            // Base SQL query to count total added club requests for the logged-in student
                                            $sql = "
                                                SELECT COUNT(*) AS total_approved_requests
                                                FROM tbl_clubs
                                                WHERE founder_id = :student_id
                                            ";

                                            // Prepare and execute the query
                                            $stmt_added_requests = $pdo->prepare($sql);
                                            $stmt_added_requests->execute(['student_id' => $student_id]);

                                            // Fetch the total number of added requests
                                            $total_added_requests = $stmt_added_requests->fetchColumn();
                                            echo "<h3>$total_added_requests</h3>";
                                        } catch (PDOException $e) {
                                            echo "Error: " . $e->getMessage();
                                        }
                                        ?>
                                        <i class="fas fa-plus-circle mt-2 me-2 p-2 icon-style"></i>
                                        <p>Total Added Request</p>
                                    </div>
                                </div>

                            </div>
                            <!-- ROW-2 CARDS END -->


                            
                        </div>
                        <!-- THE MAIN PAGE END -->
                    </div>
                </div>
            </div>
            <!-- MAINPAGE BAR END -->


        </div>
    </div>

    <!-- <?php include 'assets/components/modals.php' ?> -->
    <script src="../assets/js/jquery.dataTables.min.js"></script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/global_script.js"></script>



    <script>
        function submitClubRequest() {
            document.getElementById('clubRequestForm').submit();
        }

        function updateLabel(label) {
            document.getElementById("tabLabel").innerText = label;
        }

        // Function to fetch clubs data
        function fetchClubs(department = null) {
            let url = '/esas/esas_student/apis/clubs-api.php';
            if (department) {
                url += `?department=${encodeURIComponent(department)}`;
            }

            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    const allClubsContainer = document.getElementById('allClubsContainer');
                    const rowCountDisplay = document.getElementById('rowCountDisplay');
                    const totalRows = data.length;

                    // Update the row count display
                    rowCountDisplay.textContent = `Showing ${totalRows} / ${totalRows} Clubs`;

                    // Clear previous club cards
                    allClubsContainer.innerHTML = '';

                    if (totalRows > 0) {
                        data.forEach(club => {
                            const memberText = club.membersCount === 1 ? '1 member' : `${club.membersCount} members`;
                            const cardHTML = `
                                <div class="col-md-4 card-container club-card" data-club-name="${club.clubName}">
                                    <div class="card card-img-only-all">
                                        <small data-toggle="tooltip" title="${memberText}">
                                            <i class="fa fa-user mr-1"></i>${club.membersCount}
                                        </small>
                                        <a href="/esas/esas_student/club_info.php?club_id=${club.club_id}&club_name=${encodeURIComponent(club.clubName)}">
                                            <img src="/esas/esas_admin/images/${club.coverPhoto}" alt="Cover Photo">
                                            <div class="overlay-text">
                                                <h4 class="club-name">${club.clubName}</h4>
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

                    // Reinitialize search functionality after clubs are loaded
                    initializeSearch();
                })
                .catch(error => {
                    console.error('Error fetching clubs:', error);
                    const allClubsContainer = document.getElementById('allClubsContainer');
                    allClubsContainer.innerHTML = '<p>Failed to fetch clubs. Please try again later.</p>';
                });
        }

        // Function to initialize search functionality
        function initializeSearch() {
            const searchInput = document.getElementById('clubSearch');
            const clubCards = document.querySelectorAll('.club-card');
            const rowCountDisplay = document.getElementById('rowCountDisplay');
            const noResultsMessage = document.getElementById('noResultsMessage');

            searchInput.addEventListener('input', function() {
                const searchTerm = searchInput.value.trim().toLowerCase();
                let visibleClubCount = 0;

                clubCards.forEach(card => {
                    const clubNameElement = card.querySelector('.club-name');
                    const originalClubName = card.getAttribute('data-club-name');
                    const originalClubNameLower = originalClubName.toLowerCase();

                    if (searchTerm === '') {
                        clubNameElement.innerHTML = originalClubName; // Reset the name if search is empty
                        card.style.display = '';
                        visibleClubCount++; // Count visible cards
                    } else {
                        if (originalClubNameLower.includes(searchTerm)) {
                            card.style.display = '';
                            visibleClubCount++;
                            highlightText(clubNameElement, searchTerm); // Highlight the matching text
                        } else {
                            card.style.display = 'none';
                        }
                    }
                });

                // Update the row count to reflect the visible clubs
                const totalRows = clubCards.length; // Total number of cards available
                rowCountDisplay.textContent = `Showing ${visibleClubCount} / ${totalRows} Records`;

                // Show or hide the noResultsMessage
                if (visibleClubCount === 0 && searchTerm !== '') {
                    noResultsMessage.style.display = 'block'; // Show message
                } else {
                    noResultsMessage.style.display = 'none'; // Hide message
                }
            });
        }   

        // Fetch all clubs on page load
        window.onload = function() {
            const storedDepartment = localStorage.getItem('selectedDepartment');
            fetchClubs(storedDepartment); // Fetch clubs based on stored department
            if (storedDepartment) {
                document.getElementById('departmentSelect').value = storedDepartment; // Set the dropdown value
            } else {
                fetchClubs(); // Default fetch all clubs
            }
        };

        // Add department selection functionality
        const departmentSelect = document.getElementById('departmentSelect');
        departmentSelect.addEventListener('change', function() {
            const selectedDepartment = this.value;
            localStorage.setItem('selectedDepartment', selectedDepartment); // Store the selected department
            fetchClubs(selectedDepartment); // Fetch clubs for the selected department
        });

        // Highlight matching text
        function highlightText(element, term) {
            const originalText = element.textContent;
            const regex = new RegExp(`(${term})`, 'gi');
            element.innerHTML = originalText.replace(regex, `<span style="background-color: rgba(173, 216, 230, 0.7); border-radius: 10%;">$1</span>`);
        }
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