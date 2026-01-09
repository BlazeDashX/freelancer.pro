// assets/js/login.js

document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault(); // Stop page reload

    // 1. Clear previous errors
    clearErrors();
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;
    let isValid = true;

    // 2. Client-Side Validation (Strict)
    if (email === "") {
        showError('email-error', 'Email is required.');
        isValid = false;
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        showError('email-error', 'Please enter a valid email address.');
        isValid = false;
    }

    if (password === "") {
        showError('password-error', 'Password is required.');
        isValid = false;
    } else if (password.length < 6) {
        showError('password-error', 'Password must be at least 6 characters.');
        isValid = false;
    }

    if (!isValid) return; // Stop if validation fails

    // 3. Prepare Data
    const formData = new FormData(this);
    const submitBtn = document.getElementById('loginBtn');
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Logging in...';
    submitBtn.disabled = true;

    // 4. AJAX Request to Controller
    fetch('../controller/logincheck.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Success! Redirect
            Swal.fire({
                icon: 'success',
                title: 'Welcome Back!',
                text: 'Redirecting you now...',
                timer: 1500,
                showConfirmButton: false
            }).then(() => {
                window.location.href = data.redirect;
            });
        } else {
            // Backend Error
            document.getElementById('error-banner').style.display = 'flex';
            document.getElementById('error-message').textContent = data.message;
            submitBtn.innerHTML = 'LOG IN';
            submitBtn.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('error-banner').style.display = 'flex';
        document.getElementById('error-message').textContent = 'System error. Please try again.';
        submitBtn.innerHTML = 'LOG IN';
        submitBtn.disabled = false;
    });
});