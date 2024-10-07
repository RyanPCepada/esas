<?php
session_start();
require_once "../config.php";

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
    <title>eSAS - All Clubs</title>
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
            padding: 30px;
        }
        
        .card-img-only-all {
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
                padding: 0px !important; 
                max-width: 100%; 
            }
            .card-img-only-all {
                width: 315px;
                height: 177px;
                width: auto;
                height: 145px;
                margin-top: 10px;
            }
            #departmentSelect {
                width: 30% !important;
            }
            .table-striped {
                margin-top: 10px;
            }
            #allClubsContainer {
                margin-top: -20px !important;
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




        /* Default for larger screens (6 cards per row) */
        .sbo-officers-row, .csg-officers-row {
            flex: 0 0 16.66%; /* 100% / 6 = 16.66% width per card */
            max-width: 16.66%;
        }

        /* Media query for mobile screens (2 cards per row) */
        @media (max-width: 768px) {
            .officers-link {
                margin-left: 0px !important;
            }
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
                            <a href="../esas_student/dashboard.php" class="nav-link left-sidebar text-dark" id="dashboard">
                                <i class="fas fa-chart-line"></i> Dashboard
                            </a>
                        </li>
                        <li>
                            <a href="../esas_student/all_clubs.php" class="nav-link left-sidebar text-dark active" aria-current="page" id="all-clubs">
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
                    <div id="divpr_requesdetails" class="table-responsive px-0">
                        <div class="row g-0 p-4 px-2 pt-2 h-100">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start mt-2 ms-2 me-1 mb-1 me-sm-2">
                                        <h3 class="text-muted">Student Club Organizations</h3>
                                        <div class="officers-link mt-0 mt-sm-0 me-1 me-sm-4">
                                            <!-- <a href="../esas_student/officers/csg_officers.php" class="d-inline-block">
                                                <i class="fas fa-user-tie"></i> CSG Officers</a>
                                            <a href="../esas_student/officers/sbo_officers.php" class="ms-2 d-inline-block">
                                                <i class="fas fa-user-tie"></i> SBO Officers</a> -->
                                            <a href="../esas_student/officers/officers.php" class="ms-2 d-inline-block">
                                                <i class="fas fa-user-tie"></i> CSG and SBO Officers</a>
                                        </div>
                                    </div>

                                    <table class="table table-bordered table-striped" style="background-color: #f9f9f9;">
                                        <thead>
                                            <tr>
                                                <th colspan="9">
                                                    <div class="row">
                                                        <div class="col-12 col-md-8 d-flex align-items-center">
                                                            <select id="departmentSelect" class="form-select me-2" style="width: 20%;">
                                                                <optgroup label="Club Recommendations">
                                                                    <option value="" selected>For All</option>
                                                                    <option value="TEP">For TEP</option>
                                                                    <option value="BSBA">For BSBA</option>
                                                                    <option value="CCS">For CCS</option>
                                                                </optgroup>
                                                            </select>
                                                            <input id="clubSearch" class="form-control" type="search" placeholder="Search for clubs..." aria-label="Search">
                                                        </div>
                                                        <div class="col-12 col-md-4 d-flex align-items-center justify-content-center mt-2">
                                                            <h6 id="rowCountDisplay">Showing 0 / 0 Clubs</h6>
                                                        </div>
                                                    </div>
                                                </th>
                                            </tr>
                                        </thead>
                                    </table>

                                    <div id="noResultsMessage" class="alert alert-danger p-2 ps-3" style="display: none;">
                                        <em>No results found.</em>
                                    </div>

                                    <div class="row mainbar g-0">
                                        <div class="row g-0 mt-0" id="allClubsContainer">
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

        // Function to fetch clubs data
        function fetchClubs(department = null) {
            let url = '/esas/esas_student/apis/clubs-api.php';
            if (department) {
                url += `?department=${encodeURIComponent(department)}`;
            }

            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    const allClubsContainer = document.getElementById('allClubsContainer');
                    const rowCountDisplay = document.getElementById('rowCountDisplay');
                    const totalRows = data.length;

                    // Update the row count display
                    rowCountDisplay.textContent = `Showing ${totalRows} / ${totalRows} Clubs`;

                    // Clear previous club cards
                    allClubsContainer.innerHTML = '';

                    if (totalRows > 0) {
                        data.forEach(club => {
                            const memberText = club.membersCount === 1 ? '1 member' : `${club.membersCount} members`;
                            const cardHTML = `
                                <div class="col-md-4 card-container club-card" data-club-name="${club.clubName}">
                                    <div class="card card-img-only-all">
                                        <small data-toggle="tooltip" title="${memberText}">
                                            <i class="fa fa-user mr-1"></i>${club.membersCount}
                                        </small>
                                        <a href="/esas/esas_student/club_info.php?club_id=${club.club_id}&club_name=${encodeURIComponent(club.clubName)}">
                                            <img src="/esas/esas_admin/images/${club.coverPhoto}" alt="Cover Photo">
                                            <div class="overlay-text">
                                                <h4 class="club-name">${club.clubName}</h4>
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

                    // Reinitialize search functionality after clubs are loaded
                    initializeSearch();
                })
                .catch(error => {
                    console.error('Error fetching clubs:', error);
                    const allClubsContainer = document.getElementById('allClubsContainer');
                    allClubsContainer.innerHTML = '<p>Failed to fetch clubs. Please try again later.</p>';
                });
        }

        // Function to initialize search functionality
        function initializeSearch() {
            const searchInput = document.getElementById('clubSearch');
            const clubCards = document.querySelectorAll('.club-card');
            const rowCountDisplay = document.getElementById('rowCountDisplay');
            const noResultsMessage = document.getElementById('noResultsMessage');

            searchInput.addEventListener('input', function() {
                const searchTerm = searchInput.value.trim().toLowerCase();
                let visibleClubCount = 0;

                clubCards.forEach(card => {
                    const clubNameElement = card.querySelector('.club-name');
                    const originalClubName = card.getAttribute('data-club-name');
                    const originalClubNameLower = originalClubName.toLowerCase();

                    if (searchTerm === '') {
                        clubNameElement.innerHTML = originalClubName; // Reset the name if search is empty
                        card.style.display = '';
                        visibleClubCount++; // Count visible cards
                    } else {
                        if (originalClubNameLower.includes(searchTerm)) {
                            card.style.display = '';
                            visibleClubCount++;
                            highlightText(clubNameElement, searchTerm); // Highlight the matching text
                        } else {
                            card.style.display = 'none';
                        }
                    }
                });

                // Update the row count to reflect the visible clubs
                const totalRows = clubCards.length; // Total number of cards available
                rowCountDisplay.textContent = `Showing ${visibleClubCount} / ${totalRows} Records`;

                // Show or hide the noResultsMessage
                if (visibleClubCount === 0 && searchTerm !== '') {
                    noResultsMessage.style.display = 'block'; // Show message
                } else {
                    noResultsMessage.style.display = 'none'; // Hide message
                }
            });
        }   

        // Fetch all clubs on page load
        window.onload = function() {
            const storedDepartment = localStorage.getItem('selectedDepartment');
            fetchClubs(storedDepartment); // Fetch clubs based on stored department
            if (storedDepartment) {
                document.getElementById('departmentSelect').value = storedDepartment; // Set the dropdown value
            } else {
                fetchClubs(); // Default fetch all clubs
            }
        };

        // Add department selection functionality
        const departmentSelect = document.getElementById('departmentSelect');
        departmentSelect.addEventListener('change', function() {
            const selectedDepartment = this.value;
            localStorage.setItem('selectedDepartment', selectedDepartment); // Store the selected department
            fetchClubs(selectedDepartment); // Fetch clubs for the selected department
        });

        // Highlight matching text
        function highlightText(element, term) {
            const originalText = element.textContent;
            const regex = new RegExp(`(${term})`, 'gi');
            element.innerHTML = originalText.replace(regex, `<span style="background-color: rgba(173, 216, 230, 0.7); border-radius: 10%;">$1</span>`);
        }
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