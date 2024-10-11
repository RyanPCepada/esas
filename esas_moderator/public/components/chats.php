<div class="chatbox-section">
    <div class="row d-flex align-items-center">
        <div class="col-12 d-flex justify-content-start">
            <h5 class="text-muted mb-3" style="font-size: 1.2em;">Chat Box</h5>
        </div>
    </div>
    <div class="chat-list auto-scroll" id="chatList" style="max-height: 520px; margin-bottom: 20px; text-align: left; padding: 30px; background-color: #f4f4f9;">
        <!-- Chats will be dynamically inserted here -->
    </div>
</div>


<!-- HERE -->
 
<script>
document.addEventListener('DOMContentLoaded', function() {
    const clubId = "<?php echo $_GET['club_id']; ?>"; // Dynamically get the club_id

    function fetchChats() {
        fetch(`/esas/esas_moderator/apis/chats-api.php?club_id=${clubId}`)
        .then(response => response.json())
        .then(data => {
            const chatList = document.getElementById('chatList');
            chatList.innerHTML = ''; // Clear existing messages

            // Create a map to store only the latest message per participant
            const latestMessages = new Map();

            // Iterate through the participants and update the map with the latest message
            data.participants.forEach(participant => {
                const senderId = participant.id;

                // Always store the latest message for each participant
                latestMessages.set(senderId, participant);
            });

            // Display only the latest message for each participant
            latestMessages.forEach(participant => {
                const participantItem = document.createElement('div');
                participantItem.classList.add('chat-item', 'd-flex', 'mb-2');

                // Full name, role (moderator/student), and profile picture
                let senderName = participant.role === 'moderator' ? 
                    `${participant.moderator_firstName} ${participant.moderator_lastName}` : 
                    `${participant.student_firstName} ${participant.student_lastName}`;
                let profilePic = participant.role === 'moderator' ? participant.moderator_profilePic : participant.student_profilePic;

                // Reference the common 'id' key for both moderator and student
                let senderId = participant.id;

                // Create the participant block (with only the latest message)
                participantItem.innerHTML = `
                    <div class="row col-md-12 m-0 px-0 py-2" style="border-radius: 50px; background-color: #90dcef; cursor: pointer;">
                        <div class="row col-md-6 m-0 p-0">
                            <div class="col-2 m-0 p-0">
                                <img src="/esas/${participant.role === 'moderator' ? 'esas_moderator' : 'esas_student'}/images/${profilePic}" class="rounded-circle ml-2" alt="Profile Picture" style="width: 50px; height: 50px;">
                            </div>
                            
                            <div class="chat-content col-10 d-flex justify-content-start">
                                <div class="chat-message">
                                    <h5 class="text-dark ml-2">${senderName}</h5>
                                    <small class="text-muted">ID: ${senderId}</small>
                                </div>
                            </div>
                        </div>
                        <div class="row col-md-6 bg-light mr-1" style="border-radius: 50px; background-color: white;">
                            <!-- Display only the latest message -->
                            ${participant.message ? `<h5 class="mt-1 mb-0">${participant.message}</h5>` : `<p class="mt-1 mb-0"><em>Send ${senderName} a message now!</em></p>`}
                            <small class="text-muted">${participant.dateAdded || ''}</small>
                        </div>
                        <div class="row col-md-0>
                            <i class="fa fa-eye"></i>
                        </div>
                    </div>
                `;
                
                chatList.appendChild(participantItem);
            });
        })
        .catch(error => {
            console.error('Error fetching chats:', error);
            document.getElementById('chatList').innerHTML = '<p>Error loading chats.</p>';
        });
    }

    // Fetch chats every 5 seconds
    fetchChats();
    setInterval(fetchChats, 5000);
});

</script>

