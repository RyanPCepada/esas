<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eSAS - Departure Request Details</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
    <link href="../../../assets/css/jquery.dataTables.min.css" rel="stylesheet" />
    <script src="../../../assets/js/all.js" crossorigin="anonymous"></script>
    <script src="../../../assets/js/jquery-3.6.0.js"></script>
    <link href="../../../assets/css/styles.css" rel="stylesheet" />
    <link href="../../../assets/img/nbsclogo.png" rel="icon">
    <style>
        body {
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .wrapper {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 15px;
        }
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h3 {
            color: #343a40; /* Darker color for headings */
            font-size: 24px; /* Larger font size */
            text-align: center; /* Centered heading */
        }
        .event-item {
            border: 1px solid #e0e0e0; /* Light border */
            border-radius: 4px; /* Slightly rounded corners */
            padding: 15px;
            margin-bottom: 15px; /* Spacing between events */
            background-color: #fafafa; /* Light background for each event */
        }
        h2 {
            font-size: 24px; /* Size for event title */
            color: #007bff; /* Bootstrap primary color for titles */
        }
        p {
            font-size: 16px; /* Font size for paragraph text */
            color: #333; /* Darker color for better readability */
        }
        strong {
            color: #007bff; /* Bootstrap primary color for strong text */
        }
    </style>
</head>
<body>

<div class="wrapper">
    
<h3 class="text-muted mt-5 mb-3">Events from All Clubs</h3>
<div class="container-fluid container" id="events-container"></div>

<script>
    // Function to format the date to "Month Day, Year"
    function formatDate(dateString) {
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        return new Date(dateString).toLocaleDateString('en-US', options);
    }

    // Function to format time to "h:mm AM/PM"
    function formatTime(timeString) {
        const date = new Date(`1970-01-01T${timeString}`);
        return date.toLocaleTimeString('en-US', { hour: 'numeric', minute: 'numeric', hour12: true });
    }

    // Fetch events from the API
    fetch('/esas/esas_moderator/apis/events-all-clubs-api.php') // Ensure the path is correct
        .then(response => response.json())
        .then(data => {
            const eventsContainer = document.getElementById('events-container');
            eventsContainer.innerHTML = '';

            if (data.length > 0) {
                data.forEach(event => {
                    const eventElement = document.createElement('div');
                    eventElement.className = 'event-item';
                    eventElement.innerHTML = `
                        <h2 class="text-dark">${formatDate(event.date)}</h2>
                        <p><strong>Event:</strong> ${event.title}</p>
                        <p><strong>Club:</strong> ${event.clubName}</p>
                        <p><strong>Time:</strong> ${formatTime(event.timeStarts)} - ${formatTime(event.timeEnds)}</p>
                        <p><strong>Location:</strong> ${event.location}</p>
                    `;
                    eventsContainer.appendChild(eventElement);
                });
            } else {
                eventsContainer.innerHTML = '<p>No events available.</p>';
            }
        })
        .catch(error => {
            console.error('Error fetching events:', error);
            document.getElementById('events-container').innerHTML = '<p>Error fetching events.</p>';
        });
</script>

</div>

</body>
</html>
