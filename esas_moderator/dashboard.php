<?php 
session_start();
require_once "../config.php";  // Assuming this file holds your PDO connection

if (!isset($_SESSION['moderator_id'])) {
    echo "Moderator ID is not set in the session.";
    exit;
}

$moderator_id = $_SESSION['moderator_id']; // Get moderator ID from session

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
    <link href="../assets/css/jquery.dataTables.min.css" rel="stylesheet" />
    <script src="../assets/js/all.js" crossorigin="anonymous"></script>
    <script src="../assets/js/jquery-3.6.0.js"></script>
    <link href="../assets/css/styles.css" rel="stylesheet" />
    <link href="../assets/img/nbsclogo.png" rel="icon">
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

    </style>
</head>
<body>
    
    <div class="container-fluid">
        <div class="row g-0 h-100">
            <nav class="navbar navbar-expand-lg navbar-dark bg-primary px-2">
                <a class="navbar-brand ps-2" href="#">
                    <img src="../assets/img/SAS_LOGO.png" style="height: 0.3in;">
                    eSAS - Moderator</a>
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
            <div class="col-12 col-md-2 ps-0 pt-3 border-end">

            <div class="d-flex flex-column flex-shrink-0 p-3 bg-body-tertiary">
                <ul class="nav nav-pills flex-column mb-auto">
                    <li>
                        <a href="../esas_moderator/dashboard.php" class="nav-link left-sidebar text-dark active" id="all-clubs">
                            <i class="fas fa-chart-line"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="../esas_moderator/my_clubs.php" class="nav-link left-sidebar text-dark" aria-current="page" id="my-clubs">
                            <i class="fas fa-users"></i> My Clubs
                        </a>
                    </li>
                    <li>
                        <a href="../esas_moderator/pending_approvals.php" class="nav-link left-sidebar text-dark" id="pending-approvals">
                            <i class="fas fa-hourglass-half"></i> Pending Approvals
                        </a>
                    </li>
                    <li>
                        <a href="../esas_moderator/club_requests.php" class="nav-link left-sidebar text-dark" id="club-requests">
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
                            <label for="clubDropdown" class="col-auto col-form-label">Club:</label>
                            <div class="col-auto">
                                <select id="clubDropdown" class="form-select form-select-sm" style="width: 150px;" onchange="filterDashboard()">
                                    <?php
                                    // Prepare the SQL query
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

                                    // Generate the dropdown options
                                    foreach ($clubs as $club): ?>
                                        <option value="<?php echo htmlspecialchars($club['club_id']); ?>">
                                            <?php echo htmlspecialchars($club['clubName']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <label for="schoolYearDropdown" class="col-auto col-form-label">School Year:</label>
                                <div class="col-auto">
                                    <select id="schoolYearDropdown" class="form-select form-select-sm" style="width: 150px;" onchange="filterDashboard()">
                                        <?php 
                                        // Get the current date
                                        $currentDate = new DateTime();

                                        // Calculate school years based on current date
                                        $schoolYears = [];
                                        for ($i = 0; $i < 5; $i++) {
                                            $startYear = $currentDate->format('Y') - $i;
                                            $endYear = $startYear + 1;
                                            $schoolYears[] = "{$startYear}-{$endYear}";
                                        }

                                        // Prepare and execute the SQL query
                                        $sql = "
                                            SELECT DISTINCT 
                                                CONCAT(YEAR(dateAdded), '-', YEAR(dateAdded) + 1) AS schoolYear
                                            FROM tbl_clubs
                                            WHERE dateAdded IS NOT NULL
                                            AND YEAR(dateAdded) BETWEEN YEAR(DATE_SUB(NOW(), INTERVAL 5 YEAR)) AND YEAR(NOW())
                                        ";

                                        $stmt = $pdo->prepare($sql);
                                        $stmt->execute();
                                        $activeSchoolYears = $stmt->fetchAll(PDO::FETCH_COLUMN);

                                        // Determine which school years to display
                                        $displaySchoolYears = array_intersect($schoolYears, $activeSchoolYears);

                                        // Output school year options
                                        foreach ($displaySchoolYears as $schoolYear) {
                                            echo "<option value=\"" . htmlspecialchars($schoolYear) . "\">" . htmlspecialchars($schoolYear) . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                        </div>

                        <!-- THE MAIN PAGE START -->
                        <div class="card p-2">
                            <!-- UPPER CARDS START -->
                            <div class="row card-row1 col-md-12 mb-1" style="border: 1px solid transparent; margin: 0;">
                                <!-- Card for TOTAL MODERATORS CLUBS -->
                                <div class="col-md-3 p-1" style="border: 1px solid transparent; padding: 0;">
                                    <div class="card p-2" style="margin: 0; box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);">
                                        <?php
                                        try {
                                            // Fetch total clubs associated with the moderator using tbl_club_and_moderators
                                            $stmt_clubs = $pdo->prepare("
                                                SELECT COUNT(DISTINCT club_id) AS total_clubs 
                                                FROM tbl_clubs_and_moderators 
                                                WHERE moderator_id = :moderator_id
                                            ");
                                            $stmt_clubs->bindParam(':moderator_id', $moderator_id, PDO::PARAM_INT);
                                            $stmt_clubs->execute();
                                            $total_clubs = $stmt_clubs->fetchColumn();
                                            echo "<h3>$total_clubs</h3>";
                                        } catch (PDOException $e) {
                                            echo "Error: " . $e->getMessage();
                                        }
                                        ?>
                                        <p>Total Clubs</p>
                                    </div>
                                </div>

                                <!-- Card for TOTAL STUDENTS -->
                                <div class="col-md-3 p-1" style="border: 1px solid transparent; padding: 0;">
                                    <div class="card p-2" style="margin: 0; box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);">
                                        <?php
                                        try {
                                            // Fetch total distinct students who registered under clubs managed by this moderator
                                            $stmt_students = $pdo->prepare("
                                                SELECT COUNT(DISTINCT tr.student_id) AS total_students 
                                                FROM tbl_registration tr 
                                                JOIN tbl_clubs tc ON tr.club_id = tc.club_id 
                                                JOIN tbl_moderators tm ON tc.club_id = tm.club_id 
                                                WHERE tm.moderator_id = :moderator_id AND tr.status = 'active'
                                            ");
                                            $stmt_students->bindParam(':moderator_id', $moderator_id, PDO::PARAM_INT);
                                            $stmt_students->execute();
                                            $total_students = $stmt_students->fetchColumn();
                                            echo "<h3>$total_students</h3>";
                                        } catch (PDOException $e) {
                                            echo "Error: " . $e->getMessage();
                                        }
                                        ?>
                                        <p>Total Students</p>
                                    </div>
                                </div>

                                <!-- Card for TOTAL PENDING -->
                                <div class="col-md-3 p-1" style="border: 1px solid transparent; padding: 0;">
                                    <div class="card p-2" style="margin: 0; box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);">
                                        <?php
                                        try {
                                            // Fetch total pending student registrations for clubs managed by this moderator
                                            $stmt_pending = $pdo->prepare("
                                                SELECT COUNT(tr.student_id) AS total_pending 
                                                FROM tbl_registration tr
                                                JOIN tbl_clubs tc ON tr.club_id = tc.club_id
                                                JOIN tbl_moderators tm ON tc.club_id = tm.club_id
                                                WHERE tr.status = 'pending' AND tm.moderator_id = :moderator_id
                                            ");
                                            $stmt_pending->bindParam(':moderator_id', $moderator_id, PDO::PARAM_INT);
                                            $stmt_pending->execute();
                                            $total_pending = $stmt_pending->fetchColumn();
                                            echo "<h3>$total_pending</h3>";
                                        } catch (PDOException $e) {
                                            echo "Error: " . $e->getMessage();
                                        }
                                        ?>
                                        <p>Total Pending Approval</p>
                                    </div>
                                </div>

                                <!-- Card for LEAVE REQUESTS (you can modify this query as needed) -->
                                <div class="col-md-3 p-1" style="border: 1px solid transparent; padding: 0;">
                                    <div class="card p-2" style="margin: 0; box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);">
                                        <h3>0</h3>
                                        <p>Leave Requests</p>
                                    </div>
                                </div>
                            </div>
                            <!-- UPPER CARDS END -->


                            <!-- CHARTS AND DIAGRAMS START -->
                            <div class="row card-row2 col-12" style="border: 1px solid transparent; margin: 0;">

                                <!-- PIE CHART -->
                                <div class="col-md-5 p-1" style="border: 1px solid transparent; padding: 0;">
                                    <div class="card p-2 text-center" style="margin: 0; box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);">
                                        <p>Students per Department</p>
                                        <div style="height: 365px; background-color: transparent;">
                                            <?php
                                            try {
                                                // Fetch the count of registered students per department for the clubs managed by the current moderator
                                                $stmt = $pdo->prepare("
                                                    SELECT ts.department, COUNT(tr.student_id) AS member_count
                                                    FROM tbl_students ts
                                                    JOIN tbl_registration tr ON ts.student_id = tr.student_id
                                                    JOIN tbl_clubs_and_moderators cm ON tr.club_id = cm.club_id
                                                    WHERE cm.moderator_id = :moderator_id AND tr.status = 'active'
                                                    GROUP BY ts.department
                                                ");
                                                $stmt->bindParam(':moderator_id', $moderator_id, PDO::PARAM_INT);
                                                $stmt->execute();
                                                $department_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                            } catch (PDOException $e) {
                                                echo "Error: " . $e->getMessage();
                                            }

                                            // Prepare data for the pie chart
                                            $departments = [];
                                            $counts = [];

                                            foreach ($department_data as $row) {
                                                $departments[] = $row['department'];
                                                $counts[] = $row['member_count'];
                                            }
                                            ?>
                                            <!-- Canvas for the pie chart -->
                                            <canvas id="pieChart" style="height: 100%;"></canvas>
                                            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                                            <script>
                                                // Fetching data from PHP arrays
                                                const departments = <?php echo json_encode($departments); ?>;
                                                const counts = <?php echo json_encode($counts); ?>;

                                                // Render Pie Chart using Chart.js
                                                const ctx = document.getElementById('pieChart').getContext('2d');
                                                const pieChart = new Chart(ctx, {
                                                    type: 'pie',
                                                    data: {
                                                        labels: departments,  // Department names
                                                        datasets: [{
                                                            label: 'Members per Department',
                                                            data: counts,  // Member count per department
                                                            backgroundColor: [
                                                                'rgba(65, 105, 225, 0.8)',   // Bright Royal Blue
                                                                'rgba(255, 105, 180, 0.8)',   // Hot Pink (complementary color)
                                                                'rgba(255, 215, 0, 0.8)',     // Gold (bright and vibrant)
                                                                'rgba(0, 255, 255, 0.8)',     // Cyan (bright contrasting color)
                                                                'rgba(255, 165, 0, 0.8)',     // Orange (bright and warm)
                                                                'rgba(0, 255, 0, 0.8)'        // Lime Green (bright and fresh)
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
                                                            }
                                                        }
                                                    }
                                                });
                                            </script>
                                        </div>
                                    </div>
                                </div>
                                <!-- PIE CHART END -->



                                <!-- OTHER CHARTS -->
                                <div class="col-md-7" style="border: 1px solid transparent; padding: 0;">
                                    <div class="row" style="border: 1px solid transparent; margin: 0;">
                                        <!-- Student Gender -->
                                        <div class="col-md-12 p-1" style="border: 1px solid transparent; padding: 0;">
                                            <div class="card p-2 text-center" style="margin: 0; box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);">
                                                <p>Student Gender</p>
                                                <div style="height: 150px; position: relative;">
                                                    <?php
                                                    try {
                                                        // Replace with actual club_id value
                                                        $club_id = 1; // Example club_id

                                                        // Prepare the SQL statement to get gender counts
                                                        $sqlCounts = "
                                                            SELECT gender, COUNT(*) AS count
                                                            FROM tbl_students
                                                            JOIN tbl_registration ON tbl_students.student_id = tbl_registration.student_id
                                                            WHERE tbl_registration.status = 'active' AND tbl_registration.club_id = :club_id
                                                            GROUP BY gender
                                                        ";

                                                        $stmtCounts = $pdo->prepare($sqlCounts);
                                                        $stmtCounts->bindParam(':club_id', $club_id, PDO::PARAM_INT);
                                                        $stmtCounts->execute();
                                                        $counts = $stmtCounts->fetchAll(PDO::FETCH_ASSOC);

                                                        $maleCount = 0;
                                                        $femaleCount = 0;

                                                        foreach ($counts as $row) {
                                                            if ($row['gender'] === 'Male') {
                                                                $maleCount = $row['count'];
                                                            } elseif ($row['gender'] === 'Female') {
                                                                $femaleCount = $row['count'];
                                                            }
                                                        }

                                                        // Prepare the SQL statement to get total student count
                                                        $sqlTotal = "SELECT COUNT(*) AS total_count FROM tbl_students";
                                                        $stmtTotal = $pdo->query($sqlTotal);
                                                        $totalCount = $stmtTotal->fetchColumn();

                                                    } catch (PDOException $e) {
                                                        // Handle query error
                                                        echo 'Error: ' . $e->getMessage();
                                                    }
                                                    ?>
                                                    <canvas id="studentChart" style="width: 100%; height: 100%;"></canvas>
                                                    <script>
                                                        document.addEventListener('DOMContentLoaded', function() {
                                                            const ctx = document.getElementById('studentChart').getContext('2d');

                                                            // Data from PHP
                                                            const maleCount = <?php echo $maleCount; ?>;
                                                            const femaleCount = <?php echo $femaleCount; ?>;
                                                            const maxCount = <?php echo $totalCount; ?>; // Total student count

                                                            new Chart(ctx, {
                                                                type: 'bar', // Use 'bar' type for horizontal bars
                                                                data: {
                                                                    labels: ['Male', 'Female'],
                                                                    datasets: [{
                                                                        data: [maleCount, femaleCount],
                                                                        backgroundColor: ['#3498db', '#e74c3c'],
                                                                        borderColor: ['#2980b9', '#c0392b'],
                                                                        borderWidth: 1
                                                                    }]
                                                                },
                                                                options: {
                                                                    indexAxis: 'y', // Set to 'y' to make bars horizontal
                                                                    scales: {
                                                                        x: {
                                                                            beginAtZero: true,
                                                                            suggestedMax: maxCount // Use total count as the maximum value
                                                                        },
                                                                        y: {
                                                                            // Optional settings for y-axis if needed
                                                                        }
                                                                    },
                                                                    plugins: {
                                                                        legend: {
                                                                            display: false // Hide the legend
                                                                        }
                                                                    }
                                                                }
                                                            });
                                                        });
                                                    </script>
                                                </div>
                                            </div>
                                        </div>
                                    </div>




                                    <!-- Vertically divided Year Level Count and Members per School Year -->
                                    <div class="row" style="border: 1px solid transparent; margin: 0;">



                                    
                                        <!-- Year Level Numbers -->
<div class="col-md-6 p-1" style="border: 1px solid transparent; padding: 0;">
    <div class="card p-2 text-center" style="margin: 0; box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);">
        <p>Year Level Count</p>
        <div style="height: 150px; background-color: transparent;">
            <div>
                <canvas id="studentBarChart"></canvas>
            </div>
            <?php
            try {
                $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // SQL Query to fetch year level counts
                $sql = "SELECT s.year, COUNT(*) AS count
                        FROM tbl_students s
                        JOIN tbl_registration r ON s.student_id = r.student_id
                        WHERE r.status = 'active' AND r.club_id = :club_id
                        GROUP BY s.year";
                
                $stmtCounts = $pdo->prepare($sql);
                $stmtCounts->bindParam(':club_id', $club_id, PDO::PARAM_INT);
                $stmtCounts->execute();

                // Fetch the results into an associative array
                $yearData = $stmtCounts->fetchAll(PDO::FETCH_ASSOC);
                
                // Initialize arrays for years and counts
                $years = ['1', '2', '3', '4']; // Ensure all years are included
                $counts = [0, 0, 0, 0]; // Initialize with zeros

                // Populate the counts array based on fetched data
                foreach ($yearData as $row) {
                    $year = (int)$row['year']; // Ensure $year is an integer
                    $count = (int)$row['count']; // Ensure $count is an integer

                    // Check if $year is within the valid range
                    if ($year >= 1 && $year <= 4) {
                        $counts[$year - 1] = $count;
                    }
                }
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

                // Data for the chart
                const data = {
                    labels: labels,
                    datasets: [{
                        data: dataCounts, // Dynamic data from PHP
                        backgroundColor: ['blue', 'orange', 'green', 'red'], // Colors for bars
                    }]
                };

                // Configurations for the chart
                const config = {
                    type: 'bar',
                    data: data,
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 50 // Set max limit to 50
                            }
                        },
                        plugins: {
                            legend: {
                                display: false // Remove the "Number of Students" label
                            }
                        }
                    }
                };

                // Render the chart
                const studentBarChart = new Chart(
                    document.getElementById('studentBarChart'),
                    config
                );
            </script>
        </div>
    </div>
</div>

                                        <!-- Members per School Year -->
                                        <div class="col-md-6 p-1" style="border: 1px solid transparent; padding: 0;">
                                            <div class="card p-2 text-center" style="margin: 0; box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);">
                                                <p>Members per School Year</p>
                                                <div style="height: 150px; background-color: lightgray;">
                                                    <!-- BAR GRAPH FOR NUMBERS OF YEAR LEVEL -->
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