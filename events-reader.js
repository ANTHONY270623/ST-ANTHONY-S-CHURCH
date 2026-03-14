// Events Reader for Homepage
class EventsReader {
    constructor() {
        this.storageKey = 'churchEvents';
        this.activeKey = 'activeEvents';
        this.init();
    }

    init() {
        this.loadEvents();
        this.bindEvents();
    }

    bindEvents() {
        // Listen for updates from events manager
        window.addEventListener('message', (event) => {
            if (event.data.type === 'EVENTS_UPDATED') {
                this.loadEvents();
            }
        });

        // Listen for storage changes (when events manager updates)
        window.addEventListener('storage', (event) => {
            if (event.key === this.storageKey) {
                this.loadEvents();
            }
        });

        // Auto-refresh every 30 seconds
        setInterval(() => {
            this.loadEvents();
        }, 30000);
    }

    parseEventDateTime(eventDate, eventTime) {
        // Handle both new "HH:MM AM/PM" format and old "HH:MM" format
        if (eventTime.includes('AM') || eventTime.includes('PM')) {
            const [time, ampm] = eventTime.split(' ');
            const [hour, minute] = time.split(':');
            let hour24 = parseInt(hour);
            
            if (ampm === 'PM' && hour24 !== 12) {
                hour24 += 12;
            } else if (ampm === 'AM' && hour24 === 12) {
                hour24 = 0;
            }
            
            const eventDateTime = new Date(eventDate);
            eventDateTime.setHours(hour24, parseInt(minute), 0, 0);
            return eventDateTime;
        } else {
            // Backwards compatibility with 24-hour format
            return new Date(eventDate + 'T' + eventTime);
        }
    }

    loadEvents() {
        try {
            const stored = localStorage.getItem(this.storageKey);
            if (!stored) {
                console.log('No events found, using default events display');
                return;
            }

            const events = JSON.parse(stored);
            const activeEvents = events
                .filter(e => e.status === 'active')
                .filter(e => {
                    // Only show upcoming events (today and future)
                    const eventDateTime = this.parseEventDateTime(e.eventDate, e.eventTime);
                    const today = new Date();
                    today.setHours(0, 0, 0, 0); // Start of today
                    return eventDateTime >= today;
                })
                .sort((a, b) => {
                    const dateA = this.parseEventDateTime(a.eventDate, a.eventTime);
                    const dateB = this.parseEventDateTime(b.eventDate, b.eventTime);
                    return dateA - dateB;
                })
                .slice(0, 6); // Show only next 6 events

            console.log('Loading events:', activeEvents);
            this.updateEventsSection(activeEvents);
            
            // Store active events for other pages
            localStorage.setItem(this.activeKey, JSON.stringify(activeEvents));
        } catch (error) {
            console.error('Error loading events:', error);
        }
    }

    updateEventsSection(events) {
        const container = document.querySelector('#events .forms-grid');
        if (!container) return;

        if (events.length === 0) {
            container.innerHTML = `
                <div class="form-card">
                    <div class="form-icon"><i class="fas fa-info-circle"></i></div>
                    <h3>No Upcoming Events</h3>
                    <p>There are currently no scheduled events.</p>
                    <p>Check back later for updates.</p>
                </div>
            `;
            return;
        }

        container.innerHTML = events.map(event => {
            const eventDateTime = this.parseEventDateTime(event.eventDate, event.eventTime);
            const isToday = event.eventDate === new Date().toISOString().split('T')[0];
            const hasPoster = event.poster ? true : false;
            
            return `
                <div class="form-card ${isToday ? 'today-event' : ''} ${hasPoster ? 'has-poster' : ''}" 
                     ${hasPoster ? `onclick="showEventPoster(${event.id})"` : ''}>
                    <div class="form-icon">
                        <i class="fas fa-${this.getEventIcon(event.type)}"></i>
                        ${hasPoster ? '<span class="poster-indicator"><i class="fas fa-image"></i></span>' : ''}
                    </div>
                    <h3>${this.escapeHtml(event.title)}</h3>
                    <p class="event-date">
                        <i class="fas fa-calendar"></i>
                        ${eventDateTime.toLocaleDateString('en-US', { 
                            weekday: 'short',
                            month: 'short', 
                            day: 'numeric',
                            year: 'numeric'
                        })}
                        ${isToday ? ' (Today)' : ''}
                    </p>
                    <p class="event-time">
                        <i class="fas fa-clock"></i>
                        ${event.eventTime}
                    </p>
                    <p class="event-location">
                        <i class="fas fa-map-marker-alt"></i>
                        ${this.escapeHtml(event.location)}
                    </p>
                    <p class="event-description">${this.escapeHtml(event.description)}</p>
                    ${hasPoster ? '<div class="click-hint"><i class="fas fa-mouse-pointer"></i> Click to view poster</div>' : ''}
                </div>
            `;
        }).join('');
    }

    getEventIcon(type) {
        const icons = {
            'mass': 'church',
            'celebration': 'star',
            'meeting': 'users',
            'special': 'gift'
        };
        return icons[type] || 'calendar-alt';
    }

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Public method to force refresh
    forceRefresh() {
        console.log('Force refreshing events...');
        this.loadEvents();
    }

    // Get event by ID for poster viewing
    getEventById(eventId) {
        const stored = localStorage.getItem(this.storageKey);
        if (stored) {
            const events = JSON.parse(stored);
            return events.find(e => e.id == eventId);
        }
        return null;
    }
}
