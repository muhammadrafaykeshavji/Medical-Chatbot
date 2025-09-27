<?php

namespace App\Http\Controllers;

use App\Models\HealthMetric;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HealthMetricsController extends Controller
{
    public function index()
    {
        $healthMetrics = Auth::user()->healthMetrics()
            ->orderBy('recorded_at', 'desc')
            ->paginate(15);

        return view('health-metrics.index', compact('healthMetrics'));
    }

    public function create()
    {
        return view('health-metrics.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'metric_type' => 'required|in:blood_pressure,blood_sugar,weight,heart_rate,temperature',
            'systolic' => 'nullable|numeric|min:0|max:300',
            'diastolic' => 'nullable|numeric|min:0|max:200',
            'value' => 'required|numeric|min:0',
            'unit' => 'required|string|max:20',
            'notes' => 'nullable|string|max:500',
            'recorded_at' => 'required|date',
        ]);

        // Validate blood pressure specific fields
        if ($request->metric_type === 'blood_pressure') {
            $request->validate([
                'systolic' => 'required|numeric|min:50|max:300',
                'diastolic' => 'required|numeric|min:30|max:200',
            ]);
        }

        HealthMetric::create([
            'user_id' => Auth::id(),
            'metric_type' => $request->metric_type,
            'systolic' => $request->systolic,
            'diastolic' => $request->diastolic,
            'value' => $request->value,
            'unit' => $request->unit,
            'notes' => $request->notes,
            'recorded_at' => $request->recorded_at,
        ]);

        return redirect()->route('health-metrics.index')
            ->with('success', 'Health metric recorded successfully!');
    }

    public function show(HealthMetric $healthMetric)
    {
        // Ensure user owns this health metric
        if ($healthMetric->user_id !== Auth::id()) {
            abort(403);
        }

        return view('health-metrics.show', compact('healthMetric'));
    }

    public function edit(HealthMetric $healthMetric)
    {
        // Ensure user owns this health metric
        if ($healthMetric->user_id !== Auth::id()) {
            abort(403);
        }

        return view('health-metrics.edit', compact('healthMetric'));
    }

    public function update(Request $request, HealthMetric $healthMetric)
    {
        // Ensure user owns this health metric
        if ($healthMetric->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'metric_type' => 'required|in:blood_pressure,blood_sugar,weight,heart_rate,temperature',
            'systolic' => 'nullable|numeric|min:0|max:300',
            'diastolic' => 'nullable|numeric|min:0|max:200',
            'value' => 'required|numeric|min:0',
            'unit' => 'required|string|max:20',
            'notes' => 'nullable|string|max:500',
            'recorded_at' => 'required|date',
        ]);

        // Validate blood pressure specific fields
        if ($request->metric_type === 'blood_pressure') {
            $request->validate([
                'systolic' => 'required|numeric|min:50|max:300',
                'diastolic' => 'required|numeric|min:30|max:200',
            ]);
        }

        $healthMetric->update([
            'metric_type' => $request->metric_type,
            'systolic' => $request->systolic,
            'diastolic' => $request->diastolic,
            'value' => $request->value,
            'unit' => $request->unit,
            'notes' => $request->notes,
            'recorded_at' => $request->recorded_at,
        ]);

        return redirect()->route('health-metrics.index')
            ->with('success', 'Health metric updated successfully!');
    }

    public function destroy(HealthMetric $healthMetric)
    {
        // Ensure user owns this health metric
        if ($healthMetric->user_id !== Auth::id()) {
            abort(403);
        }

        $healthMetric->delete();

        return redirect()->route('health-metrics.index')
            ->with('success', 'Health metric deleted successfully!');
    }
}
