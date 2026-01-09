// Client-side validation for the registration form
function validateForm() {
    const username = document.getElementById('username').value.trim();
    const password = document.getElementById('password').value.trim();
    const confirmPassword = document.getElementById('confirm_password').value.trim();

    // Check if username is empty
    if (username === '') {
        alert('Username cannot be empty.');
        return false;
    }

    // Check if password is at least 10 characters long
    if (password.length < 10) {
        alert('Password must be at least 10 characters long.');
        return false;
    }

    // Check if passwords match
    if (password !== confirmPassword) {
        alert('Passwords do not match.');
        return false;
    }

    // If all validations pass, allow form submission
    return true;
}

// Client-side validation for the login form
function validateLoginForm() {
    const username = document.getElementById('username').value.trim();
    const password = document.getElementById('password').value.trim();

    // Check if username is empty
    if (username === '') {
        alert('Username cannot be empty.');
        return false;
    }

    // Check if password is empty
    if (password === '') {
        alert('Password cannot be empty.');
        return false;
    }

    // If all validations pass, allow form submission
    return true;
}