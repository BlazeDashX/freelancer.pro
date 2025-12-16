document.addEventListener('DOMContentLoaded', function() {
    // 1. Target the form
    const form = document.querySelector('.modern-form'); 
    
    // DEBUG: Check if form exists
    if (!form) {
        console.error("Validation Error: Form with class '.modern-form' not found. Please check your PHP file.");
        return;
    }
    console.log("Validation script successfully attached to form.");

    form.addEventListener('submit', function(e) {
        let isValid = true;
        
        // Clear previous errors
        document.querySelectorAll('.error-msg').forEach(el => el.textContent = '');
        document.querySelectorAll('.input-wrapper').forEach(el => el.style.borderColor = '');

        // Helper function to safely show errors
        function showError(inputElement, message) {
            if (!inputElement) return; // Guard clause

            // Find the container (parent) of the input
            const formGroup = inputElement.closest('.form-group');
            if (!formGroup) {
                console.warn("Validation Warning: .form-group not found for", inputElement.id);
                return; 
            }

            const errorSpan = formGroup.querySelector('.error-msg');
            
            // Add red border to input wrapper
            const wrapper = formGroup.querySelector('.input-wrapper');
            if (wrapper) {
                wrapper.style.borderColor = '#ff5f5f'; // Changed to borderColor for specificity
            }

            // Show text message
            if (errorSpan) {
                errorSpan.textContent = message;
            }
        }

        // --- VALIDATION LOGIC ---

        // 1. Full Name Validation
        const fullName = document.getElementById('full_name');
        if (fullName && fullName.value.trim().length < 3) {
            showError(fullName, 'Name must be at least 3 characters');
            isValid = false;
        }

        // 2. Username Validation
        const username = document.getElementById('username');
        if (username && username.value.trim().length < 4) {
            showError(username, 'Username must be at least 4 characters');
            isValid = false;
        }

        // 3. Email Validation
        const email = document.getElementById('email');
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (email && !emailPattern.test(email.value.trim())) {
            showError(email, 'Please enter a valid email address');
            isValid = false;
        }

        // 4. Password Validation
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('confirm_password');
        
        if (password && password.value.length < 6) {
            showError(password, 'Password must be at least 6 characters');
            isValid = false;
        }

        if (password && confirmPassword && password.value !== confirmPassword.value) {
            showError(confirmPassword, 'Passwords do not match');
            isValid = false;
        }

        // 5. Terms Validation
        const terms = document.getElementById('terms');
        if (terms && !terms.checked) {
            // Find the container for terms error
            const termsContainer = terms.closest('.terms-box');
            if (termsContainer) {
                const errorSpan = termsContainer.querySelector('.error-msg');
                if (errorSpan) errorSpan.textContent = "You must agree to the terms";
            }
            isValid = false;
        }

        // If invalid, stop the form from sending to PHP
        if (!isValid) {
            e.preventDefault();
            console.log("Validation failed. Form submission prevented.");
        } else {
            console.log("Validation passed. Submitting form...");
        }
    });
});