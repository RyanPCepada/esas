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
            border: solid 3px lightblue;
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
            /* width: 230px;
            height: 130px; */
            width: 270px;
            height: 152px;
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

        <!-- YOUR CLUB -->
        <div class="pe-2 ps-2">
            <h5>Your Clubs</h5>
            <!-- <button class="btn btn-primary btn-sm py-1 mt-1 rounded-3 w-100">Create New Request</button> -->
        </div>

        <!-- Tabbed Section for Your Clubs -->
        <div class="row g-0 mt-2">
            <nav>
                <div class="nav nav-tabs n" role="tablist">
                    <button title="New" class="ms-2 px-2 nav-link active" id="nav-allclubs-tab" data-bs-toggle="tab" data-bs-target="#nav-allclubs" type="button" role="tab" aria-controls="nav-allclubs" aria-selected="true" onclick="updateLabel('All')">
                        <i class="fa-regular fa-file"></i>
                    </button>
                    <button title="Ongoing" class="px-2 nav-link" id="nav-activeclubs-tab" data-bs-toggle="tab" data-bs-target="#nav-activeclubs" type="button" role="tab" aria-controls="nav-activeclubs" aria-selected="false" onclick="updateLabel('Pending Approval')">
                        <i class="fa-regular fa-clock"></i>
                    </button>
                    <button title="Approved" class="px-2 nav-link" id="nav-inactiveclubs-tab" data-bs-toggle="tab" data-bs-target="#nav-inactiveclubs" type="button" role="tab" aria-controls="nav-inactiveclubs" aria-selected="false" onclick="updateLabel('Approved')">
                        <i class="fa-regular fa-thumbs-up"></i>
                    </button>
                    <button title="Rejected" class="px-2 nav-link" id="nav-rejectedclubs-tab" data-bs-toggle="tab" data-bs-target="#nav-rejectedclubs" type="button" role="tab" aria-controls="nav-rejectedclubs" aria-selected="false" onclick="updateLabel('Rejected')">
                        <i class="fa-regular fa-thumbs-down"></i>
                    </button>
                    <button title="Filter" class="px-1 btn ms-auto" tabindex="-1" type="button" style="box-shadow: none !important;">
                        <i class="fa-solid fa-sliders"></i>
                    </button>
                </div>
            </nav>
            <!-- Label for displaying the current tab's name -->
            <div class="mt-2 mb-0 ps-2">
                <h5 id="clubTabLabel">All Clubs</h5>
            </div>

            <div class="tab-content ps-2">
                <!-- Tab for All Clubs -->
                <div class="tab-pane fade show active" id="nav-allclubs" role="tabpanel" aria-labelledby="nav-allclubs-tab">
                    <div class="table-responsive auto-scroll" style="height: 400px;">
                        <table id="tblAllClubs" class="table table-sm table-hover">
                            <!-- ALL STUDENT CLUBS HERE -->
                        </table>
                        <div style="height: 150px;"></div>
                    </div>
                </div>

                <!-- Tab for Active Clubs -->
                <div class="tab-pane fade" id="nav-activeclubs" role="tabpanel" aria-labelledby="nav-activeclubs-tab">
                    <div class="table-responsive auto-scroll" style="height: 400px;">
                        <table id="tblActiveClubs" class="table table-sm table-hover">
                            <!-- ACTIVE STUDENT CLUBS HERE -->
                        </table>
                        <div style="height: 150px;"></div>
                    </div>
                </div>

                <!-- Tab for Inactive Clubs -->
                <div class="tab-pane fade" id="nav-inactiveclubs" role="tabpanel" aria-labelledby="nav-inactiveclubs-tab">
                    <div class="table-responsive auto-scroll" style="height: 400px;">
                        <table id="tblInactiveClubs" class="table table-sm table-hover">
                            <!-- INACTIVE STUDENT CLUBS HERE -->
                        </table>
                        <div style="height: 150px;"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- YOUR CLUB END -->

        <hr>

        <!-- YOUR CLUB REQUESTS -->
        <div class="pe-2 ps-2">
            <h5>Your Club Requests</h5>
            <button class="btn btn-primary btn-sm py-1 mt-1 rounded-3 w-100">Create New Club Request</button>
        </div>
        <div class="row g-0 mt-2">
            <nav>
                <div class="nav nav-tabs n" role="tablist">
                    <button title="New" class="ms-2 px-2 nav-link active" id="nav-prnew-tab" data-bs-toggle="tab" data-bs-target="#nav-prnew" type="button" role="tab" aria-controls="nav-prnew" aria-selected="true" onclick="updateLabel('All')">
                        <i class="fa-regular fa-file"></i>
                    </button>
                    <button title="Ongoing" class="px-2 nav-link" id="nav-prongoing-tab" data-bs-toggle="tab" data-bs-target="#nav-prongoing" type="button" role="tab" aria-controls="nav-prongoing" aria-selected="false" onclick="updateLabel('Pendings')">
                        <i class="fa-regular fa-clock"></i>
                    </button>
                    <button title="Approved" class="px-2 nav-link" id="nav-prapproved-tab" data-bs-toggle="tab" data-bs-target="#nav-prapproved" type="button" role="tab" aria-controls="nav-prapproved" aria-selected="false" onclick="updateLabel('Approved')">
                        <i class="fa-regular fa-thumbs-up"></i>
                    </button>
                    <button title="Rejected" class="px-2 nav-link" id="nav-prreject-tab" data-bs-toggle="tab" data-bs-target="#nav-prreject" type="button" role="tab" aria-controls="nav-prreject" aria-selected="false" onclick="updateLabel('Disapproved')">
                        <i class="fa-regular fa-thumbs-down"></i>
                    </button>
                    <button title="Filter" class="px-1 btn ms-auto" tabindex="-1" type="button" style="box-shadow: none !important;">
                        <i class="fa-solid fa-sliders"></i>
                    </button>
                </div>
            </nav>

            <!-- Label for displaying the current tab's name -->
            <div class="mt-2 mb-0 ps-2">
                <h5 id="tabLabel">All</h5>
            </div>

            <div class="tab-content ps-2">
                <div class="col-12 pe-2 py-1">
                    <input id="inprsearchfilter" class="form-control form-control-sm me-1" placeholder="Search...">
                </div>
                <div class="tab-pane fade show active" id="nav-prnew" role="tabpanel" aria-labelledby="nav-prnew-tab">
                    <div class="table-responsive auto-scroll" style="height: 400px">
                        <table id="tblprnewsumm" class="tblprfilter table table-sm table-hover">
                            <!-- ALL STUDENT CLUB REQUESTS HERE -->
                        </table>
                        <div style="height: 150px;"></div>
                    </div>
                </div>
                <div class="tab-pane fade" id="nav-prongoing" role="tabpanel" aria-labelledby="nav-prongoing-tab">
                    <div class="table-responsive auto-scroll" style="height: 400px">
                        <table id="tblprongoingsumm" class="tblprfilter table table-sm table-hover">
                            <!-- ALL STUDENT PENDING CLUB REQUESTS HERE -->
                        </table>
                        <div style="height: 150px;"></div>
                    </div>
                </div>
                <div class="tab-pane fade" id="nav-prapproved" role="tabpanel" aria-labelledby="nav-prapproved-tab">
                    <div class="table-responsive auto-scroll" style="height: 400px">
                        <table id="tblprapprovedsumm" class="tblprfilter table table-sm table-hover">
                            <!-- ALL STUDENT APPROVED CLUB REQUESTS HERE -->
                        </table>
                        <div style="height: 150px;"></div>
                    </div>
                </div>
                <div class="tab-pane fade" id="nav-prreject" role="tabpanel" aria-labelledby="nav-prreject-tab">
                    <div class="table-responsive auto-scroll" style="height: 400px">
                        <table id="tblprrejectedsumm" class="tblprfilter table table-sm table-hover">
                            <!-- ALL STUDENT DISAPPROVED CLUB REQUESTS HERE -->
                        </table>
                        <div style="height: 150px;"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- YOUR CLUB REQUESTS END -->
    </div>
    <!-- LEFT SIDEBAR END -->

    <div class="col-8 bg-lgrey">
        <div class="row g-0 h-100">
            <div id="divpr_requesdetails" class="table-responsive px-0 auto-scroll">
                <div class="row g-0 p-4 px-2 pt-3 h-100">
                    <div class="card">
                        <div class="card-body">
                            
                            <h2 class="mt-0 mb-4">Student Club Organizations</h2>
                            <hr>
                            
                            <div class="row" id="clubsContainer">
                                <!-- Club cards will be dynamically added here -->
                            </div>
                            <a href="../esas_student/clubs.php" class="btn btn-secondary float-end">Cancel</a>

                            
                            <!-- <h2 class="mt-0 mb-4">Request for a New Club</h2>
                            <p class="py-2">Please fill out this form and submit your request for a new club.</p>
                            <form id="clubRequestForm" action="../esas_student/actions/club_request_action.php" method="POST" enctype="multipart/form-data">
                                <div class="form-group mb-3">
                                    <label for="clubName">Club Name</label>
                                    <input type="text" name="clubName" class="form-control" id="clubName" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="description">What is the primary goal of this club?</label>
                                    <textarea name="description" class="form-control" id="description" rows="4" required></textarea>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="activities">Proposed Activities</label>
                                    <textarea name="activities" class="form-control" id="activities" rows="3"></textarea>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="coverPhoto">Club Cover Photo</label>
                                    <input type="file" name="coverPhoto" class="form-control" id="coverPhoto" required onchange="previewImage(event)">
                                </div>
                                
                                <div class="form-group mb-3">
                                    <img id="coverPhotoPreview" src="#" alt="Cover Photo Preview" style="display:none; width: 50%; object-fit: cover;" />
                                </div>
                                <!-- <a href="javascript:history.go(-1)" return false;" class="btn btn-secondary float-end">Cancel</a> --
                                <a href="../esas_student/clubs.php" class="btn btn-secondary float-end">Cancel</a>
                            </form>

                            <script>
                                function previewImage(event) {
                                    var reader = new FileReader();
                                    reader.onload = function(){
                                        var output = document.getElementById('coverPhotoPreview');
                                        output.src = reader.result;
                                        output.style.display = 'block';
                                    }
                                    reader.readAsDataURL(event.target.files[0]);
                                }
                            </script> -->
                        </div>
                    </div>
                </div>
                <div style="height: 150px;"></div>
            </div>
        </div>
    </div>
    <div id="divprstatussection" class="col-2 border-start">
        <div class="row mt-3 g-0">
            <div class="col-12">
                <div class="pe-2 ps-2">
                    <h5>SBO Officers</h5>
                </div>
                <!-- <div class="ps-2">
                    <button id="prbtnsubmitforapproval" onclick="submitClubRequest()" class="btn btn-sm btn-outline-primary rounded-3 w-100 mb-1"><i class="fa fa-plane" aria-hidden="true"></i> Submit for Approval</button>
                </div> -->
            </div>
        </div>
    </div>

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
                const clubsContainer = document.getElementById('clubsContainer');
                if (data && data.length > 0) {
                    data.forEach(club => {
                        const memberText = club.membersCount === 1 ? '1 member' : `${club.membersCount} members`;
                        const cardHTML = `
                            <div class="col-md-4">
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
                        clubsContainer.innerHTML += cardHTML;
                    });
                } else {
                    clubsContainer.innerHTML = '<p>No clubs found.</p>';
                }
            })
            .catch(error => {
                console.error('Error fetching clubs:', error);
                const clubsContainer = document.getElementById('clubsContainer');
                clubsContainer.innerHTML = '<p>Failed to fetch clubs. Please try again later.</p>';
            });


        document.addEventListener('DOMContentLoaded', function () {
            const clubEndpoints = {
                all: '/esas/esas_student/apis/student-clubs-all-api.php',
                pending: '/esas/esas_student/apis/student-clubs-pending-api.php',
                approved: '/esas/esas_student/apis/student-clubs-approved-api.php',
                disapproved: '/esas/esas_student/apis/student-clubs-disapproved-api.php'
            };

            function fetchStudentClubs(endpoint, tableId) {
                fetch(endpoint)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        const table = document.getElementById(tableId);
                        if (data && data.length > 0) {
                            table.innerHTML = data.map(club => `
                                <div class="col-md-12">
                                    <div class="card card-img-only">
                                        <small data-toggle="tooltip" title="${club.membersCount} members">
                                            <i class="fa fa-user mr-1"></i>${club.membersCount}
                                        </small>
                                        <a href="/esas/esas_student/home.php?club_id=${club.club_id}&club_name=${encodeURIComponent(club.clubName)}">
                                            <img src="/esas/esas_admin/images/${club.coverPhoto}" alt="Cover Photo">
                                            <div class="overlay-text">
                                                <h4>${club.clubName}</h4>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            `).join('');
                        } else {
                            table.innerHTML = '<p>No clubs found.</p>';
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching data:', error);
                        const table = document.getElementById(tableId);
                        table.innerHTML = '<p class="mt-3">Failed to load clubs. Please try again later.</p>';
                    });
            }

            // Fetch data for each tab
            fetchStudentClubs(clubEndpoints.all, 'tblAllClubs');         // For "All Clubs"
            fetchStudentClubs(clubEndpoints.pending, 'tblActiveClubs');  // For "Pending Approval"
            fetchStudentClubs(clubEndpoints.approved, 'tblInactiveClubs'); // For "Approved Clubs"
        });


        document.addEventListener('DOMContentLoaded', function () {
            const endpoints = {
                all: '/esas/esas_student/apis/club-request-all-api.php',
                pending: '/esas/esas_student/apis/club-request-pending-api.php',
                approved: '/esas/esas_student/apis/club-request-approved-api.php',
                disapproved: '/esas/esas_student/apis/club-request-disapproved-api.php'
            };

            function fetchData(endpoint, tableId) {
                fetch(endpoint)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        const table = document.getElementById(tableId);
                        if (data && data.length > 0) {
                            table.innerHTML = data.map(request => `
                                <div class="col-md-12">
                                    <div class="card card-img-only">
                                        <a href="#">
                                            <img src="/esas/esas_student/images/${request.coverPhoto}" alt="Cover Photo">
                                            <div class="overlay-text">
                                                <h4>${request.clubName}</h4>
                                            </div>
                                        </a>
                                        <!--<div class="card-footer">
                                            <p><strong>Status:</strong> ${request.status}</p>
                                            <p><strong>Date Requested:</strong> ${new Date(request.dateRequested).toLocaleString()}</p>
                                        </div>-->
                                    </div>
                                </div>
                            `).join('');
                        } else {
                            table.innerHTML = '<p>No requests found.</p>';
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching data:', error);
                        const table = document.getElementById(tableId);
                        table.innerHTML = '<p class="mt-3">No club requests found.</p>';
                    });
            }

            fetchData(endpoints.all, 'tblprnewsumm');
            fetchData(endpoints.pending, 'tblprongoingsumm');
            fetchData(endpoints.approved, 'tblprapprovedsumm');
            fetchData(endpoints.disapproved, 'tblprrejectedsumm');
        });
    </script>

</div>

<!-- <?php include 'assets/components/modals.php' ?> -->
<script src="../assets/js/jquery.dataTables.min.js"></script>
<script src="../assets/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/global_script.js"></script>
<script>
    $(document).ready(function() {
        $('.delprreq').click(function(e) {
            e.stopPropagation();
        });
    });
</script>
</body>
</html>