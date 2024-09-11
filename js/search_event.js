document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const eventCardContainer = document.querySelector('.eventCard-Container');
    let allEvents = [];
    let debounceTimer;

    function fetchEvents(searchTerm) {
        fetch(`search_events.php?search=${encodeURIComponent(searchTerm)}`)
            .then(response => response.json())
            .then(events => {
                allEvents = events;
                displayEvents(events);
            })
            .catch(error => console.error('Error:', error));
    }

    function displayEvents(events) {
        eventCardContainer.innerHTML = ''; 
        events.forEach((event, index) => {
            const eventCard = createEventCard(event);
            eventCardContainer.appendChild(eventCard);
            setTimeout(() => {
                eventCard.style.opacity = '1';
                eventCard.style.transform = 'scale(1)';
            }, 50 * index);
        });
    }

    function createEventCard(event) {
        const card = document.createElement('div');
        card.className = 'eventCard';
        card.dataset.eventId = event.event_id;
        card.style.opacity = '0';
        card.style.transform = 'scale(0.8)';
        
        const imgSrc = event.banner_image ? event.banner_image : 'path/to/default/image.jpg';
        
        card.innerHTML = `
            <img class="eventCardImage" src="${imgSrc}" alt="Event Image">
            <div class="eventCardInfo">
                <h2>${event.event_name}</h2>
                <p>${event.location}</p><br>
                <a href="Event.php?event_id=${event.event_id}" class="button">View</a>
            </div>
        `;
        
        return card;
    }

    function filterEvents(searchTerm) {
        if (searchTerm.trim() === '') {
            fetchEvents('');
        } else {
            fetchEvents(searchTerm);
        }
    }

    searchInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            filterEvents(this.value);
        }, 300); 
    });


    fetchEvents('');
});
searchInput.addEventListener('focus', () => {
    searchInput.style.transform = 'scale(1.05)';
    searchInput.style.boxShadow = '0 0 0 4px rgba(79, 70, 229, 0.3), 0 4px 16px rgba(0, 0, 0, 0.1)';
});

searchInput.addEventListener('blur', () => {
    searchInput.style.transform = 'scale(1)';
    searchInput.style.boxShadow = '0 0 0 2px rgba(79, 70, 229, 0.1)';
});
