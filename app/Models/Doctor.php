<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $fillable = [
        'name',
        'specialty',
        'qualification',
        'hospital_name',
        'bio',
        'phone',
        'email',
        'website',
        'address',
        'city',
        'state',
        'latitude',
        'longitude',
        'rating',
        'years_experience',
        'available_days',
        'available_from',
        'available_to',
        'consultation_fee',
        'services',
        'languages',
        'accepts_insurance',
        'insurance_accepted',
        'image_url',
        'is_available',
    ];

    protected $casts = [
        'available_days' => 'array',
        'rating' => 'decimal:2',
        'consultation_fee' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_available' => 'boolean',
        'accepts_insurance' => 'boolean',
        'insurance_accepted' => 'array',
        'available_from' => 'datetime:H:i',
        'available_to' => 'datetime:H:i',
    ];

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    public function scopePakistanOnly($query)
    {
        $bounds = config('pakistan.bounds');
        $cities = collect(config('pakistan.cities'))->filter()->all();

        // Prefer coordinates if present
        $query->where(function ($q) use ($bounds) {
            $q->whereBetween('latitude', [$bounds['min_lat'], $bounds['max_lat']])
              ->whereBetween('longitude', [$bounds['min_lng'], $bounds['max_lng']]);
        })
        // Or fallback to city whitelist
        ->orWhere(function ($q) use ($cities) {
            if (!empty($cities)) {
                $q->whereIn('city', $cities);
            }
        });

        return $query;
    }

    public function scopeBySpecialty($query, $specialty)
    {
        return $query->where('specialty', 'like', '%' . $specialty . '%');
    }

    public function scopeByCity($query, $city)
    {
        return $query->where('city', 'like', '%' . $city . '%');
    }

    // Helper methods
    public function getFullAddressAttribute()
    {
        return trim($this->address . ', ' . $this->city . ', ' . $this->state);
    }

    public function getAvailabilityStatusAttribute()
    {
        return $this->is_available ? 'Available' : 'Not Available';
    }

    public function getDistanceFrom($latitude, $longitude)
    {
        if (!$this->latitude || !$this->longitude) {
            return null;
        }

        $earthRadius = 6371; // Earth's radius in kilometers

        $latDiff = deg2rad($this->latitude - $latitude);
        $lonDiff = deg2rad($this->longitude - $longitude);

        $a = sin($latDiff / 2) * sin($latDiff / 2) +
             cos(deg2rad($latitude)) * cos(deg2rad($this->latitude)) *
             sin($lonDiff / 2) * sin($lonDiff / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c;

        return round($distance, 2);
    }

    public function scopeNearby($query, $latitude, $longitude, $radius = 50)
    {
        // Check if we're using SQLite (which doesn't have trigonometric functions)
        if (config('database.default') === 'sqlite') {
            // Simple bounding box calculation for SQLite
            $latDelta = $radius / 111; // Rough conversion: 1 degree ≈ 111 km
            $lngDelta = $radius / (111 * cos(deg2rad($latitude)));
            
            return $query->whereBetween('latitude', [$latitude - $latDelta, $latitude + $latDelta])
                        ->whereBetween('longitude', [$longitude - $lngDelta, $longitude + $lngDelta]);
        } else {
            // Use proper distance calculation for MySQL/PostgreSQL
            return $query->whereRaw("
                (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) <= ?
            ", [$latitude, $longitude, $latitude, $radius]);
        }
    }

    public function scopeByDisease($query, $disease)
    {
        $diseaseSpecialtyMap = [
            'heart' => ['Cardiology', 'Cardiovascular Surgery'],
            'diabetes' => ['Endocrinology', 'Internal Medicine'],
            'cancer' => ['Oncology', 'Hematology'],
            'skin' => ['Dermatology'],
            'eye' => ['Ophthalmology'],
            'infection' => ['Internal Medicine', 'Ophthalmology'],
            'bone' => ['Orthopedics', 'Rheumatology'],
            'brain' => ['Neurology', 'Neurosurgery'],
            'kidney' => ['Nephrology', 'Urology'],
            'lung' => ['Pulmonology', 'Respiratory Medicine'],
            'stomach' => ['Gastroenterology', 'Internal Medicine'],
            'pregnancy' => ['Obstetrics and Gynecology'],
            'child' => ['Pediatrics'],
            'mental' => ['Psychiatry', 'Psychology'],
            'ear' => ['ENT', 'Otolaryngology'],
            'throat' => ['ENT', 'Otolaryngology'],
            'fever' => ['Internal Medicine', 'Family Medicine'],
            'pain' => ['Internal Medicine', 'Pain Management'],
            'headache' => ['Neurology', 'Internal Medicine'],
            'back' => ['Orthopedics', 'Physical Medicine'],
            'joint' => ['Orthopedics', 'Rheumatology'],
            'blood' => ['Hematology', 'Internal Medicine'],
            'thyroid' => ['Endocrinology'],
            'hormone' => ['Endocrinology'],
        ];

        $specialties = [];
        $disease = strtolower($disease);
        
        foreach ($diseaseSpecialtyMap as $key => $specs) {
            if (stripos($disease, $key) !== false) {
                $specialties = array_merge($specialties, $specs);
            }
        }

        if (!empty($specialties)) {
            return $query->whereIn('specialty', $specialties);
        }

        // If no specific mapping found, search in specialty, services, or bio
        return $query->where(function($q) use ($disease) {
            $q->where('specialty', 'like', '%' . $disease . '%')
              ->orWhere('services', 'like', '%' . $disease . '%')
              ->orWhere('bio', 'like', '%' . $disease . '%');
        });
    }
}
