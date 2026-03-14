/* Mobile viewport optimization */
(function() {
    // Function to fix viewport issues in various mobile browsers
    function fixViewport(updateMeta = false) {
        // Handle 100vh in mobile browsers (especially iOS Safari)
        const vh = window.innerHeight * 0.01;
        document.documentElement.style.setProperty('--vh', `${vh}px`);

        // Only touch viewport meta on initial load/orientation changes.
        if (updateMeta) {
            const width = window.innerWidth;
            const meta = document.querySelector('meta[name="viewport"]');

            if (!meta) return;

            if (width < 375) {
                meta.setAttribute('content', 'width=375, initial-scale=' + (width / 375));
            } else {
                meta.setAttribute('content', 'width=device-width, initial-scale=1.0');
            }
        }
    }
    
    // Run on load
    window.addEventListener('load', function() {
        fixViewport(true);
    });
    
    // Run on resize and orientation change
    let resizeRaf = null;
    window.addEventListener('resize', function() {
        if (resizeRaf) {
            cancelAnimationFrame(resizeRaf);
        }

        resizeRaf = requestAnimationFrame(function() {
            fixViewport(false);
            resizeRaf = null;
        });
    });

    window.addEventListener('orientationchange', function() {
        setTimeout(function() {
            fixViewport(true);
        }, 100);
    });
    
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