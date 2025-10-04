<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DoctorController extends Controller
{
    private function haversineDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371; // km
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) * sin($dLat / 2)
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2))
            * sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return round($earthRadius * $c, 2);
    }
    public function index(Request $request)
    {
        $query = Doctor::query()->available()->pakistanOnly();
        
        // Filter by specialty if provided
        if ($request->filled('specialty')) {
            $query->where('specialty', 'like', '%' . $request->specialty . '%');
        }
        
        // Filter by disease/condition if provided
        if ($request->filled('disease')) {
            $query->byDisease($request->disease);
        }
        
        // Filter by city if provided
        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }
        
        // Location-based search
        if ($request->filled('latitude') && $request->filled('longitude')) {
            $radius = $request->get('radius', 50); // Default 50km radius
            $query->nearby($request->latitude, $request->longitude, $radius);
        }
        
        // Search by name if provided
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        $doctors = $query->orderBy('rating', 'desc')->paginate(12);
        $specialties = Doctor::distinct()->pluck('specialty');
        
        return view('doctors.index', compact('doctors', 'specialties'));
    }
    
    public function search(Request $request)
    {
        $doctors = Doctor::query()->available()->pakistanOnly()
            ->when($request->specialty, function ($query, $specialty) {
                return $query->where('specialty', 'like', '%' . $specialty . '%');
            })
            ->when($request->city, function ($query, $city) {
                return $query->where('city', 'like', '%' . $city . '%');
            })
            ->when($request->search, function ($query, $search) {
                return $query->where('name', 'like', '%' . $search . '%');
            })
            ->orderBy('rating', 'desc')
            ->get();
            
        return response()->json($doctors);
    }
    
    public function show(Doctor $doctor)
    {
        return view('doctors.show', compact('doctor'));
    }
    
    public function nearby(Request $request)
    {
        try {
            $request->validate([
                'latitude' => 'required|numeric|between:-90,90',
                'longitude' => 'required|numeric|between:-180,180',
                'radius' => 'nullable|numeric|min:1|max:100',
                'disease' => 'nullable|string|max:100',
                'specialty' => 'nullable|string|max:100',
                'city' => 'nullable|string|max:100',
            ]);

            $query = Doctor::query()->available()->pakistanOnly();
            
            // Filter by disease/condition if provided
            if ($request->filled('disease')) {
                $query->byDisease($request->disease);
            }
            
            // Filter by specialty if provided
            if ($request->filled('specialty')) {
                $query->bySpecialty($request->specialty);
            }

            // Find nearby doctors (still inside Pakistan due to scope)
            $radius = $request->get('radius', 100); // Default 100km radius
            $doctors = $query->nearby($request->latitude, $request->longitude, $radius)
                ->orderBy('rating', 'desc')
                ->get()
                ->map(function ($doctor) use ($request) {
                    $doctor->distance = $doctor->getDistanceFrom($request->latitude, $request->longitude);
                    return $doctor;
                })
                ->sortBy('distance');

            // If no doctors found with disease filter, try without it
            if ($doctors->count() === 0 && $request->filled('disease')) {
                $fallback = Doctor::query()->available()->pakistanOnly();
                if ($request->filled('specialty')) {
                    $fallback->bySpecialty($request->specialty);
                }
                $doctors = $fallback
                    ->nearby($request->latitude, $request->longitude, $radius)
                    ->orderBy('rating', 'desc')
                    ->get()
                    ->map(function ($doctor) use ($request) {
                        $doctor->distance = $doctor->getDistanceFrom($request->latitude, $request->longitude);
                        return $doctor;
                    })
                    ->sortBy('distance');
            }

            // If still none, fall back to city-only inside Pakistan with filters
            if ($doctors->count() === 0 && $request->filled('city')) {
                $cityFallback = Doctor::query()->available()->pakistanOnly();
                if ($request->filled('disease')) {
                    $cityFallback->byDisease($request->disease);
                }
                if ($request->filled('specialty')) {
                    $cityFallback->bySpecialty($request->specialty);
                }
                $doctors = $cityFallback->where('city', 'like', '%' . $request->city . '%')
                    ->orderBy('rating', 'desc')
                    ->get();
                // Assign approximate coordinates for display if missing
                $centers = collect(config('pakistan.city_centers'));
                $cityName = $request->city;
                $center = $centers->first(function ($coords, $name) use ($cityName) {
                    return stripos($cityName, $name) !== false;
                });
                if ($center) {
                    $doctors = $doctors->map(function ($doctor) use ($center) {
                        if (empty($doctor->latitude) || empty($doctor->longitude)) {
                            $doctor->latitude = $center['lat'];
                            $doctor->longitude = $center['lng'];
                        }
                        return $doctor;
                    });
                }
            }

            // Last resort: city-only, ignore disease/specialty to avoid empty state
            $usedRelaxedFilters = false;
            if ($doctors->count() === 0 && $request->filled('city')) {
                $relaxed = Doctor::query()->available()->pakistanOnly()
                    ->where('city', 'like', '%' . $request->city . '%')
                    ->orderBy('rating', 'desc')
                    ->get();
                if ($relaxed->count() > 0) {
                    $usedRelaxedFilters = true;
                    $centers = collect(config('pakistan.city_centers'));
                    $cityName = $request->city;
                    $center = $centers->first(function ($coords, $name) use ($cityName) {
                        return stripos($cityName, $name) !== false;
                    });
                    if ($center) {
                        $relaxed = $relaxed->map(function ($doctor) use ($center) {
                            if (empty($doctor->latitude) || empty($doctor->longitude)) {
                                $doctor->latitude = $center['lat'];
                                $doctor->longitude = $center['lng'];
                            }
                            return $doctor;
                        });
                    }
                    $doctors = $relaxed;
                }
            }

            // External fallback via RapidAPI Places Nearby
            $externalResults = collect();
            if ($doctors->count() === 0) {
                $rapidHost = env('RAPIDAPI_HOST');
                $rapidKey = env('RAPIDAPI_KEY');
                if (!empty($rapidHost) && !empty($rapidKey)) {
                    $keyword = null;
                    if ($request->filled('specialty')) {
                        $keyword = $request->specialty;
                    } elseif ($request->filled('disease')) {
                        $keyword = $request->disease;
                    }

                    $payload = [
                        'maxResultCount' => 20,
                        'rankPreference' => 'DISTANCE',
                        'locationRestriction' => [
                            'circle' => [
                                'center' => [
                                    'latitude' => (float) $request->latitude,
                                    'longitude' => (float) $request->longitude,
                                ],
                                'radius' => (float) ($request->get('radius', 10) * 1000),
                            ],
                        ],
                    ];
                    if (!empty($keyword)) {
                        $payload['includedTypes'] = ['doctor', 'hospital', 'clinic'];
                        $payload['textQuery'] = $keyword;
                    } else {
                        $payload['includedTypes'] = ['doctor'];
                    }

                    try {
                        $url = "https://{$rapidHost}/v1/places:searchNearby";
                        $response = Http::withHeaders([
                            'X-RapidAPI-Key' => $rapidKey,
                            'X-RapidAPI-Host' => $rapidHost,
                            'Content-Type' => 'application/json',
                        ])->post($url, $payload);

                        if ($response->successful()) {
                            $json = $response->json();
                            $places = collect($json['places'] ?? []);
                            $externalResults = $places->map(function ($p) use ($request) {
                                $lat = data_get($p, 'location.latitude');
                                $lng = data_get($p, 'location.longitude');
                                $distance = null;
                                if ($lat && $lng) {
                                    $distance = $this->haversineDistance((float)$request->latitude, (float)$request->longitude, (float)$lat, (float)$lng);
                                }
                                return [
                                    'id' => data_get($p, 'id') ?? md5(json_encode($p)),
                                    'name' => data_get($p, 'displayName.text') ?? data_get($p, 'name'),
                                    'specialty' => $request->specialty ?? '',
                                    'qualification' => '',
                                    'hospital_name' => '',
                                    'rating' => data_get($p, 'rating') ?? 0,
                                    'years_experience' => '',
                                    'city' => $request->city ?? '',
                                    'consultation_fee' => '',
                                    'latitude' => $lat,
                                    'longitude' => $lng,
                                    'distance' => $distance,
                                    'external' => true,
                                ];
                            })->filter(function ($r) {
                                return !empty($r['latitude']) && !empty($r['longitude']);
                            })->sortBy('distance');
                        } else {
                            \Log::warning('RapidAPI Places error: ' . $response->status() . ' ' . $response->body());
                        }
                    } catch (\Throwable $e) {
                        \Log::error('RapidAPI Places exception: ' . $e->getMessage());
                    }
                }
            }

            return response()->json([
                'success' => true,
                'doctors' => $doctors->values(), // Reset array keys
                'external_places' => $externalResults->values(),
                'count' => $doctors->count() + $externalResults->count(),
                'message' => ($doctors->count() + $externalResults->count()) > 0 ? ($usedRelaxedFilters ? 'Results found (filters relaxed to city)' : 'Results found') : 'No doctors found in this area'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Nearby doctors search error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error searching for nearby doctors',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
