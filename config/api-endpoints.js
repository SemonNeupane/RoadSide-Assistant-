/**
 * API Endpoints Configuration
 * Centralized API endpoint management for DriveSafe
 */

// Base configuration
const API_BASE_URL = 'https://api.drivesafe.com.np/v1'; // Replace with actual API URL
const API_VERSION = 'v1';

// API Endpoints
const API_ENDPOINTS = {
    // Authentication endpoints
    auth: {
        login: `${API_BASE_URL}/auth/login`,
        register: `${API_BASE_URL}/auth/register`,
        logout: `${API_BASE_URL}/auth/logout`,
        refreshToken: `${API_BASE_URL}/auth/refresh`,
        forgotPassword: `${API_BASE_URL}/auth/forgot-password`,
        resetPassword: `${API_BASE_URL}/auth/reset-password`,
        verifyEmail: `${API_BASE_URL}/auth/verify-email`,
        verifyPhone: `${API_BASE_URL}/auth/verify-phone`,
        checkAuth: `${API_BASE_URL}/auth/check`,
        changePassword: `${API_BASE_URL}/auth/change-password`
    },
    
    // User management endpoints
    users: {
        profile: `${API_BASE_URL}/users/profile`,
        updateProfile: `${API_BASE_URL}/users/profile`,
        uploadAvatar: `${API_BASE_URL}/users/avatar`,
        deleteAccount: `${API_BASE_URL}/users/delete`,
        preferences: `${API_BASE_URL}/users/preferences`,
        notifications: `${API_BASE_URL}/users/notifications`,
        history: `${API_BASE_URL}/users/history`
    },
    
    // Service request endpoints
    services: {
        request: `${API_BASE_URL}/services/request`,
        list: `${API_BASE_URL}/services`,
        details: (id) => `${API_BASE_URL}/services/${id}`,
        cancel: (id) => `${API_BASE_URL}/services/${id}/cancel`,
        track: (id) => `${API_BASE_URL}/services/${id}/track`,
        rate: (id) => `${API_BASE_URL}/services/${id}/rate`,
        receipt: (id) => `${API_BASE_URL}/services/${id}/receipt`,
        estimate: `${API_BASE_URL}/services/estimate`,
        types: `${API_BASE_URL}/services/types`,
        availability: `${API_BASE_URL}/services/availability`
    },
    
    // Agent endpoints
    agents: {
        register: `${API_BASE_URL}/agents/register`,
        profile: `${API_BASE_URL}/agents/profile`,
        availability: `${API_BASE_URL}/agents/availability`,
        jobs: `${API_BASE_URL}/agents/jobs`,
        acceptJob: (id) => `${API_BASE_URL}/agents/jobs/${id}/accept`,
        rejectJob: (id) => `${API_BASE_URL}/agents/jobs/${id}/reject`,
        updateStatus: (id) => `${API_BASE_URL}/agents/jobs/${id}/status`,
        completeJob: (id) => `${API_BASE_URL}/agents/jobs/${id}/complete`,
        location: `${API_BASE_URL}/agents/location`,
        earnings: `${API_BASE_URL}/agents/earnings`,
        reports: `${API_BASE_URL}/agents/reports`,
        documents: `${API_BASE_URL}/agents/documents`,
        ratings: `${API_BASE_URL}/agents/ratings`,
        nearby: `${API_BASE_URL}/agents/nearby`
    },
    
    // Payment endpoints
    payments: {
        methods: `${API_BASE_URL}/payments/methods`,
        process: `${API_BASE_URL}/payments/process`,
        verify: `${API_BASE_URL}/payments/verify`,
        history: `${API_BASE_URL}/payments/history`,
        refund: `${API_BASE_URL}/payments/refund`,
        esewa: {
            initiate: `${API_BASE_URL}/payments/esewa/initiate`,
            verify: `${API_BASE_URL}/payments/esewa/verify`,
            callback: `${API_BASE_URL}/payments/esewa/callback`
        },
        khalti: {
            initiate: `${API_BASE_URL}/payments/khalti/initiate`,
            verify: `${API_BASE_URL}/payments/khalti/verify`,
            callback: `${API_BASE_URL}/payments/khalti/callback`
        }
    },
    
    // Location and mapping endpoints
    location: {
        geocode: `${API_BASE_URL}/location/geocode`,
        reverseGeocode: `${API_BASE_URL}/location/reverse-geocode`,
        autocomplete: `${API_BASE_URL}/location/autocomplete`,
        directions: `${API_BASE_URL}/location/directions`,
        serviceAreas: `${API_BASE_URL}/location/service-areas`,
        nearbyServices: `${API_BASE_URL}/location/nearby-services`
    },
    
    // Admin endpoints
    admin: {
        dashboard: `${API_BASE_URL}/admin/dashboard`,
        users: `${API_BASE_URL}/admin/users`,
        agents: `${API_BASE_URL}/admin/agents`,
        approveAgent: (id) => `${API_BASE_URL}/admin/agents/${id}/approve`,
        rejectAgent: (id) => `${API_BASE_URL}/admin/agents/${id}/reject`,
        suspendAgent: (id) => `${API_BASE_URL}/admin/agents/${id}/suspend`,
        services: `${API_BASE_URL}/admin/services`,
        payments: `${API_BASE_URL}/admin/payments`,
        reports: `${API_BASE_URL}/admin/reports`,
        settings: `${API_BASE_URL}/admin/settings`,
        zones: `${API_BASE_URL}/admin/zones`,
        pricing: `${API_BASE_URL}/admin/pricing`,
        notifications: `${API_BASE_URL}/admin/notifications`,
        analytics: `${API_BASE_URL}/admin/analytics`
    },
    
    // Notification endpoints
    notifications: {
        send: `${API_BASE_URL}/notifications/send`,
        list: `${API_BASE_URL}/notifications`,
        markRead: (id) => `${API_BASE_URL}/notifications/${id}/read`,
        markAllRead: `${API_BASE_URL}/notifications/read-all`,
        settings: `${API_BASE_URL}/notifications/settings`,
        subscribe: `${API_BASE_URL}/notifications/subscribe`,
        unsubscribe: `${API_BASE_URL}/notifications/unsubscribe`
    },
    
    // File upload endpoints
    upload: {
        image: `${API_BASE_URL}/upload/image`,
        document: `${API_BASE_URL}/upload/document`,
        avatar: `${API_BASE_URL}/upload/avatar`,
        servicePhoto: `${API_BASE_URL}/upload/service-photo`,
        agentDocument: `${API_BASE_URL}/upload/agent-document`
    },
    
    // System endpoints
    system: {
        health: `${API_BASE_URL}/system/health`,
        version: `${API_BASE_URL}/system/version`,
        status: `${API_BASE_URL}/system/status`,
        maintenance: `${API_BASE_URL}/system/maintenance`
    },
    
    // Real-time endpoints (WebSocket)
    websocket: {
        base: 'wss://ws.drivesafe.com.np',
        tracking: 'wss://ws.drivesafe.com.np/tracking',
        notifications: 'wss://ws.drivesafe.com.np/notifications',
        agent: 'wss://ws.drivesafe.com.np/agent',
        admin: 'wss://ws.drivesafe.com.np/admin'
    }
};

// HTTP Methods
const HTTP_METHODS = {
    GET: 'GET',
    POST: 'POST',
    PUT: 'PUT',
    PATCH: 'PATCH',
    DELETE: 'DELETE'
};

// API Response Status Codes
const HTTP_STATUS = {
    OK: 200,
    CREATED: 201,
    ACCEPTED: 202,
    NO_CONTENT: 204,
    BAD_REQUEST: 400,
    UNAUTHORIZED: 401,
    FORBIDDEN: 403,
    NOT_FOUND: 404,
    METHOD_NOT_ALLOWED: 405,
    CONFLICT: 409,
    UNPROCESSABLE_ENTITY: 422,
    TOO_MANY_REQUESTS: 429,
    INTERNAL_SERVER_ERROR: 500,
    BAD_GATEWAY: 502,
    SERVICE_UNAVAILABLE: 503,
    GATEWAY_TIMEOUT: 504
};

// Request Headers
const DEFAULT_HEADERS = {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'X-API-Version': API_VERSION
};

// API Client Configuration
const API_CONFIG = {
    baseURL: API_BASE_URL,
    timeout: 30000,
    retryAttempts: 3,
    retryDelay: 1000,
    headers: DEFAULT_HEADERS
};

/**
 * API Client Class
 * Handles all HTTP requests with built-in error handling, retry logic, and authentication
 */
class APIClient {
    constructor(config = {}) {
        this.config = { ...API_CONFIG, ...config };
        this.authToken = localStorage.getItem('drivesafe_token');
    }
    
    /**
     * Set authentication token
     */
    setAuthToken(token) {
        this.authToken = token;
        if (token) {
            localStorage.setItem('drivesafe_token', token);
        } else {
            localStorage.removeItem('drivesafe_token');
        }
    }
    
    /**
     * Get request headers with authentication
     */
    getHeaders(customHeaders = {}) {
        const headers = { ...this.config.headers, ...customHeaders };
        
        if (this.authToken) {
            headers['Authorization'] = `Bearer ${this.authToken}`;
        }
        
        return headers;
    }
    
    /**
     * Make HTTP request with retry logic
     */
    async request(url, options = {}, attempt = 1) {
        const config = {
            method: options.method || HTTP_METHODS.GET,
            headers: this.getHeaders(options.headers),
            ...options
        };
        
        // Add body for POST/PUT/PATCH requests
        if (config.method !== HTTP_METHODS.GET && config.method !== HTTP_METHODS.DELETE) {
            if (options.body && typeof options.body === 'object') {
                config.body = JSON.stringify(options.body);
            }
        }
        
        try {
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), this.config.timeout);
            
            const response = await fetch(url, {
                ...config,
                signal: controller.signal
            });
            
            clearTimeout(timeoutId);
            
            // Handle different response types
            let data;
            const contentType = response.headers.get('content-type');
            
            if (contentType && contentType.includes('application/json')) {
                data = await response.json();
            } else if (contentType && contentType.includes('text/')) {
                data = await response.text();
            } else {
                data = await response.blob();
            }
            
            if (!response.ok) {
                throw new APIError(data.message || 'Request failed', response.status, data);
            }
            
            return {
                data,
                status: response.status,
                headers: response.headers,
                ok: response.ok
            };
            
        } catch (error) {
            // Retry logic for network errors
            if (attempt < this.config.retryAttempts && this.shouldRetry(error)) {
                await this.delay(this.config.retryDelay * attempt);
                return this.request(url, options, attempt + 1);
            }
            
            // Handle specific error types
            if (error.name === 'AbortError') {
                throw new APIError('Request timeout', 408);
            }
            
            if (error instanceof APIError) {
                throw error;
            }
            
            throw new APIError(error.message || 'Network error', 0, error);
        }
    }
    
    /**
     * Determine if request should be retried
     */
    shouldRetry(error) {
        // Retry on network errors or 5xx server errors
        return !error.status || (error.status >= 500 && error.status < 600);
    }
    
    /**
     * Delay utility for retry logic
     */
    delay(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }
    
    // HTTP method shortcuts
    async get(url, options = {}) {
        return this.request(url, { ...options, method: HTTP_METHODS.GET });
    }
    
    async post(url, body, options = {}) {
        return this.request(url, { ...options, method: HTTP_METHODS.POST, body });
    }
    
    async put(url, body, options = {}) {
        return this.request(url, { ...options, method: HTTP_METHODS.PUT, body });
    }
    
    async patch(url, body, options = {}) {
        return this.request(url, { ...options, method: HTTP_METHODS.PATCH, body });
    }
    
    async delete(url, options = {}) {
        return this.request(url, { ...options, method: HTTP_METHODS.DELETE });
    }
}

/**
 * API Error Class
 */
class APIError extends Error {
    constructor(message, status = 0, data = null) {
        super(message);
        this.name = 'APIError';
        this.status = status;
        this.data = data;
    }
}

/**
 * Service-specific API methods
 */
const API_SERVICES = {
    // Authentication services
    auth: {
        async login(credentials) {
            return apiClient.post(API_ENDPOINTS.auth.login, credentials);
        },
        
        async register(userData) {
            return apiClient.post(API_ENDPOINTS.auth.register, userData);
        },
        
        async logout() {
            const result = await apiClient.post(API_ENDPOINTS.auth.logout);
            apiClient.setAuthToken(null);
            return result;
        },
        
        async refreshToken() {
            return apiClient.post(API_ENDPOINTS.auth.refreshToken);
        },
        
        async forgotPassword(email) {
            return apiClient.post(API_ENDPOINTS.auth.forgotPassword, { email });
        },
        
        async resetPassword(token, password) {
            return apiClient.post(API_ENDPOINTS.auth.resetPassword, { token, password });
        }
    },
    
    // Service request services
    services: {
        async requestService(serviceData) {
            return apiClient.post(API_ENDPOINTS.services.request, serviceData);
        },
        
        async getServices(filters = {}) {
            const queryParams = new URLSearchParams(filters).toString();
            const url = queryParams ? `${API_ENDPOINTS.services.list}?${queryParams}` : API_ENDPOINTS.services.list;
            return apiClient.get(url);
        },
        
        async getServiceDetails(id) {
            return apiClient.get(API_ENDPOINTS.services.details(id));
        },
        
        async cancelService(id, reason) {
            return apiClient.post(API_ENDPOINTS.services.cancel(id), { reason });
        },
        
        async trackService(id) {
            return apiClient.get(API_ENDPOINTS.services.track(id));
        },
        
        async rateService(id, rating, comment) {
            return apiClient.post(API_ENDPOINTS.services.rate(id), { rating, comment });
        },
        
        async getEstimate(serviceData) {
            return apiClient.post(API_ENDPOINTS.services.estimate, serviceData);
        }
    },
    
    // Agent services
    agents: {
        async updateAvailability(available) {
            return apiClient.patch(API_ENDPOINTS.agents.availability, { available });
        },
        
        async getJobs(status = null) {
            const url = status ? `${API_ENDPOINTS.agents.jobs}?status=${status}` : API_ENDPOINTS.agents.jobs;
            return apiClient.get(url);
        },
        
        async acceptJob(jobId) {
            return apiClient.post(API_ENDPOINTS.agents.acceptJob(jobId));
        },
        
        async updateJobStatus(jobId, status, notes = '') {
            return apiClient.patch(API_ENDPOINTS.agents.updateStatus(jobId), { status, notes });
        },
        
        async updateLocation(lat, lng) {
            return apiClient.patch(API_ENDPOINTS.agents.location, { lat, lng });
        },
        
        async getEarnings(period = 'month') {
            return apiClient.get(`${API_ENDPOINTS.agents.earnings}?period=${period}`);
        }
    },
    
    // Payment services
    payments: {
        async getPaymentMethods() {
            return apiClient.get(API_ENDPOINTS.payments.methods);
        },
        
        async processPayment(paymentData) {
            return apiClient.post(API_ENDPOINTS.payments.process, paymentData);
        },
        
        async verifyPayment(transactionId) {
            return apiClient.post(API_ENDPOINTS.payments.verify, { transactionId });
        },
        
        async initiateEsewaPayment(amount, serviceId) {
            return apiClient.post(API_ENDPOINTS.payments.esewa.initiate, { amount, serviceId });
        },
        
        async initiateKhaltiPayment(amount, serviceId) {
            return apiClient.post(API_ENDPOINTS.payments.khalti.initiate, { amount, serviceId });
        }
    },
    
    // Location services
    location: {
        async geocode(address) {
            return apiClient.get(`${API_ENDPOINTS.location.geocode}?address=${encodeURIComponent(address)}`);
        },
        
        async reverseGeocode(lat, lng) {
            return apiClient.get(`${API_ENDPOINTS.location.reverseGeocode}?lat=${lat}&lng=${lng}`);
        },
        
        async getDirections(origin, destination) {
            return apiClient.get(`${API_ENDPOINTS.location.directions}?origin=${origin}&destination=${destination}`);
        },
        
        async getNearbyAgents(lat, lng, serviceType) {
            return apiClient.get(`${API_ENDPOINTS.agents.nearby}?lat=${lat}&lng=${lng}&service=${serviceType}`);
        }
    }
};

// Create global API client instance
const apiClient = new APIClient();

// Mock API responses for development/demo
const MOCK_RESPONSES = {
    enabled: true, // Set to false for production
    
    responses: {
        [API_ENDPOINTS.auth.login]: {
            data: { token: 'mock_token', user: { id: 1, name: 'Demo User', role: 'user' } },
            status: 200
        },
        [API_ENDPOINTS.services.types]: {
            data: Object.values(window.APP_CONFIG?.serviceTypes || {}),
            status: 200
        }
    }
};

// Override fetch for mock responses in development
if (MOCK_RESPONSES.enabled && typeof window !== 'undefined') {
    const originalFetch = window.fetch;
    
    window.fetch = async function(url, options = {}) {
        // Check if we have a mock response for this URL
        if (MOCK_RESPONSES.responses[url]) {
            const mockResponse = MOCK_RESPONSES.responses[url];
            
            return new Response(JSON.stringify(mockResponse.data), {
                status: mockResponse.status,
                headers: { 'Content-Type': 'application/json' }
            });
        }
        
        // Use original fetch for non-mocked requests
        return originalFetch(url, options);
    };
}

// Export for global use
if (typeof window !== 'undefined') {
    window.API_ENDPOINTS = API_ENDPOINTS;
    window.HTTP_METHODS = HTTP_METHODS;
    window.HTTP_STATUS = HTTP_STATUS;
    window.APIClient = APIClient;
    window.APIError = APIError;
    window.API_SERVICES = API_SERVICES;
    window.apiClient = apiClient;
}

// Export for ES6 modules
export {
    API_ENDPOINTS,
    HTTP_METHODS,
    HTTP_STATUS,
    APIClient,
    APIError,
    API_SERVICES,
    apiClient
};