<?php
// Check existence of student_id parameter before processing further
if (isset($_GET["student_id"]) && !empty(trim($_GET["student_id"]))) {
    // Include config file
    require_once "../../config.php";
    
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
                $firstName = !empty($row["firstName"]) ? $row["firstName"] : 'None';
                $middleName = !empty($row["middleName"]) ? $row["middleName"] : 'None';
                $lastName = !empty($row["lastName"]) ? $row["lastName"] : 'None';
                $age = !empty($row["age"]) ? $row["age"] : 'None';
                $birthday = !empty($row["birthday"]) ? $row["birthday"] : 'None';
                $gender = !empty($row["gender"]) ? $row["gender"] : 'None';
                $email = !empty($row["instiEmail"]) ? $row["instiEmail"] : 'None';
                $phoneNumber = !empty($row["phoneNumber"]) ? $row["phoneNumber"] : 'None';
                $department = !empty($row["department"]) ? $row["department"] : 'None';
                $course = !empty($row["course"]) ? $row["course"] : 'None';
                $year = !empty($row["year"]) ? $row["year"] : 'None';
                $street = !empty($row["street"]) ? $row["street"] : 'None';
                $barangay = !empty($row["barangay"]) ? $row["barangay"] : 'None';
                $municipality = !empty($row["municipality"]) ? $row["municipality"] : 'None';
                $province = !empty($row["province"]) ? $row["province"] : 'None';
                $zipcode = !empty($row["zipcode"]) ? $row["zipcode"] : 'None';
                
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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: #031525;
            color: #b5e3ff;
        }
        .wrapper {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 15px;
        }
        .form-group label {
            color: #7cacf8;
        }
        .btn-primary {
            background-color: #7cacf8;
            border: none;
        }
        .btn-primary:hover {
            background-color: #66b2ff;
        }
        .profile-picture img {
            max-width: 100%;
            height: auto;
            border-radius: 50%;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5 mb-3">View Student</h2>
                    <div class="form-group profile-picture">
                        <label><b>Profile Picture:</b></label>
                        <p><img src="<?php echo htmlspecialchars($profilePic); ?>" alt="Profile Picture"></p>
                    </div>
                    <div class="form-group">
                        <label><b>First Name:</b></label>
                        <p><?php echo htmlspecialchars($firstName); ?></p>
                    </div>
                    <div class="form-group">
                        <label><b>Middle Name:</b></label>
                        <p><?php echo htmlspecialchars($middleName); ?></p>
                    </div>
                    <div class="form-group">
                        <label><b>Last Name:</b></label>
                        <p><?php echo htmlspecialchars($lastName); ?></p>
                    </div>
                    <div class="form-group">
                        <label><b>Age:</b></label>
                        <p><?php echo htmlspecialchars($age); ?></p>
                    </div>
                    <div class="form-group">
                        <label><b>Birthday:</b></label>
                        <p><?php echo htmlspecialchars($birthday); ?></p>
                    </div>
                    <div class="form-group">
                        <label><b>Gender:</b></label>
                        <p><?php echo htmlspecialchars($gender); ?></p>
                    </div>
                    <div class="form-group">
                        <label><b>Email:</b></label>
                        <p><?php echo htmlspecialchars($email); ?></p>
                    </div>
                    <div class="form-group">
                        <label><b>Phone Number:</b></label>
                        <p><?php echo htmlspecialchars($phoneNumber); ?></p>
                    </div>
                    <div class="form-group">
                        <label><b>Department:</b></label>
                        <p><?php echo htmlspecialchars($department); ?></p>
                    </div>
                    <div class="form-group">
                        <label><b>Course:</b></label>
                        <p><?php echo htmlspecialchars($course); ?></p>
                    </div>
                    <div class="form-group">
                        <label><b>Year:</b></label>
                        <p><?php echo htmlspecialchars($year); ?></p>
                    </div>
                    <div class="form-group">
                        <label><b>Street:</b></label>
                        <p><?php echo htmlspecialchars($street); ?></p>
                    </div>
                    <div class="form-group">
                        <label><b>Barangay:</b></label>
                        <p><?php echo htmlspecialchars($barangay); ?></p>
                    </div>
                    <div class="form-group">
                        <label><b>Municipality:</b></label>
                        <p><?php echo htmlspecialchars($municipality); ?></p>
                    </div>
                    <div class="form-group">
                        <label><b>Province:</b></label>
                        <p><?php echo htmlspecialchars($province); ?></p>
                    </div>
                    <div class="form-group">
                        <label><b>Zipcode:</b></label>
                        <p><?php echo htmlspecialchars($zipcode); ?></p>
                    </div>
                    <p><a href="#" class="btn btn-primary" onclick="window.history.back(); return false;">Back</a></p>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
