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

    // Initialize the variable
    $hasDepartureRequest = false;

    // Check if the student has already submitted a departure request for the current club
    $sql = "SELECT * FROM tbl_departure_requests WHERE student_id = :student_id AND club_id = :club_id LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
    $stmt->bindParam(':club_id', $club_id, PDO::PARAM_INT);
    $stmt->execute();
    $departureRequest = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($departureRequest) {
        // If a departure request exists, set the flag to true
        $hasDepartureRequest = true;
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
            max-width: 700px;
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

        .comment-ellipsis {
            width: 15px;
            padding: 5px;
            border-radius: 50%;
            position: absolute;
            top: 0; /* Align to the top */
            right: 0; /* Align to the right */
            cursor: pointer;
            font-size: 14px;
            color: #333;
        }

        .comment-ellipsis:hover {
            background-color: lightgrey;
        }

        .dropdown .dropdown-menu {
            position: absolute;
            margin-left: -150px !important;
            z-index: 2;
        }
        
        @media (min-width: 767px) {
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
            .comment-ellipsis {
                right: 0px !important;
            }
        }


        @media (max-width: 767px) {
            .btn-custom  {
                font-size: 10px;
                border: 1px solid #ddd !important;
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




        .event-ellipsis:hover {
            background-color: lightgrey;
        }

        .dropdown {
            display: none; /* Hidden by default */
            position: absolute; /* Position the dropdown */
            z-index: 1; /* Sit on top */
            background-color: white; /* Dropdown background */
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2); /* Dropdown shadow */
            border-radius: 4px; /* Rounded corners */
        }

        .dropdown a {
            color: black; /* Link color */
            padding: 12px 16px; /* Link padding */
            text-decoration: none; /* No underline */
            display: block; /* Make it a block */
        }

        .dropdown a:hover {
            background-color: #f1f1f1; /* Change color on hover */
        }

    </style>
</head>
<body>

<div class="wrapper">
    <div class="container-fluid">
        <div class="row">
            <!-- Main Body Section with Cover Photo -->
            <div class="col-md-12">
                <div class="cover-photo-container mb-3">
                    <img src="/esas/esas_admin/images/<?php echo htmlspecialchars($coverPhoto); ?>" alt="Cover Photo" class="img-fluid mb-3">
                </div>
                <div class="overlay-text">
                    <div class="d-flex align-items-center">
                        <h3><?php echo htmlspecialchars($clubName); ?></h3>
                    </div>
                </div>

                <!-- Buttons for Posts, Events, and Chats -->
                <div class="d-flex justify-content-end mb-3">
                    <button class="btn btn-custom" onclick="showContent('posts')">Posts</button>
                    <button class="btn btn-custom" onclick="showContent('events')">Events</button>
                    <button class="btn btn-custom" onclick="showContent('chats')">Chats</button>

                    <div class="event-ellipsis text-center" style="cursor: pointer; width: 22px; height: 22px; border-radius: 50%;" onclick="toggleDropdown()">
                        <i class="fas fa-ellipsis-v" style="color: #666;"></i>
                    </div>

                    <div id="dropdownMenu" class="dropdown mt-4">
                        <?php if ($hasDepartureRequest): ?>
                            <a href="../esas_student/crud/departure_requests/departure_request_read.php?club_id=<?php echo $_GET['club_id']; ?>">See Departure Request</a>
                        <?php else: ?>
                            <a href="#" onclick="openDepartureModal()">Request for Departure</a>
                        <?php endif; ?>
                    </div>
                </div>


                <!-- Content Section -->
                <div id="slideContent">
                    <hr>
                    <!-- DEFAULT POSTS DISPLAY -->
                    <div id="posts">
                        <!-- Post List -->
                        <div class="post_list">
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <div class="d-flex align-items-center bg-info text-white p-2 rounded">
                                        <img src="./icons/ICON_ANNOUNCEMENT.png" height="75" class="d-inline-block align-top" id="icon_announcement" alt="Announcement Icon">
                                        <h4 class="mb-0">Announcements and Updates</h4>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <?php include './components/posts_and_comments.php' ?>
                            </div>
                        </div>
                    </div>

                    <!-- EVENTS DISPLAY -->
                    <div id="events" class="p-0" style="display: none;">
                            <?php include './components/events.php' ?>
                    </div>

                    <!-- CHATS DISPLAY -->
                    <div id="chats" class="p-0" style="display: none;">
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

                <div class="mt-2 text-center align-items-center justify-content-center">
                    <!-- <a href="javascript:history.back();" class="btn btn-secondary">Go Back</a> -->
                    <a href="../esas_student/my_clubs.php" class="btn btn-secondary">Go Back</a>
                </div>
            </div>
        </div>
    </div>
</div>




<!-- Departure Request Modal -->
<div id="departureModal" class="modal" style="display:none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgb(0,0,0); background-color: rgba(0,0,0,0.4);">
    <div style="background-color: white; margin: 15% auto; padding: 20px; border: 1px solid #888; width: 80%; max-width: 400px;">
        <span style="color: red; font-weight: bold;">We're sorry to see you go.</span>
        <p>Could you please provide a reason for leaving the club?</p>
        <form id="departureRequestForm" onsubmit="submitDepartureRequest(event)">
            <div class="form-group">
                <textarea id="reasonInput" class="form-control" rows="3" placeholder="Share your reason..." required></textarea>
            </div>
            <input type="hidden" id="clubIdInput" name="club_id" value="<?php echo $club_id; ?>">
            <button type="submit" class="btn btn-danger mb-1">Submit Departure Request</button>
            <button type="button" class="btn btn-secondary mb-1" onclick="document.getElementById('departureModal').style.display='none'">Cancel</button>
        </form>
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







function toggleDropdown() {
    const dropdown = document.getElementById('dropdownMenu');
    dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
}

function openDepartureModal() {
    // Close the dropdown
    toggleDropdown();

    // Open the modal
    document.getElementById('departureModal').style.display = 'block';
}

function submitDepartureRequest(event) {
    event.preventDefault(); // Prevent the default form submission

    const reason = document.getElementById('reasonInput').value; // Get reason from input
    const club_id = document.getElementById('clubIdInput').value; // Get club ID from input

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "../esas_student/actions/departure_request_action.php", true); // Update the path as needed
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onload = function() {
        const response = JSON.parse(xhr.responseText);
        if (response.success) {
            alert('Departure request submitted successfully!'); // Show success message
            window.location.href = '/esas/esas_student/home.php?club_id=' + encodeURIComponent(club_id);
        } else {
            alert(response.message || 'Something went wrong.'); // Show error message
        }
    };

    xhr.send("reason=" + encodeURIComponent(reason) + "&club_id=" + encodeURIComponent(club_id));
}

// Close the modal when the user clicks outside of it
window.onclick = function(event) {
    const modal = document.getElementById('departureModal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}

</script>





 
    



     <!-- <?php include 'assets/components/modals.php' ?> -->
     <script src="../assets/js/jquery.dataTables.min.js"></script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/global_script.js"></script>





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