<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SymptomCheck extends Model
{
    protected $fillable = [
        'user_id',
        'symptoms',
        'description',
        'ai_analysis',
        'urgency_level',
        'recommendations',
        'doctor_recommended',
    ];

    protected $casts = [
        'symptoms' => 'array',
        'ai_analysis' => 'array',
        'doctor_recommended' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scope for high urgency checks
    public function scopeHighUrgency($query)
    {
        return $query->whereIn('urgency_level', ['high', 'emergency']);
    }

    // Scope for doctor recommended cases
    public function scopeDoctorRecommended($query)
    {
        return $query->where('doctor_recommended', true);
    }

    // Scope for recent checks
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
}
