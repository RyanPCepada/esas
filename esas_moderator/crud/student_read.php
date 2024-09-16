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
    <link href="../assets/css/jquery.dataTables.min.css" rel="stylesheet" />
    <script src="../assets/js/all.js" crossorigin="anonymous"></script>
    <script src="../assets/js/jquery-3.6.0.js"></script>
    <link href="../assets/css/styles.css" rel="stylesheet" />
    <link href="../assets/img/nbsclogo.png" rel="icon">
    <style>
    body {
        font-family: Arial, sans-serif;
        color: #333;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
    }
    .wrapper {
        width: 100%;
        max-width: 600px;
        margin: 30px auto;
        padding: 20px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    h2 {
        color: #2c3e50;
        border-bottom: 2px solid #3498db;
        padding-bottom: 10px;
        margin-bottom: 20px;
    }
    .form-group {
        margin-bottom: 15px;
        padding: 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
        background-color: #f9f9f9;
    }
    .form-group h3 {
        margin-top: 0;
        margin-bottom: 10px;
    }
    .form-group .row {
        display: flex;
        flex-direction: column;
        margin: -5px;
    }
    .form-group .row div {
        display: flex;
        margin: 5px 0;
    }
    .form-group .row div label {
        width: 150px;
        font-weight: bold;
        color: #3498db;
    }
    .form-group .row div p {
        margin: 0;
        padding: 0;
        line-height: 1.6;
    }
    .profile-picture {
        text-align: center;
        margin-bottom: 20px;
        padding: 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
        background-color: #f9f9f9;
    }
    .profile-picture img {
        max-width: 150px;
        height: auto;
        border-radius: 50%;
        border: 3px solid #3498db;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    }
    .btn-primary {
        background-color: #3498db;
        color: #fff;
        padding: 10px 15px;
        border: none;
        border-radius: 5px;
        text-decoration: none;
        display: inline-block;
        transition: background-color 0.3s ease;
    }
    .btn-primary:hover {
        background-color: #2980b9;
    }
</style>


</head>
<body>
<div class="wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="profile-picture">
                    <p><img src="<?php echo htmlspecialchars($profilePic); ?>" alt="Profile Picture"></p>
                </div>
                <div class="form-group">
                    <h3>Personal Information</h3>
                    <div class="row">
                        <div>
                            <label><b>First Name:</b></label>
                            <p><?php echo htmlspecialchars($firstName); ?></p>
                        </div>
                        <div>
                            <label><b>Middle Name:</b></label>
                            <p><?php echo htmlspecialchars($middleName); ?></p>
                        </div>
                        <div>
                            <label><b>Last Name:</b></label>
                            <p><?php echo htmlspecialchars($lastName); ?></p>
                        </div>
                        <div>
                            <label><b>Age:</b></label>
                            <p><?php echo htmlspecialchars($age); ?></p>
                        </div>
                        <div>
                            <label><b>Birthday:</b></label>
                            <p><?php echo htmlspecialchars($birthday); ?></p>
                        </div>
                        <div>
                            <label><b>Gender:</b></label>
                            <p><?php echo htmlspecialchars($gender); ?></p>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <h3>Contact Information</h3>
                    <div class="row">
                        <div>
                            <label><b>Email:</b></label>
                            <p><?php echo htmlspecialchars($email); ?></p>
                        </div>
                        <div>
                            <label><b>Phone Number:</b></label>
                            <p><?php echo htmlspecialchars($phoneNumber); ?></p>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <h3>Academic Information</h3>
                    <div class="row">
                        <div>
                            <label><b>Department:</b></label>
                            <p><?php echo htmlspecialchars($department); ?></p>
                        </div>
                        <div>
                            <label><b>Course:</b></label>
                            <p><?php echo htmlspecialchars($course); ?></p>
                        </div>
                        <div>
                            <label><b>Year:</b></label>
                            <p><?php echo htmlspecialchars($year); ?></p>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <h3>Address Information</h3>
                    <div class="row">
                        <div>
                            <label><b>Street:</b></label>
                            <p><?php echo htmlspecialchars($street); ?></p>
                        </div>
                        <div>
                            <label><b>Barangay:</b></label>
                            <p><?php echo htmlspecialchars($barangay); ?></p>
                        </div>
                        <div>
                            <label><b>Municipality:</b></label>
                            <p><?php echo htmlspecialchars($municipality); ?></p>
                        </div>
                        <div>
                            <label><b>Province:</b></label>
                            <p><?php echo htmlspecialchars($province); ?></p>
                        </div>
                        <div>
                            <label><b>Zipcode:</b></label>
                            <p><?php echo htmlspecialchars($zipcode); ?></p>
                        </div>
                    </div>
                </div>
                <p><a href="#" class="btn-primary" onclick="window.history.back(); return false;">Back</a></p>
            </div>
        </div>        
    </div>
</div>

    </body>

</html>