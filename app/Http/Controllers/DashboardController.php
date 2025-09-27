<?php

namespace App\Http\Controllers;

use App\Models\HealthMetric;
use App\Models\ChatConversation;
use App\Models\SymptomCheck;
use App\Models\Medication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get recent health metrics
        $recentMetrics = $user->healthMetrics()
            ->recent(7)
            ->orderBy('recorded_at', 'desc')
            ->limit(10)
            ->get();
        
        // Get active medications
        $activeMedications = $user->medications()
            ->active()
            ->limit(5)
            ->get();
        
        // Get recent conversations
        $recentConversations = $user->chatConversations()
            ->with('latestMessage')
            ->orderBy('last_message_at', 'desc')
            ->limit(5)
            ->get();
        
        // Get recent symptom checks
        $recentSymptomChecks = $user->symptomChecks()
            ->recent(30)
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();
        
        // Health metrics summary for charts
        $healthMetricsSummary = $this->getHealthMetricsSummary($user);
        
        return view('dashboard', compact(
            'recentMetrics',
            'activeMedications',
            'recentConversations',
            'recentSymptomChecks',
            'healthMetricsSummary'
        ));
    }
    
    private function getHealthMetricsSummary($user)
    {
        $summary = [];
        $metricTypes = ['blood_pressure', 'blood_sugar', 'weight', 'heart_rate'];
        
        foreach ($metricTypes as $type) {
            $metrics = $user->healthMetrics()
                ->ofType($type)
                ->recent(30)
                ->orderBy('recorded_at', 'desc')
                ->limit(10)
                ->get();
            
            if ($metrics->count() > 0) {
                $summary[$type] = [
                    'latest' => $metrics->first(),
                    'data' => $metrics->map(function ($metric) use ($type) {
                        return [
                            'date' => $metric->recorded_at->format('Y-m-d'),
                            'value' => $type === 'blood_pressure' 
                                ? $metric->systolic . '/' . $metric->diastolic
                                : $metric->value,
                            'unit' => $metric->unit
                        ];
                    })->reverse()->values()
                ];
            }
        }
        
        return $summary;
    }
}
