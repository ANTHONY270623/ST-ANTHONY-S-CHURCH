// Add scroll animations to make sections fade in as users scroll
document.addEventListener('DOMContentLoaded', function() {
    // Add scroll-animation class to all sections
    const sections = document.querySelectorAll('.section');
    sections.forEach(section => {
        section.classList.add('scroll-animation');
    });
    
    // Also add to priest cards, council cards, and form cards
    const cards = document.querySelectorAll('.priest-card, .council-card, .form-card, .gallery-item');
    cards.forEach(card => {
        card.classList.add('scroll-animation');
    });
    
    // Function to check if element is in viewport
    function isInViewport(element) {
        const rect = element.getBoundingClientRect();
        return (
            rect.top <= (window.innerHeight || document.documentElement.clientHeight) * 0.85
        );
    }
    
    // Function to handle scroll animations
    function handleScrollAnimations() {
        const scrollAnimations = document.querySelectorAll('.scroll-animation');
        scrollAnimations.forEach(animation => {
            if (isInViewport(animation)) {
                animation.classList.add('active');
            }
        });
    }
    
    // Run once on load
    handleScrollAnimations();
    
    // Add scroll event listener
    window.addEventListener('scroll', handleScrollAnimations);
    
    // Enhance hero section with parallax effect
    const heroSection = document.querySelector('.hero');
    if (heroSection) {
        window.addEventListener('scroll', function() {
            const scrollPosition = window.scrollY;
            if (scrollPosition < window.innerHeight) {
                const slides = document.querySelectorAll('.slide');
                slides.forEach(slide => {
                    if (slide.style.display !== 'none') {
                        slide.style.transform = `translateY(${scrollPosition * 0.3}px)`;
                    }
                });
            }
        });
    }
    
    // Add hover effect to navigation links
    const navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
        link.addEventListener('mouseenter', function() {
            this.style.transition = 'all 0.3s ease';
            this.style.color = getComputedStyle(document.documentElement).getPropertyValue('--primary-color');
        });
        
        link.addEventListener('mouseleave', function() {
            if (!this.classList.contains('active')) {
                this.style.transition = 'all 0.3s ease';
                this.style.color = '';
            }
        });
    });
    
    // Add animated counter for special sections (like years of service)
    function animateCounter(element) {
        const target = parseInt(element.getAttribute('data-count'));
        const duration = 2000; // 2 seconds
        const increment = Math.ceil(target / (duration / 16)); // 60fps
        let current = 0;
        
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                element.textContent = target;
                clearInterval(timer);
            } else {
                element.textContent = current;
            }
        }, 16);
    }
    
    // Get all counter elements
    const counters = document.querySelectorAll('[data-count]');
    counters.forEach(counter => {
        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounter(counter);
                    observer.unobserve(entry.target);
                }
            });
        });
        
        observer.observe(counter);
    });
});
