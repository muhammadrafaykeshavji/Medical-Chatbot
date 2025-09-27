<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('AI Symptom Checker') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Important Notice -->
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <span class="text-red-400">🚨</span>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Emergency Warning</h3>
                                <p class="text-sm text-red-700 mt-1">
                                    If you are experiencing a medical emergency, please call emergency services immediately. This tool is for informational purposes only and should not replace professional medical care.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Symptom Checker Form -->
                    <form id="symptom-form" class="space-y-6">
                        @csrf
                        
                        <!-- Symptoms Input -->
                        <div>
                            <label for="symptoms" class="block text-sm font-medium text-gray-700 mb-2">
                                What symptoms are you experiencing?
                            </label>
                            <div id="symptoms-container" class="space-y-2">
                                <div class="flex space-x-2">
                                    <input 
                                        type="text" 
                                        name="symptoms[]" 
                                        class="flex-1 border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500" 
                                        placeholder="e.g., headache, fever, nausea"
                                        required
                                    >
                                    <button type="button" onclick="removeSymptom(this)" class="px-3 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 hidden">
                                        ✕
                                    </button>
                                </div>
                            </div>
                            <button type="button" onclick="addSymptom()" class="mt-2 text-green-600 hover:text-green-800 text-sm">
                                + Add another symptom
                            </button>
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Additional Description (Optional)
                            </label>
                            <textarea 
                                id="description" 
                                name="description" 
                                rows="4" 
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500" 
                                placeholder="Please describe your symptoms in more detail, including when they started, severity, and any other relevant information..."
                            ></textarea>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <button 
                                type="submit" 
                                id="analyze-button"
                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg disabled:opacity-50"
                            >
                                🩺 Analyze Symptoms
                            </button>
                        </div>
                    </form>

                    <!-- Results Section (Hidden initially) -->
                    <div id="results-section" class="mt-8 hidden">
                        <div class="border-t pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Analysis Results</h3>
                            <div id="analysis-results"></div>
                        </div>
                    </div>

                    <!-- Disclaimer -->
                    <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <span class="text-yellow-400">⚠️</span>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">Medical Disclaimer</h3>
                                <p class="text-sm text-yellow-700 mt-1">
                                    This symptom checker provides general health information and should not replace professional medical advice, diagnosis, or treatment. Always consult with qualified healthcare providers for medical concerns. If you have serious symptoms or are unsure about your condition, seek immediate medical attention.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function addSymptom() {
            const container = document.getElementById('symptoms-container');
            const newSymptom = document.createElement('div');
            newSymptom.className = 'flex space-x-2';
            newSymptom.innerHTML = `
                <input 
                    type="text" 
                    name="symptoms[]" 
                    class="flex-1 border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500" 
                    placeholder="e.g., headache, fever, nausea"
                    required
                >
                <button type="button" onclick="removeSymptom(this)" class="px-3 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">
                    ✕
                </button>
            `;
            container.appendChild(newSymptom);
            
            // Show remove buttons for all symptoms if more than one
            const removeButtons = container.querySelectorAll('button');
            if (removeButtons.length > 1) {
                removeButtons.forEach(btn => btn.classList.remove('hidden'));
            }
        }

        function removeSymptom(button) {
            const container = document.getElementById('symptoms-container');
            button.parentElement.remove();
            
            // Hide remove buttons if only one symptom left
            const removeButtons = container.querySelectorAll('button');
            if (removeButtons.length === 1) {
                removeButtons[0].classList.add('hidden');
            }
        }

        // Form submission
        document.getElementById('symptom-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const analyzeButton = document.getElementById('analyze-button');
            const resultsSection = document.getElementById('results-section');
            
            // Get form data
            const formData = new FormData(this);
            const symptoms = Array.from(formData.getAll('symptoms[]')).filter(s => s.trim());
            const description = formData.get('description');

            if (symptoms.length === 0) {
                alert('Please enter at least one symptom.');
                return;
            }

            // Disable button and show loading
            analyzeButton.disabled = true;
            analyzeButton.innerHTML = '🔄 Analyzing...';
            
            try {
                const response = await fetch('{{ route("symptom-checker.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        symptoms: symptoms,
                        description: description
                    })
                });

                const data = await response.json();

                if (data.success) {
                    displayResults(data.symptom_check, data.analysis);
                    resultsSection.classList.remove('hidden');
                    resultsSection.scrollIntoView({ behavior: 'smooth' });
                } else {
                    alert('Error analyzing symptoms. Please try again.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error analyzing symptoms. Please try again.');
            } finally {
                // Re-enable button
                analyzeButton.disabled = false;
                analyzeButton.innerHTML = '🩺 Analyze Symptoms';
            }
        });

        function displayResults(symptomCheck, analysis) {
            const resultsContainer = document.getElementById('analysis-results');
            
            const urgencyColors = {
                'emergency': 'bg-red-100 text-red-800 border-red-200',
                'high': 'bg-orange-100 text-orange-800 border-orange-200',
                'medium': 'bg-yellow-100 text-yellow-800 border-yellow-200',
                'low': 'bg-green-100 text-green-800 border-green-200'
            };

            const urgencyColor = urgencyColors[analysis.urgency_level] || urgencyColors['low'];

            resultsContainer.innerHTML = `
                <div class="space-y-4">
                    <!-- Urgency Level -->
                    <div class="p-4 rounded-lg border ${urgencyColor}">
                        <h4 class="font-medium mb-2">Urgency Level: ${analysis.urgency_level.charAt(0).toUpperCase() + analysis.urgency_level.slice(1)}</h4>
                        <p class="text-sm">${analysis.recommendations}</p>
                    </div>

                    <!-- Symptoms Summary -->
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <h4 class="font-medium mb-2">Reported Symptoms:</h4>
                        <div class="flex flex-wrap gap-2">
                            ${symptomCheck.symptoms.map(symptom => 
                                `<span class="px-2 py-1 bg-white text-gray-700 text-sm rounded border">${symptom}</span>`
                            ).join('')}
                        </div>
                        ${symptomCheck.description ? `
                            <div class="mt-3">
                                <h5 class="font-medium text-sm mb-1">Description:</h5>
                                <p class="text-sm text-gray-600">${symptomCheck.description}</p>
                            </div>
                        ` : ''}
                    </div>

                    <!-- Analysis -->
                    ${analysis.analysis ? `
                        <div class="p-4 bg-blue-50 rounded-lg">
                            <h4 class="font-medium mb-2">General Information:</h4>
                            <p class="text-sm text-gray-700">${analysis.analysis.general_advice || 'Please consult with a healthcare professional for proper evaluation.'}</p>
                        </div>
                    ` : ''}

                    <!-- Doctor Recommendation -->
                    ${analysis.doctor_recommended ? `
                        <div class="p-4 bg-blue-100 border border-blue-200 rounded-lg">
                            <h4 class="font-medium text-blue-800 mb-2">👨‍⚕️ Medical Consultation Recommended</h4>
                            <p class="text-sm text-blue-700">Based on your symptoms, we recommend consulting with a healthcare professional for proper evaluation and treatment.</p>
                        </div>
                    ` : ''}

                    <!-- Action Buttons -->
                    <div class="flex space-x-4 pt-4">
                        <a href="{{ route('symptom-checker.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            View History
                        </a>
                        <a href="{{ route('chat.new') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            💬 Discuss with AI
                        </a>
                        <button onclick="window.print()" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            🖨️ Print Results
                        </button>
                    </div>
                </div>
            `;
        }
    </script>
    @endpush
</x-app-layout>
