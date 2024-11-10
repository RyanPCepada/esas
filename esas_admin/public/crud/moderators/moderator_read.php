<?php
// Include config file
require_once "../../../../config.php";

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Check if the moderator_id parameter exists
if (isset($_GET["moderator_id"]) && !empty(trim($_GET["moderator_id"]))) {
    // Get the moderator_id from the query string
    $moderator_id = trim($_GET["moderator_id"]);

    // Prepare the SQL query to fetch moderator details and associated clubs
    $sql = "SELECT 
                m.moderator_id,
                m.firstName,
                m.middleName,
                m.lastName,
                m.age,
                m.birthday,
                m.gender,
                m.email,
                m.phoneNumber,
                m.department,
                m.profession,
                m.profilePic,
                GROUP_CONCAT(DISTINCT c.clubName ORDER BY c.clubName ASC SEPARATOR ', ') AS clubNames
            FROM tbl_moderators m
            LEFT JOIN tbl_clubs_and_moderators cm ON m.moderator_id = cm.moderator_id
            LEFT JOIN tbl_clubs c ON cm.club_id = c.club_id
            WHERE m.moderator_id = :moderator_id
            GROUP BY m.moderator_id";

    // Prepare the statement
    if ($stmt = $pdo->prepare($sql)) {
        // Bind the parameter
        $stmt->bindParam(":moderator_id", $moderator_id, PDO::PARAM_INT);

        // Execute the prepared statement
        if ($stmt->execute()) {
            // Check if the moderator was found
            if ($stmt->rowCount() == 1) {
                // Fetch the row data
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                // Retrieve the details
                $fullName = htmlspecialchars($row["firstName"] . " " . $row["middleName"] . " " . $row["lastName"]);
                $age = !empty($row["age"]) ? htmlspecialchars($row["age"]) : 'None';
                $birthday = !empty($row["birthday"]) ? htmlspecialchars($row["birthday"]) : 'None';
                $gender = !empty($row["gender"]) ? htmlspecialchars($row["gender"]) : 'None';
                $email = !empty($row["email"]) ? htmlspecialchars($row["email"]) : 'None';
                $phoneNumber = !empty($row["phoneNumber"]) ? htmlspecialchars($row["phoneNumber"]) : 'None';
                $department = !empty($row["department"]) ? htmlspecialchars($row["department"]) : 'None';
                $profession = !empty($row["profession"]) ? htmlspecialchars($row["profession"]) : 'None';
                $clubNames = !empty($row["clubNames"]) ? htmlspecialchars($row["clubNames"]) : 'None';
                $profilePic = !empty($row["profilePic"]) ? htmlspecialchars($row["profilePic"]) : "default-profile.jpg";
            } else {
                // Redirect if no record is found
                header("location: error.php");
                exit();
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
    }

    // Close statement
    unset($stmt);
} else {
    // Redirect if the moderator_id parameter is missing
    header("location: error.php");
    exit();
}

// Close connection
unset($pdo);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ESAS - Moderator Details</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
    <link href="../../../assets/css/jquery.dataTables.min.css" rel="stylesheet" />
    <script src="../../../assets/js/all.js" crossorigin="anonymous"></script>
    <script src="../../../assets/js/jquery-3.6.0.js"></script>
    <link href="../../../assets/css/styles.css" rel="stylesheet" />
    <link href="../../../assets/img/nbsclogo.png" rel="icon">
</head>
<body>
    <div class="container">
        <div class="row mt-5">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3>Moderator Profile</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 text-center">
                                <img src="/esas/esas_moderator/images/<?php echo $profilePic; ?>" 
                                     alt="<?php echo $fullName; ?> Profile Picture" 
                                     class="img-fluid rounded-circle" style="width: 150px; height: 150px;">
                            </div>
                            <div class="col-md-9">
                                <h3 class="text-muted mb-3"><?php echo $fullName; ?></h3>
                                <hr>
                                <p><strong>Email: </strong><?php echo $email; ?></p>
                                <p><strong>Phone Number: </strong><?php echo $phoneNumber; ?></p>
                                <p><strong>Department: </strong><?php echo $department; ?></p>
                                <p><strong>Profession: </strong><?php echo $profession; ?></p>
                                <p><strong>Gender: </strong><?php echo $gender; ?></p>
                                <p><strong>Age: </strong><?php echo $age; ?></p>
                                <p><strong>Birthday: </strong><?php echo $birthday; ?></p>
                                <p><strong>Clubs: </strong><?php echo $clubNames; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <a href="moderator_update.php?moderator_id=<?php echo $moderator_id; ?>" class="btn btn-warning">Update</a>
                        <a href="moderator_delete.php?moderator_id=<?php echo $moderator_id; ?>" class="btn btn-danger">Delete</a>
                        <a href="javascript:window.history.back();" class="btn btn-secondary">Go Back</a>
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



<!-- <tr class="moderator-row">
    <td class="text-center">
        <img class="moderator-profile-pic" src="/esas/esas_moderator/images/' . $profilePic . '" 
            alt="' . $fullName . ' profile picture" 
            style="width: 50px; height: 50px; border-radius: 50%;">
    </td>
    <td class="moderator-name"><?php echo $fullName?></td>
    <td class="moderator-club"><?php echo $clubNames?></td>
    <td class="moderator-email"><?php echo $email?></td>
    <td class="moderator-phone"><?php echo $phoneNumber?></td>
    <td class="moderator-department"><?php echo $department?></td>
    <td class="text-center">
        <a href="../public/crud/moderators/moderator_read.php?moderator_id=' . htmlspecialchars($row['moderator_id']) . '" class="mr-2" title="View Record" data-toggle="tooltip"><span class="fa fa-eye"></span></a>
        <a href="../public/crud/moderators/moderator_update.php?moderator_id=' . htmlspecialchars($row['moderator_id']) . '" class="mr-2" title="Update Record" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>
        <a href="../public/crud/moderators/moderator_delete.php?moderator_id=' . htmlspecialchars($row['moderator_id']) . '" title="Delete Record" data-toggle="tooltip"><span class="fa fa-trash"></span></a>
    </td>
</tr> -->