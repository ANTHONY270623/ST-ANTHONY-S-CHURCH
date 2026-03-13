/* Mobile viewport optimization */
(function() {
    // Function to fix viewport issues in various mobile browsers
    function fixViewport() {
        // Handle 100vh in mobile browsers (especially iOS Safari)
        let vh = window.innerHeight * 0.01;
        document.documentElement.style.setProperty('--vh', `${vh}px`);
        
        // Force redraw to address mobile rendering issues
        document.body.style.display = 'none';
        setTimeout(function() {
            document.body.style.display = '';
        }, 0);
        
        // Ensure proper scaling on orientation change
        const width = window.innerWidth;
        const meta = document.querySelector('meta[name="viewport"]');
        
        if (width < 375) {
            // For very small screens, adjust content to fit
            meta.setAttribute('content', 'width=375, initial-scale=' + (width / 375));
        } else {
            // Reset for larger screens
            meta.setAttribute('content', 'width=device-width, initial-scale=1.0');
        }
    }
    
    // Run on load
    window.addEventListener('load', fixViewport);
    
    // Run on resize and orientation change
    window.addEventListener('resize', fixViewport);
    window.addEventListener('orientationchange', fixViewport);
    
    // Add CSS variable for viewport height
    document.documentElement.style.setProperty('--vh', `${window.innerHeight * 0.01}px`);
})();

/* Additional mobile optimizations */
document.addEventListener('DOMContentLoaded', function() {
    // Ensure all external links open in new tab
    document.querySelectorAll('a[href^="http"]').forEach(function(link) {
        if (!link.hasAttribute('target')) {
            link.setAttribute('target', '_blank');
        }
        if (!link.hasAttribute('rel')) {
            link.setAttribute('rel', 'noopener noreferrer');
        }
    });
    
    // Fix for iOS hover state sticking
    document.addEventListener('touchend', function() {});
    
    // Improve performance in scrolling
    let ticking = false;
    window.addEventListener('scroll', function() {
        if (!ticking) {
            window.requestAnimationFrame(function() {
                // Handle scroll-based effects here if needed
                ticking = false;
            });
            ticking = true;
        }
    });
});