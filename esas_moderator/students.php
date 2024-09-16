<?php
session_start();
require_once "../config.php";  // Assuming this file holds your PDO connection

if (!isset($_SESSION['moderator_id'])) {
    echo "Moderator ID is not set in the session.";
    exit;
}

$moderator_id = $_SESSION['moderator_id']; // Get moderator ID from session

// Fetch distinct students with an active status in tbl_registration
$sql = "
    SELECT DISTINCT s.student_id, s.firstName, s.middleName, s.lastName, s.age, s.birthday, s.gender, s.instiEmail, s.phoneNumber, s.department, s.course, s.year, s.street, s.barangay, s.municipality, s.province, s.zipcode, s.profilePic
    FROM tbl_students s
    JOIN tbl_registration r ON s.student_id = r.student_id
    WHERE r.status = 'active'
";

$stmt = $pdo->prepare($sql);

$stmt->execute();

$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

unset($stmt);
unset($pdo);
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>eSAS - Students</title>
    <link href="../assets/css/jquery.dataTables.min.css" rel="stylesheet" />
    <script src="../assets/js/all.js" crossorigin="anonymous"></script>
    <script src="../assets/js/jquery-3.6.0.js"></script>
    <link href="../assets/css/styles.css" rel="stylesheet" />
    <link href="../assets/img/nbsclogo.png" rel="icon">
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

    </style>
</head>
<body>
    
    <div class="container-fluid">
        <div class="row g-0 h-100">
            <nav class="navbar navbar-expand-lg navbar-dark bg-primary px-2">
                <a class="navbar-brand ps-2" href="#">
                    <img src="../assets/img/SAS_LOGO.png" style="height: 0.3in;">
                    eSAS - Moderator</a>
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
                        <a href="../esas_moderator/dashboard.php" class="nav-link left-sidebar text-dark" id="all-clubs">
                            <i class="fas fa-chart-line"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="../esas_moderator/my_clubs.php" class="nav-link left-sidebar text-dark" aria-current="page" id="my-clubs">
                            <i class="fas fa-university"></i> My Clubs
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="../esas_moderator/students.php" class="nav-link left-sidebar text-dark active" aria-current="page" id="my-clubs">
                            <i class="fas fa-users"></i> Students
                        </a>
                    </li>
                    <li>
                        <a href="../esas_moderator/pending_approvals.php" class="nav-link left-sidebar text-dark" id="pending-approvals">
                            <i class="fas fa-hourglass-half"></i> Pending Approvals
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




                   

<div class="wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="search-container-top">
                    <input class="form-control" type="search" placeholder="Search" aria-label="Search" id="searchInput">
                    <h6 class="text-center mt-2" id="recordCount">Showing <span id="visibleRows"><?php echo count($students); ?></span> / <span id="totalRows"><?php echo count($students); ?></span> Records</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Profile Picture</th>
                                <th>#</th>
                                <th>First Name</th>
                                <th>Middle Name</th>
                                <th>Last Name</th>
                                <th>Age</th>
                                <th>Birthday</th>
                                <th>Gender</th>
                                <th>Email</th>
                                <th>Phone Number</th>
                                <th>Department</th>
                                <th>Course</th>
                                <th>Year</th>
                                <th>Street</th>
                                <th>Barangay</th>
                                <th>Municipality</th>
                                <th>Province</th>
                                <th>Zipcode</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="studentsTbody">
                            <?php if (count($students) > 0): ?>
                                <?php foreach ($students as $row): ?>
                                    <tr>
                                        <td>
                                            <?php if ($row['profilePic']): ?>
                                                <img src="<?php echo htmlspecialchars('/esas/esas_student/images/' . $row['profilePic']); ?>" alt="Profile Picture" class="rounded-circle mr-2" width="40" height="40">
                                            <?php else: ?>
                                                No Image
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($row['student_id']); ?></td>
                                        <td><?php echo htmlspecialchars($row['firstName']); ?></td>
                                        <td><?php echo htmlspecialchars($row['middleName']); ?></td>
                                        <td><?php echo htmlspecialchars($row['lastName']); ?></td>
                                        <td><?php echo htmlspecialchars($row['age']); ?></td>
                                        <td><?php echo htmlspecialchars($row['birthday']); ?></td>
                                        <td><?php echo htmlspecialchars($row['gender']); ?></td>
                                        <td><?php echo htmlspecialchars($row['instiEmail']); ?></td>
                                        <td><?php echo htmlspecialchars($row['phoneNumber']); ?></td>
                                        <td><?php echo htmlspecialchars($row['department']); ?></td>
                                        <td><?php echo htmlspecialchars($row['course']); ?></td>
                                        <td><?php echo htmlspecialchars($row['year']); ?></td>
                                        <td><?php echo htmlspecialchars($row['street']); ?></td>
                                        <td><?php echo htmlspecialchars($row['barangay']); ?></td>
                                        <td><?php echo htmlspecialchars($row['municipality']); ?></td>
                                        <td><?php echo htmlspecialchars($row['province']); ?></td>
                                        <td><?php echo htmlspecialchars($row['zipcode']); ?></td>
                                        <td>
                                            <a href="crud/student_read.php?student_id=<?php echo htmlspecialchars($row['student_id']); ?>" class="mr-3" title="View Record" data-toggle="tooltip"><span class="fa fa-eye"></span></a>
                                            <a href="crud/student_update.php?student_id=<?php echo htmlspecialchars($row['student_id']); ?>" class="mr-3" title="Update Record" data-toggle="tooltip"><span class="fa fa-pencil-alt"></span></a>
                                            <a href="crud/student_delete.php?student_id=<?php echo htmlspecialchars($row['student_id']); ?>" title="Delete Record" data-toggle="tooltip"><span class="fa fa-trash"></span></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="18" class="text-center">No students found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div id="noResultsMessage" class="alert alert-danger" style="display:none;"><em>No results found.</em></div>
            </div> <!-- Close col-md-12 -->
        </div> <!-- Close row -->
    </div> <!-- Close container-fluid -->
</div>

<div class="fixed-bottom">
    <div class="container text-center align-items-center justify-content-center">
        <div class="row">
            <div class="search-container-bottom col-md-7">
                <input class="form-control" type="search" placeholder="Search" aria-label="Search" id="searchInputFixed">
            </div>
            <div class="col-md-5 d-flex align-items-center justify-content-center mt-2">
                <h6 id="recordCountFixed" class="record-count mb-0">Showing <span id="visibleRowsFixed"></span> / <span id="totalRowsFixed"></span> Records</h6>
            </div>
        </div>
    </div>
</div>


    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


    <script>
        $(document).ready(function() {
            function updateRecordCount(visibleRows) {
                var totalRows = $("#questionsTbody tr").length;
                if (typeof visibleRows === 'undefined') {
                    visibleRows = totalRows; // Initialize visibleRows if not provided
                }
                $("#visibleRows").text(visibleRows);
                $("#totalRows").text(totalRows);

                $("#visibleRowsFixed").text(visibleRows);
                $("#totalRowsFixed").text(totalRows);

                if (visibleRows === 0) {
                    $("#noResultsMessage").show();
                } else {
                    $("#noResultsMessage").hide();
                }
            }

            function highlightText(text, searchTerm) {
                if (searchTerm.trim() === '') {
                    return text;
                }
                var regex = new RegExp('(' + searchTerm + ')', 'gi');
                return text.replace(regex, '<span class="highlight">$1</span>');
            }

            function filterRows() {
                var valueTop = $("#searchInput").val().toLowerCase();
                var valueBottom = $("#searchInputFixed").val().toLowerCase();
                var value = valueTop || valueBottom;
                var visibleRows = 0;

                $("#questionsTbody tr").each(function() {
                    var $row = $(this);
                    var isVisible = false;

                    // Loop through each cell, except the last one (the action column)
                    $row.find('td').each(function(index) {
                        var $cell = $(this);

                        // Skip the last column (adjust index as needed for your layout)
                        if (index < $row.find('td').length - 1) {
                            var cellText = $cell.text().toLowerCase();

                            if (cellText.indexOf(value) > -1) {
                                isVisible = true;
                            }

                            // Highlight the matched text
                            $cell.html(highlightText($cell.text(), value));
                        }
                    });

                    $row.toggle(isVisible);

                    if (isVisible) {
                        visibleRows++;
                    }
                });

                updateRecordCount(visibleRows);
            }

            // Initial update of record count
            updateRecordCount();

            // Event handlers for search fields
            $("#searchInput").on("keyup", function() {
                $("#searchInputFixed").val($(this).val()); // Sync bottom search field
                filterRows();
            });

            $("#searchInputFixed").on("keyup", function() {
                $("#searchInput").val($(this).val()); // Sync top search field
                filterRows();
            });

            $(window).on('scroll', function() {
                var topSearchButtonHeight = $('.fixed-back-button').outerHeight();
                if ($(this).scrollTop() > topSearchButtonHeight) {
                    $('.fixed-bottom').fadeIn(); // Show bottom search bar
                } else {
                    $('.fixed-bottom').fadeOut(); // Hide bottom search bar
                }

                if ($(this).scrollTop() > 0) {
                    $('.fixed-back-button').addClass('scrolled');
                } else {
                    $('.fixed-back-button').removeClass('scrolled');
                }

                var button = document.querySelector('.fixed-back-button');
                if (window.scrollY > 50) { // Adjust scroll threshold as needed
                    button.classList.add('scrolled');
                } else {
                    button.classList.remove('scrolled');
                }
            });
        });


    </script>









                            <!-- CHARTS AND DIAGRAMS END -->
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