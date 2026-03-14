// Contact form handler with improved mobile support
function initContactForm() {
    const contactForm = document.getElementById('contactForm');
    if (!contactForm) return;
    
    // Add submission handler
    contactForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Show loading state
        const submitBtn = contactForm.querySelector('button[type="submit"]');
        const originalBtnText = submitBtn.textContent;
        submitBtn.textContent = 'Sending...';
        submitBtn.disabled = true;
        
        // Get form elements
        const nameInput = document.getElementById('name');
        const emailInput = document.getElementById('email');
        const phoneInput = document.getElementById('phone');
        const subjectInput = document.getElementById('subject');
        const messageInput = document.getElementById('message');
        const successMsg = document.getElementById('contact-success-message');
        const errorMsg = document.getElementById('contact-error-message');
        
        // Hide any existing messages
        if (successMsg) successMsg.style.display = 'none';
        if (errorMsg) errorMsg.style.display = 'none';
        
        // Basic client-side validation
        let isValid = true;
        let errorMessage = '';
        
        if (!nameInput.value.trim()) {
            isValid = false;
            errorMessage = 'Please enter your name';
            nameInput.focus();
        } else if (!emailInput.value.trim()) {
            isValid = false;
            errorMessage = 'Please enter your email';
            emailInput.focus();
        } else if (!validateEmail(emailInput.value.trim())) {
            isValid = false;
            errorMessage = 'Please enter a valid email address';
            emailInput.focus();
        } else if (!subjectInput.value.trim()) {
            isValid = false;
            errorMessage = 'Please enter a subject';
            subjectInput.focus();
        } else if (!messageInput.value.trim()) {
            isValid = false;
            errorMessage = 'Please enter your message';
            messageInput.focus();
        }
        
        if (!isValid) {
            // Show validation error
            if (errorMsg) {
                errorMsg.textContent = errorMessage;
                errorMsg.style.display = 'block';
            }
            submitBtn.textContent = originalBtnText;
            submitBtn.disabled = false;
            return;
        }
        
        // Create FormData object
        const formData = new FormData(contactForm);
        
        // Debug: Log form data
        console.log('Submitting form to:', contactForm.action);
        for (let [key, value] of formData.entries()) {
            console.log(key, value);
        }
        
        // Send AJAX request
        fetch(contactForm.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response ok:', response.ok);
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            
            // Reset button state
            submitBtn.textContent = originalBtnText;
            submitBtn.disabled = false;
            
            if (data.success) {
                // Show success message
                if (successMsg) {
                    successMsg.style.display = 'block';
                    
                    // Scroll to success message for mobile
                    successMsg.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
                
                // Reset form
                contactForm.reset();
                
                // Vibrate on success for mobile devices (if supported)
                if (navigator.vibrate) {
                    navigator.vibrate([100, 50, 100]);
                }
            } else {
                // Show error message
                if (errorMsg) {
                    errorMsg.textContent = data.message || 'There was an error sending your message. Please try again.';
                    errorMsg.style.display = 'block';
                    
                    // Scroll to error message for mobile
                    errorMsg.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        })
        .catch(error => {
            // Reset button state
            submitBtn.textContent = originalBtnText;
            submitBtn.disabled = false;
            
            // Handle network errors
            console.error('Error:', error);
            if (errorMsg) {
                errorMsg.textContent = 'Network error. Please try again later.';
                errorMsg.style.display = 'block';
                
                // Scroll to error message for mobile
                errorMsg.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });
    });
    
    // Add input validation handlers for better UX
    const inputs = contactForm.querySelectorAll('input, textarea');
    inputs.forEach(input => {
        // Clear error message when user starts typing
        input.addEventListener('input', function() {
            const errorMsg = document.getElementById('contact-error-message');
            if (errorMsg && errorMsg.style.display === 'block') {
                errorMsg.style.display = 'none';
            }
        });
    });
    
    // Check URL parameters for form submission status (for non-AJAX fallback)
    const urlParams = new URLSearchParams(window.location.search);
    const contactStatus = urlParams.get('contact');
    const contactMessage = urlParams.get('message');
    
    if (contactStatus === 'success') {
        const successMsg = document.getElementById('contact-success-message');
        if (successMsg) {
            successMsg.style.display = 'block';
            successMsg.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    } else if (contactStatus === 'error') {
        const errorMsg = document.getElementById('contact-error-message');
        if (errorMsg) {
            errorMsg.textContent = contactMessage || 'There was an error sending your message. Please try again.';
            errorMsg.style.display = 'block';
            errorMsg.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }
}

// Helper function to validate email
function validateEmail(email) {
    const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}