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
    </style>
</head>
<body>
    
    <div class="container-fluid">
        <div class="row g-0 h-100">
            <nav class="navbar navbar-expand-lg navbar-dark bg-primary px-2">
                <a class="navbar-brand ps-2" href="#">
                    <img src="../../assets/img/SAS_LOGO.png" style="height: 0.3in;">
                    eSAS - Moderator</a>
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
                            <a href="../../esas_moderator/public/dashboard.php" class="nav-link left-sidebar text-dark active" aria-current="page" id="dashboard">
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
                    </ul>
                </div>

            </div>
            <!-- LEFT SIDEBAR END -->


            <!--HERE-->
            
            <!-- MAINPAGE BAR -->
            <div class="col-12 col-md-10 bg-lgrey auto-scroll">
                <div class="row g-0 h-100">
                    <div class="row g-0 p-4 px-2 pt-2 h-100">
                        


                        <!-- THE MAIN PAGE START -->
                        <div class="card p-2">

                            <div class="row mb-2 p-2">
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
                                    </select>
                                </div>

                                <!-- Display selected club name or default club name -->
                                <div class="clubname-display text-center" style="width: 540px;">
                                    <?php
                                    // Change: Check if a club is selected via the URL
                                    $club_id = isset($_GET['club_id']) ? intval($_GET['club_id']) : $defaultClubId; // Use the default club ID if not set

                                    // Fetch and display the selected club name
                                    $sql = "SELECT clubName FROM tbl_clubs WHERE club_id = :club_id";
                                    $stmt = $pdo->prepare($sql);
                                    $stmt->execute(['club_id' => $club_id]);
                                    $club = $stmt->fetch(PDO::FETCH_ASSOC);

                                    if ($club) {
                                        echo "<h5 class='text-muted mt-1 ms-3'>" . htmlspecialchars($club['clubName']) . "</h5>";
                                    } else {
                                        echo "<p>Club not found.</p>";
                                    }
                                    ?>
                                </div>

                                <script>
                                    function filterDashboard() {
                                        var club_id = document.getElementById('clubDropdown').value;
                                        var school_year = document.getElementById('schoolYearDropdown').value;
                                        var queryParams = new URLSearchParams(window.location.search);

                                        // Update the club_id and school_year parameters in the URL
                                        queryParams.set('club_id', club_id);
                                        queryParams.set('school_year', school_year);

                                        // Navigate to the updated URL
                                        window.location.search = queryParams.toString();
                                    }
                                </script>
                            </div>



                            <!-- UPPER CARDS START -->
                            <div class="row card-row1 col-md-12 mb-1" style="border: 1px solid transparent; margin: 0;">
                                <!-- Card for TOTAL MODERATORS CLUBS -->
                                <div class="col-md-3 p-1" style="border: 1px solid transparent; padding: 0;">
                                    <div class="card p-2" style="margin: 0; box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);">
                                        <?php
                                        try {
                                            // Get the selected club_id and school_year from the URL
                                            $selectedClubId = isset($_GET['club_id']) ? intval($_GET['club_id']) : $defaultClubId; //
                                            $selectedSchoolYear = isset($_GET['school_year']) ? $_GET['school_year'] : $defaultSchoolYear;

                                            // Initialize variable for cumulative date filtering
                                            $cumulativeDate = null;

                                            // Parse the selected school year to define the cumulative cutoff date
                                            if ($selectedSchoolYear) {
                                                list($startYear, $endYear) = explode('-', $selectedSchoolYear);
                                                // Set cumulative date to July 31 of the end year
                                                $cumulativeDate = "$endYear-07-31";
                                            }

                                            // Base SQL query to count total clubs associated with the moderator
                                            $sql = "
                                                SELECT COUNT(DISTINCT cm.club_id) AS total_clubs 
                                                FROM tbl_clubs_and_moderators cm
                                                JOIN tbl_clubs c ON cm.club_id = c.club_id
                                                WHERE cm.moderator_id = :moderator_id
                                                AND c.dateAdded <= :cumulative_date
                                            ";

                                            // Parameters for the query
                                            $params = [
                                                'moderator_id' => $moderator_id,
                                                'cumulative_date' => $cumulativeDate
                                            ];

                                            // Do not filter by club_id when counting total clubs unless it's specifically required
                                            // So if you only want the total number of clubs, exclude this condition unless you need to filter
                                            // by a specific club later in other logic
                                            if ($selectedClubId && isset($_GET['filter_by_club'])) {
                                                // Use this filter only if you have a specific filter flag in the logic
                                                $sql .= " AND c.club_id = :club_id";
                                                $params['club_id'] = $selectedClubId;
                                            }

                                            // Prepare and execute the query
                                            $stmt_clubs = $pdo->prepare($sql);
                                            $stmt_clubs->execute($params);

                                            // Fetch the total number of clubs
                                            $total_clubs = $stmt_clubs->fetchColumn();
                                            echo "<h3>$total_clubs</h3>";
                                        } catch (PDOException $e) {
                                            echo "Error: " . $e->getMessage();
                                        }
                                        ?>
                                        <i class="fas fa-university mt-2 me-2 p-2 icon-style"></i>
                                        <p>Total Club</p>
                                    </div>
                                </div>


                                <!-- Card for TOTAL STUDENTS -->
                                <div class="col-md-3 p-1" style="border: 1px solid transparent; padding: 0;">
                                    <div class="card p-2" style="margin: 0; box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);">
                                        <?php
                                        // Get the selected club_id and school_year from the URL
                                        $selectedClubId = isset($_GET['club_id']) ? intval($_GET['club_id']) : $defaultClubId; // Use default club ID if not set
                                        $selectedSchoolYear = isset($_GET['school_year']) ? $_GET['school_year'] : $defaultSchoolYear;

                                        // Extract the start and end year from the selected school year
                                        if ($selectedSchoolYear) {
                                            list($startYear, $endYear) = explode('-', $selectedSchoolYear);
                                            $startDate = "$startYear-08-01"; // August 1 of the start year
                                            $endDate = "$endYear-07-31"; // July 31 of the end year
                                        } else {
                                            // Default to the latest school year if none is selected
                                            $latestYear = date('Y');
                                            $startDate = "$latestYear-08-01";
                                            $endDate = ($latestYear + 1) . "-07-31";
                                        }

                                        try {
                                            // Base SQL query to count cumulative active students for clubs handled by the moderator
                                            $sql = "
                                                SELECT COUNT(DISTINCT tr.student_id) AS total_students 
                                                FROM tbl_registration tr 
                                                JOIN tbl_clubs tc ON tr.club_id = tc.club_id 
                                                JOIN tbl_clubs_and_moderators cm ON tc.club_id = cm.club_id 
                                                WHERE cm.moderator_id = :moderator_id 
                                                AND tr.status = 'active'
                                                AND tr.dateApproved <= :end_date
                                            ";

                                            // Parameters for the query
                                            $params = [
                                                'moderator_id' => $moderator_id,
                                                'end_date' => $endDate,
                                            ];

                                            // Add condition for the selected club, if applicable
                                            if ($selectedClubId) {
                                                $sql .= " AND tr.club_id = :club_id";
                                                $params['club_id'] = $selectedClubId; // Ensure this is set
                                            }

                                            // Prepare and execute the query
                                            $stmt_students = $pdo->prepare($sql);
                                            $stmt_students->execute($params);

                                            // Fetch the total number of students
                                            $total_students = $stmt_students->fetchColumn();
                                            echo "<h3>$total_students</h3>";
                                        } catch (PDOException $e) {
                                            echo "Error: " . $e->getMessage();
                                        }
                                        ?>
                                        <i class="fas fa-users mt-2 me-2 p-2 icon-style"></i>
                                        <p>Total Student</p>
                                    </div>
                                </div>





                                <!-- Card for TOTAL PENDING -->
                                <div class="col-md-3 p-1" style="border: 1px solid transparent; padding: 0;">
                                    <div class="card p-2" style="margin: 0; box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);">
                                        <?php
                                        // Get the selected club_id and school_year from the URL
                                        $selectedClubId = isset($_GET['club_id']) ? intval($_GET['club_id']) : $defaultClubId; // Use default club ID if not set
                                        $selectedSchoolYear = isset($_GET['school_year']) ? $_GET['school_year'] : $defaultSchoolYear;

                                        try {
                                            // Extract the start and end year from the selected school year
                                            if ($selectedSchoolYear) {
                                                list($startYear, $endYear) = explode('-', $selectedSchoolYear);
                                                $endDate = "$endYear-07-31"; // End date for the selected school year
                                            } else {
                                                // Default to the latest school year if none is selected
                                                $latestYear = date('Y');
                                                $endDate = ($latestYear + 1) . "-07-31"; // End date for the latest school year
                                            }

                                            // Base SQL query to count total pending registrations for clubs handled by the moderator
                                            $sql = "
                                                SELECT COUNT(tr.registration_id) AS total_pending 
                                                FROM tbl_registration tr
                                                JOIN tbl_clubs tc ON tr.club_id = tc.club_id
                                                JOIN tbl_clubs_and_moderators tcm ON tc.club_id = tcm.club_id
                                                WHERE tr.status = 'pending' 
                                                AND tcm.moderator_id = :moderator_id
                                                AND tr.dateApplied <= :end_date
                                            ";

                                            // Parameters for the query
                                            $params = [
                                                'moderator_id' => $moderator_id,
                                                'end_date' => $endDate,
                                            ];

                                            // Add condition for the selected club, if applicable
                                            if ($selectedClubId) {
                                                $sql .= " AND tr.club_id = :club_id";
                                                $params['club_id'] = $selectedClubId;
                                            }

                                            // Prepare and execute the query
                                            $stmt_pending = $pdo->prepare($sql);
                                            $stmt_pending->execute($params);

                                            // Fetch the total number of pending registrations
                                            $total_pending = $stmt_pending->fetchColumn();
                                            echo "<h3>$total_pending</h3>";
                                        } catch (PDOException $e) {
                                            echo "Error: " . $e->getMessage();
                                        }
                                        ?>
                                        <i class="fas fa-hourglass-half mt-2 me-2 p-2 icon-style"></i>
                                        <p>Total Pending Approval</p>
                                    </div>
                                </div>




                                <!-- Card for DEPARTURE REQUESTS -->
                                <div class="col-md-3 p-1" style="border: 1px solid transparent; padding: 0;">
                                    <div class="card p-2" style="margin: 0; box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);">
                                        <?php
                                        // Get the selected club_id and school_year from the URL
                                        $selectedClubId = isset($_GET['club_id']) ? intval($_GET['club_id']) : $defaultClubId; // Use default club ID if not set
                                        $selectedSchoolYear = isset($_GET['school_year']) ? $_GET['school_year'] : $defaultSchoolYear;

                                        try {
                                            // Extract the start and end year from the selected school year
                                            if ($selectedSchoolYear) {
                                                list($startYear, $endYear) = explode('-', $selectedSchoolYear);
                                                $endDate = "$endYear-07-31"; // End date for the selected school year
                                            } else {
                                                // Default to the latest school year if none is selected
                                                $latestYear = date('Y');
                                                $endDate = ($latestYear + 1) . "-07-31"; // End date for the latest school year
                                            }

                                            // Base SQL query to count departure requests for clubs handled by the moderator
                                            $sql = "
                                                SELECT COUNT(td.departure_id) AS total_departure_requests 
                                                FROM tbl_departure_requests td
                                                JOIN tbl_clubs tc ON td.club_id = tc.club_id
                                                JOIN tbl_clubs_and_moderators tcm ON tc.club_id = tcm.club_id
                                                WHERE td.status = 'pending' 
                                                AND tcm.moderator_id = :moderator_id
                                                AND td.dateRequested <= :end_date
                                            ";

                                            // Parameters for the query
                                            $params = [
                                                'moderator_id' => $moderator_id,
                                                'end_date' => $endDate,
                                            ];

                                            // Add condition for the selected club, if applicable
                                            if ($selectedClubId) {
                                                $sql .= " AND td.club_id = :club_id";
                                                $params['club_id'] = $selectedClubId;
                                            }

                                            // Prepare and execute the query
                                            $stmt_departure_requests = $pdo->prepare($sql);
                                            $stmt_departure_requests->execute($params);

                                            // Fetch the total number of departure requests
                                            $total_departure_requests = $stmt_departure_requests->fetchColumn();
                                            echo "<h3>$total_departure_requests</h3>";
                                        } catch (PDOException $e) {
                                            echo "Error: " . $e->getMessage();
                                        }
                                        ?>
                                        <i class="fas fa-door-open mt-2 me-2 p-2 icon-style"></i>
                                        <p>Total Departure Request</p>
                                    </div>
                                </div>

                            </div>
                            <!-- UPPER CARDS END -->


                            <!-- CHARTS AND DIAGRAMS START -->
                            <div class="row card-row2 col-12" style="border: 1px solid transparent; margin: 0;">

                                <!-- PIE CHART -->
                                <div class="col-md-5 p-1" style="border: 1px solid transparent; padding: 0;">
                                    <div class="card p-2 text-center" style="margin: 0; box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);">
                                        <p>Total Students per Department</p>
                                        <div style="height: 365px; background-color: transparent;">
                                            <?php
                                            try {
                                                // Get the selected club_id and school_year from the URL
                                                $selectedClubId = isset($_GET['club_id']) ? intval($_GET['club_id']) : $defaultClubId; // Use default club ID if not set
                                                $selectedSchoolYear = isset($_GET['school_year']) ? $_GET['school_year'] : $defaultSchoolYear;

                                                // Extract the start and end year from the selected school year
                                                if ($selectedSchoolYear) {
                                                    list($startYear, $endYear) = explode('-', $selectedSchoolYear);
                                                    $startDate = "$startYear-08-01"; // August 1 of the start year
                                                    $endDate = "$endYear-07-31"; // July 31 of the end year
                                                } else {
                                                    // Default to the latest school year if none is selected
                                                    $latestYear = date('Y');
                                                    $startDate = "$latestYear-08-01";
                                                    $endDate = ($latestYear + 1) . "-07-31";
                                                }

                                                // Prepare the SQL query for cumulative counts
                                                $sql = "
                                                    SELECT ts.department, COUNT(DISTINCT tr.student_id) AS member_count
                                                    FROM tbl_students ts
                                                    JOIN tbl_registration tr ON ts.student_id = tr.student_id
                                                    JOIN tbl_clubs tc ON tr.club_id = tc.club_id
                                                    JOIN tbl_clubs_and_moderators cm ON tc.club_id = cm.club_id
                                                    WHERE cm.moderator_id = :moderator_id 
                                                    AND tr.status = 'active'
                                                    AND tr.dateApproved <= :end_date
                                                ";

                                                // Add condition for club_id if it's set
                                                if ($club_id) {
                                                    $sql .= " AND tr.club_id = :club_id";
                                                }

                                                $sql .= " GROUP BY ts.department";

                                                $stmt = $pdo->prepare($sql);
                                                $stmt->bindParam(':moderator_id', $moderator_id, PDO::PARAM_INT);
                                                $stmt->bindParam(':end_date', $endDate); // Bind end date for cumulative count

                                                // Bind club_id parameter if it's set
                                                if ($club_id) {
                                                    $stmt->bindParam(':club_id', $club_id, PDO::PARAM_INT);
                                                }

                                                $stmt->execute();
                                                $department_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                            } catch (PDOException $e) {
                                                echo "Error: " . $e->getMessage();
                                            }

                                            // Prepare data for the pie chart
                                            $departments = [];
                                            $counts = [];
                                            $total_members = 0;

                                            foreach ($department_data as $row) {
                                                $departments[] = $row['department'];
                                                $counts[] = $row['member_count'];
                                                $total_members += $row['member_count'];
                                            }

                                            // Calculate percentage for each department with two decimal places
                                            $percentages = [];
                                            foreach ($counts as $count) {
                                                $percentages[] = $total_members > 0 ? number_format(($count / $total_members) * 100, 2) : 0;
                                            }

                                            // Combine percentages and department names
                                            $labels_with_percentages = [];
                                            foreach ($departments as $index => $department) {
                                                $labels_with_percentages[] = $percentages[$index] . '% ' . $department;
                                            }
                                            ?>
                                            <!-- Canvas for the pie chart -->
                                            <canvas id="pieChart" style="height: 100%; margin: auto;"></canvas>
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
                                                            labels: labelsWithPercentages,  // Department names with percentages
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
                                                                    labels: {
                                                                        boxWidth: 12,  // Set square shape
                                                                        padding: 20    // Add spacing between labels and box
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
                                                <p>Total Registry per SY</p>
                                                <div style="height: 100%; width: 100%; background-color: transparent;">
                                                    <canvas id="registryPerSYChart"></canvas>
                                                </div>
                                                <p id="noDataMessageSY" style="display: none; text-align: center; font-size: 16px; color: red; margin-top: 7%; margin-bottom: 14%;"><em>No students.</em></p>

                                                <?php
                                                try {
                                                    // Get the selected club_id from the URL
                                                    $club_id = isset($_GET['club_id']) ? intval($_GET['club_id']) : null;

                                                    // SQL query to fetch the count of members per academic year (June to May)
                                                    $sql = "
                                                        SELECT 
                                                            CASE 
                                                                WHEN MONTH(r.dateApproved) >= 6 THEN CONCAT(YEAR(r.dateApproved), '-', YEAR(r.dateApproved) + 1)
                                                                ELSE CONCAT(YEAR(r.dateApproved) - 1, '-', YEAR(r.dateApproved))
                                                            END AS academicYear, 
                                                            COUNT(DISTINCT s.student_id) AS memberCount
                                                        FROM tbl_students s
                                                        JOIN tbl_registration r ON s.student_id = r.student_id
                                                        JOIN tbl_clubs c ON r.club_id = c.club_id
                                                        JOIN tbl_clubs_and_moderators cm ON c.club_id = cm.club_id
                                                        WHERE r.status = 'active' AND cm.moderator_id = :moderator_id
                                                    ";

                                                    // Add condition for club_id if it's set
                                                    if ($club_id) {
                                                        $sql .= " AND r.club_id = :club_id";
                                                    }

                                                    $sql .= " GROUP BY academicYear ORDER BY academicYear;";

                                                    // Prepare the statement
                                                    $stmt = $pdo->prepare($sql);
                                                    $stmt->bindParam(':moderator_id', $moderator_id, PDO::PARAM_INT);

                                                    // Bind club_id parameter if it's set
                                                    if ($club_id) {
                                                        $stmt->bindParam(':club_id', $club_id, PDO::PARAM_INT);
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

                                                    // Fill in missing academic years with zero counts
                                                    $allAcademicYears = [];
                                                    $currentYear = date('Y'); // Current year
                                                    for ($year = $currentYear - 1; $year <= $currentYear + 1; $year++) {
                                                        $allAcademicYears[] = ($year - 1) . '-' . $year; // Format: 2023-2024
                                                    }

                                                    // Initialize member counts to zero
                                                    $memberCountsAllYears = array_fill(0, count($allAcademicYears), 0);

                                                    // Map fetched data to the corresponding years
                                                    foreach ($registryPerSYData as $row) {
                                                        $index = array_search($row['academicYear'], $allAcademicYears);
                                                        if ($index !== false) {
                                                            $memberCountsAllYears[$index] = $row['memberCount'];
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
                                                    const academicYears = <?php echo json_encode($allAcademicYears); ?>;
                                                    const memberCountsPerSY = <?php echo json_encode($memberCountsAllYears); ?>;
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



                                    
                                        <!-- Year Level Numbers -->
<div class="col-md-6 p-1" style="border: 1px solid transparent; padding: 0;">
    <div class="card p-2 text-center" style="margin: 0; box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);">
        <p>Total Year Level Count</p>
        <div style="height: 150px; background-color: transparent;">
            <div>
                <canvas id="studentBarChart"></canvas>
            </div>
            <p id="noDataMessageYearLevels" style="display: none; text-align: center; font-size: 16px; color: red; margin-top: 14%; margin-bottom: 7%;"><em>No students.</em></p>

            <?php
            // Get the selected club_id and school_year from the URL 
            $selectedClubId = isset($_GET['club_id']) ? intval($_GET['club_id']) : $defaultClubId; // Use default club ID if not set
            $selectedSchoolYear = isset($_GET['school_year']) ? $_GET['school_year'] : $defaultSchoolYear;

            try {
                // Extract start and end years from selected school year
                list($startYear, $endYear) = explode('-', $selectedSchoolYear);

                // SQL Query to fetch year level counts, filtered by moderator, club_id, and school year
                $sql = "
                SELECT s.year, COUNT(DISTINCT s.student_id) AS count
                FROM tbl_students s
                JOIN tbl_registration r ON s.student_id = r.student_id
                JOIN tbl_clubs_and_moderators cm ON r.club_id = cm.club_id
                WHERE r.status = 'active'
                AND cm.moderator_id = :moderator_id
                AND r.club_id = :club_id
                AND r.dateApproved BETWEEN :start_date AND :end_date
                GROUP BY s.year";

                // Prepare and execute the query
                $stmtCounts = $pdo->prepare($sql);
                $stmtCounts->bindParam(':moderator_id', $moderator_id, PDO::PARAM_INT);
                $stmtCounts->bindParam(':club_id', $selectedClubId, PDO::PARAM_INT);

                // Bind the start and end date based on the school year
                $startDate = $startYear . '-08-01'; // Assuming school year starts in August
                $endDate = $endYear . '-07-31'; // Assuming school year ends in July
                $stmtCounts->bindParam(':start_date', $startDate);
                $stmtCounts->bindParam(':end_date', $endDate);

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

                // SQL Query to get the total student count from tbl_students
                $sqlTotalStudents = "
                SELECT COUNT(DISTINCT student_id) AS total_student_count
                FROM tbl_students";

                $stmtTotalStudents = $pdo->prepare($sqlTotalStudents);
                $stmtTotalStudents->execute();
                $totalStudentCount = $stmtTotalStudents->fetchColumn();

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
                    const studentBarChart = new Chart(
                        studentBarChartElement,
                        config
                    );
                }
            </script>

        </div>
    </div>
</div>



                                        <!-- Student Gender --> 
<div class="col-md-6 p-1" style="border: 1px solid transparent; padding: 0;">
    <div class="card p-2 text-center" style="margin: 0; box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);">
        <p>Total Student Gender</p>
        <div style="height: 150px; position: relative;">
            <?php
            try {
                // Get the selected club_id and school_year from the URL
                $selectedClubId = isset($_GET['club_id']) ? intval($_GET['club_id']) : $defaultClubId; // Use default club ID if not set
                $selectedSchoolYear = isset($_GET['school_year']) ? $_GET['school_year'] : $defaultSchoolYear;

                // Determine the start and end year for the selected school year
                list($startYear, $endYear) = explode('-', $selectedSchoolYear);
                $startDate = "$startYear-08-01"; // Start from August 1 of the start year
                $endDate = "$endYear-07-31"; // End on July 31 of the end year

                // Prepare the SQL statement to get gender counts filtered by club, moderator, and date
                $sqlCounts = "
                    SELECT s.gender, COUNT(DISTINCT s.student_id) AS count
                    FROM tbl_students s
                    JOIN tbl_registration r ON s.student_id = r.student_id
                    JOIN tbl_clubs c ON r.club_id = c.club_id
                    JOIN tbl_clubs_and_moderators cm ON c.club_id = cm.club_id
                    WHERE r.status = 'active' AND cm.moderator_id = :moderator_id
                    AND r.dateApproved BETWEEN :startDate AND :endDate
                ";

                // Add condition for club_id if it's set
                if ($selectedClubId) {
                    $sqlCounts .= " AND r.club_id = :club_id";
                }

                $sqlCounts .= " GROUP BY s.gender";

                $stmtCounts = $pdo->prepare($sqlCounts);
                $stmtCounts->bindParam(':moderator_id', $moderator_id, PDO::PARAM_INT);
                $stmtCounts->bindParam(':startDate', $startDate);
                $stmtCounts->bindParam(':endDate', $endDate);

                // Bind club_id parameter if it's set
                if ($selectedClubId) {
                    $stmtCounts->bindParam(':club_id', $selectedClubId, PDO::PARAM_INT);
                }

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

                // Prepare the SQL statement to get the total student count in the system
                $sqlTotal = "SELECT COUNT(DISTINCT student_id) AS total_count FROM tbl_students";
                $stmtTotal = $pdo->query($sqlTotal);
                $totalCount = $stmtTotal->fetchColumn();

                // Function to round up to the nearest multiple of 5
                function roundUpToNearest5($num) {
                    return $num > 0 ? ceil($num / 5) * 5 : 5;
                }

                // Calculate the peak limit
                $peakLimit = roundUpToNearest5($totalCount);

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

                    // Data from PHP
                    const labels = <?php echo json_encode($labels); ?>;
                    const dataCounts = <?php echo json_encode($data); ?>;
                    const peakLimit = <?php echo $peakLimit; ?>;

                    // Check if there is no data to display
                    const hasDataGender = dataCounts.some(count => count > 0);

                    // Show or hide the "No Data" message based on data availability
                    const studentGenderChartElement = document.getElementById('studentGenderChart');
                    const noDataMessageGender = document.getElementById('noDataMessageGender');

                    if (!hasDataGender) {
                        studentGenderChartElement.style.display = 'none';  // Hide the chart
                        noDataMessageGender.style.display = 'block';  // Show "No data available" message
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
                                        max: peakLimit // Set the maximum value to the nearest higher multiple of 5
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