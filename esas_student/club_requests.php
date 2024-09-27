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
    <meta name="goal" content="" />
    <meta name="author" content="" />
    <title>eSAS - Club Requests</title>
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> -->

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
            border-radius: 10px;
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


        
        body.modal-open {
            padding-right: 0 !important;
        }

        .modal-paragraph {
            text-align: justify;         /* Ensures text is justified (end-to-end alignment) */
            /* text-indent: 30px;           Indents the first line of the paragraph */
            margin-bottom: 15px;         /* Adds space between paragraphs */
        }


        .btn {
            border-radius: 5px;
        }


        .notification-badge {
            position: absolute;
            min-width: 20px;
            height: auto;
            top: 5px;
            right: 5px;
            background-color: red;
            color: white;
            border-radius: 50%;
            padding: 4px 4px;
            font-size: 12px;
            display: inline-block;
            text-align: center;
            line-height: 1;
            z-index: 1000;
        }


        .nav-link {
            position: relative; /* This makes the span position relative to the button */
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
                                <i class="fas fa-university"></i> All Clubs
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../esas_student/my_clubs.php" class="nav-link left-sidebar text-dark" id="my-clubs">
                                <i class="fas fa-user"></i> My Clubs
                                <span id="notification-count" class="notification-badge" style="display:none;">3</span>
                            </a>
                        </li>

                        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                        <script>
                            // Fetch and display notification count
                            function fetchNotificationCount() {
                                $.ajax({
                                    url: '/esas/esas_student/apis/notifications/notifications-api.php',
                                    method: 'GET',
                                    success: function(response) {
                                        const data = JSON.parse(response);
                                        if (data.unread_count > 0) {
                                            $('#notification-count').text(data.unread_count).show();
                                        } else {
                                            $('#notification-count').hide();
                                        }
                                    }
                                });
                            }

                            // Fetch notifications every 10 seconds
                            setInterval(fetchNotificationCount, 10000);
                            fetchNotificationCount();

                            // // Mark notifications as read when "My Clubs" is clicked
                            // $('#my-clubs').click(function() {
                            //     $.ajax({
                            //         url: '/esas/esas_student/apis/notifications/notifications-mark-read.php',
                            //         method: 'POST',
                            //         data: { student_id: <?php echo $_SESSION['student_id']; ?> },
                            //         success: function() {
                            //             $('#notification-count').hide(); // Hide the count after marking notifications as read
                            //         }
                            //     });
                            // });
                        </script>
                        <li>
                            <a href="../esas_student/club_requests.php" class="nav-link left-sidebar text-dark active" aria-current="page" id="club-requests">
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
                    <div id="divpr_requesdetails" class="table-responsive px-0">
                        <div class="row g-0 p-4 px-2 pt-3 h-100">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row g-0">
                                        <div class="d-flex align-items-center justify-content-end pb-3 mt-2 mb-3">
                                            <!-- <h2 class="text-muted mt-0 mb-0">My Club Requests</h2> -->
                                            <a href="../esas_student/crud/club_requests/club_request_create.php" class="btn btn-primary" id="request-club-btn" style="width: 160px; border-radius: 5px;">
                                                Request for a Club
                                            </a>
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
            url: `/esas/esas_student/apis/club-request-${tab}-api.php`,
            type: "GET",
            success: function(response) {
                const clubsContainer = document.getElementById(containerId);
                if (response && response.length > 0) {
                    clubsContainer.innerHTML = response.map(club => `
                        <div class="col-md-4 card-container">
                            <div class="card card-img-only">
                                <a href="#" class="club-card" data-id="${club.request_id}">
                                    <img src="/esas/esas_student/images/${club.coverPhoto}" alt="Cover Photo">
                                    <div class="overlay-text">
                                        <h4>${club.clubName}</h4>
                                    </div>
                                </a>
                            </div>
                            <!-- <p class="request-id">Request ID: ${club.request_id}</p> -->
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




    function loadClubDetails(requestId) { 
    $.ajax({
        url: `/esas/esas_student/apis/club-request-details-api.php`,
        type: "GET",
        data: { request_id: requestId },
        success: function(response) {
            if (response) {
                // Set cover photo
                $('#modalCoverPhoto').find('img').attr('src', `/esas/esas_student/images/${response.coverPhoto}`);

                // Set club name
                $('#modalClubName').text(response.clubName);

                // Set date requested
                    $('#modalDateRequested').html(`<strong>Date Requested:</strong> ${response.dateRequested}`);

                    // Set status
                    $('#modalStatus').html(`<strong>Status:</strong> ${response.status}`);

                    // Conditionally set the approved or disapproved date
                    if (response.status === 'approved') {
                        $('#modalDateApprovedOrDisapproved').html(`<strong>Date Approved:</strong> ${response.dateDecided}`);
                    } else if (response.status === 'disapproved') {
                        $('#modalDateApprovedOrDisapproved').html(`<strong>Date Disapproved:</strong> ${response.dateDecided}`);
                    } else {
                        // If pending, keep the approved/disapproved date empty
                        $('#modalDateApprovedOrDisapproved').html('');
                    }

                // Conditional display of buttons based on status
                if (response.status === 'pending') {
                    $('#updateButton').show();
                    $('#deleteButton').show();
                } else if (response.status === 'disapproved') {
                    $('#updateButton').hide();
                    $('#deleteButton').show();
                } else if (response.status === 'approved') {
                    $('#updateButton').hide();
                    $('#deleteButton').hide();
                }

                // Set Request ID
                $('#request_id').val(response.request_id); // Set the value of the hidden input

                // Set goal, mission, vision, and activities
                $('#modalGoal').text(response.goal);
                $('#modalMission').text(response.mission);
                $('#modalVision').text(response.vision);
                $('#modalActivities').text(response.activities);

                // Handle the request letter (if exists)
                if (response.requestLetter) {
                    const fileType = response.requestLetter.split('.').pop().toLowerCase();
                    const fileIcon = (fileType === 'pdf') ? '/esas/esas_student/icons/ICON_PDF.png' :
                                     (fileType === 'doc' || fileType === 'docx') ? '/esas/esas_student/icons/ICON_WORD.png' : '';

                    // Open file when icon is clicked
                    $('#modalRequestLetter').html(`
                        <div class="d-flex align-items-center">
                            <img src="${fileIcon}" alt="${fileType.toUpperCase()} File" style="width: 100px; margin: 10px;">
                            <a href="/esas/esas_student/request_letters/${response.requestLetter}" target="_blank" style="color: blue; text-decoration: underline; margin-left: 5px;">See Your Attached Request Letter</a>
                        </div>
                    `);
                } else {
                    $('#modalRequestLetter').html('<p>No attached request letter.</p>');
                }

                // Show the modal
                $('#clubDetailsModal').modal('show');
            }
        },
        error: function() {
            alert('Failed to fetch club details. Please try again later.');
        }
    });
}




$(document).on('click', '#updateButton', function(e) {
    e.preventDefault();
    const requestId = $('#request_id').val(); // Get the value from the hidden input
    if (requestId) {
        window.location.href = `../esas_student/crud/club_requests/club_request_update.php?request_id=${requestId}`;
    } else {
        alert('Request ID not found.');
    }
});





    $(document).on('click', '.club-card', function(e) {
        e.preventDefault();
        const requestId = $(this).data('id');
        loadClubDetails(requestId);
    });

    // Initial load for All Clubs tab
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






<div id="clubDetailsModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 id="modalClubName"></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="max-height: 450px; overflow-y: auto; padding: 15px;">
                <div id="modalCoverPhoto" class="text-center">
                    <img src="" alt="Cover Photo" class="img-fluid">
                </div>
                <div class="mt-3">
                    <p id="modalStatus" class="text-left m-0"></p>
                    <p id="modalDateRequested" class="text-left m-0"></p>
                    <p id="modalDateApprovedOrDisapproved" class="text-left m-0"></p>
                </div>
                <hr>
                <strong>Goal:</strong>
                <p id="modalGoal" class="modal-paragraph"></p>
                <strong>Mission:</strong>
                <p id="modalMission" class="modal-paragraph"></p>
                <strong>Vision:</strong>
                <p id="modalVision" class="modal-paragraph"></p>
                <strong>Activities:</strong>
                <p id="modalActivities" class="modal-paragraph"></p>

                <hr>
                <strong>Request Letter:</strong>
                <div class="card mt-3 p-3 align-items-center bg-light" id="modalRequestLetter" style="border-radius: 15px;"></div>
            </div>
            <div class="modal-footer text-center align-items-center justify-content-center">
                <input type="hidden" name="request_id" id="request_id" value="">
                <a href="#" class="btn btn-warning" id="updateButton" title="Update Request" data-toggle="tooltip">Update</a>
                <a href="#" class="btn btn-danger" id="deleteButton" title="Delete Request" data-toggle="tooltip">Delete</a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>




</body>
</html>