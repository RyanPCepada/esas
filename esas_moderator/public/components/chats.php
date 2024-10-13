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
    width: 80%;
    word-wrap: break-word !important; /* Ensure long words break and wrap within the box */
    overflow-wrap: break-word !important; /* This works the same way as word-wrap */
    white-space: normal !important; /* Allow the message to wrap to a new line when necessary */
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
document.addEventListener('DOMContentLoaded', function () {
    const clubId = "<?php echo isset($_GET['club_id']) ? $_GET['club_id'] : 0; ?>"; 
    const modal = document.getElementById('chatModal'); 
    const modalChatsContent = document.getElementById('modal-chats-content'); 

    function fetchChats() {
        fetch(`/esas/esas_moderator/apis/chats-api.php?club_id=${clubId}`)
            .then(response => response.json())
            .then(data => {
                const chatList = document.getElementById('chatList');
                chatList.innerHTML = ''; 

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
                    const message = student.message ? student.message : `<em class="text-primary">Start a conversation with ${student.firstName} now!</em>`;
                    const messageDate = student.messageDate ? student.messageDate : '';

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

                    const imageSrc = student.is_moderator 
                        ? `/esas/esas_moderator/images/${student.profilePic}` 
                        : `/esas/esas_student/images/${student.profilePic}`;

                    const chatItemHTML = `
                        <div class="chat-item ${departmentClass}" data-fullname="${fullName}" data-student-id="${student.student_id}" data-department="${student.department}">
                            <div class="chat-avatar">
                                <img src="${imageSrc}" alt="${fullName}" />
                            </div>
                            <div class="chat-content">
                                <div class="chat-header">
                                    <span class="student-name">
                                        ${fullName}${student.is_moderator ? '<span class="moderator-shield"> <i class="fas fa-shield-alt text-danger"></i></span>' : ''}
                                    </span>
                                    <span class="message-date">${messageDate}</span>
                                </div>
                                <div class="message">${message}</div>
                                <!-- ${student.student_id} -->
                            </div>
                        </div>
                    `;

                    chatList.innerHTML += chatItemHTML;
                });

                document.querySelectorAll('.chat-item').forEach(item => {
                    item.addEventListener('click', function () {
                        document.querySelectorAll('.chat-item').forEach(chat => chat.classList.remove('active'));
                        this.classList.add('active'); 

                        const studentId = this.getAttribute('data-student-id'); 
                        const currentModeratorId = "<?php echo isset($_SESSION['moderator_id']) ? $_SESSION['moderator_id'] : '0'; ?>"; 
                        const studentFullName = this.getAttribute('data-fullname'); 
                        const studentPic = this.querySelector('.chat-avatar img').src; 

                        modal.querySelector('.modal-student-avatar img').src = studentPic; 
                        modal.querySelector('#modal-student-name').innerText = studentFullName; 

                        modal.querySelector('.modal-header').classList.remove('chat-tep', 'chat-bsba', 'chat-ccs', 'chat-default');

                        let departmentClass = this.getAttribute('data-department') === 'TEP' ? 'chat-tep' :
                                            this.getAttribute('data-department') === 'BSBA' ? 'chat-bsba' :
                                            this.getAttribute('data-department') === 'CCS' ? 'chat-ccs' :
                                            'chat-default';
                                            
                        modal.querySelector('.modal-header').classList.add(departmentClass);

                        fetch(`/esas/esas_moderator/apis/chats-modal-api.php?student_id=${studentId}`)
                            .then(response => response.json())
                            .then(data => {
                                modalChatsContent.innerHTML = ''; 

                                if (data.error) {
                                    modalChatsContent.innerHTML = `<p class="text-danger">${data.error}</p>`;
                                    return;
                                }

                                data.forEach(chat => {
                                    const messageClass = chat.sender_id == currentModeratorId ? 'message-right' : 'message-left';
                                    const messageHTML = `
                                        <div class="chat-message ${messageClass}">
                                            <p>${chat.message}</p>
                                            <span class="chat-date">${formatDate(chat.dateAdded)}</span>
                                        </div>
                                    `;
                                    modalChatsContent.innerHTML += messageHTML;
                                });

                                modalChatsContent.scrollTop = modalChatsContent.scrollHeight; // Scroll to the bottom of the chat

                                // Add this line to scroll to the #scroll-bottom div
                                document.getElementById('scroll-bottom').scrollIntoView({ behavior: 'smooth' });
                                document.getElementById('scroll-bottom').scrollIntoView({ behavior: 'auto' });


                            })
                            .catch(error => {
                                console.error('Error fetching chat messages:', error);
                            });

                        function formatDate(dateString) {
                            const options = { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' };
                            return new Date(dateString).toLocaleString(undefined, options);
                        }

                        modal.classList.add('show');
                    });
                });

            })
            .catch(error => {
                console.error('Error fetching chats:', error);
            });
    }

    fetchChats();

    document.querySelector('.modal-close-btn').addEventListener('click', function () {
        modal.classList.remove('show');
    });

    document.getElementById('send-message-btn').addEventListener('click', function () {
        const messageInput = document.getElementById('message-input');
        const message = messageInput.value.trim();
        const recipientId = document.querySelector('.chat-item.active') ? 
            document.querySelector('.chat-item.active').getAttribute('data-student-id') : null; 

        if (message === '') {
            alert('Please enter a message.');
            return;
        }

        if (!recipientId) {
            alert('Please select a recipient.');
            return;
        }

        fetch('../actions/send_chat_action.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `message=${encodeURIComponent(message)}&recipient_id=${recipientId}&club_id=${clubId}`,
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
            } else {
                const messageHTML = `
                    <div class="chat-message message-right">
                        <p>${message}</p>
                        <span class="chat-date">${formatDate(new Date().toISOString())}</span>
                    </div>
                `;
                modalChatsContent.innerHTML += messageHTML;
                modalChatsContent.scrollTop = modalChatsContent.scrollHeight; // Scroll to the bottom of the chat
                messageInput.value = ''; // Clear the input field
            }
        }) 
        .catch(error => {
            console.error('Error sending message:', error);
        });
    });

    function formatDate(dateString) {
        const options = { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' };
        return new Date(dateString).toLocaleString(undefined, options);
    }
});


</script>





<!-- ADDING FORM FOR SENDING MESSAGE FROM THE MODAL -->


<!-- Modal Structure -->
<div id="chatModal" class="chats-modal">
    <div class="modal-header">
        <div class="modal-student-avatar">
            <img id="modal-student-pic" src="" alt="Student Avatar" />
        </div>
        <div class="modal-student-info">
            <span id="modal-student-name">Student Name</span>
        </div>
        <div class="modal-close-btn">
            <span id="close-modal">&times;</span>
        </div>
    </div>
    <div class="chats-modal-body auto-scroll">
        <!-- Include all chats here -->
        <div id="modal-chats-content">
            <?php include '../public/components/chats_modal.php'; ?>
        </div>
        <div class="scroll-bottom" id="scroll-bottom"></div>
    </div>
    <div class="modal-footer">
        <input type="text" id="message-input" placeholder="Type your message here..." />
        <button id="send-message-btn">Send</button>
    </div>
</div>

<style>
.chats-modal {
    display: none;
    position: fixed; 
    z-index: 1000; 
    left: 70%; 
    top: 31%; 
    width: 350px; 
    max-height: 450px; 
    background-color: white;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3); 
    transition: bottom 0.4s ease; 
    border-radius: 10px 10px 0 0; 
    /* padding: 15px;  */
    transform: translateX(-50%); 
    overflow-y: hidden; 
}

.chats-modal.show {
    display: block; 
    bottom: 0px; 
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center; /* Aligns items at the start */
    font-size: 16px;
    font-weight: bold;
    padding-bottom: 10px;
    border-bottom: 1px solid #ddd;
    position: sticky;
}

.modal-student-avatar img {
    width: 40px;  
    height: 40px; 
    border-radius: 50%; 
    object-fit: cover; 
    margin-right: 10px; /* Add margin to space out the name */
}

.modal-student-info {
    width: 240px;
    display: flex;
    align-items: flex-start; /* Aligns items at the start */
    flex-direction: column; /* Stack the name if it wraps */
}

#modal-student-name {
    font-size: 1.2em;
    line-height: 1.2;
    white-space: nowrap; /* Prevents the name from wrapping to the next line */
    overflow: hidden;
    text-overflow: ellipsis; /* Adds an ellipsis if text overflows */
    max-width: 240px; /* Adjust this value to prevent overflow */
    margin-left: 0px;
}

.modal-close-btn {
    display: flex;                    
    align-items: center;           
    justify-content: center;             
    width: 25px;                      
    height: 25px;        
    padding: 0px;             
    font-size: 1.5em;          
    cursor: pointer;                  
    border-radius: 50%;               
    background-color: transparent;    
}

.modal-close-btn:hover {
    background-color: white;          
}
#close-modal {
    margin-top: -5px;
}




.chats-modal-body {
    padding-top: 10px;
    font-size: 14px;
    color: #333;
    max-height: 300px; 
    overflow-y: auto; 
}


.modal-header-tep {
    background-color: #f0ad4e; 
    color: #fff; 
}

.modal-header-bsba {
    background-color: #5bc0de; 
    color: #fff; 
}

.modal-header-ccs {
    background-color: #d9534f; 
    color: #fff; 
}

.modal-header-default {
    background-color: #007bff; 
    color: #fff; 
}

@media (max-width: 600px) {
    .chats-modal {
        width: 95%;
        top: 20%;
        left: 50%;
        right: 0;
        max-height: 450px; 
        border-radius: 0;
    }
}









.chat-message {
    max-width: 60%; /* Limit the width of chat messages */
    padding: 10px;  /* Add some padding */
    margin: 5px;    /* Space between messages */
    border-radius: 10px; /* Rounded corners */
    word-wrap: break-word; /* Ensure long words break and wrap within the box */
    overflow-wrap: break-word; /* This works the same way as word-wrap */
    white-space: normal; /* Allow the message to wrap to a new line when necessary */
}
.chat-message p {
    font-size: 1.2em;
}

.message-left {
    background-color: #f1f1f1; /* Light background for student messages */
    align-self: flex-start; /* Align to the left */
    margin: 10px;
}

.message-right {
    background-color: #007bff; /* Primary color for moderator messages */
    color: white; /* White text for better contrast */
    margin: 10px 0;
    margin-left: 40%;
}

.chat-date {
    font-size: 0.8em; /* Smaller font for date */
    color: #666; /* Light gray color for the date in general */
    margin-top: 0px; /* Space between message and date */
}
.message-right .chat-date {
    font-size: 0.8em; /* Smaller font for date */
    color: #e0e0e0 !important; /* Lighter gray for better contrast */
    /* text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5); */
}

/* Additional styles to create a flex container for messages */
.modal-chats-content {
    display: flex; /* Use flexbox */
    flex-direction: column; /* Stack messages vertically */
    overflow-y: auto; /* Enable scrolling if content is too long */
    height: 400px; /* Set a height for the chat area */
}








.modal-footer {
    display: flex;
    align-items: center;
    padding: 10px;
    border-top: 1px solid #ccc; 
    background-color: #fff; 
    position: sticky; 
    bottom: 0; 
    z-index: 10; 
}

#message-input {
    flex: 1; 
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    margin-right: 10px; 
    font-size: 14px;
}

#send-message-btn {
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    background-color: #007bff; 
    color: white;
    cursor: pointer;
    font-size: 14px;
}

#send-message-btn:hover {
    background-color: #0056b3; 
}
</style>





 