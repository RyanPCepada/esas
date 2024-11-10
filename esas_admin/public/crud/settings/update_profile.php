<?php
require_once "../../../../config.php"; // Include your database config file
session_start();

if (!isset($_SESSION['moderator_id'])) {
    die("You are not logged in.");
}

$moderator_id = $_SESSION['moderator_id'];

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Fetch moderator data to pre-fill the form
$sql = "SELECT * FROM tbl_moderators WHERE moderator_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$moderator_id]);
$moderator = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$moderator) {
    die("Moderator not found.");
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form inputs
    $firstName = $_POST['firstName'];
    $middleName = $_POST['middleName'];
    $lastName = $_POST['lastName'];
    $age = $_POST['age'];
    $birthday = $_POST['birthday'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $phoneNumber = $_POST['phoneNumber'];
    $department = $_POST['department'];
    $profession = $_POST['profession'];

    // Initialize profilePic with the existing picture
    $profilePic = $moderator['profilePic'];

    // Handle profile picture upload
    if (isset($_FILES['profilePic']) && $_FILES['profilePic']['error'] == UPLOAD_ERR_OK) {
        $uploadedPic = $_FILES['profilePic'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxFileSize = 10 * 1024 * 1024; // 10MB
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/esas/esas_moderator/images/';

        if (in_array($uploadedPic['type'], $allowedTypes)) {
            if ($uploadedPic['size'] <= $maxFileSize) {
                $fileName = uniqid() . '-' . basename($uploadedPic['name']);
                $targetFilePath = $uploadDir . $fileName;

                // Move the uploaded file to the desired location
                if (move_uploaded_file($uploadedPic['tmp_name'], $targetFilePath)) {
                    $profilePic = $fileName; // Update profile picture to new one
                } else {
                    echo "Error uploading the profile picture.";
                }
            } else {
                echo "File size exceeds the limit of 10MB.";
            }
        } else {
            echo "Invalid file type. Only JPEG, PNG, and GIF are allowed.";
        }
    }

    // Update other information
    $updateSql = "
        UPDATE tbl_moderators SET 
            firstName = ?, 
            middleName = ?, 
            lastName = ?, 
            age = ?, 
            birthday = ?, 
            gender = ?, 
            email = ?, 
            phoneNumber = ?, 
            department = ?, 
            profession = ?,
            profilePic = ? 
        WHERE moderator_id = ?";

    $updateStmt = $pdo->prepare($updateSql);
    if ($updateStmt->execute([
        $firstName, 
        $middleName, 
        $lastName, 
        $age, 
        $birthday, 
        $gender, 
        $email, 
        $phoneNumber, 
        $department, 
        $profession, 
        $profilePic, 
        $moderator_id
    ])) {
        // Profile updated successfully, now log the activity
        $logSql = "INSERT INTO tbl_activity_logs (activity, dateAdded, moderator_id) VALUES (:activity, :dateAdded, :moderator_id)";
        $logStmt = $pdo->prepare($logSql);
        $logStmt->execute([
            'activity' => 'You updated your profile',
            'dateAdded' => date('Y-m-d H:i:s'), // current timestamp
            'moderator_id' => $moderator_id
        ]);

        echo "Profile updated successfully!";
        header("location: ../../../settings.php");
        exit();
    } else {
        // Get detailed error info for debugging
        $errorInfo = $updateStmt->errorInfo();
        echo "Error updating profile: " . $errorInfo[2]; // More detailed error message
    }
}
?>


<h4 class="text-muted mb-3">Update Profile</h4>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <?php if (!empty($moderator['profilePic'])): ?>
            <img src="/esas/esas_moderator/images/<?php echo htmlspecialchars($moderator['profilePic']); ?>" alt="Profile Picture" style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover;">
        <?php endif; ?>
        
        <input type="file" class="d-none" id="profilePic" name="profilePic" accept="image/*" onchange="updateImagePreview(this)">
        
        <label for="profilePic" class="btn btn-light text-primary" style="cursor: pointer;">
            <i class="fa fa-edit" aria-hidden="true"></i>
        </label>
    </div>
    <div class="form-group">
        <label for="firstName">First Name</label>
        <input type="text" class="form-control" id="firstName" name="firstName" value="<?php echo htmlspecialchars($moderator['firstName']); ?>" required>
    </div>
    <div class="form-group">
        <label for="middleName">Middle Name</label>
        <input type="text" class="form-control" id="middleName" name="middleName" value="<?php echo htmlspecialchars($moderator['middleName']); ?>">
    </div>
    <div class="form-group">
        <label for="lastName">Last Name</label>
        <input type="text" class="form-control" id="lastName" name="lastName" value="<?php echo htmlspecialchars($moderator['lastName']); ?>" required>
    </div>
    <div class="form-group row">
        <div class="col-2">
            <label for="age">Age</label>
            <input type="number" class="form-control" id="age" name="age" value="<?php echo htmlspecialchars($moderator['age']); ?>" required style="width: 80px;">
        </div>
        <div class="col-5">
            <label for="birthday">Birthday</label>
            <input type="date" class="form-control" id="birthday" name="birthday" value="<?php echo htmlspecialchars($moderator['birthday']); ?>" required>
        </div>
        <div class="col-5">
            <label for="gender">Gender</label>
            <select class="form-control" id="gender" name="gender" required>
                <option value="Male" <?php echo $moderator['gender'] == 'Male' ? 'selected' : ''; ?>>Male</option>
                <option value="Female" <?php echo $moderator['gender'] == 'Female' ? 'selected' : ''; ?>>Female</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($moderator['email']); ?>" required>
    </div>
    <div class="form-group">
        <label for="phoneNumber">Phone Number</label>
        <input type="text" class="form-control" id="phoneNumber" name="phoneNumber" value="<?php echo htmlspecialchars($moderator['phoneNumber']); ?>" required>
    </div>
    <div class="form-group">
        <label for="department">Department</label>
        <input type="text" class="form-control" id="department" name="department" value="<?php echo htmlspecialchars($moderator['department']); ?>" required>
    </div>
    <div class="form-group">
        <label for="profession">Profession</label>
        <input type="text" class="form-control" id="profession" name="profession" value="<?php echo htmlspecialchars($moderator['profession']); ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Update Profile</button>
</form>

<script>
function updateImagePreview(input) {
    const imgTag = document.querySelector('img[alt="Profile Picture"]');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            imgTag.src = e.target.result; // Update the image source to the new file
        };
        reader.readAsDataURL(input.files[0]); // Read the file as a data URL
    }
}
</script>
