<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eSAS - Club Request</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 15px;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h2 class="mt-5 text-center">Request for a New Club</h2>
                <p>Please fill out this form and submit your request for a new club.</p>
                <form action="club_request_action.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="clubName">Club Name</label>
                        <input type="text" name="clubName" class="form-control" id="clubName" required>
                    </div>
                    <div class="form-group">
                    <label for="description">What is the primary goal of this club?</label>
                        <textarea name="description" class="form-control" id="description" rows="4" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="activities">Proposed Activities</label>
                        <textarea name="activities" class="form-control" id="activities" rows="3"></textarea>
                    </div>
                    <input type="submit" class="btn btn-primary" value="Submit Request">
                    <a href="#" onclick="history.back(); return false;"  class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
