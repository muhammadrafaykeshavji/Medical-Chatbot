 @extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-blue-900 to-slate-900">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-white mb-4">Find Healthcare Professionals</h1>
            <p class="text-slate-300 text-lg">Connect with qualified doctors and specialists in your area</p>
        </div>

        <!-- Search Filters -->
        <div class="bg-slate-800/50 backdrop-blur-lg rounded-xl p-6 mb-8 border border-slate-700">
            <div class="mb-4 flex justify-center space-x-4">
                <button type="button" id="list-view-btn" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-list mr-2"></i>List View
                </button>
                <button type="button" id="map-view-btn" class="px-4 py-2 bg-slate-600 text-white rounded-lg hover:bg-slate-700 transition-colors">
                    <i class="fas fa-map mr-2"></i>Map View
                </button>
            </div>
            
            <form method="GET" action="{{ route('doctors.index') }}" id="search-form" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Search by Name</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Doctor's name..." 
                           class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Disease/Condition</label>
                    <input type="text" name="disease" value="{{ request('disease') }}" 
                           placeholder="e.g., heart, diabetes, cancer..." 
                           class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Specialty</label>
                    <select name="specialty" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent" onchange="onSpecialtyChange()">
                        <option value="">All Specialties</option>
                        @foreach($specialties as $specialty)
                            <option value="{{ $specialty }}" {{ request('specialty') == $specialty ? 'selected' : '' }}>
                                {{ $specialty }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">City or Address</label>
                    <div class="relative">
                        <input type="text" name="city" id="city-input" value="{{ request('city') }}" 
                               placeholder="Enter city or address..." 
                               class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <button type="button" id="geocode-btn" class="absolute right-2 top-2 text-slate-400 hover:text-white">
                            <i class="fas fa-map-marker-alt"></i>
                        </button>
                    </div>
                </div>
                
                <div class="flex items-end space-x-2">
                    <button type="submit" class="flex-1 bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-4 py-2 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 font-medium">
                        Search
                    </button>
                    <button type="button" id="location-search-btn" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors font-medium">
                        <i class="fas fa-map-marker-alt mr-2"></i>Near Me
                    </button>
                </div>
            </form>
        </div>

        <!-- Map View -->
        <div id="map-container" class="hidden mb-8">
            <div class="bg-slate-800/50 backdrop-blur-lg rounded-xl p-6 border border-slate-700">
                <div id="map" style="height: 500px; border-radius: 8px;"></div>
                <div id="map-results" class="mt-4 max-h-60 overflow-y-auto"></div>
            </div>
        </div>
        
        <!-- List View Results -->
        <div id="list-container">
        @if($doctors->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @foreach($doctors as $doctor)
                    <div class="bg-slate-800/50 backdrop-blur-lg rounded-xl p-6 border border-slate-700 hover:border-blue-500 transition-all duration-200">
                        <div class="flex items-start space-x-4">
                            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white text-xl font-bold">
                                {{ substr($doctor->name, 0, 1) }}
                            </div>
                            <div class="flex-1">
                                <h3 class="text-xl font-semibold text-white mb-1">Dr. {{ $doctor->name }}</h3>
                                <p class="text-blue-400 text-sm mb-2">{{ $doctor->specialty }}</p>
                                <p class="text-slate-400 text-sm mb-2">{{ $doctor->qualification }}</p>
                                <div class="flex items-center space-x-4 text-sm text-slate-300 mb-3">
                                    <span><i class="fas fa-star text-yellow-400 mr-1"></i>{{ $doctor->rating }}/5</span>
                                    <span><i class="fas fa-clock text-blue-400 mr-1"></i>{{ $doctor->years_experience }} years</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-300 text-sm">
                                        <i class="fas fa-map-marker-alt text-red-400 mr-1"></i>{{ $doctor->city }}
                                    </span>
                                    @if($doctor->consultation_fee)
                                        <span class="text-green-400 font-medium">${{ $doctor->consultation_fee }}</span>
                                    @endif
                                </div>
                                <a href="{{ route('doctors.show', $doctor) }}" 
                                   class="inline-block mt-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-4 py-2 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 text-sm font-medium">
                                    View Profile
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="flex justify-center">
                {{ $doctors->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <div class="w-24 h-24 bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-user-md text-3xl text-slate-400"></i>
                </div>
                <h3 class="text-xl font-semibold text-white mb-2">No doctors found</h3>
                <p class="text-slate-400">Try adjusting your search criteria</p>
            </div>
        @endif
        </div> <!-- End list-container -->
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
let map;
let markers = [];
let userLocation = null;

document.addEventListener('DOMContentLoaded', function() {
    const listViewBtn = document.getElementById('list-view-btn');
    const mapViewBtn = document.getElementById('map-view-btn');
    const listContainer = document.getElementById('list-container');
    const mapContainer = document.getElementById('map-container');
    const locationSearchBtn = document.getElementById('location-search-btn');
    const geocodeBtn = document.getElementById('geocode-btn');
    const cityInput = document.getElementById('city-input');

    // Geocoding functionality
    geocodeBtn.addEventListener('click', function() {
        const address = cityInput.value.trim();
        if (!address) {
            alert('Please enter a city or address');
            return;
        }
        
        geocodeAddress(address);
    });

    // View switching
    listViewBtn.addEventListener('click', function() {
        listContainer.classList.remove('hidden');
        mapContainer.classList.add('hidden');
        listViewBtn.classList.remove('bg-slate-600');
        listViewBtn.classList.add('bg-blue-600');
        mapViewBtn.classList.remove('bg-blue-600');
        mapViewBtn.classList.add('bg-slate-600');
    });

    mapViewBtn.addEventListener('click', function() {
        listContainer.classList.add('hidden');
        mapContainer.classList.remove('hidden');
        mapViewBtn.classList.remove('bg-slate-600');
        mapViewBtn.classList.add('bg-blue-600');
        listViewBtn.classList.remove('bg-blue-600');
        listViewBtn.classList.add('bg-slate-600');
        
        if (!map) {
            initMap();
        }
    });

    // Location search
    locationSearchBtn.addEventListener('click', function() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const latitude = position.coords.latitude;
                const longitude = position.coords.longitude;
                userLocation = { lat: latitude, lng: longitude };
                
                searchNearbyDoctors(latitude, longitude);
            }, function(error) {
                const city = cityInput.value.trim();
                if (city) {
                    geocodeAddress(city);
                } else {
                    alert('Unable to access your location. Enter your city and try again.');
                }
            });
        } else {
            alert('Geolocation is not supported by this browser.');
        }
    });
});

function initMap() {
    // Leaflet centered on Pakistan
    map = L.map('map').setView([30.3753, 69.3451], 6);
    // Use a clean basemap without POI icons to avoid hospital/clinic noise
    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_nolabels/{z}/{x}/{y}{r}.png', {
        attribution: '© OpenStreetMap contributors, © CARTO',
        maxZoom: 19
    }).addTo(map);
    // Optional: add labels layer
    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_only_labels/{z}/{x}/{y}{r}.png', {
        attribution: '',
        pane: 'shadowPane'
    }).addTo(map);
}

function geocodeAddress(address) {
    const geocodeBtn = document.getElementById('geocode-btn');
    const originalHtml = geocodeBtn.innerHTML;
    geocodeBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    geocodeBtn.disabled = true;

    const encodedAddress = encodeURIComponent(address);
    const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodedAddress}&limit=1&addressdetails=1&countrycodes=pk`;
    fetch(url)
        .then(response => response.json())
        .then(data => {
            geocodeBtn.innerHTML = originalHtml;
            geocodeBtn.disabled = false;
            if (data && data.length > 0) {
                const result = data[0];
                const latitude = parseFloat(result.lat);
                const longitude = parseFloat(result.lon);
                userLocation = { lat: latitude, lng: longitude };
                document.getElementById('map-view-btn').click();
                if (!map) { initMap(); }
                map.setView([latitude, longitude], 12);
                const searchIcon = L.divIcon({
                    html: '<div style="background-color: #10b981; width: 20px; height: 20px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3);"></div>',
                    iconSize: [26, 26],
                    iconAnchor: [13, 13],
                    className: 'custom-div-icon'
                });
                markers.forEach(marker => map.removeLayer(marker));
                markers = [];
                const searchMarker = L.marker([latitude, longitude], { icon: searchIcon })
                    .addTo(map)
                    .bindPopup(`<div class="text-center"><strong>${result.display_name}</strong></div>`)
                    .openPopup();
                markers.push(searchMarker);
                searchNearbyDoctors(latitude, longitude);
            } else {
                alert('Location not found. Please try a different address or city name.');
            }
        })
        .catch(error => {
            geocodeBtn.innerHTML = originalHtml;
            geocodeBtn.disabled = false;
            console.error('Geocoding error:', error);
            alert('Error finding location. Please try again.');
        });
}

function searchNearbyDoctors(latitude, longitude) {
    const disease = document.querySelector('input[name="disease"]').value;
    const specialty = document.querySelector('select[name="specialty"]').value;
    const city = document.querySelector('input[name="city"]').value || document.getElementById('city-input')?.value || '';
    
    fetch('{{ route("doctors.nearby") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            latitude: latitude,
            longitude: longitude,
            radius: 100, // Increased radius to 100km
            disease: disease,
            specialty: specialty,
            city: city
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (data.doctors.length > 0) {
                displayDoctorsOnMap(data.doctors, latitude, longitude);
                displayMapResults(data.doctors);
                renderListFromNearby(data.doctors);
            } else {
                alert(`No doctors found in your area${disease ? ' for "' + disease + '"' : ''}. Try expanding your search or removing filters.`);
            }
        } else {
            alert(data.message || 'Error finding nearby doctors');
            console.error('API Error:', data);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Network error. Please check your connection and try again.');
    });
}

function onSpecialtyChange() {
    const specialty = document.querySelector('select[name="specialty"]').value;
    if (!specialty) return;
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            const latitude = position.coords.latitude;
            const longitude = position.coords.longitude;
            userLocation = { lat: latitude, lng: longitude };
            searchNearbyDoctors(latitude, longitude);
        });
    }
}

function displayDoctorsOnMap(doctors, userLat, userLng) {
    if (!map) { initMap(); }
    // Leaflet markers only
    markers.forEach(marker => map.removeLayer(marker));
    markers = [];
    const userIcon = L.divIcon({
        html: '<div style="background-color: #ef4444; width: 20px; height: 20px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3);"></div>',
        iconSize: [26, 26],
        iconAnchor: [13, 13],
        className: 'custom-div-icon'
    });
    const doctorIcon = L.divIcon({
        html: '<div style="background-color: #3b82f6; width: 16px; height: 16px; border-radius: 50%; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3);"></div>',
        iconSize: [20, 20],
        iconAnchor: [10, 10],
        className: 'custom-div-icon'
    });
    const userMarker = L.marker([userLat, userLng], { icon: userIcon })
        .addTo(map)
        .bindPopup('<div class="text-center"><strong>Your Location</strong></div>');
    markers.push(userMarker);
    doctors.forEach(doctor => {
        if (doctor.latitude && doctor.longitude) {
            const marker = L.marker([parseFloat(doctor.latitude), parseFloat(doctor.longitude)], { icon: doctorIcon })
                .addTo(map)
                .bindPopup(`
                    <div class="p-2 min-w-[200px]">
                        <h3 class="font-bold text-lg">Dr. ${doctor.name}</h3>
                        <p class="text-sm text-blue-600 mb-1">${doctor.specialty}</p>
                        <p class="text-sm text-gray-600 mb-1">${doctor.hospital_name || ''}</p>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm">Rating: ${doctor.rating}/5 ⭐</span>
                            <span class="text-sm font-medium text-green-600">$${doctor.consultation_fee}</span>
                        </div>
                        <p class="text-sm text-gray-500">Distance: ${doctor.distance} km</p>
                        <a href="/doctors/${doctor.id}" class="inline-block mt-2 bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600">View Profile</a>
                    </div>
                `);
            markers.push(marker);
        }
    });
    if (markers.length > 0) {
        const group = new L.featureGroup(markers);
        map.fitBounds(group.getBounds().pad(0.1));
    }
}

function displayMapResults(doctors) {
    const resultsDiv = document.getElementById('map-results');
    
    if (doctors.length === 0) {
        resultsDiv.innerHTML = '<p class="text-slate-400 text-center">No doctors found in your area.</p>';
        return;
    }
    
    resultsDiv.innerHTML = `
        <h3 class="text-white font-semibold mb-3">Found ${doctors.length} doctors near you:</h3>
        <div class="space-y-2">
            ${doctors.map(doctor => `
                <div class="bg-slate-700 rounded-lg p-3 flex justify-between items-center">
                    <div>
                        <h4 class="text-white font-medium">Dr. ${doctor.name}</h4>
                        <p class="text-blue-400 text-sm">${doctor.specialty}</p>
                        <p class="text-slate-300 text-sm">${doctor.distance} km away • $${doctor.consultation_fee}</p>
                    </div>
                    <div class="text-right">
                        <div class="text-yellow-400 text-sm">⭐ ${doctor.rating}/5</div>
                        <a href="/doctors/${doctor.id}" class="text-blue-400 hover:text-blue-300 text-sm">View Profile</a>
                    </div>
                </div>
            `).join('')}
        </div>
    `;
}

// Replace the list view with nearby results so list matches the map filters
function renderListFromNearby(doctors) {
    const listContainer = document.getElementById('list-container');
    if (!listContainer) return;
    if (!doctors || doctors.length === 0) {
        listContainer.innerHTML = `
            <div class="text-center py-12">
                <div class="w-24 h-24 bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-user-md text-3xl text-slate-400"></i>
                </div>
                <h3 class="text-xl font-semibold text-white mb-2">No doctors found</h3>
                <p class="text-slate-400">Try adjusting your search criteria</p>
            </div>
        `;
        return;
    }
    const cards = doctors.map(doctor => `
        <div class="bg-slate-800/50 backdrop-blur-lg rounded-xl p-6 border border-slate-700 hover:border-blue-500 transition-all duration-200">
            <div class="flex items-start space-x-4">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white text-xl font-bold">
                    ${String(doctor.name || '?').substring(0,1)}
                </div>
                <div class="flex-1">
                    <h3 class="text-xl font-semibold text-white mb-1">Dr. ${doctor.name}</h3>
                    <p class="text-blue-400 text-sm mb-2">${doctor.specialty || ''}</p>
                    <p class="text-slate-400 text-sm mb-2">${doctor.qualification || ''}</p>
                    <div class="flex items-center space-x-4 text-sm text-slate-300 mb-3">
                        <span><i class="fas fa-star text-yellow-400 mr-1"></i>${doctor.rating || '0'}/5</span>
                        <span><i class="fas fa-clock text-blue-400 mr-1"></i>${doctor.years_experience || 0} years</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-300 text-sm">
                            <i class="fas fa-map-marker-alt text-red-400 mr-1"></i>${doctor.city || ''}
                        </span>
                        ${doctor.consultation_fee ? `<span class="text-green-400 font-medium">$${doctor.consultation_fee}</span>` : ''}
                    </div>
                    <a href="/doctors/${doctor.id}" 
                       class="inline-block mt-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-4 py-2 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 text-sm font-medium">
                        View Profile
                    </a>
                </div>
            </div>
        </div>
    `).join('');
    listContainer.innerHTML = `<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">${cards}</div>`;
}
</script>
@endpush
@endsection
