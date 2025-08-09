/**
 * DriveSafe Application Constants
 * Central configuration for the application
 */

const APP_CONFIG = {
    name: 'DriveSafe',
    version: '1.0.0',
    description: 'Roadside Assistance System for Nepal',
    
    // Service Areas
    serviceAreas: {
        kathmandu: {
            name: 'Kathmandu Valley',
            bounds: {
                north: 27.7772,
                south: 27.6588,
                east: 85.4560,
                west: 85.2017
            },
            districts: ['Kathmandu', 'Lalitpur', 'Bhaktapur']
        },
        pokhara: {
            name: 'Pokhara',
            bounds: {
                north: 28.2380,
                south: 28.1853,
                east: 84.0099,
                west: 83.9738
            },
            districts: ['Kaski']
        },
        chitwan: {
            name: 'Chitwan',
            bounds: {
                north: 27.7056,
                south: 27.5290,
                east: 84.5360,
                west: 84.2906
            },
            districts: ['Chitwan']
        }
    },
    
    // Service Types
    serviceTypes: {
        battery: {
            name: 'Battery Jump-Start',
            icon: '🔋',
            color: '#FF6B35',
            estimatedTime: 15,
            basePrice: 500,
            description: 'Professional battery jump-start service'
        },
        towing: {
            name: 'Vehicle Towing',
            icon: '🚗',
            color: '#004E89',
            estimatedTime: 30,
            basePrice: 1500,
            description: 'Safe vehicle towing to workshop or location'
        },
        ev: {
            name: 'EV Support',
            icon: '⚡',
            color: '#7209B7',
            estimatedTime: 45,
            basePrice: 800,
            description: 'Electric vehicle charging and support'
        },
        repair: {
            name: 'Minor Repairs',
            icon: '🔧',
            color: '#F77F00',
            estimatedTime: 60,
            basePrice: 1000,
            description: 'Quick roadside repairs and fixes'
        },
        tire: {
            name: 'Tire Services',
            icon: '🛞',
            color: '#2D3748',
            estimatedTime: 30,
            basePrice: 600,
            description: 'Flat tire repair and replacement'
        },
        wash: {
            name: 'Mobile Car Wash',
            icon: '🧽',
            color: '#38B2AC',
            estimatedTime: 90,
            basePrice: 800,
            description: 'Professional car cleaning at your location'
        },
        fuel: {
            name: 'Fuel Assistance',
            icon: '⛽',
            color: '#E53E3E',
            estimatedTime: 20,
            basePrice: 300,
            description: 'Fuel station referral and towing (delivery not permitted by law)'
        }
    },
    
    // Vehicle Types
    vehicleTypes: {
        motorcycle: {
            name: 'Motorcycle',
            icon: '🏍️',
            fuelTypes: ['petrol']
        },
        car: {
            name: 'Car',
            icon: '🚗',
            fuelTypes: ['petrol', 'diesel', 'hybrid', 'electric']
        },
        suv: {
            name: 'SUV',
            icon: '🚙',
            fuelTypes: ['petrol', 'diesel', 'hybrid', 'electric']
        },
        truck: {
            name: 'Truck',
            icon: '🚚',
            fuelTypes: ['diesel']
        },
        bus: {
            name: 'Bus',
            icon: '🚌',
            fuelTypes: ['diesel']
        },
        van: {
            name: 'Van',
            icon: '🚐',
            fuelTypes: ['petrol', 'diesel']
        }
    },
    
    // Fuel Types
    fuelTypes: {
        petrol: {
            name: 'Petrol',
            icon: '⛽',
            color: '#E53E3E'
        },
        diesel: {
            name: 'Diesel',
            icon: '⛽',
            color: '#2D3748'
        },
        electric: {
            name: 'Electric',
            icon: '🔌',
            color: '#38B2AC'
        },
        hybrid: {
            name: 'Hybrid',
            icon: '🔋',
            color: '#805AD5'
        }
    },
    
    // User Roles
    userRoles: {
        user: {
            name: 'Customer',
            permissions: ['request_service', 'view_history', 'make_payment'],
            dashboard: 'user'
        },
        agent: {
            name: 'Service Agent',
            permissions: ['accept_jobs', 'update_status', 'generate_invoice', 'view_earnings'],
            dashboard: 'agent'
        },
        admin: {
            name: 'Administrator',
            permissions: ['manage_agents', 'view_reports', 'manage_zones', 'system_settings'],
            dashboard: 'admin'
        }
    },
    
    // Service Status
    serviceStatus: {
        requested: {
            name: 'Requested',
            color: '#3182CE',
            icon: '🔵'
        },
        accepted: {
            name: 'Agent Assigned',
            color: '#38A169',
            icon: '✅'
        },
        enroute: {
            name: 'Agent En Route',
            color: '#D69E2E',
            icon: '🚗'
        },
        arrived: {
            name: 'Agent Arrived',
            color: '#9F7AEA',
            icon: '📍'
        },
        inprogress: {
            name: 'Service in Progress',
            color: '#ED8936',
            icon: '🔧'
        },
        completed: {
            name: 'Completed',
            color: '#38A169',
            icon: '✅'
        },
        cancelled: {
            name: 'Cancelled',
            color: '#E53E3E',
            icon: '❌'
        },
        failed: {
            name: 'Failed',
            color: '#E53E3E',
            icon: '⚠️'
        }
    },
    
    // Payment Methods
    paymentMethods: {
        cod: {
            name: 'Cash on Delivery',
            icon: '💰',
            enabled: true,
            description: 'Pay cash when service is completed'
        },
        esewa: {
            name: 'eSewa',
            icon: '📱',
            enabled: true,
            description: 'Digital wallet payment',
            processingFee: 0.02 // 2%
        },
        khalti: {
            name: 'Khalti',
            icon: '📱',
            enabled: true,
            description: 'Digital wallet payment',
            processingFee: 0.015 // 1.5%
        },
        bank: {
            name: 'Bank Transfer',
            icon: '🏦',
            enabled: false,
            description: 'Direct bank account transfer'
        }
    },
    
    // Agent Specializations
    agentSpecializations: {
        towing: {
            name: 'Towing Specialist',
            services: ['towing'],
            requiredEquipment: ['tow_truck', 'safety_equipment']
        },
        mechanic: {
            name: 'Mechanic',
            services: ['repair', 'battery', 'tire'],
            requiredEquipment: ['toolkit', 'spare_parts']
        },
        ev_specialist: {
            name: 'EV Specialist',
            services: ['ev', 'battery'],
            requiredEquipment: ['portable_charger', 'ev_toolkit']
        },
        general: {
            name: 'General Support',
            services: ['battery', 'tire', 'wash', 'fuel'],
            requiredEquipment: ['basic_toolkit']
        }
    },
    
    // Time Zones and Formats
    timezone: 'Asia/Kathmandu',
    dateFormat: 'YYYY-MM-DD',
    timeFormat: 'HH:mm',
    dateTimeFormat: 'YYYY-MM-DD HH:mm',
    currency: 'NPR',
    
    // API Configuration
    api: {
        timeout: 30000, // 30 seconds
        retryAttempts: 3,
        retryDelay: 1000 // 1 second
    },
    
    // Map Configuration
    map: {
        defaultZoom: 13,
        maxZoom: 18,
        minZoom: 8,
        defaultCenter: {
            lat: 27.7172,
            lng: 85.3240 // Kathmandu
        },
        searchRadius: 10000, // 10km in meters
        trackingInterval: 30000 // 30 seconds
    },
    
    // Notification Settings
    notifications: {
        duration: 4000, // 4 seconds
        position: 'top-right',
        maxVisible: 3
    },
    
    // File Upload Limits
    upload: {
        maxFileSize: 5 * 1024 * 1024, // 5MB
        allowedTypes: ['image/jpeg', 'image/png', 'image/gif'],
        maxFiles: 3
    },
    
    // Business Hours
    businessHours: {
        emergency: { start: 0, end: 24 }, // 24/7 for emergency
        regular: { start: 6, end: 22 }, // 6 AM to 10 PM
        support: { start: 8, end: 20 } // 8 AM to 8 PM
    },
    
    // Contact Information
    contact: {
        emergency: '+977-1-XXXXX',
        support: 'help@drivesafe.com.np',
        website: 'https://drivesafe.com.np',
        address: 'Kathmandu, Nepal'
    },
    
    // Legal and Compliance
    legal: {
        fuelDeliveryProhibited: true,
        minimumAge: 18,
        termsVersion: '1.0',
        privacyVersion: '1.0'
    },
    
    // Performance Settings
    performance: {
        locationUpdateInterval: 30000, // 30 seconds
        cacheTimeout: 300000, // 5 minutes
        offlineStorageLimit: 50 * 1024 * 1024 // 50MB
    },
    
    // Feature Flags
    features: {
        realTimeTracking: true,
        voiceRequests: false,
        multiLanguage: false,
        darkMode: true,
        pushNotifications: true,
        offlineMode: true,
        analytics: true
    }
};

// Pricing Configuration
const PRICING_CONFIG = {
    baseFees: {
        battery: 500,
        towing: 1500,
        ev: 800,
        repair: 1000,
        tire: 600,
        wash: 800,
        fuel: 300
    },
    
    distanceRates: {
        motorcycle: 15, // NPR per km
        car: 20,
        suv: 25,
        truck: 35,
        bus: 40,
        van: 22
    },
    
    timeMultipliers: {
        normal: 1.0, // 6 AM - 10 PM
        night: 1.5,  // 10 PM - 6 AM
        holiday: 1.3,
        emergency: 2.0
    },
    
    surcharges: {
        heavyTraffic: 200,
        difficultAccess: 300,
        weatherCondition: 150,
        mountainous: 250
    }
};

// Error Messages
const ERROR_MESSAGES = {
    network: 'Network error. Please check your internet connection.',
    location: 'Could not get your location. Please enable GPS.',
    permission: 'Permission denied. Please allow the required permissions.',
    invalidData: 'Invalid data provided. Please check your input.',
    serverError: 'Server error. Please try again later.',
    notFound: 'The requested resource was not found.',
    unauthorized: 'You are not authorized to perform this action.',
    sessionExpired: 'Your session has expired. Please login again.',
    serviceUnavailable: 'Service is currently unavailable in your area.',
    paymentFailed: 'Payment failed. Please try a different payment method.',
    agentNotFound: 'No agents available in your area at the moment.',
    invalidPhone: 'Please enter a valid Nepal phone number.',
    invalidEmail: 'Please enter a valid email address.',
    passwordMismatch: 'Passwords do not match.',
    weakPassword: 'Password must be at least 8 characters long.'
};

// Success Messages
const SUCCESS_MESSAGES = {
    loginSuccess: 'Successfully logged in!',
    registrationSuccess: 'Account created successfully!',
    serviceRequested: 'Service request submitted successfully!',
    paymentSuccess: 'Payment completed successfully!',
    profileUpdated: 'Profile updated successfully!',
    locationUpdated: 'Location updated successfully!',
    serviceCompleted: 'Service completed successfully!'
};

// Export constants for use in other modules
if (typeof window !== 'undefined') {
    window.APP_CONFIG = APP_CONFIG;
    window.PRICING_CONFIG = PRICING_CONFIG;
    window.ERROR_MESSAGES = ERROR_MESSAGES;
    window.SUCCESS_MESSAGES = SUCCESS_MESSAGES;
}

// Export for ES6 modules
export { APP_CONFIG, PRICING_CONFIG, ERROR_MESSAGES, SUCCESS_MESSAGES };