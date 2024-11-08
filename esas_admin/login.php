<?php
session_start();
require_once '../config.php';

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

$admin_id = '';
$password = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $admin_id = trim($_POST['admin_id']);
    $password = trim($_POST['password']);
    
    // Function to check admin login with hashed password
    function checkAdmin($pdo, $admin_id, $password) {
        $query = "SELECT * FROM tbl_admin WHERE admin_id = :admin_id";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['admin_id' => $admin_id]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // If the admin exists, verify the password
        if ($admin) {
            return password_verify($password, $admin['password']) ? $admin : false;
        }
        return false; // Admin not found
    }

    // Function to insert activity log
    function insertActivityLog($pdo, $admin_id) {
        $query = "INSERT INTO tbl_activity_logs (activity, dateAdded, admin_id) VALUES (:activity, :dateAdded, :admin_id)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'activity' => 'You logged in to your account',
            'dateAdded' => date('Y-m-d H:i:s'), // current timestamp
            'admin_id' => $admin_id
        ]);
    }
    
    // Assuming $pdo is correctly initialized in your config.php
    
    $admin_result = checkAdmin($pdo, $admin_id, $password);
    if ($admin_result) {
        $_SESSION['admin_id'] = $admin_result['admin_id'];
        
        // Insert the activity log after successful login
        insertActivityLog($pdo, $admin_result['admin_id']);

        // Redirect to clubs.php upon successful login
        echo "<script>alert('Logged in successfully!');</script>";
        echo "<script>window.location.href = '/esas/esas_admin/public/dashboard.php';</script>";
        exit();
    } else {
        // Show an alert if login credentials are incorrect
        echo "<script>alert('Invalid Admin ID or password.');</script>";
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
    
    <link href="../assets/css/jquery.dataTables.min.css" rel="stylesheet" />
    <script src="../assets/js/all.js" crossorigin="anonymous"></script>
    <script src="../assets/js/jquery-3.6.0.js"></script>
    <link href="../assets/css/styles.css" rel="stylesheet" />
    <link href="../assets/img/nbsclogo.png" rel="icon">

    <style>
        *{
            margin: 0;
            padding: 0;
            font-family: 'Helvetica';
            box-sizing: border-box;
        }
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            padding: 0px;
            background-color: #f8f9fa;
            margin: 0;
        }
        .nbsc-sas {
            width: 100%;
            max-width: 400px;
            padding: 20px;
            padding-top: 0px; /*To lift up the whole login container*/
            display: block;
            margin: 0 auto;
        }
        #nbsc_sas_name {
            margin-bottom: 10px;
        }
        #system_name {
            line-height: 1.2 !important;
            font-weight: normal !important; 
        }
        .login-container {
            width: 100%;
            max-width: 400px;
            padding: 20px;
            display: block;
            margin: 0 auto;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        #sas_logo {
            width: 25%;
            display: block; /* Ensures image is centered */
            margin: 0 auto; /* Centers image horizontally */
        }
        #label_login {
            margin-bottom: 20px;
        }
        .form-control {
            font-size: 16px;
            padding: 10px;
        }
        .form-control:focus {
            box-shadow: none;
            border-color: #28a745;
        }

        @media (min-width: 768px) {
            #sas_logo {
                width: 25%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="nbsc-sas text-center">
            <img src="../assets/img/SAS_LOGO.png" id="sas_logo" alt="SAS Logo">
            <h3 class="mb-1" id="nbsc_sas_name">NBSC-SAS</h3>
            <h5 class="text-muted" id="system_name">Student Club Organization and Information System<h5>
        </div>

        <div class="login-container">
            <h4 class="text-center" id="label_login">Admin Login</h4>
            <form action="" method="POST">
                <div class="form-group">
                    <label for="admin_id">Admin ID</label>
                    <input type="text" class="form-control" id="admin_id" name="admin_id" value="<?php echo htmlspecialchars($admin_id); ?>" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" value="<?php echo htmlspecialchars($password); ?>" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block btn-login">Login</button>
            </form>
        </div>
    </div>

    
    <!-- <?php include 'assets/components/modals.php' ?> -->
    <script src="../assets/js/jquery.dataTables.min.js"></script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/global_script.js"></script>
    <script>
        // clickSubModule('nav/userreg/userreg_main.php')
        // clickSubModule('nav/purchaserequest/pr_main.php');
        // clickSubModule('nav/purchaserequest/pr_main.php')
    </script>
</body>
</html>