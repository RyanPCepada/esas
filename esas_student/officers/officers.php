<?php
session_start();
require_once "../../config.php";

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

$student_id = $_SESSION['student_id'];

try {
    // Use the existing PDO instance from config.php 
    global $pdo;

    // Prepare and execute the SQL statement
    $sql = "SELECT firstName, middleName, lastName FROM tbl_students WHERE student_id = :student_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
    $stmt->execute();
    
    // Fetch the result
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if a result was found
    if ($result) {
        $firstName = strtoupper($result['firstName']);
        $middleName = strtoupper($result['middleName']);
        $lastName = strtoupper($result['lastName']);
    } else {
        // Handle the case where no data is found
        $firstName = $middleName = $lastName = "UNKNOWN";
    }

} catch (PDOException $e) {
    // Handle database connection or query error
    die("Database error: " . $e->getMessage());
}

try {
    // Use the existing PDO instance from config.php 
    global $pdo;

    // Prepare and execute the SQL statement to fetch officer charts
    $sql = "SELECT chart_id, chart, organizationType, department FROM tbl_officers_charts";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    
    // Fetch all results
    $officerCharts = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Handle database connection or query error
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>ESAS - Officers</title>
    <link href="../../assets/css/jquery.dataTables.min.css" rel="stylesheet" />
    <script src="../../assets/js/all.js" crossorigin="anonymous"></script>
    <script src="../../assets/js/jquery-3.6.0.js"></script>
    <link href="../../assets/css/styles.css" rel="stylesheet" />
    <link href="../../assets/img/NBSC_LOGO.png" rel="icon">
    <style>
        /* body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f4f4;
        } */
        /* Header design */
        .header {
            background-color: #004d80;
            color: white;
            text-align: left;
            padding: 20px 40px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
        .header h1 {
            margin: 0;
            font-size: 2em;
        }

        /* Navigation Bar */
        .nav-bar {
            background-color: #2980b9;
            overflow: hidden;
            display: flex;
            justify-content: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
        .nav-bar button {
            background-color: #2980b9;
            border: none;
            color: white;
            padding: 14px 20px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .nav-bar button:hover {
            background-color: #3498db;
        }

        /* Mission and Vision Section */
        .mission-vision {
            background-color: #3498db;
            padding: 40px 20px;
            text-align: center;
            color: white;
        }
        .mission-vision h2 {
            margin-top: 0;
            font-size: 2.5em;
        }
        .mission-vision p {
            font-size: 1.5em;
            line-height: 1.6;
            margin: 20px auto;
            max-width: 800px;
        }

        /* Officer Section */
        .csg-officer-section, .sbo-officer-section {
            padding: 20px 10px;
            text-align: center;
        }
        .officer-section h2 {
            font-size: 1.8em;
            margin-bottom: 20px;
        }
        .officer-row {
            margin: 0 auto;
            max-width: 900px;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px 0;
            background-color: #ffffff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s;
        }
        .officer-row:nth-child(even) {
            background-color: #f7f7f7;
        }
        .officer-row img {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 20px 0;
        }
        .officer-label {
            font-size: 40px;
            color: #004d80;
            font-weight: bold;
            margin-bottom: 10px;
        }

        /* Ensure responsiveness */
        @media screen and (max-width: 768px) {
            .officer-row {
                padding: 20px 10px;
            }
            .mission-vision h2 {
                font-size: 1.8em;
            }
            .mission-vision p {
                font-size: 1em;
            }
            .nav-bar button {
                padding: 10px;
                font-size: 14px;
            }
            .parallax1, .parallax2 {
                height: 50vw !important;
                background-image: url('../images/NBSC_BLDG_FINAL_NO_WIRES_JPG.jpg') !important;
                background-position: 52.5% !important;
            }
            .parallax1 h1, .parallax2 h1 {
                font-size: 45px !important;
            }
            .officer-label {
                font-size: 30px;
            }
        }


        
        .parallax1 {
        height: 40vw !important;
        background-image: url('../images/COVERPHOTO_DEFAULT.png');
        background-position: 50%;
        background-repeat: no-repeat;
        background-size: cover;
        background-attachment: fixed;
        color: white;
        display: flex;
        align-items: center;
        }
        .parallax2 {
        height: 40vw;
        background-image: url('../images/COVERPHOTO_DEFAULT.png');
        background-position: 50%;
        background-repeat: no-repeat;
        background-size: cover;
        background-attachment: fixed;
        color: white;
        display: flex;
        align-items: center;
        }
        .parallax1 h1, .parallax2 h1 {
            font-size: 100px;
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.7); /* Adjust the values as needed */
        }


    </style>
</head>
<body>
    
    <div class="container-fluid">
        <div class="row g-0 h-100">
            <nav class="navbar navbar-expand-lg navbar-dark bg-primary px-2">
                <a class="navbar-brand ps-2" href="#">
                    <img src="../../assets/img/SAS_LOGO.png" alt="ESAS Logo" style="height: .4in; vertical-align: middle;">
                    <!-- <img src="../../assets/img/NBSC_LOGO.png" style="height: 0.3in;"> NBSC SIS -->
                    </a>
                </button>
                <!-- <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#main_nav" aria-expanded="true">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="navbar-collapse collapse hide" id="main_nav">
                    <div class="navbar-collapse flex-grow-1 text-right" id="sampleid" style="padding-left: 20px">
                        <php include '../nav/nav_main.php' ?>
                    </div>
                </div> -->
            </nav>

            <!-- Header -->
            <!-- <div class="header">
                <h1>
                    <img src="../../assets/img/SAS_LOGO.png" alt="ESAS Logo" style="height: 1in; vertical-align: middle;">
                    ESAS
                </h1>
            </div> -->

            <!-- Navigation Bar -->
            <div class="nav-bar">
                <!-- <button onclick="showSection('mission-vision')"><!-- Mission & Vision --</button>
                <button onclick="showSection('csg')"><!-- CSG Officers --</button>
                <button onclick="showSection('sbo')"><!-- SBO Officers --</button> -->

                <h5 class="text-light p-3"><em>College Student Government and Student Body Organization Officers</em></h5>
            </div>

            <!-- Mission and Vision Section -->
            <div class="mission-vision">
                <h2>Vision</h2>
                <p>Northern Bukidnon State College will be a college of choice, nationally recognized for having innovative and sustainable academic programs, research, extensions, and services that cultivate educational, personal, and professional growth to meet the needs of our students, our society, and the global community.</p>
                <h2>Mission</h2>
                <p>Northern Bukidnon State College is an accessible institution of higher education that provides quality educational opportunities to develop students into socially responsible, competent, and productive professionals.</p>
            </div>


            <!-- PARRALAX 1 -->
            <div class="container-fluid">
                <div class="parallax1 text-center d-flex align-items-center justify-content-center">
                    <h1><small class="text-warning">The</small><em>CSG OFFICERS</em></h1>
                </div>
            </div>
            <!-- END PARRALAX 1 -->

            <!-- CSG Officer Sections -->
            <div class="csg-officer-section">
                <div class="officer-row">
                    <?php
                    // Display CSG Officers Chart
                    foreach ($officerCharts as $chart) {
                        if (strtoupper($chart['organizationType']) === 'CSG') {
                            echo '<img src="/esas/esas_admin/images/' . htmlspecialchars($chart['chart']) . '" alt="CSG Officers">';
                        }
                    }
                    ?>
                </div>
            </div>

            <!-- PARRALAX 2 -->
            <div class="container-fluid">
                <div class="parallax2 text-center d-flex align-items-center justify-content-center">
                    <h1><small class="text-warning">The</small><em>SBO OFFICERS</em></h1>
                </div>
            </div>
            <!-- END PARRALAX 2 -->

            <!-- SBO Officer Sections -->
            <div class="sbo-officer-section">
                <?php
                // Display SBO Officers Charts
                foreach ($officerCharts as $chart) {
                    if (strtoupper($chart['organizationType']) === 'SBO') {
                        echo '<div class="officer-row">';
                        echo '<div class="officer-label">' . htmlspecialchars($chart['department']) . '</div>';
                        echo '<img src="/esas/esas_admin/images/' . htmlspecialchars($chart['chart']) . '" alt="' . htmlspecialchars($chart['department']) . ' SBO Officers">';
                        echo '</div>';
                    }
                }
                ?>
            </div>


        </div>
    </div>

    <!-- <?php include 'assets/components/modals.php' ?> -->
    <script src="../assets/js/jquery.dataTables.min.js"></script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/global_script.js"></script>
</body>
</html>

<footer style="background-color: #004d80; color: white; padding: 15px 10px; text-align: center; font-size: 0.9em;">
    <div class="container" style="max-width: 1200px; margin: 0 auto;">
        <div class="row" style="display: flex; justify-content: space-between;">
            <div class="col-md-4" style="flex: 1; margin-bottom: 10px;">
                <h5 style="margin-bottom: 10px; font-size: 1.2em;">Contact Us</h5>
                <ul class="list-unstyled" style="list-style-type: none; padding: 0;">
                    <li>Email: <a href="mailto:sas@nbsc.edu.ph" style="color: #f1c40f; text-decoration: underline;">sas@nbsc.edu.ph</a></li>
                    <li>Phone: <a href="tel:+639276690090" style="color: #f1c40f; text-decoration: underline;">0927 669 0090</a></li>
                </ul>
            </div>
            <div class="col-md-4" style="flex: 1; margin-bottom: 10px;">
                <h5 style="margin-bottom: 10px; font-size: 1.2em;">Follow Us</h5>
                <ul class="list-unstyled" style="list-style-type: none; padding: 0;">
                    <li><a href="https://www.facebook.com/nbscstudentaffairsandservices" style="color: #f1c40f; text-decoration: underline;"><i class="fa fa-facebook-square"></i> Facebook</a></li>
                    <li><a href="#" style="color: #f1c40f; text-decoration: underline;"><i class="fa fa-twitter-square"></i> Twitter</a></li>
                    <li><a href="#" style="color: #f1c40f; text-decoration: underline;"><i class="fa fa-instagram"></i> Instagram</a></li>
                </ul>
            </div>
            <div class="col-md-4" style="flex: 1; margin-bottom: 10px;">
                <h5 style="margin-bottom: 10px; font-size: 1.2em;">Quick Links</h5>
                <ul class="list-unstyled" style="list-style-type: none; padding: 0;">
                    <li><a href="http://nbsc.edu.ph" style="color: #f1c40f; text-decoration: underline;">NBSC Website</a></li>
                    <li><a href="https://nbsc.edu.ph/student-affairs-services/" style="color: #f1c40f; text-decoration: underline;">SAS Website</a></li>
                    <!-- <li><a href="#" style="color: #f1c40f; text-decoration: underline;">About Us</a></li>
                    <li><a href="#" style="color: #f1c40f; text-decoration: underline;">Privacy Policy</a></li>
                    <li><a href="#" style="color: #f1c40f; text-decoration: underline;">Terms of Service</a></li> -->
                </ul>
            </div>
        </div>
        <hr style="border-color: rgba(255, 255, 255, 0.2);">
        <p class="mb-0" style="font-size: 1em;">©ESAS2024. All rights reserved.</p>
    </div>
</footer>