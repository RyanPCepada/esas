
<?php
session_start();
include '../config.php';

$email = '';
$password = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    
    // Function to check admin login with plain text password
    function checkAdmin($pdo, $email, $password) {
        $query = "SELECT * FROM tbl_admin WHERE email = :email AND password = :password";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['email' => $email, 'password' => $password]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Assuming $pdo is correctly initialized in your config.php
    
    $admin_result = checkAdmin($pdo, $email, $password);
    if ($admin_result) {
        $_SESSION['admin_id'] = $admin_result['admin_id'];
        // Redirect to clubs.php upon successful login
        echo "<script>alert('Logged in successfully!');</script>";
        echo "<script>window.location.href = '/esas/esas-admin/public/dashboard.php';</script>";
        exit();
    } else {
        // Show an alert if login credentials are incorrect
        echo "<script>alert('Invalid username/email or password.');</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eSAS - Admin Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
    <link rel="stylesheet" href="../esas-admin/css/login.css">
    <div class="container">
        <div class="nbsc-sas text-center">
            <img src="icons/SAS_LOGO.png" id="sas_logo" alt="SAS Logo">
            <h3 class="mb-1" id="nbsc_sas_name">NBSC-SAS</h3>
            <h5 class="text-muted" id="system_name">Student Organization Club Membership and Information System<h5>
        </div>

        <div class="login-container">
            <h4 class="text-center" id="label_login">Admin Login</h4>
            <form action="" method="POST">
                <div class="form-group">
                    <label for="email">Username/Email</label>
                    <input type="text" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" value="<?php echo htmlspecialchars($password); ?>" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block btn-login">Login</button>
            </form>
        </div>
    </div>
</body>
</html>