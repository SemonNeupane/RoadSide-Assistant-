// map.js - Google Maps Integration for Roadside Assistance

class MapManager {
    constructor() {
        this.map = null;
        this.userMarker = null;
        this.agentMarkers = [];
        this.directionsService = null;
        this.directionsRenderer = null;
        this.userLocation = null;
        this.watchId = null;
        this.isMapLoaded = false;
        
        // Nepal center coordinates
        this.defaultCenter = { lat: 27.7172, lng: 85.3240 }; // Kathmandu
        this.mapOptions = {
            center: this.defaultCenter,
            zoom: 13,
            mapTypeId: 'roadmap',
            styles: this.getMapStyles(),
            zoomControl: true,
            mapTypeControl: false,
            scaleControl: true,
            streetViewControl: false,
            rotateControl: false,
            fullscreenControl: true
        };
        
        this.init();
    }

    async init() {
        try {
            // Load Google Maps API if not already loaded
            if (!window.google || !window.google.maps) {
                await this.loadGoogleMapsAPI();
            }
            
            // Get user's current location
            await this.getUserLocation();
            
        } catch (error) {
            console.error('Error initializing map:', error);
            Utils.showNotification('Failed to initialize maps', 'warning');
        }
    }

    async loadGoogleMapsAPI() {
        return new Promise((resolve, reject) => {
            if (window.google && window.google.maps) {
                resolve();
                return;
            }

            const script = document.createElement('script');
            script.src = `https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_API_KEY&libraries=geometry,places&callback=initGoogleMaps`;
            script.async = true;
            script.defer = true;
            
            window.initGoogleMaps = () => {
                this.isMapLoaded = true;
                resolve();
            };
            
            script.onerror = () => {
                reject(new Error('Failed to load Google Maps API'));
            };
            
            document.head.appendChild(script);
        });
    }

    async getUserLocation() {
        try {
            const position = await Utils.getCurrentLocation();
            this.userLocation = {
                lat: position.latitude,
                lng: position.longitude
            };
            
            // Update map center if map is already initialized
            if (this.map) {
                this.map.setCenter(this.userLocation);
                this.updateUserMarker();
            }
            
        } catch (error) {
            console.warn('Could not get user location:', error.message);
            // Use default location (Kathmandu)
            this.userLocation = this.defaultCenter;
        }
    }

    initializeMap(containerId) {
        if (!window.google || !window.google.maps) {
            console.error('Google Maps API not loaded');
            return;
        }

        const container = document.getElementById(containerId);
        if (!container) {
            console.error(`Map container '${containerId}' not found`);
            return;
        }

        // Set map center to user location or default
        this.mapOptions.center = this.userLocation || this.defaultCenter;
        
        // Create map
        this.map = new google.maps.Map(container, this.mapOptions);
        
        // Initialize directions service
        this.directionsService = new google.maps.DirectionsService();
        this.directionsRenderer = new google.maps.DirectionsRenderer({
            suppressMarkers: false,
            polylineOptions: {
                strokeColor: '#1e40af',
                strokeWeight: 4,
                strokeOpacity: 0.8
            }
        });
        this.directionsRenderer.setMap(this.map);
        
        // Add user marker
        this.updateUserMarker();
        
        // Setup map event listeners
        this.setupMapEventListeners();
        
        // Start watching user location
        this.startLocationWatch();
        
        return this.map;
    }

    updateUserMarker() {
        if (!this.map || !this.userLocation) return;

        if (this.userMarker) {
            this.userMarker.setPosition(this.userLocation);
        } else {
            this.userMarker = new google.maps.Marker({
                position: this.userLocation,
                map: this.map,
                title: 'Your Location',
                icon: {
                    url: 'data:image/svg+xml;base64,' + btoa(`
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12" cy="12" r="8" fill="#3b82f6" stroke="#ffffff" stroke-width="2"/>
                            <circle cx="12" cy="12" r="3" fill="#ffffff"/>
                        </svg>
                    `),
                    scaledSize: new google.maps.Size(24, 24),
                    anchor: new google.maps.Point(12, 12)
                }
            });
        }
    }

    addAgentMarker(agent) {
        if (!this.map) return;

        const marker = new google.maps.Marker({
            position: { lat: agent.latitude, lng: agent.longitude },
            map: this.map,
            title: `${agent.name} - ${agent.serviceType}`,
            icon: {
                url: this.getAgentIcon(agent.status),
                scaledSize: new google.maps.Size(32, 32),
                anchor: new google.maps.Point(16, 16)
            }
        });

        const infoWindow = new google.maps.InfoWindow({
            content: this.createAgentInfoWindow(agent)
        });

        marker.addListener('click', () => {
            // Close other info windows
            this.agentMarkers.forEach(m => m.infoWindow?.close());
            infoWindow.open(this.map, marker);
        });

        // Store marker with info window
        marker.infoWindow = infoWindow;
        marker.agentId = agent.id;
        
        this.agentMarkers.push(marker);
        return marker;
    }

    updateAgentMarker(agentId, position, status) {
        const marker = this.agentMarkers.find(m => m.agentId === agentId);
        if (marker) {
            marker.setPosition(position);
            if (status) {
                marker.setIcon({
                    url: this.getAgentIcon(status),
                    scaledSize: new google.maps.Size(32, 32),
                    anchor: new google.maps.Point(16, 16)
                });
            }
        }
    }

    removeAgentMarker(agentId) {
        const index = this.agentMarkers.findIndex(m => m.agentId === agentId);
        if (index !== -1) {
            this.agentMarkers[index].setMap(null);
            this.agentMarkers.splice(index, 1);
        }
    }

    clearAgentMarkers() {
        this.agentMarkers.forEach(marker => marker.setMap(null));
        this.agentMarkers = [];
    }

    getAgentIcon(status) {
        const colors = {
            'available': '#10b981', // green
            'busy': '#f59e0b', // amber
            'offline': '#6b7280', // gray
            'en_route': '#3b82f6' // blue
        };
        
        const color = colors[status] || colors.available;
        
        return 'data:image/svg+xml;base64,' + btoa(`
            <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="16" cy="16" r="12" fill="${color}" stroke="#ffffff" stroke-width="2"/>
                <path d="M16 8l-3 8h2v6h2v-6h2l-3-8z" fill="#ffffff"/>
            </svg>
        `);
    }

    createAgentInfoWindow(agent) {
        const distance = this.userLocation ? 
            Utils.calculateDistance(
                this.userLocation.lat, 
                this.userLocation.lng, 
                agent.latitude, 
                agent.longitude
            ).toFixed(1) : 'N/A';

        return `
            <div class="agent-info-window">
                <h3>${agent.name}</h3>
                <p><strong>Service:</strong> ${agent.serviceType}</p>
                <p><strong>Rating:</strong> ⭐ ${agent.rating}/5</p>
                <p><strong>Distance:</strong> ${distance} km</p>
                <p><strong>Status:</strong> <span class="status-${agent.status}">${agent.status}</span></p>
                ${agent.status === 'available' ? 
                    `<button onclick="requestAgent('${agent.id}')" class="btn-request-agent">Request Agent</button>` : 
                    ''
                }
            </div>
        `;
    }

    showDirections(destination, mode = 'DRIVING') {
        if (!this.directionsService || !this.userLocation) return;

        const request = {
            origin: this.userLocation,
            destination: destination,
            travelMode: google.maps.TravelMode[mode],
            unitSystem: google.maps.UnitSystem.METRIC,
            avoidHighways: false,
            avoidTolls: false
        };

        this.directionsService.route(request, (result, status) => {
            if (status === 'OK') {
                this.directionsRenderer.setDirections(result);
                
                // Extract route info
                const route = result.routes[0];
                const leg = route.legs[0];
                
                Utils.showNotification(
                    `Route: ${leg.distance.text}, ${leg.duration.text}`, 
                    'info'
                );
            } else {
                console.error('Directions request failed:', status);
                Utils.showNotification('Could not get directions', 'error');
            }
        });
    }

    clearDirections() {
        if (this.directionsRenderer) {
            this.directionsRenderer.setDirections({routes: []});
        }
    }

    startLocationWatch() {
        if (!navigator.geolocation) return;

        this.watchId = navigator.geolocation.watchPosition(
            (position) => {
                const newLocation = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };
                
                // Update user location and marker
                this.userLocation = newLocation;
                this.updateUserMarker();
                
                // Emit location update event
                document.dispatchEvent(new CustomEvent('locationUpdate', {
                    detail: newLocation
                }));
            },
            (error) => {
                console.warn('Location watch error:', error);
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 30000
            }
        );
    }

    stopLocationWatch() {
        if (this.watchId) {
            navigator.geolocation.clearWatch(this.watchId);
            this.watchId = null;
        }
    }

    setupMapEventListeners() {
        if (!this.map) return;

        // Map click event
        this.map.addListener('click', (event) => {
            document.dispatchEvent(new CustomEvent('mapClick', {
                detail: {
                    lat: event.latLng.lat(),
                    lng: event.latLng.lng()
                }
            }));
        });

        // Map bounds changed event
        this.map.addListener('bounds_changed', () => {
            document.dispatchEvent(new CustomEvent('mapBoundsChanged', {
                detail: this.map.getBounds()
            }));
        });
    }

    getMapStyles() {
        return [
            {
                featureType: 'water',
                elementType: 'geometry',
                stylers: [{ color: '#e9e9e9' }, { lightness: 17 }]
            },
            {
                featureType: 'landscape',
                elementType: 'geometry',
                stylers: [{ color: '#f5f5f5' }, { lightness: 20 }]
            },
            {
                featureType: 'road.highway',
                elementType: 'geometry.fill',
                stylers: [{ color: '#ffffff' }, { lightness: 17 }]
            },
            {
                featureType: 'road.highway',
                elementType: 'geometry.stroke',
                stylers: [{ color: '#ffffff' }, { lightness: 29 }, { weight: 0.2 }]
            },
            {
                featureType: 'road.arterial',
                elementType: 'geometry',
                stylers: [{ color: '#ffffff' }, { lightness: 18 }]
            },
            {
                featureType: 'road.local',
                elementType: 'geometry',
                stylers: [{ color: '#ffffff' }, { lightness: 16 }]
            },
            {
                featureType: 'poi',
                elementType: 'geometry',
                stylers: [{ color: '#f5f5f5' }, { lightness: 21 }]
            },
            {
                featureType: 'poi.park',
                elementType: 'geometry',
                stylers: [{ color: '#dedede' }, { lightness: 21 }]
            }
        ];
    }

    // Utility methods
    geocodeAddress(address) {
        return new Promise((resolve, reject) => {
            if (!window.google?.maps?.Geocoder) {
                reject(new Error('Geocoder not available'));
                return;
            }

            const geocoder = new google.maps.Geocoder();
            geocoder.geocode({ address }, (results, status) => {
                if (status === 'OK' && results[0]) {
                    resolve({
                        lat: results[0].geometry.location.lat(),
                        lng: results[0].geometry.location.lng(),
                        formatted_address: results[0].formatted_address
                    });
                } else {
                    reject(new Error(`Geocoding failed: ${status}`));
                }
            });
        });
    }

    reverseGeocode(lat, lng) {
        return new Promise((resolve, reject) => {
            if (!window.google?.maps?.Geocoder) {
                reject(new Error('Geocoder not available'));
                return;
            }

            const geocoder = new google.maps.Geocoder();
            const latlng = { lat, lng };
            
            geocoder.geocode({ location: latlng }, (results, status) => {
                if (status === 'OK' && results[0]) {
                    resolve(results[0].formatted_address);
                } else {
                    reject(new Error(`Reverse geocoding failed: ${status}`));
                }
            });
        });
    }

    fitBounds(locations) {
        if (!this.map || !locations || locations.length === 0) return;

        const bounds = new google.maps.LatLngBounds();
        locations.forEach(location => {
            bounds.extend(new google.maps.LatLng(location.lat, location.lng));
        });
        
        this.map.fitBounds(bounds);
    }

    setCenter(lat, lng, zoom = 15) {
        if (!this.map) return;
        
        this.map.setCenter({ lat, lng });
        this.map.setZoom(zoom);
    }

    // Places autocomplete
    initPlacesAutocomplete(inputElement) {
        if (!window.google?.maps?.places?.Autocomplete) {
            console.error('Places API not loaded');
            return;
        }

        const autocomplete = new google.maps.places.Autocomplete(inputElement, {
            types: ['establishment', 'geocode'],
            componentRestrictions: { country: 'np' } // Restrict to Nepal
        });

        autocomplete.addListener('place_changed', () => {
            const place = autocomplete.getPlace();
            if (place.geometry) {
                const location = {
                    lat: place.geometry.location.lat(),
                    lng: place.geometry.location.lng(),
                    address: place.formatted_address || place.name
                };
                
                document.dispatchEvent(new CustomEvent('placeSelected', {
                    detail: location
                }));
            }
        });

        return autocomplete;
    }

    // Cleanup
    destroy() {
        this.stopLocationWatch();
        this.clearAgentMarkers();
        if (this.userMarker) {
            this.userMarker.setMap(null);
        }
        if (this.map) {
            this.map = null;
        }
    }
}

// Global map manager instance
let mapManager = null;

// Initialize map manager when Google Maps API is ready
function initGoogleMaps() {
    mapManager = new MapManager();
    document.dispatchEvent(new CustomEvent('mapsReady'));
}

// Export for global use
window.MapManager = MapManager;
window.mapManager = mapManager;

// Global functions for use in HTML
window.requestAgent = function(agentId) {
    // This would typically trigger a service request
    console.log('Requesting agent:', agentId);
    Utils.showNotification('Agent request sent!', 'success');
};