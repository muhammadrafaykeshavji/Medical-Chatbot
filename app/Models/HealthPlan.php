<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HealthPlan extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'goals',
        'daily_activities',
        'weekly_activities',
        'dietary_recommendations',
        'exercise_plan',
        'health_targets',
        'start_date',
        'end_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'goals' => 'array',
        'daily_activities' => 'array',
        'weekly_activities' => 'array',
        'dietary_recommendations' => 'array',
        'exercise_plan' => 'array',
        'health_targets' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // Helper methods
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'active' => 'bg-green-100 text-green-800',
            'completed' => 'bg-blue-100 text-blue-800',
            'paused' => 'bg-yellow-100 text-yellow-800',
        ];

        return $badges[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    public function getDurationAttribute()
    {
        if (!$this->end_date) {
            return 'Ongoing';
        }

        $days = $this->start_date->diffInDays($this->end_date);
        return $days . ' days';
    }

    public function getProgressPercentageAttribute()
    {
        if (!$this->end_date || $this->status === 'completed') {
            return 100;
        }

        $totalDays = $this->start_date->diffInDays($this->end_date);
        $elapsedDays = $this->start_date->diffInDays(now());

        if ($elapsedDays >= $totalDays) {
            return 100;
        }

        return round(($elapsedDays / $totalDays) * 100);
    }
}
