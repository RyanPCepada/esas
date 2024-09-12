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
                        <a href="#" class="nav-link left-sidebar text-dark" id="all-clubs">
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
            <div class="col-12 col-md-10 bg-lgrey">
                <div class="row g-0 h-100">
                    <div id="divpr_requesdetails" class="table-responsive px-0 auto-scroll">
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

            // Function to set the active link and blue background
            function setActiveMenu(activeLink) {
                $('.nav-link').removeClass('active').css('background-color', ''); // Remove previous active states
                $(activeLink).addClass('active').css('background-color', '#007bff'); // Set active and blue background
            }

            // Function to set default active link and blue background for "All Clubs"
            function setDefaultActive() {
                const defaultLink = $('#all-clubs');
                setActiveMenu(defaultLink); // Ensure "All Clubs" is blue
            }

            // Load the stored page or default to "All Clubs"
            function loadStoredPage() {
                const storedPage = localStorage.getItem('activePage') || 'all_clubs.php'; // Default to "All Clubs"
                loadPage(storedPage);

                // Determine which link should be active based on the stored page
                const linkId = storedPage.split('.')[0]; // Derive link ID from page name
                const activeLink = $('#' + linkId);

                // Set the appropriate button as active
                setActiveMenu(activeLink);
            }

            // Event listeners for menu items
            $('#my-clubs').on('click', function(e) {
                e.preventDefault();
                const page = 'my_clubs.php';
                loadPage(page);
                localStorage.setItem('activePage', page); // Store the page
                setActiveMenu(this); // Set the clicked button as active
            });

            $('#all-clubs').on('click', function(e) {
                e.preventDefault();
                const page = 'all_clubs.php';
                loadPage(page);
                localStorage.setItem('activePage', page); // Store the page
                setActiveMenu(this); // Set the clicked button as active
            });

            $('#club-requests').on('click', function(e) {
                e.preventDefault();
                const page = 'club_requests.php';
                loadPage(page);
                localStorage.setItem('activePage', page); // Store the page
                setActiveMenu(this); // Set the clicked button as active
            });

            // Initialize the page with the stored or default page
            loadStoredPage();
            
            // Ensure "All Clubs" has the default blue background if no page is stored
            if (!localStorage.getItem('activePage')) {
                setDefaultActive();
            }
        });
    </script>





    <script>
        $(document).ready(function() {
            $('.delprreq').click(function(e) {
                e.stopPropagation();
            });
            // let value= $("classname").val()
        });
    </script>



</body>
</html>