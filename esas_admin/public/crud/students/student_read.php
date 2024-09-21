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
                $clubSql = "SELECT c.clubName FROM tbl_registration r 
                    JOIN tbl_clubs c ON r.club_id = c.club_id 
                    WHERE r.student_id = :student_id AND r.status = 'active'"; // Add status condition
                
                if ($clubStmt = $pdo->prepare($clubSql)) {
                    $clubStmt->bindParam(":student_id", $student_id);
                    if ($clubStmt->execute()) {
                        $clubs = $clubStmt->fetchAll(PDO::FETCH_COLUMN);
                        $clubNames = !empty($clubs) ? implode(", ", $clubs) : 'None';
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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <div class="row mt-5">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3>Student Profile</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <img src="<?php echo $profilePic; ?>" 
                                 alt="<?php echo htmlspecialchars($fullName); ?> Profile Picture" 
                                 class="img-fluid rounded-circle" style="width: 150px; height: 150px;">
                        </div>
                        <div class="col-md-9">
                            <h3 class="text-muted mb-3"><?php echo htmlspecialchars($fullName); ?></h3>
                            <hr>
                            <p><strong>Student ID: </strong><?php echo $student_id; ?></p>
                            <p><strong>Email: </strong><?php echo $email; ?></p>
                            <p><strong>Phone Number: </strong><?php echo $phoneNumber; ?></p>
                            <p><strong>Department: </strong><?php echo $department; ?></p>
                            <p><strong>Course: </strong><?php echo $course; ?></p>
                            <p><strong>Year: </strong><?php echo $year; ?></p>
                            <p><strong>Gender: </strong><?php echo $gender; ?></p>
                            <p><strong>Age: </strong><?php echo $age; ?></p>
                            <p><strong>Birthday: </strong><?php echo $birthday; ?></p>
                            <p><strong>Clubs: </strong><?php echo $clubNames; ?></p>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="student_update.php?student_id=<?php echo $student_id; ?>" class="btn btn-warning">Update</a>
                    <a href="student_delete.php?student_id=<?php echo $student_id; ?>" class="btn btn-danger">Delete</a>
                    <a href="javascript:window.history.back();" class="btn btn-secondary">Back to Students List</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
