<?php
// Include config file
require_once "../../../../config.php";

// Define variables and initialize with empty values
$clubName = $information = $coverPhoto = "";
$clubName_err = $information_err = $coverPhoto_err = "";
$moderators = []; // Array to store moderators

// Define a default cover photo filename
define('COVERPHOTO_DEFAULT', 'COVERPHOTO_DEFAULT.png');

// Fetch moderators from the database
$moderatorQuery = "SELECT moderator_id, CONCAT(firstName, ' ', lastName) AS moderator_name FROM tbl_moderators";
if ($stmt = $pdo->prepare($moderatorQuery)) {
    if ($stmt->execute()) {
        $moderators = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate clubName
    $input_clubName = trim($_POST["clubName"]);
    if (empty($input_clubName)) {
        $clubName_err = "Please enter a club name.";
    } else {
        $clubName = $input_clubName;
    }

    // Validate information
    $input_information = trim($_POST["information"]);
    if (empty($input_information)) {
        $information_err = "Please enter club information.";
    } else {
        $information = $input_information;
    }

    // Validate and handle cover photo upload
    if (isset($_FILES['coverPhoto']) && $_FILES['coverPhoto']['name']) {
        $coverPhotoName = $_FILES['coverPhoto']['name'];
        $coverPhotoSize = $_FILES['coverPhoto']['size'];
        $coverPhotoTmpName = $_FILES['coverPhoto']['tmp_name'];

        // Add image validation logic here
        $validImageExtensions = ['jpg', 'jpeg', 'png'];
        $imageExtension = pathinfo($coverPhotoName, PATHINFO_EXTENSION);
        $imageExtension = strtolower($imageExtension);

        if (!in_array($imageExtension, $validImageExtensions)) {
            $coverPhoto_err = "Invalid image extension. Only JPG, JPEG, and PNG are allowed.";
        } elseif ($coverPhotoSize > 5000000) { // Max size 5MB
            $coverPhoto_err = "Image size is too large (max 5MB).";
        } else {
            // Generate a unique file name for the new image
            $newCoverPhotoName = 'club_' . uniqid() . '.' . $imageExtension;

            // Save the image to your server
            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/esas/esas-admin/images/';
            $uploadPath = $uploadDir . $newCoverPhotoName;

            // Ensure the directory exists
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true); // Create directory if it doesn't exist
            }

            if (!move_uploaded_file($coverPhotoTmpName, $uploadPath)) {
                $coverPhoto_err = "Failed to upload image.";
            } else {
                $coverPhoto = $newCoverPhotoName;
            }
        }
    } else {
        // If no file was uploaded, use the default cover photo
        $coverPhoto = COVERPHOTO_DEFAULT;
    }

    // Check input errors before inserting into database
    if (empty($clubName_err) && empty($information_err) && empty($coverPhoto_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO tbl_clubs (clubName, information, coverPhoto, dateAdded) VALUES (:clubName, :information, :coverPhoto, NOW())";

        if ($stmt = $pdo->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":clubName", $clubName);
            $stmt->bindParam(":information", $information);
            $stmt->bindParam(":coverPhoto", $coverPhoto);

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Get the last inserted club ID
                $clubId = $pdo->lastInsertId();

                // Get selected moderator from the form
                $selectedModerator = $_POST["moderator"];

                // Insert the club-moderator relationship
                if (!empty($selectedModerator)) {
                    $sql2 = "INSERT INTO tbl_clubs_and_moderators (club_id, moderator_id, dateAdded) VALUES (:clubId, :moderatorId, NOW())";
                    if ($stmt2 = $pdo->prepare($sql2)) {
                        $stmt2->bindParam(":clubId", $clubId);
                        $stmt2->bindParam(":moderatorId", $selectedModerator);
                        $stmt2->execute();
                    }
                }

                // Records created successfully. Redirect to landing page
                header("location: ../../all_clubs.php");
                exit();
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        unset($stmt);
    }

    // Close connection
    unset($pdo);
}
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
                <h2 class="mt-5">Add Club</h2>
                <p>Please fill this form and submit to add a new club to the record.</p>
                <form id="clubForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data" onsubmit="saveImageData()">
                    <div class="form-group">
                        <label>Club Name</label>
                        <input type="text" name="clubName" class="form-control <?php echo (!empty($clubName_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $clubName; ?>">
                        <span class="invalid-feedback"><?php echo $clubName_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Information</label>
                        <textarea name="information" class="form-control <?php echo (!empty($information_err)) ? 'is-invalid' : ''; ?>"><?php echo $information; ?></textarea>
                        <span class="invalid-feedback"><?php echo $information_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Cover Photo</label>
                        <input type="file" name="coverPhoto" id="coverPhoto" class="form-control-file <?php echo (!empty($coverPhoto_err)) ? 'is-invalid' : ''; ?>" onchange="previewImage()">
                        <span class="invalid-feedback"><?php echo $coverPhoto_err; ?></span>
                        <img id="coverPhotoPreview" src="#" alt="Cover Photo Preview" style="display: none;">
                        <input type="hidden" name="hiddenCoverPhoto" id="hiddenCoverPhoto" value="<?php echo htmlspecialchars($coverPhoto); ?>">
                    </div>
                    <div class="form-group">
                        <label>Add Moderator</label>
                        <select name="moderator" class="form-control">
                            <option value="">Select from existing Moderators</option>
                            <?php foreach ($moderators as $moderator): ?>
                                <option value="<?php echo htmlspecialchars($moderator['moderator_id']); ?>">
                                    <?php echo htmlspecialchars($moderator['moderator_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <input type="submit" class="btn btn-primary" value="Submit">
                    <a href="../../all_clubs.php" class="btn btn-secondary">Cancel</a>
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
    let cropper;

    function previewImage() {
        const file = document.querySelector('input[name="coverPhoto"]').files[0];
        const preview = document.getElementById('coverPhotoPreview');
        const reader = new FileReader();

        reader.addEventListener("load", function () {
            // Convert image file to base64 string
            preview.src = reader.result;
            preview.style.display = 'block';

            // Initialize Cropper.js with options (adjust as needed)
            cropper = new Cropper(preview, {
                aspectRatio: 16 / 9, // Set aspect ratio (e.g., 16:9)
                crop(event) {
                    // Output the cropped area data
                    console.log(event.detail.x);
                    console.log(event.detail.y);
                    console.log(event.detail.width);
                    console.log(event.detail.height);
                },
            });
        }, false);

        if (file) {
            reader.readAsDataURL(file);
        }
    }

    function saveImageData() {
        if (cropper) {
            // Get cropped canvas as base64 encoded JPEG image
            const canvas = cropper.getCroppedCanvas({
                width: 640, // Set desired output width (optional)
                height: 360, // Set desired output height (optional)
                imageSmoothingEnabled: true, // Smooth the image (optional)
                imageSmoothingQuality: 'high', // High quality smoothing (optional)
            });

            if (canvas) {
                // Convert canvas to base64 data URL
                const dataURL = canvas.toDataURL('image/jpeg');

                // Update hidden input with cropped image data
                document.getElementById('hiddenCoverPhoto').value = dataURL;

                // Optionally, display the cropped image preview
                const preview = document.getElementById('coverPhotoPreview');
                preview.src = dataURL;
                preview.style.display = 'block';
            }
        }
    }

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
