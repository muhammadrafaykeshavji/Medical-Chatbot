@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-blue-900 to-slate-900">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-white mb-4">AI Symptom Checker</h1>
            <p class="text-slate-300 text-lg">Get AI-powered analysis of your symptoms and health concerns</p>
        </div>

        <!-- Main Content -->
        <div class="max-w-4xl mx-auto">
            <!-- Important Notice -->
            <div class="mb-8 p-6 bg-red-500/20 border border-red-500 rounded-xl">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-red-400 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-red-300">Emergency Warning</h3>
                        <p class="text-sm text-red-200 mt-1">
                            If you are experiencing a medical emergency, please call emergency services immediately. This tool is for informational purposes only and should not replace professional medical care.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Symptom Checker Form -->
            <div class="bg-slate-800/50 backdrop-blur-lg rounded-xl p-8 border border-slate-700">
                <form id="symptom-form" class="space-y-6">
                    @csrf
                    
                    <!-- Basic Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Age</label>
                            <input type="number" name="age" min="1" max="120" required
                                   class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:ring-2 focus:ring-pink-500 focus:border-transparent"
                                   placeholder="Enter your age">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Gender</label>
                            <select name="gender" required class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white focus:ring-2 focus:ring-pink-500 focus:border-transparent">
                                <option value="">Select gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>

                    <!-- Primary Symptoms -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Primary Symptoms</label>
                        <textarea name="primary_symptoms" rows="4" required
                                  class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:ring-2 focus:ring-pink-500 focus:border-transparent resize-none"
                                  placeholder="Describe your main symptoms in detail (e.g., headache, fever, cough, pain location, severity, duration)"></textarea>
                    </div>

                    <!-- Additional Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Duration</label>
                            <select name="duration" required class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white focus:ring-2 focus:ring-pink-500 focus:border-transparent">
                                <option value="">How long have you had these symptoms?</option>
                                <option value="less_than_day">Less than a day</option>
                                <option value="1_3_days">1-3 days</option>
                                <option value="4_7_days">4-7 days</option>
                                <option value="1_2_weeks">1-2 weeks</option>
                                <option value="more_than_2_weeks">More than 2 weeks</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Severity (1-10)</label>
                            <input type="range" name="severity" min="1" max="10" value="5" 
                                   class="w-full h-2 bg-slate-700 rounded-lg appearance-none cursor-pointer"
                                   oninput="document.getElementById('severity-value').textContent = this.value">
                            <div class="flex justify-between text-xs text-slate-400 mt-1">
                                <span>Mild (1)</span>
                                <span id="severity-value" class="text-pink-400 font-medium">5</span>
                                <span>Severe (10)</span>
                            </div>
                        </div>
                    </div>

                    <!-- Medical History -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Relevant Medical History</label>
                        <textarea name="medical_history" rows="3"
                                  class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:ring-2 focus:ring-pink-500 focus:border-transparent resize-none"
                                  placeholder="Any relevant medical conditions, medications, allergies, or recent changes (optional)"></textarea>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full bg-gradient-to-r from-pink-600 to-purple-600 text-white px-6 py-4 rounded-lg hover:from-pink-700 hover:to-purple-700 transition-all duration-200 font-medium text-lg">
                        <i class="fas fa-stethoscope mr-2"></i>Analyze Symptoms
                    </button>
                </form>
            </div>

            <!-- Results Section -->
            <div id="results-section" class="hidden mt-8">
                <div class="bg-slate-800/50 backdrop-blur-lg rounded-xl p-8 border border-slate-700">
                    <h3 class="text-2xl font-semibold text-white mb-6">Symptom Analysis Results</h3>
                    <div id="analysis-results"></div>
                </div>
            </div>

            <!-- Loading Section -->
            <div id="loading-section" class="hidden mt-8">
                <div class="bg-slate-800/50 backdrop-blur-lg rounded-xl p-8 border border-slate-700 text-center">
                    <div class="animate-spin w-12 h-12 border-4 border-pink-500 border-t-transparent rounded-full mx-auto mb-4"></div>
                    <p class="text-white font-medium">Analyzing your symptoms...</p>
                    <p class="text-slate-400 text-sm mt-2">This may take a few moments</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const symptomForm = document.getElementById('symptom-form');
    const loadingSection = document.getElementById('loading-section');
    const resultsSection = document.getElementById('results-section');
    
    symptomForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        // Convert primary_symptoms to symptoms array format expected by backend
        const primarySymptoms = formData.get('primary_symptoms');
        if (primarySymptoms) {
            // Split symptoms by common delimiters and clean up
            const symptomsArray = primarySymptoms
                .split(/[,;\n]+/)
                .map(s => s.trim())
                .filter(s => s.length > 0);
            
            // Remove the original field and add the array format
            formData.delete('primary_symptoms');
            symptomsArray.forEach((symptom, index) => {
                formData.append(`symptoms[${index}]`, symptom);
            });
        }
        
        // Add description field from medical_history if provided
        const medicalHistory = formData.get('medical_history');
        if (medicalHistory) {
            formData.append('description', medicalHistory);
        }
        
        // Show loading
        loadingSection.classList.remove('hidden');
        resultsSection.classList.add('hidden');
        
        // Submit form with proper error handling
        fetch('{{ route("symptom-checker.analyze") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                if (response.status === 422) {
                    return response.json().then(data => {
                        throw new Error('Validation error: ' + Object.values(data.errors).flat().join(', '));
                    });
                } else if (response.status === 500) {
                    throw new Error('Server error. Please try again later.');
                } else {
                    throw new Error('Network error. Please check your connection.');
                }
            }
            return response.json();
        })
        .then(data => {
            loadingSection.classList.add('hidden');
            
            if (data.success) {
                displayResults(data.analysis);
                resultsSection.classList.remove('hidden');
            } else {
                throw new Error(data.error || 'Analysis failed');
            }
        })
        .catch(error => {
            loadingSection.classList.add('hidden');
            console.error('Analysis error:', error);
            
            // Show user-friendly error message
            const errorDiv = document.createElement('div');
            errorDiv.className = 'bg-red-500/20 border border-red-500 rounded-lg p-4 mb-4';
            errorDiv.innerHTML = `
                <div class="flex items-center space-x-2">
                    <i class="fas fa-exclamation-triangle text-red-400"></i>
                    <span class="text-red-300 font-medium">Analysis Failed</span>
                </div>
                <p class="text-red-200 mt-2">${error.message}</p>
                <button onclick="this.parentElement.remove()" class="text-red-300 hover:text-red-200 mt-2 text-sm underline">
                    Dismiss
                </button>
            `;
            
            symptomForm.insertBefore(errorDiv, symptomForm.firstChild);
        });
    });
    
    function displayResults(analysis) {
        const resultsDiv = document.getElementById('analysis-results');
        
        // Handle the analysis structure returned by the backend
        const analysisData = analysis.analysis || analysis;
        const urgencyLevel = analysis.urgency_level || 'low';
        const recommendations = analysis.recommendations || 'Please consult with a healthcare professional.';
        
        resultsDiv.innerHTML = `
            <div class="space-y-6">
                <!-- Summary -->
                <div class="bg-slate-900 rounded-lg p-6 border border-slate-700">
                    <h4 class="text-lg font-semibold text-white mb-3">
                        <i class="fas fa-clipboard-list text-pink-400 mr-2"></i>Summary
                    </h4>
                    <p class="text-slate-300">${analysisData.summary || 'Symptom analysis completed.'}</p>
                </div>

                <!-- Possible Conditions -->
                <div class="bg-slate-900 rounded-lg p-6 border border-slate-700">
                    <h4 class="text-lg font-semibold text-white mb-3">
                        <i class="fas fa-search text-blue-400 mr-2"></i>Possible Conditions
                    </h4>
                    <ul class="space-y-2">
                        ${(analysisData.possible_conditions || []).map(condition => `
                            <li class="flex items-start space-x-2">
                                <i class="fas fa-check-circle text-green-400 mt-1 flex-shrink-0"></i>
                                <span class="text-slate-300">${condition}</span>
                            </li>
                        `).join('')}
                    </ul>
                </div>

                <!-- Immediate Care Advice -->
                ${analysisData.immediate_care ? `
                <div class="bg-slate-900 rounded-lg p-6 border border-slate-700">
                    <h4 class="text-lg font-semibold text-white mb-3">
                        <i class="fas fa-first-aid text-green-400 mr-2"></i>Immediate Care
                    </h4>
                    <ul class="space-y-2">
                        ${analysisData.immediate_care.map(care => `
                            <li class="flex items-start space-x-2">
                                <i class="fas fa-arrow-right text-green-400 mt-1 flex-shrink-0"></i>
                                <span class="text-slate-300">${care}</span>
                            </li>
                        `).join('')}
                    </ul>
                </div>
                ` : ''}

                <!-- General Advice -->
                ${analysisData.general_advice ? `
                <div class="bg-slate-900 rounded-lg p-6 border border-slate-700">
                    <h4 class="text-lg font-semibold text-white mb-3">
                        <i class="fas fa-lightbulb text-yellow-400 mr-2"></i>General Advice
                    </h4>
                    <p class="text-slate-300">${analysisData.general_advice}</p>
                </div>
                ` : ''}

                <!-- Warning Signs -->
                ${analysisData.warning_signs ? `
                <div class="bg-slate-900 rounded-lg p-6 border border-red-500">
                    <h4 class="text-lg font-semibold text-red-300 mb-3">
                        <i class="fas fa-exclamation-triangle text-red-400 mr-2"></i>Warning Signs - Seek Immediate Care
                    </h4>
                    <ul class="space-y-2">
                        ${analysisData.warning_signs.map(sign => `
                            <li class="flex items-start space-x-2">
                                <i class="fas fa-exclamation-triangle text-red-400 mt-1 flex-shrink-0"></i>
                                <span class="text-red-200">${sign}</span>
                            </li>
                        `).join('')}
                    </ul>
                </div>
                ` : ''}

                <!-- Urgency Level -->
                <div class="bg-slate-900 rounded-lg p-6 border border-slate-700">
                    <h4 class="text-lg font-semibold text-white mb-3">
                        <i class="fas fa-tachometer-alt text-orange-400 mr-2"></i>Urgency Level
                    </h4>
                    <div class="flex items-center space-x-2 mb-3">
                        <span class="px-4 py-2 rounded-full text-sm font-medium ${
                            urgencyLevel === 'emergency' ? 'bg-red-600/30 text-red-300 border border-red-500' :
                            urgencyLevel === 'high' ? 'bg-red-500/20 text-red-400 border border-red-500' : 
                            urgencyLevel === 'medium' ? 'bg-yellow-500/20 text-yellow-400 border border-yellow-500' : 
                            'bg-green-500/20 text-green-400 border border-green-500'
                        }">
                            ${urgencyLevel.charAt(0).toUpperCase() + urgencyLevel.slice(1)} Priority
                        </span>
                    </div>
                    <p class="text-slate-300">${recommendations}</p>
                </div>

                <!-- Medical Disclaimer -->
                <div class="bg-red-500/10 border border-red-500/30 rounded-lg p-4">
                    <div class="flex items-start space-x-2">
                        <i class="fas fa-exclamation-triangle text-red-400 mt-1 flex-shrink-0"></i>
                        <div>
                            <h5 class="text-red-300 font-medium">Important Medical Disclaimer</h5>
                            <p class="text-red-200 text-sm mt-1">
                                This analysis is for informational purposes only and should not replace professional medical advice, diagnosis, or treatment. Always consult with a qualified healthcare provider for proper medical evaluation.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }
});
</script>
@endsection
