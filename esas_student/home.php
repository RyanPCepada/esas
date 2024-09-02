<?php
session_start();
require_once "../config.php";

// Fetch the current student's ID
$student_id = $_SESSION['student_id'];

// Initialize variables and error messages
$postContent = "";
$postContent_err = "";
$club_id = ""; // Initialize club_id variable
$clubName = ""; // Initialize clubName variable
$coverPhoto = ""; // Initialize coverPhoto variable
$profilePic = ""; // Initialize profilePic variable

try {
    // Use the existing PDO instance from config.php
    global $pdo;

    // Fetch the student's club_id and profile picture
    $sql = "SELECT club_id, profilePic FROM tbl_registered_students WHERE student_id = :student_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":student_id", $student_id, PDO::PARAM_INT);
    $stmt->execute();
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($student) {
        $club_id = $student['club_id']; // Set club_id from student
        $profilePic = $student['profilePic']; // Set profilePic from student

        // Fetch the club name and cover photo using the club_id
        $sql = "SELECT clubName, coverPhoto FROM tbl_clubs WHERE club_id = :club_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":club_id", $club_id, PDO::PARAM_INT);
        $stmt->execute();
        $club = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($club) {
            $clubName = $club['clubName']; // Set clubName from clubs table
            $coverPhoto = $club['coverPhoto']; // Set coverPhoto from clubs table
        }
    }

    // Fetch the student's full name
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eSAS - Student Home</title>
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    
    <link href="../assets/css/jquery.dataTables.min.css" rel="stylesheet" />
    <script src="../assets/js/all.js" crossorigin="anonymous"></script>
    <script src="../assets/js/jquery-3.6.0.js"></script>
    <link href="../assets/css/styles.css" rel="stylesheet" />
    <link href="../assets/img/nbsclogo.png" rel="icon">

    <style>
        /* body {
            font: 14px Helvetica;
        } */
        .wrapper {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 0px;
            min-height: 500px;
            /* height: 1000px;
            overflow-y: transparent;
            overflow-x: hidden; */
        }
        .container-fluid {
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
        .active-nav {
            background-color: rgba(255, 255, 255, 0.25);
            border-radius: 3px;
            padding-left: 15px;
        }
        .cover-photo-container {
            position: relative;
        }
        .cover-photo-container img {
            display: block;
            width: 100%;
            height: auto;
            object-fit: cover; /* Ensure the image covers the container */
            object-position: center 20%; /* Vertically center the image, accounting for the 20% crop */
            border-radius: 1%;
            margin-top: -15px;
        }
        .cover-photo-container::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 50%;
            background: linear-gradient(to bottom, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 1) 100%);
            /* background: linear-gradient(to bottom, rgba(0, 0, 0, 0) 0%, rgba(0, 0, 0, 0.5) 100%); */
        }
        .overlay-text {
            position: absolute;
            color: white;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.6); /* Shadow effect */
            padding: 10px;
            border-radius: 5px;
            line-height: .5; /* Adjust line height for closer spacing */
            margin-top: -50px;
        }
        .overlay-text h4 {
            line-height: .9; /* Adjust line height for closer spacing */
        }
        #icon_announcement {
            margin-top: -30px;
            margin-left: -5px;
            animation: zoomAndWave 1.2s ease-in-out;
            animation-play-state: paused; /* Start with the animation paused */
        }

        .card {
            padding: 0px;
        }
        .card-header {
            padding: 10px;
        }
        .moderator-name {
            margin-left: 10px !important;
        }
        .card-body {
            padding: 15px;
        }
        .card-footer {
            padding: 10px;
        }
        .comment-form {
            padding: 5px;
        }
        
        @keyframes zoomAndWave {
            0% {
                transform: rotate(0deg) scale(1);
            }
            25% {
                transform: rotate(-10deg) scale(1.05);
            }
            50% {
                transform: rotate(10deg) scale(1.05);
            }
            75% {
                transform: rotate(-10deg) scale(1.05);
            }
            100% {
                transform: rotate(0deg) scale(1);
            }
        }
        
        .card-footer .form-control {
            margin: 5px 0px !important;
            border-radius: 20px;
        }
        .card-footer img, .comment img {
            border-radius: 50%;
            border: 2px solid lightblue;
        }
        .send-icon {
            color: #007bff;
            font-size: 22px;
            margin-left: 5px;
            cursor: pointer;
            transition: transform 0.2s ease-in-out;
        }
        .send-icon:hover {
            transform: scale(1.1);
            color: #0056b3;
        }

        .comsec {
            margin-left: 10px;
        }

        @media (min-width: 768px) {
            .overlay-text {
                padding: 10px;
                border-radius: 5px;
                line-height: .5; /* Adjust line height for closer spacing */
                margin-top: -80px;
            }
            .overlay-text h4 {
                font-size: 40px;
                margin-right: 10px;
                line-height: 1.2; /* Adjust line height for closer spacing */
            }

        }
    </style>
</head>
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


    <div class="wrapper">
        <div class="container-fluid">
            <div class="cover-photo-container">
                <img src="/esas/esas_moderator/images/<?php echo htmlspecialchars($coverPhoto); ?>" alt="Cover Photo" class="img-fluid mb-3">
            </div>
            <div class="overlay-text">
                <div class="d-flex align-items-center">
                    <h4><?php echo htmlspecialchars($clubName); ?></h4>
                </div>
            </div>
            <hr>
            <div class="post_list">
                <div class="row">
                    <div class="col-12 mb-3">
                        <div class="d-flex align-items-center bg-info text-white p-2 rounded">
                            <img src="./icons/ICON_ANNOUNCEMENT.png" height="75" class="d-inline-block align-top" id="icon_announcement" alt="Announcement Icon">
                            <h4 class="mb-0">Announcements and Updates</h4>
                        </div>
                    </div>
                </div>
                <div class="row" id="postsContainer">
                    <!-- Posts will be dynamically inserted here -->
                </div>
            </div>
        </div>
    </div>

     <!-- <?php include 'assets/components/modals.php' ?> -->
     <script src="../assets/js/jquery.dataTables.min.js"></script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/global_script.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Function to format a date as "Month Day, Year"
            function formatDate(dateString) {
                const options = { year: 'numeric', month: 'long', day: 'numeric' };
                return new Intl.DateTimeFormat('en-US', options).format(new Date(dateString));
            }

            // Function to format a time as "hh:mm am/pm"
            function formatTime(timeString) {
                const options = { hour: '2-digit', minute: '2-digit', hour12: true };
                return new Intl.DateTimeFormat('en-US', options).format(new Date(`1970-01-01T${timeString}`));
            }

            // Function to fetch and display posts
            function fetchPosts() {
                fetch('/esas/esas_student/apis/posts-api.php')
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Fetched posts:', data); // Log the fetched data
                        const postsContainer = document.getElementById('postsContainer');
                        if (data && data.length > 0) {
                            postsContainer.innerHTML = ''; // Clear existing posts
                            data.forEach(post => {
                                const postDate = formatDate(post.dateAdded.split(' ')[0]);
                                const postTime = formatTime(post.dateAdded.split(' ')[1]);

                                // Determine comment text
                                const commentText = post.numberOfComments === 1 ? '1 comment' : `${post.numberOfComments || 0} comments`;

                                const postHTML = `
                                    <div class="col-md-12 mb-3">
                                        <div class="card" id="card_posts">
                                            <div class="card-header d-flex align-items-start">
                                                <img src="/esas/esas_moderator/images/${post.profilePic}" alt="${post.fullName}" class="rounded-circle mr-3" width="50" height="50">
                                                <div class="moderator-name">
                                                    <h5 class="card-title mb-1">${post.fullName}</h5>
                                                    <p class="text-muted mb-0">${postDate} @ ${postTime}</p>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <p class="card-text">${post.post}</p>
                                            </div>
                                            <div class="card-footer d-flex align-items-center">
                                                <img src="/esas/esas_student/images/<?php echo htmlspecialchars($profilePic); ?>" alt="Profile Picture" class="rounded-circle mr-2" width="40" height="40">
                                                <form class="comment-form d-flex align-items-center w-100" method="POST" action="../esas_student/actions/send_comment_action.php" data-post-id="${post.post_id}">
                                                    <input type="hidden" name="post_id" value="${post.post_id}">
                                                    <input type="text" class="form-control" name="comment" placeholder="Add a comment..." required>
                                                    <button type="submit" class="btn btn-link ml-2 p-0">
                                                        <i class="fas fa-paper-plane send-icon" aria-hidden="true"></i>
                                                    </button>
                                                </form>
                                            </div>
                                            <div class="post" id="post-${post.post_id}">
                                                <div class="comment-section-header">
                                                    <a class="btn btn-link ml-2 text-info" onclick="toggleComments(${post.post_id})">
                                                        <span id="comment-count-${post.post_id}">${commentText}</span>
                                                    </a>
                                                </div>
                                                <div class="comment-section mt-3 ml-3 mr-2" id="comments-${post.post_id}" style="display: none; padding: 10px;">
                                                    <!-- Comments will be dynamically loaded here -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                `;
                                postsContainer.innerHTML += postHTML;
                                
                                // Fetch comments after adding post to the DOM
                                fetchComments(post.post_id);
                            });
                        } else {
                            postsContainer.innerHTML = '<div class="alert alert-danger ml-3 mr-3"><em>No announcements or updates at the moment. Stay tuned!</em></div>';
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching posts:', error);
                        const postsContainer = document.getElementById('postsContainer');
                        postsContainer.innerHTML = '<p>Failed to fetch posts. Please try again later.</p>';
                    });
            }
            

            // Function to fetch comments for a post
            function fetchComments(postId) {
                console.log(`Fetching comments for post ID: ${postId}`); // Debug log
                fetch(`/esas/esas_student/apis/comments-api.php?post_id=${postId}`)
                    .then(response => response.json())
                    .then(data => {
                        console.log('Fetched comments:', data); // Debug log
                        if (data.success) {
                            const commentsSection = document.getElementById(`comments-${postId}`);
                            const commentCount = document.getElementById(`comment-count-${postId}`);
                            commentsSection.innerHTML = data.comments.map(comment => {
                                const [date, time] = comment.dateAdded.split(' ');
                                return `
                                    <div class="comment d-flex align-items-start mb-2">
                                        <img src="/esas/esas_student/images/${comment.profilePic}" alt="${comment.student_name}'s profile picture" class="rounded-circle mr-2" width="40" height="40">
                                        <div class="comsec">
                                            <p class="student-name mb-1"><strong>${comment.student_name}</strong><br>${comment.comment}</p>
                                            <p class="comment text-muted">${formatDate(date)} @ ${formatTime(time)}</p>
                                        </div>
                                    </div>
                                `;
                            }).join('');
                            commentCount.textContent = data.comments.length === 1 ? '1 comment' : `${data.comments.length} comments`;
                        } else {
                            console.error(data.message);
                        }
                    })
                    .catch(error => console.error('Error fetching comments:', error));
            }

            // Function to toggle comments visibility
            window.toggleComments = function(postId) {
                const commentsSection = document.getElementById(`comments-${postId}`);
                if (commentsSection.style.display === 'none') {
                    commentsSection.style.display = 'block';
                    fetchComments(postId);
                } else {
                    commentsSection.style.display = 'none';
                }
            };

            // Call fetchPosts on page load
            fetchPosts();
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var icon = document.getElementById("icon_announcement");

            function triggerAnimation() {
                icon.style.animation = "none"; // Reset the animation
                icon.offsetHeight; // Trigger a reflow to restart the animation
                icon.style.animation = "zoomAndWave 1.2s ease-in-out"; // Apply the animation

                setTimeout(function() {
                    icon.style.animationPlayState = "paused"; // Pause the animation after it completes
                }, 1200);
            }

            // Trigger the animation every 5 seconds
            setInterval(triggerAnimation, 5000);
            
            // Initial trigger
            triggerAnimation();
        });
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