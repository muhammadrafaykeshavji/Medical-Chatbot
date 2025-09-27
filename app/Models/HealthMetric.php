<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HealthMetric extends Model
{
    protected $fillable = [
        'user_id',
        'metric_type',
        'systolic',
        'diastolic',
        'value',
        'unit',
        'notes',
        'recorded_at',
    ];

    protected $casts = [
        'recorded_at' => 'datetime',
        'systolic' => 'decimal:2',
        'diastolic' => 'decimal:2',
        'value' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scope for filtering by metric type
    public function scopeOfType($query, $type)
    {
        return $query->where('metric_type', $type);
    }

    // Scope for recent metrics
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('recorded_at', '>=', now()->subDays($days));
    }
}
