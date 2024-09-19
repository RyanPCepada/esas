<?php
session_start();
require_once "../../config.php";  // Assuming this file holds your PDO connection

if (!isset($_SESSION['moderator_id'])) {
    echo "Moderator ID is not set in the session.";
    exit;
}

$moderator_id = $_SESSION['moderator_id']; // Get moderator ID from session

try {
    // Use the existing PDO instance from config.php
    global $pdo;

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

    // Fetch distinct students with an active status in tbl_registration
    $sqlStudents = "
        SELECT DISTINCT s.student_id, s.firstName, s.middleName, s.lastName, s.age, s.birthday, s.gender, s.instiEmail, s.phoneNumber, s.department, s.course, s.year, s.street, s.barangay, s.municipality, s.province, s.zipcode, s.profilePic
        FROM tbl_students s
        JOIN tbl_registration r ON s.student_id = r.student_id
        WHERE r.status = 'active'
    ";

    $stmtStudents = $pdo->prepare($sqlStudents);
    $stmtStudents->execute();
    $students = $stmtStudents->fetchAll(PDO::FETCH_ASSOC);

    unset($stmtModerator);
    unset($stmtStudents);
    unset($pdo);

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


            
                <div class="col-md-12">
                    <div class="search-container-top">
                        <input class="form-control" type="search" placeholder="Search" aria-label="Search" id="searchInput">
                        <h6 class="text-center mt-2" id="recordCount">
                            Showing <span id="visibleRows"><?php echo count($students); ?></span> / 
                            <span id="totalRows"><?php echo count($students); ?></span> Records
                        </h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-fixed">
                            <thead>
                                <tr>
                                    <th>Profile Picture</th>
                                    <th>Student ID</th>
                                    <th>First Name</th>
                                    <th>Middle Name</th>
                                    <th>Last Name</th>
                                    <th>Age</th>
                                    <th>Birthday</th>
                                    <th>Gender</th>
                                    <th>Email</th>
                                    <th>Phone Number</th>
                                    <th>Department</th>
                                    <th>Course</th>
                                    <th>Year</th>
                                    <th>Street</th>
                                    <th>Barangay</th>
                                    <th>Municipality</th>
                                    <th>Province</th>
                                    <th>Zipcode</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="studentsTbody">
                                <?php if (count($students) > 0): ?>
                                    <?php foreach ($students as $row): ?>
                                        <tr>
                                            <td>
                                                <?php if ($row['profilePic']): ?>
                                                    <img src="<?php echo htmlspecialchars('/esas/esas_student/images/' . $row['profilePic']); ?>" 
                                                         alt="Profile Picture" 
                                                         class="rounded-circle mr-2" width="50" height="50">
                                                <?php else: ?>
                                                    No Image
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($row['student_id']); ?></td>
                                            <td><?php echo htmlspecialchars($row['firstName']); ?></td>
                                            <td><?php echo htmlspecialchars($row['middleName']); ?></td>
                                            <td><?php echo htmlspecialchars($row['lastName']); ?></td>
                                            <td><?php echo htmlspecialchars($row['age']); ?></td>
                                            <td><?php echo htmlspecialchars($row['birthday']); ?></td>
                                            <td><?php echo htmlspecialchars($row['gender']); ?></td>
                                            <td><?php echo htmlspecialchars($row['instiEmail']); ?></td>
                                            <td><?php echo htmlspecialchars($row['phoneNumber']); ?></td>
                                            <td><?php echo htmlspecialchars($row['department']); ?></td>
                                            <td><?php echo htmlspecialchars($row['course']); ?></td>
                                            <td><?php echo htmlspecialchars($row['year']); ?></td>
                                            <td><?php echo htmlspecialchars($row['street']); ?></td>
                                            <td><?php echo htmlspecialchars($row['barangay']); ?></td>
                                            <td><?php echo htmlspecialchars($row['municipality']); ?></td>
                                            <td><?php echo htmlspecialchars($row['province']); ?></td>
                                            <td><?php echo htmlspecialchars($row['zipcode']); ?></td>
                                            <td>
                                                <a href="crud/student_read.php?student_id=<?php echo htmlspecialchars($row['student_id']); ?>" 
                                                   class="mr-3" title="View Record" data-toggle="tooltip">
                                                   <span class="fa fa-eye"></span>
                                                </a>
                                                <a href="crud/student_delete.php?student_id=<?php echo htmlspecialchars($row['student_id']); ?>" 
                                                   title="Delete Record" data-toggle="tooltip">
                                                   <span class="fa fa-trash"></span>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="18" class="text-center">No students found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- THE MAIN PAGE END -->
        </div>
    </div>
</div>
<!-- MAINPAGE BAR END -->

<script>
// Search function to filter table rows based on input
document.getElementById('searchInput').addEventListener('keyup', function() {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll('#studentsTbody tr');
    let visibleCount = 0;
    
    rows.forEach(row => {
        let rowData = row.textContent.toLowerCase();
        if (rowData.includes(filter)) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });
    
    // Update the visible rows count
    document.getElementById('visibleRows').textContent = visibleCount;
});
</script>



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