/**
 * DriveSafe - Main Application Logic
 * Handles core functionality, PWA features, and user interactions
 */

class DriveSafeApp {
    constructor() {
        this.currentUser = null;
        this.currentLocation = null;
        this.deferredPrompt = null;
        this.isOnline = navigator.onLine;
        
        this.init();
    }
    
    /**
     * Initialize the application
     */
    init() {
        this.setupEventListeners();
        this.setupPWA();
        this.handleInitialLoad();
        this.checkAuthState();
        
        // Remove loading screen after initialization
        setTimeout(() => {
            const loadingScreen = document.getElementById('loading-screen');
            if (loadingScreen) {
                loadingScreen.classList.add('hide');
                setTimeout(() => loadingScreen.remove(), 500);
            }
        }, 1500);
    }
    
    /**
     * Setup event listeners
     */
    setupEventListeners() {
        // Navigation toggle
        const navToggle = document.getElementById('nav-toggle');
        const navMenu = document.getElementById('nav-menu');
        
        if (navToggle && navMenu) {
            navToggle.addEventListener('click', () => {
                navMenu.classList.toggle('active');
                navToggle.classList.toggle('active');
            });
        }
        
        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', (e) => {
                e.preventDefault();
                const target = document.querySelector(anchor.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                    // Close mobile menu if open
                    if (navMenu) navMenu.classList.remove('active');
                    if (navToggle) navToggle.classList.remove('active');
                }
            });
        });
        
        // Close modals when clicking outside
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('modal')) {
                this.closeModal(e.target.id);
            }
        });
        
        // Role selector in registration
        document.querySelectorAll('.role-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                document.querySelectorAll('.role-btn').forEach(b => b.classList.remove('active'));
                e.target.classList.add('active');
                
                const agentFields = document.querySelector('.agent-fields');
                if (agentFields) {
                    agentFields.style.display = e.target.dataset.role === 'agent' ? 'block' : 'none';
                }
            });
        });
        
        // Form submissions
        const loginForm = document.getElementById('login-form');
        const registerForm = document.getElementById('register-form');
        
        if (loginForm) {
            loginForm.addEventListener('submit', (e) => this.handleLogin(e));
        }
        
        if (registerForm) {
            registerForm.addEventListener('submit', (e) => this.handleRegister(e));
        }
        
        // Online/Offline status
        window.addEventListener('online', () => {
            this.isOnline = true;
            this.showNotification('You are back online!', 'success');
        });
        
        window.addEventListener('offline', () => {
            this.isOnline = false;
            this.showNotification('You are offline. Some features may be limited.', 'warning');
        });
        
        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                // Close any open modals
                document.querySelectorAll('.modal.active').forEach(modal => {
                    this.closeModal(modal.id);
                });
            }
        });
    }
    
    /**
     * Setup PWA features
     */
    setupPWA() {
        // Handle PWA install prompt
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            this.deferredPrompt = e;
            
            const installBtn = document.getElementById('install-btn');
            if (installBtn) {
                installBtn.style.display = 'inline-flex';
                installBtn.addEventListener('click', () => this.installPWA());
            }
        });
        
        // Handle successful PWA installation
        window.addEventListener('appinstalled', () => {
            this.showNotification('DriveSafe has been installed!', 'success');
            const installBtn = document.getElementById('install-btn');
            if (installBtn) installBtn.style.display = 'none';
        });
        
        // Handle PWA display mode
        if (window.matchMedia('(display-mode: standalone)').matches) {
            document.body.classList.add('pwa-installed');
        }
    }
    
    /**
     * Handle initial page load
     */
    handleInitialLoad() {
        // Check if user is returning from a service request
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('service')) {
            this.handleServiceReturn(urlParams.get('service'));
        }
        
        // Initialize geolocation if needed
        if ('geolocation' in navigator) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    this.currentLocation = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };
                    this.updateLocationDisplay();
                },
                (error) => {
                    console.log('Geolocation error:', error);
                },
                { enableHighAccuracy: false, timeout: 10000 }
            );
        }
    }
    
    /**
     * Check authentication state
     */
    checkAuthState() {
        const token = localStorage.getItem('drivesafe_token');
        const userData = localStorage.getItem('drivesafe_user');
        
        if (token && userData) {
            try {
                this.currentUser = JSON.parse(userData);
                this.handleAuthenticatedUser();
            } catch (error) {
                console.error('Error parsing user data:', error);
                this.logout();
            }
        }
    }
    
    /**
     * Handle authenticated user state
     */
    handleAuthenticatedUser() {
        // Redirect to appropriate dashboard based on user role
        if (this.currentUser) {
            const role = this.currentUser.role;
            switch (role) {
                case 'user':
                    window.location.href = 'pages/user/dashboard.html';
                    break;
                case 'agent':
                    window.location.href = 'pages/agent/dashboard.html';
                    break;
                case 'admin':
                    window.location.href = 'pages/admin/dashboard.html';
                    break;
                default:
                    console.error('Unknown user role:', role);
            }
        }
    }
    
    /**
     * Handle login form submission
     */
    async handleLogin(e) {
        e.preventDefault();
        
        const formData = new FormData(e.target);
        const loginData = {
            email: document.getElementById('login-email').value,
            password: document.getElementById('login-password').value,
            remember: document.getElementById('remember-me').checked
        };
        
        try {
            this.showLoading('Signing you in...');
            
            // Simulate API call for demo
            await this.delay(2000);
            
            // Mock successful login
            const mockUser = {
                id: 1,
                name: 'John Doe',
                email: loginData.email,
                role: loginData.email.includes('agent') ? 'agent' : 
                      loginData.email.includes('admin') ? 'admin' : 'user',
                phone: '+977-98XXXXXXXX',
                verified: true
            };
            
            const mockToken = 'mock_jwt_token_' + Date.now();
            
            // Store auth data
            localStorage.setItem('drivesafe_token', mockToken);
            localStorage.setItem('drivesafe_user', JSON.stringify(mockUser));
            
            this.currentUser = mockUser;
            this.hideLoading();
            this.closeModal('login-modal');
            this.showNotification('Welcome back!', 'success');
            
            // Redirect after short delay
            setTimeout(() => {
                this.handleAuthenticatedUser();
            }, 1000);
            
        } catch (error) {
            this.hideLoading();
            this.showNotification('Login failed. Please check your credentials.', 'error');
            console.error('Login error:', error);
        }
    }
    
    /**
     * Handle registration form submission
     */
    async handleRegister(e) {
        e.preventDefault();
        
        const password = document.getElementById('reg-password').value;
        const confirmPassword = document.getElementById('reg-confirm').value;
        
        if (password !== confirmPassword) {
            this.showNotification('Passwords do not match!', 'error');
            return;
        }
        
        const activeRole = document.querySelector('.role-btn.active');
        const role = activeRole ? activeRole.dataset.role : 'user';
        
        const registrationData = {
            name: document.getElementById('reg-name').value,
            email: document.getElementById('reg-email').value,
            phone: document.getElementById('reg-phone').value,
            password: password,
            role: role
        };
        
        if (role === 'agent') {
            registrationData.serviceType = document.getElementById('service-type').value;
            registrationData.serviceArea = document.getElementById('service-area').value;
        }
        
        try {
            this.showLoading('Creating your account...');
            
            // Simulate API call for demo
            await this.delay(2500);
            
            // Mock successful registration
            const mockUser = {
                id: Date.now(),
                ...registrationData,
                verified: false,
                status: role === 'agent' ? 'pending_approval' : 'active'
            };
            
            delete mockUser.password; // Don't store password
            
            const mockToken = 'mock_jwt_token_' + Date.now();
            
            // Store auth data
            localStorage.setItem('drivesafe_token', mockToken);
            localStorage.setItem('drivesafe_user', JSON.stringify(mockUser));
            
            this.currentUser = mockUser;
            this.hideLoading();
            this.closeModal('register-modal');
            
            if (role === 'agent') {
                this.showNotification('Account created! Please wait for admin approval.', 'info');
            } else {
                this.showNotification('Welcome to DriveSafe!', 'success');
            }
            
            // Redirect after short delay
            setTimeout(() => {
                this.handleAuthenticatedUser();
            }, 1000);
            
        } catch (error) {
            this.hideLoading();
            this.showNotification('Registration failed. Please try again.', 'error');
            console.error('Registration error:', error);
        }
    }
    
    /**
     * Get current location
     */
    getCurrentLocation() {
        if (!('geolocation' in navigator)) {
            this.showNotification('Geolocation is not supported by this browser.', 'error');
            return;
        }
        
        const locationInput = document.getElementById('emergency-location');
        const emergencyBtn = document.getElementById('emergency-btn');
        
        if (locationInput) locationInput.value = 'Getting your location...';
        
        navigator.geolocation.getCurrentPosition(
            (position) => {
                this.currentLocation = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };
                
                // Reverse geocode to get address (mock for demo)
                const mockAddress = 'Kathmandu, Nepal';
                
                if (locationInput) {
                    locationInput.value = mockAddress;
                }
                
                if (emergencyBtn) {
                    emergencyBtn.disabled = false;
                    emergencyBtn.textContent = '🚨 Request Emergency Help';
                }
                
                this.showNotification('Location found!', 'success');
            },
            (error) => {
                if (locationInput) locationInput.value = '';
                
                let errorMessage = 'Could not get your location. ';
                switch (error.code) {
                    case error.PERMISSION_DENIED:
                        errorMessage += 'Please allow location access.';
                        break;
                    case error.POSITION_UNAVAILABLE:
                        errorMessage += 'Location information is unavailable.';
                        break;
                    case error.TIMEOUT:
                        errorMessage += 'Location request timed out.';
                        break;
                }
                
                this.showNotification(errorMessage, 'error');
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 60000
            }
        );
    }
    
    /**
     * Request emergency help
     */
    requestEmergencyHelp() {
        if (!this.currentLocation) {
            this.showNotification('Please enable location services first.', 'warning');
            return;
        }
        
        if (!this.currentUser) {
            this.showNotification('Please login to request emergency help.', 'info');
            this.showLogin();
            return;
        }
        
        // For now, redirect to service request page
        this.showNotification('Redirecting to emergency service request...', 'info');
        setTimeout(() => {
            window.location.href = 'pages/user/request-service.html?emergency=true';
        }, 1000);
    }
    
    /**
     * Update location display
     */
    updateLocationDisplay() {
        if (this.currentLocation) {
            const locationInput = document.getElementById('emergency-location');
            const emergencyBtn = document.getElementById('emergency-btn');
            
            // Mock reverse geocoding for demo
            const mockAddress = 'Current Location - Kathmandu, Nepal';
            
            if (locationInput) {
                locationInput.value = mockAddress;
            }
            
            if (emergencyBtn) {
                emergencyBtn.disabled = false;
            }
        }
    }
    
    /**
     * Install PWA
     */
    async installPWA() {
        if (!this.deferredPrompt) return;
        
        this.deferredPrompt.prompt();
        const result = await this.deferredPrompt.userChoice;
        
        if (result.outcome === 'accepted') {
            console.log('User accepted PWA installation');
        }
        
        this.deferredPrompt = null;
    }
    
    /**
     * Show installation instructions
     */
    showInstallInstructions() {
        const instructions = `
            <div class="install-instructions">
                <h3>Install DriveSafe</h3>
                <p>For the best experience, add DriveSafe to your home screen:</p>
                <ol>
                    <li>Tap the share button in your browser</li>
                    <li>Select "Add to Home Screen"</li>
                    <li>Tap "Add" to confirm</li>
                </ol>
                <p>You'll be able to access DriveSafe like a native app!</p>
            </div>
        `;
        
        this.showNotification(instructions, 'info', 8000);
    }
    
    /**
     * Show modal
     */
    showModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
            
            // Focus first input
            const firstInput = modal.querySelector('input[type="text"], input[type="email"], input[type="tel"]');
            if (firstInput) {
                setTimeout(() => firstInput.focus(), 100);
            }
        }
    }
    
    /**
     * Close modal
     */
    closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('active');
            document.body.style.overflow = '';
            
            // Clear form if it exists
            const form = modal.querySelector('form');
            if (form) form.reset();
            
            // Reset role selector
            const roleButtons = modal.querySelectorAll('.role-btn');
            roleButtons.forEach(btn => btn.classList.remove('active'));
            if (roleButtons.length > 0) roleButtons[0].classList.add('active');
            
            // Hide agent fields
            const agentFields = modal.querySelector('.agent-fields');
            if (agentFields) agentFields.style.display = 'none';
        }
    }
    
    /**
     * Show login modal
     */
    showLogin() {
        this.showModal('login-modal');
    }
    
    /**
     * Show register modal
     */
    showRegister() {
        this.closeModal('login-modal');
        this.showModal('register-modal');
    }
    
    /**
     * Show forgot password modal
     */
    showForgotPassword() {
        this.closeModal('login-modal');
        this.showNotification('Password reset feature coming soon!', 'info');
    }
    
    /**
     * Logout user
     */
    logout() {
        localStorage.removeItem('drivesafe_token');
        localStorage.removeItem('drivesafe_user');
        this.currentUser = null;
        
        this.showNotification('You have been logged out.', 'info');
        
        // Redirect to home page if not already there
        if (window.location.pathname !== '/' && window.location.pathname !== '/index.html') {
            window.location.href = '/';
        }
    }
    
    /**
     * Show notification
     */
    showNotification(message, type = 'info', duration = 4000) {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                ${message}
                <button class="notification-close" onclick="this.parentElement.parentElement.remove()">×</button>
            </div>
        `;
        
        // Add styles if not already added
        if (!document.querySelector('#notification-styles')) {
            const styles = document.createElement('style');
            styles.id = 'notification-styles';
            styles.textContent = `
                .notification {
                    position: fixed;
                    top: calc(var(--header-height) + 20px);
                    right: 20px;
                    max-width: 400px;
                    background: white;
                    border-radius: var(--border-radius-lg);
                    box-shadow: var(--shadow-xl);
                    z-index: var(--z-toast);
                    transform: translateX(100%);
                    transition: transform var(--transition-normal);
                }
                .notification.show {
                    transform: translateX(0);
                }
                .notification-success { border-left: 4px solid var(--color-success); }
                .notification-error { border-left: 4px solid var(--color-danger); }
                .notification-warning { border-left: 4px solid var(--color-warning); }
                .notification-info { border-left: 4px solid var(--color-primary); }
                .notification-content {
                    padding: var(--spacing-4);
                    display: flex;
                    justify-content: space-between;
                    align-items: flex-start;
                    gap: var(--spacing-3);
                }
                .notification-close {
                    background: none;
                    border: none;
                    font-size: var(--font-size-lg);
                    cursor: pointer;
                    color: var(--color-gray-500);
                    padding: 0;
                    line-height: 1;
                }
                @media (max-width: 767px) {
                    .notification {
                        right: 10px;
                        left: 10px;
                        max-width: none;
                    }
                }
            `;
            document.head.appendChild(styles);
        }
        
        // Add to DOM
        document.body.appendChild(notification);
        
        // Trigger animation
        requestAnimationFrame(() => {
            notification.classList.add('show');
        });
        
        // Auto remove
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => notification.remove(), 300);
        }, duration);
    }
    
    /**
     * Show loading overlay
     */
    showLoading(message = 'Loading...') {
        const existingLoader = document.querySelector('.loading-overlay');
        if (existingLoader) return;
        
        const loader = document.createElement('div');
        loader.className = 'loading-overlay';
        loader.innerHTML = `
            <div class="loading-content">
                <div class="loading-spinner"></div>
                <p>${message}</p>
            </div>
        `;
        
        // Add styles
        const styles = document.createElement('style');
        styles.textContent = `
            .loading-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100vh;
                background: rgba(0, 0, 0, 0.7);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 9998;
                backdrop-filter: blur(5px);
            }
            .loading-content {
                background: white;
                padding: var(--spacing-8);
                border-radius: var(--border-radius-xl);
                text-align: center;
                box-shadow: var(--shadow-2xl);
            }
            .loading-content .loading-spinner {
                width: 40px;
                height: 40px;
                margin: 0 auto var(--spacing-4);
            }
        `;
        document.head.appendChild(styles);
        document.body.appendChild(loader);
    }
    
    /**
     * Hide loading overlay
     */
    hideLoading() {
        const loader = document.querySelector('.loading-overlay');
        if (loader) loader.remove();
    }
    
    /**
     * Handle service return from URL
     */
    handleServiceReturn(serviceType) {
        const messages = {
            'completed': 'Your service request has been completed successfully!',
            'cancelled': 'Your service request was cancelled.',
            'pending': 'Your service request is being processed.'
        };
        
        const message = messages[serviceType] || 'Service request updated.';
        this.showNotification(message, serviceType === 'completed' ? 'success' : 'info');
    }
    
    /**
     * Utility function to create delays
     */
    delay(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }
    
    /**
     * Format currency for Nepal
     */
    formatCurrency(amount) {
        return new Intl.NumberFormat('en-NP', {
            style: 'currency',
            currency: 'NPR',
            minimumFractionDigits: 0
        }).format(amount);
    }
    
    /**
     * Format date and time
     */
    formatDateTime(date) {
        return new Intl.DateTimeFormat('en-NP', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        }).format(new Date(date));
    }
    
    /**
     * Validate phone number (Nepal format)
     */
    validateNepalPhone(phone) {
        const nepalPhoneRegex = /^(\+977[-\s]?)?[0-9]{10}$/;
        return nepalPhoneRegex.test(phone.replace(/[-\s]/g, ''));
    }
    
    /**
     * Get distance between two points (Haversine formula)
     */
    getDistance(lat1, lng1, lat2, lng2) {
        const R = 6371; // Earth's radius in kilometers
        const dLat = this.deg2rad(lat2 - lat1);
        const dLng = this.deg2rad(lng2 - lng1);
        const a = 
            Math.sin(dLat/2) * Math.sin(dLat/2) +
            Math.cos(this.deg2rad(lat1)) * Math.cos(this.deg2rad(lat2)) * 
            Math.sin(dLng/2) * Math.sin(dLng/2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        return R * c; // Distance in kilometers
    }
    
    deg2rad(deg) {
        return deg * (Math.PI/180);
    }
    
    /**
     * Estimate ETA based on distance
     */
    estimateETA(distance) {
        // Assume average speed of 30 km/h in city traffic
        const averageSpeed = 30;
        const timeInHours = distance / averageSpeed;
        const timeInMinutes = Math.ceil(timeInHours * 60);
        
        if (timeInMinutes < 60) {
            return `${timeInMinutes} min`;
        } else {
            const hours = Math.floor(timeInMinutes / 60);
            const minutes = timeInMinutes % 60;
            return `${hours}h ${minutes}m`;
        }
    }
    
    /**
     * Check if device is mobile
     */
    isMobile() {
        return window.innerWidth <= 767;
    }
    
    /**
     * Debounce function
     */
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
    
    /**
     * Throttle function
     */
    throttle(func, limit) {
        let inThrottle;
        return function() {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    }
}

// Global functions for HTML onclick handlers
window.showLogin = () => app.showLogin();
window.showRegister = () => app.showRegister();
window.showForgotPassword = () => app.showForgotPassword();
window.closeModal = (modalId) => app.closeModal(modalId);
window.getCurrentLocation = () => app.getCurrentLocation();
window.requestEmergencyHelp = () => app.requestEmergencyHelp();
window.showInstallInstructions = () => app.showInstallInstructions();

// Initialize app when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.app = new DriveSafeApp();
});

// Handle page visibility changes (PWA optimization)
document.addEventListener('visibilitychange', () => {
    if (document.visibilityState === 'visible' && window.app) {
        // App became visible - refresh location if needed
        if (window.app.currentLocation) {
            window.app.updateLocationDisplay();
        }
    }
});

// Handle browser back button
window.addEventListener('popstate', (event) => {
    // Close any open modals
    document.querySelectorAll('.modal.active').forEach(modal => {
        if (window.app) window.app.closeModal(modal.id);
    });
});

// Export for ES6 modules (if used elsewhere)
export default DriveSafeApp;