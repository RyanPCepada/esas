<?php
session_start();
require_once "../../config.php";

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Fetch the current moderator's ID
$moderator_id = $_SESSION['moderator_id'];

// Initialize variables and error messages
$postContent = "";
$postContent_err = "";
$club_id = ""; 
$clubName = ""; 
$coverPhoto = ""; 

// Check if club_id is provided in the URL
if (isset($_GET['club_id'])) {
    $club_id = intval($_GET['club_id']); // Use intval to ensure it's an integer

    // Fetch the club name and cover photo using the club_id
    $sql = "SELECT clubName, coverPhoto FROM tbl_clubs WHERE club_id = :club_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":club_id", $club_id, PDO::PARAM_INT);
    $stmt->execute();
    $club = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($club) {
        $clubName = $club['clubName']; 
        $coverPhoto = $club['coverPhoto']; 
    }
}
 else {
    // Handle the case where no club_id is provided
    // echo "<script>alert('No club ID provided.'); window.location.href = 'home.php';</script>";
    // exit();
    
    header("Location: /esas/esas_moderator/public/home.php?success=1&deleted=1");
    exit(); // Make sure to call exit after redirecting
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
        // Prepare an insert statement for the post
        $sql = "INSERT INTO tbl_posts (post, dateAdded, club_id, moderator_id) VALUES (:post, NOW(), :club_id, :moderator_id)";
        $stmt = $pdo->prepare($sql);

        // Bind variables to the prepared statement as parameters
        $stmt->bindParam(":post", $postContent);
        $stmt->bindParam(":club_id", $club_id, PDO::PARAM_INT); 
        $stmt->bindParam(":moderator_id", $moderator_id, PDO::PARAM_INT);
        
        // Execute the statement
        if ($stmt->execute()) {
            // Get the ID of the inserted post
            $post_id = $pdo->lastInsertId();

            // Notify all students registered in the club
            $sql = "SELECT student_id FROM tbl_registration WHERE club_id = :club_id AND status = 'active'";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":club_id", $club_id, PDO::PARAM_INT);
            $stmt->execute();
            $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Check if any students were found
            if (!empty($students)) {
                foreach ($students as $student) {
                    // Insert notification for each student
                    $sql = "INSERT INTO tbl_notifications (student_id, club_id, post_id, is_read, dateAdded) VALUES (:student_id, :club_id, :post_id, 0, NOW())";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(":student_id", $student['student_id'], PDO::PARAM_INT);
                    $stmt->bindParam(":club_id", $club_id, PDO::PARAM_INT);
                    $stmt->bindParam(":post_id", $post_id, PDO::PARAM_INT);
                    $stmt->execute();
                }
            }

            // Return a JSON response
            echo json_encode([
                "success" => true,
                "message" => "Post created successfully!",
                "redirect_url" => "home.php?club_id={$club_id}"
            ]);
        } else {
            echo json_encode(["success" => false, "message" => "Oops! Something went wrong. Please try again later."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => $postContent_err]);
    }

    // Close statement
    unset($stmt);
    // Close connection
    unset($pdo);
    exit();
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <link href="../../assets/css/jquery.dataTables.min.css" rel="stylesheet" />
    <script src="../../assets/js/all.js" crossorigin="anonymous"></script>
    <script src="../../assets/js/jquery-3.6.0.js"></script>
    <link href="../../assets/css/styles.css" rel="stylesheet" />
    <link href="../../assets/img/nbsclogo.png" rel="icon">


    <style>
        /* body {
            font: 14px Helvetica;
        } */
         

        body.modal-open {
            padding-right: 0 !important;
        }
        .wrapper {
            width: 100%;
            max-width: 700px;
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
            margin-top: -50px;
        }
        .overlay-text h4 {
            line-height: .9; /* Adjust line height for closer spacing */
        }
        #icon_announcement {
            height: 75px;
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
        
        #card_posts {
            /* box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1); */
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
        .post-ellipsis {
            width: 15px;
            right: -16px;
            padding: 5px;
            border-radius: 50%;
            position: absolute;
            cursor: pointer;
            align-self: right;
            font-size: 14px;
            color: #333;
            margin-top: 0px;
            margin-left: 100%;
        }

        .post-ellipsis:hover {
            background-color: lightgrey;
        }

        .dropdown .dropdown-menu {
            position: absolute;
            margin-left: -150px !important;
            z-index: 2;
        }


        @media (min-width: 768px) {
            .overlay-text {
                padding: 10px;
                border-radius: 5px;
                line-height: .5; /* Adjust line height for closer spacing */
                margin-top: -80px;
            }
            .overlay-text h3 {
                font-size: 40px;
                margin-right: 10px;
                line-height: 1.2; /* Adjust line height for closer spacing */
            }

        }

        @media (max-width: 767px) {
            .btn-custom  {
                font-size: 12px;
                border: 1px solid #ddd !important;
            }
            #addEventBtn {
                margin-left: -25px !important;
            }
        }








        .btn-custom {
    border: 2px solid #ddd;
    margin: 3px;
    color: #555;
    background-color: #f9f9f9;
    background-color: #e6e6e6;
    padding: 5px 10px;
    border-radius: 5px;
    font-weight: 500;
    transition: all 0.3s ease;
    box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
}

.btn-custom:hover {
    background-color: #e6e6e6;
    border-color: #bbb;
    color: #333;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.15);
}

.btn-custom:focus {
    outline: none;
    background-color: #e6e6e6;
    border-color: #aaa;
    box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.2);
}


    </style>
</head>
<body>
    

<div class="wrapper">
    <div class="container-fluid">
        <div class="row">
            <!-- Main Body Section with Cover Photo -->
            <div class="">
                <div class="cover-photo-container mb-3">
                    <img src="/esas/esas_moderator/images/<?php echo htmlspecialchars($coverPhoto); ?>" alt="Cover Photo" class="img-fluid mb-3">
                </div>
                <div class="overlay-text">
                    <div class="d-flex align-items-center">
                        <h3><?php echo htmlspecialchars($clubName); ?></h3>
                    </div>
                </div>

                <!-- <hr> -->

                <!-- Buttons for Posts, Events, Chats -->
                <div class="d-flex justify-content-end mb-3">
                    <button class="btn btn-custom" onclick="showContent('posts')">Posts</button>
                    <button class="btn btn-custom" onclick="showContent('events')">Events</button>
                    <button class="btn btn-custom" onclick="showContent('chats')">Chats</button>
                </div>

                <!-- Content Section -->
                <div id="slideContent">
                    <hr>
                    <!-- DEFAULT POSTS DISPLAY -->
                    <div id="posts">
                        <!-- Post Form -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card" id="card_post">
                                    <div class="card-header bg-info text-white d-flex align-items-center" style="position: relative; z-index: 1;">
                                        <img src="../icons/ICON_ANNOUNCEMENT.png" class="d-inline-block align-top" id="icon_announcement" alt="Announcement Icon">
                                        <h4 class="mb-0">Share Something Exciting!</h4>
                                    </div>
                                    <div class="card-body">
                                        <form id="postForm" method="POST" action="home.php?club_id=<?php echo $club_id; ?>" enctype="multipart/form-data">
                                            <div class="form-group mb-0">
                                                <label for="postContent">What's on your mind?</label>
                                                <textarea name="postContent" class="form-control" id="postContent" rows="3" placeholder="Share <?php echo htmlspecialchars($clubName); ?>'s latest news, events, or updates..."><?php echo htmlspecialchars($postContent); ?></textarea>
                                                <span class="text-danger"><?php echo $postContent_err; ?></span>
                                            </div>

                                            <!-- Icon Buttons for Uploading Images, Files, and Videos -->
                                            <!-- <div class="form-group d-flex justify-content-end mt-0 mb-0">
                                                <button type="button" class="btn btn-outline px-1 py-0 m-1" id="imageUploadBtn" style="background: transparent; border: none; font-size: 1.5rem;">
                                                    <i class="fa fa-image text-info"></i>
                                                    <input type="file" name="images[]" id="imageUpload" accept="image/*" multiple style="display: none;">
                                                </button>
                                                <button type="button" class="btn btn-outline px-1 py-0 m-1" id="fileUploadBtn" style="background: transparent; border: none; font-size: 1.5rem;">
                                                    <i class="fa fa-file text-info"></i>
                                                    <input type="file" name="files[]" id="fileUpload" accept=".pdf,.doc,.docx,.xls,.xlsx,.txt" multiple style="display: none;">
                                                </button>
                                                <button type="button" class="btn btn-outline px-1 py-0 m-1" id="videoUploadBtn" style="background: transparent; border: none; font-size: 1.5rem;">
                                                    <i class="fa fa-video text-info"></i>
                                                    <input type="file" name="videos[]" id="videoUpload" accept="video/*" multiple style="display: none;">
                                                </button>
                                            </div> -->

                                            <div class="d-flex justify-content-between align-items-center mt-3">
                                                <button type="submit" class="btn btn-primary" style="border-radius: 3px;"><i class="fa fa-paper-plane"></i> Post</button>
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
                            <div>
                                <?php include '../public/components/posts_and_comments.php' ?>
                            </div>
                        </div>
                    </div>

                    <!-- EVENTS DISPLAY -->
                    <div id="events" class="p-0" style="display: none;"> <!-- style="border-radius: 10px; border: 1px solid #ddd; box-shadow: 0 2px 5px rgba(0,0,0,0.1); display: none;"-->
                        <?php include '../public/components/events.php' ?>
                    </div>

                    <!-- CHATS DISPLAY -->
                    <div id="chats" class="p-0" style="display: none;">
                        <?php include '../public/components/chats.php' ?>
                    </div>
                </div>

                <div class="mt-2 text-center align-items-center justify-content-center">
                    <a href="../public/my_clubs.php" class="btn btn-secondary">Go Back</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript to switch between Posts, Events, and Chats -->
<script>
function showContent(contentId) {
    // Hide all content sections
    document.getElementById('posts').style.display = 'none';
    document.getElementById('events').style.display = 'none';
    document.getElementById('chats').style.display = 'none';

    // Show the selected content section
    document.getElementById(contentId).style.display = 'block';

    // Save the selected section in localStorage
    localStorage.setItem('selectedContent', contentId);
}

// Function to load the selected section from localStorage on page load
function loadSelectedContent() {
    const selectedContent = localStorage.getItem('selectedContent');

    // If a section is saved, display it; otherwise, default to posts
    if (selectedContent) {
        showContent(selectedContent);
    } else {
        showContent('posts'); // Default to posts if nothing is saved
    }
}

// Call loadSelectedContent on page load
document.addEventListener('DOMContentLoaded', loadSelectedContent);
</script>




<!-- <?php include 'assets/components/modals.php' ?> -->
<script src="../../assets/js/jquery.dataTables.min.js"></script>
<script src="../../assets/js/bootstrap.bundle.min.js"></script>
<script src="../../assets/js/global_script.js"></script>


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

</html>