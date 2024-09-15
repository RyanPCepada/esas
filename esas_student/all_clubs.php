<?php
session_start();
require_once "../config.php";

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
    // Fetch SBO-CCS Officers
    $sboCCSStmt = $pdo->prepare("SELECT firstName, middleName, lastName, position, profilePic FROM tbl_officers WHERE type = 'SBO' AND department = 'CCS'");
    $sboCCSStmt->execute();
    $sboCCSOfficers = $sboCCSStmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch SBO-TEP Officers
    $sboTEPStmt = $pdo->prepare("SELECT firstName, middleName, lastName, position, profilePic FROM tbl_officers WHERE type = 'SBO' AND department = 'TEP'");
    $sboTEPStmt->execute();
    $sboTEPOfficers = $sboTEPStmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch SBO-BSBA Officers
    $sboBSBAStmt = $pdo->prepare("SELECT firstName, middleName, lastName, position, profilePic FROM tbl_officers WHERE type = 'SBO' AND department = 'BSBA'");
    $sboBSBAStmt->execute();
    $sboBSBAOfficers = $sboBSBAStmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch CSG Officers
    $csgStmt = $pdo->prepare("SELECT firstName, middleName, lastName, position, profilePic FROM tbl_officers WHERE type = 'CSG'");
    $csgStmt->execute();
    $csgOfficers = $csgStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
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
    <title>Sample Template</title>
    <link href="../assets/css/jquery.dataTables.min.css" rel="stylesheet" />
    <script src="../assets/js/all.js" crossorigin="anonymous"></script>
    <script src="../assets/js/jquery-3.6.0.js"></script>
    <link href="../assets/css/styles.css" rel="stylesheet" />
    <link href="../assets/img/nbsclogo.png" rel="icon">
    <style>
        .left-sidebar {
            font-size: 16px;
            text-align: start;
        }
        /* .nav-link:hover {
          background-color: #cce4ff !important;
        } */

        .nav-link.active {
          color: white !important;
          background-color: black;
        }
        .mainbar h2 {
            margin-left: 15px;
            margin-bottom: 32px;
        }
        .card-body {
            width: 100%;
            /* max-width: 600px; */
            margin: 0 auto;
            padding: 50px;
        }
        
        .card-img-only-all {
            position: relative;
            width: 295px;
            height: 166px;
            /* width: 330px;
            height: 188px; */
            border: solid 3px transparent;
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 10px rgba(0, 0, 0, .5);
            margin: 10px auto;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card-img-only-all:hover {
            transform: scale(1.01);
            border: solid 3px transparent;
            border: none;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.6);
        }

        .card-img-only-all img {
            max-width: 100%; /* Image can scale down to fit container width */
            max-height: 100%; /* Image can scale down to fit container height */
            display: block;
            margin: auto; /* Center the image within the container */
            background: linear-gradient(to top right, rgba(0,0,0,0.6), rgba(255,255,255,0.2));
        }

        .card small {
            position: absolute;
            top: 5px;
            right: 5px;
            color: white;
            padding: 8px;
            font-size: 14px;
            cursor: pointer;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.6); /* Shadow effect */
        }
        .overlay-text {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 10px;
            background: linear-gradient(to top, rgba(0, 0, 0, .7), rgba(0, 0, 0, 0));
            color: white;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.6); /* Shadow effect */
            text-align: left;
            line-height: 1.2; /* Adjust line height for closer spacing */
        }
        .overlay-text h4 {
            margin: 7px;
            font-size: 25px;
            line-height: 1.1;
        }
        .overlay-text p {
            margin: 5px 0 0;
            font-size: 14px;
        }


        @media (max-width: 768px) {
            
            .mainbar {
                padding: 16px;
            }
            .mainbar h2 {
                margin-bottom: 16px;
                margin-left: 0px;
            }
            .card-body {
                padding: 10px !important; 
                max-width: 100%; 
            }
            .card-img-only-all {
                margin: 10px auto;
            }
        }






        .card-container {
            opacity: 0;
            transform: translateY(20px); /* Start from below */
            animation: slideIn 0.6s forwards; /* Apply animation */
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(20px); /* Start from below */
            }
            to {
                opacity: 1;
                transform: translateY(0); /* End at normal position */
            }
        }

        /* Optional: Adjust the delay for each card */
        .card-container:nth-child(1) {
            animation-delay: 0s;
        }
        .card-container:nth-child(2) {
            animation-delay: 0.1s;
        }
        .card-container:nth-child(3) {
            animation-delay: 0.2s;
        }





        
        
        .card-body { 
            width: 100%;
            /* max-width: 600px; */
            margin: 0 auto;
            padding: 50px;
        }

        /* Default for larger screens (6 cards per row) */
        .sbo-officers-row, .csg-officers-row {
            flex: 0 0 16.66%; /* 100% / 6 = 16.66% width per card */
            max-width: 16.66%;
        }

        /* Media query for mobile screens (2 cards per row) */
        @media (max-width: 768px) {
            .sbo-officers-row, .csg-officers-row {
                flex: 0 0 50%; /* 100% / 2 = 50% width per card */
                max-width: 50%;
            }
        }

    </style>
</head>
<body>
    
    <div class="container-fluid">
        <div class="row g-0 h-100">
            <nav class="navbar navbar-expand-lg navbar-dark bg-primary px-2">
                <a class="navbar-brand ps-2" href="#">
                    <img src="../assets/img/nbsclogo.png" style="height: 0.3in;">
                    NBSC SIS</a>
                </button>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#main_nav" aria-expanded="true">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="navbar-collapse collapse hide" id="main_nav">
                    <div class="navbar-collapse flex-grow-1 text-right" id="sampleid" style="padding-left: 20px">
                        <?php include 'nav/nav_main.php' ?>
                    </div>
                </div>
            </nav>
            
            <!-- LEFT SIDEBAR -->
            <div class="col-12 col-md-2 ps-0 pt-3 border-end">

                <div class="d-flex flex-column flex-shrink-0 p-3 bg-body-tertiary">
                    <ul class="nav nav-pills flex-column mb-auto">
                        <li>
                            <a href="../esas_student/all_clubs.php" class="nav-link left-sidebar text-dark active" id="all-clubs">
                                <i class="fas fa-university"></i> All Clubs
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../esas_student/my_clubs.php" class="nav-link left-sidebar text-dark" aria-current="page" id="my-clubs">
                                <i class="fas fa-user"></i> My Clubs
                            </a>
                        </li>
                        <li>
                            <a href="../esas_student/club_requests.php" class="nav-link left-sidebar text-dark" id="club-requests">
                                <i class="fas fa-file-alt"></i> My Club Requests
                            </a>
                        </li>
                    </ul>
                </div>

            </div>
            <!-- LEFT SIDEBAR END -->

            
            <!-- MAINPAGE BAR -->
            <div class="col-12 col-md-10 bg-lgrey auto-scroll">
                <div class="row g-0 h-100">

                    <div class="officers-div pt-2">
                        <div class="row g-0 p-1 px-2 pt-1">
                            <h5 class="ms-2">CSG Officers</h5>
                            <?php foreach ($csgOfficers as $officer): ?>
                                <div class="csg-officers-row col-md-2 p-1 text-center align-items-center justify-content-center">
                                    <div class="card card-csg-officer d-flex flex-row align-items-center p-2" style="width: auto; height: 70px; background-color: white; box-shadow: 0 5px 10px rgba(0, 0, 0, .3);">
                                        <!-- Profile Picture -->
                                        <div class="profile-pic" style="margin-right: 10px;">
                                            <img src="/esas/esas_admin/images/<?php echo $officer['profilePic']; ?>" alt="Profile Pic" style="width: 50px; height: 50px; border-radius: 50%;">
                                        </div>
                                        <!-- Officer Details (Name and Position) -->
                                        <div class="officer-details text-left" style="line-height: 1.2; max-width: 80px;">
                                            <h6 style="font-size: 12px; margin-bottom: 2px;"><?php echo $officer['firstName'] . ' ' . $officer['lastName']; ?></h6>
                                            <p style="font-size: 10px; margin: 0;"><?php echo $officer['position']; ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="mt-2"></div>

                        <div class="row g-0 p-1 px-2 pt-1">
                            <h5 class="ms-2">SBO Officers</h5>
                            <?php foreach ($sboCCSOfficers as $CCSofficer): ?>
                                <div class="sbo-officers-row col-md-2 p-1 text-center align-items-center justify-content-center">
                                    <div class="card card-sbo-officer d-flex flex-row align-items-center p-2" style="width: auto; height: 70px; background-color: #A6E22E; background-color: white; box-shadow: 0 5px 10px rgba(0, 0, 0, .3);">
                                        <!-- Profile Picture -->
                                        <div class="profile-pic" style="margin-right: 10px;">
                                            <img src="/esas/esas_admin/images/<?php echo $CCSofficer['profilePic']; ?>" alt="Profile Pic" style="width: 50px; height: 50px; border-radius: 50%;">
                                        </div>
                                        <!-- Officer Details (Name and Position) -->
                                        <div class="officer-details text-left" style="line-height: 1.2; max-width: 80px;">
                                            <h6 style="font-size: 12px; margin-bottom: 2px;"><?php echo $CCSofficer['firstName'] . ' ' . $CCSofficer['lastName']; ?></h6>
                                            <p style="font-size: 10px; margin: 0;"><?php echo $CCSofficer['position']; ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            <?php foreach ($sboTEPOfficers as $TEPofficer): ?>
                                <div class="sbo-officers-row col-md-2 p-1 text-center align-items-center justify-content-center">
                                    <div class="card card-sbo-officer d-flex flex-row align-items-center p-2" style="width: auto; height: 70px; background-color: #6A8CCF; background-color: white; box-shadow: 0 5px 10px rgba(0, 0, 0, .3);">
                                        <!-- Profile Picture -->
                                        <div class="profile-pic" style="margin-right: 10px;">
                                            <img src="/esas/esas_admin/images/<?php echo $TEPofficer['profilePic']; ?>" alt="Profile Pic" style="width: 50px; height: 50px; border-radius: 50%;">
                                        </div>
                                        <!-- Officer Details (Name and Position) -->
                                        <div class="officer-details text-left" style="line-height: 1.2; max-width: 80px;">
                                            <h6 style="font-size: 12px; margin-bottom: 2px;"><?php echo $TEPofficer['firstName'] . ' ' . $TEPofficer['lastName']; ?></h6>
                                            <p style="font-size: 10px; margin: 0;"><?php echo $TEPofficer['position']; ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            <?php foreach ($sboBSBAOfficers as $BSBAofficer): ?>
                                <div class="sbo-officers-row col-md-2 p-1 text-center align-items-center justify-content-center">
                                    <div class="card card-sbo-officer d-flex flex-row align-items-center p-2" style="width: auto; height: 70px; background-color: #FFF176; background-color: white; box-shadow: 0 5px 10px rgba(0, 0, 0, .3);">
                                        <!-- Profile Picture -->
                                        <div class="profile-pic" style="margin-right: 10px;">
                                            <img src="/esas/esas_admin/images/<?php echo $BSBAofficer['profilePic']; ?>" alt="Profile Pic" style="width: 50px; height: 50px; border-radius: 50%;">
                                        </div>
                                        <!-- Officer Details (Name and Position) -->
                                        <div class="officer-details text-left" style="line-height: 1.2; max-width: 80px;">
                                            <h6 style="font-size: 12px; margin-bottom: 2px;"><?php echo $BSBAofficer['firstName'] . ' ' . $BSBAofficer['lastName']; ?></h6>
                                            <p style="font-size: 10px; margin: 0;"><?php echo $BSBAofficer['position']; ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    
                    <div id="divpr_requesdetails" class="table-responsive px-0">
                        <div class="row g-0 p-4 px-2 pt-2 h-100">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row mainbar g-0 h-100">
                                        <h2 class="text-muted mt-0">Student Club Organizations</h2>
                                        <div class="row g-2 mt-0" id="allClubsContainer">
                                            <!-- Club cards will be dynamically added here -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
            <!-- MAINPAGE BAR END -->

        </div>
    </div>

    <!-- <?php include 'assets/components/modals.php' ?> -->
    <script src="../assets/js/jquery.dataTables.min.js"></script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/global_script.js"></script>



    <script>
        function submitClubRequest() {
            document.getElementById('clubRequestForm').submit();
        }

        function updateLabel(label) {
            document.getElementById("tabLabel").innerText = label;
        }

        // Fetch clubs data from API
        fetch('/esas/esas_student/apis/clubs-api.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // $('#post-26').html(data)
                const allClubsContainer = document.getElementById('allClubsContainer');
                if (data && data.length > 0) {
                    data.forEach(club => {
                        const memberText = club.membersCount === 1 ? '1 member' : `${club.membersCount} members`;
                        const cardHTML = `
                            <div class="col-md-4 card-container">
                                <div class="card card-img-only-all">
                                    <small data-toggle="tooltip" title="${memberText}">
                                        <i class="fa fa-user mr-1"></i>${club.membersCount}
                                    </small>
                                    <a href="/esas/esas_student/club_info.php?club_id=${club.club_id}&club_name=${encodeURIComponent(club.clubName)}">
                                        <img src="/esas/esas_admin/images/${club.coverPhoto}" alt="Cover Photo">
                                        <div class="overlay-text">
                                            <h4>${club.clubName}</h4>
                                            <!--<div class="moderators-container">
                                                ${club.formattedModerators}
                                            </div>-->
                                        </div>
                                    </a>
                                </div>
                            </div>
                        `;
                        allClubsContainer.innerHTML += cardHTML;
                    });
                } else {
                    allClubsContainer.innerHTML = '<p>No clubs found.</p>';
                }
            })
            .catch(error => {
                console.error('Error fetching clubs:', error);
                const allClubsContainer = document.getElementById('allClubsContainer');
                allClubsContainer.innerHTML = '<p>Failed to fetch clubs. Please try again later.</p>';
            });


    </script>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Cache the elements
        const allClubsLink = document.getElementById('all-clubs');
        const myClubsLink = document.getElementById('my-clubs');
        const clubRequestsLink = document.getElementById('club-requests');
        const officersDiv = document.querySelector('.officers-div');
        const csgCards = document.querySelectorAll('.card-csg-officer'); // CSG officer cards
        const sboCards = document.querySelectorAll('.card-sbo-officer'); // SBO officer cards

        function animateCards(cards) {
            // Apply the animation waveIn dynamically for a group of cards
            cards.forEach((card, index) => {
                // Reset styles
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px) scale(0.95)';
                card.style.transition = 'none'; // Disable transition for reset

                // Trigger a reflow to apply reset styles
                void card.offsetWidth;

                // Re-enable transitions
                card.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';

                // Apply animation with a delay (wave effect)
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0) scale(1)';
                    card.style.animation = `waveIn 0.6s ease-out forwards`;
                }, index * 100); // Delay per card to create the wave effect
            });
        }

        function updateVisibility() {
            if (allClubsLink.classList.contains('active')) {
                officersDiv.style.display = 'block'; // Show officers div

                // Trigger animations for CSG and SBO cards at the same time but separately
                animateCards(csgCards);  // Animate CSG officers
                animateCards(sboCards);  // Animate SBO officers
            } else {
                officersDiv.style.display = 'none'; // Hide officers div
            }
        }

        // Add keyframes dynamically
        const styleSheet = document.createElement('style');
        styleSheet.type = 'text/css';
        styleSheet.innerHTML = `
            @keyframes waveIn {
                0% {
                    opacity: 0;
                    transform: translateY(20px) scale(0.95);
                }
                50% {
                    opacity: 0.5;
                    transform: translateY(-10px) scale(1.05); /* Peak of the wave */
                }
                100% {
                    opacity: 1;
                    transform: translateY(0) scale(1);
                }
            }
        `;
        document.head.appendChild(styleSheet);

        // Initial visibility setup
        updateVisibility();
    });


    $(document).ready(function() {
        $('.delprreq').click(function(e) {
            e.stopPropagation();
        });
        // let value= $("classname").val()
    });
</script>

</body>
</html>