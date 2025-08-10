// Show login modal
function showLogin() {
    closeModal('register-modal');
    document.getElementById('login-modal').style.display = 'flex';
}

// Show register modal
function showRegister() {
    closeModal('login-modal');
    document.getElementById('register-modal').style.display = 'flex';
}

// Close modal by ID
function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'none';
    }
}

// Show forgot password (placeholder)
function showForgotPassword() {
    alert("Forgot Password flow not implemented yet.");
}

// Toggle role selection in register modal
document.addEventListener('DOMContentLoaded', function () {
    const roleButtons = document.querySelectorAll('.role-btn');
    const agentFields = document.querySelector('.agent-fields');

    roleButtons.forEach(button => {
        button.addEventListener('click', () => {
            roleButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');

            const selectedRole = button.dataset.role;
            if (selectedRole === 'agent') {
                agentFields.style.display = 'block';
            } else {
                agentFields.style.display = 'none';
            }
        });
    });

    // Optional: Close modal when clicking outside modal-content
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        modal.addEventListener('click', function (e) {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });
    });

    // Handle login form submission
    document.getElementById('login-form')?.addEventListener('submit', function (e) {
        e.preventDefault();
        const email = document.getElementById('login-email').value;
        const password = document.getElementById('login-password').value;

        // Replace this with your API call
        console.log('Logging in with:', { email, password });
        alert("Login successful (demo)");
        closeModal('login-modal');
    });

    // Handle register form submission
    document.getElementById('register-form')?.addEventListener('submit', function (e) {
        e.preventDefault();
        const fullName = document.getElementById('reg-name').value;
        const email = document.getElementById('reg-email').value;
        const phone = document.getElementById('reg-phone').value;
        const password = document.getElementById('reg-password').value;
        const confirm = document.getElementById('reg-confirm').value;
        const role = document.querySelector('.role-btn.active').dataset.role;

        if (password !== confirm) {
            alert("Passwords do not match!");
            return;
        }

        const userData = {
            fullName,
            email,
            phone,
            password,
            role
        };

        if (role === 'agent') {
            userData.serviceType = document.getElementById('service-type').value;
            userData.serviceArea = document.getElementById('service-area').value;
        }

        // Replace this with your API call
        console.log('Registering:', userData);
        alert("Registration successful (demo)");
        closeModal('register-modal');
    });
});
