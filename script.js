// Dark Mode Toggle
const darkModeToggle = document.getElementById('darkModeToggle');
darkModeToggle.addEventListener('click', () => {
    document.body.classList.toggle('dark-mode');
    if (document.body.classList.contains('dark-mode')) {
        darkModeToggle.innerHTML = '<i class="fas fa-sun"></i>';
        localStorage.setItem('darkMode', 'enabled');
    } else {
        darkModeToggle.innerHTML = '<i class="fas fa-moon"></i>';
        localStorage.setItem('darkMode', 'disabled');
    }
});

// Check for saved dark mode preference
if (localStorage.getItem('darkMode') === 'enabled') {
    document.body.classList.add('dark-mode');
    darkModeToggle.innerHTML = '<i class="fas fa-sun"></i>';
}

// Create floating stars
function createStars() {
    const starsContainer = document.getElementById('stars');
    const starsCount = 100;
    
    for (let i = 0; i < starsCount; i++) {
        const star = document.createElement('div');
        star.classList.add('star');
        
        // Random size between 1px and 3px
        const size = Math.random() * 2 + 1;
        star.style.width = `${size}px`;
        star.style.height = `${size}px`;
        
        // Random position
        star.style.left = `${Math.random() * 100}%`;
        star.style.top = `${Math.random() * 100}%`;
        
        // Random animation delay
        star.style.animationDelay = `${Math.random() * 2}s`;
        
        starsContainer.appendChild(star);
    }
}

// Hamburger menu functionality
const hamburger = document.getElementById('hamburger');
const navMenu = document.getElementById('navMenu');

hamburger.addEventListener('click', () => {
    hamburger.classList.toggle('active');
    navMenu.classList.toggle('active');
});

// Dropdown menu functionality - enhanced for both mobile and desktop
document.addEventListener('DOMContentLoaded', () => {
    const dropdowns = document.querySelectorAll('.dropdown');
    const dropdownStates = new Map(); // Track open state of each dropdown
    
    // Initialize each dropdown
    dropdowns.forEach((dropdown, index) => {
        const dropdownLink = dropdown.querySelector('.nav-link');
        const dropdownContent = dropdown.querySelector('.dropdown-content');
        dropdownStates.set(dropdown, false); // Initially closed
        
        // For mobile: toggle dropdown on click
        dropdownLink.addEventListener('click', (e) => {
            if (window.innerWidth <= 767) {
                e.preventDefault();
                
                // Close all other dropdowns
                dropdowns.forEach(otherDropdown => {
                    if (otherDropdown !== dropdown) {
                        otherDropdown.classList.remove('active');
                    }
                });
                
                // Toggle this dropdown
                dropdown.classList.toggle('active');
            } else {
                // For desktop - prevent navigation when clicking the dropdown link
                // This keeps the dropdown menu open
                e.preventDefault();
                
                const isOpen = !dropdownStates.get(dropdown);
                dropdownStates.set(dropdown, isOpen);
                
                // Close all other dropdowns
                dropdowns.forEach(otherDropdown => {
                    if (otherDropdown !== dropdown && dropdownStates.get(otherDropdown)) {
                        const otherContent = otherDropdown.querySelector('.dropdown-content');
                        otherContent.style.display = 'none';
                        dropdownStates.set(otherDropdown, false);
                    }
                });
                
                // Toggle this dropdown
                if (isOpen) {
                    dropdownContent.style.display = 'block';
                } else {
                    dropdownContent.style.display = 'none';
                }
            }
        });
        
        // Add extra hover stability for desktop
        if (window.innerWidth > 767) {
            dropdown.addEventListener('mouseenter', () => {
                dropdownStates.set(dropdown, true);
                dropdownContent.style.display = 'block';
            });
            
            // Add a slight delay before closing the dropdown
            // to prevent accidental closures when moving mouse
            dropdown.addEventListener('mouseleave', () => {
                setTimeout(() => {
                    if (!dropdown.matches(':hover')) {
                        dropdownStates.set(dropdown, false);
                        dropdownContent.style.display = 'none';
                    }
                }, 100);
            });
        }
        
        // Handle dropdown menu items clicks
        dropdownContent.querySelectorAll('a').forEach(dropdownItem => {
            dropdownItem.addEventListener('click', () => {
                // Close mobile menu and dropdowns when a dropdown item is clicked
                hamburger.classList.remove('active');
                navMenu.classList.remove('active');
                dropdown.classList.remove('active');
                dropdownStates.set(dropdown, false);
            });
        });
    });
    
    // Handle window resize
    window.addEventListener('resize', () => {
        if (window.innerWidth > 767) {
            // On desktop, remove active class and reset to hover behavior
            dropdowns.forEach(dropdown => {
                dropdown.classList.remove('active');
                const dropdownContent = dropdown.querySelector('.dropdown-content');
                dropdownContent.style.removeProperty('display');
            });
        } else {
            // On mobile, ensure dropdowns are hidden unless active
            dropdowns.forEach(dropdown => {
                if (!dropdown.classList.contains('active')) {
                    const dropdownContent = dropdown.querySelector('.dropdown-content');
                    dropdownContent.style.display = 'none';
                }
            });
        }
    });
});

// Close menu when clicking on a nav link
document.querySelectorAll('.nav-link').forEach(link => {
    link.addEventListener('click', function(e) {
        // Don't close menu for dropdown toggle on mobile
        if (this.parentNode.classList.contains('dropdown') && window.innerWidth <= 767) {
            return;
        }
        
        hamburger.classList.remove('active');
        navMenu.classList.remove('active');
        
        // Close any open dropdowns (for mobile)
        document.querySelectorAll('.dropdown').forEach(dropdown => {
            dropdown.classList.remove('active');
        });
        
        // Update active link
        document.querySelectorAll('.nav-link').forEach(navLink => {
            navLink.classList.remove('active');
        });
        link.classList.add('active');
    });
});

// Smooth scrolling for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        e.preventDefault();
        
        const targetId = this.getAttribute('href');
        if (targetId === '#') return;
        
        const targetElement = document.querySelector(targetId);
        if (targetElement) {
            window.scrollTo({
                top: targetElement.offsetTop - 70,
                behavior: 'smooth'
            });
        }
    });
});

// Highlight active section in navigation
window.addEventListener('scroll', () => {
    const sections = document.querySelectorAll('.section, .hero');
    let currentSection = '';
    
    sections.forEach(section => {
        const sectionTop = section.offsetTop;
        const sectionHeight = section.clientHeight;
        
        if (window.scrollY >= sectionTop - 100 && window.scrollY < sectionTop + sectionHeight - 100) {
            currentSection = section.getAttribute('id');
        }
    });
    
    document.querySelectorAll('.nav-link').forEach(link => {
        link.classList.remove('active');
        if (link.getAttribute('href') === `#${currentSection}`) {
            link.classList.add('active');
        }
    });
});

// Note: Modal functionality has been replaced with direct links to admin pages

// Slideshow functionality
let slideIndex = 0;
let slideTimeout;

function showSlides() {
    let slides = document.getElementsByClassName("slide");
    let dots = document.getElementsByClassName("dot");
    for (let i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }
    slideIndex++;
    if (slideIndex > slides.length) {slideIndex = 1}
    for (let i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(" active", "");
    }
    if(slides[slideIndex-1]) slides[slideIndex-1].style.display = "block";
    if(dots[slideIndex-1]) dots[slideIndex-1].className += " active";
    slideTimeout = setTimeout(showSlides, 30000); // Change image every 30 seconds
}

function currentSlide(n) {
    clearTimeout(slideTimeout);
    slideIndex = n-1;
    showSlides();
}

// Gallery Lightbox functionality
function initGalleryLightbox() {
    // Create lightbox elements
    const lightbox = document.createElement('div');
    lightbox.className = 'lightbox';
    lightbox.innerHTML = `
        <div class="lightbox-container">
            <div class="lightbox-controls">
                <button class="lightbox-close">&times;</button>
                <button class="lightbox-prev">&#10094;</button>
                <button class="lightbox-next">&#10095;</button>
            </div>
            <div class="lightbox-content">
                <img class="lightbox-image" src="" alt="Gallery image">
            </div>
            <div class="lightbox-caption"></div>
        </div>
    `;
    document.body.appendChild(lightbox);
    
    // Variables to track current image and gallery
    let currentGallery = [];
    let currentIndex = 0;
    
    // Get lightbox elements
    const lightboxElement = document.querySelector('.lightbox');
    const lightboxImage = document.querySelector('.lightbox-image');
    const lightboxCaption = document.querySelector('.lightbox-caption');
    const closeBtn = document.querySelector('.lightbox-close');
    const prevBtn = document.querySelector('.lightbox-prev');
    const nextBtn = document.querySelector('.lightbox-next');
    
    // Open lightbox
    function openLightbox(gallery, index) {
        currentGallery = gallery;
        currentIndex = index;
        updateLightboxImage();
        lightboxElement.classList.add('active');
        document.body.style.overflow = 'hidden'; // Prevent scrolling
    }
    
    // Close lightbox
    function closeLightbox() {
        lightboxElement.classList.remove('active');
        document.body.style.overflow = ''; // Enable scrolling
    }
    
    // Update lightbox image
    function updateLightboxImage() {
        const image = currentGallery[currentIndex];
        lightboxImage.src = image.src;
        lightboxCaption.textContent = image.alt;
        
        // Handle navigation buttons
        prevBtn.style.display = currentIndex > 0 ? 'block' : 'none';
        nextBtn.style.display = currentIndex < currentGallery.length - 1 ? 'block' : 'none';
    }
    
    // Next image
    function nextImage() {
        if (currentIndex < currentGallery.length - 1) {
            currentIndex++;
            updateLightboxImage();
        }
    }
    
    // Previous image
    function prevImage() {
        if (currentIndex > 0) {
            currentIndex--;
            updateLightboxImage();
        }
    }
    
    // Event listeners for lightbox
    closeBtn.addEventListener('click', closeLightbox);
    prevBtn.addEventListener('click', prevImage);
    nextBtn.addEventListener('click', nextImage);
    
    // Close on background click
    lightboxElement.addEventListener('click', function(e) {
        if (e.target === lightboxElement) {
            closeLightbox();
        }
    });
    
    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (!lightboxElement.classList.contains('active')) return;
        
        if (e.key === 'Escape') {
            closeLightbox();
        } else if (e.key === 'ArrowRight') {
            nextImage();
        } else if (e.key === 'ArrowLeft') {
            prevImage();
        }
    });
    
    // Set up gallery sections
    document.querySelectorAll('.gallery-section').forEach(section => {
        const galleryItems = Array.from(section.querySelectorAll('.gallery-item img'));
        const sectionTitle = section.querySelector('h3').textContent;
        
        // Make images clickable
        galleryItems.forEach((img, index) => {
            img.style.cursor = 'pointer';
            img.addEventListener('click', () => {
                openLightbox(galleryItems, index);
            });
        });
        
        // Add "Show All" button
        const showAllBtn = document.createElement('a');
        showAllBtn.className = 'show-all-btn';
        showAllBtn.href = '#';
        showAllBtn.innerHTML = 'Show All <i class="fas fa-expand"></i>';
        section.querySelector('h3').appendChild(showAllBtn);
        
        // Create full gallery page
        showAllBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Create full gallery overlay
            const fullGallery = document.createElement('div');
            fullGallery.className = 'full-gallery-overlay';
            fullGallery.innerHTML = `
                <div class="full-gallery-container">
                    <div class="full-gallery-header">
                        <h2>${sectionTitle}</h2>
                        <button class="full-gallery-close">&times;</button>
                    </div>
                    <div class="full-gallery-grid"></div>
                </div>
            `;
            document.body.appendChild(fullGallery);
            
            const galleryGrid = fullGallery.querySelector('.full-gallery-grid');
            const closeBtn = fullGallery.querySelector('.full-gallery-close');
            
            // Add all images to the grid
            galleryItems.forEach((img, index) => {
                const galleryItem = document.createElement('div');
                galleryItem.className = 'full-gallery-item';
                
                const galleryImage = document.createElement('img');
                galleryImage.src = img.src;
                galleryImage.alt = img.alt;
                galleryImage.style.cursor = 'pointer';
                
                galleryItem.appendChild(galleryImage);
                galleryGrid.appendChild(galleryItem);
                
                // Open lightbox when clicking on image
                galleryImage.addEventListener('click', () => {
                    openLightbox(galleryItems, index);
                });
            });
            
            // Close full gallery
            closeBtn.addEventListener('click', () => {
                document.body.removeChild(fullGallery);
            });
            
            // Close on background click
            fullGallery.addEventListener('click', function(e) {
                if (e.target === fullGallery) {
                    document.body.removeChild(fullGallery);
                }
            });
        });
    });
}

// Initialize - create stars and show slideshow
document.addEventListener('DOMContentLoaded', function() {
    createStars();
    showSlides();
    
    // Set home link as active initially
    document.querySelector('.nav-link[href="#home"]').classList.add('active');
    
    // Initialize gallery lightbox
    initGalleryLightbox();
});

// Security features are now configured to allow right-click functionality
document.addEventListener('keydown', function(e) {
    if (e.key === 'PrintScreen') {
        // PrintScreen is now allowed
        // No action needed
    }
});
