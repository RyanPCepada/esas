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
    <title>eSAS - Club Requests</title>
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
                        <a href="../../esas_admin/public/club_requests.php" class="nav-link left-sidebar text-dark active" aria-current="page" id="club-requests">
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
                <div class="row g-0 h-100">
                    <div class="row g-0 p-4 px-2 pt-2 h-100">

                        <!-- THE MAIN PAGE START -->
                        <div class="card p-2">

                            <!-- ALL STUDENT TABLE START -->
                            <div class="row card-row1 col-md-12 mb-1" style="border: 1px solid transparent; margin: 0;">
                                
                                <div class="mt-1 mb-3 d-flex justify-content-between align-items-center">
                                    <h4 class="text-muted mb-0">Club Requests</h4>
                                    <a href="../public/crud/students/student_create.php" class="btn btn-danger disabled" style="visibility: hidden;">
                                        <i class="fa fa-plus"></i> Add New Student
                                    </a>
                                </div>

                                
                                <table class="table table-bordered table-striped" style="background-color: #f9f9f9;">
                                        <thead>
                                            <tr>
                                                <th colspan="9">
                                                    <div class="row">
                                                        <div class="col-12 col-md-8 d-flex align-items-center">
                                                            <select id="statusSelect" class="form-select me-2" style="width: 20%;">
                                                                <optgroup label="Select Club Request Status">
                                                                    <option value="" selected>All</option>
                                                                    <option value="pending">Pending</option>
                                                                    <option value="approved">Approved</option>
                                                                    <option value="disapproved">Disapproved</option>
                                                                </optgroup>
                                                            </select>
                                                            <input id="studentSearch" class="form-control mr-sm-2" type="search" placeholder="Search for clubs here..." aria-label="Search">
                                                        </div>
                                                        <div class="col-12 col-md-4 d-flex align-items-center justify-content-center mt-2">
                                                            <h6 id="rowCountDisplay">Showing 0 / 0 Club Requests</h6>
                                                        </div>
                                                    </div>
                                                </th>
                                            </tr>
                                        </thead>
                                    </table>


                                <?php
                                // Include config file
                                require_once "../../config.php";

                                // SQL query to fetch all club requests for active students
                                $sql = "SELECT 
                                            r.request_id,
                                            r.clubName,
                                            r.goal,
                                            r.activities,
                                            r.status,
                                            r.coverPhoto,
                                            s.firstName,
                                            s.middleName,
                                            s.lastName,
                                            s.instiEmail,
                                            s.department,
                                            s.course,  -- Added Course field
                                            s.profilePic,
                                            GROUP_CONCAT(DISTINCT r.clubName ORDER BY r.clubName ASC SEPARATOR ', ') AS clubNames
                                        FROM tbl_club_requests r
                                        JOIN tbl_students s ON r.student_id = s.student_id
                                        LEFT JOIN tbl_application reg ON s.student_id = reg.student_id
                                        WHERE reg.status = 'active' -- Filter for active students
                                        GROUP BY r.request_id
                                        ORDER BY r.dateRequested DESC";

                                if ($result = $pdo->query($sql)) {
                                    $totalRows = $result->rowCount();

                                    if ($totalRows > 0) {
                                        echo '
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped" style="background-color: #f9f9f9;">
                                                <thead>
                                                    <tr>
                                                        <th>Cover Photo</th> <!-- New Cover Photo Column -->
                                                        <th>Club Name</th>
                                                        <th>Request From</th>
                                                        <th>Department</th>
                                                        <th>Course</th>
                                                        <th>Status</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>';

                                        while ($row = $result->fetch()) {
                                            $studentName = htmlspecialchars($row['firstName'] . ' ' . $row['middleName'] . ' ' . $row['lastName']);
                                            $department = htmlspecialchars($row['department']);
                                            $course = htmlspecialchars($row['course']);
                                            $clubName = htmlspecialchars($row['clubName']);
                                            $status = htmlspecialchars($row['status']);
                                            $requestId = htmlspecialchars($row['request_id']);
                                            $profilePic = htmlspecialchars($row['profilePic'] ? $row['profilePic'] : 'default-profile.jpg');
                                            $coverPhoto = htmlspecialchars($row['coverPhoto'] ? $row['coverPhoto'] : 'default-club.jpg'); // Use a default if no cover photo

                                            echo '
                                            <tr class="request-row">
                                                <td class="text-center p-1">
                                                    <img class="club-cover-photo" src="/esas/esas_student/images/' . $coverPhoto . '" 
                                                        alt="' . $clubName . ' cover photo" 
                                                        style="width: 55px; height: 35px; border-radius: 5%; object-fit: cover;">
                                                </td>
                                                <td>' . $clubName . '</td>  <!-- Club data -->
                                                <td class="p-1">
                                                    <img class="student-profile-pic" src="/esas/esas_student/images/' . $profilePic . '" 
                                                        alt="' . $studentName . ' profile picture" 
                                                        style="width: 35px; height: 35px; border-radius: 50%;">
                                                    ' . $studentName . '
                                                </td>
                                                <td>' . $department . '</td>
                                                <td>' . $course . '</td>  <!-- Course data -->
                                                <td>' . $status . '</td>
                                                <td class="text-center">
                                                    <a href="../public/crud/club_requests/club_request_read.php?request_id=' . $requestId . '" class="mr-2" title="View Request" data-toggle="tooltip"><span class="fa fa-eye"></span></a>
                                                    <!--<a href="../public/crud/club_requests/club_request_update.php?request_id=' . $requestId . '" class="mr-2" title="Update Request" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>-->
                                                    <!--<a href="../public/crud/club_requests/club_request_delete.php?request_id=' . $requestId . '" class="text-danger" title="Delete Request" data-toggle="tooltip"><span class="fa fa-trash"></span></a>-->
                                                </td>
                                            </tr>';
                                        }

                                        echo '
                                        </tbody>
                                        </table>
                                        </div>'; // End of table-responsive
                                    } else {
                                        echo '<div class="alert alert-danger"><em>No club requests found.</em></div>';
                                    }
                                } else {
                                    echo "Oops! Something went wrong. Please try again later.";
                                }
                                ?>


                            </div>
                            <!-- ALL STUDENT TABLE END -->

                            <div id="noResultsMessage" class="alert alert-danger p-2 ps-3" style="display: none;">
                                <em>No results found.</em>
                            </div>

                            <script>
                                document.addEventListener('DOMContentLoaded', function () {
                                    const searchInput = document.getElementById('studentSearch');
                                    const statusSelect = document.getElementById('statusSelect');
                                    const requestRows = document.querySelectorAll('.request-row'); // Update class name here
                                    const rowCountDisplay = document.getElementById('rowCountDisplay');
                                    const noResultsMessage = document.getElementById('noResultsMessage');
                                    const totalRows = requestRows.length;

                                    // Retrieve stored filter from localStorage and apply it if present
                                    const storedFilter = localStorage.getItem('selectedStatus');
                                    if (storedFilter) {
                                        statusSelect.value = storedFilter;
                                    }

                                    rowCountDisplay.textContent = `Showing ${totalRows} / ${totalRows} Records`;

                                    // Apply the filter right after loading the page
                                    filterTable();

                                    // Function to handle filtering by status
                                    statusSelect.addEventListener('change', function () {
                                        localStorage.setItem('selectedStatus', statusSelect.value); // Store the selected status in localStorage
                                        filterTable();
                                    });

                                    // Function to handle search filtering
                                    searchInput.addEventListener('input', function () {
                                        filterTable();
                                    });

                                    // Function to filter the table based on both search term and status
                                    function filterTable() {
                                        const searchTerm = searchInput.value.trim().toLowerCase();
                                        const selectedStatus = statusSelect.value;
                                        let visibleRowCount = 0;

                                        requestRows.forEach(function (row) {
                                            const cells = row.querySelectorAll('td');
                                            let rowContainsSearchTerm = false;
                                            let rowMatchesStatus = false;
                                            let rowVisible = true;

                                            const statusCell = row.cells[5].textContent.trim().toLowerCase(); // Adjust based on status column index

                                            // Check if row matches selected status
                                            if (selectedStatus === '' || selectedStatus === statusCell) {
                                                rowMatchesStatus = true;
                                            }

                                            // Check if row matches search term (highlighting logic)
                                            cells.forEach(function (cell) {
                                                cell.innerHTML = removeHighlight(cell.innerHTML); // Remove previous highlights
                                                if (highlightText(cell, searchTerm)) {
                                                    rowContainsSearchTerm = true;
                                                }
                                            });

                                            // Determine if row should be visible
                                            if (!rowContainsSearchTerm && searchTerm !== '') {
                                                rowVisible = false;
                                            }
                                            if (!rowMatchesStatus) {
                                                rowVisible = false;
                                            }

                                            // Toggle row visibility
                                            if (rowVisible) {
                                                row.style.display = '';
                                                visibleRowCount++;
                                            } else {
                                                row.style.display = 'none';
                                            }
                                        });

                                        rowCountDisplay.textContent = `Showing ${visibleRowCount} / ${totalRows} Records`;
                                        noResultsMessage.style.display = (visibleRowCount === 0) ? 'block' : 'none';
                                    }

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