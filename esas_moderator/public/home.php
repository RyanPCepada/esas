<?php
session_start();
require_once "../../config.php";

// Fetch the current moderator's ID
$moderator_id = $_SESSION['moderator_id'];

// Initialize variables and error messages
$postContent = "";
$postContent_err = "";
$club_id = ""; // Initialize club_id variable
$clubName = ""; // Initialize clubName variable
$coverPhoto = ""; // Initialize coverPhoto variable

// Fetch the club_id for the current moderator through tbl_clubs_and_moderators
$sql = "SELECT club_id FROM tbl_clubs_and_moderators WHERE moderator_id = :moderator_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(":moderator_id", $moderator_id, PDO::PARAM_INT);
$stmt->execute();
$clubModerator = $stmt->fetch(PDO::FETCH_ASSOC);

if ($clubModerator) {
    $club_id = $clubModerator['club_id']; // Set club_id from tbl_clubs_and_moderators

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

// Handle post submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate post content
    $input_postContent = trim($_POST["postContent"]);
    if (empty($input_postContent)) {
        $postContent_err = "Please enter the post content.";
    } else {
        $postContent = $input_postContent;
    }
    
    // Check for errors before inserting into the database
    if (empty($postContent_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO tbl_posts (post, dateAdded, club_id, moderator_id) VALUES (:post, NOW(), :club_id, :moderator_id)";

        $stmt = $pdo->prepare($sql);

        // Bind variables to the prepared statement as parameters
        $stmt->bindParam(":post", $postContent);
        $stmt->bindParam(":club_id", $club_id, PDO::PARAM_INT); // Bind club_id
        $stmt->bindParam(":moderator_id", $moderator_id, PDO::PARAM_INT);

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            echo "<script>alert('Post created successfully!');</script>";
            echo "<script>window.location.href = 'home.php';</script>";
            exit();
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
    }

    // Close statement
    unset($stmt);
}

// Close connection
unset($pdo);
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eSAS - Moderator Home</title>
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> -->

    
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

    <link href="../assets/css/jquery.dataTables.min.css" rel="stylesheet" />
    <script src="../assets/js/all.js" crossorigin="anonymous"></script>
    <script src="../assets/js/jquery-3.6.0.js"></script>
    <link href="../assets/css/styles.css" rel="stylesheet" />
    <link href="../assets/img/nbsclogo.png" rel="icon">
    <style>
        body {
            font: 14px Helvetica;
        }
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
            /* text-decoration: underline; */
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
            margin-top: -80px;
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

        #card_post {
            position: relative;
            width: 100%;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .post-list .post-item {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
            background-color: #f9f9f9;
        }
        .post-item .post-date {
            font-size: 0.9em;
            color: #666;
        }
        .comment img {
            border-radius: 50%;
            border: 2px solid lightblue;
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
    
    <div class="wrapper">
        <div class="container-fluid">
            <div class="cover-photo-container">
                <img src="/e-sas/esas-moderator/images/<?php echo htmlspecialchars($coverPhoto); ?>" alt="Cover Photo" class="img-fluid mb-3">
            </div>
            <div class="overlay-text">
                <div class="d-flex align-items-center">
                    <h4><?php echo htmlspecialchars($clubName); ?></h4>
                </div>
            </div>
            <hr>
            <!-- Post Form -->
            <div class="row">
                <div class="col-12">
                    <div class="card" id="card_post">
                        <div class="card-header bg-info text-white d-flex align-items-center">
                            <i class="fa fa-pencil-square-o fa-2x mr-2"></i>
                            <h4 class="mb-0">Share Something Exciting!</h4>
                        </div>
                        <div class="card-body">
                            <form id="postForm" method="POST" action="home.php">
                                <div class="form-group">
                                    <label for="postContent">What's on your mind?</label>
                                    <textarea name="postContent" class="form-control" id="postContent" rows="3" placeholder="Share your club's latest news, events, or updates..."><?php echo htmlspecialchars($postContent); ?></textarea>
                                    <span class="text-danger"><?php echo $postContent_err; ?></span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-paper-plane"></i> Post</button>
                                    <div class="text-muted ml-2">
                                        <p>Let your club members know what's happening!</p>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <br><hr>
            <!-- Post List -->
            <div class="post_list">
                <div class="row">
                    <div class="col-12 mb-3">
                        <div class="d-flex align-items-center bg-info text-white p-2 rounded">
                            <img src="../icons/ICON_ANNOUNCEMENT.png" height="75" class="d-inline-block align-top" id="icon_announcement" alt="Announcement Icon">
                            <h4 class="mb-0">Announcements and Updates</h4>
                        </div>
                    </div>
                </div>
                <!-- Post List -->
                <div class="row" id="postsContainer">
                    <!-- Posts will be dynamically inserted here -->
                </div>
            </div>
        </div>
    </div>

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
                fetch('/e-sas/esas-moderator/public/posts-api.php')
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
                                const [date, time] = post.dateAdded.split(' ');
                                const formattedDate = formatDate(date);
                                const formattedTime = formatTime(time);

                                // Determine the comment count text
                                const commentText = post.numberOfComments === 1 ? '1 comment' : `${post.numberOfComments || 0} comments`;

                                const postHTML = `
                                    <div class="col-md-12 mb-3">
                                        <div class="card" id="card_posts">
                                            <div class="card-header d-flex align-items-start">
                                                <img src="/e-sas/esas-moderator/images/${post.profilePic}" alt="${post.fullName}" class="rounded-circle mr-3" width="50" height="50">
                                                <div>
                                                    <h5 class="card-title mb-1">${post.fullName}</h5>
                                                    <p class="text-muted mb-0">${formattedDate} @ ${formattedTime}</p>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <p class="card-text">${post.post}</p>
                                            </div>
                                            <div class="card-footer d-flex align-items-center">
                                                <span class="text-info">
                                                    <a class="btn btn-link text-info" onclick="toggleComments(${post.post_id})">
                                                        <span id="comment-count-${post.post_id}">${commentText}</span>
                                                    </a>
                                                </span>
                                            </div>
                                            <div class="comment-section mt-3 ml-3 mr-3" id="comments-${post.post_id}" style="display: none;">
                                                <!-- Comments will be dynamically loaded here -->
                                            </div>
                                        </div>
                                    </div>
                                `;
                                postsContainer.innerHTML += postHTML;

                                // Fetch comments after adding post to the DOM
                                fetchComments(post.post_id);
                            });
                        } else {
                            postsContainer.innerHTML = '<div class="alert alert-danger ml-3 mr-3"><em>You have no announcements or updates yet.</em></div>';
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching posts:', error);
                        const postsContainer = document.getElementById('postsContainer');
                        postsContainer.innerHTML = '<p>Failed to fetch posts. Please try again later.</p>';
                    });
            }

            // Function to fetch and display comments for a post
            function fetchComments(postId) {
                console.log(`Fetching comments for post ID: ${postId}`); // Debug log
                fetch(`/e-sas/esas-moderator/public/comments-api.php?post_id=${postId}`)
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
                                        <img src="/e-sas/esas-student/images/${comment.profilePic}" alt="${comment.student_name}'s profile picture" class="rounded-circle mr-2" width="40" height="40">
                                        <div>
                                            <p class="mb-1"><strong>${comment.student_name}</strong>: ${comment.comment}</p>
                                            <p class="text-muted">${formatDate(date)} @ ${formatTime(time)}</p>
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


<!-- <footer class="navbar-darkblue text-white mt-1 p-4 text-center">
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
</footer> -->

</html>