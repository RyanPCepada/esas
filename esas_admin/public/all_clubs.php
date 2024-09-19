<?php 
session_start();
require_once "../../config.php";  // Assuming this file holds your PDO connection

if (!isset($_SESSION['admin_id'])) {
    echo "Admin ID is not set in the session.";
    exit;
}

$admin_id = $_SESSION['admin_id']; // Get admin ID from session

try {
    // Use the existing PDO instance from config.php
    global $pdo;

    // Prepare and execute the SQL statement
    $sql = "SELECT email FROM tbl_admin WHERE admin_id = :admin_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':admin_id', $admin_id, PDO::PARAM_INT);
    $stmt->execute();
    
    // Fetch the result
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if a result was found
    if ($result) {
        $email = strtoupper($result['email']);
    } else {
        // Handle the case where no data is found
        $email = "UNKNOWN";
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
    <title>eSAS - Dashboard</title>
    <link href="../../assets/css/jquery.dataTables.min.css" rel="stylesheet" />
    <script src="../../assets/js/all.js" crossorigin="anonymous"></script>
    <script src="../../assets/js/jquery-3.6.0.js"></script>
    <link href="../../assets/css/styles.css" rel="stylesheet" />
    <link href="../../assets/img/nbsclogo.png" rel="icon">
    <style>
        .nav-link.active {
          color: white !important;
          background-color: black;
        }
        .left-sidebar {
            font-size: 16px;
            text-align: start;
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
        
        

        @keyframes waveIn {
            0% {
                opacity: 0;
                transform: translateY(5px) scale(0.95); /* Adjusted Y translation */
            }
            50% {
                opacity: 0.5;
                transform: translateY(-2px) scale(1.05); /* Peak of the wave, adjusted */
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .card {
            /* Ensure your card styles are here */
        }

        

        .icon-style {
            position: absolute;
            font-size: 18px;
            width: 8%;
            background-color: #4682B4; /*steel blue*/
            background-color: #89CFF0; /* baby blue */
            background-color: #70B9E6; /*darker baby blue*/
            color: white;
            /* color: #6A5ACD; slate blue */
            border-radius: 50%;
            align-self: end;
        }


        .col-md-7 {
            text-align: justify;
            text-indent: 30px;
        }
        .club-row {
            transition: background-color 0.3s ease;
        }

        .club-row:hover {
            background-color: #e0e0e0;
        }
        .moderator-list {
            padding: 0;
            margin-bottom: 10px;
        }

        .moderator-list h6 {
            line-height: .8;
        }

        @media (max-width: 768px) {
            .col-auto {
                width: auto;
            }
            .icon-style {
                width: 6% !important;
            }
        }

    </style>
</head>
<body>
    
    <div class="container-fluid">
        <div class="row g-0 h-100">
            <nav class="navbar navbar-expand-lg navbar-dark bg-primary px-2">
                <a class="navbar-brand ps-2" href="#">
                    <img src="../assets/img/SAS_LOGO.png" style="height: 0.3in;">
                    eSAS - Admin</a>
                </button>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#main_nav" aria-expanded="true">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="navbar-collapse collapse hide" id="main_nav">
                    <div class="navbar-collapse flex-grow-1 text-right" id="sampleid" style="padding-left: 20px">
                        <?php include '../nav/nav_main.php' ?>
                    </div>
                </div>
            </nav>
            <!-- LEFT SIDEBAR -->
            <div class="col-12 col-md-2 ps-0 pt-3 border-end">

            <div class="d-flex flex-column flex-shrink-0 p-3 bg-body-tertiary">
                <ul class="nav nav-pills flex-column mb-auto">
                    <li>
                        <a href="../../esas_admin/public/dashboard.php" class="nav-link left-sidebar text-dark" id="dashboard">
                            <i class="fas fa-chart-line"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="../../esas_admin/public/all_clubs.php" class="nav-link left-sidebar text-dark active" aria-current="page" id="all-clubs">
                            <i class="fas fa-university"></i> All Clubs
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="../esas_admin/public/moderators.php" class="nav-link left-sidebar text-dark" id="moderators">
                            <i class="fa fa-user-shield"></i> Moderators
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="../../esas_admin/public/students.php" class="nav-link left-sidebar text-dark" aria-current="page" id="students">
                            <i class="fas fa-users"></i> Students
                        </a>
                    </li>
                    <li>
                        <a href="../../esas_admin/public/club_requests.php" class="nav-link left-sidebar text-dark" id="club-requests">
                            <i class="fas fa-envelope"></i> Club Requests
                        </a>
                    </li>
                </ul>
            </div>

            </div>
            <!-- LEFT SIDEBAR END -->


            
            
            <!-- MAINPAGE BAR -->
            <div class="col-12 col-md-10 bg-lgrey auto-scroll">
                <div class="row g-0 h-100">
                    <div class="row g-0 p-4 px-2 pt-2 h-100">

                        <!-- THE MAIN PAGE START -->
                        <div class="card p-2">

                            <!-- ALL CLUB CARDS START -->
                            <div class="row card-row1 col-md-12 mb-1" style="border: 1px solid transparent; margin: 0;">

                                <div class="mt-1 mb-3 clearfix text-end">
                                    <a href="../public/crud/all_clubs/club_create.php" class="btn btn-danger pull-right">
                                        <i class="fa fa-plus"></i>Add New Club</a>
                                </div>

                                <?php
                                // Include config file
                                require_once "../../config.php";

                                // SQL query to fetch all clubs with related information, moderators, member count, and actions
                                $sql = "SELECT 
                                            c.club_id,
                                            c.clubName, 
                                            c.information, 
                                            c.coverPhoto, 
                                            GROUP_CONCAT(DISTINCT CONCAT(m.firstName, ' ', m.lastName) SEPARATOR '|||') AS moderators,
                                            (SELECT COUNT(DISTINCT r.student_id) FROM tbl_registration r WHERE r.club_id = c.club_id AND r.status = 'active') AS member_count,
                                            c.dateAdded
                                        FROM tbl_clubs c
                                        LEFT JOIN tbl_clubs_and_moderators cm ON c.club_id = cm.club_id
                                        LEFT JOIN tbl_moderators m ON cm.moderator_id = m.moderator_id
                                        GROUP BY c.club_id
                                        ORDER BY c.dateAdded ASC";

                                if ($result = $pdo->query($sql)) {
                                    $totalRows = $result->rowCount();
                                    $rowCount = 0; // To keep track of row number for striping

                                    if ($totalRows > 0) {
                                        echo '
                                        <table class="table table-bordered table-striped" style="background-color: #f9f9f9;"> <!-- Lighter stripe style -->
                                            <thead>
                                                <tr>
                                                    <th> <input id="clubSearch" class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search"></th>
                                                    <th class="text-center" colspan="8">
                                                        <h6 id="rowCountDisplay">Showing ' . $totalRows . ' / ' . $totalRows . ' Records</h6>
                                                    </th>
                                                </tr>
                                            </thead>
                                        </table>
                                        ';

                                        // Populate the rows using the row template
                                        while ($row = $result->fetch()) {
                                            $formattedDate = date('F j, Y', strtotime($row['dateAdded']));
                                            $moderators = explode('|||', $row['moderators']);
                                            $moderatorCount = count($moderators);
                                        
                                            // Generate moderator list
                                            $moderatorList = '';
                                            foreach ($moderators as $moderator) {
                                                $moderatorList .= '<h6>' . htmlspecialchars($moderator) . '</h6>';
                                            }
                                        
                                            $moderatorLabel = ($moderatorCount == 1) ? 'Moderator:' : 'Moderators:';
                                            $memberText = ($row['member_count'] == 1) ? 'member' : 'members';
                                        
                                            // Alternate row colors
                                            $rowStyle = ($rowCount % 2 == 0) ? 'background-color: #f2f2f2;' : 'background-color: #ffffff;';
                                            $rowCount++;
                                        
                                            echo '
                                            <div class="row ms-0 mb-3 p-3 club-row" style="' . $rowStyle . '">
                                                <!-- Club Cover Photo and Details -->
                                                <div class="col-md-4">
                                                    <div class="club-cover-photo" style="text-align: center;">
                                                        <img src="/esas/esas_admin/images/' . htmlspecialchars($row['coverPhoto'] ? $row['coverPhoto'] : 'default-cover.jpg') . '" 
                                                            alt="' . htmlspecialchars($row['clubName']) . ' cover photo" 
                                                            style="width: 100%; max-width: 100%; height: auto; border-radius: 5px; box-shadow: 0 5px 10px rgba(0, 0, 0, .5);">
                                                        <div>
                                                            <h4 class="text-muted mt-3">' . htmlspecialchars($row['clubName']) . '</h4>
                                                        </div>
                                                        <h6 class="text-muted mt-2 mb-2">' . $moderatorLabel . '</h6>
                                                        <div class="moderator-list">' . $moderatorList . '</div>
                                                        <hr class="m-1">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <h7 class="text-muted mb-0">Created <span class="creation-date">' . htmlspecialchars($formattedDate) . '</span></h7>
                                                            <div class="member-count">' . htmlspecialchars($row['member_count']) . ' ' . $memberText . '</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Club Information -->
                                                <div class="col-md-7">
                                                    <div>' . htmlspecialchars($row['information']) . '</div>
                                                </div>
                                                <!-- Actions -->
                                                <div class="col-md-1 text-center">
                                                    <a href="../public/crud/all_clubs/club_read.php?club_id=' . htmlspecialchars($row['club_id']) . '" class="mr-2" title="View Record" data-toggle="tooltip"><span class="fa fa-eye"></span></a>
                                                    <a href="../public/crud/all_clubs/club_update.php?club_id=' . htmlspecialchars($row['club_id']) . '" class="mr-2" title="Update Record" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>
                                                    <a href="../public/crud/all_clubs/club_delete.php?club_id=' . htmlspecialchars($row['club_id']) . '" title="Delete Record" data-toggle="tooltip"><span class="fa fa-trash"></span></a>
                                                </div>
                                            </div>';
                                        }
                                        
                                        

                                        // Free result set
                                        unset($result);
                                    } else {
                                        echo '<div class="alert alert-danger"><em>No clubs were found.</em></div>';
                                    }
                                } else {
                                    echo "Oops! Something went wrong. Please try again later.";
                                }
                                ?>

                            </div>
                            <!-- ALL CLUB CARDS END -->

                            <script>
                                // Wait for the DOM to load
                                document.addEventListener('DOMContentLoaded', function () {
                                    const searchInput = document.getElementById('clubSearch');
                                    const clubRows = document.querySelectorAll('.club-row');
                                    const rowCountDisplay = document.getElementById('rowCountDisplay');
                                    const totalRows = clubRows.length; // Total number of rows

                                    // Initial display of total rows
                                    rowCountDisplay.textContent = `Showing ${totalRows} / ${totalRows} Records`;

                                    searchInput.addEventListener('input', function () {
                                        const searchTerm = searchInput.value.toLowerCase();
                                        let visibleRowCount = 0; // To track how many rows are visible

                                        clubRows.forEach(function (row) {
                                            const clubNameElement = row.querySelector('h4');
                                            const moderatorNames = row.querySelectorAll('h6');
                                            const clubInfoElement = row.querySelector('.col-md-7');
                                            const memberCountElement = row.querySelector('.member-count');
                                            const creationDateElement = row.querySelector('.creation-date');

                                            const clubName = clubNameElement.textContent.toLowerCase();
                                            const clubInfo = clubInfoElement.textContent.toLowerCase();
                                            const memberCount = memberCountElement.textContent.toLowerCase();
                                            const creationDate = creationDateElement.textContent.toLowerCase();

                                            let moderatorMatch = false;
                                            let clubInfoMatch = false;
                                            let memberCountMatch = false;
                                            let creationDateMatch = false;

                                            // Remove previous highlights
                                            resetHighlight(clubNameElement);
                                            moderatorNames.forEach(resetHighlight);
                                            resetHighlight(clubInfoElement);
                                            resetHighlight(memberCountElement);
                                            resetHighlight(creationDateElement);

                                            // Check if any moderator matches the search term
                                            moderatorNames.forEach(function (moderator) {
                                                const moderatorText = moderator.textContent.toLowerCase();
                                                if (moderatorText.includes(searchTerm)) {
                                                    moderatorMatch = true;
                                                    highlightText(moderator, searchTerm);
                                                }
                                            });

                                            // Check if the club information matches the search term
                                            if (clubInfo.includes(searchTerm)) {
                                                clubInfoMatch = true;
                                                highlightText(clubInfoElement, searchTerm);
                                            }

                                            // Check if the member count matches the search term
                                            if (memberCount.includes(searchTerm)) {
                                                memberCountMatch = true;
                                                highlightText(memberCountElement, searchTerm);
                                            }

                                            // Check if the creation date matches the search term
                                            if (creationDate.includes(searchTerm)) {
                                                creationDateMatch = true;
                                                highlightText(creationDateElement, searchTerm);
                                            }

                                            // Check if the club name, any moderator, or the information matches
                                            if (clubName.includes(searchTerm) || moderatorMatch || clubInfoMatch || memberCountMatch || creationDateMatch) {
                                                row.style.display = ''; // Show the row
                                                visibleRowCount++; // Increment visible row count
                                                if (clubName.includes(searchTerm)) {
                                                    highlightText(clubNameElement, searchTerm);
                                                }
                                            } else {
                                                row.style.display = 'none'; // Hide the row
                                            }
                                        });

                                        // Update the row count display
                                        rowCountDisplay.textContent = `Showing ${visibleRowCount} / ${totalRows} Records`;
                                    });

                                    // Function to highlight matching text
                                    function highlightText(element, term) {
                                        const text = element.textContent;
                                        const regex = new RegExp(`(${term})`, 'gi'); // Create a regex to match all occurrences of the term
                                        const highlightedHTML = text.replace(regex, '<span style="background-color: lightblue; color: #0033cc;">$1</span>');

                                        element.innerHTML = highlightedHTML; // Replace the content with highlighted text
                                    }

                                    // Function to reset highlight (removing the span tag)
                                    function resetHighlight(element) {
                                        const html = element.innerHTML;
                                        const regex = /<span style="background-color: lightblue; color: #0033cc;">(.*?)<\/span>/gi;
                                        const resetHTML = html.replace(regex, '$1');

                                        element.innerHTML = resetHTML; // Replace the content with the original text
                                    }
                                });

                            </script>
                        </div>
                        <!-- THE MAIN PAGE END -->


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
        fetch('/esas/esas_moderator/apis/clubs-api.php')
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
                                    <a href="/esas/esas_moderator/club_info.php?club_id=${club.club_id}&club_name=${encodeURIComponent(club.clubName)}">
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


        // JavaScript to Animate Cards
        document.addEventListener('DOMContentLoaded', function() {
            function animateCards(cards) { 
                cards.forEach((card, index) => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(5px) scale(0.95)'; // Adjusted Y translation
                    card.style.transition = 'none'; // Disable transition for reset

                    void card.offsetWidth; // Trigger reflow

                    card.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';
                    
                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0) scale(1)';
                        card.style.animation = `waveIn 0.6s ease-out forwards`;
                    }, index * 100); // Staggered delay
                });
            }

            // Select only the upper cards
            const upperCards = document.querySelectorAll('.card-row1 .card');
            animateCards(upperCards);
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