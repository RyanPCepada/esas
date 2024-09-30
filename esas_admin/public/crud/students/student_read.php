<?php
// Check existence of student_id parameter before processing further
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

                // For clubs
                $clubDetails = 'None'; // Default value for now
                $clubSql = "SELECT DISTINCT c.clubName, c.coverPhoto, r.dateApplied, r.dateApproved 
                            FROM tbl_registration r 
                            JOIN tbl_clubs c ON r.club_id = c.club_id 
                            WHERE r.student_id = :student_id AND r.status = 'active'"; // Add status condition

                if ($clubStmt = $pdo->prepare($clubSql)) {
                    $clubStmt->bindParam(":student_id", $student_id);
                    if ($clubStmt->execute()) {
                        $clubs = $clubStmt->fetchAll(PDO::FETCH_ASSOC);
                        if (!empty($clubs)) {
                            $clubDetails = '';
                            foreach ($clubs as $club) {
                                $clubName = htmlspecialchars($club['clubName']);
                                $dateApplied = !empty($club['dateApplied']) ? date("F j, Y", strtotime($club['dateApplied'])) : 'None';
                                $dateApproved = !empty($club['dateApproved']) ? date("F j, Y", strtotime($club['dateApproved'])) : 'None';
                                
                                // Append each club with its details
                                $clubDetails .= "<p><strong class='text-muted'>{$clubName}</strong><br>";
                                $clubDetails .= "<small>Date Applied: {$dateApplied}</small><br>";
                                $clubDetails .= "<small>Date Approved: {$dateApproved}</small></p>";
                            }
                        }
                    }
                }

                // Fetch moderators for the clubs the student is part of
                $moderatorNames = 'None'; // Default value for moderators
                $moderatorSql = "SELECT DISTINCT m.firstName, m.middleName, m.lastName 
                                 FROM tbl_clubs_and_moderators cm 
                                 JOIN tbl_moderators m ON cm.moderator_id = m.moderator_id 
                                 JOIN tbl_registration r ON cm.club_id = r.club_id 
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
     
    // Close statement
    unset($stmt);
    
    // Close connection
    unset($pdo);
} else {
    // URL doesn't contain student_id parameter. Redirect to error page
    header("location: ../public/error.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Details</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
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
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>Student Profile</h3>
                    <button class="btn btn-info" id="generateIDBtn" data-toggle="modal" data-target="#generateIDModal">Generate ID</button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <img src="<?php echo $profilePic; ?>" 
                                 alt="<?php echo htmlspecialchars($fullName); ?> Profile Picture" 
                                 class="img-fluid rounded-circle" style="width: 150px; height: 150px;">
                        </div>
                        <div class="col-md-8">
                            <h3 class="text-muted mb-3"><?php echo htmlspecialchars($fullName); ?></h3>
                            <hr>
                            <div class="row col-md-12">
                                <div class="col-md-6">
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
                                    <p><strong>Clubs:</strong><br><?php echo $clubDetails; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <a href="student_update.php?student_id=<?php echo $student_id; ?>" class="btn btn-warning">Update</a>
                    <a href="student_delete.php?student_id=<?php echo $student_id; ?>" class="btn btn-danger">Delete</a>
                    <a href="javascript:window.history.back();" class="btn btn-secondary">Back to Students List</a>
                </div>
            </div>
        </div>

        
    </div>
</div>




<!-- Modal -->
<div class="modal fade" id="generateIDModal" tabindex="-1" aria-labelledby="generateIDModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="generateIDModalLabel">Student Club ID</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>


                <div class="modal-body p-5">

                    <div class="card" style="border: none;">
                        <!-- ID Card Section Start -->
                        <div style="position: relative; width: 100%; height: 100%;">

                            <!-- ID Background Image -->
                            <div style="position: relative; width: 100%; height: auto; border-radius: 10px; overflow: hidden; z-index: 2;">
                                
                                <div style="position: relative; display: inline-block;">
                                    <img src="/esas/esas_admin/images/ID_BACKGROUND.png" alt="ID Generator" 
                                        style="width: 100%; height: auto; border-radius: 10px; position: relative; z-index: 1; 
                                                filter: drop-shadow(0 0 25px white);">
                                </div>

                                
                                <img class="trapezoid-img" 
                                    src="/esas/esas_admin/images/COVERPHOTO_ARTSSOCIETY.png" 
                                    alt="Mountaineering Society Cover Photo" 
                                    style="margin-top: 50px; margin-left: -204px; width: 37%; height: 170px; display: block; opacity: 1;
                                    transform: perspective(410px) rotateX(0deg) rotateY(-56deg) rotateZ(0deg) translateX(200px) translateY(-165px); transform-origin: center left;
                                    z-index: -1">

                            </div>



                            <!-- Overlay Content -->
                            <div class="text-center" style="position: absolute; top: 2%; left: 50%; transform: translateX(-50%); text-align: center; color: white; width: 90%; z-index: 2000;">
                                
                                <div class="row d-flex align-items-center">
                                    <div class="ml-2">
                                        <img src="../../../../assets/img/nbsclogo.png" style="height: 0.5in; margin-right: 10px; filter: drop-shadow(0px 3px 5px rgba(0, 0, 0, 0.5));">
                                    </div>
                                    <div class="text-start" style="line-height: 1; margin-top: -13px; text-align: left;">
                                        <p style="font-size: 8px; margin: 0; text-shadow: 0 3px 5px rgba(0, 0, 0, .5);">REPUBLIC OF THE PHILIPPINES</p>
                                        <p style="font-size: 11px; margin: 0; text-shadow: 0 3px 5px rgba(0, 0, 0, .5);"><strong>NORTHERN BUKIDNON STATE COLLEGE</strong></p>
                                        <p style="font-size: 9px; margin: 0; text-shadow: 0 3px 5px rgba(0, 0, 0, .5);">Kihare, Manolo Fortich, Bukidnon</p>
                                    </div>
                                </div>

                                <div class="text-center" style="position: absolute; top: 50px; left: 0; right: 0; margin: 20px auto; max-width: 90%;">
                                    <h2 style="color: gold; line-height: 1; text-shadow: 0 3px 3px rgba(0, 0, 0, .5);">
                                        <em><strong><?php echo htmlspecialchars($clubNames); ?></strong></em>
                                    </h2>
                                </div>

                                <!-- Profile Pic -->
                                <img src="<?php echo $profilePic; ?>" 
                                    alt="<?php echo htmlspecialchars($fullName); ?> Profile Picture" 
                                    style="width: 150px; height: 150px; border: solid 5px white; border-radius: 50%;
                                    margin-top: 110px; margin-bottom: 10px;
                                    box-shadow: 0 5px 10px rgba(0, 0, 0, .5);">

                                <!-- Student Info -->
                                <h3 style="color: black; 
                                            text-shadow: 
                                                2px 2px 0 rgba(255, 255, 255, 1),  
                                                -2px -2px 0 rgba(255, 255, 255, 1),  
                                                2px -2px 0 rgba(255, 255, 255, 1),  
                                                -2px 2px 0 rgba(255, 255, 255, 1),  
                                                0 0 5px rgba(0, 0, 0, 0.7);">
                                    <strong><?php echo htmlspecialchars($fullName); ?></strong>
                                </h3>


                                <div style="margin-top: 20px; line-height: .5;">
                                    <h6 style="color: black; text-shadow: 
                                                0 0 5px rgba(255, 255, 255, 0.7),  
                                                0 0 10px rgba(255, 255, 255, 0.5),  
                                                0 0 15px rgba(255, 255, 255, 0.3);">
                                        Student ID: <?php echo $student_id; ?>
                                    </h6>
                                    <h6 style="color: black; text-shadow: 
                                                0 0 5px rgba(255, 255, 255, 0.7),  
                                                0 0 10px rgba(255, 255, 255, 0.5),  
                                                0 0 15px rgba(255, 255, 255, 0.3);">
                                        Email: <?php echo $email; ?>
                                    </h6>
                                    <h6 style="color: black; text-shadow: 
                                                0 0 5px rgba(255, 255, 255, 0.7),  
                                                0 0 10px rgba(255, 255, 255, 0.5),  
                                                0 0 15px rgba(255, 255, 255, 0.3);">
                                        Phone: <?php echo $phoneNumber; ?>
                                    </h6>
                                </div>

                                <!-- MODERATORS' DIV -->
                                <div class="" style="margin-top: 80px; color: black;">
                                    <strong>Moderators:<br></strong>
                                    <?php
                                    // Assuming $moderatorNames is a string with names separated by commas
                                    $moderatorArray = explode(',', $moderatorNames);
                                    foreach ($moderatorArray as $moderator) {
                                        echo '<h6><span style="margin-top: 8px; display: block;">' . htmlspecialchars(trim($moderator)) . '</span></h6>';
                                    }
                                    ?>
                                </div>
                            </div>

                            

                        </div>
                        <!-- ID Card Section End -->
                    </div>

                    <div style="text-align: center; margin-top: 20px;">
                        <button id="downloadPDF" class="btn btn-primary" onclick="downloadPDF()">Download PDF</button>
                        <button id="printID" class="btn btn-secondary" onclick="printID()">Print</button>
                    </div>
                    <script>
                        function downloadPDF() {
                            alert("Download PDF functionality not implemented yet.");
                        }

                        function printID() {
                            window.print();
                        }
                    </script>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
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
