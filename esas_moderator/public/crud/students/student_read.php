<?php
// Check existence of student_id parameter before processing further
// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

if (isset($_GET["application_id"]) && !empty(trim($_GET["application_id"]))) {
    $application_id = trim($_GET["application_id"]);
} else {
    $application_id = 'None'; // Default if not provided
}

// Retrieve fullName
if (isset($_GET["fullName"]) && !empty(trim($_GET["fullName"]))) {
    $fullName = trim($_GET["fullName"]);
} else {
    $fullName = 'Unknown'; // Default if not provided
}

// Retrieve firstName
if (isset($_GET["firstName"]) && !empty(trim($_GET["firstName"]))) {
    $firstName = trim($_GET["firstName"]);
} else {
    $firstName = 'Unknown'; // Default if not provided
}

// Retrieve middleName
if (isset($_GET["middleName"]) && !empty(trim($_GET["middleName"]))) {
    $middleName = trim($_GET["middleName"]);
} else {
    $middleName = 'Unknown'; // Default if not provided
}

// Retrieve lastName
if (isset($_GET["lastName"]) && !empty(trim($_GET["lastName"]))) {
    $lastName = trim($_GET["lastName"]);
} else {
    $lastName = 'Unknown'; // Default if not provided
}

// Retrieve fullName using firstName, middleName, and lastName
$fullName = trim("$firstName $middleName $lastName");


if (isset($_GET["student_id"]) && !empty(trim($_GET["student_id"]))) {
    // Include config file
    require_once "../../../../config.php";
    
    // Prepare a select statement to fetch the student details
    $sql = "SELECT * FROM tbl_students WHERE student_id = :student_id";
    
    if ($stmt = $pdo->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bindParam(":student_id", $param_student_id);
        
        // Set parameters
        $param_student_id = trim($_GET["student_id"]);
        
        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            if ($stmt->rowCount() == 1) {
                // Fetch result row as an associative array
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                // Retrieve individual field values and set default values if necessary
                $firstName = !empty($row["firstName"]) ? $row["firstName"] : '';
                $middleName = !empty($row["middleName"]) ? $row["middleName"] : '';
                $lastName = !empty($row["lastName"]) ? $row["lastName"] : '';
                $fullName = trim("$firstName $middleName $lastName");

                $age = !empty($row["age"]) ? $row["age"] : 'None';
                $birthday = !empty($row["birthday"]) ? date("F j, Y", strtotime($row["birthday"])) : 'None';
                $gender = !empty($row["gender"]) ? $row["gender"] : 'None';
                $email = !empty($row["instiEmail"]) ? $row["instiEmail"] : 'None';
                $phoneNumber = !empty($row["phoneNumber"]) ? $row["phoneNumber"] : 'None';
                $department = !empty($row["department"]) ? $row["department"] : 'None';
                $course = !empty($row["course"]) ? $row["course"] : 'None';
                $year = !empty($row["year"]) ? $row["year"] : 'None';
                $student_id = $row["student_id"]; // Save student ID

                // For current clubs
                $currentClubDetails = 'Not a member of any clubs'; // Default value for now
                $clubSql = "SELECT DISTINCT c.clubName, c.coverPhoto, r.dateApplied, r.dateDecided 
                            FROM tbl_application r 
                            JOIN tbl_clubs c ON r.club_id = c.club_id 
                            WHERE r.student_id = :student_id AND r.status = 'active'"; // Add status condition

                if ($clubStmt = $pdo->prepare($clubSql)) {
                    $clubStmt->bindParam(":student_id", $student_id);
                    if ($clubStmt->execute()) {
                        $clubs = $clubStmt->fetchAll(PDO::FETCH_ASSOC);
                        if (!empty($clubs)) {
                            $currentClubDetails = '';
                            foreach ($clubs as $club) {
                                $clubName = htmlspecialchars($club['clubName']);
                                $dateApplied = !empty($club['dateApplied']) ? date("F j, Y", strtotime($club['dateApplied'])) : 'None';
                                $dateDecided = !empty($club['dateDecided']) ? date("F j, Y", strtotime($club['dateDecided'])) : 'None';
                                
                                // Append each club with its details
                                $currentClubDetails .= "<p><strong class='text-muted'>{$clubName}</strong><br>";
                                $currentClubDetails .= "<small>Date Applied: {$dateApplied}</small><br>";
                                $currentClubDetails .= "<small>Date Approved: {$dateDecided}</small></p>";
                            }
                        }
                    }
                }

                // For previous clubs
                $previousClubDetails = 'No previous club memberships'; // Default value for now
                $clubSql = "SELECT DISTINCT c.clubName, c.coverPhoto, r.dateDecided, r.dateModified 
                            FROM tbl_application r 
                            JOIN tbl_clubs c ON r.club_id = c.club_id 
                            WHERE r.student_id = :student_id AND r.status = 'departed'"; // Add status condition

                if ($clubStmt = $pdo->prepare($clubSql)) {
                    $clubStmt->bindParam(":student_id", $student_id);
                    if ($clubStmt->execute()) {
                        $clubs = $clubStmt->fetchAll(PDO::FETCH_ASSOC);
                        if (!empty($clubs)) {
                            $previousClubDetails = '';  // Initialize previous club details here
                            foreach ($clubs as $club) {
                                $clubName = htmlspecialchars($club['clubName']);
                                $dateDecided = !empty($club['dateDecided']) ? date("F j, Y", strtotime($club['dateDecided'])) : 'None';
                                $dateModified = !empty($club['dateModified']) ? date("F j, Y", strtotime($club['dateModified'])) : 'None';
                                
                                // Append each club with its details
                                $previousClubDetails .= "<p><strong class='text-muted'>{$clubName}</strong><br>";
                                $previousClubDetails .= "<small>Membership Approved: {$dateDecided}</small><br>";
                                $previousClubDetails .= "<small>Date Departed: {$dateModified}</small></p>";
                            }
                        }
                    }
                }

                // Fetch moderators for the clubs the student is part of
                $moderatorNames = 'None'; // Default value for moderators
                $moderatorSql = "SELECT DISTINCT m.firstName, m.middleName, m.lastName 
                                 FROM tbl_clubs_and_moderators cm 
                                 JOIN tbl_moderators m ON cm.moderator_id = m.moderator_id 
                                 JOIN tbl_application r ON cm.club_id = r.club_id 
                                 WHERE r.student_id = :student_id AND r.status = 'active'";
                
                if ($moderatorStmt = $pdo->prepare($moderatorSql)) {
                    $moderatorStmt->bindParam(":student_id", $student_id);
                    if ($moderatorStmt->execute()) {
                        $moderators = $moderatorStmt->fetchAll(PDO::FETCH_ASSOC);
                        $moderatorNames = !empty($moderators) ? implode(", ", array_map(function($mod) {
                            return trim("{$mod['firstName']} {$mod['middleName']} {$mod['lastName']}");
                        }, $moderators)) : 'None';
                    }
                }

                // For profile picture
                $profilePic = !empty($row['profilePic']) ? '/esas/esas_student/images/' . $row['profilePic'] : 'No Image Available';
            } else {
                // Redirect to error page if no valid id is found
                header("location: ../public/error.php");
                exit();
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
    
    // Check if the student is active or inactive in any club
    $isActiveOrInactive = false; // Default to not showing the button

    $statusSql = "SELECT status FROM tbl_application WHERE student_id = :student_id AND (status = 'active' OR status = 'inactive') LIMIT 1";
    if ($statusStmt = $pdo->prepare($statusSql)) {
        $statusStmt->bindParam(":student_id", $student_id);
        if ($statusStmt->execute()) {
            $statusRow = $statusStmt->fetch(PDO::FETCH_ASSOC);
            if ($statusRow && ($statusRow['status'] === 'active' || $statusRow['status'] === 'inactive')) {
                $isActiveOrInactive = true; // Student is active or inactive
            }
        }
    }


    // Close statement and connection after all queries
    unset($stmt);
    unset($statusStmt);
    // unset($pdo);

} else {
    // URL doesn't contain student_id parameter. Redirect to error page
    header("location: ../public/error.php");
    exit();
}

// COUNT THE CURRENT CLUB OF THE STUDENT
$currentClubCountQuery = "SELECT COUNT(application_id) AS current_club_count FROM tbl_application 
                   WHERE student_id = :student_id AND status = 'active'";

$stmt = $pdo->prepare($currentClubCountQuery);
$stmt->bindParam(':student_id', $student_id);
$stmt->execute();

$row = $stmt->fetch(PDO::FETCH_ASSOC);
$currentClubCount = $row['current_club_count'];

$currentClubLabel = $currentClubCount > 1 ? "Current Clubs:" : "Current Club:";

// COUNT THE PREVIOUS CLUB OF THE STUDENT
$previousClubCountQuery = "SELECT COUNT(application_id) AS previous_club_count FROM tbl_application 
                   WHERE student_id = :student_id AND status = 'departed'";

$stmt = $pdo->prepare($previousClubCountQuery);
$stmt->bindParam(':student_id', $student_id);
$stmt->execute();

$row = $stmt->fetch(PDO::FETCH_ASSOC);
$previousClubCount = $row['previous_club_count'];

if ($previousClubCount === 1) {
    $previousClubLabel = "Previous Club:";
} elseif ($previousClubCount > 1) {
    $previousClubLabel = "Previous Clubs:";
} else {
    $previousClubLabel = "Previous Club:";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eSAS - Student Details</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="../../../assets/css/jquery.dataTables.min.css" rel="stylesheet" />
    <script src="../../../assets/js/all.js" crossorigin="anonymous"></script>
    <script src="../../../assets/js/jquery-3.6.0.js"></script>
    <link href="../../../assets/css/styles.css" rel="stylesheet" />
    <link href="../../../assets/img/nbsclogo.png" rel="icon">
    <style>
    @media print {
        /* Hide the modal header and footer */
        .modal-header,
        .modal-footer {
            display: none;
        }

        /* Optionally, you can adjust styles for the modal body if needed */
        .modal-body {
            margin: 0; /* Remove any margins */
        }
    }
</style>

</head>
<body>
<div class="container">
    <div class="row mt-5">
        <div class="col-md-12">
            <div class="card mb-5">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>Student Profile</h3>
                    <!-- <button class="btn btn-info" id="generateIDBtn" data-toggle="modal" data-target="#generateIDModal">Generate ID</button> -->
                     <div>
                        <a href="../../../application_details.php?application_id=<?php echo $application_id; ?>&student_id=<?php echo $student_id; ?>&fullName=<?php echo $fullName; ?>&club_id=<?php echo $_GET['club_id']; ?>" class="btn btn-outline-info">Application Details</a>
                        <a href="../../crud/students/student_generate_id.php?student_id=<?php echo $student_id; ?>&club_id=<?php echo $_GET['club_id']; ?>" class="btn btn-primary">Generate ID</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <img src="<?php echo $profilePic; ?>" 
                                 alt="<?php echo htmlspecialchars($fullName); ?> Profile Picture" 
                                 class="img-fluid rounded-circle mb-4" style="width: 150px; height: 150px;">
                                 
                            <!-- <h4><strong class="text-dark"><?php echo $firstName; ?></strong><br><h6 class="text-small">First Name</h6></h4>
                            <h4><strong class="text-dark"><?php echo $middleName; ?></strong><br><h6 class="text-small">Middle Name</h6></h4>
                            <h4><strong class="text-dark"><?php echo $lastName; ?></strong><br><h6 class="text-small">Last Name</h6></h4> -->
                            
                            <!-- <p>First Name: <strong class="text-dark"><?php echo $firstName; ?></strong></p>
                            <p>Middle Name: <strong class="text-dark"><?php echo $middleName; ?></strong></p>
                            <p>Last Name: <strong class="text-dark"><?php echo $lastName; ?></strong></p> -->
                            
                            <p><strong>First Name: </strong><?php echo $firstName; ?></p>
                            <p><strong>Middle Name: </strong><?php echo $middleName; ?></p>
                            <p><strong>Last Name: </strong><?php echo $lastName; ?></p>
                        </div>
                        <div class="col-md-8">
                            <div class="row col-md-12">
                                <div class="col-md-6">
                                    <!-- <p><strong>Application ID: </strong><?php echo $application_id; ?></p> -->
                                    <p><strong>Student ID: </strong><?php echo $student_id; ?></p>
                                    <p><strong>Email: </strong><?php echo $email; ?></p>
                                    <p><strong>Phone Number: </strong><?php echo $phoneNumber; ?></p>
                                    <p><strong>Department: </strong><?php echo $department; ?></p>
                                    <p><strong>Course: </strong><?php echo $course; ?></p>
                                    <p><strong>Year: </strong><?php echo $year; ?></p>
                                    <p><strong>Gender: </strong><?php echo $gender; ?></p>
                                    <p><strong>Age: </strong><?php echo $age; ?></p>
                                    <p><strong>Birthday: </strong><?php echo $birthday; ?></p>
                                </div>
                                <div class="col-md-6"> 
                                    <p><strong><?php echo $currentClubLabel; ?></strong><br><?php echo $currentClubDetails; ?></p>
                                    <p><strong><?php echo $previousClubLabel; ?></strong><br><?php echo $previousClubDetails; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <?php if ($isActiveOrInactive): ?>
                        <a href="student_update.php?application_id=<?php echo $application_id; ?>&student_id=<?php echo $student_id; ?>&club_id=<?php echo $_GET['club_id']; ?>" class="btn btn-warning">Update</a>
                    <?php endif; ?>
                    <a href="student_delete.php?student_id=<?php echo $student_id; ?>&club_id=<?php echo $_GET['club_id']; ?>" class="btn btn-danger">Delete</a>
                    <a href="../../students.php" class="btn btn-secondary">Go Back</a>
                </div>
            </div>
        </div>

        
    </div>
</div>


    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>




<!-- CLUB NAME -->
                    <!-- <div class="" style="position: absolute; margin-top: 60px; margin-left: -50px; max-width: 100%; transform: rotate(-42.5deg);">
                        <h3 style="color: gold; font-style: italic; transform: skewX(-30deg);"><strong><?php echo htmlspecialchars($clubNames); ?></strong></h3>
                    </div> -->
     <!-- <img class="trapezoid-img" 
            src="/esas/esas_admin/images/COVERPHOTO_MOUNTAINEERINGSOCIETY.png" 
            alt="Mountaineering Society Cover Photo" 
            style="width: 130px; height: 200px; display: block; transform: perspective(400px) rotateX(0deg) rotateY(-60deg) rotateZ(0deg) translateX(200px) translateY(-250px); transform-origin: center left;"> -->


<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
