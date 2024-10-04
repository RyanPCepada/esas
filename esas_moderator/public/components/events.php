<div class="events-section">

<div class="row d-flex align-items-center">
    <div class="col-10">
        <h5 class="text-muted mb-3" style="font-size: 1.2em;">Upcoming Events</h5>
    </div>
    <div class="col-2">
        <button type="button" class="btn btn-info p-0" id="addEventBtn" data-toggle="modal" data-target="#addEventModal"
            style="color: white; width: 30px; height: 30px; margin-top: -20px; border-radius: 10%; padding: 0;">
            <i class="fas fa-plus"></i>
        </button>
    </div>
</div>





                        <!-- Calendar Section -->
                        <div id="calendar" style="margin-bottom: 20px; text-align: center;"></div>

                        <!-- Static Event List -->
                        <div class="event-list" id="eventList">
                            <!-- Events will be dynamically inserted here -->
                        </div>
                    </div>
       

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

    // Get today's date with timezone Asia/Manila
    const today = new Date(new Date().toLocaleString("en-US", { timeZone: "Asia/Manila" }));
    const todayFormatted = `${today.getMonth() + 1}/${today.getDate()}/${today.getFullYear()}`;
    const endDate = new Date(today);
    endDate.setDate(today.getDate() + 5); // Set end date to 5 days from today

    // Fill calendar with dates
    for (let date = 1; date <= daysInMonth; date++) {
        const td = document.createElement('td');
        td.innerText = date;
        td.style.padding = '5px'; 
        td.style.textAlign = 'center'; 
        td.style.fontSize = '1em'; 

        // Highlight event dates
        const eventDate = new Date(year, month, date);
        const formattedEventDates = events.map(event => {
            const parts = event.split(' ');
            const day = parts[1].replace(',', '');
            const monthIndex = monthNames.indexOf(parts[0]) + 1;
            return new Date(parts[2], monthIndex - 1, day); // Return Date object for comparison
        });

        // Check if today's date has events
        const isToday = today.getDate() === date && today.getMonth() === month && today.getFullYear() === year;

        // Encircle today's date if there are events
        if (isToday && formattedEventDates.some(d => d.getTime() === today.getTime())) {
            td.style.backgroundColor = 'yellowgreen';
            td.style.color = '#fff'; 
            td.style.borderRadius = '50%'; 
            td.style.padding = '10px'; 
            td.style.margin = '5px'; 
        }

        // Check if it's an event date and highlight accordingly
        if (formattedEventDates.some(d => d.getTime() === eventDate.getTime())) {
            if (eventDate >= today && eventDate <= endDate) {
                td.style.backgroundColor = '#007bff';
                td.style.color = '#fff';
                td.style.borderRadius = '50%'; 
                td.style.padding = '10px'; 
                td.style.margin = '5px'; 
            } else if (eventDate > endDate) {
                // Encircle future event dates beyond the range
                td.style.backgroundColor = '#007bff';
                td.style.color = '#fff';
                td.style.borderRadius = '50%'; 
                td.style.padding = '10px'; 
                td.style.margin = '5px'; 
            }
        } else {
            td.style.backgroundColor = '#f9f9f9'; 
            td.style.color = '#555'; 
            td.style.border = '1px solid #e6e6e6'; 
        }

        row.appendChild(td);
        if (row.children.length === 7) {
            tbody.appendChild(row);
            row = document.createElement('tr');
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

                // Check if event date is today or tomorrow
                const today = new Date();
                const tomorrow = new Date();
                tomorrow.setDate(today.getDate() + 1);

                let displayDate = formattedDate; // Default display date
                if (eventDate.toDateString() === today.toDateString()) {
                    displayDate = "Today"; // Change to "Today" for today's date
                } else if (eventDate.toDateString() === tomorrow.toDateString()) {
                    displayDate = "Tomorrow"; // Change to "Tomorrow" for tomorrow's date
                }

                const eventCard = document.createElement('div');
                eventCard.className = 'card mb-3'; // Bootstrap card class
                eventCard.style.maxWidth = '540px'; // Set a max width for the card

                // Cover photo with 50% opacity and square shape
                const eventIcon = `
                    <div style="position: relative; width: 100%; height: 0; padding-top: 100%; overflow: hidden;">
                        <img src="/esas/esas_moderator/images/<?php echo htmlspecialchars($coverPhoto); ?>"
                             alt="Cover Photo" 
                             class="img-fluid" 
                             style="position: absolute; top: 0; left: 12px; width: 100%; height: 100%; object-fit: cover; opacity: 0.5;">
                    </div>
                `;

                eventCard.innerHTML = `
                    <div class="row no-gutters">
                        <div class="col-md-4" style="text-align: center;">
                            ${eventIcon} <!-- Inserted Cover Photo -->
                        </div>
                        <div class="col-md-8">
                            <div class="card-body" style="position: relative;">
                                <style>.event-ellipsis:hover{background-color: lightgrey;"}</style>
                                <div class="event-ellipsis text-center" style="position: absolute; top: 10px; right: 12px; cursor: pointer; width: 22px; border-radius: 50%;" onclick="toggleDropdown(${event.event_id})">
                                    <i class="fas fa-ellipsis-v" style="color: #666;"></i>
                                </div>
                                <div id="dropdown-${event.event_id}" style="display: none; position: absolute; top: 30px; right: 20px; background-color: white; border: 1px solid #ccc; border-radius: 4px; z-index: 1000;">
                                    <a href="#" class="dropdown-item" onclick="editEvent(${event.event_id})" style="display: block; padding: 5px 10px; color: #007bff;">Edit</a>
                                    <a href="#" class="dropdown-item" onclick="openDeleteModal(${event.event_id})" style="display: block; padding: 5px 10px; color: #ff6b6b;">Delete</a>
                                </div>
                                <h5 class="card-title" style="color: #007bff;">${event.title}</h5>
                                <p class="card-text" style="margin: 0; color: #666;">${displayDate}</p> <!-- Display "Today" or "Tomorrow" -->
                                <p class="card-text" style="display: flex; align-items: center; justify-content: flex-start;">
                                    <a href="#" class="btn btn-link" onclick="showEventDetails(${event.event_id})" style="white-space: nowrap; padding: 0; margin-right: 40px;">View Details</a>
                                </p>
                            </div>
                        </div>
                    </div>
                `;

                eventList.appendChild(eventCard);
            });

            // Create the calendar for the current month
            const today = new Date();
            createCalendar(today.getMonth(), today.getFullYear(), eventDates);
        })
        .catch(error => {
            console.error('Error fetching events:', error);
        });
}




function toggleDropdown(eventId) {
    const dropdown = document.getElementById(`dropdown-${eventId}`);
    if (dropdown.style.display === "none" || dropdown.style.display === "") {
        dropdown.style.display = "block"; // Show the dropdown
    } else {
        dropdown.style.display = "none"; // Hide the dropdown
    }
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

                // Get the event date and format it for the modal
                const eventDate = new Date(event.date);
                const today = new Date();
                const tomorrow = new Date();
                tomorrow.setDate(today.getDate() + 1);

                let displayDate = eventDate.toLocaleDateString('en-US', { 
                    month: 'long', 
                    day: 'numeric', 
                    year: 'numeric' 
                }); // Default display date

                // Check if the event date is today or tomorrow
                if (eventDate.toDateString() === today.toDateString()) {
                    displayDate = "Today"; // Change to "Today" for today's date
                } else if (eventDate.toDateString() === tomorrow.toDateString()) {
                    displayDate = "Tomorrow"; // Change to "Tomorrow" for tomorrow's date
                }

                document.getElementById('eventDate').innerText = displayDate; // Set the formatted date
                document.getElementById('eventTime').innerText = formatTime(event.time); // Use the new formatTime function
                document.getElementById('eventLocation').innerText = event.location;
                document.getElementById('eventLink').href = event.registrationLink;
                document.getElementById('eventLinkText').innerText = event.registrationLink; // Display the link text

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
                <p><strong>Registration Link:</strong> <a id="eventLink" href="#" target="_blank" style="color: #007bff;"> 
                    <span id="eventLinkText"></span>
                </a></p> <!-- Display the link text -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
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
                <form id="eventForm" action="../actions/add_event_action.php" method="POST">
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


<!-- Modal for Editing Events -->
<div class="modal fade" id="editEventModal" tabindex="-1" role="dialog" aria-labelledby="editEventModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editEventModalLabel">Edit Event</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editEventForm" action="../actions/edit_event_action.php" method="POST">
                    <input type="hidden" name="club_id" value="<?php echo $club_id; ?>">
                    <input type="hidden" name="moderator_id" value="<?php echo $moderator_id; ?>">
                    <input type="hidden" name="event_id" id="editEventId">
                    <div class="form-group mb-2">
                        <label for="editEventTitle">Title</label>
                        <input type="text" class="form-control" id="editEventTitle" name="title" required>
                    </div>
                    <div class="form-group mb-2">
                        <label for="editEventDescription">Description</label>
                        <textarea class="form-control" id="editEventDescription" name="description" required></textarea>
                    </div>
                    <div class="form-group mb-2">
                        <label for="editEventDate">Date</label>
                        <input type="date" class="form-control" id="editEventDate" name="date" required>
                    </div>
                    <div class="form-group mb-2">
                        <label for="editEventTime">Time</label>
                        <input type="time" class="form-control" id="editEventTime" name="time" required>
                    </div>
                    <div class="form-group mb-2">
                        <label for="editEventLocation">Location</label>
                        <input type="text" class="form-control" id="editEventLocation" name="location" required>
                    </div>
                    <div class="form-group mb-2">
                        <label for="editRegistrationLink">Registration Link</label>
                        <input type="url" class="form-control" id="editRegistrationLink" name="registrationLink">
                    </div>
                    <button type="submit" class="btn btn-primary">Update Event</button>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this event?
      </div>
      <div class="modal-footer">
        <!-- Form for delete confirmation -->
        <form id="deleteForm" method="POST" action="/esas/esas_moderator/actions/delete_event_action.php">
            <input type="hidden" name="club_id" value="<?php echo $club_id; ?>">
            <input type="hidden" name="moderator_id" value="<?php echo $moderator_id; ?>">
          <input type="hidden" name="event_id" id="deleteEventId" value="">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
          <button type="submit" class="btn btn-danger">Yes, Delete</button>
        </form>
      </div>
    </div>
  </div>
</div>


<script>

function editEvent(event_id) {
    // Fetch event details from the server using the event_id
    fetch(`/esas/esas_moderator/apis/events-api.php?event_id=${event_id}`)
        .then(response => response.json())
        .then(event => {
            // Populate the edit modal fields
            document.getElementById('editEventId').value = event.event_id; // Assuming the event object contains an 'id' field
            document.getElementById('editEventTitle').value = event.title;
            document.getElementById('editEventDescription').value = event.description;
            document.getElementById('editEventDate').value = event.date.split('T')[0]; // Format date correctly
            document.getElementById('editEventTime').value = event.time; // Make sure this is the correct format
            document.getElementById('editEventLocation').value = event.location;
            document.getElementById('editRegistrationLink').value = event.registrationLink;

            // Show the edit modal
            $('#editEventModal').modal('show');
        })
        .catch(error => {
            console.error('Error fetching event details:', error);
        });
}

let eventToDelete = null;

function openDeleteModal(eventId) {
  // Set the hidden input value in the form to the event ID
  document.getElementById('deleteEventId').value = eventId;
  $('#deleteModal').modal('show'); // Show the modal
}


document.getElementById('confirmDeleteButton').addEventListener('click', function() {
  if (eventToDelete !== null) {
    deleteEvent(eventToDelete); // Call the delete function with the stored event ID
    $('#deleteModal').modal('hide'); // Hide the modal
  }
});

</script>