<style>
/* Main chat container */
#chatList {
    max-height: 520px;
    margin-bottom: 20px;
    text-align: left;
    padding: 30px;
    background-color: #f4f4f9;
}

/* Chat item styling */
.chat-item {
    display: flex;
    padding: 10px;
    margin-bottom: 10px;
    border-radius: 8px;
    cursor: pointer;
    box-shadow: 0 1px 5px rgba(0, 0, 0, 0.05);
    transition: background-color 0.2s ease;
}

/* Color coding for departments */
.chat-tep {
    background-color: #cce5ff; /* Light blue for TEP */
}

.chat-bsba {
    background-color: #fff3cd; /* Light yellow for BSBA */
}

.chat-ccs {
    background-color: #d4edda; /* Light green for CCS */
}

/* Default background color for other departments */
.chat-default {
    background-color: #f9f9f9; /* Light gray for default */
}

/* Hover effects */
.chat-tep:hover {
    background-color: #b3d7ff; /* Darker shade for TEP on hover */
}

.chat-bsba:hover {
    background-color: #ffeeba; /* Darker shade for BSBA on hover */
}

.chat-ccs:hover {
    background-color: #c3e6cb; /* Darker shade for CCS on hover */
}

/* Default hover effect */
.chat-default:hover {
    background-color: #e2e2e2; /* Slightly darker for default */
}

/* Avatar styling */
.chat-avatar img {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); 
    margin-right: 15px;
}

/* Chat content container */
.chat-content {
    flex-grow: 1;
}

/* Chat header with name and date */
.chat-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 5px;
}

.student-name {
    font-size: 16px;
    font-weight: bold;
    color: #333;
}

.message-date {
    font-size: 12px;
    color: #999;
}

/* Message text */
.message {
    font-size: 14px;
    color: #555;
    line-height: 1.4;
}

/* Smooth scrolling for long chat lists */
#chatList {
    scroll-behavior: smooth;
}

/* Mobile adjustments */
@media (max-width: 768px) {
    
    #chatList {
        padding: 10px;
    }
    .chat-item {
        flex-direction: row !important; /* Ensure avatar and content are still side-by-side */
        align-items: top; /* Vertically align avatar and content */
        padding: 10px;
    }

    .chat-avatar {
        margin-right: 15px; /* Keep some space between avatar and content */
        margin-bottom: 0; /* Remove bottom margin */
    }

    .chat-content {
        flex-grow: 1;
        width: 100%; /* Ensure chat content takes full width */
    }

    .chat-header { /*student name is located */
        margin-left: -20px;
    }
    .student-name {
        line-height: 1.2;
    }
    .message-date {
        display: none;; /* Align the message date on the right */
    }
    .message {
        margin-left: -20px;
    }
}




/* Modal styling - Center-bottom popup */
.modal {
    display: none; /* Hidden by default */
    position: fixed; 
    z-index: 1000; /* On top */
    left: 70%; /* Center horizontally */
    top: 30%; /* Fixed at the bottom */
    width: 350px; /* Adjust width for a smaller, more compact size */
    max-height: 450px; /* Limit height */
    background-color: white;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3); /* Softer shadow for depth */
    transition: bottom 0.4s ease; /* Slide up transition */
    border-radius: 10px 10px 0 0; /* Rounded top corners */
    padding: 15px; /* Adjust padding for a more compact look */
    overflow-y: auto; /* Enable scroll if content overflows */
    transform: translateX(-50%); /* Center horizontally */
}

/* Visible state */
.modal.show {
    display: block; /* Show modal */
    bottom: 20px; /* Visible at the bottom with a margin */
}


/* Modal header */
.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 16px;
    font-weight: bold;
    padding-bottom: 10px;
    border-bottom: 1px solid #ddd;
}

/* Close button */
.close-modal {
    cursor: pointer;
    font-size: 20px;
    line-height: 1;
    color: #999;
}

/* Modal body */
.modal-body {
    padding-top: 10px;
    font-size: 14px;
    color: #333;
}

.modal-student-avatar img {
    width: 40px;  /* Set the width to 40 pixels */
    height: 40px; /* Set the height to 40 pixels */
    border-radius: 50%; /* Optional: makes the image circular */
    object-fit: cover; /* Ensures the image covers the area without distortion */
}

/* Department-specific modal header styles */
.modal-header-tep {
    background-color: #f0ad4e; /* Example color for TEP */
    color: #fff; /* Text color */
}

.modal-header-bsba {
    background-color: #5bc0de; /* Example color for BSBA */
    color: #fff; /* Text color */
}

.modal-header-ccs {
    background-color: #d9534f; /* Example color for CCS */
    color: #fff; /* Text color */
}

/* Default modal header style */
.modal-header-default {
    background-color: #007bff; /* Default color */
    color: #fff; /* Text color */
}

/* Responsive adjustments for mobile */
@media (max-width: 600px) {
    .modal {
        width: 95%;
        top: 30%;
        left: 50%;
        right: 0;
        max-height: 400px; /* Limit height */
        border-radius: 0;
    }
}



</style>

<div class="chatbox-section">
    <div class="row d-flex align-items-center">
        <div class="col-12 d-flex justify-content-start">
            <h5 class="text-muted mb-3" style="font-size: 1.2em;">Chat Box</h5>
        </div>
    </div>
    <div class="chat-list auto-scroll" id="chatList">
        <!-- Chats will be dynamically inserted here -->
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const clubId = "<?php echo isset($_GET['club_id']) ? $_GET['club_id'] : 0; ?>"; // Get the club_id dynamically

    function fetchChats() {
        fetch(`/esas/esas_moderator/apis/chats-api.php?club_id=${clubId}`)
            .then(response => response.json())
            .then(data => {
                const chatList = document.getElementById('chatList');
                chatList.innerHTML = ''; // Clear existing chat list

                if (data.error) {
                    chatList.innerHTML = `<p class="text-danger">${data.error}</p>`;
                    return;
                }

                if (data.message) {
                    chatList.innerHTML = `<p class="text-muted">${data.message}</p>`;
                    return;
                }

                data.forEach(student => {
                    const fullName = `${student.firstName} ${student.middleName ? student.middleName + ' ' : ''}${student.lastName}`;
                    const message = student.message ? student.message : ''; // Show message if exists
                    const messageDate = student.messageDate ? student.messageDate : ''; // Use formatted date from API

                    let departmentClass = '';
                    switch (student.department) {
                        case 'TEP':
                            departmentClass = 'chat-tep';
                            break;
                        case 'BSBA':
                            departmentClass = 'chat-bsba';
                            break;
                        case 'CCS':
                            departmentClass = 'chat-ccs';
                            break;
                        default:
                            departmentClass = 'chat-default';
                    }

                    const chatItemHTML = `
                        <div class="chat-item ${departmentClass}" data-fullname="${fullName}" data-message="${message}" data-department="${student.department}">
                            <div class="chat-avatar">
                                <img src="/esas/esas_student/images/${student.profilePic}" alt="${fullName}" />
                            </div>
                            <div class="chat-content">
                                <div class="chat-header">
                                    <span class="student-name">${fullName}</span>
                                    <span class="message-date">${messageDate}</span>
                                </div>
                                <div class="message">${message} ${student.student_id}</div>
                            </div>
                        </div>
                    `;

                    chatList.innerHTML += chatItemHTML;
                });

                // Add event listeners for chat items to open modal
                document.querySelectorAll('.chat-item').forEach(item => {
                    item.addEventListener('click', function() {
                        const modal = document.getElementById('chatModal');
                        const studentName = this.getAttribute('data-fullname');
                        const message = this.getAttribute('data-message');
                        const profilePic = this.querySelector('.chat-avatar img').src; // Get the profile picture URL
                        const department = this.getAttribute('data-department'); // Get the department

                        // Set modal content
                        document.getElementById('modal-student-name').textContent = studentName;
                        document.getElementById('modal-student-message').textContent = message;
                        document.getElementById('modal-student-pic').src = profilePic; // Set the profile picture in modal

                        // Remove existing department classes
                        modal.querySelector('.modal-header').classList.remove('chat-tep', 'chat-bsba', 'chat-ccs', 'chat-default');

                        // Add the department class for the modal header
                        let departmentClass = '';
                        switch (department) {
                            case 'TEP':
                                departmentClass = 'chat-tep';
                                break;
                            case 'BSBA':
                                departmentClass = 'chat-bsba';
                                break;
                            case 'CCS':
                                departmentClass = 'chat-ccs';
                                break;
                            default:
                                departmentClass = 'chat-default';
                        }
                        modal.querySelector('.modal-header').classList.add(departmentClass);

                        // Show the modal with the slide-up effect
                        modal.classList.add('show');
                    });
                });
            })
            .catch(error => {
                console.error('Error fetching chats:', error);
            });
    }

    // Fetch chats when the page loads
    fetchChats();

    // Close modal when clicking the 'X' button
    document.querySelector('.close-modal').addEventListener('click', function() {
        const modal = document.getElementById('chatModal');
        modal.classList.remove('show');
    });
});
</script>

<!-- Modal Structure -->
<div id="chatModal" class="modal">
    <div class="modal-header">
        <div class="modal-student-avatar">
            <img id="modal-student-pic" src="" alt="Student Avatar" />
        </div>
        <span id="modal-student-name">Student Name</span>
        <span class="close-modal">&times;</span>
    </div>
    <div class="modal-body">
        <p id="modal-student-message">Message Content</p>
    </div>
</div>


 