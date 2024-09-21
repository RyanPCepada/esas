<?php
require_once "../../../../config.php";

$clubId = $_GET['club_id'] ?? null; // Get the club ID from the query string
$clubName = $information = $coverPhoto = "";
$clubName_err = $information_err = $coverPhoto_err = "";
define('COVERPHOTO_DEFAULT', 'COVERPHOTO_DEFAULT.png');

if ($clubId) {
    $clubQuery = "SELECT * FROM tbl_clubs WHERE club_id = :clubId";
    if ($stmt = $pdo->prepare($clubQuery)) {
        $stmt->bindParam(":clubId", $clubId);
        if ($stmt->execute()) {
            $club = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($club) {
                $clubName = $club['clubName'];
                $information = $club['information'];
                $coverPhoto = $club['coverPhoto'] ?: COVERPHOTO_DEFAULT; // Set default if no cover photo exists
            } else {
                echo "No club found with that ID.";
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

    $input_information = trim($_POST["information"]);
    if (empty($input_information)) {
        $information_err = "Please enter club information.";
    } else {
        $information = $input_information;
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
        } elseif ($coverPhotoSize > 5000000) {
            $coverPhoto_err = "Image size is too large (max 5MB).";
        } else {
            $newCoverPhotoName = 'club_' . uniqid() . '.' . $imageExtension;
            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/esas/esas-admin/images/';
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

    // Update the club if no errors
    if (empty($clubName_err) && empty($information_err) && empty($coverPhoto_err)) {
        $sql = "UPDATE tbl_clubs SET clubName = :clubName, information = :information, coverPhoto = :coverPhoto WHERE club_id = :clubId";
        if ($stmt = $pdo->prepare($sql)) {
            $stmt->bindParam(":clubName", $clubName);
            $stmt->bindParam(":information", $information);
            $stmt->bindParam(":coverPhoto", $coverPhoto);
            $stmt->bindParam(":clubId", $clubId);

            if ($stmt->execute()) {
                header("location: ../../all_clubs.php");
                exit();
            } else {
                echo "Oops! Something went wrong. Please try again later.";
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
    <title>eSAS - Update Club</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="../../../assets/css/styles.css" rel="stylesheet" />
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
                    <h2 class="mt-5">Update Club</h2>
                    <p>Please fill this form to update the club information.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?club_id=' . $clubId; ?>" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Club Name</label>
                            <input type="text" name="clubName" class="form-control <?php echo (!empty($clubName_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($clubName); ?>">
                            <span class="invalid-feedback"><?php echo $clubName_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Information</label>
                            <textarea name="information" class="form-control <?php echo (!empty($information_err)) ? 'is-invalid' : ''; ?>" rows="5"><?php echo htmlspecialchars($information); ?></textarea>
                            <span class="invalid-feedback"><?php echo $information_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Cover Photo</label>
                            <input type="file" name="coverPhoto" class="form-control-file <?php echo (!empty($coverPhoto_err)) ? 'is-invalid' : ''; ?>" onchange="previewImage()">
                            <span class="invalid-feedback"><?php echo $coverPhoto_err; ?></span>
                            <img src="/esas/esas_admin/images/<?php echo htmlspecialchars($coverPhoto); ?>" id="coverPhotoPreview" alt="Cover Photo Preview" style="display: block; margin-top: 10px; width: 100%; height: auto;">
                        </div>
                        <input type="submit" class="btn btn-primary" value="Update">
                        <a href="javascript:history.back()" class="btn btn-secondary">Cancel</a>
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
