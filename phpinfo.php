<?php
// filepath: c:\Users\Javed Sharma\Desktop\CODEING\TEST\Church_project\contact.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#8b0000">
    <title>Contact Us - St. Anthony's Church Green Park</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="threads-fix.css">
    <link rel="stylesheet" href="responsive.css">
    <link rel="stylesheet" href="gallery.css">
    <link rel="stylesheet" href="mobile-enhancements.css">
    <link rel="stylesheet" href="extracted-styles.css">
    <style>
        /* Additional Contact Form Styles */
        .contact-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
        }
        
        .contact-info {
            flex: 1;
            min-width: 300px;
            background-color: var(--secondary-bg-color);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .contact-form-container {
            flex: 2;
            min-width: 300px;
            background-color: var(--primary-bg-color);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .contact-info-item {
            display: flex;
            margin-bottom: 20px;
            align-items: flex-start;
        }
        
        .contact-info-item i {
            margin-right: 15px;
            font-size: 20px;
            color: var(--primary-color);
            width: 30px;
            text-align: center;
            margin-top: 5px;
        }
        
        .contact-info-item .content h4 {
            margin: 0 0 5px 0;
            color: var(--text-color);
        }
        
        .contact-info-item .content p {
            margin: 0;
            color: var(--text-color-secondary);
        }
        
        .contact-form {
            width: 100%;
        }
        
        .form-header {
            margin-bottom: 25px;
        }
        
        .form-header h3 {
            color: var(--heading-color);
            font-size: 24px;
            margin-bottom: 10px;
        }
        
        .form-header p {
            color: var(--text-color-secondary);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: var(--text-color);
            font-weight: 500;
        }
        
        .form-group input, 
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background-color: var(--input-bg-color);
            color: var(--text-color);
            font-size: 16px;
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        
        .form-group input:focus, 
        .form-group textarea:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(139, 0, 0, 0.1);
            outline: none;
        }
        
        .form-group textarea {
            min-height: 150px;
            resize: vertical;
        }
        
        .btn-submit {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 25px;
            font-size: 16px;
            font-weight: 500;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
            display: inline-block;
        }
        
        .btn-submit:hover {
            background-color: #700000;
            transform: translateY(-2px);
        }
        
        .btn-submit:active {
            transform: translateY(0);
        }
        
        .form-status {
            margin-top: 20px;
            padding: 15px;
            border-radius: 8px;
            display: none;
        }
        
        .success-message {
            background-color: #d4edda;
            color: #155724;
            border-left: 4px solid #155724;
        }
        
        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            border-left: 4px solid #721c24;
        }
        
        .map-container {
            width: 100%;
            margin-top: 40px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .map-container iframe {
            width: 100%;
            height: 400px;
            border: 0;
        }
        
        /* CSS Variables for Light/Dark Mode */
        :root {
            --primary-color: #8b0000;
            --primary-bg-color: #ffffff;
            --secondary-bg-color: #f8f9fa;
            --text-color: #333333;
            --text-color-secondary: #666666;
            --heading-color: #8b0000;
            --border-color: #dddddd;
            --input-bg-color: #ffffff;
        }
        
        .dark-mode {
            --primary-bg-color: #1a1a1a;
            --secondary-bg-color: #2d2d2d;
            --text-color: #f0f0f0;
            --text-color-secondary: #cccccc;
            --heading-color: #ff9999;
            --border-color: #444444;
            --input-bg-color: #333333;
        }
        
        @media (max-width: 768px) {
            .contact-container {
                flex-direction: column;
            }
            
            .contact-info, .contact-form-container {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <!-- Page Preloader -->
    <div class="preloader" id="preloader">
        <div class="loader"></div>
    </div>
    
    <!-- Dark Mode Toggle -->
    <button class="dark-mode-toggle" id="darkModeToggle" title="Toggle Dark Mode">
        <i class="fas fa-moon"></i>
    </button>

    <!-- Social Media Sidebar -->
    <div class="social-sidebar">
        <a href="https://www.facebook.com/share/19cjbzWyGW/" class="sidebar-link facebook" title="Facebook" target="_blank" rel="noopener"><i class="fab fa-facebook-f"></i></a>
        <a href="https://youtube.com/@stanthonyschurchgreenpark?feature=shared" class="sidebar-link youtube" title="YouTube" target="_blank" rel="noopener"><i class="fab fa-youtube"></i></a>
        <a href="https://www.instagram.com/stanthonyschurch_greenpark?igsh=cjNhcGR3ZGthZjdv" class="sidebar-link instagram" title="Instagram" target="_blank" rel="noopener"><i class="fab fa-instagram"></i></a>
        <a href="#" class="sidebar-link twitter" title="X" target="_blank"><i class="fab fa-x-twitter"></i></a>
        <a href="https://www.threads.com/@stanthonyschurch_greenpark" class="sidebar-link threads" title="Threads" target="_blank" rel="noopener"><i class="fab fa-threads"></i></a>
    </div>

    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="index.html" class="logo">
                <img src="images/logo.png" alt="St. Anthony's Church Logo">
                <span>St. Anthony's Church</span>
            </a>
            
            <div class="menu-toggle" id="hamburger">
                <div class="bar"></div>
                <div class="bar"></div>
                <div class="bar"></div>
            </div>
            
            <ul class="nav-menu" id="navMenu">
                <li><a href="index.html#home" class="nav-link">Home</a></li>
                <li><a href="index.html#about" class="nav-link">About</a></li>
                <li><a href="index.html#parish" class="nav-link">Parish</a></li>
                <li><a href="index.html#prayer" class="nav-link">Prayer Times</a></li>
                <li><a href="index.html#events" class="nav-link">Events</a></li>
                <li><a href="index.html#gallery" class="nav-link">Gallery</a></li>
                <li><a href="index.html#council" class="nav-link">Council</a></li>
                <li><a href="contact.php" class="nav-link">Contact</a></li>
                <li><a href="index.html#forms" class="nav-link">Forms</a></li>
            </ul>
        </div>
    </nav>

    <!-- Contact Section -->
    <section id="contact" class="section">
        <h2>CONTACT US</h2>
        
        <div class="contact-container">
            <div class="contact-info">
                <h3>Get in Touch</h3>
                <p>Feel free to reach out to us with any questions, concerns, or prayer requests.</p>
                
                <div class="contact-info-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <div class="content">
                        <h4>Location</h4>
                        <p>Chitranjan Para, New Barrackpore, Kolkata, West Bengal 700133</p>
                    </div>
                </div>
                
                <div class="contact-info-item">
                    <i class="fas fa-phone-alt"></i>
                    <div class="content">
                        <h4>Phone</h4>
                        <p>+91 7025727541</p>
                    </div>
                </div>
                
                <div class="contact-info-item">
                    <i class="fas fa-envelope"></i>
                    <div class="content">
                        <h4>Email</h4>
                        <p>greenparkchurch25@gmail.com</p>
                    </div>
                </div>
                
                <div class="contact-info-item">
                    <i class="far fa-clock"></i>
                    <div class="content">
                        <h4>Office Hours</h4>
                        <p>Monday - Friday: 9:00 AM - 5:00 PM<br>
                        Saturday: 9:00 AM - 12:00 PM<br>
                        Sunday: Closed (Except for Mass)</p>
                    </div>
                </div>
                
                <div class="contact-info-item">
                    <i class="fas fa-church"></i>
                    <div class="content">
                        <h4>Mass Times</h4>
                        <p>Sunday: 8:00 AM (English), 10:30 AM (Malayalam)<br>
                        Weekdays: 6:30 AM (Tuesday & Friday)</p>
                    </div>
                </div>
            </div>
            
            <div class="contact-form-container">
                <div class="form-header">
                    <h3>Send Us a Message</h3>
                    <p>Fill out the form below and we'll get back to you as soon as possible.</p>
                </div>
                
                <div class="contact-form">
                    <!-- Replace YOUR_FORMSPREE_ID with your actual Formspree form ID -->
                    <form id="contactForm" action="https://formspree.io/f/YOUR_FORMSPREE_ID" method="POST">
                        <div class="form-group">
                            <label for="name">Your Name</label>
                            <input type="text" id="name" name="name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone">
                        </div>
                        
                        <div class="form-group">
                            <label for="subject">Subject</label>
                            <input type="text" id="subject" name="subject" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="message">Your Message</label>
                            <textarea id="message" name="message" required></textarea>
                        </div>
                        
                        <!-- Hidden field to identify the form source -->
                        <input type="hidden" name="form-source" value="Church Website Contact Form">
                        
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-paper-plane"></i> Send Message
                        </button>
                    </form>
                    
                    <div id="form-status" class="form-status"></div>
                </div>
            </div>
        </div>
        
        <!-- Google Map -->
        <div class="map-container">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3678.4607383505917!2d88.4386366!3d22.7857995!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39f89fb8d7b7a953%3A0x1c7bb47cef2d3a9b!2sSt.%20Anthony%E2%80%99s%20Church%20Green%20Park!5e0!3m2!1sen!2sin!4v1692912347717!5m2!1sen!2sin" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-logo">
                <img src="images/logo.png" alt="St. Anthony's Church Logo">
                <h3>St. Anthony's Church Green Park</h3>
            </div>
            
            <div class="footer-links">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="index.html#home">Home</a></li>
                    <li><a href="index.html#about">About</a></li>
                    <li><a href="index.html#events">Events</a></li>
                    <li><a href="index.html#gallery">Gallery</a></li>
                    <li><a href="contact.php">Contact</a></li>
                </ul>
            </div>
            
            <div class="footer-contact">
                <h4>Contact Info</h4>
                <p><i class="fas fa-map-marker-alt"></i> Chitranjan Para, New Barrackpore, Kolkata, WB 700133</p>
                <p><i class="fas fa-phone"></i> +91 7025727541</p>
                <p><i class="fas fa-envelope"></i> greenparkchurch25@gmail.com</p>
            </div>
            
            <div class="footer-social">
                <h4>Follow Us</h4>
                <div class="social-icons">
                    <a href="https://www.facebook.com/share/19cjbzWyGW/" target="_blank" rel="noopener"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://youtube.com/@stanthonyschurchgreenpark?feature=shared" target="_blank" rel="noopener"><i class="fab fa-youtube"></i></a>
                    <a href="https://www.instagram.com/stanthonyschurch_greenpark?igsh=cjNhcGR3ZGthZjdv" target="_blank" rel="noopener"><i class="fab fa-instagram"></i></a>
                    <a href="#" target="_blank"><i class="fab fa-x-twitter"></i></a>
                    <a href="https://www.threads.com/@stanthonyschurch_greenpark" target="_blank" rel="noopener"><i class="fab fa-threads"></i></a>
                </div>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> St. Anthony's Church Green Park. All Rights Reserved.</p>
            <p>Designed with <i class="fas fa-heart"></i> by Community Members</p>
        </div>
    </footer>

    <script>
        // Contact form submission
        document.addEventListener('DOMContentLoaded', function() {
            const contactForm = document.getElementById('contactForm');
            const formStatus = document.getElementById('form-status');
            
            if (contactForm) {
                contactForm.addEventListener('submit', function(e) {
                    // Form is handled by Formspree, no need to prevent default
                    // But we can still show a loading message
                    formStatus.textContent = 'Sending your message...';
                    formStatus.className = 'form-status';
                    formStatus.style.display = 'block';
                    
                    // We'll let Formspree handle the form submission
                    // But we'll also add a success message when it returns
                    window.onFormspreeSuccess = function() {
                        formStatus.textContent = 'Your message has been sent successfully! We\'ll get back to you soon.';
                        formStatus.className = 'form-status success-message';
                        contactForm.reset();
                    };
                });
            }
            
            // Dark mode toggle
            const darkModeToggle = document.getElementById('darkModeToggle');
            const body = document.body;
            
            if (darkModeToggle) {
                darkModeToggle.addEventListener('click', function() {
                    body.classList.toggle('dark-mode');
                    
                    if (body.classList.contains('dark-mode')) {
                        localStorage.setItem('darkMode', 'enabled');
                        darkModeToggle.innerHTML = '<i class="fas fa-sun"></i>';
                    } else {
                        localStorage.setItem('darkMode', 'disabled');
                        darkModeToggle.innerHTML = '<i class="fas fa-moon"></i>';
                    }
                });
            }
            
            // Check for saved dark mode preference
            if (localStorage.getItem('darkMode') === 'enabled') {
                body.classList.add('dark-mode');
                if (darkModeToggle) {
                    darkModeToggle.innerHTML = '<i class="fas fa-sun"></i>';
                }
            }
            
            // Mobile menu toggle
            const hamburger = document.getElementById('hamburger');
            const navMenu = document.getElementById('navMenu');
            
            if (hamburger) {
                hamburger.addEventListener('click', function() {
                    navMenu.classList.toggle('active');
                    hamburger.classList.toggle('active');
                });
            }
            
            // Remove preloader after page loads
            const preloader = document.getElementById('preloader');
            if (preloader) {
                window.addEventListener('load', function() {
                    preloader.style.display = 'none';
                });
            }
        });
    </script>
</body>
</html>