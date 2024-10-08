<?php
require_once "../../../../config.php";

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

$clubName = $information = $coverPhoto = "";
$clubName_err = $information_err = $coverPhoto_err = "";
$moderators = [];
define('COVERPHOTO_DEFAULT', 'COVERPHOTO_DEFAULT.png');
define('PROF_PIC_DEFAULT', 'PROF_PIC.png');

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
    $email = trim($_POST['email']);
    $password = trim($_POST['password']); // No hashing

    // Set the profile picture to default
    $profilePic = PROF_PIC_DEFAULT;

    $sql2 = "INSERT INTO tbl_moderators (firstName, middleName, lastName, email, password, profilePic, dateAdded) 
             VALUES (:firstName, :middleInitial, :lastName, :email, :password, :profilePic, NOW())";
    if ($stmt2 = $pdo->prepare($sql2)) {
        $stmt2->bindParam(":firstName", $firstName);
        $stmt2->bindParam(":middleInitial", $middleInitial);
        $stmt2->bindParam(":lastName", $lastName);
        $stmt2->bindParam(":email", $email);
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

    $input_information = trim($_POST["information"]);
    if (empty($input_information)) {
        $information_err = "Please enter club information.";
    } else {
        $information = $input_information;
    }

    if (isset($_FILES['coverPhoto']) && $_FILES['coverPhoto']['name']) {
        // File was uploaded
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
            // Generate a unique name for the cover photo
            $newCoverPhotoName = 'club_' . uniqid() . '.' . $imageExtension;
            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/esas/esas_admin/images/';
            $uploadPath = $uploadDir . $newCoverPhotoName;

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            if (move_uploaded_file($coverPhotoTmpName, $uploadPath)) {
                // Successful upload, use the new image name
                $coverPhoto = $newCoverPhotoName;
            } else {
                $coverPhoto_err = "Failed to upload image.";
            }
        }
    } else {
        // No new file uploaded, use the hidden field value
        $coverPhoto = $_POST['hiddenCoverPhoto']; // Keep the old cover photo
        
        // Construct the path for the existing cover photo in the student images directory
        $existingCoverPhotoPath = $_SERVER['DOCUMENT_ROOT'] . '/esas/esas_student/images/' . $coverPhoto;
        
        // Construct the destination path for the admin images
        $adminImagesDir = $_SERVER['DOCUMENT_ROOT'] . '/esas/esas_admin/images/';
        $adminCoverPhotoPath = $adminImagesDir . $coverPhoto;

        // Check if the existing cover photo file exists in the student images directory
        if (file_exists($existingCoverPhotoPath)) {
            // Only copy if it doesn't already exist in admin images
            if (!file_exists($adminCoverPhotoPath)) {
                copy($existingCoverPhotoPath, $adminCoverPhotoPath);
            }
        } else {
            $coverPhoto_err = "Existing cover photo does not exist.";
        }
    }

    // If no cover photo is uploaded and no previous one exists, use a default image
    if (empty($coverPhoto)) {
        $coverPhoto = COVERPHOTO_DEFAULT; // You can set your own default image path here
    }

    $founder_id = isset($_POST['founder_id']) ? $_POST['founder_id'] : null; // Fetch the value from POST

    if (empty($clubName_err) && empty($information_err) && empty($coverPhoto_err) && !empty($founder_id)) {
        $sql = "INSERT INTO tbl_clubs (clubName, information, coverPhoto, founder_id, dateAdded) VALUES (:clubName, :information, :coverPhoto, :founder_id, NOW())";
        if ($stmt = $pdo->prepare($sql)) {
            $stmt->bindParam(":clubName", $clubName);
            $stmt->bindParam(":information", $information);
            $stmt->bindParam(":coverPhoto", $coverPhoto);
            $stmt->bindParam(":founder_id", $founder_id); // Ensure this is not null

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

// $clubName = isset($_GET['clubName']) ? htmlspecialchars($_GET['clubName']) : '';
// $coverPhoto = isset($_GET['coverPhoto']) ? htmlspecialchars($_GET['coverPhoto']) : '';
$clubName = isset($_GET['clubName']) ? $_GET['clubName'] : '';
$coverPhoto = isset($_GET['coverPhoto']) ? $_GET['coverPhoto'] : '';
$student_id = isset($_GET['student_id']) ? $_GET['student_id'] : '';

?>




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
                <h2 class="mt-5">Add Request to Clubs List</h2>
                <p>Please fill this form and submit to add a new club to the record.</p>
                <form id="clubForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data" onsubmit="saveImageData()">
                    <input type="hidden" name="founder_id" value="<?php echo $student_id; ?>">
                    <div class="form-group mb-2">
                        <label>Club Name</label>
                        <input type="text" name="clubName" class="form-control <?php echo (!empty($clubName_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $clubName; ?>">
                        <span class="invalid-feedback"><?php echo $clubName_err; ?></span>
                    </div>
                    <div class="form-group mb-2">
                        <label>Information</label>
                        <textarea name="information" class="form-control <?php echo (!empty($information_err)) ? 'is-invalid' : ''; ?>"><?php echo $information; ?></textarea>
                        <span class="invalid-feedback"><?php echo $information_err; ?></span>
                    </div>
                    <div class="form-group mb-2">
                        <label>Cover Photo</label>
                        <input type="file" name="coverPhoto" id="coverPhoto" class="form-control-file <?php echo (!empty($coverPhoto_err)) ? 'is-invalid' : ''; ?>" onchange="previewImage()">
                        <span class="invalid-feedback"><?php echo $coverPhoto_err; ?></span>
                        <img id="coverPhotoPreview" src="<?php echo !empty($coverPhoto) ? '/esas/esas_student/images/' . htmlspecialchars($coverPhoto) : '#'; ?>" alt="Cover Photo Preview" style="display: <?php echo !empty($coverPhoto) ? 'block' : 'none'; ?>;">
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

                    <hr>

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
    let cropper;

    
function previewImage() {
    var file = document.getElementById('coverPhoto').files[0]; // Get the file from input
    var preview = document.getElementById('coverPhotoPreview'); // Get the image element
    var reader = new FileReader();

    // If there's a file, display the image
    if (file) {
        reader.onload = function(e) {
            preview.src = e.target.result; // Set the src of the image to the loaded file
            preview.style.display = 'block'; // Display the image
            preview.style.maxWidth = '100%'; // Adjust preview styling
            preview.style.maxHeight = '300px'; // Limit the image height
        };
        reader.readAsDataURL(file); // Read the file as a data URL
    } else {
        preview.src = ''; // Clear the src if no file
        preview.style.display = 'none'; // Hide the image preview
    }
}

</script>
</body>
</html>