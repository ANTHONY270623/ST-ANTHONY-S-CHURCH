// Forms dropdown navigation - smooth scrolling to forms section
document.addEventListener('DOMContentLoaded', function() {
    console.log('Forms navigation loaded');
    
    // Add smooth scrolling for all dropdown content links (forms, login, etc.)
    const dropdownLinks = document.querySelectorAll('.dropdown-content a[href^="#"]');
    console.log('Found', dropdownLinks.length, 'dropdown links');
    
    dropdownLinks.forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId);
            
            console.log('Dropdown link clicked, target:', targetId);
            
            if (targetElement) {
                // Close mobile menu if open
                const hamburger = document.getElementById('hamburger');
                const navMenu = document.getElementById('navMenu');
                
                if (hamburger && navMenu) {
                    hamburger.classList.remove('active');
                    navMenu.classList.remove('active');
                }
                
                // Get navbar height for offset
                const navbar = document.querySelector('.navbar');
                const navbarHeight = navbar ? navbar.offsetHeight : 0;
                
                // Calculate position with offset
                const targetPosition = targetElement.getBoundingClientRect().top + window.pageYOffset - navbarHeight - 20;
                
                // Smooth scroll to target
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
                
                console.log('Scrolled to:', targetId);
                
                // Highlight the target form card briefly
                targetElement.style.transition = 'all 0.3s ease';
                targetElement.style.boxShadow = '0 0 20px rgba(139, 0, 0, 0.5)';
                
                setTimeout(function() {
                    targetElement.style.boxShadow = '';
                }, 2000);
                
            } else {
                console.error('Target element not found:', targetId);
            }
        });
    });
});
