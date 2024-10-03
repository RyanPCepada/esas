<?php
session_start();
require_once "../config.php";

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

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

    // Check if a club_id is passed in the URL
    if (isset($_GET['club_id']) && is_numeric($_GET['club_id'])) {
        $club_id = $_GET['club_id']; // Use the passed club_id
    } else {
        // Default behavior if no club_id is passed (fallback)
        $sql = "SELECT club_id FROM tbl_registration WHERE student_id = :student_id LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":student_id", $student_id, PDO::PARAM_INT);
        $stmt->execute();
        $student = $stmt->fetch(PDO::FETCH_ASSOC);
        $club_id = $student['club_id']; // Default to the first club
    }

    // Fetch the student's profile picture
    $sql = "SELECT profilePic FROM tbl_students WHERE student_id = :student_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":student_id", $student_id, PDO::PARAM_INT);
    $stmt->execute();
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($student) {
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

            // Update the is_read field to 1 for notifications for this student and club
            $sql = "UPDATE tbl_notifications SET is_read = 1 WHERE student_id = :student_id AND club_id = :club_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":student_id", $student_id, PDO::PARAM_INT);
            $stmt->bindParam(":club_id", $club_id, PDO::PARAM_INT);
            $stmt->execute();
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
        /* body {
            font: 14px Helvetica;
        } */
        .wrapper {
            width: 100%;
            /* max-width: 800px; */
            margin: 0 auto;
            padding: 0px !important;
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

        #dashboard-btn {
            border-radius: 5px;
            margin: 5px;
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
            max-width: 90%;
        }


        body.modal-open {
            padding-right: 0 !important;
        }
        /* html {
            overflow-y: scroll;
        } */





        /* Comment container to position ellipsis */
        .comment {
            position: relative; /* Ensure positioning context for the absolute element */
            transition: background-color 0.3s ease;
        }

        .comment:hover {
            background-color: #f7f7f7; /* Light background when hovering over the comment row */
        }

        .dropdown {
            position: absolute;
            margin-left: 93%;
            z-index: 2;
        }
        .dropdown .ellipsis {
            position: absolute !important;
            right: 20px; /* Position it on the far right */
            top: 0%;
            border-radius: 50%;
            padding: 5px;
            transition: background-color 0.3s ease;
            background-color: #f7f7f7; /* Ensure background color matches hover */
        }
        .dropdown .ellipsis:hover {
            background-color: #d3d3d3; /* Light gray background on hover */
            cursor: pointer;
        }
        .dropdown .dropdown-menu {
            position: absolute;
            margin-left: -170px !important;
            z-index: 2;
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
            .dropdown .ellipsis {
                position: absolute !important;
                right: 20px; /* Position it on the far right */
            }
        }
    </style>
</head>
<body>

    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">


            
                <!-- Events Section -->
                <div class="card col-md-3 p-3 auto-scroll" style="border-radius: 10px; border: 1px solid #ddd; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                    <div class="events-section">
                        <h5 class="text-muted mb-3" style="text-align: center; font-size: 1.2em;">Upcoming Events</h5>

                        <!-- Calendar Section -->
                        <div id="calendar" style="margin-bottom: 20px; text-align: center;"></div>

                        <!-- Static Event List -->
                        <div class="event-list" id="eventList">
                            <!-- Events will be dynamically inserted here -->
                        </div>
                    </div>
                </div>


                <!-- Main Body Section with Cover Photo -->
                <div class="col-md-6 auto-scroll">
                    <div class="cover-photo-container mb-3">
                        <img src="/esas/esas_moderator/images/<?php echo htmlspecialchars($coverPhoto); ?>" alt="Cover Photo" class="img-fluid">
                    </div>
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
                        <div class="mt-2 text-center align-items-center justify-content-center">
                            <a href="javascript:history.back();" class="btn btn-secondary">Go Back</a>
                        </div>
                    </div>
                </div>

                <!-- Chatbox Section -->
                <div class="card col-md-3 p-3 auto-scroll">
                    <div class="chatbox-section">
                        <label class="text-muted" style="font-size: 15px;"><em>Start a conversation with your moderator(s) and fellow club members!</em></label>
                        <div class="chatbox" id="chatbox">
                            <!-- Example Chat Interface -->
                            <div class="messages">
                                <!-- Messages will be displayed here -->
                            </div>
                            <input type="text" id="chatInput" placeholder="Type a message..." class="form-control">
                            <button id="sendMessage" class="btn btn-primary mt-2">Send</button>
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
                const clubId = "<?php echo $club_id; ?>"; // Get the club_id from PHP

                fetch(`/esas/esas_student/apis/posts-api.php?club_id=${clubId}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Fetched posts:', data);
                        const postsContainer = document.getElementById('postsContainer');
                        if (data && data.length > 0) {
                            postsContainer.innerHTML = ''; // Clear existing posts
                            data.forEach(post => {
                                const postDate = formatDate(post.dateAdded.split(' ')[0]);
                                const postTime = formatTime(post.dateAdded.split(' ')[1]);

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
                                                    <input type="hidden" name="club_id" value="<?php echo $club_id; ?>">
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
                                                <div class="comment-section mt-1 ml-1 mr-2" id="comments-${post.post_id}" style="display: none; padding: 10px;">
                                                    <!-- Comments will be dynamically loaded here -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                `;
                                postsContainer.innerHTML += postHTML;

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
                const clubId = "<?php echo $club_id; ?>"; // Get the club_id from PHP
                const currentStudentId = "<?php echo $student_id; ?>"; // Get the current student's ID from PHP

                fetch(`/esas/esas_student/apis/comments-api.php?post_id=${postId}&club_id=${clubId}`)
                    .then(response => response.json())
                    .then(data => {
                        console.log('Fetched comments:', data);
                        if (data.success) {
                            const commentsSection = document.getElementById(`comments-${postId}`);
                            const commentCount = document.getElementById(`comment-count-${postId}`);
                            commentsSection.innerHTML = data.comments.map(comment => {
                                const [date, time] = comment.dateAdded.split(' ');

                                // Only show the edit button if the comment's student_id matches the current user's student_id
                                const showEllipsisButton = comment.student_id == currentStudentId ? `
                                    <div class="dropdown">
                                        <i class="fas fa-ellipsis-v ellipsis" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
                                        <div class="dropdown-menu">
                                            <button class="dropdown-item" data-comment-id="${comment.comment_id}" data-comment-text="${comment.comment}" onclick="openEditCommentModal(this)">
                                                <i class="fa fa-pencil"></i> Edit
                                            </button>
                                            <button class="dropdown-item text-danger" data-comment-id="${comment.comment_id}" onclick="deleteComment(this)">
                                                <i class="fa fa-trash"></i> Delete
                                            </button>
                                        </div>
                                    </div>` : '';
                                return `
                                    <div class="comment d-flex align-items-start">
                                        <img src="/esas/esas_student/images/${comment.profilePic}" alt="${comment.student_name}'s profile picture" class="rounded-circle mr-2" width="40" height="40">
                                        <div class="comsec">
                                            <p class="student-name mb-1">
                                                ${showEllipsisButton} <!-- Conditionally render the dropdown -->
                                                <strong>${comment.student_name}</strong><br>
                                                <span id="comment-text-${comment.comment_id}">${comment.comment}</span>
                                                <p class="comment text-muted">${formatDate(date)} @ ${formatTime(time)}</p>
                                            </p>
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

     // Function to create a simple calendar
function createCalendar(month, year, events) {
    const calendarDiv = document.getElementById('calendar');
    calendarDiv.innerHTML = ''; // Clear existing calendar

    const monthNames = [
        "January", "February", "March", "April", "May", "June", 
        "July", "August", "September", "October", "November", "December"
    ];
    
    // Header for the month and year
    const header = document.createElement('h4');
    header.innerText = `${monthNames[month]} ${year}`;
    header.style.textAlign = 'center';
    header.style.fontSize = '1.2em'; // Increased header font size for emphasis
    header.style.fontWeight = 'bold'; 
    header.style.color = '#333'; 
    calendarDiv.appendChild(header);

    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const firstDay = new Date(year, month, 1).getDay();

    // Create a styled table for the calendar
    const table = document.createElement('table');
    table.style.width = '100%';
    table.style.borderCollapse = 'collapse'; 
    table.style.fontSize = '12px'; // Consistent font size
    table.style.color = '#555'; // Consistent text color
    const tbody = document.createElement('tbody');

    // Create header row for days of the week
    const daysRow = document.createElement('tr'); 
    ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"].forEach(day => {
        const th = document.createElement('th');
        th.innerText = day;
        th.style.color = '#ff6b6b'; 
        th.style.padding = '2px'; // Padding for header cells
        th.style.fontWeight = 'bold'; 
        th.style.textAlign = 'center'; 
        th.style.fontSize = '1em'; // Slightly larger font size for day headers
        daysRow.appendChild(th);
    });
    tbody.appendChild(daysRow);

    let row = document.createElement('tr');
    for (let i = 0; i < firstDay; i++) {
        const td = document.createElement('td');
        td.style.border = '1px solid #e6e6e6'; // Border for empty cells
        row.appendChild(td);
    }

    // Fill calendar with dates
    for (let date = 1; date <= daysInMonth; date++) {
        const td = document.createElement('td');
        td.innerText = date;
        td.style.padding = '5px'; // Adjusted padding for better spacing
        td.style.textAlign = 'center'; 
        td.style.fontSize = '1em'; // Increased font size for dates

        // Highlight event dates
        const eventDate = `${month + 1}/${date}/${year}`; // Use MM/DD/YYYY format
        const formattedEventDates = events.map(event => {
            const parts = event.split(' ');
            const day = parts[1].replace(',', ''); // Extract day
            const monthIndex = monthNames.indexOf(parts[0]) + 1; // Get month index
            return `${monthIndex}/${day}/${parts[2]}`; // Format as MM/DD/YYYY
        });

        if (formattedEventDates.includes(eventDate)) {
            td.style.backgroundColor = '#007bff'; // Event highlight color
            td.style.color = '#fff'; // Text color for event dates
            td.style.borderRadius = '50%'; // Circular effect
            td.style.padding = '10px'; // Increased padding for event dates
            td.style.margin = '5px'; // Margin for spacing
        } else {
            td.style.backgroundColor = '#f9f9f9'; // Light background for normal days
            td.style.color = '#555'; 
            td.style.border = '1px solid #e6e6e6'; // Border for normal days
        }

        row.appendChild(td);
        if (row.children.length === 7) { // If 7 days are filled
            tbody.appendChild(row);
            row = document.createElement('tr'); // Start a new row
        }
    }
    tbody.appendChild(row); // Append the last row if it has any remaining days
    table.appendChild(tbody);
    calendarDiv.appendChild(table);

    // Style for the calendar container
    calendarDiv.style.border = '1px solid #e6e6e6'; // Outer border for the calendar
    calendarDiv.style.padding = '15px'; // Padding for the calendar container
    calendarDiv.style.borderRadius = '10px'; // Rounded corners for the calendar container
    calendarDiv.style.boxShadow = '0 1px 3px rgba(0,0,0,0.1)'; // Subtle shadow for depth
    calendarDiv.style.backgroundColor = '#f9f9f9'; // Background color for the calendar
}


// Call this function after fetching events
function fetchEvents() {
    const clubId = "<?php echo $club_id; ?>"; // Get the club_id from PHP

    fetch(`/esas/esas_student/apis/events-api.php?club_id=${clubId}`)
        .then(response => response.json())
        .then(data => {
            const eventList = document.getElementById('eventList');
            eventList.innerHTML = ''; // Clear the existing content
            
            const eventDates = []; // Array to store event dates
            
            if (data.length === 0) {
                // No events found
                eventList.innerHTML = `
                    <div style="text-align: center; margin-top: 30px;">
                        <i class="fas fa-calendar-times" style="font-size: 50px; color: #ff6b6b;"></i>
                        <p style="color: #666; margin-top: 10px;">No upcoming events.<br>Please stay tuned!</p>
                    </div>
                `;
                document.getElementById('calendar').style.display = 'none'; // Hide calendar
                return; // Exit the function early
            }

            // If events are present, show the calendar
            document.getElementById('calendar').style.display = 'block'; // Show calendar
            
            data.forEach(event => {
                const eventDate = new Date(event.date);
                const monthNames = [
                    "January", "February", "March", "April", "May", "June", 
                    "July", "August", "September", "October", "November", "December"
                ];
                const formattedDate = `${monthNames[eventDate.getMonth()]} ${eventDate.getDate().toString().padStart(2, '0')}, ${eventDate.getFullYear()}`; // Format the date

                eventDates.push(formattedDate); // Add date to the array

                const eventItem = document.createElement('div');
                eventItem.className = 'event-item';
                eventItem.style.padding = '10px';
                eventItem.style.borderBottom = '1px solid #ddd';
                eventItem.style.marginBottom = '10px';

                eventItem.innerHTML = `
                    <h6 style="color: #007bff;">${event.title}</h6> 
                    <p style="margin: 0; color: #666;">${formattedDate}</p>
                    <p><a href="#" class="btn btn-link" style="padding: 0; color: #007bff;" onclick="showEventDetails(${event.event_id})">View Details</a></p>
                `;
                eventList.appendChild(eventItem);
            });

            // Create the calendar for the current month
            const today = new Date();
            createCalendar(today.getMonth(), today.getFullYear(), eventDates);
        })
        .catch(error => {
            console.error('Error fetching events:', error);
        });
}




// Function to convert time to 12-hour format
function formatTime(time) {
    const [hours, minutes] = time.split(':').map(Number);
    const isAM = hours < 12;
    const formattedHours = hours % 12 || 12; // Converts 0 to 12 for midnight
    const ampm = isAM ? 'AM' : 'PM';
    return `${formattedHours}:${minutes < 10 ? '0' : ''}${minutes} ${ampm}`;
}

// Function to show event details in a modal
function showEventDetails(event_id) {
    fetch(`/esas/esas_student/apis/events-api.php?event_id=${event_id}`)
        .then(response => response.json())
        .then(event => {
            if (event) {
                // Populate modal with event details
                document.getElementById('eventTitle').innerText = event.title;
                document.getElementById('eventDescription').innerText = event.description;
                document.getElementById('eventDate').innerText = new Date(event.date).toLocaleDateString('en-US', { 
                    month: 'long', 
                    day: 'numeric', 
                    year: 'numeric' 
                });
                document.getElementById('eventTime').innerText = formatTime(event.time); // Use the new formatTime function
                document.getElementById('eventLocation').innerText = event.location;
                document.getElementById('eventLink').href = event.registrationLink;

                // Show the modal
                $('#eventDetailsModal').modal('show');
            }
        })
        .catch(error => {
            console.error('Error fetching event details:', error);
        });
}


    // Fetch events when the page loads
    window.onload = fetchEvents;
</script>




<!-- Modal for Event Details -->
<div class="modal fade" id="eventDetailsModal" tabindex="-1" role="dialog" aria-labelledby="eventDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #007bff; color: #ffffff;">
                <h5 class="modal-title" id="eventDetailsModalLabel">Event Details</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" style="color: #ffffff;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h6 id="eventTitle" style="color: #007bff;"></h6>
                <p id="eventDescription" style="margin-bottom: 1rem;"></p>
                <p><strong>Date:</strong> <span id="eventDate" style="color: #666;"></span></p>
                <p><strong>Time:</strong> <span id="eventTime" style="color: #666;"></span></p>
                <p><strong>Location:</strong> <span id="eventLocation" style="color: #666;"></span></p>
                <p><strong>Registration Link:</strong> <a id="eventLink" href="#" target="_blank" class="btn btn-primary btn-sm">Register</a></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>





<script>

        // Function to fetch chats and their replies
function fetchChats() {
    const clubId = "<?php echo $club_id; ?>"; // Get the club_id from PHP

    fetch(`/esas/esas_student/apis/chats-api.php?club_id=${clubId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const chatSection = document.getElementById('chatbox');
                chatSection.innerHTML = '';

                data.chats.forEach(chat => {
                    // Display the main chat message
                    const chatHtml = `
                        <div class="chat-message">
                            <strong>${chat.student_name}</strong>: ${chat.message}
                            <div class="reply-section" id="replies-${chat.chat_id}"></div>
                            <button onclick="showReplyInput(${chat.chat_id})">Reply</button>
                            <input type="text" id="replyInput-${chat.chat_id}" placeholder="Type your reply..." style="display:none;">
                            <button id="sendReply-${chat.chat_id}" style="display:none;" onclick="sendReply(${chat.chat_id})">Send</button>
                        </div>
                    `;
                    chatSection.innerHTML += chatHtml;

                    // Fetch replies for the current chat message
                    fetchReplies(chat.chat_id);
                });
            } else {
                console.error(data.message);
            }
        })
        .catch(error => console.error('Error fetching chats:', error));
}

// Function to fetch replies for a specific chat message
function fetchReplies(chatId) {
    fetch(`/esas/esas_student/apis/replies-api.php?reply_to=${chatId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const repliesSection = document.getElementById(`replies-${chatId}`);
                repliesSection.innerHTML = data.replies.map(reply => `
                    <div class="reply-message">
                        <strong>${reply.student_name}</strong>: ${reply.message}
                    </div>
                `).join('');
            } else {
                console.error(data.message);
            }
        })
        .catch(error => console.error('Error fetching replies:', error));
}

// Function to send a reply
function sendReply(chatId) {
    const replyInput = document.getElementById(`replyInput-${chatId}`);
    const replyMessage = replyInput.value;

    // Here you would send the replyMessage to the server via an API
    // For simplicity, just logging it
    console.log(`Sending reply to chat ${chatId}: ${replyMessage}`);
}

        

    </script>







<!-- EDIT COMMENT MODAL -->
<div class="modal fade" id="editCommentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content editCommentModal">
            <div class="modal-header">
                <h6 class="mb-0">Edit Comment</h6>
            </div>
            <div class="modal-body">
                <form action="../esas_student/actions/edit_comment_action.php" method="post">
                    <div class="row">
                        <div class="col-12">
                            <textarea id="editCommentText" name="new_comment" class="form-control form-control-sm" rows="3" required></textarea>
                            <input type="hidden" name="comment_id" id="editCommentId">
                            <input type="hidden" name="club_id" value="<?php echo $club_id; ?>">
                        </div>
                    </div>
                    <div class="modal-footer py-0">
                        <button type="button" class="btn btn-sm btn-default" data-bs-dismiss="modal">Cancel</button>
                        <input type="submit" class="btn btn-primary" data-bs-dismiss="modal" value="Submit">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- CONFIRMATION DELETE MODAL -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="confirmDeleteModalLabel">Confirm Deletion</h6>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this comment?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal" id="confirmDeleteBtn">Yes</button>
            </div>
        </div>
    </div>
</div>


    <script>
        
        

        //EDIT MODAL FUNCTION START//
        // Function to open the edit comment modal and populate fields
function openEditCommentModal(button) {
    const commentId = button.getAttribute('data-comment-id');
    const commentText = button.getAttribute('data-comment-text');
    const postId = button.getAttribute('data-post-id'); // Store the post ID

    console.log('Opening modal for Comment ID:', commentId); // Debugging output

    // Set the comment text and ID in the modal fields
    document.getElementById('editCommentText').value = commentText;
    document.getElementById('editCommentId').value = commentId;

    // Show the modal
    $('#editCommentModal').modal('show');
}



// Handle form submission for editing comment
document.querySelector('#editCommentModal form').addEventListener('submit', function(e) {
    e.preventDefault(); // Prevent default form submission

    // Retrieve form data
    const formData = new FormData(this);

    // Send request to edit the comment
    fetch('../esas_student/actions/edit_comment_action.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())  // Parse JSON response
    .then(data => {
        console.log('Edit result:', data);
        if (data.success) {
            alert(data.message);  // Display success message
            if (data.redirect_url) {
                window.location.href = data.redirect_url; // Redirect if URL is provided
            } else {
                // Reload comments or update the UI as needed
                fetchComments(formData.get('club_id')); // Call function to fetch and update comments
            }
        } else {
            alert(data.message);  // Display error message
        }

        // Hide the modal
        $('#editCommentModal').modal('hide');
    })
    .catch(error => console.error('Error editing comment:', error));
});


        //EDIT MODAL FUNCTION END//



        //DELETE MODAL FUNCTION START//
        let commentToDelete = null; // Store the comment ID for deletion
        let postId = null; // Store the post ID

        function deleteComment(button) {
            commentToDelete = button.getAttribute('data-comment-id'); // Store the comment ID
            postId = button.getAttribute('data-post-id'); // Store the post ID

            // Show confirmation modal
            $('#confirmDeleteModal').modal('show');
        }

        // Handle the confirmation of deletion
        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            console.log('Confirm delete button clicked');
            if (commentToDelete) {
                // Store the postId in localStorage to keep track of which post's comments section was open
                localStorage.setItem('openPostId', postId);

                // Send request to delete the comment
                fetch('/esas/esas_student/actions/delete_comment_action.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        'comment_id': commentToDelete,
                        'club_id': "<?php echo $club_id; ?>"
                    }),
                })
                .then(response => response.json())  // Parse JSON response
                .then(data => {
                    console.log('Delete result:', data);
                    if (data.success) {
                        alert(data.message);  // Display success message
                        if (data.redirect_url) {
                            window.location.href = data.redirect_url; // Redirect to home.php with the club_id
                        } else {
                            fetchComments(postId); // Reload comments or update the UI as needed
                        }
                    } else {
                        alert(data.message);  // Display error message
                    }
                })
                .catch(error => console.error('Error deleting comment:', error));

                // Hide the modal
                $('#confirmDeleteModal').modal('hide');
                commentToDelete = null; // Clear the stored comment ID
            }
        });

        //DELETE MODAL FUNCTION END//




        document.addEventListener('DOMContentLoaded', function() {
    // Check if there's a postId stored in localStorage
    const openPostId = localStorage.getItem('openPostId');
    
    if (openPostId) {
        console.log('Opening comments for post:', openPostId);

        // Open the comments section of the stored postId
        toggleComments(openPostId);
        
        // Remove the stored postId after using it
        localStorage.removeItem('openPostId');
    }
});






        //WAVE ANIMATION OF OFFICERS CARDS START//
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
        //WAVE ANIMATION OF OFFICERS CARDS END//

    </script>
    

</body>

</html>