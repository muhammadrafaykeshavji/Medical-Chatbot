@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-blue-900 to-slate-900">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-4xl font-bold text-white mb-2">Add Health Metric</h1>
                <p class="text-slate-300 text-lg">Record your health measurements and track your progress</p>
            </div>
            <a href="{{ route('health-metrics.index') }}" 
               class="bg-slate-700 text-white px-6 py-3 rounded-lg hover:bg-slate-600 transition-all duration-200 font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Back to Metrics
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-500/20 border border-green-500 text-green-300 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <!-- Form -->
        <div class="max-w-2xl mx-auto">
            <div class="bg-slate-800/50 backdrop-blur-lg rounded-xl p-8 border border-slate-700">
                <form method="POST" action="{{ route('health-metrics.store') }}" id="health-metric-form">
                    @csrf

                    <!-- Metric Type -->
                    <div class="mb-6">
                        <label for="metric_type" class="block text-sm font-medium text-slate-300 mb-2">
                            Metric Type <span class="text-red-400">*</span>
                        </label>
                        <select id="metric_type" name="metric_type" class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent" required>
                            <option value="">Select a metric type</option>
                            <option value="blood_pressure">Blood Pressure</option>
                            <option value="blood_sugar">Blood Sugar</option>
                            <option value="weight">Weight</option>
                            <option value="heart_rate">Heart Rate</option>
                            <option value="temperature">Temperature</option>
                        </select>
                        @error('metric_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Blood Pressure Fields (Hidden by default) -->
                    <div id="blood-pressure-fields" class="mb-6 hidden">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="systolic" class="block text-sm font-medium text-slate-300 mb-2">
                                    Systolic <span class="text-red-400">*</span>
                                </label>
                                <input type="number" id="systolic" name="systolic" min="50" max="300" step="1" 
                                       class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
                                       placeholder="120">
                                @error('systolic')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="diastolic" class="block text-sm font-medium text-slate-300 mb-2">
                                    Diastolic <span class="text-red-400">*</span>
                                </label>
                                <input type="number" id="diastolic" name="diastolic" min="30" max="200" step="1"
                                       class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
                                       placeholder="80">
                                @error('diastolic')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- General Value Field -->
                    <div id="value-field" class="mb-6 hidden">
                        <label for="value" class="block text-sm font-medium text-slate-300 mb-2">
                            Value <span class="text-red-400">*</span>
                        </label>
                        <input type="number" id="value" name="value" min="0" step="0.01"
                               class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
                               placeholder="Enter value">
                        @error('value')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Unit Field -->
                    <div id="unit-field" class="mb-6 hidden">
                        <label for="unit" class="block text-sm font-medium text-slate-300 mb-2">
                            Unit <span class="text-red-400">*</span>
                        </label>
                        <select id="unit" name="unit" class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                            <option value="">Select unit</option>
                        </select>
                        @error('unit')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Recorded At -->
                    <div class="mb-6">
                        <label for="recorded_at" class="block text-sm font-medium text-slate-300 mb-2">
                            Recorded At <span class="text-red-400">*</span>
                        </label>
                        <input type="datetime-local" id="recorded_at" name="recorded_at" 
                               class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
                               value="{{ now()->format('Y-m-d\TH:i') }}" required>
                        @error('recorded_at')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div class="mb-6">
                        <label for="notes" class="block text-sm font-medium text-slate-300 mb-2">
                            Notes (Optional)
                        </label>
                        <textarea id="notes" name="notes" rows="3"
                                  class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
                                  placeholder="Any additional notes about this measurement..."></textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('health-metrics.index') }}" 
                           class="bg-slate-600 hover:bg-slate-700 text-white px-6 py-3 rounded-lg transition-all duration-200 font-medium">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="bg-gradient-to-r from-cyan-600 to-blue-600 text-white px-6 py-3 rounded-lg hover:from-cyan-700 hover:to-blue-700 transition-all duration-200 font-medium">
                            <i class="fas fa-save mr-2"></i>Save Metric
                        </button>
                    </div>
                </form>

                <!-- Quick Reference -->
                <div class="mt-8 p-6 bg-slate-700/50 rounded-lg border border-slate-600">
                    <h3 class="text-lg font-semibold text-white mb-4"><i class="fas fa-info-circle text-cyan-400 mr-2"></i>Quick Reference</h3>
                    <div class="text-sm text-slate-300 space-y-2">
                        <p><strong class="text-white">Blood Pressure:</strong> Normal: 120/80 mmHg, High: >140/90 mmHg</p>
                        <p><strong class="text-white">Blood Sugar:</strong> Normal fasting: 70-100 mg/dL, After meals: <140 mg/dL</p>
                        <p><strong class="text-white">Heart Rate:</strong> Normal resting: 60-100 bpm</p>
                        <p><strong class="text-white">Temperature:</strong> Normal: 98.6°F (37°C)</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const metricTypeSelect = document.getElementById('metric_type');
    const bloodPressureFields = document.getElementById('blood-pressure-fields');
    const valueField = document.getElementById('value-field');
    const unitField = document.getElementById('unit-field');
    const unitSelect = document.getElementById('unit');
    const valueInput = document.getElementById('value');

    // Unit options for different metric types
    const unitOptions = {
        blood_sugar: [
            { value: 'mg/dL', label: 'mg/dL' },
            { value: 'mmol/L', label: 'mmol/L' }
        ],
        weight: [
            { value: 'kg', label: 'kg' },
            { value: 'lbs', label: 'lbs' }
        ],
        heart_rate: [
            { value: 'bpm', label: 'bpm' }
        ],
        temperature: [
            { value: '°F', label: '°F' },
            { value: '°C', label: '°C' }
        ]
    };

    metricTypeSelect.addEventListener('change', function() {
        const selectedType = this.value;
        
        // Hide all conditional fields first
        bloodPressureFields.classList.add('hidden');
        valueField.classList.add('hidden');
        unitField.classList.add('hidden');
        
        // Clear unit options
        unitSelect.innerHTML = '<option value="">Select unit</option>';
        
        if (selectedType === 'blood_pressure') {
            bloodPressureFields.classList.remove('hidden');
            unitField.classList.remove('hidden');
            // For blood pressure, set the unit automatically
            unitSelect.innerHTML = '<option value="mmHg" selected>mmHg</option>';
            valueInput.value = '1'; // Set a dummy value for validation
        } else if (selectedType && unitOptions[selectedType]) {
            valueField.classList.remove('hidden');
            unitField.classList.remove('hidden');
            
            // Populate unit options
            unitOptions[selectedType].forEach(option => {
                const optionElement = document.createElement('option');
                optionElement.value = option.value;
                optionElement.textContent = option.label;
                unitSelect.appendChild(optionElement);
            });
            
            // Auto-select the first unit if there's only one option
            if (unitOptions[selectedType].length === 1) {
                unitSelect.value = unitOptions[selectedType][0].value;
            }
        }
    });
    
    // Form validation
    document.getElementById('health-metric-form').addEventListener('submit', function(e) {
        const metricType = metricTypeSelect.value;
        
        if (metricType === 'blood_pressure') {
            const systolic = document.getElementById('systolic').value;
            const diastolic = document.getElementById('diastolic').value;
            
            if (!systolic || !diastolic) {
                e.preventDefault();
                alert('Please enter both systolic and diastolic values for blood pressure.');
                return;
            }
            
            // Set the value field to systolic for storage
            valueInput.value = systolic;
        } else {
            const value = valueInput.value;
            const unit = unitSelect.value;
            
            if (!value || !unit) {
                e.preventDefault();
                alert('Please enter both value and unit.');
                return;
            }
        }
    });
});
</script>
@endsection
