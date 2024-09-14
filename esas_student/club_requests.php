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
        .nav-tabs {
            margin-top: -20px !important;
            margin-bottom: 50px;
        }
        .card-body {
            width: 100%;
            /* max-width: 600px; */
            margin: 0 auto;
            padding: 50px;
            padding-top: 35px;
        }
        .tab-content {
        }
        
        .card-img-only {
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
            margin: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card-img-only:hover {
            transform: scale(1.01);
            border: solid 3px transparent;
            border: none;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.6);
        }

        .card-img-only img {
            max-width: 100%; /* Image can scale down to fit container width */
            max-height: 100%; /* Image can scale down to fit container height */
            display: block;
            margin: auto; /* Center the image within the container */
            background: linear-gradient(to top right, rgba(0,0,0,0.6), rgba(255,255,255,0.2));
        }

        .card-img-only img {
            max-width: 100%; /* Image can scale down to fit container width */
            max-height: 100%; /* Image can scale down to fit container height */
            display: block;
            margin: auto; /* Center the image within the container */
            background: linear-gradient(to top right, rgba(0,0,0,0.6), rgba(255,255,255,0.2));
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
        .date {
            margin-left: 12px;
        }


        @media (max-width: 768px) {
            .card-body {
                padding: 10px; 
                max-width: 100%; 
            }
            .card-img-only {
                margin: 10px auto;
            }
            .date {
                margin-left: 20px;
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
    </style>
</head>

<!--HERE-->

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
                            <a href="../esas_student/all_clubs.php" class="nav-link left-sidebar text-dark" id="all-clubs">
                                All Clubs
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../esas_student/my_clubs.php" class="nav-link left-sidebar text-dark" aria-current="page" id="my-clubs">
                                My Clubs
                            </a>
                        </li>
                        <li>
                            <a href="../esas_student/club_requests.php" class="nav-link left-sidebar text-dark active" id="club-requests">
                                My Club Requests
                            </a>
                        </li>
                    </ul>
                </div>

            </div>
            <!-- LEFT SIDEBAR END -->

            
            <!-- MAINPAGE BAR -->
            <div class="col-12 col-md-10 bg-lgrey auto-scroll">
                <div class="row g-0 h-100">
                    <div id="divpr_requesdetails" class="table-responsive px-0">
                        <div class="row g-0 p-4 px-2 pt-3 h-100">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row g-0">
                                        <div class="d-flex justify-content-between align-items-center p-3 mb-5">
                                            <h2 class="text-muted mt-0 mb-0">My Club Requests</h2>
                                            <button type="button" class="btn btn-primary" id="request-club-btn" data-bs-toggle="modal" data-bs-target="#requestClubModal" style="width: 210px; border-radius: 5px;">
                                                Request for a Club
                                            </button>
                                        </div>

                                        <!-- Modal HTML -->
                                        <div class="modal fade" id="requestClubModal" tabindex="-1" aria-labelledby="requestClubModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="requestClubModalLabel">Request for a New Club</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p class="py-2">Please fill out this form and submit your request for a new club.</p>
                                                        <form id="clubRequestForm" action="../esas_student/actions/club_request_action.php" method="POST" enctype="multipart/form-data">
                                                            <div class="form-group mb-3">
                                                                <label for="clubName">Club Name</label>
                                                                <input type="text" name="clubName" class="form-control" id="clubName" required>
                                                            </div>
                                                            <div class="form-group mb-3">
                                                                <label for="description">What is the primary goal of this club?</label>
                                                                <textarea name="description" class="form-control" id="description" rows="3" required></textarea>
                                                            </div>
                                                            <div class="form-group mb-3">
                                                                <label for="activities">Proposed Activities</label>
                                                                <textarea name="activities" class="form-control" id="activities" rows="2"></textarea>
                                                            </div>
                                                            <div class="form-group mb-3">
                                                                <label for="coverPhoto">Club Cover Photo</label>
                                                                <input type="file" name="coverPhoto" class="form-control" id="coverPhoto" required onchange="previewImage(event)">
                                                            </div>
                                                            
                                                            <div class="form-group mb-3">
                                                                <img id="coverPhotoPreview" src="#" alt="Cover Photo Preview" style="display:none; width: 50%; object-fit: cover;" />
                                                            </div>
                                                            <div class="d-flex justify-content-end">
                                                                <a href="javascript:history.go(0)" class="btn btn-secondary me-2">Cancel</a>
                                                                <button type="submit" class="btn btn-primary">Submit</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <nav>
                                            <div class="nav nav-tabs n" role="tablist">
                                                <button title="Requested Clubs" class="ms-2 px-2 nav-link active" id="nav-requestedclubs-tab" data-bs-toggle="tab" data-bs-target="#nav-requestedclubs" type="button" role="tab" aria-controls="nav-requestedclubs" aria-selected="true" onclick="updateLabel('Requested Clubs')">
                                                    Requests
                                                </button>
                                                <button title="Pending Approval" class="px-2 nav-link" id="nav-pendingclubs-tab" data-bs-toggle="tab" data-bs-target="#nav-pendingclubs" type="button" role="tab" aria-controls="nav-pendingclubs" aria-selected="false" onclick="updateLabel('Pending Approval')">
                                                    Pending
                                                </button>
                                                <button title="Approve" class="px-2 nav-link" id="nav-approvedclubs-tab" data-bs-toggle="tab" data-bs-target="#nav-approvedclubs" type="button" role="tab" aria-controls="nav-approvedclubs" aria-selected="false" onclick="updateLabel('Approve')">
                                                    Approved
                                                </button>
                                                <button title="Disapproved" class="px-2 nav-link" id="nav-disapprovedclubs-tab" data-bs-toggle="tab" data-bs-target="#nav-disapprovedclubs" type="button" role="tab" aria-controls="nav-disapprovedclubs" aria-selected="false" onclick="updateLabel('Disapproved')">
                                                    Disapproved
                                                </button>
                                                <!-- <button title="Filter" class="px-1 btn ms-auto" tabindex="-1" type="button" style="box-shadow: none !important;">
                                                    <i class="fa-solid fa-sliders"></i>
                                                </button> -->
                                            </div>
                                        </nav>
                                        <div class="tab-content">
                                            <!-- All Club Request Tab -->
                                            <div class="tab-pane fade show active" id="nav-requestedclubs" role="tabpanel" aria-labelledby="nav-requestedclubs-tab">
                                                <div class="row g-2 mt-0" id="allClubRequestContainer">
                                                    <!-- All student clubs cards will be dynamically added here -->
                                                </div>
                                            </div>

                                            <!-- Pending Club Request Tab -->
                                            <div class="tab-pane fade" id="nav-pendingclubs" role="tabpanel" aria-labelledby="nav-pendingclubs-tab">
                                                <div class="row g-2 mt-0" id="pendingClubRequestContainer">
                                                    <!-- All student pending clubs cards will be dynamically added here -->
                                                </div>
                                            </div>

                                            <!-- Approved Club Request Tab -->
                                            <div class="tab-pane fade" id="nav-approvedclubs" role="tabpanel" aria-labelledby="nav-approvedclubs-tab">
                                                <div class="row g-2 mt-0" id="approvedClubRequestContainer">
                                                    <!-- All student approved clubs cards will be dynamically added here -->
                                                </div>
                                            </div>

                                            <!-- Disapproved Club Request Tab -->
                                            <div class="tab-pane fade" id="nav-disapprovedclubs" role="tabpanel" aria-labelledby="nav-disapprovedclubs-tab">
                                                <div class="row g-2 mt-0" id="disapprovedClubRequestContainer">
                                                    <!-- All student disapproved clubs cards will be dynamically added here -->
                                                </div>
                                            </div>
                                        </div>
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



    <script>
        function submitClubRequest() {
            document.getElementById('clubRequestForm').submit();
        }

        function updateLabel(label) {
            document.getElementById("tabLabel").innerText = label;
        }

        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function(){
                var output = document.getElementById('coverPhotoPreview');
                output.src = reader.result;
                output.style.display = 'block';
            }
            reader.readAsDataURL(event.target.files[0]);
        }

        $(document).ready(function() {
            function loadClubs(tab, containerId, dateLabel) {
                $.ajax({
                    url: `/esas/esas_student/apis/club-request-${tab}-api.php`, // Adjust the URL for each tab
                    type: "GET",
                    success: function(response) {
                        const clubsContainer = document.getElementById(containerId);
                        if (response && response.length > 0) {
                            clubsContainer.innerHTML = response.map(club => `
                                <div class="col-md-4 mb-4 card-container">
                                    <div class="card card-img-only">
                                        <a href="#">
                                            <img src="/esas/esas_student/images/${club.coverPhoto}" alt="Cover Photo">
                                            <div class="overlay-text">
                                                <h4>${club.clubName}</h4>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="date text-muted">
                                        ${dateLabel}: ${club.dateModified}
                                    </div>
                                </div>
                            `).join('');
                        } else {
                            clubsContainer.innerHTML = '<p>No clubs found.</p>';
                        }
                    },
                    error: function() {
                        const clubsContainer = document.getElementById(containerId);
                        clubsContainer.innerHTML = '<p>Failed to fetch clubs. Please try again later.</p>';
                    }
                });
            }

            // Load initial content for All Clubs tab
            loadClubs('all', 'allClubRequestContainer', 'Date requested');

            // Event listeners for each tab
            $('#nav-requestedclubs-tab').on('click', function() {
                loadClubs('all', 'allClubRequestContainer', 'Date requested');
            });

            $('#nav-pendingclubs-tab').on('click', function() {
                loadClubs('pending', 'pendingClubRequestContainer', 'Date requested');
            });

            $('#nav-approvedclubs-tab').on('click', function() {
                loadClubs('approved', 'approvedClubRequestContainer', 'Date approved');
            });

            $('#nav-disapprovedclubs-tab').on('click', function() {
                loadClubs('disapproved', 'disapprovedClubRequestContainer', 'Date disapproved');
            });
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