<?php
// Include config file
require_once "../../../../config.php";

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Check if the club_id parameter exists
if (isset($_GET["club_id"]) && !empty(trim($_GET["club_id"]))) {
    // Get the club_id from the query string
    $club_id = trim($_GET["club_id"]);

    // Prepare the SQL query to fetch club details and associated moderators
    $sql = "SELECT 
                c.club_id,
                c.clubName,
                c.description,
                c.mission,
                c.vision,
                c.history,
                c.founder,
                c.dateAdded,
                c.coverPhoto,
                GROUP_CONCAT(DISTINCT m.firstName, ' ', m.middleName, ' ', m.lastName ORDER BY m.lastName ASC SEPARATOR ', ') AS moderatorNames
            FROM tbl_clubs c
            LEFT JOIN tbl_clubs_and_moderators cm ON c.club_id = cm.club_id
            LEFT JOIN tbl_moderators m ON cm.moderator_id = m.moderator_id
            WHERE c.club_id = :club_id
            GROUP BY c.club_id";

    // Prepare the statement
    if ($stmt = $pdo->prepare($sql)) {
        // Bind the parameter
        $stmt->bindParam(":club_id", $club_id, PDO::PARAM_INT);

        // Execute the prepared statement
        if ($stmt->execute()) {
            // Check if the club was found
            if ($stmt->rowCount() == 1) {
                // Fetch the row data
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                // Retrieve the details
                $clubName = htmlspecialchars($row["clubName"]);
                $description = !empty($row["description"]) ? htmlspecialchars($row["description"]) : 'No description available.';
                $mission = !empty($row["mission"]) ? htmlspecialchars($row["mission"]) : 'No mission available.';
                $vision = !empty($row["vision"]) ? htmlspecialchars($row["vision"]) : 'No vision available.';
                $history = !empty($row["history"]) ? htmlspecialchars($row["history"]) : 'No history available.';
                $founder = !empty($row["founder"]) ? htmlspecialchars($row["founder"]) : 'No founder available.';
                $dateAdded = !empty($row["dateAdded"]) ? htmlspecialchars($row["dateAdded"]) : 'None';
                $moderatorNames = !empty($row["moderatorNames"]) ? htmlspecialchars($row["moderatorNames"]) : 'None';
                $coverPhoto = !empty($row["coverPhoto"]) ? htmlspecialchars($row["coverPhoto"]) : "default-cover.jpg";
            
                // Determine if the label should be "Moderator" or "Moderators"
                $moderatorCount = substr_count($moderatorNames, ',') + 1; // Count commas and add 1 for total moderators
                $moderatorLabel = ($moderatorCount > 1) ? "Moderators:" : "Moderator:";
            
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
    // Redirect if the club_id parameter is missing
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
    <title>ESAS - Club Details</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
    <link href="../../../assets/css/jquery.dataTables.min.css" rel="stylesheet" />
    <script src="../../../assets/js/all.js" crossorigin="anonymous"></script>
    <script src="../../../assets/js/jquery-3.6.0.js"></script>
    <link href="../../../assets/css/styles.css" rel="stylesheet" />
    <link href="../../../assets/img/NBSC_LOGO.png" rel="icon">
</head>
    <style>
    
        .col-md-7 {
            text-align: justify;
            text-indent: 30px;
        }
    </style>
<body>
    <div class="container">
        <div class="row mt-5">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3>Club Profile</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 text-center">
                                <img src="/esas/esas_admin/images/<?php echo $coverPhoto; ?>" 
                                     alt="<?php echo $clubName; ?> Cover Photo" 
                                     class="img-fluid" style="width: 300px; height: auto; border-radius: 5px; object-fit: cover;">
                                     <!-- class="img-fluid" style="width: 300px; height: 171px; border-radius: 5px; object-fit: cover;"> -->
                            </div>
                            <div class="col-md-8">
                                <h3 class="text-muted mb-3"><?php echo $clubName; ?></h3>
                                <hr>
                                <p><strong>Date Created: </strong><?php echo date("F j, Y", strtotime($dateAdded)); ?></p>
                                <p><strong><?php echo $moderatorLabel; ?> </strong><?php echo $moderatorNames; ?></p>
                            </div>
                        </div>
                        <hr>
                        <div class="row p-2 mt-4">
                            <div class="col-md-12">
                                <h5>Description:</h5>
                                <p style="text-align: justify; text-indent: 30px;"><?php echo $description; ?></p>
                                <h5>Mission:</h5>
                                <p style="text-align: justify; text-indent: 30px;"><?php echo $mission; ?></p>
                                <h5>Vision:</h5>
                                <p style="text-align: justify; text-indent: 30px;"><?php echo $vision; ?></p>
                                <h5>History:</h5>
                                <p style="text-align: justify; text-indent: 30px;"><?php echo $history; ?></p>
                                <h5>Founder:</h5>
                                <p style="text-align: justify; text-indent: 30px;"><?php echo $founder; ?></p>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer text-center">
                        <a href="club_update.php?club_id=<?php echo $club_id; ?>" class="btn btn-warning">Update</a>
                        <a href="club_delete.php?club_id=<?php echo $club_id; ?>" class="btn btn-danger">Delete</a>
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
