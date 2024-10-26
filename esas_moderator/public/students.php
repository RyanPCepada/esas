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
    // Fetch moderator's name
    $sqlModerator = "SELECT firstName, middleName, lastName FROM tbl_moderators WHERE moderator_id = :moderator_id";
    $stmtModerator = $pdo->prepare($sqlModerator);
    $stmtModerator->bindParam(':moderator_id', $moderator_id, PDO::PARAM_INT);
    $stmtModerator->execute();
    
    // Fetch the result
    $resultModerator = $stmtModerator->fetch(PDO::FETCH_ASSOC);

    // Check if a result was found
    if ($resultModerator) {
        $firstName = strtoupper($resultModerator['firstName']);
        $middleName = strtoupper($resultModerator['middleName']);
        $lastName = strtoupper($resultModerator['lastName']);
    } else {
        // Handle the case where no data is found
        $firstName = $middleName = $lastName = "UNKNOWN";
    }

    // Fetch students with active status
    $sqlStudents = "
        SELECT s.student_id, s.firstName, s.middleName, s.lastName, s.age, s.birthday, s.gender, s.instiEmail, s.phoneNumber, s.department, s.course, s.year, s.street, s.barangay, s.municipality, s.province, s.zipcode, s.profilePic
        FROM tbl_students s
        JOIN tbl_application r ON s.student_id = r.student_id
        WHERE r.status = 'active'
    ";

    $stmtStudents = $pdo->prepare($sqlStudents);
    $stmtStudents->execute();
    $students = $stmtStudents->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Handle database connection or query error
    die("Database error: " . $e->getMessage());
}

// Now you can use $firstName, $middleName, $lastName for the moderator details
// and $students for the list of students
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>eSAS - Students List</title>
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
    align-items: flex-start !important; /* Align items to the start */
}



        table.table-striped tbody tr:hover {
            background-color: #f5f5f5;
            cursor: pointer;
        }
        
/*HERE*/



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
                            <a href="../../esas_moderator/public/dashboard.php" class="nav-link left-sidebar text-dark" id="all-clubs">
                                <i class="fas fa-chart-line"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../../esas_moderator/public/my_clubs.php" class="nav-link left-sidebar text-dark" id="my-clubs">
                                <i class="fas fa-university"></i> My Clubs
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../../esas_moderator/public/students.php" class="nav-link left-sidebar text-dark active" aria-current="page" id="my-clubs">
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
                            <a href="../../esas_moderator/public/reports.php" class="nav-link left-sidebar text-dark" id="reports">
                                <i class="fas fa-file-alt"></i> Reports
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

                                        // Set the first club as default if no club is selected
                                        $defaultClubId = $clubs[0]['club_id'] ?? null;
                                        $defaultClubName = $clubs[0]['clubName'] ?? 'Club not available';

                                        // Generate the dropdown options
                                        foreach ($clubs as $club): ?>
                                            <option value="<?php echo htmlspecialchars($club['club_id']); ?>"
                                                <?php if (isset($_GET['club_id']) && $_GET['club_id'] == $club['club_id']) echo 'selected'; ?>>
                                                <?php echo htmlspecialchars($club['clubName']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

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


                                <!-- Display selected club name or default club name -->
                                <div class="clubname-display text-center" style="width: 540px;">
                                    <?php
                                    // Check if a club is selected via the URL
                                    $club_id = isset($_GET['club_id']) ? intval($_GET['club_id']) : $defaultClubId;

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

                            <!-- ALL STUDENT TABLE START -->
                            <div class="row card-row1 col-md-12 mb-1" style="border: 1px solid transparent; margin: 0;">
                                
                                <table class="table table-bordered table-striped" style="background-color: #f9f9f9;">
                                    <thead>
                                        <tr>
                                            <th colspan="9">
                                                <div class="row">
                                                    <!-- Dropdown for filtering by status -->
                                                    <div class="col-12 col-md-8 d-flex align-items-center">
                                                        <select id="statusSelect" class="form-select me-2" style="width: 20%;">
                                                            <optgroup label="Select Club Request Status">
                                                                <option value="" selected>All</option>
                                                                <option value="active">Active</option>
                                                                <option value="inactive">Inactive</option>
                                                                <option value="disapproved">Disapproved</option>
                                                                <option value="departed">Departed</option>
                                                            </optgroup>
                                                        </select>
                                                        <!-- Search input -->
                                                        <input id="studentSearch" class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                                                    </div>
                                                    <!-- Row count display -->
                                                    <div class="col-12 col-md-4 d-flex align-items-center justify-content-center mt-2">
                                                        <h6 id="rowCountDisplay">Showing 0 / 0 Records</h6>
                                                    </div>
                                                </div>
                                            </th>
                                        </tr>
                                    </thead>
                                </table>

                                <?php
                                    // Include config file
                                    require_once "../../config.php";

                                    $selectedClubId = isset($_GET['club_id']) ? intval($_GET['club_id']) : $defaultClubId; // Use default club ID if not set
                                    $selectedMonth = isset($_GET['school_year']) ? intval($_GET['school_year']) : null;

                                    // SQL query to fetch cumulative students with "active" status in the selected club
                                    $sql = "SELECT 
                                        s.student_id,
                                        s.firstName,
                                        s.middleName,
                                        s.lastName,
                                        s.age,
                                        s.gender,
                                        s.instiEmail,
                                        s.phoneNumber,
                                        s.department,
                                        s.course,
                                        s.year,
                                        s.profilePic,
                                        s.dateAdded AS student_dateAdded,
                                        r.application_id,
                                        r.status AS status,  -- Include the status here
                                        GROUP_CONCAT(DISTINCT c.clubName ORDER BY c.clubName ASC SEPARATOR ', ') AS clubNames
                                    FROM tbl_students s
                                    LEFT JOIN tbl_application r ON s.student_id = r.student_id
                                    LEFT JOIN tbl_clubs c ON r.club_id = c.club_id
                                    WHERE r.status IN ('active', 'inactive', 'departed', 'disapproved')";



                                    // Check if club ID is set and add to the query
                                    if ($selectedClubId) {
                                        $sql .= " AND c.club_id = :club_id";
                                    }

                                    // Check if a month is selected and add to the query
                                    if ($selectedMonth) {
                                        $sql .= " AND MONTH(s.dateAdded) <= :selectedMonth";
                                    }

                                    $sql .= " GROUP BY s.student_id ORDER BY s.student_id ASC";

                                    // Prepare the SQL statement
                                    $stmt = $pdo->prepare($sql);

                                    // Bind parameters
                                    $params = [];
                                    if ($selectedClubId) {
                                        $params['club_id'] = $selectedClubId;
                                    }
                                    if ($selectedMonth) {
                                        $params['selectedMonth'] = $selectedMonth;
                                    }

                                    // Execute the SQL statement
                                    $stmt->execute($params);

                                    // Fetch all results
                                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    $totalRows = count($result);
                                    $rowCount = 0;

                                    if ($totalRows > 0) {
                                        echo '
                                        <table class="table table-bordered table-striped" style="background-color: #f9f9f9;" id="studentTable">
                                            <thead>
                                                <tr>
                                                    <!-- <th>Application ID</th> -->
                                                    <th>Student ID</th>
                                                    <th>Profile</th>
                                                    <th>Full Name</th>
                                                    <th>Gender</th>
                                                    <th>Age</th>
                                                    <th>Email</th>
                                                    <th>Phone</th>
                                                    <th>Course</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>';

                                        foreach ($result as $row) {
                                            $formattedDate = date('F j, Y', strtotime($row['student_dateAdded']));
                                            $application_id = htmlspecialchars($row['application_id']);
                                            $student_id = htmlspecialchars($row['student_id']);
                                            $fullName = htmlspecialchars($row['firstName'] . ' ' . $row['middleName'] . ' ' . $row['lastName']);
                                            $profilePic = htmlspecialchars($row['profilePic'] ? $row['profilePic'] : 'default-profile.jpg');
                                            $gender = htmlspecialchars($row['gender']);
                                            $age = htmlspecialchars($row['age']);
                                            $email = htmlspecialchars($row['instiEmail']);
                                            $phoneNumber = htmlspecialchars($row['phoneNumber']);
                                            $course = htmlspecialchars($row['course']);
                                            $status = htmlspecialchars($row['status']);

                                            $rowCount++;

                                            echo '
                                            <tr class="student-row" data-status="' . htmlspecialchars($row['status']) . '"> 
                                                <!-- <td>' . $application_id . '</td> -->
                                                <td>' . $student_id . '</td>
                                                <td class="text-center p-1">
                                                    <img class="student-profile-pic" src="/esas/esas_student/images/' . $profilePic . '" 
                                                        alt="' . $fullName . ' profile picture" 
                                                        style="width: 35px; height: 35px; border-radius: 50%;">
                                                </td>
                                                <td>' . $fullName . '</td>
                                                <td>' . $gender . '</td>
                                                <td>' . $age . '</td>
                                                <td>' . $email . '</td>
                                                <td>' . $phoneNumber . '</td>
                                                <td>' . $course . '</td>
                                                <td>' . $status . '</td>
                                                <td class="text-center">
                                                    <a href="../public/crud/students/student_read.php?application_id=' . htmlspecialchars($row['application_id']) . '&student_id=' . htmlspecialchars($row['student_id']) . '&club_id=' . htmlspecialchars($selectedClubId) . '&fullName=' . htmlspecialchars($fullName) . '" class="mr-2" title="View Record" data-toggle="tooltip"><span class="fa fa-eye"></span></a>
                                                </td>
                                            </tr>';
                                        }

                                        echo '
                                            </tbody>
                                        </table>';
                                    } else {
                                        echo '<p style="font-size: 16px; color: red;"><em>No students.</em></p>';
                                    }

                                    // Update the row count display
                                    echo "<script>
                                        document.getElementById('rowCountDisplay').innerText = 'Showing $rowCount / $totalRows Records';
                                    </script>";
                                ?>

                            </div>
                            <!-- ALL STUDENT TABLE END -->

                            <div id="noResultsMessage" class="alert p-2 ps-3" style="display: none;">
                                <em>No results found.</em>
                            </div>

                            <script>
                                document.addEventListener('DOMContentLoaded', function () {
                                    const searchInput = document.getElementById('studentSearch');
                                    const studentRows = document.querySelectorAll('.student-row');
                                    const rowCountDisplay = document.getElementById('rowCountDisplay');
                                    const noResultsMessage = document.getElementById('noResultsMessage');
                                    const totalRows = studentRows.length;
                                    const statusSelect = document.getElementById('statusSelect');

                                    rowCountDisplay.textContent = `Showing ${totalRows} / ${totalRows} Records`;

                                    // Filter by status
                                    statusSelect.addEventListener('change', function () {
                                        const selectedStatus = this.value.toLowerCase();
                                        let visibleRowCount = 0;

                                        studentRows.forEach(function (row) {
                                            const rowStatus = row.getAttribute('data-status').toLowerCase();
                                            if (!selectedStatus || rowStatus === selectedStatus) {
                                                row.style.display = '';
                                                visibleRowCount++;
                                            } else {
                                                row.style.display = 'none';
                                            }
                                        });

                                        rowCountDisplay.textContent = `Showing ${visibleRowCount} / ${totalRows} Records`;
                                        noResultsMessage.style.display = (visibleRowCount === 0) ? 'block' : 'none';
                                    });

                                    searchInput.addEventListener('input', function () {
                                        const searchTerm = searchInput.value.trim().toLowerCase();
                                        let visibleRowCount = 0;

                                        studentRows.forEach(function (row) {
                                            const cells = row.querySelectorAll('td');
                                            let rowContainsTerm = false;

                                            cells.forEach(function (cell) {
                                                // Reset cell content and apply highlight
                                                cell.innerHTML = removeHighlight(cell.innerHTML);
                                                if (highlightText(cell, searchTerm)) {
                                                    rowContainsTerm = true;
                                                }
                                            });

                                            const selectedStatus = statusSelect.value.toLowerCase();
                                            const rowStatus = row.getAttribute('data-status').toLowerCase();

                                            // Check if the row matches the search term and the selected status
                                            if (rowContainsTerm && (!selectedStatus || rowStatus === selectedStatus)) {
                                                row.style.display = '';
                                                visibleRowCount++;
                                            } else {
                                                row.style.display = 'none';
                                            }
                                        });

                                        rowCountDisplay.textContent = `Showing ${visibleRowCount} / ${totalRows} Records`;
                                        noResultsMessage.style.display = (visibleRowCount === 0) ? 'block' : 'none';
                                    });

                                    // Highlight matching text
                                    function highlightText(cell, term) {
                                        const textNodes = getTextNodes(cell);
                                        let found = false;

                                        textNodes.forEach(node => {
                                            const text = node.textContent;
                                            if (text.toLowerCase().includes(term)) {
                                                const regex = new RegExp(`(${term})`, 'gi');
                                                const highlightedText = text.replace(regex, '<span style="background-color: lightblue; color: #0033cc;">$1</span>');
                                                const span = document.createElement('span');
                                                span.innerHTML = highlightedText;
                                                node.replaceWith(span);
                                                found = true;
                                            }
                                        });

                                        return found;
                                    }

                                    // Get text nodes for highlighting
                                    function getTextNodes(element) {
                                        let textNodes = [];
                                        function recurse(node) {
                                            if (node.nodeType === Node.TEXT_NODE && node.textContent.trim() !== '') {
                                                textNodes.push(node);
                                            } else if (node.nodeType === Node.ELEMENT_NODE) {
                                                node.childNodes.forEach(recurse);
                                            }
                                        }
                                        recurse(element);
                                        return textNodes;
                                    }

                                    // Remove highlights from text
                                    function removeHighlight(html) {
                                        return html.replace(/<span[^>]*style="[^"]*background-color:[^"]*"[^>]*>(.*?)<\/span>/gi, '$1');
                                    }
                                });
                            </script>


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
    $(document).ready(function() {
        $('.delprreq').click(function(e) {
            e.stopPropagation();
        });
        // let value= $("classname").val()
    });
</script>

</body>
</html>