<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Health Metric') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('health-metrics.store') }}" id="health-metric-form">
                        @csrf

                        <!-- Metric Type -->
                        <div class="mb-6">
                            <label for="metric_type" class="block text-sm font-medium text-gray-700 mb-2">
                                Metric Type <span class="text-red-500">*</span>
                            </label>
                            <select id="metric_type" name="metric_type" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
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
                                    <label for="systolic" class="block text-sm font-medium text-gray-700 mb-2">
                                        Systolic <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" id="systolic" name="systolic" min="50" max="300" step="1" 
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                           placeholder="120">
                                    @error('systolic')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="diastolic" class="block text-sm font-medium text-gray-700 mb-2">
                                        Diastolic <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" id="diastolic" name="diastolic" min="30" max="200" step="1"
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                           placeholder="80">
                                    @error('diastolic')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- General Value Field -->
                        <div id="value-field" class="mb-6 hidden">
                            <label for="value" class="block text-sm font-medium text-gray-700 mb-2">
                                Value <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="value" name="value" min="0" step="0.01"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   placeholder="Enter value">
                            @error('value')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Unit Field -->
                        <div id="unit-field" class="mb-6 hidden">
                            <label for="unit" class="block text-sm font-medium text-gray-700 mb-2">
                                Unit <span class="text-red-500">*</span>
                            </label>
                            <select id="unit" name="unit" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Select unit</option>
                            </select>
                            @error('unit')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Recorded At -->
                        <div class="mb-6">
                            <label for="recorded_at" class="block text-sm font-medium text-gray-700 mb-2">
                                Recorded At <span class="text-red-500">*</span>
                            </label>
                            <input type="datetime-local" id="recorded_at" name="recorded_at" 
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   value="{{ now()->format('Y-m-d\TH:i') }}" required>
                            @error('recorded_at')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div class="mb-6">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                Notes (Optional)
                            </label>
                            <textarea id="notes" name="notes" rows="3"
                                      class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                      placeholder="Any additional notes about this measurement..."></textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('health-metrics.index') }}" 
                               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                📊 Save Metric
                            </button>
                        </div>
                    </form>

                    <!-- Quick Reference -->
                    <div class="mt-8 p-4 bg-gray-50 rounded-lg">
                        <h3 class="text-sm font-medium text-gray-900 mb-2">Quick Reference:</h3>
                        <div class="text-xs text-gray-600 space-y-1">
                            <p><strong>Blood Pressure:</strong> Normal: 120/80 mmHg, High: >140/90 mmHg</p>
                            <p><strong>Blood Sugar:</strong> Normal fasting: 70-100 mg/dL, After meals: <140 mg/dL</p>
                            <p><strong>Heart Rate:</strong> Normal resting: 60-100 bpm</p>
                            <p><strong>Temperature:</strong> Normal: 98.6°F (37°C)</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        const metricUnits = {
            'blood_pressure': ['mmHg'],
            'blood_sugar': ['mg/dL', 'mmol/L'],
            'weight': ['kg', 'lbs'],
            'heart_rate': ['bpm'],
            'temperature': ['°F', '°C']
        };

        const metricPlaceholders = {
            'blood_sugar': '100',
            'weight': '70',
            'heart_rate': '72',
            'temperature': '98.6'
        };

        document.getElementById('metric_type').addEventListener('change', function() {
            const selectedType = this.value;
            const bloodPressureFields = document.getElementById('blood-pressure-fields');
            const valueField = document.getElementById('value-field');
            const unitField = document.getElementById('unit-field');
            const unitSelect = document.getElementById('unit');
            const valueInput = document.getElementById('value');

            // Hide all fields first
            bloodPressureFields.classList.add('hidden');
            valueField.classList.add('hidden');
            unitField.classList.add('hidden');

            // Clear previous values
            unitSelect.innerHTML = '<option value="">Select unit</option>';
            valueInput.value = '';

            if (selectedType) {
                if (selectedType === 'blood_pressure') {
                    bloodPressureFields.classList.remove('hidden');
                    unitField.classList.remove('hidden');
                    // Set blood pressure unit
                    unitSelect.innerHTML = '<option value="mmHg" selected>mmHg</option>';
                    // Set a default value for blood pressure (will be calculated from systolic/diastolic)
                    valueInput.value = '120';
                } else {
                    valueField.classList.remove('hidden');
                    unitField.classList.remove('hidden');
                    
                    // Populate units for selected metric type
                    const units = metricUnits[selectedType] || [];
                    units.forEach(unit => {
                        const option = document.createElement('option');
                        option.value = unit;
                        option.textContent = unit;
                        if (units.length === 1) option.selected = true;
                        unitSelect.appendChild(option);
                    });

                    // Set placeholder
                    if (metricPlaceholders[selectedType]) {
                        valueInput.placeholder = metricPlaceholders[selectedType];
                    }
                }
            }
        });

        // For blood pressure, automatically calculate the value field (using systolic for simplicity)
        document.getElementById('systolic').addEventListener('input', function() {
            document.getElementById('value').value = this.value;
        });

        // Form validation
        document.getElementById('health-metric-form').addEventListener('submit', function(e) {
            const metricType = document.getElementById('metric_type').value;
            
            if (metricType === 'blood_pressure') {
                const systolic = document.getElementById('systolic').value;
                const diastolic = document.getElementById('diastolic').value;
                
                if (!systolic || !diastolic) {
                    e.preventDefault();
                    alert('Please enter both systolic and diastolic values for blood pressure.');
                    return;
                }
                
                // Set the value field to systolic for storage
                document.getElementById('value').value = systolic;
            } else {
                const value = document.getElementById('value').value;
                if (!value) {
                    e.preventDefault();
                    alert('Please enter a value for the selected metric.');
                    return;
                }
            }
        });
    </script>
    @endpush
</x-app-layout>
