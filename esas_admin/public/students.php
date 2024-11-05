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
                        <a href="../../esas_admin/public/students.php" class="nav-link left-sidebar text-dark active" aria-current="page" id="students">
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
                <div class="row g-0 h-100">
                    <div class="row g-0 p-4 px-2 pt-2 h-100">

                        <!-- THE MAIN PAGE START -->
                        <div class="card p-2">

                            <!-- ALL STUDENT TABLE START -->
                            <div class="row card-row1 col-md-12 mb-1" style="border: 1px solid transparent; margin: 0;">
                                
                                <div class="mt-1 mb-3 d-flex justify-content-between align-items-center">
                                    <h4 class="text-muted mb-0">Students Record</h4>
                                    <a href="../public/crud/students/student_create.php" class="btn btn-danger disabled" style="visibility: hidden;">
                                        <i class="fa fa-plus"></i> Add New Student
                                    </a>
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
                                                                <?php
                                                                // Fetch clubs from tbl_clubs
                                                                $clubSql = "SELECT club_id, clubName FROM tbl_clubs";
                                                                $clubs = $pdo->query($clubSql);
                                                                while ($club = $clubs->fetch()) {
                                                                    echo '<option value="' . htmlspecialchars($club['club_id']) . '">' . htmlspecialchars($club['clubName']) . '</option>';
                                                                }
                                                                ?>
                                                            </optgroup>
                                                        </select>
                                                        <input id="studentSearch" class="form-control" type="search" placeholder="Search for students here..." aria-label="Search">
                                                    </div>
                                                    <div class="col-12 col-md-4 d-flex align-items-center justify-content-center mt-2">
                                                        <h6 id="rowCountDisplay">Showing 0 / 0 Records</h6> <!-- Updated row count display -->
                                                    </div>
                                                </div>
                                            </th>
                                        </tr>
                                    </thead>
                                </table>

                                <?php
                                    // Include config file
                                    require_once "../../config.php";

                                    $club_id = isset($_GET['club_id']) ? $_GET['club_id'] : null;


                                    
                                    $sql = "SELECT 
                                    s.student_id,
                                    s.firstName,
                                    s.middleName,
                                    s.lastName,
                                    s.instiEmail,
                                    s.phoneNumber,
                                    s.department,
                                    s.course,
                                    s.year,
                                    s.profilePic,
                                    r.application_id,
                                    r.club_id,  -- Add this line to select club_id
                                    GROUP_CONCAT(DISTINCT c.clubName ORDER BY c.clubName ASC SEPARATOR ', ') AS clubNames
                                FROM 
                                    tbl_students s
                                LEFT JOIN 
                                    tbl_application r ON s.student_id = r.student_id AND r.status = 'active' 
                                LEFT JOIN 
                                    tbl_clubs c ON r.club_id = c.club_id
                                WHERE
                                    r.status = 'active' 
                                GROUP BY 
                                    s.student_id, s.firstName, s.middleName, s.lastName, s.instiEmail, s.phoneNumber, s.department, s.course, s.year, s.profilePic
                                ORDER BY 
                                    s.student_id ASC;";

                            
                            if ($result = $pdo->query($sql)) {
                                $totalRows = $result->rowCount();
                            
                                if ($totalRows > 0) {
                                    echo '
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped" style="background-color: #f9f9f9;">
                                            <thead>
                                                <tr>
                                                    <!-- <th>Application ID</th> -->
                                                    <!-- <th>Club ID</th> -->
                                                    <th></th>
                                                    <th>Full Name</th>
                                                    <th>Department</th>
                                                    <th>Course</th>
                                                    <th>Club</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>';
                            
                                            while ($row = $result->fetch()) {
                                                $application_id = htmlspecialchars($row['application_id']);
                                                $club_id = isset($row['club_id']) ? htmlspecialchars($row['club_id']) : ''; // Check if club_id is set
                                                $firstName = htmlspecialchars($row['firstName']);
                                                $middleName = htmlspecialchars($row['middleName']);
                                                $lastName = htmlspecialchars($row['lastName']);
                                                $fullName = htmlspecialchars($row['firstName'] . ' ' . $row['middleName'] . ' ' . $row['lastName']);
                                                $clubNames = htmlspecialchars($row['clubNames']);
                                                $profilePic = htmlspecialchars($row['profilePic'] ? $row['profilePic'] : 'default-profile.jpg');
                            
                                                echo '
                                                <tr class="student-row" data-club="' . htmlspecialchars($clubNames) . '">
                                                    <!-- <td>' . $application_id . '</td> -->
                                                    <!-- <td>' . $club_id . '</td> -->
                                                    <td class="text-center p-1">
                                                        <img class="student-profile-pic" src="/esas/esas_student/images/' . $profilePic . '" 
                                                            alt="' . $fullName . ' profile picture" 
                                                            style="width: 35px; height: 35px; border-radius: 50%;">
                                                    </td>
                                                    <td>' . $fullName . '</td>
                                                    <td>' . htmlspecialchars($row['department']) . '</td>
                                                    <td>' . htmlspecialchars($row['course']) . '</td>
                                                    <td>' . $clubNames . '</td>
                                                    <td class="text-center">
                                                        <a href="../public/crud/students/student_read.php?application_id=' . htmlspecialchars($row['application_id']) . '&student_id=' . htmlspecialchars($row['student_id']) . '&club_id=' . htmlspecialchars($club_id) 
                                                        . '&fullName=' . htmlspecialchars($fullName) . '&firstName=' . htmlspecialchars($firstName) . '&middleName=' . htmlspecialchars($middleName) . '&lastName=' . htmlspecialchars($lastName) . '" class="mr-2" title="View Record" data-toggle="tooltip"><span class="fa fa-eye"></span></a>
                                                    </td>
                                                </tr>';
                                            }
                            
                                            echo '
                                            </tbody>
                                        </table>
                                    </div>'; // End of table-responsive
                                } else {
                                    echo '<div class="alert alert-danger"><em>No students were found.</em></div>';
                                }
                            } else {
                                echo "Oops! Something went wrong. Please try again later.";
                            }
                            
                                ?>

                            </div>
                            <!-- ALL STUDENT TABLE END -->

                            <div id="noResultsMessage" class="alert alert-danger p-2 ps-3" style="display: none;">
                                <em>No students found.</em>
                            </div>

                            <script>
                                document.addEventListener('DOMContentLoaded', function () {
                                    const searchInput = document.getElementById('studentSearch');
                                    const clubSelect = document.getElementById('clubSelect');
                                    const studentRows = document.querySelectorAll('.student-row');
                                    const rowCountDisplay = document.getElementById('rowCountDisplay');
                                    const noResultsMessage = document.getElementById('noResultsMessage');
                                    const totalRows = studentRows.length;

                                    rowCountDisplay.textContent = `Showing ${totalRows} / ${totalRows} Records`;

                                    function applyFilters() {
                                        const selectedClub = clubSelect.value;
                                        const searchTerm = searchInput.value.trim().toLowerCase();
                                        let visibleRowCount = 0;

                                        studentRows.forEach(function (row) {
                                            const clubCell = row.getAttribute('data-club').toLowerCase(); // Club data for filtering
                                            const cells = row.querySelectorAll('td');
                                            let rowContainsTerm = false;

                                            // Reset cell content and apply highlight if it matches the search term
                                            cells.forEach(function (cell) {
                                                cell.innerHTML = removeHighlight(cell.innerHTML);
                                                if (highlightText(cell, searchTerm)) {
                                                    rowContainsTerm = true;
                                                }
                                            });

                                            // Display row only if it matches both club selection and search term
                                            if ((selectedClub === '' || clubCell.includes(clubSelect.options[clubSelect.selectedIndex].text.toLowerCase())) && rowContainsTerm) {
                                                row.style.display = '';
                                                visibleRowCount++;
                                            } else {
                                                row.style.display = 'none';
                                            }
                                        });

                                        rowCountDisplay.textContent = `Showing ${visibleRowCount} / ${totalRows} Records`;
                                        noResultsMessage.style.display = (visibleRowCount === 0) ? 'block' : 'none';
                                    }

                                    // Attach applyFilters to both events
                                    clubSelect.addEventListener('change', applyFilters);
                                    searchInput.addEventListener('input', applyFilters);

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