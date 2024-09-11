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
        .nav-tabs {
            margin-top: -20px !important;
            margin-bottom: 50px;
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
            width: 295px;
            height: 166px;
            /* width: 330px;
            height: 188px; */
            border: solid 3px transparent;
            border: none;
            border-radius: 15px;
            overflow: hidden;
            /* box-shadow: 0 5px 10px rgba(0, 0, 0, .5); */
            margin: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card-img-only:hover {
            transform: scale(1.01);
            border: solid 3px white;
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
    </style>
</head>

<!--HERE-->

<body>
    <div class="row g-0">
        <h2 class="text-muted mt-0 mb-5">My Clubs</h2>
        <nav>
            <div class="nav nav-tabs n" role="tablist">
                <button title="Registered Clubs" class="ms-2 px-2 nav-link active" id="nav-activeclubs-tab" data-bs-toggle="tab" data-bs-target="#nav-activeclubs" type="button" role="tab" aria-controls="nav-activeclubs" aria-selected="true" onclick="updateLabel('Registered Clubs')">
                    Registered Clubs
                </button>
                <button title="Pending Approval" class="px-2 nav-link" id="nav-pendingclubs-tab" data-bs-toggle="tab" data-bs-target="#nav-pendingclubs" type="button" role="tab" aria-controls="nav-pendingclubs" aria-selected="false" onclick="updateLabel('Pending Approval')">
                    Pending Approval
                </button>
                <button title="Disapproved" class="px-2 nav-link" id="nav-disapprovedclubs-tab" data-bs-toggle="tab" data-bs-target="#nav-disapprovedclubs" type="button" role="tab" aria-controls="nav-disapprovedclubs" aria-selected="false" onclick="updateLabel('Disapproved')">
                    Disapproved
                </button>
                <button title="Filter" class="px-1 btn ms-auto" tabindex="-1" type="button" style="box-shadow: none !important;">
                    <i class="fa-solid fa-sliders"></i>
                </button>
            </div>
        </nav>
        <div class="tab-content">
            <!-- Registered Clubs Tab -->
            <div class="tab-pane fade show active" id="nav-activeclubs" role="tabpanel" aria-labelledby="nav-activeclubs-tab">
                <div class="row g-2 mt-0" id="activeClubsContainer">
                    <!-- All student clubs cards will be dynamically added here -->
                </div>
            </div>

            <!-- Pending Approval Clubs Tab -->
            <div class="tab-pane fade" id="nav-pendingclubs" role="tabpanel" aria-labelledby="nav-pendingclubs-tab">
                <div class="row g-2 mt-0" id="pendingClubsContainer">
                    <!-- All student pending clubs cards will be dynamically added here -->
                </div>
            </div>

            <!-- Disapproved Clubs Tab -->
            <div class="tab-pane fade" id="nav-disapprovedclubs" role="tabpanel" aria-labelledby="nav-disapprovedclubs-tab">
                <div class="row g-2 mt-0" id="disapprovedClubsContainer">
                    <!-- All student disapproved clubs cards will be dynamically added here -->
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

        $(document).ready(function() {
            function loadClubs(tab, containerId) {
                $.ajax({
                    url: `/esas/esas_student/apis/student-clubs-${tab}-api.php`, // Adjust the URL for each tab
                    type: "GET",
                    success: function(response) {
                        const clubsContainer = document.getElementById(containerId);
                        if (response && response.length > 0) {
                            clubsContainer.innerHTML = response.map(club => `
                                <div class="col-md-4">
                                    <div class="card card-img-only">
                                        <!--<small data-toggle="tooltip" title="${club.membersCount === 1 ? '1 member' : `${club.membersCount} members`}">
                                            <i class="fa fa-user mr-1"></i>${club.membersCount}
                                        </small>-->
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
            loadClubs('active', 'activeClubsContainer');

            // Event listeners for each tab
            $('#nav-activeclubs-tab').on('click', function() {
                loadClubs('active', 'activeClubsContainer');
            });

            $('#nav-pendingclubs-tab').on('click', function() {
                loadClubs('pending', 'pendingClubsContainer');
            });

            $('#nav-disapprovedclubs-tab').on('click', function() {
                loadClubs('disapproved', 'disapprovedClubsContainer');
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