
<div class="row" id="postsContainer">
    <!-- Posts will be dynamically inserted here -->
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
                                    <div class="comment-dropdown">
                                        <i class="fas fa-ellipsis-v ellipsis comment-ellipsis" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
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
