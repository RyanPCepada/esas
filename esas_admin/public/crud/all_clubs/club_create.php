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

$clubName = $description = $mission = $vision = $history = $coverPhoto = "";
$clubName_err = $description_err = $mission_err = $vision_err = $history_err = $coverPhoto_err = "";
$moderators = [];
define('COVERPHOTO_DEFAULT', 'COVERPHOTO_DEFAULT.png');
define('PROF_PIC_DEFAULT', 'PROF_PIC.png');

// Function to get the school year code '2425' for the 2024-2025 school year
function getSchoolYearCode() {
    $currentYear = date('Y');
    $nextYear = $currentYear + 1; // School year spans two years
    return substr($currentYear, 2, 2) . substr($nextYear, 2, 2); // Returns '2425' for 2024-2025
}

// Function to get the next available 4-digit increment for the club ID
function getNextClubIncrement($pdo, $schoolYearCode) {
    $sql = "SELECT club_id FROM tbl_clubs WHERE club_id LIKE :schoolYearCode ORDER BY club_id DESC LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $likePattern = $schoolYearCode . '%';
    $stmt->bindParam(':schoolYearCode', $likePattern);
    $stmt->execute();

    $lastClubId = $stmt->fetchColumn();

    if ($lastClubId) {
        $lastIncrement = (int)substr($lastClubId, 4, 4);
        $nextIncrement = str_pad($lastIncrement + 1, 4, '0', STR_PAD_LEFT); 
    } else {
        $nextIncrement = '0001';
    }

    return $nextIncrement;
}

// Function to get the next available 4-digit increment for the moderator ID
function getNextModeratorIncrement($pdo, $schoolYearCode) {
    $sql = "SELECT moderator_id FROM tbl_moderators WHERE moderator_id LIKE :schoolYearCode ORDER BY moderator_id DESC LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $likePattern = $schoolYearCode . '%';
    $stmt->bindParam(':schoolYearCode', $likePattern);
    $stmt->execute();
    
    $lastModeratorId = $stmt->fetchColumn();
    
    if ($lastModeratorId) {
        $lastIncrement = (int)substr($lastModeratorId, 4, 4);
        $nextIncrement = str_pad($lastIncrement + 1, 4, '0', STR_PAD_LEFT); 
    } else {
        $nextIncrement = '0001';
    }
    
    return $nextIncrement;
}

$moderatorQuery = "SELECT moderator_id, CONCAT(firstName, ' ', lastName) AS moderator_name FROM tbl_moderators";
if ($stmt = $pdo->prepare($moderatorQuery)) {
    if ($stmt->execute()) {
        $moderators = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

if (isset($_POST['action']) && $_POST['action'] == 'add_moderator') {
    $firstName = trim($_POST['firstName']);
    $middleInitial = trim($_POST['middleInitial']);
    $lastName = trim($_POST['lastName']);
    $password = trim($_POST['password']); // No hashing

    // Set the profile picture to default
    $profilePic = PROF_PIC_DEFAULT;

    // Generate moderator ID
    $schoolYearCode = getSchoolYearCode();
    $nextIncrement = getNextModeratorIncrement($pdo, $schoolYearCode);
    $moderator_id = $schoolYearCode . $nextIncrement; // Example: '24250001'

    $sql2 = "INSERT INTO tbl_moderators (firstName, middleName, lastName, moderator_id, password, profilePic, dateAdded) 
             VALUES (:firstName, :middleInitial, :lastName, :moderator_id, :password, :profilePic, NOW())";
    if ($stmt2 = $pdo->prepare($sql2)) {
        $stmt2->bindParam(":firstName", $firstName);
        $stmt2->bindParam(":middleInitial", $middleInitial);
        $stmt2->bindParam(":lastName", $lastName);
        $stmt2->bindParam(":moderator_id", $moderator_id);
        $stmt2->bindParam(":password", $password); // No hashing
        $stmt2->bindParam(":profilePic", $profilePic); // Bind the default profile picture

        if ($stmt2->execute()) {
            $newModeratorId = $pdo->lastInsertId();
            echo json_encode(['success' => true, 'moderatorId' => $newModeratorId, 'fullName' => "$firstName $middleInitial. $lastName"]);
            exit();
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input_clubName = trim($_POST["clubName"]);
    if (empty($input_clubName)) {
        $clubName_err = "Please enter a club name.";
    } else {
        $clubName = $input_clubName;
    }

    $input_description = trim($_POST["description"]);
    if (empty($input_description)) {
        $description_err = "Please enter club description.";
    } else {
        $description = $input_description;
    }

    // Validate mission
    $input_mission = trim($_POST["mission"]);
    if (empty($input_mission)) {
        $mission_err = "Please enter the club's mission.";
    } else {
        $mission = $input_mission;
    }

    // Validate vision
    $input_vision = trim($_POST["vision"]);
    if (empty($input_vision)) {
        $vision_err = "Please enter the club's vision.";
    } else {
        $vision = $input_vision;
    }

    // Validate history
    $input_history = trim($_POST["history"]);
    if (empty($input_history)) {
        $history_err = "Please enter the club's history.";
    } else {
        $history = $input_history;
    }

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
        $coverPhoto = COVERPHOTO_DEFAULT;
    }

    if (empty($clubName_err) && empty($description_err) && empty($coverPhoto_err)) {
        $sql = "INSERT INTO tbl_clubs (clubName, description, mission, vision, history, coverPhoto, founder_id, dateAdded) 
        VALUES (:clubName, :description, :mission, :vision, :history, :coverPhoto, :admin_id, NOW())";

        if ($stmt = $pdo->prepare($sql)) {
            // Bind parameters
            $stmt->bindParam(":clubName", $clubName);
            $stmt->bindParam(":description", $description);
            $stmt->bindParam(":mission", $mission);
            $stmt->bindParam(":vision", $vision);
            $stmt->bindParam(":history", $history);
            $stmt->bindParam(":coverPhoto", $coverPhoto);
            $stmt->bindParam(":admin_id", $adminId);
            
            if ($stmt->execute()) {
                $clubId = $pdo->lastInsertId();
                $selectedModerator = $_POST["moderator"];
                $newModeratorId = null;
    
                if ($selectedModerator == "add_new_moderator") {
                    $newModeratorId = $_POST['newModeratorId'];
                } else {
                    $newModeratorId = $selectedModerator;
                }
    
                if (!empty($newModeratorId)) {
                    $sql3 = "INSERT INTO tbl_clubs_and_moderators (club_id, moderator_id, dateAdded) 
                             VALUES (:clubId, :moderatorId, NOW())";
                    if ($stmt3 = $pdo->prepare($sql3)) {
                        $stmt3->bindParam(":clubId", $clubId);
                        $stmt3->bindParam(":moderatorId", $newModeratorId);
                        $stmt3->execute();
                    }
                }
    
                // Process department recommendations
                if (isset($_POST['departments'])) {
                    $departments = $_POST['departments'];
                    $recommendationSql = "INSERT INTO tbl_club_recommendations (club_id, department, dateAdded) VALUES (:clubId, :department, NOW())";
    
                    foreach ($departments as $department) {
                        if ($stmt4 = $pdo->prepare($recommendationSql)) {
                            $stmt4->bindParam(":clubId", $clubId);
                            $stmt4->bindParam(":department", $department);
                            $stmt4->execute();
                        }
                    }
                }
    
                // Log the activity of adding the club
                $activity = "You added " . $clubName . " to the clubs list";
                // $moderator_id = $newModeratorId; // Assuming this is the moderator's ID
                $moderator_id = null;
                $student_id = null; // Replace with actual student ID if applicable
    
                $logSql = "INSERT INTO tbl_activity_logs (activity, dateAdded, admin_id, moderator_id, student_id) VALUES (:activity, NOW(), :admin_id, :moderator_id, :student_id)";
                if ($logStmt = $pdo->prepare($logSql)) {
                    $logStmt->bindParam(":activity", $activity);
                    $logStmt->bindParam(":admin_id", $adminId);  // Use the correctly assigned variable
                    $logStmt->bindParam(":moderator_id", $moderator_id);
                    $logStmt->bindParam(":student_id", $student_id); // Use actual student_id if needed
                    $logStmt->execute();
                }
    
                header("location: ../../all_clubs.php");
                exit();
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        unset($stmt);
    }
    
    unset($pdo);
    
}
?>

<!-- HERE -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eSAS - Add Club</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
    <link href="../../../assets/css/jquery.dataTables.min.css" rel="stylesheet" />
    <script src="../../../assets/js/all.js" crossorigin="anonymous"></script>
    <script src="../../../assets/js/jquery-3.6.0.js"></script>
    <link href="../../../assets/css/styles.css" rel="stylesheet" />
    <link href="../../../assets/img/nbsclogo.png" rel="icon">

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
    </style>
</head>
<body>
<div class="wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h2 class="mt-5">Add New Club</h2>
                <p>Please fill this form and submit to add a new club to the record.</p>
                <form id="clubForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data" onsubmit="saveImageData()">
                    <div class="form-group mb-2">
                        <label>Club Name</label>
                        <input type="text" name="clubName" class="form-control <?php echo (!empty($clubName_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $clubName; ?>">
                        <span class="invalid-feedback"><?php echo $clubName_err; ?></span>
                    </div>
                    <div class="form-group mb-2">
                        <label>Description</label>
                        <textarea name="description" class="form-control <?php echo (!empty($description_err)) ? 'is-invalid' : ''; ?>"><?php echo $description; ?></textarea>
                        <span class="invalid-feedback"><?php echo $description_err; ?></span>
                    </div>
                    <div class="form-group mb-2">
                        <label>Mission</label>
                        <textarea name="mission" class="form-control <?php echo (!empty($mission_err)) ? 'is-invalid' : ''; ?>"><?php echo $mission; ?></textarea>
                        <span class="invalid-feedback"><?php echo $mission_err; ?></span>
                    </div>
                    <div class="form-group mb-2">
                        <label>Vision</label>
                        <textarea name="vision" class="form-control <?php echo (!empty($vision_err)) ? 'is-invalid' : ''; ?>"><?php echo $vision; ?></textarea>
                        <span class="invalid-feedback"><?php echo $vision_err; ?></span>
                    </div>
                    <div class="form-group mb-2">
                        <label>History</label>
                        <textarea name="history" class="form-control <?php echo (!empty($history_err)) ? 'is-invalid' : ''; ?>"><?php echo $history; ?></textarea>
                        <span class="invalid-feedback"><?php echo $history_err; ?></span>
                    </div>
                    <div class="form-group mb-2">
                        <label>Cover Photo</label>
                        <input type="file" name="coverPhoto" id="coverPhoto" class="form-control-file <?php echo (!empty($coverPhoto_err)) ? 'is-invalid' : ''; ?>" onchange="previewImage()">
                        <span class="invalid-feedback"><?php echo $coverPhoto_err; ?></span>
                        <img id="coverPhotoPreview" src="#" alt="" style="display: none;">
                        <input type="hidden" name="hiddenCoverPhoto" id="hiddenCoverPhoto" value="<?php echo htmlspecialchars($coverPhoto); ?>">
                    </div>
                    <br>

                    <div class="form-group mb-2">
                        <label>Recommend to Departments<p class="text-muted"><em>(Check all that applies)</em></label>
                        <div>
                            <input type="checkbox" name="departments[]" value="TEP" id="tep">
                            <label for="tep">TEP</label>
                        </div>
                        <div>
                            <input type="checkbox" name="departments[]" value="BSBA" id="bsba">
                            <label for="bsba">BSBA</label>
                        </div>
                        <div>
                            <input type="checkbox" name="departments[]" value="CCS" id="ccs">
                            <label for="ccs">CCS</label>
                        </div>
                    </div>



                    <hr>
                    
                    <div class="form-group mb-2">
                        <label>Add Moderator</label>
                        <select name="moderator" id="moderatorSelect" class="form-control">
                            <option value="">-- Select From Existing Moderators --</option>
                            <optgroup label="">
                                <?php foreach ($moderators as $moderator): ?>
                                    <option value="<?php echo htmlspecialchars($moderator['moderator_id']); ?>">
                                        <?php echo htmlspecialchars($moderator['moderator_name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </optgroup>
                        </select>
                    </div>

                    <!-- DROPDOWN FOR ADDING EXISTING OR "NEW" MODERATOR COMMENTED TEMPORARILY 
                    <div class="form-group mb-2">
                        <label>Add Moderator</label>
                        <select name="moderator" id="moderatorSelect" class="form-control">
                            <option value="">-- Select From Existing Moderators or Add New --</option>
                            <optgroup label="">
                                <?php foreach ($moderators as $moderator): ?>
                                    <option value="<?php echo htmlspecialchars($moderator['moderator_id']); ?>">
                                        <?php echo htmlspecialchars($moderator['moderator_name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </optgroup>
                            <optgroup label=" ">
                                <option value="add_new_moderator" style="font-weight: bold;">+ Add New Moderator</option>
                            </optgroup>
                        </select>
                    </div> -->


                    <input type="submit" class="btn btn-primary" value="Submit">
                    <a href="javascript:window.history.back();" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
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
                        <input type="text" name="middleInitial" maxlength="1" class="form-control underline-input" required>
                    </div>
                    <div class="form-group mb-2">
                        <label>Last Name:</label>
                        <input type="text" name="lastName" class="form-control underline-input" required>
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
    // let cropper;

    function previewImage() {
        const file = document.querySelector('input[name="coverPhoto"]').files[0];
        const preview = document.getElementById('coverPhotoPreview');
        const reader = new FileReader();

        reader.addEventListener("load", function () {
            // Convert image file to base64 string
            preview.src = reader.result;
            preview.style.display = 'block';

            // Initialize Cropper.js with options (adjust as needed)
            // cropper = new Cropper(preview, {
            //     aspectRatio: 16 / 9, // Set aspect ratio (e.g., 16:9)
            //     crop(event) {
            //         // Output the cropped area data
            //         console.log(event.detail.x);
            //         console.log(event.detail.y);
            //         console.log(event.detail.width);
            //         console.log(event.detail.height);
            //     },
            // });
        }, false);

        if (file) {
            reader.readAsDataURL(file);
        }
    }

    // function saveImageData() {
    //     if (cropper) {
    //         // Get cropped canvas as base64 encoded JPEG image
    //         const canvas = cropper.getCroppedCanvas({
    //             width: 640, // Set desired output width (optional)
    //             height: 360, // Set desired output height (optional)
    //             imageSmoothingEnabled: true, // Smooth the image (optional)
    //             imageSmoothingQuality: 'high', // High quality smoothing (optional)
    //         });

    //         if (canvas) {
    //             // Convert canvas to base64 data URL
    //             const dataURL = canvas.toDataURL('image/jpeg');

    //             // Update hidden input with cropped image data
    //             document.getElementById('hiddenCoverPhoto').value = dataURL;

    //             // Optionally, display the cropped image preview
    //             const preview = document.getElementById('coverPhotoPreview');
    //             preview.src = dataURL;
    //             preview.style.display = 'block';
    //         }
    //     }
    // }

    window.addEventListener('load', function () {
        const hiddenCoverPhoto = document.getElementById('hiddenCoverPhoto').value;
        if (hiddenCoverPhoto) {
            const preview = document.getElementById('coverPhotoPreview');
            preview.src = hiddenCoverPhoto;
            preview.style.display = 'block';
        }
    });
</script>
</body>
</html>
