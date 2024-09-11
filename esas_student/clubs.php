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
    <title>Sample Template</title>
    <link href="../assets/css/jquery.dataTables.min.css" rel="stylesheet" />
    <script src="../assets/js/all.js" crossorigin="anonymous"></script>
    <script src="../assets/js/jquery-3.6.0.js"></script>
    <link href="../assets/css/styles.css" rel="stylesheet" />
    <link href="../assets/img/nbsclogo.png" rel="icon">
    <style>
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
        .tab-content {
        }
        .card-img-only {
            position: relative;
            /* width: 230px;
            height: 130px; */
            width: 220px;
            height: 124px;
            border: solid 2px transparent;
            border-radius: 10px;
            overflow: hidden;
            /* box-shadow: 0 5px 10px rgba(0, 0, 0, .5); */
            margin-left: 7px;
            margin-top: 10px;
            margin-bottom: 10px;
            display: flex; /* Flexbox added */
            justify-content: center; /* Horizontally center the image */
            align-items: center; /* Vertically center the image */
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card-img-only:hover {
            transform: scale(1.03);
            border: solid 3px white;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.6);
        }

        .card-img-only img {
            max-width: 100%; /* Image can scale down to fit container width */
            max-height: 100%; /* Image can scale down to fit container height */
            display: block;
            margin: auto; /* Center the image within the container */
            background: linear-gradient(to top right, rgba(0,0,0,0.6), rgba(255,255,255,0.2));
        }
        
        .card-img-only-all {
            position: relative;
            width: auto;
            height: auto;
            border: solid 2px transparent;
            border-radius: 10px;
            overflow: hidden;
            /* box-shadow: 0 5px 10px rgba(0, 0, 0, .5); */
            display: flex;
            justify-content: center;
            align-items: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card-img-only-all:hover {
            transform: scale(1.03);
            border: solid 3px lightblue;
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
            margin: 0;
            font-size: 20px;
        }
        .overlay-text p {
            margin: 5px 0 0;
            font-size: 14px;
        }

    </style>
</head>
<body>
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
    <div class="col-2 ps-0 pt-3 pl-3 border-end">

      <div class="d-flex flex-column flex-shrink-0 p-3 bg-body-tertiary">
        <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none text-dark">
          <svg class="bi pe-none me-2" width="40" height="32"><use xlink:href="#bootstrap"></use></svg>
          <span class="fs-4"><h1><i class="fa fa-university text-primary"></i></h1></span>
        </a>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
          <li>
            <a href="#" class="nav-link text-dark" id="all-clubs">
              <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#speedometer2"></use></svg>
              All Clubs
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link text-dark" aria-current="page" id="my-clubs">
              <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#home"></use></svg>
              My Clubs
            </a>
          </li>
          <li>
            <a href="#" class="nav-link text-dark" id="club-requests">
              <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#table"></use></svg>
              My Club Requests
            </a>
          </li>
        </ul>

        <hr>
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
        </div>
      </div>

    </div>
    <!-- LEFT SIDEBAR END -->

    <div class="col-10 bg-lgrey">
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

    // Function to handle active class and background color
    function updateActiveMenu(activeLink) {
        // Remove active class and background color from all links
        $('.nav-link').removeClass('active');
        $('.nav-link').css('background-color', ''); // Reset background color

        // Add active class and primary blue background color to the clicked link
        $(activeLink).addClass('active');
        $(activeLink).css('background-color', '#007bff'); // Set primary blue background color for active link
    }

    // Load the page based on stored page URL
    function loadStoredPage() {
        const storedPage = localStorage.getItem('activePage');
        if (storedPage) {
            loadPage(storedPage);
            // Set the appropriate menu item as active
            $('#' + storedPage.replace('.php', '')).addClass('active').css('background-color', '#007bff');
        } else {
            loadPage('all_clubs.php'); // Default page
            $('#all-clubs').addClass('active').css('background-color', '#007bff');
        }
    }

    // Event listeners for each menu item
    $('#my-clubs').on('click', function(e) {
        e.preventDefault();
        const page = 'my_clubs.php';
        loadPage(page);
        localStorage.setItem('activePage', page); // Store the active page
        updateActiveMenu(this);
    });

    $('#all-clubs').on('click', function(e) {
        e.preventDefault();
        const page = 'all_clubs.php';
        loadPage(page);
        localStorage.setItem('activePage', page); // Store the active page
        updateActiveMenu(this);
    });

    $('#club-requests').on('click', function(e) {
        e.preventDefault();
        const page = 'club_requests.php';
        loadPage(page);
        localStorage.setItem('activePage', page); // Store the active page
        updateActiveMenu(this);
    });

    // Initialize the page load
    loadStoredPage();
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