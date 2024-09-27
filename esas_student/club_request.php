<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eSAS - Add Club</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
    <link href="../../../assets/css/jquery.dataTables.min.css" rel="stylesheet" />
    <script src="../../../assets/js/all.js" crossorigin="anonymous"></script>
    <script src="../../../assets/js/jquery-3.6.0.js"></script>
    <link href="../../../assets/css/styles.css" rel="stylesheet" />
    <link href="../../../assets/img/nbsclogo.png" rel="icon">
    <style>
        .wrapper {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 15px;
        }
        #coverPhotoPreview {
            width: 100%;
            height: auto;
            margin-top: 10px;
            display: none;
        }
        #fileIconPreview {
            width: 100px;
            height: auto;
            display: none;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="modal-title" id="requestClubModalLabel">Request for a New Club</h2>
                    <p>Please fill out this form and submit to add your club request.</p>
                    
                    <form id="clubRequestForm" action="../esas_student/actions/club_request_action.php" method="POST" enctype="multipart/form-data">
                        <div class="form-group mb-3">
                            <label for="clubName">Club Name</label>
                            <input type="text" name="clubName" class="form-control" id="clubName" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="goal">What is the primary goal of this club?</label>
                            <textarea name="goal" class="form-control" id="goal" rows="3" required></textarea>
                        </div>

                        <div class="form-group mb-3">
                            <label for="mission">Mission</label>
                            <textarea name="mission" class="form-control" id="mission" rows="3" required></textarea>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="vision">Vision</label>
                            <textarea name="vision" class="form-control" id="vision" rows="3" required></textarea>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="activities">Proposed Activities</label>
                            <textarea name="activities" class="form-control" id="activities" rows="2"></textarea>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="coverPhoto">Add a Cover Photo</label>
                            <input type="file" name="coverPhoto" class="form-control" id="coverPhoto" accept="image/*" required onchange="previewImage(event)">
                        </div>
                        
                        <div class="form-group mb-3">
                            <img id="coverPhotoPreview" src="#" alt="Cover Photo Preview" style="display:none; width: 50%; object-fit: cover;" />
                        </div>

                        <!-- New section for attaching a letter -->
                        <div class="form-group mb-3">
                            <label for="requestLetter">Attach a Request Letter</label>
                            <input type="file" name="requestLetter" class="form-control" id="requestLetter" accept=".pdf,.doc,.docx" required onchange="previewFile(event)">
                            <small class="form-text text-muted">Accepted formats: PDF, DOC, DOCX.</small>
                        </div>

                        <div class="form-group mb-3">
                            <!-- PDF or DOC icon Preview will appear here -->
                            <img id="fileIconPreview" src="#" alt="File Icon Preview" />
                            <p id="fileNamePreview" style="display:none;"></p> <!-- For showing the filename -->
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="./club_requests.php" class="btn btn-secondary mr-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- Preview Image Function -->
    <script>
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function(){
                var output = document.getElementById('coverPhotoPreview');
                output.src = reader.result;
                output.style.display = 'block';
            }
            reader.readAsDataURL(event.target.files[0]);
        }

        // Function to preview PDF or Word icons
        function previewFile(event) {
            var file = event.target.files[0];
            var fileIconPreview = document.getElementById('fileIconPreview');
            var fileNamePreview = document.getElementById('fileNamePreview');

            // Clear previous previews
            fileIconPreview.style.display = 'none';
            fileNamePreview.style.display = 'none';

            // Detect file type
            if (file.type === "application/pdf") {
                // Show PDF icon
                fileIconPreview.src = "../esas_student/icons/ICON_PDF.png"; // Path to your PDF icon
                fileIconPreview.style.display = 'block';
            } else if (file.type === "application/msword" || file.type === "application/vnd.openxmlformats-officedocument.wordprocessingml.document") {
                // Show Word document icon
                fileIconPreview.src = "../esas_student/icons/ICON_WORD.png"; // Path to your Word icon
                fileIconPreview.style.display = 'block';
            } else {
                // For other file types, show just the filename
                fileNamePreview.textContent = "Selected File: " + file.name;
                fileNamePreview.style.display = 'block';
            }
        }
    </script>
    
    <!-- Bootstrap JS and Dependencies -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
