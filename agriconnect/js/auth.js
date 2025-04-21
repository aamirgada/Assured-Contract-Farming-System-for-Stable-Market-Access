document.addEventListener('DOMContentLoaded', function() {
    // Password visibility toggle
    const togglePasswordButtons = document.querySelectorAll('.toggle-password');
    togglePasswordButtons.forEach(button => {
        button.addEventListener('click', function() {
            const input = this.previousElementSibling;
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    });

    // Form validation
    const forms = document.querySelectorAll('.auth-form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            // Determine which form is being submitted
            const isSignupForm = form.querySelector('input[name="confirm_password"]') !== null;
            
            if (isSignupForm) {
                // For signup form
                if (!validateSignupForm()) {
                    e.preventDefault();
                    return false;
                }
            } else {
                // For login form
                if (!validateLoginForm()) {
                    e.preventDefault();
                    return false;
                }
            }
            
            // If validation passes, form will submit naturally
        });
    });

    // Form field animations
    const inputs = document.querySelectorAll('.input-group input, .input-group select');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });

        input.addEventListener('blur', function() {
            if (!this.value) {
                this.parentElement.classList.remove('focused');
            }
        });
    });

    // Login form validation
    function validateLoginForm() {
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        let isValid = true;

        if (!validateEmail(email)) {
            showError('email', 'Please enter a valid email address');
            isValid = false;
        } else {
            removeError('email');
        }

        // Removed password length validation
        if (!password) {
            showError('password', 'Please enter your password');
            isValid = false;
        } else {
            removeError('password');
        }

        return isValid;
    }

    // Signup form validation
    function validateSignupForm() {
        const fullname = document.getElementById('fullname').value;
        const email = document.getElementById('email').value;
        const phone = document.getElementById('phone').value;
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm_password').value;
        const terms = document.querySelector('input[name="terms"]').checked;
        let isValid = true;

        // Validate full name
        if (fullname.trim().length < 2) {
            showError('fullname', 'Please enter your full name');
            isValid = false;
        } else {
            removeError('fullname');
        }

        // Validate email
        if (!validateEmail(email)) {
            showError('email', 'Please enter a valid email address');
            isValid = false;
        } else {
            removeError('email');
        }

        // Validate phone
        if (!validatePhone(phone)) {
            showError('phone', 'Please enter a valid phone number');
            isValid = false;
        } else {
            removeError('phone');
        }

        // Validate password - removed strength requirements
        if (!password) {
            showError('password', 'Please enter a password');
            isValid = false;
        } else {
            removeError('password');
        }

        // Validate confirm password
        if (password !== confirmPassword) {
            showError('confirm_password', 'Passwords do not match');
            isValid = false;
        } else {
            removeError('confirm_password');
        }

        // Validate terms
        if (!terms) {
            showError('terms', 'You must agree to the Terms of Service and Privacy Policy');
            isValid = false;
        } else {
            removeError('terms');
        }

        return isValid;
    }

    // Helper functions remain the same
    // ...
});

// Remove these password strength functions
// function checkPasswordStrength(password) { ... }
// function updatePasswordStrengthIndicator(input, strength) { ... }
// function createStrengthIndicator(input) { ... }

// Keep the error handling functions
function createErrorDiv(input) {
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    input.parentElement.appendChild(errorDiv);
    return errorDiv;
}

function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

function validatePhone(phone) {
    const re = /^\+?[\d\s-]{10,}$/;
    return re.test(phone);
}

function showError(input, message) {
    const errorDiv = input.parentElement.querySelector('.error-message') || 
                    createErrorDiv(input);
    errorDiv.textContent = message;
    input.classList.add('error');
}

function removeError(inputId) {
    const input = document.getElementById(inputId);
    const errorDiv = input.parentElement.querySelector('.error-message');
    
    if (errorDiv) {
        errorDiv.remove();
    }
    
    input.classList.remove('error');
}

// Social auth buttons animation
const socialButtons = document.querySelectorAll('.social-button');
socialButtons.forEach(button => {
    button.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-2px)';
    });
    
    button.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0)';
    });
});

// User type selection animation
const userTypeBoxes = document.querySelectorAll('.user-type-box');
userTypeBoxes.forEach(box => {
    box.addEventListener('click', function() {
        userTypeBoxes.forEach(b => b.classList.remove('selected'));
        this.classList.add('selected');
    });
});