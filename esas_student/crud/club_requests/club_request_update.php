<?php
session_start(); // Start the session

require_once "../../../config.php";

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

$requestId = $_GET['request_id'] ?? null; // Get the request ID from the query string
$clubName = $goal = $activities = $coverPhoto = $requestLetter = "";
$clubName_err = $goal_err = $activities_err = $coverPhoto_err = $requestLetter_err = "";
define('COVERPHOTO_DEFAULT', 'COVERPHOTO_DEFAULT.png');

// Fetch the current club request
if ($requestId) {
    $requestQuery = "SELECT * FROM tbl_club_requests WHERE request_id = :requestId";
    if ($stmt = $pdo->prepare($requestQuery)) {
        $stmt->bindParam(":requestId", $requestId);
        if ($stmt->execute()) {
            $request = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($request) {
                $clubName = $request['clubName'];
                $goal = $request['goal'];
                $activities = $request['activities'];
                $mission = $request['mission'] ?? ''; // Adjust based on your database
                $vision = $request['vision'] ?? '';   // Adjust based on your database
                $coverPhoto = $request['coverPhoto'] ?: COVERPHOTO_DEFAULT; // Set default if no cover photo exists
                $requestLetter = $request['requestLetter'] ?? ''; // Fetch request letter
            } else {
                echo "No request found with that ID.";
                exit();
            }
        }
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input_clubName = trim($_POST["clubName"]);
    if (empty($input_clubName)) {
        $clubName_err = "Please enter a club name.";
    } else {
        $clubName = $input_clubName;
    }

    $input_goal = trim($_POST["goal"]);
    if (empty($input_goal)) {
        $goal_err = "Please enter the club's goal.";
    } else {
        $goal = $input_goal;
    }

    $input_mission = trim($_POST["mission"]);
    if (empty($input_mission)) {
        $mission_err = "Please enter the club's mission.";
    } else {
        $mission = $input_mission;
    }

    $input_vision = trim($_POST["vision"]);
    if (empty($input_vision)) {
        $vision_err = "Please enter the club's vision.";
    } else {
        $vision = $input_vision;
    }

    $input_activities = trim($_POST["activities"]);
    if (empty($input_activities)) {
        $activities_err = "Please enter the activities of the club.";
    } else {
        $activities = $input_activities;
    }

    // Handle cover photo upload
    if (isset($_FILES['coverPhoto']) && $_FILES['coverPhoto']['name']) {
        $coverPhotoName = $_FILES['coverPhoto']['name'];
        $coverPhotoSize = $_FILES['coverPhoto']['size'];
        $coverPhotoTmpName = $_FILES['coverPhoto']['tmp_name'];

        $validImageExtensions = ['jpg', 'jpeg', 'png'];
        $imageExtension = strtolower(pathinfo($coverPhotoName, PATHINFO_EXTENSION));

        // Validate image
        if (!in_array($imageExtension, $validImageExtensions)) {
            $coverPhoto_err = "Invalid image extension. Only JPG, JPEG, and PNG are allowed.";
        } else {
            $newCoverPhotoName = 'request_' . uniqid() . '.' . $imageExtension;
            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/esas/esas_student/images/';
            $uploadPath = $uploadDir . $newCoverPhotoName;

            // Create directory if not exists
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // Move uploaded file
            if (!move_uploaded_file($coverPhotoTmpName, $uploadPath)) {
                $coverPhoto_err = "Failed to upload image.";
            } else {
                // Use the new cover photo if uploaded successfully
                $coverPhoto = $newCoverPhotoName;
            }
        }
    } else {
        // If no new cover photo is uploaded, keep the existing one
        $coverPhoto = $request['coverPhoto'] ?? COVERPHOTO_DEFAULT;
    }

    // Handle request letter upload
    if (isset($_FILES['requestLetter']) && $_FILES['requestLetter']['name']) {
        $requestLetterName = $_FILES['requestLetter']['name'];
        $requestLetterTmpName = $_FILES['requestLetter']['tmp_name'];

        $validLetterExtensions = ['pdf'];
        $letterExtension = strtolower(pathinfo($requestLetterName, PATHINFO_EXTENSION));

        // Validate request letter
        if (!in_array($letterExtension, $validLetterExtensions)) {
            $requestLetter_err = "Invalid file extension. Only PDF are allowed.";
        } else {
            $newRequestLetterName = 'request_letter_' . uniqid() . '.' . $letterExtension;
            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/esas/esas_student/request_letters/';
            $uploadPath = $uploadDir . $newRequestLetterName;

            // Create directory if not exists
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // Move uploaded file
            if (!move_uploaded_file($requestLetterTmpName, $uploadPath)) {
                $requestLetter_err = "Failed to upload request letter.";
            } else {
                // Use the new request letter if uploaded successfully
                $requestLetter = $newRequestLetterName;
            }
        }
    } else {
        // If no new request letter is uploaded, keep the existing one
        $requestLetter = $request['requestLetter'] ?? '';
    }

    // Update the club request if no errors
    if (empty($clubName_err) && empty($goal_err) && empty($activities_err) && empty($coverPhoto_err) && empty($requestLetter_err)) {
        $sql = "UPDATE tbl_club_requests SET clubName = :clubName, goal = :goal, mission = :mission, vision = :vision, activities = :activities, coverPhoto = :coverPhoto, requestLetter = :requestLetter WHERE request_id = :requestId";
        if ($stmt = $pdo->prepare($sql)) {
            $stmt->bindParam(":clubName", $clubName);
            $stmt->bindParam(":goal", $goal);
            $stmt->bindParam(":mission", $mission);
            $stmt->bindParam(":vision", $vision);
            $stmt->bindParam(":activities", $activities);
            $stmt->bindParam(":coverPhoto", $coverPhoto);
            $stmt->bindParam(":requestLetter", $requestLetter);
            $stmt->bindParam(":requestId", $requestId);

            if ($stmt->execute()) {
                // Insert activity log
                $activityLog = "You updated your club request '$clubName'";
                $logSQL = "INSERT INTO tbl_activity_logs (activity, dateAdded, student_id) 
                VALUES (:activity, NOW(), :student_id)";

                if ($logStmt = $pdo->prepare($logSQL)) {
                    $logStmt->bindParam(":activity", $activityLog);
                    $logStmt->bindParam(":student_id", $_SESSION['student_id']); // Get student_id from session
                    $logStmt->execute(); // Log the activity
                }

                header("location: ../../club_requests.php");
                exit();
            } else {
                echo "Oops! Something went wrong with the club request update. Please try again later.";
            }
        }
    }

}

unset($stmt);
unset($pdo);
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ESAS - Update Club Request</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="../../../assets/css/jquery.dataTables.min.css" rel="stylesheet" />
    <link href="../../../assets/css/styles.css" rel="stylesheet" />
    <link href="../../../assets/img/NBSC_LOGO.png" rel="icon">
    <style>
        .wrapper {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 15px;
        }
        #coverPhotoPreview {
            width: 100%;
            height: auto;
            margin-top: 10px;
            display: none;
        }
        #fileIconPreview {
            width: 100px !important;
            height: auto;
            display: none;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Update Club Request</h2>
                    <p>Please fill this form to update the club request information.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?request_id=' . $requestId; ?>" method="post" enctype="multipart/form-data">
                        <div class="form-group mb-3">
                            <label for="clubName">Club Name</label>
                            <input type="text" name="clubName" class="form-control <?php echo (!empty($clubName_err)) ? 'is-invalid' : ''; ?>" id="clubName" value="<?php echo htmlspecialchars($clubName); ?>" required>
                            <span class="invalid-feedback"><?php echo $clubName_err; ?></span>
                        </div>

                        <div class="form-group mb-3">
                            <label for="goal">What is the primary goal of this club?</label>
                            <textarea name="goal" class="form-control <?php echo (!empty($goal_err)) ? 'is-invalid' : ''; ?>" id="goal" rows="3" required><?php echo htmlspecialchars($goal); ?></textarea>
                            <span class="invalid-feedback"><?php echo $goal_err; ?></span>
                        </div>

                        <div class="form-group mb-3">
                            <label for="mission">What is the mission of this club?</label>
                            <textarea name="mission" class="form-control <?php echo (!empty($mission_err)) ? 'is-invalid' : ''; ?>" id="mission" rows="3" required><?php echo htmlspecialchars($mission); ?></textarea>
                            <span class="invalid-feedback"><?php echo $mission_err; ?></span>
                        </div>

                        <div class="form-group mb-3">
                            <label for="vision">What vision does this club aspire to achieve?</label>
                            <textarea name="vision" class="form-control <?php echo (!empty($vision_err)) ? 'is-invalid' : ''; ?>" id="vision" rows="3" required><?php echo htmlspecialchars($vision); ?></textarea>
                            <span class="invalid-feedback"><?php echo $vision_err; ?></span>
                        </div>

                        <div class="form-group mb-3">
                            <label for="activities">Club's proposed activities:</label>
                            <textarea name="activities" class="form-control <?php echo (!empty($activities_err)) ? 'is-invalid' : ''; ?>" id="activities" rows="2"><?php echo htmlspecialchars($activities); ?></textarea>
                            <span class="invalid-feedback"><?php echo $activities_err; ?></span>
                        </div>

                        <div class="form-group mb-3">
                            <label for="coverPhoto">Change Cover Photo</label>
                            <input type="file" name="coverPhoto" class="form-control <?php echo (!empty($coverPhoto_err)) ? 'is-invalid' : ''; ?>" id="coverPhoto" onchange="previewImage(event)">
                            <span class="invalid-feedback"><?php echo $coverPhoto_err; ?></span>
                            
                            <!-- Existing Cover Photo Preview -->
                            <?php if (!empty($coverPhoto)): ?>
                                <img src="<?php echo htmlspecialchars('/esas/esas_student/images/' . $coverPhoto); ?>" alt="Cover Photo Preview" id="coverPhotoPreview" style="display:block; margin-top:10px; max-width: 100%;">
                            <?php else: ?>
                                <img id="coverPhotoPreview" alt="Cover Photo Preview" style="display:none; margin-top:10px; max-width: 100%;">
                            <?php endif; ?>
                        </div>

                        
                        <div class="form-group mb-3">
                            <label for="requestLetter">Update Request Letter</label>
                            <input type="file" name="requestLetter" class="form-control <?php echo (!empty($requestLetter_err)) ? 'is-invalid' : ''; ?>" id="requestLetter" accept=".pdf" onchange="previewRequestLetter(event)">
                            <small class="form-text text-muted">Accepted format: PDF only.</small>
                            <span class="invalid-feedback"><?php echo $requestLetter_err; ?></span>

                            <!-- Existing Request Letter Preview -->
                            <?php if (!empty($requestLetter)): ?>
                                <?php
                                // Get the file extension
                                $fileExtension = strtolower(pathinfo($requestLetter, PATHINFO_EXTENSION));
                                $icon = '';

                                // Determine which icon to display based on the file extension
                                if ($fileExtension === 'pdf') {
                                    $icon = '/esas/esas_student/icons/ICON_PDF.png'; // Path to PDF icon
                                }
                                ?>
                                
                                <a href="<?php echo htmlspecialchars('/esas/esas_student/request_letters/' . $requestLetter); ?>" target="_blank">
                                    <img src="<?php echo htmlspecialchars($icon); ?>" alt="Request Letter Preview" id="fileIconPreview" style="display:block; margin-top:10px; width: 100px;">
                                </a>
                            <?php else: ?>
                                <img id="fileIconPreview" alt="Request Letter Preview" style="display:none; margin-top:10px; width: 100px;">
                            <?php endif; ?>
                        </div>

                        <script>
                            function previewRequestLetter(event) {
                                const file = event.target.files[0]; // Get the selected file
                                const preview = document.getElementById('fileIconPreview'); // Get the preview element
                                const allowedExtension = 'pdf'; // Define allowed file type
                                
                                if (file) {
                                    const fileExtension = file.name.split('.').pop().toLowerCase(); // Get file extension
                                    let iconPath = '';

                                    // Set icon based on file type
                                    if (fileExtension === allowedExtension) {
                                        iconPath = '/esas/esas_student/icons/ICON_PDF.png';
                                    }

                                    // If the file is allowed, update the preview
                                    if (fileExtension === allowedExtension) {
                                        preview.src = iconPath; // Set the icon image based on file type
                                        preview.style.display = 'block'; // Show the preview
                                    } else {
                                        preview.style.display = 'none'; // Hide the preview if file type is not allowed
                                    }
                                } else {
                                    preview.style.display = 'none'; // Hide the preview if no file is selected
                                }
                            }
                        </script>


                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="../../club_requests.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Preview Image Function -->
    <script>
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function(){
                var output = document.getElementById('coverPhotoPreview');
                output.src = reader.result;
                output.style.display = 'block';
            }
            reader.readAsDataURL(event.target.files[0]);
        }

    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>