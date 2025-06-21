Please wait...

<?php
$studentId = isset($_GET['student_id']) ? intval($_GET['student_id']) : 0; // Get the student_id dynamically
$currentModeratorId = $_SESSION['moderator_id']; // Assuming you have the moderator ID stored in the session
?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const studentId = "<?php echo $studentId; ?>"; // Get the student ID from PHP
    const currentModeratorId = "<?php echo $currentModeratorId; ?>"; // Get the current moderator ID from PHP

    function fetchChatMessages() {
        fetch(`/esas/esas_moderator/apis/chats-modal-api.php?student_id=${studentId}`)
            .then(response => response.json())
            .then(data => {
                const modalChatContent = document.getElementById('modal-chat-content');
                modalChatContent.innerHTML = ''; // Clear existing content

                if (data.error) {
                    modalChatContent.innerHTML = `<p class="text-danger">${data.error}</p>`;
                    return;
                }

                data.forEach(chat => {
                    // Determine message alignment based on sender ID
                    const isCurrentModerator = chat.sender_id == currentModeratorId;
                    const modalChatMessageClass = isCurrentModerator ? 'modal-chat-message-right' : 'modal-chat-message-left';

                    // Format date (example: "Oct 12, 2024, 10:30 AM")
                    const date = new Date(chat.dateAdded);
                    const options = { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' };
                    const formattedDate = date.toLocaleString('en-US', options);

                    const modalChatMessageHTML = `
                        <div class="modal-chat-message ${modalChatMessageClass}">
                            <img src="${chat.sender_profile_pic}" class="modal-chat-profile-pic" alt="Profile Picture"> <!-- Add sender profile pic -->
                            <div class="modal-chat-text">
                                <p>${chat.message}</p>
                                <span class="modal-chat-date">${formattedDate}</span>
                            </div>
                        </div>
                    `;
                    modalChatContent.innerHTML += modalChatMessageHTML;
                });
            })
            .catch(error => {
                console.error('Error fetching chat messages:', error);
            });
    }

    // Fetch chat messages when the modal is opened
    fetchChatMessages();
});
</script>

<style>
.modal-chat-message-left,
.modal-chat-message-right {
    display: flex;
    align-items: center; /* Aligns profile picture and text */
    margin: 10px 0;
    padding: 10px;
    border-radius: 5px;
    background-color: #007bff; /* Primary blue background */
    color: white; /* Text color */
}

.modal-chat-message-left {
    justify-content: flex-start; /* Aligns to the left */
}

.modal-chat-message-right {
    justify-content: flex-end; /* Aligns to the right */
}

.modal-chat-profile-pic {
    width: 40px; /* Set profile picture width */
    height: 40px; /* Set profile picture height */
    border-radius: 50%; /* Make it circular */
    margin-right: 10px; /* Space between pic and message */
}

.modal-chat-text {
    flex-grow: 1; /* Allows text to take up remaining space */
}

.modal-chat-date {
    font-size: 0.8em;
    color: rgba(255, 255, 255, 0.7); /* Lighter date color */
    margin-top: 5px; /* Space above date */
}
</style>
