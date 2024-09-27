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
                $clubNames = 'None'; // Default value for now
                $clubSql = "SELECT c.clubName, c.coverPhoto FROM tbl_registration r 
                    JOIN tbl_clubs c ON r.club_id = c.club_id 
                    WHERE r.student_id = :student_id AND r.status = 'active'"; // Add status condition
                
                if ($clubStmt = $pdo->prepare($clubSql)) {
                    $clubStmt->bindParam(":student_id", $student_id);
                    if ($clubStmt->execute()) {
                        $clubs = $clubStmt->fetchAll(PDO::FETCH_COLUMN);
                        $clubNames = !empty($clubs) ? implode(", ", $clubs) : 'None';
                    }
                }
                // Fetch moderators for the clubs the student is part of
                $moderatorNames = 'None'; // Default value for moderators
                $moderatorSql = "SELECT m.firstName, m.middleName, m.lastName 
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
    <div class="row p-5 text-center align-items-center justify-content-center">


        <div class="card" style="border: none; border-radius: 20px; width: 350px; height: 550px; background-image: url('/esas/esas_admin/images/COVERPHOTO_ARTSSOCIETY.png'); background-size: 100% 100%; background-position: center;">

            <img src="/esas/esas_admin/images/ID_BACKGROUND.png" 
                style="width: 350px; height: 550px; border-radius: 20px; 
                        opacity: 0.8;">



                

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

                    <div class="text-center" style="position: absolute; top: 40px; left: 0; right: 0; margin: 20px auto; max-width: 90%;">
                        <h2 style="color: gold; line-height: 1; text-shadow: 0 3px 3px rgba(0, 0, 0, .5);">
                            <em><strong><?php echo htmlspecialchars($clubNames); ?></strong></em>
                        </h2>
                    </div>

                    <!-- Profile Pic -->
                    <img src="<?php echo $profilePic; ?>" 
                        alt="<?php echo htmlspecialchars($fullName); ?> Profile Picture" 
                        style="width: 125px; height: 125px; border: solid 5px white; border-radius: 50%;
                        margin-top: 90px; margin-bottom: 10px; box-shadow: 0 5px 10px rgba(0, 0, 0, 0.5);">

                    <!-- Student Info -->
                    <h3 style="color: black; text-shadow: 
                                2px 2px 0 rgba(255, 255, 255, 1),  
                                -2px -2px 0 rgba(255, 255, 255, 1),  
                                2px -2px 0 rgba(255, 255, 255, 1),  
                                -2px 2px 0 rgba(255, 255, 255, 1),  
                                0 0 5px rgba(0, 0, 0, 0.7);">
                        <strong><?php echo htmlspecialchars($fullName); ?></strong>
                    </h3>

                    <div style="margin-top: 20px;">
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
                    <div class="" style="position: relative; margin-bottom: -40px; color: black;">
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
    </div>
</div>

</body>
</html>