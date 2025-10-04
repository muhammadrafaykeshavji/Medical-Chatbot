<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relationships
    public function healthMetrics(): HasMany
    {
        return $this->hasMany(HealthMetric::class);
    }

    public function chatConversations(): HasMany
    {
        return $this->hasMany(ChatConversation::class);
    }

    public function symptomChecks(): HasMany
    {
        return $this->hasMany(SymptomCheck::class);
    }

    public function medications(): HasMany
    {
        return $this->hasMany(Medication::class);
    }

    public function healthPlans(): HasMany
    {
        return $this->hasMany(HealthPlan::class);
    }

    // Helper methods
    public function getActiveMedications()
    {
        return $this->medications()->active()->get();
    }

    public function getRecentHealthMetrics($type = null, $days = 30)
    {
        $query = $this->healthMetrics()->recent($days);
        
        if ($type) {
            $query->ofType($type);
        }
        
        return $query->orderBy('recorded_at', 'desc')->get();
    }
}
