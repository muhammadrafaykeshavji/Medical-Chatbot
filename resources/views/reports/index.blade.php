@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-blue-900 to-slate-900">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-white mb-4">Medical Report Analyzer</h1>
            <p class="text-slate-300 text-lg">Upload and analyze your medical reports with AI-powered insights</p>
        </div>

        <!-- Upload Section -->
        <div class="max-w-4xl mx-auto">
            <div class="bg-slate-800/50 backdrop-blur-lg rounded-xl p-8 border border-slate-700 mb-8">
                <div class="text-center mb-6">
                    <div class="w-20 h-20 bg-orange-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-file-medical text-orange-400 text-3xl"></i>
                    </div>
                    <h2 class="text-2xl font-semibold text-white mb-2">Upload Your Medical Report</h2>
                    <p class="text-slate-400">Supported formats: PDF, DOC, DOCX, TXT, Images (Max 10MB)</p>
                </div>

                <form id="reportForm" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <!-- Upload Options -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <!-- File Upload -->
                        <div class="border-2 border-dashed border-slate-600 rounded-lg p-6 text-center hover:border-orange-500 transition-colors">
                            <input type="file" id="report_file" name="report_file" accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png,.gif" class="hidden" required>
                            <label for="report_file" class="cursor-pointer">
                                <i class="fas fa-cloud-upload-alt text-3xl text-slate-400 mb-3 block"></i>
                                <p class="text-white font-medium mb-2">Upload File</p>
                                <p class="text-slate-400 text-sm">PDF, DOC, Images up to 10MB</p>
                            </label>
                        </div>

                        <!-- Camera Capture -->
                        <div class="border-2 border-dashed border-slate-600 rounded-lg p-6 text-center hover:border-blue-500 transition-colors">
                            <button type="button" id="cameraBtn" class="w-full">
                                <i class="fas fa-camera text-3xl text-slate-400 mb-3 block"></i>
                                <p class="text-white font-medium mb-2">Take Photo</p>
                                <p class="text-slate-400 text-sm">Capture report with camera</p>
                            </button>
                        </div>
                    </div>

                    <!-- Camera Modal -->
                    <div id="cameraModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden flex items-center justify-center">
                        <div class="bg-slate-800 rounded-xl p-6 max-w-2xl w-full mx-4">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-xl font-semibold text-white">Capture Medical Report</h3>
                                <button id="closeCameraBtn" class="text-slate-400 hover:text-white">
                                    <i class="fas fa-times text-xl"></i>
                                </button>
                            </div>
                            
                            <div class="relative">
                                <video id="cameraVideo" class="w-full rounded-lg bg-black" autoplay playsinline></video>
                                <canvas id="cameraCanvas" class="hidden"></canvas>
                            </div>
                            
                            <div class="flex justify-center space-x-4 mt-4">
                                <button id="captureBtn" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-camera mr-2"></i>Capture
                                </button>
                                <button id="retakeBtn" class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition-colors hidden">
                                    <i class="fas fa-redo mr-2"></i>Retake
                                </button>
                                <button id="useCaptureBtn" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition-colors hidden">
                                    <i class="fas fa-check mr-2"></i>Use Photo
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Preview Area -->
                    <div id="previewArea" class="hidden mb-6">
                        <div class="bg-slate-700 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-white font-medium">Selected File:</span>
                                <button id="removeFile" class="text-red-400 hover:text-red-300">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            <div id="fileName" class="text-green-400 font-medium"></div>
                            <div id="imagePreview" class="mt-3 hidden">
                                <img id="previewImg" class="max-w-full h-48 object-contain rounded-lg" alt="Preview">
                            </div>
                        </div>
                    </div>

                    <!-- Report Type -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Report Type</label>
                        <select name="report_type" required class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                            <option value="">Select report type...</option>
                            <option value="blood_test">Blood Test Report</option>
                            <option value="xray">X-Ray Image</option>
                            <option value="mri">MRI Scan</option>
                            <option value="ct_scan">CT Scan</option>
                            <option value="image_report">Medical Image/Photo</option>
                            <option value="general">General Medical Report</option>
                        </select>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full bg-gradient-to-r from-orange-600 to-red-600 text-white px-6 py-3 rounded-lg hover:from-orange-700 hover:to-red-700 transition-all duration-200 font-medium text-lg">
                        <i class="fas fa-brain mr-2"></i>Analyze Report
                    </button>
                </form>
            </div>

            <!-- Results Section -->
            <div id="resultsSection" class="hidden">
                <div class="bg-slate-800/50 backdrop-blur-lg rounded-xl p-8 border border-slate-700">
                    <h3 class="text-2xl font-semibold text-white mb-6">Analysis Results</h3>
                    <div id="analysisResults"></div>
                </div>
            </div>

            <!-- Loading Section -->
            <div id="loadingSection" class="hidden">
                <div class="bg-slate-800/50 backdrop-blur-lg rounded-xl p-8 border border-slate-700 text-center">
                    <div class="animate-spin w-12 h-12 border-4 border-orange-500 border-t-transparent rounded-full mx-auto mb-4"></div>
                    <p class="text-white font-medium">Analyzing your report...</p>
                    <p class="text-slate-400 text-sm mt-2">This may take a few moments</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Function to format AI analysis text into proper HTML
function formatAIAnalysis(text) {
    if (!text) return '';
    
    let formatted = text;
    
    // Remove all ** formatting completely and convert to headings
    formatted = formatted.replace(/\*\*([^*]+):\*\*/g, '<h5 class="text-lg font-semibold text-white mt-6 mb-3 first:mt-0"><i class="fas fa-chevron-right text-blue-400 mr-2"></i>$1</h5>');
    formatted = formatted.replace(/\*\*([^*]+)\*\*/g, '<strong class="text-white">$1</strong>');
    
    // Remove any remaining asterisks
    formatted = formatted.replace(/\*/g, '');
    
    // Convert bullet points and dashes to proper HTML
    formatted = formatted.replace(/^[\-•]\s*(.+)$/gm, '<div class="flex items-start space-x-2 mb-2"><i class="fas fa-circle text-blue-400 mt-2 text-xs flex-shrink-0"></i><span>$1</span></div>');
    
    // Convert numbered lists
    formatted = formatted.replace(/^\d+\.\s*(.+)$/gm, '<div class="flex items-start space-x-2 mb-2"><i class="fas fa-arrow-right text-green-400 mt-1 flex-shrink-0"></i><span>$1</span></div>');
    
    // Remove any remaining standalone dashes at the beginning of lines
    formatted = formatted.replace(/^-\s*/gm, '');
    
    // Convert double line breaks to spacing
    formatted = formatted.replace(/\n\n+/g, '<div class="mb-4"></div>');
    formatted = formatted.replace(/\n/g, '<br>');
    
    // Clean up any remaining formatting characters
    formatted = formatted.replace(/[\*\-]{2,}/g, '');
    
    // Split by spacing divs and wrap paragraphs
    const sections = formatted.split('<div class="mb-4"></div>');
    const processedSections = sections.map(section => {
        section = section.trim();
        if (section && !section.startsWith('<') && !section.includes('class=')) {
            // This is plain text, wrap it in a paragraph
            return `<p class="text-slate-300 mb-3">${section}</p>`;
        }
        return section;
    });
    
    return processedSections.join('<div class="mb-4"></div>');
}

document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('report_file');
    const fileName = document.getElementById('fileName');
    const form = document.getElementById('reportForm');
    const loadingSection = document.getElementById('loadingSection');
    const resultsSection = document.getElementById('resultsSection');
    const previewArea = document.getElementById('previewArea');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    const removeFileBtn = document.getElementById('removeFile');
    
    // Camera elements
    const cameraBtn = document.getElementById('cameraBtn');
    const cameraModal = document.getElementById('cameraModal');
    const closeCameraBtn = document.getElementById('closeCameraBtn');
    const cameraVideo = document.getElementById('cameraVideo');
    const cameraCanvas = document.getElementById('cameraCanvas');
    const captureBtn = document.getElementById('captureBtn');
    const retakeBtn = document.getElementById('retakeBtn');
    const useCaptureBtn = document.getElementById('useCaptureBtn');
    
    let stream = null;
    let capturedFile = null;

    // File input change handler
    fileInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            handleFileSelection(this.files[0]);
        }
    });

    // Camera button handler
    cameraBtn.addEventListener('click', function() {
        openCamera();
    });

    // Close camera modal
    closeCameraBtn.addEventListener('click', function() {
        closeCamera();
    });

    // Capture photo
    captureBtn.addEventListener('click', function() {
        capturePhoto();
    });

    // Retake photo
    retakeBtn.addEventListener('click', function() {
        retakePhoto();
    });

    // Use captured photo
    useCaptureBtn.addEventListener('click', function() {
        useCapturedPhoto();
    });

    // Remove file
    removeFileBtn.addEventListener('click', function() {
        clearFile();
    });

    function handleFileSelection(file) {
        fileName.textContent = file.name;
        previewArea.classList.remove('hidden');
        
        // Show image preview for image files
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                imagePreview.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        } else {
            imagePreview.classList.add('hidden');
        }
    }

    function clearFile() {
        fileInput.value = '';
        capturedFile = null;
        previewArea.classList.add('hidden');
        imagePreview.classList.add('hidden');
    }

    async function openCamera() {
        try {
            stream = await navigator.mediaDevices.getUserMedia({ 
                video: { 
                    facingMode: 'environment', // Use back camera if available
                    width: { ideal: 1280 },
                    height: { ideal: 720 }
                } 
            });
            cameraVideo.srcObject = stream;
            cameraModal.classList.remove('hidden');
            captureBtn.classList.remove('hidden');
            retakeBtn.classList.add('hidden');
            useCaptureBtn.classList.add('hidden');
        } catch (error) {
            alert('Camera access denied or not available. Please upload a file instead.');
        }
    }

    function closeCamera() {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
            stream = null;
        }
        cameraModal.classList.add('hidden');
    }

    function capturePhoto() {
        const canvas = cameraCanvas;
        const video = cameraVideo;
        
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        
        const ctx = canvas.getContext('2d');
        ctx.drawImage(video, 0, 0);
        
        // Hide video, show canvas
        video.classList.add('hidden');
        canvas.classList.remove('hidden');
        
        // Update buttons
        captureBtn.classList.add('hidden');
        retakeBtn.classList.remove('hidden');
        useCaptureBtn.classList.remove('hidden');
    }

    function retakePhoto() {
        cameraVideo.classList.remove('hidden');
        cameraCanvas.classList.add('hidden');
        
        captureBtn.classList.remove('hidden');
        retakeBtn.classList.add('hidden');
        useCaptureBtn.classList.add('hidden');
    }

    function useCapturedPhoto() {
        const canvas = cameraCanvas;
        
        canvas.toBlob(function(blob) {
            const file = new File([blob], 'captured_report.jpg', { type: 'image/jpeg' });
            
            // Create a new FileList-like object
            const dt = new DataTransfer();
            dt.items.add(file);
            fileInput.files = dt.files;
            
            capturedFile = file;
            handleFileSelection(file);
            closeCamera();
        }, 'image/jpeg', 0.8);
    }

    // Form submit handler with enhanced error handling
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        // Show loading
        loadingSection.classList.remove('hidden');
        resultsSection.classList.add('hidden');
        
        // Submit form with proper error handling
        fetch('{{ route("reports.analyze") }}', {
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
            
            form.insertBefore(errorDiv, form.firstChild);
        });
    });

    function displayResults(analysis) {
        const resultsDiv = document.getElementById('analysisResults');

        // Ensure analysis object has all required properties with fallbacks
        const safeAnalysis = {
            summary: analysis.summary || 'Analysis completed successfully',
            key_findings: Array.isArray(analysis.key_findings) ? analysis.key_findings : [],
            normal_findings: Array.isArray(analysis.normal_findings) ? analysis.normal_findings : [],
            abnormal_findings: Array.isArray(analysis.abnormal_findings) ? analysis.abnormal_findings : [],
            recommendations: Array.isArray(analysis.recommendations) ? analysis.recommendations : [],
            medical_interpretation: analysis.medical_interpretation || '',
            risk_assessment: analysis.risk_assessment || '',
            reassurance_concerns: analysis.reassurance_concerns || '',
            technical_details: Array.isArray(analysis.technical_details) ? analysis.technical_details : [],
            urgency: analysis.urgency || 'low',
            next_steps: analysis.next_steps || 'Consult with healthcare provider',
            ai_analysis: analysis.ai_analysis || ''
        };

        resultsDiv.innerHTML = `
            <div class="space-y-6">
                <!-- Raw AI Analysis is now hidden - we use structured sections instead -->

                <!-- Summary -->
                <div class="bg-slate-900 rounded-lg p-6 border border-slate-700">
                    <h4 class="text-lg font-semibold text-white mb-3">
                        <i class="fas fa-clipboard-list text-green-400 mr-2"></i>Summary
                    </h4>
                    <p class="text-slate-300">${safeAnalysis.summary}</p>
                </div>

                <!-- Key Findings -->
                <div class="bg-slate-900 rounded-lg p-6 border border-slate-700">
                    <h4 class="text-lg font-semibold text-white mb-3">
                        <i class="fas fa-search text-blue-400 mr-2"></i>Key Findings
                    </h4>
                    <ul class="space-y-2">
                        ${safeAnalysis.key_findings.length > 0 ? safeAnalysis.key_findings.map(finding => `
                            <li class="flex items-start space-x-2">
                                <i class="fas fa-check-circle text-green-400 mt-1 flex-shrink-0"></i>
                                <span class="text-slate-300">${finding}</span>
                            </li>
                        `).join('') : '<li class="text-slate-400">No specific findings identified</li>'}
                    </ul>
                </div>

                <!-- Recommendations -->
                <div class="bg-slate-900 rounded-lg p-6 border border-slate-700">
                    <h4 class="text-lg font-semibold text-white mb-3">
                        <i class="fas fa-lightbulb text-yellow-400 mr-2"></i>Recommendations
                    </h4>
                    <ul class="space-y-2">
                        ${safeAnalysis.recommendations.length > 0 ? safeAnalysis.recommendations.map(rec => `
                            <li class="flex items-start space-x-2">
                                <i class="fas fa-arrow-right text-yellow-400 mt-1 flex-shrink-0"></i>
                                <span class="text-slate-300">${rec}</span>
                            </li>
                        `).join('') : '<li class="text-slate-400">No specific recommendations available</li>'}
                    </ul>
                </div>

                <!-- Next Steps -->
                <div class="bg-slate-900 rounded-lg p-6 border border-slate-700">
                    <h4 class="text-lg font-semibold text-white mb-3">
                        <i class="fas fa-route text-purple-400 mr-2"></i>Next Steps
                    </h4>
                    <div class="flex items-start space-x-2">
                        <i class="fas fa-arrow-right text-purple-400 mt-1 flex-shrink-0"></i>
                        <span class="text-slate-300">${safeAnalysis.next_steps}</span>
                    </div>
                </div>

                <!-- Normal Findings -->
                ${safeAnalysis.normal_findings.length > 0 ? `
                <div class="bg-slate-900 rounded-lg p-6 border border-green-500/30">
                    <h4 class="text-lg font-semibold text-white mb-3">
                        <i class="fas fa-check-circle text-green-400 mr-2"></i>Normal Findings
                    </h4>
                    <ul class="space-y-2">
                        ${safeAnalysis.normal_findings.map(finding => `
                            <li class="flex items-start space-x-2">
                                <i class="fas fa-check text-green-400 mt-1 flex-shrink-0"></i>
                                <span class="text-slate-300">${finding}</span>
                            </li>
                        `).join('')}
                    </ul>
                </div>
                ` : ''}

                <!-- Abnormal Findings -->
                ${safeAnalysis.abnormal_findings.length > 0 ? `
                <div class="bg-slate-900 rounded-lg p-6 border border-red-500/30">
                    <h4 class="text-lg font-semibold text-white mb-3">
                        <i class="fas fa-exclamation-circle text-red-400 mr-2"></i>Abnormal Findings
                    </h4>
                    <ul class="space-y-2">
                        ${safeAnalysis.abnormal_findings.map(finding => `
                            <li class="flex items-start space-x-2">
                                <i class="fas fa-exclamation-triangle text-red-400 mt-1 flex-shrink-0"></i>
                                <span class="text-slate-300">${finding}</span>
                            </li>
                        `).join('')}
                    </ul>
                </div>
                ` : ''}

                <!-- Medical Interpretation -->
                ${safeAnalysis.medical_interpretation ? `
                <div class="bg-slate-900 rounded-lg p-6 border border-slate-700">
                    <h4 class="text-lg font-semibold text-white mb-3">
                        <i class="fas fa-stethoscope text-cyan-400 mr-2"></i>Medical Interpretation
                    </h4>
                    <div class="text-slate-300">${formatAIAnalysis(safeAnalysis.medical_interpretation)}</div>
                </div>
                ` : ''}

                <!-- Risk Assessment -->
                ${safeAnalysis.risk_assessment ? `
                <div class="bg-slate-900 rounded-lg p-6 border border-orange-500/30">
                    <h4 class="text-lg font-semibold text-white mb-3">
                        <i class="fas fa-shield-alt text-orange-400 mr-2"></i>Risk Assessment
                    </h4>
                    <div class="text-slate-300">${formatAIAnalysis(safeAnalysis.risk_assessment)}</div>
                </div>
                ` : ''}

                <!-- Reassurance & Concerns -->
                ${safeAnalysis.reassurance_concerns ? `
                <div class="bg-slate-900 rounded-lg p-6 border border-purple-500/30">
                    <h4 class="text-lg font-semibold text-white mb-3">
                        <i class="fas fa-heart text-purple-400 mr-2"></i>Patient Guidance
                    </h4>
                    <div class="text-slate-300">${formatAIAnalysis(safeAnalysis.reassurance_concerns)}</div>
                </div>
                ` : ''}

                <!-- Technical Details -->
                ${safeAnalysis.technical_details.length > 0 ? `
                <div class="bg-slate-900 rounded-lg p-6 border border-slate-700">
                    <h4 class="text-lg font-semibold text-white mb-3">
                        <i class="fas fa-microscope text-indigo-400 mr-2"></i>Technical Details
                    </h4>
                    <ul class="space-y-2">
                        ${safeAnalysis.technical_details.map(detail => `
                            <li class="flex items-start space-x-2">
                                <i class="fas fa-info-circle text-indigo-400 mt-1 flex-shrink-0"></i>
                                <span class="text-slate-300">${detail}</span>
                            </li>
                        `).join('')}
                    </ul>
                </div>
                ` : ''}

                <!-- Urgency Level -->
                <div class="bg-slate-900 rounded-lg p-6 border border-slate-700">
                    <h4 class="text-lg font-semibold text-white mb-3">
                        <i class="fas fa-exclamation-triangle text-orange-400 mr-2"></i>Urgency Level
                    </h4>
                    <div class="flex items-center space-x-2">
                        <span class="px-4 py-2 rounded-full text-sm font-medium ${
                            safeAnalysis.urgency === 'high' ? 'bg-red-500/20 text-red-400 border border-red-500' :
                            safeAnalysis.urgency === 'medium' ? 'bg-yellow-500/20 text-yellow-400 border border-yellow-500' :
                            'bg-green-500/20 text-green-400 border border-green-500'
                        }">
                            ${safeAnalysis.urgency.charAt(0).toUpperCase() + safeAnalysis.urgency.slice(1)} Priority
                        </span>
                    </div>
                </div>
            </div>
        `;
    }
});
</script>
@endsection
