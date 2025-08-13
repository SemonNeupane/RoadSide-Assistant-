// // Show login modal
// function showLogin() {
//     closeModal('register-modal');
//     document.getElementById('login-modal').style.display = 'flex';
// }

// // Show register modal
// function showRegister() {
//     closeModal('login-modal');
//     document.getElementById('register-modal').style.display = 'flex';
// }

// // Close modal by ID
// function closeModal(modalId) {
//     const modal = document.getElementById(modalId);
//     if (modal) {
//         modal.style.display = 'none';
//     }
// }

// // Show forgot password (placeholder)
// function showForgotPassword() {
//     alert("Forgot Password flow not implemented yet.");
// }

// // Toggle role selection in register modal
// document.addEventListener('DOMContentLoaded', function () {
//     const roleButtons = document.querySelectorAll('.role-btn');
//     const agentFields = document.querySelector('.agent-fields');

//     roleButtons.forEach(button => {
//         button.addEventListener('click', () => {
//             roleButtons.forEach(btn => btn.classList.remove('active'));
//             button.classList.add('active');

//             const selectedRole = button.dataset.role;
//             if (selectedRole === 'agent') {
//                 agentFields.style.display = 'block';
//             } else {
//                 agentFields.style.display = 'none';
//             }
//         });
//     });

//     // Optional: Close modal when clicking outside modal-content
//     const modals = document.querySelectorAll('.modal');
//     modals.forEach(modal => {
//         modal.addEventListener('click', function (e) {
//             if (e.target === modal) {
//                 modal.style.display = 'none';
//             }
//         });
//     });

//     // Handle login form submission
//     document.getElementById('login-form')?.addEventListener('submit', function (e) {
//         e.preventDefault();
//         const email = document.getElementById('login-email').value;
//         const password = document.getElementById('login-password').value;

//         // Replace this with your API call
//         console.log('Logging in with:', { email, password });
//         alert("Login successful (demo)");
//         closeModal('login-modal');
//     });

//     // Handle register form submission
//     document.getElementById('register-form')?.addEventListener('submit', function (e) {
//         e.preventDefault();
//         const fullName = document.getElementById('reg-name').value;
//         const email = document.getElementById('reg-email').value;
//         const phone = document.getElementById('reg-phone').value;
//         const password = document.getElementById('reg-password').value;
//         const confirm = document.getElementById('reg-confirm').value;
//         const role = document.querySelector('.role-btn.active').dataset.role;

//         if (password !== confirm) {
//             alert("Passwords do not match!");
//             return;
//         }

//         const userData = {
//             fullName,
//             email,
//             phone,
//             password,
//             role
//         };

//         if (role === 'agent') {
//             userData.serviceType = document.getElementById('service-type').value;
//             userData.serviceArea = document.getElementById('service-area').value;
//         }

//         // Replace this with your API call
//         console.log('Registering:', userData);
//         alert("Registration successful (demo)");
//         closeModal('register-modal');
//     });
// });

document.addEventListener('DOMContentLoaded', () => {
  // Elements check
  const loginModal = document.getElementById('login-modal');
  const registerModal = document.getElementById('register-modal');
  const loginFormModal = document.getElementById('login-form');
  const registerFormModal = document.getElementById('register-form');
  const loginFormPage = document.getElementById('loginForm'); // full page login form
  const registerFormPage = document.getElementById('registerForm'); // if exists
  
  // Role buttons - modal or full-page
  const roleButtons = document.querySelectorAll('.role-btn, .role-tab');
  
  // Show/hide modals (only if modals exist)
  function showModal(modalId) {
    if (document.getElementById(modalId)) {
      closeAllModals();
      document.getElementById(modalId).style.display = 'flex';
    }
  }
  
  function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) modal.style.display = 'none';
  }
  
  function closeAllModals() {
    if (loginModal) loginModal.style.display = 'none';
    if (registerModal) registerModal.style.display = 'none';
  }
  
  // Role selection handler (shared)
  roleButtons.forEach(button => {
    button.addEventListener('click', () => {
      roleButtons.forEach(btn => btn.classList.remove('active'));
      button.classList.add('active');
      
      const selectedRole = button.dataset.role;
      const agentFields = document.querySelector('.agent-fields');
      if (agentFields) {
        agentFields.style.display = selectedRole === 'agent' ? 'block' : 'none';
      }
    });
  });
  
  // Attach modal show/hide triggers if modals exist
  if (loginModal && registerModal) {
    window.showLogin = () => {
      showModal('login-modal');
    };
    window.showRegister = () => {
      showModal('register-modal');
    };
  }
  
  // Password toggle (works for any input with toggle button)
  document.querySelectorAll('.password-toggle').forEach(toggleBtn => {
    toggleBtn.addEventListener('click', () => {
      const input = toggleBtn.parentElement.querySelector('input[type="password"], input[type="text"]');
      if (!input) return;
      const icon = toggleBtn.querySelector('i');
      if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'fas fa-eye-slash';
      } else {
        input.type = 'password';
        icon.className = 'fas fa-eye';
      }
    });
  });
  
  // Form submit handler helper (login)
  function handleLoginSubmit(e, form) {
    e.preventDefault();
    const emailInput = form.querySelector('input[name="email"], input#loginEmail, input#login-email');
    const passwordInput = form.querySelector('input[name="password"], input#loginPassword, input#login-password');
    
    if (!emailInput || !passwordInput) return;
    
    const email = emailInput.value.trim();
    const password = passwordInput.value;
    
    // Simple validation example
    if (!email || !password) {
      alert('Please enter both email and password');
      return;
    }
    
    // TODO: replace with your API call
    console.log('Logging in with:', { email, password });
    alert('Login successful (demo)');
    
    // Close modal if exists
    if (loginModal) closeModal('login-modal');
    else form.reset();
  }
  
  // Form submit handler helper (register)
  function handleRegisterSubmit(e, form) {
    e.preventDefault();
    
    const fullName = form.querySelector('input#reg-name, input[name="fullName"]')?.value.trim();
    const email = form.querySelector('input#reg-email, input[name="email"]')?.value.trim();
    const phone = form.querySelector('input#reg-phone, input[name="phone"]')?.value.trim();
    const password = form.querySelector('input#reg-password, input[name="password"]')?.value;
    const confirm = form.querySelector('input#reg-confirm, input[name="confirm"]')?.value;
    
    const selectedRoleBtn = Array.from(roleButtons).find(btn => btn.classList.contains('active'));
    const role = selectedRoleBtn?.dataset.role || 'user';
    
    if (!fullName || !email || !phone || !password || !confirm) {
      alert('Please fill all required fields');
      return;
    }
    
    if (password !== confirm) {
      alert('Passwords do not match!');
      return;
    }
    
    const userData = { fullName, email, phone, password, role };
    
    if (role === 'agent') {
      userData.serviceType = form.querySelector('#service-type')?.value;
      userData.serviceArea = form.querySelector('#service-area')?.value;
    }
    
    // TODO: replace with your API call
    console.log('Registering:', userData);
    alert('Registration successful (demo)');
    
    if (registerModal) closeModal('register-modal');
    else form.reset();
  }
  
  // Attach event listeners to login forms
  if (loginFormModal) loginFormModal.addEventListener('submit', e => handleLoginSubmit(e, loginFormModal));
  if (loginFormPage) loginFormPage.addEventListener('submit', e => handleLoginSubmit(e, loginFormPage));
  
  // Attach event listeners to register forms
  if (registerFormModal) registerFormModal.addEventListener('submit', e => handleRegisterSubmit(e, registerFormModal));
  if (registerFormPage) registerFormPage.addEventListener('submit', e => handleRegisterSubmit(e, registerFormPage));
  
  // Optional: click outside modal closes modal (modal only)
  if (loginModal) {
    loginModal.addEventListener('click', e => {
      if (e.target === loginModal) closeModal('login-modal');
    });
  }
  if (registerModal) {
    registerModal.addEventListener('click', e => {
      if (e.target === registerModal) closeModal('register-modal');
    });
  }
});
