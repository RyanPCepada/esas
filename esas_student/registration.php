<?php
// Include config file
require_once "../config.php";

// Define variables and initialize with empty values
$question1 = $question2 = $question3 = $status = $club_id = "";
$question1_err = $question2_err = $question3_err = $status_err = $club_id_err = "";

// Fetch club name and club_id if club_id is provided in the query parameter
if (isset($_GET['club_id'])) {
    $club_id = intval($_GET['club_id']);
    $sql = "SELECT clubName FROM tbl_clubs WHERE club_id = :club_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":club_id", $club_id, PDO::PARAM_INT);
    $stmt->execute();
    $club = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($club) {
        $clubName = $club['clubName'];
    }
    unset($stmt);
}

// Set default values if not set or empty
if (empty($status)) {
    $status = 'pending';
}

// Include the registration form
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eSAS - Student Registration</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        body {
            font: 14px Helvetica;
        }
        .wrapper {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 0px;
        }
        .container-fluid {
            padding: 20px;
        }
        .navbar-darkblue {
            background-color: #003366;
        }
        .navbar-darkblue .navbar-toggler {
            border-color: rgba(255, 255, 255, 0.1);
        }
        .navbar-darkblue .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 30 30' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath stroke='rgba(255, 255, 255, 1)' stroke-width='2' stroke-linecap='round' stroke-miterlimit='10' d='M4 7h22M4 15h22M4 23h22'/%3E%3C/svg%3E");
        }
        #dashboard_navigations {
            float: flex-end;
        }
        .form-group select {
            max-height: auto !important;
            overflow-y: auto !important;
        }

    </style>
</head>
<body>
    <!-- <nav class="navbar navbar-expand-lg navbar-darkblue">
        <img src="icons/SAS_LOGO.png" height="50" class="d-inline-block align-top" alt="SAS Logo">
        <h5 class="ml-2 mb-0 text-light" id="nbsc_sas_name">Student Organization Club Membership and Information System</h5>
        <button class="navbar-toggler mt-2" type="button" data-toggle="collapse" data-target="#dashboard_navigations" aria-controls="dashboard_navigations" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="dashboard_navigations">
            <ul class="navbar-nav mr-auto"></ul>
            <form class="form-inline my-2 my-lg-0">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-light my-2 my-sm-0" type="submit">Search</button>
            </form>
            <!-- <a href="logout.php" class="btn btn-danger ml-3">Log out</a> --
            <a href="../logout.php" class="btn btn-danger ml-3">Log out</a>
        </div>
    </nav> -->

    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <?php if (!empty($clubName)): ?>
                        <h2><?php echo htmlspecialchars($clubName); ?></h2>
                        <!-- <h2><?php echo htmlspecialchars($club_id); ?></h2> -->
                        <h4>Student Registration</h4>
                    <?php endif; ?>
                    <p class="mb-5">Please fill this form and submit to register.</p>
                    <form action="../esas_student/actions/registration_action.php" method="post">
                        <div class="form-group">
                            <label>Why do you want to join this club?</label>
                            <textarea name="question1" rows="3" class="form-control <?php echo (!empty($question1_err)) ? 'is-invalid' : ''; ?>" required><?php echo htmlspecialchars($question1); ?></textarea>
                            <span class="invalid-feedback"><?php echo $question1_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>What skills or experiences do you have that will contribute to the club's activities?</label>
                            <textarea name="question2" rows="3" class="form-control <?php echo (!empty($question2_err)) ? 'is-invalid' : ''; ?>" required><?php echo htmlspecialchars($question2); ?></textarea>
                            <span class="invalid-feedback"><?php echo $question2_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>How do you plan to balance your time between club activities and your academic responsibilities?</label>
                            <textarea name="question3" rows="3" class="form-control <?php echo (!empty($question3_err)) ? 'is-invalid' : ''; ?>" required><?php echo htmlspecialchars($question3); ?></textarea>
                            <span class="invalid-feedback"><?php echo $question3_err; ?></span>
                        </div>
                        <input type="hidden" name="club_id" value="<?php echo htmlspecialchars($club_id); ?>">
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="#" onclick="window.history.back();" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>





    <!-- <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    ?php if (!empty($clubName)): ?>
                        <h2>?php echo htmlspecialchars($clubName); ?></h2>
                        <!-- <h2>?php echo htmlspecialchars($club_id); ?></h2> --
                        <h4>Student Registration</h4>
                    ?php endif; ?>
                    <p class="mb-5">Please fill this form and submit to register.</p>
                    <form action="../esas_student/actions/registration_action.php" method="post">
                        <input type="hidden" name="club_id" value="<?php echo htmlspecialchars($club_id); ?>">

                        <!-- Question 1 --
                        <div class="form-group">
                            <label for="question1">Why do you want to join this club?</label>
                            <textarea id="question1" name="question1" class="form-control" rows="4" required></textarea>
                            <span class="text-danger"><?php echo $question1_err ?? ''; ?></span>
                        </div>

                        <!-- Question 2 --
                        <div class="form-group">
                            <label for="question2">What skills or experiences do you have that will contribute to the club's activities?</label>
                            <textarea id="question2" name="question2" class="form-control" rows="4" required></textarea>
                            <span class="text-danger"><?php echo $question2_err ?? ''; ?></span>
                        </div>

                        <!-- Question 3 --
                        <div class="form-group">
                            <label for="question3">How do you plan to balance your time between club activities and your academic responsibilities?</label>
                            <textarea id="question3" name="question3" class="form-control" rows="4" required></textarea>
                            <span class="text-danger"><?php echo $question3_err ?? ''; ?></span>
                        </div>
                        
                        <input type="hidden" name="club_id" value="?php echo htmlspecialchars($club_id); ?>">

                        <!-- Submit and Cancel Buttons --
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="#" onclick="window.history.back();" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div> -->


</body>

<!-- <footer class="navbar-darkblue text-white mt-1 p-4 text-center">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h5>Contact Us</h5>
                <ul class="list-unstyled">
                    <li>Email: sas@nbsc.edu.ph</li>
                    <li>Phone: 0927 669 0090</li>
                </ul>
            </div>
            <div class="col-md-4">
                <h5>Follow Us</h5>
                <ul class="list-unstyled">
                    <li><a href="https://www.facebook.com/nbscstudentaffairsandservices" class="text-white"><i class="fa fa-facebook-square"></i> Facebook</a></li>
                    <li><a href="#" class="text-white"><i class="fa fa-twitter-square"></i>Twitter</a></li>
                    <li><a href="#" class="text-white"><i class="fa fa-instagram"></i>Instagram</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h5>Quick Links</h5>
                <ul class="list-unstyled">
                    <li><a href="http://nbsc.edu.ph" class="text-white">NBSC Website</a></li>
                    <li><a href="https://nbsc.edu.ph/student-affairs-services/" class="text-white">SAS Website</a></li>
                    <li><a href="#" class="text-white">About Us</a></li>
                    <li><a href="#" class="text-white">Privacy Policy</a></li>
                    <li><a href="#" class="text-white">Terms of Service</a></li>
                </ul>
            </div>
        </div>
        <hr>
        <p class="mb-0">© 2024 Student Organization Club Membership and Information System. All rights reserved.</p>
    </div>
</footer> -->

</html>
