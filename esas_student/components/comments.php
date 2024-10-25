
<div class="comment-section mt-1 ml-1 mr-2" id="comments-${post.post_id}" style="display: none; padding: 10px;">
    <!-- Comments will be dynamically loaded here -->
</div>


<script>
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

</script>