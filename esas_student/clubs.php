<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eSAS - Clubs</title>
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> -->

    
    <link href="../assets/css/jquery.dataTables.min.css" rel="stylesheet" />
    <script src="../assets/js/all.js" crossorigin="anonymous"></script>
    <script src="../assets/js/jquery-3.6.0.js"></script>
    <link href="../assets/css/styles.css" rel="stylesheet" />
    <link href="../assets/img/nbsclogo.png" rel="icon">
    <style>
        /* body {
            font: 14px Helvetica;
        } */
        .container-fluid {
            text-align: center;
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
        .section-label {
            margin-top: 10px;
            margin-bottom: 0px;
            color: #343a40;
        }
        .card-img-only {
            position: relative;
            width: 100%;
            border: solid 3px transparent;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(0, 0, 0, .5);
            margin-bottom: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card-img-only:hover {
            transform: scale(1.03);
            border: solid 3px lightblue;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.6);
        }
        .card-img-only img {
            width: 100%;
            height: auto;
            display: block;
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

        .moderators-container {
    display: flex;
    align-items: center;
    padding-left: 0; /* Removed padding */
}

.moderator-pics {
    display: flex;
    align-items: center;
    margin-left: 10px; /* Space between pictures and names */
}

.moderator-pic {
    width: 25px !important; /* Adjust size as needed */
    height: 30px;
    /* border: solid 3px rgba(255, 255, 255, 0.5); */
    border-radius: 50%;
    margin-left: -6px; /* Adjust to make the pics overlap */
    box-shadow: 2px 2px 4px rgba(0, 0, 0, 0.6);
}


.moderator-names {
    display: flex;
    align-items: center;
    flex-wrap: nowrap; /* Ensure names stay in one row */
    white-space: nowrap; /* Prevent line breaks in names */
    font-size: 14px; /* Adjust font size as needed */
    color: white;
}


.moderator-pic:nth-child(1) {
    z-index: 3; /* First picture on top */
}

.moderator-pic:nth-child(2) {
    z-index: 2; /* Second picture below the first */
}

.moderator-pic:nth-child(3) {
    z-index: 1; /* Third picture below the second */
}

.moderator-pic:nth-child(4) {
    z-index: 0; /* Fourth picture below the third */
}

.moderator-names {
    margin-left: 10px; /* Space between profile pics and names */
}


        .list-unstyled{
            line-height: 1.5;
        }
        
        @media (min-width: 768px) {
            .card-img-only {
                margin-bottom: 30px;
            }
            .overlay-text h4{
                font-size: 28px;
                margin-right: 10px;
                line-height: 1.2; /* Adjust line height for closer spacing */
            }
            .overlay-text {
                left: 0; /* Ensure it stays aligned to the left */
                bottom: 0; /* Keep it at the bottom */
                right: 0; /* Ensure it covers full width */
                padding: 15px; /* Increased padding */
            }
            .moderator-pic {
                width: 30px !important;
            }
        }

    </style>
</head>
<!-- <body class="sb-nav-fixed"> -->
<body>
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

    <!-- <script>
        $(document).ready(function() {
            alert("Welcome! Since you are new here, you need to pick or select one club.");
        });
    </script> -->

    <div class="modal fade" id="welcomeModal" tabindex="-1" aria-labelledby="welcomeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="welcomeModalLabel">Welcome to eSAS!</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Since you are new here, we encourage you to explore the various clubs available and select the one that best aligns with your interests and goals. This is an excellent opportunity to engage with like-minded peers and enhance your college experience.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Get Started</button>
                </div>
            </div>
        </div>
    </div>


    <div class="container-fluid">
        
        <div class="row" id="studentClubsContainer">
            <!-- Student club cards will be dynamically added here -->
        </div>

        <div class="row">
            <div class="col-12 mb-3">
                <h1 class="section-label">
                    All Student Club Organizations <i class="fa fa-university"></i>
                </h1>
                <p class="text-muted"><em>(Students are only allowed to choose two clubs.)</em></p>
            </div>
        </div>

        <div class="row" id="clubsContainer">
            <!-- Club cards will be dynamically added here -->
        </div>
    </div>
    

    
    <!-- <?php include 'assets/components/modals.php' ?> -->
    <script src="../assets/js/jquery.dataTables.min.js"></script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/global_script.js"></script>
    <script>

        // $(document).ready(function() {
        //     $('#welcomeModal').modal('show');
        // });

        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
        });

        // Fetch student-specific clubs data from API
    fetch('/esas/esas_student/apis/student-clubs-api.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            const studentClubsContainer = document.getElementById('studentClubsContainer');
            if (data && data.length > 0) {
                data.forEach(club => {
                    const memberText = club.membersCount === 1 ? '1 member' : `${club.membersCount} members`;
                    const cardHTML = `
                        <div class="col-md-4">
                            <div class="card card-img-only">
                                <small data-toggle="tooltip" title="${memberText}">
                                    <i class="fa fa-user mr-1"></i>${club.membersCount}
                                </small>
                                <a href="/esas/esas_student/home.php?club_id=${club.club_id}&club_name=${encodeURIComponent(club.clubName)}">
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
                    studentClubsContainer.innerHTML += cardHTML;
                });
            } else {
                studentClubsContainer.innerHTML = '<p>No clubs found.</p>';
            }
        })
        .catch(error => {
            console.error('Error fetching student clubs:', error);
            const studentClubsContainer = document.getElementById('studentClubsContainer');
            studentClubsContainer.innerHTML = '<p>Failed to fetch your clubs. Please try again later.</p>';
        });

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
                                <div class="card card-img-only">
                                    <small data-toggle="tooltip" title="${memberText}">
                                        <i class="fa fa-user mr-1"></i>${club.membersCount}
                                    </small>
                                    <a href="/esas/esas_student/club_info.php?club_id=${club.club_id}&club_name=${encodeURIComponent(club.clubName)}">
                                        <img src="/esas/esas_admin/images/${club.coverPhoto}" alt="Cover Photo">
                                        <div class="overlay-text">
                                            <h4>${club.clubName}</h4>
                                            <div class="moderators-container">
                                                ${club.formattedModerators}
                                            </div>
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


        function registerNow(clubId) {
            window.location.href = `/esas/esas_student/registration.php?club_id=${clubId}`;
        }

    </script>

</body>

<footer class="navbar-darkblue text-white mt-1 p-4 text-center">
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
</footer>

</html>
