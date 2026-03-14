// Navigation functionality
document.addEventListener('DOMContentLoaded', function() {
    console.log('Navigation JS loaded successfully');
    
    // Get all navigation links
    const navLinks = document.querySelectorAll('.nav-link');
    console.log('Found', navLinks.length, 'navigation links');
    
    // Add click event listeners to each navigation link
    navLinks.forEach(function(link) {
        link.addEventListener('click', function(e) {
            console.log('Navigation link clicked:', this.textContent);
            
            // Only prevent default for dropdown toggles on mobile
            if (this.parentNode.classList.contains('dropdown') && 
                this.nextElementSibling && 
                window.innerWidth <= 767) {
                e.preventDefault();
                console.log('Mobile dropdown toggle - not changing active state');
                return;
            }
            
            // Get the current active link for debugging
            const previousActive = document.querySelector('.nav-link.active');
            console.log('Previous active link:', previousActive ? previousActive.textContent : 'none');
            
            // For regular links, remove active class from all links
            navLinks.forEach(function(navLink) {
                navLink.classList.remove('active');
                console.log('Removed active class from:', navLink.textContent);
            });
            
            // Add active class only to the clicked link
            this.classList.add('active');
            console.log('Added active class to:', this.textContent);
            
            // Close mobile menu if it's open
            const hamburger = document.getElementById('hamburger');
            const navMenu = document.getElementById('navMenu');
            
            if (hamburger && navMenu) {
                hamburger.classList.remove('active');
                navMenu.classList.remove('active');
            }
        });
    });
    
    // Handle hamburger menu
    const hamburger = document.getElementById('hamburger');
    const navMenu = document.getElementById('navMenu');
    
    if (hamburger && navMenu) {
        hamburger.addEventListener('click', function() {
            console.log('Hamburger menu clicked');
            hamburger.classList.toggle('active');
            navMenu.classList.toggle('active');
        });
    }
    
    // DEBUG: Just to be sure, add a direct handler to each nav link by ID
    const mainLinks = ['home', 'about', 'parish', 'prayer', 'updates', 'gallery', 'council', 'contact', 'login', 'forms'];
    
    mainLinks.forEach(function(id) {
        const linkElement = document.querySelector(`a[href="#${id}"]`);
        if (linkElement) {
            linkElement.addEventListener('click', function(e) {
                console.log(`Clicked on ${id} link via direct handler`);
                
                // Clear all active states
                document.querySelectorAll('.nav-link').forEach(function(link) {
                    link.classList.remove('active');
                });
                
                // Set this one as active
                this.classList.add('active');
            });
        }
    });
});