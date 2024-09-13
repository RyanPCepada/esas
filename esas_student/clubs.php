<?php
session_start();
require_once "../config.php";

// Fetch the current student's ID from the session
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
    // Fetch SBO Officers
    $sboStmt = $pdo->prepare("SELECT firstName, middleName, lastName, position, profilePic FROM tbl_officers WHERE type = 'SBO'");
    $sboStmt->execute();
    $sboOfficers = $sboStmt->fetchAll(PDO::FETCH_ASSOC);

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
    <title>NBSC - eSAS Student Club Organizations</title>
    <link href="../assets/css/jquery.dataTables.min.css" rel="stylesheet" />
    <script src="../assets/js/all.js" crossorigin="anonymous"></script>
    <script src="../assets/js/jquery-3.6.0.js"></script>
    <link href="../assets/css/styles.css" rel="stylesheet" />
    <link href="../assets/img/nbsclogo.png" rel="icon">
    <style>
        .left-sidebar {
            font-size: 18px;
            text-align: start;
        }
        /* .nav-link:hover {
          background-color: #cce4ff !important;
        } */

        .nav-link.active {
          color: white !important;
          background-color: #e9ecef;
        }
        
        .card-body {
            width: 100%;
            /* max-width: 600px; */
            margin: 0 auto;
            padding: 50px;
        }
        .csg-officers-row, .sbo-officers-row {
            padding: 2px !important;
        }



        .card-sbo-officer, .card-csg-officer {
            opacity: 0;
            transform: translateY(20px) scale(0.95); /* Start from below and slightly scaled down */
            transition: opacity 0.6s ease-out, transform 0.6s ease-out; /* Smooth transition */
        }

        .card-visible {
            opacity: 1;
            transform: translateY(0) scale(1); /* End at normal position and scale */
        }

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
                    <!-- <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none text-dark">
                    <svg class="bi pe-none me-2" width="40" height="32"><use xlink:href="#bootstrap"></use></svg>
                    <span class="fs-4"><h1><i class="fa fa-university text-primary"></i></h1></span>
                    </a>
                    <hr> -->
                    <ul class="nav nav-pills flex-column mb-auto">
                        <li>
                            <a href="#" class="nav-link left-sidebar text-dark active" id="all-clubs">
                                All Clubs
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link left-sidebar text-dark" aria-current="page" id="my-clubs">
                                My Clubs
                            </a>
                        </li>
                        <li>
                            <a href="#" class="nav-link left-sidebar text-dark" id="club-requests">
                                My Club Requests
                            </a>
                        </li>
                    </ul>

                    <!-- <hr>
                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="https://github.com/mdo.png" alt="" width="32" height="32" class="rounded-circle me-2">
                            <strong>mdo</strong>
                        </a>
                        <ul class="dropdown-menu text-small shadow">
                            <li><a class="dropdown-item" href="#">New project...</a></li>
                            <li><a class="dropdown-item" href="#">Settings</a></li>
                            <li><a class="dropdown-item" href="#">Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#">Sign out</a></li>
                        </ul>
                    </div> -->
                </div>

            </div>
            <!-- LEFT SIDEBAR END -->

            
            <!-- MAINPAGE BAR -->
            <div class="col-12 col-md-10 bg-lgrey auto-scroll">
                <div class="row g-0 h-100">

                    <div class="officers-div pt-3">
                        <div class="row g-0 p-1 px-2 pt-1">
                            <h5>SBO Officers</h5>
                            <?php foreach ($sboOfficers as $officer): ?>
                                <div class="sbo-officers-row col-md-1 text-center align-items-center justify-content-center">
                                    <div class="card card-sbo-officer text-center" style="width: auto; height: auto; margin: auto; background-color: white; box-shadow: 0 5px 10px rgba(0, 0, 0, .3);">
                                        <div class="text-center d-flex align-items-center justify-content-center" style="line-height: 1.1; height: 28px; margin-top: 3px;">
                                            <h7 style="font-size: 12px;"><?php echo $officer['position']; ?></h7>
                                        </div>
                                        <img src="/esas/esas_admin/images/<?php echo $officer['profilePic']; ?>" alt="Profile Pic" style="width: 60px; height: 60px; border-radius: 50%; margin: auto;">
                                        <div class="text-center d-flex align-items-center justify-content-center" style="line-height: 1.1; height: 40px;">
                                            <h7 style="font-size: 12px;"><?php echo $officer['firstName'] . ' ' . $officer['lastName']; ?></h7>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="mt-2"></div>

                        <div class="row g-0 p-1 px-2 pt-1">
                            <h5>CSG Officers</h5>
                            <?php foreach ($csgOfficers as $officer): ?>
                                <div class="csg-officers-row col-md-1 text-center align-items-center justify-content-center">
                                    <div class="card card-csg-officer text-center" style="width: auto; height: auto; margin: auto; background-color: white; box-shadow: 0 5px 10px rgba(0, 0, 0, .3);">
                                        <div class="text-center d-flex align-items-center justify-content-center" style="line-height: 1.1; height: 30px; margin-top: 3px;">
                                            <h7 style="font-size: 12px;"><?php echo $officer['position']; ?></h7>
                                        </div>
                                        <img src="/esas/esas_admin/images/<?php echo $officer['profilePic']; ?>" alt="Profile Pic" style="width: 60px; height: 60px; border-radius: 50%; margin: auto;">
                                        <div class="text-center d-flex align-items-center justify-content-center" style="line-height: 1.1; height: 40px;">
                                            <h7 style="font-size: 12px;"><?php echo $officer['firstName'] . ' ' . $officer['lastName']; ?></h7>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <div id="divpr_requesdetails" class="table-responsive px-0">
                        <div class="row g-0 p-4 px-2 pt-3 h-100">
                            <div class="card">
                                <div class="card-body">
                                    <!-- DISPLAY CLICKED MENU PAGES HERE --> 
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>





    <script>
        $(document).ready(function() {
    // Function to load content based on the clicked menu item
    function loadPage(page) {
        $.ajax({
            url: page, // Page to load
            type: "GET",
            success: function(response) {
                $('.card-body').html(response); // Load the content into the card body
            },
            error: function() {
                $('.card-body').html('<p>Error loading page.</p>');
            }
        });
    }

    // Function to set the active link styling
    function setActiveMenu(activeLink) {
        $('.nav-link').removeClass('active'); // Remove 'active' from all links
        $(activeLink).addClass('active'); // Add 'active' to the clicked link
    }

    // Load "All Clubs" as default page when the page is opened
    function loadDefaultPage() {
        const page = 'all_clubs.php'; // Default to "All Clubs"
        loadPage(page);
        setActiveMenu($('#all-clubs')); // Ensure "All Clubs" is active
    }

    // Event listeners for menu items
    $('#my-clubs').on('click', function(e) {
        e.preventDefault();
        const page = 'my_clubs.php';
        loadPage(page);
        setActiveMenu(this); // Set the clicked button as active
    });

    $('#all-clubs').on('click', function(e) {
        e.preventDefault();
        const page = 'all_clubs.php';
        loadPage(page);
        setActiveMenu(this); // Set the clicked button as active
    });

    $('#club-requests').on('click', function(e) {
        e.preventDefault();
        const page = 'club_requests.php';
        loadPage(page);
        setActiveMenu(this); // Set the clicked button as active
    });

    // Initialize the page with "All Clubs" as the default
    loadDefaultPage();
});

    </script>





    <script>
document.addEventListener('DOMContentLoaded', () => {
    const cards = document.querySelectorAll('.card-sbo-officer, .card-csg-officer');

    cards.forEach((card, index) => {
        setTimeout(() => {
            card.style.animation = `waveIn 0.6s ease-out forwards`;
        }, index * 100); // Adjust the delay as needed (e.g., 100ms per card)
    });
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