// Static Announcements Reader for Homepage
class AnnouncementsReader {
    constructor() {
        this.storageKey = 'churchAnnouncements';
        this.activeKey = 'activeAnnouncements';
        this.init();
    }

    init() {
        this.loadAnnouncements();
        this.bindEvents();
    }

    bindEvents() {
        // Listen for updates from announcements manager
        window.addEventListener('message', (event) => {
            if (event.data.type === 'ANNOUNCEMENTS_UPDATED') {
                this.loadAnnouncements();
            }
        });

        // Listen for storage changes (when announcements manager updates)
        window.addEventListener('storage', (event) => {
            if (event.key === this.storageKey) {
                this.loadAnnouncements();
            }
        });

        // Auto-refresh every 30 seconds
        setInterval(() => {
            this.loadAnnouncements();
        }, 30000);
    }

    loadAnnouncements() {
        try {
            const stored = localStorage.getItem(this.storageKey);
            if (!stored) {
                console.log('No announcements found, creating defaults');
                this.createDefaultAnnouncements();
                return;
            }

            const announcements = JSON.parse(stored);
            const activeAnnouncements = announcements
                .filter(a => a.status === 'active')
                .sort((a, b) => a.displayOrder - b.displayOrder);

            console.log('Loading announcements:', activeAnnouncements);
            this.updateUI(activeAnnouncements);
            
            // Store active announcements for other pages
            localStorage.setItem(this.activeKey, JSON.stringify(activeAnnouncements));
        } catch (error) {
            console.error('Error loading announcements:', error);
            this.createDefaultAnnouncements();
        }
    }

    createDefaultAnnouncements() {
        const defaults = [
            {
                id: 1,
                title: 'UPDATE 1',
                content: 'ON 27th JULY 2025 (SUNDAY) MASS WILL BE ON NEW CHAPEL.',
                type: 'update',
                status: 'active',
                displayOrder: 1,
                createdAt: new Date().toISOString()
            },
            {
                id: 2,
                title: 'UPDATE 2',
                content: 'FR SEBASTAIN WIL BE NOT AVAILABLE FOR ONE WEEK',
                type: 'update',
                status: 'active',
                displayOrder: 2,
                createdAt: new Date().toISOString()
            },
            {
                id: 3,
                title: 'UPDATE 3',
                content: 'HOUSE BLESSINGS ARE GOING ON PLEASE CONTACT COUNCIL MEMBERS.',
                type: 'update',
                status: 'active',
                displayOrder: 3,
                createdAt: new Date().toISOString()
            }
        ];
        
        localStorage.setItem(this.storageKey, JSON.stringify(defaults));
        this.loadAnnouncements();
    }

    updateUI(announcements) {
        this.updateTicker(announcements);
        this.updateAnnouncementsSection(announcements);
        this.updateScrollingText(announcements);
        this.updateMobileTicker(announcements);
        this.updateHomeTicker(announcements);
    }

    updateTicker(announcements) {
        // Update the main updates section ticker
        const ticker = document.querySelector('.updates-ticker .ticker-content');
        if (ticker && announcements.length > 0) {
            ticker.innerHTML = announcements
                .map(a => `${a.title}: ${a.content}`)
                .join(' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ');
        }
    }

    updateMobileTicker(announcements) {
        // Update mobile ticker
        const mobileTicker = document.querySelector('.mobile-ticker');
        if (mobileTicker && announcements.length > 0) {
            mobileTicker.innerHTML = announcements
                .map(a => `${a.title}: ${a.content}`)
                .join('\n        ');
        }
    }

    updateHomeTicker(announcements) {
        // Update home section ticker
        const homeTicker = document.querySelector('.home-ticker .ticker-content');
        if (homeTicker && announcements.length > 0) {
            homeTicker.innerHTML = announcements
                .map(a => `${a.title}: ${a.content}`)
                .join(' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;') + '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
        }
    }

    updateAnnouncementsSection(announcements) {
        const container = document.querySelector('.announcements');
        if (!container) return;

        if (announcements.length === 0) {
            container.innerHTML = `
                <div class="announcement-item">
                    <h3>No Active Announcements</h3>
                    <p>There are currently no active announcements. Check back later for updates.</p>
                </div>
            `;
            return;
        }

        container.innerHTML = announcements.map(announcement => `
            <div class="announcement-item">
                <h3>
                    <i class="fas fa-${this.getTypeIcon(announcement.type)}"></i>
                    ${this.escapeHtml(announcement.title)}
                </h3>
                <p>${this.escapeHtml(announcement.content)}</p>
                <small class="announcement-meta">
                    <i class="fas fa-clock"></i>
                    ${new Date(announcement.createdAt).toLocaleDateString()}
                </small>
            </div>
        `).join('');
    }

    updateScrollingText(announcements) {
        // Update the scrolling text in the top banner if it exists
        const scrollingElement = document.querySelector('.scrolling-text');
        if (scrollingElement && announcements.length > 0) {
            scrollingElement.innerHTML = announcements
                .map(a => `${a.title}: ${a.content}`)
                .join(' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ');
        }
    }

    getTypeIcon(type) {
        const icons = {
            'announcement': 'bullhorn',
            'update': 'sync-alt',
            'alert': 'exclamation-triangle'
        };
        return icons[type] || 'info-circle';
    }

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Public method to force refresh
    forceRefresh() {
        console.log('Force refreshing announcements...');
        this.loadAnnouncements();
    }
}
