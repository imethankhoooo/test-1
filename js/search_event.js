// search_event.js
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const filterOptions = document.querySelectorAll('input[name="filter"]');
    const eventGrid = document.querySelector('.event-grid');

    function fetchEvents() {
        const searchTerm = searchInput.value;
        const selectedFilter = document.querySelector('input[name="filter"]:checked').value;

        fetch(`search_events.php?search=${searchTerm}&filter=${selectedFilter}`)
            .then(response => response.json())
            .then(events => {
                eventGrid.innerHTML = '';
                events.forEach(event => {
                    const eventDate = new Date(event.event_date).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
                    const eventCard = `
                        <div class="event-card">
                            <img class="event-image" src="${event.banner_image}" alt="Event Image">
                            <div class="event-info">
                                <h2 class="event-name">${event.event_name}</h2>
                                <p class="event-location">${event.location}</p>
                                <div class="event-details">
                                    <div class="event-date">
                                        <span class="event-date-label">Date</span>
                                        <span class="event-date-value">${eventDate}</span>
                                    </div>
                                    <div class="event-tickets">
                                        <span class="event-tickets-label">Tickets Sold</span>
                                        <span class="event-tickets-value">${event.ticket_count}</span>
                                    </div>
                                </div>
                                <a href="Event.php?event_id=${event.event_id}" class="event-button">Learn More</a>
                            </div>
                        </div>
                    `;
                    eventGrid.innerHTML += eventCard;
                });
            })
            .catch(error => console.error('Error:', error));
    }

    searchInput.addEventListener('input', fetchEvents);
    filterOptions.forEach(option => option.addEventListener('change', fetchEvents));

    // Initial fetch
    fetchEvents();
});