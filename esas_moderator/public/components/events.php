<div class="events-section">
                        <button type="button" class="btn btn-info p-0" id="addEventBtn" data-toggle="modal" data-target="#addEventModal"
                            style="position: absolute; color: white; width: 30px; height: 30px; border-radius: 10%;">
                            <i class="fas fa-plus"></i>
                        </button>
                        <h5 class="text-muted mb-3" style="text-align: center; font-size: 1.2em;">Upcoming Events</h5>
                        

                        <!-- Calendar Section -->
                        <div id="calendar" style="margin-bottom: 20px; text-align: center;"></div>

                        <!-- Static Event List -->
                        <div class="event-list" id="eventList">
                            <!-- Events will be dynamically inserted here -->
                        </div>
                    </div>


                    <!-- Modal for Adding Events -->
<div class="modal fade" id="addEventModal" tabindex="-1" role="dialog" aria-labelledby="addEventModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addEventModalLabel">Add New Event</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="eventForm" action="../actions/add_event.php" method="POST">
                    <input type="hidden" name="club_id" value="<?php echo $club_id; ?>">
                    <input type="hidden" name="moderator_id" value="<?php echo $moderator_id; ?>">
                    
                    <div class="form-group mb-2">
                        <label for="eventTitle">Title</label>
                        <input type="text" class="form-control" id="eventTitle" name="title" required>
                    </div>
                    <div class="form-group mb-2">
                        <label for="eventDescription">Description</label>
                        <textarea class="form-control" id="eventDescription" name="description" required></textarea>
                    </div>
                    <div class="form-group mb-2">
                        <label for="eventDate">Date</label>
                        <input type="date" class="form-control" id="eventDate" name="date" required>
                    </div>
                    <div class="form-group mb-2">
                        <label for="eventTime">Time</label>
                        <input type="time" class="form-control" id="eventTime" name="time" required>
                    </div>
                    <div class="form-group mb-2">
                        <label for="eventLocation">Location</label>
                        <input type="text" class="form-control" id="eventLocation" name="location" required>
                    </div>
                    <div class="form-group mb-2">
                        <label for="registrationLink">Registration Link</label>
                        <input type="url" class="form-control" id="registrationLink" name="registrationLink">
                    </div>
                    <button type="submit" class="btn btn-primary">Add Event</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('eventForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent default form submission

        // Get form data
        var formData = new FormData(this);

        // Perform AJAX request
        fetch('../actions/add_event.php', { 
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Redirect on success
                window.location.href = '/esas/esas_moderator/public/home.php';
            } else {
                // Handle the error
                alert('Error: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
</script>




<script>

     // Function to create a simple calendar
function createCalendar(month, year, events) {
    const calendarDiv = document.getElementById('calendar');
    calendarDiv.innerHTML = ''; // Clear existing calendar

    const monthNames = [
        "January", "February", "March", "April", "May", "June", 
        "July", "August", "September", "October", "November", "December"
    ];
    
    // Header for the month and year
    const header = document.createElement('h4');
    header.innerText = `${monthNames[month]} ${year}`;
    header.style.textAlign = 'center';
    header.style.fontSize = '1.2em'; // Increased header font size for emphasis
    header.style.fontWeight = 'bold'; 
    header.style.color = '#333'; 
    calendarDiv.appendChild(header);

    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const firstDay = new Date(year, month, 1).getDay();

    // Create a styled table for the calendar
    const table = document.createElement('table');
    table.style.width = '100%';
    table.style.borderCollapse = 'collapse'; 
    table.style.fontSize = '12px'; // Consistent font size
    table.style.color = '#555'; // Consistent text color
    const tbody = document.createElement('tbody');

    // Create header row for days of the week
    const daysRow = document.createElement('tr'); 
    ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"].forEach(day => {
        const th = document.createElement('th');
        th.innerText = day;
        th.style.color = '#ff6b6b'; 
        th.style.padding = '2px'; // Padding for header cells
        th.style.fontWeight = 'bold'; 
        th.style.textAlign = 'center'; 
        th.style.fontSize = '1em'; // Slightly larger font size for day headers
        daysRow.appendChild(th);
    });
    tbody.appendChild(daysRow);

    let row = document.createElement('tr');
    for (let i = 0; i < firstDay; i++) {
        const td = document.createElement('td');
        td.style.border = '1px solid #e6e6e6'; // Border for empty cells
        row.appendChild(td);
    }

    // Fill calendar with dates
    for (let date = 1; date <= daysInMonth; date++) {
        const td = document.createElement('td');
        td.innerText = date;
        td.style.padding = '5px'; // Adjusted padding for better spacing
        td.style.textAlign = 'center'; 
        td.style.fontSize = '1em'; // Increased font size for dates

        // Highlight event dates
        const eventDate = `${month + 1}/${date}/${year}`; // Use MM/DD/YYYY format
        const formattedEventDates = events.map(event => {
            const parts = event.split(' ');
            const day = parts[1].replace(',', ''); // Extract day
            const monthIndex = monthNames.indexOf(parts[0]) + 1; // Get month index
            return `${monthIndex}/${day}/${parts[2]}`; // Format as MM/DD/YYYY
        });

        if (formattedEventDates.includes(eventDate)) {
            td.style.backgroundColor = '#007bff'; // Event highlight color
            td.style.color = '#fff'; // Text color for event dates
            td.style.borderRadius = '50%'; // Circular effect
            td.style.padding = '10px'; // Increased padding for event dates
            td.style.margin = '5px'; // Margin for spacing
        } else {
            td.style.backgroundColor = '#f9f9f9'; // Light background for normal days
            td.style.color = '#555'; 
            td.style.border = '1px solid #e6e6e6'; // Border for normal days
        }

        row.appendChild(td);
        if (row.children.length === 7) { // If 7 days are filled
            tbody.appendChild(row);
            row = document.createElement('tr'); // Start a new row
        }
    }
    tbody.appendChild(row); // Append the last row if it has any remaining days
    table.appendChild(tbody);
    calendarDiv.appendChild(table);

    // Style for the calendar container
    calendarDiv.style.border = '1px solid #e6e6e6'; // Outer border for the calendar
    calendarDiv.style.padding = '15px'; // Padding for the calendar container
    calendarDiv.style.borderRadius = '10px'; // Rounded corners for the calendar container
    calendarDiv.style.boxShadow = '0 1px 3px rgba(0,0,0,0.1)'; // Subtle shadow for depth
    calendarDiv.style.backgroundColor = '#f9f9f9'; // Background color for the calendar
}


// Call this function after fetching events
function fetchEvents() {
    const clubId = "<?php echo $club_id; ?>"; // Get the club_id from PHP

    fetch(`/esas/esas_moderator/apis/events-api.php?club_id=${clubId}`)
        .then(response => response.json())
        .then(data => {
            const eventList = document.getElementById('eventList');
            eventList.innerHTML = ''; // Clear the existing content
            
            const eventDates = []; // Array to store event dates
            
            if (data.length === 0) {
                // No events found
                eventList.innerHTML = `
                    <div style="text-align: center; margin-top: 30px;">
                        <i class="fas fa-calendar-times" style="font-size: 50px; color: #ff6b6b;"></i>
                        <p style="color: #666; margin-top: 10px;">No upcoming events.<br>Please stay tuned!</p>
                    </div>
                `;
                document.getElementById('calendar').style.display = 'none'; // Hide calendar
                return; // Exit the function early
            }

            // If events are present, show the calendar
            document.getElementById('calendar').style.display = 'block'; // Show calendar
            
            data.forEach(event => {
                const eventDate = new Date(event.date);
                const monthNames = [
                    "January", "February", "March", "April", "May", "June", 
                    "July", "August", "September", "October", "November", "December"
                ];
                const formattedDate = `${monthNames[eventDate.getMonth()]} ${eventDate.getDate().toString().padStart(2, '0')}, ${eventDate.getFullYear()}`; // Format the date

                eventDates.push(formattedDate); // Add date to the array

                const eventItem = document.createElement('div');
                eventItem.className = 'event-item';
                eventItem.style.padding = '10px';
                eventItem.style.borderBottom = '1px solid #ddd';
                eventItem.style.marginBottom = '10px';

                eventItem.innerHTML = `
                    <h6 style="color: #007bff;">${event.title}</h6> 
                    <p style="margin: 0; color: #666;">${formattedDate}</p>
                    <p><a href="#" class="btn btn-link" style="padding: 0; color: #007bff;" onclick="showEventDetails(${event.event_id})">View Details</a></p>
                `;
                eventList.appendChild(eventItem);
            });

            // Create the calendar for the current month
            const today = new Date();
            createCalendar(today.getMonth(), today.getFullYear(), eventDates);
        })
        .catch(error => {
            console.error('Error fetching events:', error);
        });
}




// Function to convert time to 12-hour format
function formatTime(time) {
    const [hours, minutes] = time.split(':').map(Number);
    const isAM = hours < 12;
    const formattedHours = hours % 12 || 12; // Converts 0 to 12 for midnight
    const ampm = isAM ? 'AM' : 'PM';
    return `${formattedHours}:${minutes < 10 ? '0' : ''}${minutes} ${ampm}`;
}

// Function to show event details in a modal
function showEventDetails(event_id) {
    fetch(`/esas/esas_moderator/apis/events-api.php?event_id=${event_id}`)
        .then(response => response.json())
        .then(event => {
            if (event) {
                // Populate modal with event details
                document.getElementById('eventTitle').innerText = event.title;
                document.getElementById('eventDescription').innerText = event.description;
                document.getElementById('eventDate').innerText = new Date(event.date).toLocaleDateString('en-US', { 
                    month: 'long', 
                    day: 'numeric', 
                    year: 'numeric' 
                });
                document.getElementById('eventTime').innerText = formatTime(event.time); // Use the new formatTime function
                document.getElementById('eventLocation').innerText = event.location;
                document.getElementById('eventLink').href = event.registrationLink;

                // Show the modal
                $('#eventDetailsModal').modal('show');
            }
        })
        .catch(error => {
            console.error('Error fetching event details:', error);
        });
}


    // Fetch events when the page loads
    window.onload = fetchEvents;
</script>




<!-- Modal for Event Details -->
<div class="modal fade" id="eventDetailsModal" tabindex="-1" role="dialog" aria-labelledby="eventDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #007bff; color: #ffffff;">
                <h5 class="modal-title" id="eventDetailsModalLabel">Event Details</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" style="color: #ffffff;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h6 id="eventTitle" style="color: #007bff;"></h6>
                <p id="eventDescription" style="margin-bottom: 1rem;"></p>
                <p><strong>Date:</strong> <span id="eventDate" style="color: #666;"></span></p>
                <p><strong>Time:</strong> <span id="eventTime" style="color: #666;"></span></p>
                <p><strong>Location:</strong> <span id="eventLocation" style="color: #666;"></span></p>
                <p><strong>Registration Link:</strong> <a id="eventLink" href="#" target="_blank" class="btn btn-primary btn-sm">Register</a></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
