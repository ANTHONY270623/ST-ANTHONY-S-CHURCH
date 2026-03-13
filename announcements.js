// announcements.js - Dynamic announcements loading for frontend

class AnnouncementsManager {
    constructor() {
        this.apiUrl = 'announcements_api.php';
        this.loadAnnouncements();
    }

    async loadAnnouncements() {
        try {
            // Load ticker announcements
            await this.loadTickerAnnouncements();
            
            // Load updates section announcements
            await this.loadUpdatesAnnouncements();
            
        } catch (error) {
            console.error('Error loading announcements:', error);
            // Fall back to static content if API fails
        }
    }

    async loadTickerAnnouncements() {
        try {
            const response = await fetch(`${this.apiUrl}?action=ticker`);
            const data = await response.json();
            
            if (data.success && data.ticker) {
                // Update home ticker
                const homeTicker = document.querySelector('.home-ticker .ticker-content');
                if (homeTicker) {
                    homeTicker.innerHTML = data.ticker;
                }
                
                // Update mobile ticker
                const mobileTicker = document.querySelector('.mobile-ticker');
                if (mobileTicker) {
                    mobileTicker.innerHTML = data.ticker.replace(/&nbsp;/g, ' ');
                }
                
                // Update updates section ticker
                const updatesTicker = document.querySelector('.updates-ticker .ticker-content');
                if (updatesTicker) {
                    updatesTicker.innerHTML = data.ticker;
                }
            }
        } catch (error) {
            console.error('Error loading ticker announcements:', error);
        }
    }

    async loadUpdatesAnnouncements() {
        try {
            const response = await fetch(`${this.apiUrl}?action=updates`);
            const data = await response.json();
            
            if (data.success && data.updates && data.updates.length > 0) {
                // Update announcements in updates section
                const announcementsContainer = document.querySelector('.announcements');
                if (announcementsContainer) {
                    announcementsContainer.innerHTML = '';
                    
                    data.updates.forEach((update, index) => {
                        const announcementItem = document.createElement('p');
                        announcementItem.className = 'announcement-item';
                        announcementItem.innerHTML = `${index + 1}. ${update.text}`;
                        announcementsContainer.appendChild(announcementItem);
                    });
                }
            }
        } catch (error) {
            console.error('Error loading updates announcements:', error);
        }
    }

    // Method to refresh announcements (can be called from admin panel)
    async refresh() {
        await this.loadAnnouncements();
    }

    // Method to add real-time update capability
    startAutoRefresh(intervalMinutes = 5) {
        setInterval(() => {
            this.loadAnnouncements();
        }, intervalMinutes * 60 * 1000);
    }
}

// Initialize announcements manager when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Only initialize if we're on a page that needs announcements
    if (document.querySelector('.ticker-content') || 
        document.querySelector('.announcements') || 
        document.querySelector('.mobile-ticker')) {
        
        const announcementsManager = new AnnouncementsManager();
        
        // Auto-refresh every 5 minutes to get latest announcements
        announcementsManager.startAutoRefresh(5);
        
        // Make it globally available for manual refresh
        window.announcementsManager = announcementsManager;
    }
});

// Function to manually refresh announcements (for admin use)
function refreshAnnouncements() {
    if (window.announcementsManager) {
        window.announcementsManager.refresh();
        console.log('Announcements refreshed');
    }
}