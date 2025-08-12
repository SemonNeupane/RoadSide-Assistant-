// notifications.js - Push Notifications System

class NotificationManager {
    constructor() {
        this.vapidPublicKey = 'YOUR_VAPID_PUBLIC_KEY'; // Replace with actual VAPID key
        this.subscription = null;
        this.isSupported = 'serviceWorker' in navigator && 'PushManager' in window;
        this.permission = Notification.permission;
        
        this.init();
    }

    async init() {
        if (!this.isSupported) {
            console.warn('Push notifications not supported');
            return;
        }

        try {
            const registration = await navigator.serviceWorker.ready;
            this.registration = registration;
            
            // Check for existing subscription
            this.subscription = await registration.pushManager.getSubscription();
            
            // Update UI based on subscription status
            this.updateSubscriptionStatus();
            
        } catch (error) {
            console.error('Error initializing notifications:', error);
        }
    }

    async requestPermission() {
        if (!this.isSupported) {
            throw new Error('Push notifications not supported');
        }

        try {
            const permission = await Notification.requestPermission();
            this.permission = permission;
            
            if (permission === 'granted') {
                Utils.showNotification('Notifications enabled!', 'success');
                return true;
            } else if (permission === 'denied') {
                Utils.showNotification('Notifications blocked. Please enable in browser settings.', 'warning');
                return false;
            } else {
                Utils.showNotification('Notification permission dismissed.', 'info');
                return false;
            }
        } catch (error) {
            console.error('Error requesting notification permission:', error);
            throw error;
        }
    }

    async subscribe() {
        if (!this.registration) {
            throw new Error('Service worker not registered');
        }

        if (this.permission !== 'granted') {
            const granted = await this.requestPermission();
            if (!granted) return null;
        }

        try {
            const subscription = await this.registration.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: this.urlBase64ToUint8Array(this.vapidPublicKey)
            });

            this.subscription = subscription;
            
            // Send subscription to server
            await this.sendSubscriptionToServer(subscription);
            
            Utils.showNotification('Push notifications activated!', 'success');
            this.updateSubscriptionStatus();
            
            return subscription;
        } catch (error) {
            console.error('Error subscribing to push notifications:', error);
            Utils.showNotification('Failed to enable push notifications', 'error');
            throw error;
        }
    }

    async unsubscribe() {
        if (!this.subscription) {
            return;
        }

        try {
            await this.subscription.unsubscribe();
            
            // Remove subscription from server
            await this.removeSubscriptionFromServer(this.subscription);
            
            this.subscription = null;
            Utils.showNotification('Push notifications disabled', 'info');
            this.updateSubscriptionStatus();
            
        } catch (error) {
            console.error('Error unsubscribing from push notifications:', error);
            Utils.showNotification('Failed to disable push notifications', 'error');
        }
    }

    async sendSubscriptionToServer(subscription) {
        const subscriptionData = {
            endpoint: subscription.endpoint,
            keys: {
                p256dh: this.arrayBufferToBase64(subscription.getKey('p256dh')),
                auth: this.arrayBufferToBase64(subscription.getKey('auth'))
            },
            userId: Auth.getCurrentUser()?.id,
            userAgent: navigator.userAgent,
            timestamp: new Date().toISOString()
        };

        try {
            const response = await fetch(`${API_BASE_URL}/notifications/subscribe`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${Auth.getToken()}`
                },
                body: JSON.stringify(subscriptionData)
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const result = await response.json();
            console.log('Subscription saved to server:', result);
            
        } catch (error) {
            console.error('Failed to save subscription to server:', error);
            // Don't throw error here to avoid breaking the subscription process
        }
    }

    async removeSubscriptionFromServer(subscription) {
        try {
            const response = await fetch(`${API_BASE_URL}/notifications/unsubscribe`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${Auth.getToken()}`
                },
                body: JSON.stringify({
                    endpoint: subscription.endpoint,
                    userId: Auth.getCurrentUser()?.id
                })
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

        } catch (error) {
            console.error('Failed to remove subscription from server:', error);
        }
    }

    showLocalNotification(title, options = {}) {
        if (this.permission !== 'granted') {
            console.warn('Notification permission not granted');
            return;
        }

        const defaultOptions = {
            icon: '/assets/images/logo.png',
            badge: '/assets/images/favicon.ico',
            tag: 'roadassist-notification',
            requireInteraction: false,
            ...options
        };

        return new Notification(title, defaultOptions);
    }

    updateSubscriptionStatus() {
        const subscribeBtn = document.getElementById('subscribe-notifications');
        const unsubscribeBtn = document.getElementById('unsubscribe-notifications');
        const notificationStatus = document.getElementById('notification-status');
        
        if (subscribeBtn && unsubscribeBtn && notificationStatus) {
            if (this.subscription) {
                subscribeBtn.style.display = 'none';
                unsubscribeBtn.style.display = 'inline-block';
                notificationStatus.textContent = 'Notifications: Enabled';
                notificationStatus.className = 'status-enabled';
            } else {
                subscribeBtn.style.display = 'inline-block';
                unsubscribeBtn.style.display = 'none';
                notificationStatus.textContent = 'Notifications: Disabled';
                notificationStatus.className = 'status-disabled';
            }
        }
    }

    // Utility methods
    urlBase64ToUint8Array(base64String) {
        const padding = '='.repeat((4 - base64String.length % 4) % 4);
        const base64 = (base64String + padding)
            .replace(/-/g, '+')
            .replace(/_/g, '/');

        const rawData = window.atob(base64);
        const outputArray = new Uint8Array(rawData.length);

        for (let i = 0; i < rawData.length; ++i) {
            outputArray[i] = rawData.charCodeAt(i);
        }
        return outputArray;
    }

    arrayBufferToBase64(buffer) {
        const bytes = new Uint8Array(buffer);
        let binary = '';
        for (let i = 0; i < bytes.byteLength; i++) {
            binary += String.fromCharCode(bytes[i]);
        }
        return window.btoa(binary);
    }

    // Notification templates
    getServiceNotificationTemplate(type, data) {
        const templates = {
            'service_requested': {
                title: '🚗 Service Request Received',
                body: `Your ${data.serviceType} request has been received. Finding nearby agents...`,
                icon: '/assets/images/icons/service.png',
                tag: 'service-request',
                actions: [
                    {
                        action: 'track',
                        title: 'Track Service'
                    },
                    {
                        action: 'cancel',
                        title: 'Cancel'
                    }
                ]
            },
            'agent_assigned': {
                title: '👨‍🔧 Agent Assigned',
                body: `${data.agentName} is heading to your location. ETA: ${data.eta} minutes`,
                icon: '/assets/images/icons/agent.png',
                tag: 'agent-assigned',
                requireInteraction: true,
                actions: [
                    {
                        action: 'call',
                        title: 'Call Agent'
                    },
                    {
                        action: 'track',
                        title: 'Track'
                    }
                ]
            },
            'agent_arrived': {
                title: '✅ Agent Arrived',
                body: `${data.agentName} has arrived at your location`,
                icon: '/assets/images/icons/arrived.png',
                tag: 'agent-arrived',
                requireInteraction: true,
                vibrate: [200, 100, 200]
            },
            'service_completed': {
                title: '🎉 Service Completed',
                body: `Your ${data.serviceType} service is complete. Please rate your experience.`,
                icon: '/assets/images/icons/completed.png',
                tag: 'service-completed',
                actions: [
                    {
                        action: 'rate',
                        title: 'Rate Service'
                    },
                    {
                        action: 'receipt',
                        title: 'View Receipt'
                    }
                ]
            },
            'payment_due': {
                title: '💳 Payment Required',
                body: `Please complete payment of ${data.amount} for your service`,
                icon: '/assets/images/icons/payment.png',
                tag: 'payment-due',
                requireInteraction: true,
                actions: [
                    {
                        action: 'pay',
                        title: 'Pay Now'
                    }
                ]
            },
            'new_job': {
                title: '🔔 New Job Available',
                body: `${data.serviceType} service needed ${data.distance}km away. Earn ${data.earnings}`,
                icon: '/assets/images/icons/job.png',
                tag: 'new-job',
                requireInteraction: true,
                actions: [
                    {
                        action: 'accept',
                        title: 'Accept Job'
                    },
                    {
                        action: 'decline',
                        title: 'Decline'
                    }
                ]
            }
        };

        return templates[type] || {
            title: 'RoadAssist Notification',
            body: data.message || 'You have a new notification',
            icon: '/assets/images/logo.png'
        };
    }

    // Handle notification clicks
    setupNotificationHandlers() {
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.addEventListener('message', event => {
                if (event.data && event.data.type === 'NOTIFICATION_CLICK') {
                    this.handleNotificationClick(event.data.action, event.data.data);
                }
            });
        }
    }

    handleNotificationClick(action, data) {
        switch (action) {
            case 'track':
                window.open('/pages/user/track-service.html', '_blank');
                break;
            case 'call':
                if (data.agentPhone) {
                    window.open(`tel:${data.agentPhone}`, '_self');
                }
                break;
            case 'rate':
                window.open(`/pages/user/rate-service.html?id=${data.serviceId}`, '_blank');
                break;
            case 'pay':
                window.open(`/pages/user/payment.html?id=${data.serviceId}`, '_blank');
                break;
            case 'accept':
                // Handle job acceptance
                this.acceptJob(data.jobId);
                break;
            case 'decline':
                // Handle job decline
                this.declineJob(data.jobId);
                break;
            default:
                window.focus();
        }
    }

    async acceptJob(jobId) {
        try {
            const response = await fetch(`${API_BASE_URL}/jobs/${jobId}/accept`, {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${Auth.getToken()}`
                }
            });

            if (response.ok) {
                Utils.showNotification('Job accepted successfully!', 'success');
                window.open('/pages/agent/jobs.html', '_blank');
            }
        } catch (error) {
            console.error('Error accepting job:', error);
            Utils.showNotification('Failed to accept job', 'error');
        }
    }

    async declineJob(jobId) {
        try {
            const response = await fetch(`${API_BASE_URL}/jobs/${jobId}/decline`, {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${Auth.getToken()}`
                }
            });

            if (response.ok) {
                Utils.showNotification('Job declined', 'info');
            }
        } catch (error) {
            console.error('Error declining job:', error);
        }
    }
}

// Create global notification manager instance
const notificationManager = new NotificationManager();

// Export for use in other modules
window.NotificationManager = NotificationManager;
window.notifications = notificationManager;

// Setup notification handlers when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    notificationManager.setupNotificationHandlers();
    
    // Add event listeners for notification buttons if they exist
    const subscribeBtn = document.getElementById('subscribe-notifications');
    const unsubscribeBtn = document.getElementById('unsubscribe-notifications');
    
    if (subscribeBtn) {
        subscribeBtn.addEventListener('click', () => {
            notificationManager.subscribe().catch(console.error);
        });
    }
    
    if (unsubscribeBtn) {
        unsubscribeBtn.addEventListener('click', () => {
            notificationManager.unsubscribe().catch(console.error);
        });
    }
});