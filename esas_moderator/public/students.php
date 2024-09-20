<?php
session_start();
require_once "../../config.php";  // Assuming this file holds your PDO connection

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
        JOIN tbl_registration r ON s.student_id = r.student_id
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
    <title>eSAS - Students</title>
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
            <div class="col-12 col-md-2 ps-0 pt-3 border-end">

                <div class="d-flex flex-column flex-shrink-0 p-3 bg-body-tertiary">
                    <ul class="nav nav-pills flex-column mb-auto">
                        <li>
                            <a href="../../esas_moderator/public/dashboard.php" class="nav-link left-sidebar text-dark" id="all-clubs">
                                <i class="fas fa-chart-line"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../../esas_moderator/public/my_clubs.php" class="nav-link left-sidebar text-dark" aria-current="page" id="my-clubs">
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
                                <table class="table table-bordered table-striped" style="background-color: #f9f9f9;"> <!-- Lighter stripe style -->
                                    <thead>
                                        <tr>
                                            <th>
                                                <input id="studentSearch" class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                                            </th>
                                            <th class="text-center" colspan="8">
                                                <h6 id="rowCountDisplay">Showing 0 / 0 Records</h6> <!-- Updated row count display -->
                                            </th>
                                        </tr>
                                    </thead>
                                </table>

                                <?php
                                // Include config file
                                require_once "../../config.php";

                                // SQL query to fetch all students with their registered clubs and active status
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
                                            GROUP_CONCAT(DISTINCT c.clubName ORDER BY c.clubName ASC SEPARATOR ', ') AS clubNames
                                        FROM tbl_students s
                                        LEFT JOIN tbl_registration r ON s.student_id = r.student_id
                                        LEFT JOIN tbl_clubs c ON r.club_id = c.club_id
                                        WHERE r.status = 'active' -- Filter for active status
                                        GROUP BY s.student_id
                                        ORDER BY s.student_id ASC";

                                if ($result = $pdo->query($sql)) {
                                    $totalRows = $result->rowCount();
                                    $rowCount = 0;

                                    if ($totalRows > 0) {
                                        echo '
                                        <table class="table table-bordered table-striped" style="background-color: #f9f9f9;">
                                            <thead>
                                                <tr>
                                                    <th>Profile</th>
                                                    <th>Name</th>
                                                    <th>Club</th>
                                                    <th>Gender</th>
                                                    <th>Age</th>
                                                    <th>Email</th>
                                                    <th>Phone</th>
                                                    <th>Course</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>';

                                        while ($row = $result->fetch()) {
                                            $formattedDate = date('F j, Y', strtotime($row['student_dateAdded']));
                                            $fullName = htmlspecialchars($row['firstName'] . ' ' . $row['middleName'] . ' ' . $row['lastName']);
                                            $clubNames = htmlspecialchars($row['clubNames']);
                                            $profilePic = htmlspecialchars($row['profilePic'] ? $row['profilePic'] : 'default-profile.jpg');
                                            $gender = htmlspecialchars($row['gender']);
                                            $age = htmlspecialchars($row['age']);
                                            $email = htmlspecialchars($row['instiEmail']);
                                            $phoneNumber = htmlspecialchars($row['phoneNumber']);
                                            $course = htmlspecialchars($row['course']);

                                            $rowCount++;

                                            echo '
                                            <tr class="student-row">
                                                <td class="text-center">
                                                    <img class="student-profile-pic" src="/esas/esas_student/images/' . $profilePic . '" 
                                                        alt="' . $fullName . ' profile picture" 
                                                        style="width: 60px; height: 60px; border-radius: 50%;">
                                                </td>
                                                <td>' . $fullName . '</td>
                                                <td>' . $clubNames . '</td>
                                                <td>' . $gender . '</td>
                                                <td>' . $age . '</td>
                                                <td>' . $email . '</td>
                                                <td>' . $phoneNumber . '</td>
                                                <td>' . $course . '</td>
                                                <td class="text-center">
                                                    <a href="../public/crud/student_read.php?student_id=' . htmlspecialchars($row['student_id']) . '" class="mr-2" title="View Record" data-toggle="tooltip"><span class="fa fa-eye"></span></a>
                                                    <a href="../public/crud/student_update.php?student_id=' . htmlspecialchars($row['student_id']) . '" class="mr-2" title="Update Record" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>
                                                    <a href="../public/crud/student_delete.php?student_id=' . htmlspecialchars($row['student_id']) . '" title="Delete Record" data-toggle="tooltip"><span class="fa fa-trash"></span></a>
                                                </td>
                                            </tr>';
                                        }

                                        echo '
                                            </tbody>
                                        </table>';
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
                                <em>No results found.</em>
                            </div>

                            <script>
                                document.addEventListener('DOMContentLoaded', function () {
                                    const searchInput = document.getElementById('studentSearch');
                                    const studentRows = document.querySelectorAll('.student-row');
                                    const rowCountDisplay = document.getElementById('rowCountDisplay');
                                    const noResultsMessage = document.getElementById('noResultsMessage');
                                    const totalRows = studentRows.length;

                                    rowCountDisplay.textContent = `Showing ${totalRows} / ${totalRows} Records`;

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

                                            if (rowContainsTerm) {
                                                row.style.display = '';
                                                visibleRowCount++;
                                            } else {
                                                row.style.display = 'none';
                                            }
                                        });

                                        rowCountDisplay.textContent = `Showing ${visibleRowCount} / ${totalRows} Records`;
                                        noResultsMessage.style.display = (visibleRowCount === 0) ? 'block' : 'none';
                                    });

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
    $(document).ready(function() {
        $('.delprreq').click(function(e) {
            e.stopPropagation();
        });
        // let value= $("classname").val()
    });
</script>

</body>
</html>