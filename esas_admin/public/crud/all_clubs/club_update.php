<?php
require_once "../../../../config.php";
session_start();

// Ensure the moderator ID is set in the session
if (isset($_SESSION['admin_id'])) {
    $adminId = $_SESSION['admin_id'];
} else {
    echo json_encode(['error' => 'Admin not logged in.']);
    exit;
}

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

    $clubId = $_GET['club_id'] ?? null; // Get the club ID from the query string
    $clubName = $description = $mission = $vision = $history = $founder = $coverPhoto = "";
    $clubName_err = $description_err = $mission_err = $vision_err = $history_err = $founder_err = $coverPhoto_err = "";
    define('COVERPHOTO_DEFAULT', 'COVERPHOTO_DEFAULT.png');

    // Fetch moderators
    $moderatorQuery = "SELECT moderator_id, CONCAT(firstName, ' ', lastName) AS moderator_name FROM tbl_moderators";
    if ($stmt = $pdo->prepare($moderatorQuery)) {
        if ($stmt->execute()) {
            $moderators = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    // Fetch current moderators for the club
    $currentModerators = [];
    if ($clubId) {
        $moderatorCountQuery = "SELECT cm.clubmod_id, cm.moderator_id, cm.club_id, CONCAT(m.firstName, ' ', m.lastName) AS moderator_name 
                                FROM tbl_clubs_and_moderators cm
                                JOIN tbl_moderators m ON cm.moderator_id = m.moderator_id
                                WHERE cm.club_id = :clubId";
        if ($stmt = $pdo->prepare($moderatorCountQuery)) {
            $stmt->bindParam(":clubId", $clubId);
            if ($stmt->execute()) {
                $currentModerators = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        }
    }


    $currentModeratorId = null; // Variable to hold current moderator ID

    if ($clubId) {
        // Update club query to fetch current moderator ID
        $clubQuery = "SELECT c.*, cm.moderator_id FROM tbl_clubs c
                    LEFT JOIN tbl_clubs_and_moderators cm ON c.club_id = cm.club_id
                    WHERE c.club_id = :clubId";
        if ($stmt = $pdo->prepare($clubQuery)) {
            $stmt->bindParam(":clubId", $clubId);
            if ($stmt->execute()) {
                $club = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($club) {
                    $clubName = $club['clubName'];
                    $description = $club['description'];
                    $mission = $club['mission'];
                    $vision = $club['vision'];
                    $history = $club['history'];
                    $founder = $club['founder'];
                    $coverPhoto = $club['coverPhoto'] ?: COVERPHOTO_DEFAULT; // Set default if no cover photo exists
                    $currentModeratorId = $club['moderator_id']; // Fetch current moderator ID
                } else {
                    echo "No club found with that ID.";
                    exit();
                }
            }
        }
    }

    // Fetch all departments
$departmentQuery = "SELECT DISTINCT department FROM tbl_club_recommendations";
$departments = [];
if ($stmt = $pdo->prepare($departmentQuery)) {
    if ($stmt->execute()) {
        $departments = $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}

// Fetch current recommendations for the club
$currentRecommendations = [];
if ($clubId) {
    $recommendationQuery = "SELECT department FROM tbl_club_recommendations WHERE club_id = :clubId";
    if ($stmt = $pdo->prepare($recommendationQuery)) {
        $stmt->bindParam(":clubId", $clubId);
        if ($stmt->execute()) {
            $currentRecommendations = $stmt->fetchAll(PDO::FETCH_COLUMN);
        }
    }
}

// Handle recommendations
if (!empty($_POST['recommendedDepartments'])) {
    // First, delete existing recommendations for the club
    $deleteRecommendationsSql = "DELETE FROM tbl_club_recommendations WHERE club_id = :clubId";
    if ($deleteStmt = $pdo->prepare($deleteRecommendationsSql)) {
        $deleteStmt->bindParam(":clubId", $clubId);
        $deleteStmt->execute();
    }

    // Insert selected recommendations
    foreach ($_POST['recommendedDepartments'] as $department) {
        $insertRecommendationSql = "INSERT INTO tbl_club_recommendations (club_id, department, dateAdded) VALUES (:clubId, :department, NOW())";
        if ($insertStmt = $pdo->prepare($insertRecommendationSql)) {
            $insertStmt->bindParam(":clubId", $clubId);
            $insertStmt->bindParam(":department", $department);
            $insertStmt->execute();
        }
    }
} else {
    // If no departments are selected, delete all existing recommendations
    $deleteRecommendationsSql = "DELETE FROM tbl_club_recommendations WHERE club_id = :clubId";
    if ($deleteStmt = $pdo->prepare($deleteRecommendationsSql)) {
        $deleteStmt->bindParam(":clubId", $clubId);
        $deleteStmt->execute();
    }
}


    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $input_clubName = trim($_POST["clubName"]);
        if (empty($input_clubName)) {
            // $clubName_err = "Please enter a club name.";
        } else {
            $clubName = $input_clubName;
        }

        $input_description = trim($_POST["description"]);
        if (empty($input_description)) {
            // $description_err = "Please enter club description.";
        } else {
            $description = $input_description;
        }

        // Validate mission
        $input_mission = trim($_POST["mission"]);
        if (empty($input_mission)) {
            // $mission_err = "Please enter the club's mission.";
        } else {
            $mission = $input_mission;
        }
    
        // Validate vision
        $input_vision = trim($_POST["vision"]);
        if (empty($input_vision)) {
            // $vision_err = "Please enter the club's vision.";
        } else {
            $vision = $input_vision;
        }
    
        // Validate history
        $input_history = trim($_POST["history"]);
        if (empty($input_history)) {
            // $history_err = "Please enter the club's history.";
        } else {
            $history = $input_history;
        }
    
        // Validate founder
        $input_founder = trim($_POST["founder"]);
        if (empty($input_founder)) {
            // $founder_err = "Please enter the club's founder.";
        } else {
            $founder = $input_founder;
        }

        // Handle cover photo upload
        if (isset($_FILES['coverPhoto']) && $_FILES['coverPhoto']['name']) {
            $coverPhotoName = $_FILES['coverPhoto']['name'];
            $coverPhotoSize = $_FILES['coverPhoto']['size'];
            $coverPhotoTmpName = $_FILES['coverPhoto']['tmp_name'];

            $validImageExtensions = ['jpg', 'jpeg', 'png'];
            $imageExtension = strtolower(pathinfo($coverPhotoName, PATHINFO_EXTENSION));

            if (!in_array($imageExtension, $validImageExtensions)) {
                $coverPhoto_err = "Invalid image extension. Only JPG, JPEG, and PNG are allowed.";
            } elseif ($coverPhotoSize > 10000000) {
                $coverPhoto_err = "Image size is too large (max 10MB).";
            } else {
                $newCoverPhotoName = 'club_' . uniqid() . '.' . $imageExtension;
                $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/esas/esas_admin/images/';
                $uploadPath = $uploadDir . $newCoverPhotoName;

                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                if (!move_uploaded_file($coverPhotoTmpName, $uploadPath)) {
                    $coverPhoto_err = "Failed to upload image.";
                } else {
                    $coverPhoto = $newCoverPhotoName;
                }
            }
        } else {
            // Retain old cover photo if not uploaded
            $coverPhoto = $club['coverPhoto'] ?: COVERPHOTO_DEFAULT;
        }

        // Get selected moderator
        $selectedModerator = $_POST["moderator"] ?? null;


        // Update the club if no errors
        if (empty($clubName_err) && empty($description_err) && empty($mission_err) && empty($vision_err) && empty($history_err) && empty($founder_err) && empty($coverPhoto_err)) {
            $sql = "UPDATE tbl_clubs 
                    SET clubName = :clubName, 
                        description = :description, 
                        mission = :mission, 
                        vision = :vision, 
                        history = :history, 
                        founder = :founder, 
                        coverPhoto = :coverPhoto 
                    WHERE club_id = :clubId";
            
            if ($stmt = $pdo->prepare($sql)) {
                // Bind parameters
                $stmt->bindParam(":clubName", $clubName);
                $stmt->bindParam(":description", $description);
                $stmt->bindParam(":mission", $mission);
                $stmt->bindParam(":vision", $vision);
                $stmt->bindParam(":history", $history);
                $stmt->bindParam(":founder", $founder);
                $stmt->bindParam(":coverPhoto", $coverPhoto);
                $stmt->bindParam(":clubId", $clubId);

                if ($stmt->execute()) {
                    // Log the activity
                    $logSql = "INSERT INTO tbl_activity_logs (activity, dateAdded, admin_id, moderator_id, student_id) 
                            VALUES (:activity, NOW(), :adminId, NULL, NULL)"; // Set moderator_id and student_id to NULL
                    if ($logStmt = $pdo->prepare($logSql)) {
                        // Prepare the activity message
                        $activityMessage = "You updated {$clubName} information";
                        
                        // Bind parameters
                        $logStmt->bindParam(":activity", $activityMessage);
                        $logStmt->bindParam(":adminId", $adminId); // Use the adminId from the session
                        
                        // Execute the log insert
                        if ($logStmt->execute()) {
                            // Log inserted successfully
                        } else {
                            // Handle error
                            echo "Failed to log the activity.";
                        }
                    }

                    // Handle moderator association (unchanged)
                    if (!empty($_POST['moderator'])) {
                        foreach ($_POST['moderator'] as $index => $moderatorId) {
                            if ($moderatorId === 'none') {
                                // Get the current moderator ID for this dropdown
                                $currentModeratorId = $currentModerators[$index]['moderator_id'];

                                // Delete the specific moderator association
                                $deleteSql = "DELETE FROM tbl_clubs_and_moderators WHERE club_id = :clubId AND moderator_id = :moderatorId";
                                if ($deleteStmt = $pdo->prepare($deleteSql)) {
                                    $deleteStmt->bindParam(":clubId", $clubId);
                                    $deleteStmt->bindParam(":moderatorId", $currentModeratorId);
                                    $deleteStmt->execute();
                                }
                            } else {
                                // Associate the existing moderator with the club
                                $sql2 = "INSERT INTO tbl_clubs_and_moderators (club_id, moderator_id) VALUES (:clubId, :moderatorId)";
                                if ($stmt2 = $pdo->prepare($sql2)) {
                                    $stmt2->bindParam(":clubId", $clubId);
                                    $stmt2->bindParam(":moderatorId", $moderatorId);
                                    if (!$stmt2->execute()) { // Check if the insertion was successful
                                        // Log or handle the error
                                        echo "Failed to associate moderator ID $moderatorId with club. Error: " . implode(", ", $stmt2->errorInfo());
                                    }
                                }
                            }
                        }
                    } else {
                        echo "No moderators selected for association.";
                    }

                    header("location: ../../all_clubs.php");
                    exit();
                } else {
                    echo "Oops! Something went wrong with the club update. Please try again later.";
                }
            }
        }



    unset($stmt);
    }

unset($pdo);
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ESAS - Update Club</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
    <link href="../../../assets/css/jquery.dataTables.min.css" rel="stylesheet" />
    <script src="../../../assets/js/all.js" crossorigin="anonymous"></script>
    <script src="../../../assets/js/jquery-3.6.0.js"></script>
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
        }

        .club-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .remove-btn {
            background-color: red;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 3px;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        .remove-btn:hover {
            background-color: #cc0000;
        }

    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Update Club</h2>
                    <p>Please fill this form to update the club information.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?club_id=' . $clubId; ?>" method="post" enctype="multipart/form-data">
                        <div class="form-group mb-2">
                            <label>Club Name</label>
                            <input type="text" name="clubName" class="form-control <?php echo (!empty($clubName_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($clubName); ?>">
                            <span class="invalid-feedback"><?php echo $clubName_err; ?></span>
                        </div>
                        <div class="form-group mb-2">
                            <label>Description</label>
                            <textarea name="description" class="form-control <?php echo (!empty($description_err)) ? 'is-invalid' : ''; ?>" rows="5"><?php echo htmlspecialchars($description); ?></textarea>
                            <span class="invalid-feedback"><?php echo $description_err; ?></span>
                        </div>
                        <div class="form-group mb-2">
                            <label>Mission</label>
                            <textarea name="mission" class="form-control <?php echo (!empty($mission_err)) ? 'is-invalid' : ''; ?>" rows="5"><?php echo htmlspecialchars($mission); ?></textarea>
                            <span class="invalid-feedback"><?php echo $mission_err; ?></span>
                        </div>
                        <div class="form-group mb-2">
                            <label>Vision</label>
                            <textarea name="vision" class="form-control <?php echo (!empty($vision_err)) ? 'is-invalid' : ''; ?>" rows="5"><?php echo htmlspecialchars($vision); ?></textarea>
                            <span class="invalid-feedback"><?php echo $vision_err; ?></span>
                        </div>
                        <div class="form-group mb-2">
                            <label>History</label>
                            <textarea name="history" class="form-control <?php echo (!empty($history_err)) ? 'is-invalid' : ''; ?>" rows="5"><?php echo htmlspecialchars($history); ?></textarea>
                            <span class="invalid-feedback"><?php echo $history_err; ?></span>
                        </div>
                        <div class="form-group mb-2">
                            <label>Founder</label>
                            <textarea name="founder" class="form-control <?php echo (!empty($founder_err)) ? 'is-invalid' : ''; ?>" rows="1"><?php echo $founder; ?></textarea>
                            <span class="invalid-feedback"><?php echo $founder_err; ?></span>
                        </div>
                        <div class="form-group mb-2">
                            <label>Cover Photo</label>
                            <input type="file" name="coverPhoto" id="coverPhoto" class="form-control-file <?php echo (!empty($coverPhoto_err)) ? 'is-invalid' : ''; ?>" onchange="previewImageUpdate()">
                            <span class="invalid-feedback"><?php echo $coverPhoto_err; ?></span>
                            <img src="/esas/esas_admin/images/<?php echo htmlspecialchars($coverPhoto); ?>" id="coverPhotoPreview" alt="" style="display: block; margin-top: 10px; width: 100%; height: auto;">
                        </div>

                        <hr>

                        <div class="form-group mb-2">
                            <label>Recommend to Departments<p class="text-muted"><em>(Check all that applies)</em></label>
                            <?php foreach ($departments as $department): ?>
                                <div class="form-check">
                                    <input type="checkbox" name="recommendedDepartments[]" value="<?php echo htmlspecialchars($department); ?>" 
                                    <?php echo in_array($department, $currentRecommendations) ? 'checked' : ''; ?> 
                                    class="form-check-input" id="<?php echo htmlspecialchars($department); ?>">
                                    <label class="form-check-label" for="<?php echo htmlspecialchars($department); ?>">
                                        <?php echo htmlspecialchars($department); ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <input type="submit" class="btn btn-block mt-5 btn-primary" value="Update">
                    </form>
                </div>
            </div>






            <!-- <hr>
            <div class="assign-section mt-5">
                <h3 class="text-muted">Current Moderators for this Club</h3>
                <div class="clubs-handled">
                    <?php
                    if (!empty($currentModerators)) {
                        echo '<p>' . (count($currentModerators) === 1 ? 'Moderator for' : 'Moderators for') . ' <strong>' . htmlspecialchars($clubName) . '</strong></p>';
                        foreach ($currentModerators as $moderator) {
                            echo '
                            <div class="club-item">
                                <span>' . htmlspecialchars($moderator['moderator_name']) . '</span>
                                <form action="club_moderator_remove.php" method="POST" style="margin: 0;">
                                    <input type="hidden" name="club_id" value="' . htmlspecialchars($clubId) . '">
                                    <input type="hidden" name="moderator_id" value="' . htmlspecialchars($moderator['moderator_id']) . '">
                                    <button type="submit" class="btn remove-btn text-light">Remove as Moderator</button>
                                </form>
                            </div>';
                        }
                    } else {
                        echo '<p>No moderators assigned to this club. <a href="/esas/esas_admin/public/crud/all_clubs/club_moderator_assign.php" class="btn btn-warning mt-2">
                            +Assign a Moderator</a></p>';
                    }                    
                    ?>
                </div>
            </div> -->




            <hr>
            <div class="text-center">
                <a href="../../all_clubs.php" class="btn text-align-end btn-secondary">Go Back</a>
            </div>

        </div>
    </div>



    

    <!-- Add New Moderator Modal -->
    <div class="modal fade" id="addModeratorModal" tabindex="-1" role="dialog" aria-labelledby="addModeratorModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModeratorModalLabel">Add New Moderator</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addModeratorForm">
                        <div class="form-group mb-2">
                            <label>First Name:</label>
                            <input type="text" name="firstName" class="form-control underline-input" required>
                        </div>
                        <div class="form-group mb-2">
                            <label>Middle Initial:</label>
                            <input type="text" name="middleInitial" maxlength="1" class="form-control underline-input">
                        </div>
                        <div class="form-group mb-2">
                            <label>Last Name:</label>
                            <input type="text" name="lastName" class="form-control underline-input">
                        </div>
                        <div class="form-group mb-2">
                            <label>Email:</label>
                            <input type="email" name="email" class="form-control underline-input" required>
                        </div>
                        <div class="form-group mb-2">
                            <label>Temporary Password:</label>
                            <input type="password" name="password" class="form-control underline-input" required>
                        </div>
                        <input type="hidden" id="newModeratorId" name="newModeratorId" value="">
                        <input type="submit" class="btn btn-primary" value="Add Moderator">
                    </form>
                </div>
            </div>
        </div>
    </div>






    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Include Popper.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
    <!-- Include Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Include Cropper.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>


    <script>

        function openAddModeratorModal() {
            $('#addModeratorModal').modal('show'); // Open the modal using Bootstrap's modal method
        }
        
        document.getElementById("moderatorSelectNew").addEventListener("change", function() {
            if (this.value === "add_new_moderator") {
                $("#addModeratorModal").modal("show");
            }
        });
        
        document.getElementById("moderatorSelect").addEventListener("change", function() {
            if (this.value === "add_new_moderator") {
                $("#addModeratorModal").modal("show");
            }
        });

        document.getElementById("addModeratorForm").addEventListener("submit", function (e) {
            e.preventDefault();
            const form = this;
            const formData = new FormData(form);
            formData.append('action', 'add_moderator');

            fetch("<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>", {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const select = document.getElementById("moderatorSelect");
                    const newOption = document.createElement("option");
                    newOption.value = data.moderatorId;
                    newOption.text = data.fullName;
                    select.appendChild(newOption);
                    select.value = data.moderatorId;

                    $("#addModeratorModal").modal("hide");
                    document.getElementById('newModeratorId').value = data.moderatorId;
                } else {
                    alert("Error adding moderator.");
                }
            })
            .catch(error => console.error('Error:', error));
        });
    </script>





<script>
    function previewImageUpdate() {
        const fileInput = document.getElementById('coverPhoto');
        const preview = document.getElementById('coverPhotoPreview');
        const file = fileInput.files[0];
        const reader = new FileReader();

        reader.addEventListener("load", function () {
            // Set the preview image src to the file's data URL
            preview.src = reader.result;
        }, false);

        if (file) {
            reader.readAsDataURL(file); // Read the file as a data URL
        }
    }
</script>



</body>
</html>
