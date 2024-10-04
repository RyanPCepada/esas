
                        <!-- Post List -->
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
            function fetchPosts(clubId) { // Pass clubId as a parameter
                fetch(`/esas/esas_moderator/apis/posts-api.php?club_id=${clubId}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Fetched posts:', data); // Log the fetched data
                        const postsContainer = document.getElementById('postsContainer');
                        postsContainer.innerHTML = ''; // Clear existing posts
                        if (data && data.length > 0) {
                            data.forEach(post => {
                                const [date, time] = post.dateAdded.split(' ');
                                const formattedDate = formatDate(date);
                                const formattedTime = formatTime(time);
                                const commentText = post.numberOfComments === 1 ? '1 comment' : `${post.numberOfComments || 0} comments`;

                                const currentModeratorId = "<?php echo $moderator_id; ?>"; // Get the current moderator's ID from PHP

                                const postHTML = `
                                    <div class="col-md-12 mb-3">
                                        <div class="card" id="card_posts">
                                            <div class="card-header d-flex align-items-start">
                                                <img src="/esas/esas_moderator/images/${post.profilePic}" alt="${post.fullName}" class="rounded-circle mr-3" width="50" height="50">
                                                <div>
                                                    <h5 class="card-title mb-1">${post.fullName}</h5>
                                                    <p class="text-muted mb-0">${formattedDate} @ ${formattedTime}</p>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                ${post.moderator_id == currentModeratorId ? `
                                                <div class="dropdown">
                                                    <i class="fas fa-ellipsis-v ellipsis" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
                                                    <div class="dropdown-menu">
                                                        <button class="dropdown-item" data-post-id="${post.post_id}" onclick="openEditPostModal(this)">
                                                            <i class="fa fa-pencil"></i> Edit
                                                        </button>
                                                        <button class="dropdown-item text-danger" data-post-id="${post.post_id}" onclick="deletePost(this)">
                                                            <i class="fa fa-trash"></i> Delete
                                                        </button>
                                                    </div>
                                                </div>` : ''}
                                                <p class="card-text" style="width: 97%;">${post.post}</p>
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
                fetch(`/esas/esas_moderator/apis/comments-api.php?post_id=${postId}`)
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
                                        <img src="/esas/esas_student/images/${comment.profilePic}" alt="${comment.student_name}'s profile picture" class="rounded-circle mr-2" width="40" height="40">
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

            // Call fetchPosts with the correct club ID on page load
            const clubId = "<?php echo $club_id; ?>"; // Ensure this variable is set correctly
            fetchPosts(clubId);
        });
        
    </script>








<!-- Edit Post Modal -->
<div class="modal fade" id="editPostModal" tabindex="-1" role="dialog" aria-labelledby="editPostModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editPostModalLabel">Edit Post</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="editPostForm" action="/esas/esas_moderator/actions/edit_post_action.php" method="POST">
        <div class="modal-body">
          <input type="hidden" id="editPostId" name="post_id">
          <input type="hidden" name="club_id" value="<?php echo $club_id; ?>">
          <div class="form-group">
            <label for="editPostContent">Post Content</label>
            <textarea class="form-control" id="editPostContent" name="post_content" rows="4" required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Save changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Delete Post Modal -->
<div class="modal fade" id="deletePostModal" tabindex="-1" role="dialog" aria-labelledby="deletePostModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deletePostModalLabel">Delete Post</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this post?
        <input type="hidden" id="deletePostId" name="post_id">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal" onclick="confirmDelete()">Delete</button>
      </div>
    </div>
  </div>
</div>




<script>
    // Open the Edit Post modal
    function openEditPostModal(button) {
        const postId = button.getAttribute('data-post-id');
        const postContent = button.closest('.card').querySelector('.card-text').innerText;
        document.getElementById('editPostId').value = postId;
        document.getElementById('editPostContent').value = postContent;
        $('#editPostModal').modal('show');
    }
    
    // Handle edit post form submission
    document.getElementById('editPostForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent default form submission

        const formData = new FormData(this);
        fetch(this.action, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message); // Alert the success message
                location.href = data.redirect_url; // Redirect after successful update
            } else {
                alert('Error: ' + data.message); // Alert for errors
            }
        })
        .catch(error => console.error('Error:', error));
    });




    // Confirm delete post function
    function confirmDelete() {
        const postId = document.getElementById('deletePostId').value; // Get the post ID from hidden input

        fetch(`/esas/esas_moderator/actions/delete_post_action.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ post_id: postId }) // Send post ID as JSON
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message); // Alert the success message
                location.reload(); // Reload the page to reflect changes
            } else {
                alert('Error: ' + data.message); // Alert for errors
            }
        })
        .catch(error => console.error('Error:', error));
    }


    // Function to open the Delete Post modal
    function deletePost(button) {
        const postId = button.getAttribute('data-post-id');
        document.getElementById('deletePostId').value = postId; // Set post ID
        $('#deletePostModal').modal('show'); // Show modal
    }

    
</script>


<script>
    document.getElementById('postForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent default form submission

        const formData = new FormData(this); // Get form data
        fetch(this.action, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json()) // Parse JSON response
        .then(data => {
            if (data.success) {
                alert(data.message); // Show success message
                window.location.href = data.redirect_url; // Redirect after successful post creation
            } else {
                alert('Error: ' + data.message); // Show error message
            }
        })
        .catch(error => console.error('Error:', error)); // Log errors to console
    });
</script>
