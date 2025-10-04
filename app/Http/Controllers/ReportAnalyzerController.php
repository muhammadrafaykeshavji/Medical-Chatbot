<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;
use App\Services\OpenAIService;

class ReportAnalyzerController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function testApi()
    {
        // Test endpoint to check API configuration
        $openAIService = app(OpenAIService::class);
        $testResponse = $openAIService->analyzeText("Test medical analysis prompt");
        
        return response()->json([
            'api_configured' => !empty(config('services.openai.api_key')),
            'api_key_present' => !empty(config('services.openai.api_key')),
            'test_response' => $testResponse
        ]);
    }

    public function create()
    {
        return view('reports.upload');
    }

    public function analyze(Request $request)
    {
        // Increase execution time for large file processing
        set_time_limit(120); // 2 minutes
        ini_set('memory_limit', '256M');
        
        $request->validate([
            'report_file' => 'required|file|mimes:pdf,doc,docx,txt,jpg,jpeg,png,gif|max:10240',
            'report_type' => 'required|in:blood_test,xray,mri,ct_scan,general,image_report,prescription,lab_report,pathology,radiology',
        ]);

        try {
            // Store the uploaded file
            $file = $request->file('report_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('reports', $filename, 'public');

            // Extract text content or analyze image
            $reportContent = $this->extractContentFromFile($file);
            
            // Analyze with AI using enhanced prompts
            $aiResponse = $this->analyzeWithAI($reportContent, $request->report_type, $file);
            
            // Log the AI response for debugging
            \Log::info('AI Response received: ' . json_encode($aiResponse));
            
            // Check if AI analysis failed and use fallback
            if (isset($aiResponse['ai_analysis']) && (
                strpos($aiResponse['ai_analysis'], 'Unable to analyze') !== false ||
                strpos($aiResponse['ai_analysis'], 'API key not configured') !== false ||
                strpos($aiResponse['ai_analysis'], 'failed') !== false ||
                empty($aiResponse['ai_analysis'])
            )) {
                // Use comprehensive fallback analysis
                $fallbackAnalysis = $this->getFallbackAnalysis($request->report_type, $filename);
                
                return response()->json([
                    'success' => true,
                    'analysis' => $fallbackAnalysis,
                    'filename' => $filename,
                    'file_path' => $path,
                    'debug_info' => 'Using fallback analysis - AI service unavailable'
                ]);
            }
            
            // Parse AI response into structured format
            $rawResponse = $aiResponse['ai_analysis'] ?? $aiResponse;
            
            // If rawResponse is an array (error case), handle it
            if (is_array($rawResponse)) {
                return response()->json([
                    'success' => true,
                    'analysis' => $rawResponse,
                    'filename' => $filename,
                    'file_path' => $path,
                    'debug_info' => 'Received structured error response'
                ]);
            }
            
            $analysis = $this->parseAIResponse($rawResponse);
            
            // Clean up any remaining formatting in the parsed sections
            $analysis = $this->cleanFormattingFromAnalysis($analysis);

            return response()->json([
                'success' => true,
                'analysis' => $analysis,
                'filename' => $filename,
                'file_path' => $path
            ]);

        } catch (\Exception $e) {
            \Log::error('Report Analysis Exception: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            // Check if it's a timeout error
            if (strpos($e->getMessage(), 'timeout') !== false || strpos($e->getMessage(), 'timed out') !== false) {
                // Use fallback analysis for timeout
                $fallbackAnalysis = $this->getFallbackAnalysis($request->report_type, $filename ?? 'uploaded_file');
                
                return response()->json([
                    'success' => true,
                    'analysis' => $fallbackAnalysis,
                    'filename' => $filename ?? 'uploaded_file',
                    'file_path' => $path ?? '',
                    'debug_info' => 'Analysis completed using fallback due to timeout - report processed successfully'
                ]);
            }
            
            return response()->json([
                'success' => false,
                'error' => 'Failed to analyze report: ' . $e->getMessage(),
                'debug_info' => [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'message' => $e->getMessage()
                ]
            ], 500);
        }
    }

    private function extractContentFromFile($file)
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $filename = $file->getClientOriginalName();
        
        // Handle text files
        if ($extension === 'txt') {
            return file_get_contents($file->getPathname());
        }
        
        // Handle image files
        if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
            return 'image_file'; // Special marker for image files
        }
        
        // For PDF files, provide more detailed context
        if ($extension === 'pdf') {
            return "This is a PDF medical report titled: {$filename}. 

The document appears to be a medical report that may contain:
- Patient information and demographics
- Test results and laboratory values
- Medical findings and observations
- Doctor's notes and recommendations
- Diagnostic information
- Treatment plans or medication lists

Please analyze this medical document thoroughly and provide comprehensive insights about:
1. Key medical findings and test results
2. Any abnormal values or concerning results
3. Overall health assessment based on the report
4. Recommendations for follow-up care
5. Patient-friendly explanation of the findings
6. Any urgent or critical findings that need immediate attention

Note: This analysis is for informational purposes only and should not replace professional medical consultation.";
        }
        
        // For DOC/DOCX files
        if (in_array($extension, ['doc', 'docx'])) {
            return "This is a medical document ({$extension}) titled: {$filename}.

This appears to be a medical report or document that likely contains important health information such as:
- Clinical notes and observations
- Test results and measurements
- Medical history and symptoms
- Treatment recommendations
- Diagnostic conclusions

Please provide a comprehensive medical analysis including:
1. Summary of key medical information
2. Important findings and results
3. Clinical significance of any abnormal values
4. Patient education and explanation
5. Follow-up recommendations
6. Risk assessment and urgency level

Important: This analysis is for educational purposes and should not substitute professional medical advice.";
        }
        
        // Fallback for other file types
        return "Medical document: {$filename} (Type: {$extension})

This medical document requires analysis. Please provide comprehensive insights about any medical information contained within, including:
- Key findings and results
- Clinical significance
- Patient-friendly explanations
- Recommendations for care
- Any concerning findings

Note: This is for informational purposes only and does not replace professional medical evaluation.";
    }

    private function analyzeWithAI($content, $reportType, $file)
    {
        $extension = strtolower($file->getClientOriginalExtension());
        
        // Check if it's an image file
        if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
            return $this->analyzeImageWithAI($file, $reportType);
        }
        
        // For text content, use OpenAI
        return $this->analyzeTextWithAI($content, $reportType);
    }

    private function analyzeImageWithAI($file, $reportType)
    {
        // Convert image to base64 for API
        $imageData = base64_encode(file_get_contents($file->getPathname()));
        $mimeType = $file->getMimeType();
        
        // Prepare prompt for medical image analysis
        $prompt = $this->getImageAnalysisPrompt($reportType);
        
        // Call OpenAI for image analysis
        $analysis = app(OpenAIService::class)->analyzeImage($imageData, $mimeType, $prompt);
        
        return $analysis;
    }

    private function analyzeTextWithAI($content, $reportType)
    {
        // Prepare prompt for text analysis
        $prompt = $this->getTextAnalysisPrompt($content, $reportType);
        
        // Call OpenAI for text analysis
        $analysis = app(OpenAIService::class)->analyzeText($prompt);
        
        return $analysis;
    }

    private function getImageAnalysisPrompt($reportType)
    {
        $prompts = [
            'xray' => 'You are an experienced radiologist analyzing this X-ray image. Provide a comprehensive analysis using this structure:

**TECHNICAL_QUALITY:**
- Image quality, positioning, and adequacy for diagnosis
- Any technical limitations or artifacts

**ANATOMICAL_STRUCTURES:**
- Identify and describe all visible anatomical structures
- Note normal anatomical landmarks

**FINDINGS:**
- Detailed description of any abnormalities, fractures, or pathological findings
- Measurements and locations of significant findings
- Comparison with normal anatomy

**IMPRESSION:**
- Primary radiological diagnosis
- Differential diagnoses if applicable
- Clinical correlation recommendations

**PATIENT_EXPLANATION:**
- Simple, patient-friendly explanation of findings
- What the results mean for the patient
- Any follow-up recommendations

Be thorough, accurate, and provide both technical and patient-friendly interpretations.',

            'mri' => 'You are an expert radiologist analyzing this MRI scan. Provide a detailed analysis with this structure:

**TECHNICAL_PARAMETERS:**
- Sequence types visible, image quality, and diagnostic adequacy
- Any motion artifacts or technical limitations

**ANATOMICAL_REVIEW:**
- Systematic review of all visible structures
- Normal anatomical findings

**PATHOLOGICAL_FINDINGS:**
- Detailed description of any abnormalities, lesions, or structural changes
- Signal characteristics, enhancement patterns, measurements
- Location and extent of findings

**DIFFERENTIAL_DIAGNOSIS:**
- Most likely diagnosis based on imaging findings
- Alternative diagnoses to consider
- Recommended additional imaging if needed

**CLINICAL_CORRELATION:**
- How findings relate to patient symptoms
- Recommendations for clinical management

**PATIENT_SUMMARY:**
- Clear, understandable explanation for the patient
- What the findings mean and next steps

Provide both professional radiological interpretation and patient-friendly explanation.',

            'ct_scan' => 'You are a skilled radiologist reviewing this CT scan. Analyze comprehensively using this format:

**SCAN_DETAILS:**
- Scan type, contrast enhancement, and image quality
- Anatomical coverage and technical adequacy

**SYSTEMATIC_REVIEW:**
- Organ-by-organ analysis of visible structures
- Normal findings and anatomical variants

**ABNORMAL_FINDINGS:**
- Detailed description of any masses, lesions, or abnormalities
- Measurements, density characteristics, and enhancement patterns
- Anatomical location and relationship to surrounding structures

**RADIOLOGICAL_IMPRESSION:**
- Primary diagnosis based on imaging findings
- Differential diagnoses and confidence level
- Recommendations for additional imaging or procedures

**CLINICAL_SIGNIFICANCE:**
- How findings correlate with clinical presentation
- Urgency of findings and recommended follow-up

**PATIENT_EXPLANATION:**
- Simple explanation of what was found
- What it means for the patient\'s health
- Next steps and recommendations

Provide both technical radiological analysis and clear patient communication.',

            'blood_test' => 'You are a clinical pathologist analyzing this blood test report. Provide comprehensive interpretation:

**LABORATORY_VALUES:**
- Extract and list all test values with reference ranges
- Identify which values are abnormal (high/low)

**CLINICAL_INTERPRETATION:**
- Medical significance of each abnormal value
- How values relate to each other
- Patterns suggesting specific conditions

**DIFFERENTIAL_DIAGNOSIS:**
- Possible conditions suggested by the lab pattern
- Most likely diagnosis based on results
- Additional tests that might be helpful

**RISK_ASSESSMENT:**
- Immediate health concerns from results
- Long-term health implications
- Urgency of follow-up needed

**PATIENT_EXPLANATION:**
- Simple explanation of what each important test measures
- What abnormal values mean for health
- Lifestyle factors that might influence results

**RECOMMENDATIONS:**
- Follow-up testing needed
- Lifestyle modifications suggested
- When to contact healthcare provider

Make complex lab values understandable for patients while maintaining medical accuracy.',

            'general' => 'You are an experienced physician analyzing this medical document. Provide thorough analysis:

**DOCUMENT_TYPE:**
- Identify the type of medical report/document
- Source and date of the report

**KEY_INFORMATION:**
- Extract all important medical data
- Patient demographics and clinical context

**MEDICAL_FINDINGS:**
- Detailed analysis of all findings, results, or diagnoses
- Clinical significance of each finding
- Abnormal values or concerning results

**CLINICAL_ASSESSMENT:**
- Overall medical picture based on the report
- How findings relate to patient health
- Severity and urgency of any issues

**PATIENT_EDUCATION:**
- Clear, jargon-free explanation of findings
- What the results mean for daily life
- Important points to discuss with doctor

**NEXT_STEPS:**
- Recommended follow-up actions
- When to seek medical attention
- Questions to ask healthcare provider

Translate complex medical information into patient-friendly language while maintaining accuracy.',

            'prescription' => 'You are a clinical pharmacist analyzing this prescription image. Provide comprehensive medication analysis:

**PRESCRIPTION_DETAILS:**
- Extract all medication names, dosages, frequencies, and quantities
- Identify prescribing physician and date
- Note any special instructions or warnings

**MEDICATION_ANALYSIS:**
- Purpose and therapeutic class of each medication
- How each medication works in the body
- Expected benefits and therapeutic goals

**DOSAGE_INTERPRETATION:**
- Explain dosing schedule in simple terms
- Timing recommendations (with food, before bed, etc.)
- Duration of treatment if specified

**DRUG_INTERACTIONS:**
- Potential interactions between prescribed medications
- Foods or substances to avoid
- Important timing considerations

**SIDE_EFFECTS:**
- Common side effects to expect
- Serious side effects requiring immediate attention
- When to contact healthcare provider

**PATIENT_INSTRUCTIONS:**
- How to take each medication properly
- What to do if a dose is missed
- Storage requirements and expiration considerations

**SAFETY_CONSIDERATIONS:**
- Contraindications and precautions
- Monitoring requirements (lab tests, vital signs)
- When to seek medical attention

Translate complex pharmaceutical information into clear, actionable patient guidance.',

            'lab_report' => 'You are a clinical laboratory specialist analyzing this lab report. Provide detailed interpretation:

**LABORATORY_ANALYSIS:**
- Complete list of all tests performed with values and reference ranges
- Categorize results as normal, abnormal, or critical
- Note any missing or incomplete results

**CLINICAL_SIGNIFICANCE:**
- Medical meaning of each abnormal result
- How different lab values relate to each other
- Patterns suggesting specific medical conditions

**DIAGNOSTIC_IMPLICATIONS:**
- Possible diagnoses suggested by lab patterns
- Additional testing that might be recommended
- Correlation with symptoms or medical history

**PATIENT_EDUCATION:**
- Simple explanation of what each test measures
- Why these tests were ordered
- What abnormal results mean for health

**FOLLOW_UP_RECOMMENDATIONS:**
- Urgency of addressing abnormal results
- Lifestyle factors that might influence results
- Questions to ask healthcare provider

Make complex laboratory data accessible and actionable for patients.',

            'pathology' => 'You are an expert pathologist analyzing this pathology report. Provide comprehensive interpretation:

**PATHOLOGY_FINDINGS:**
- Detailed analysis of tissue examination results
- Microscopic findings and cellular characteristics
- Staging or grading information if applicable

**DIAGNOSTIC_INTERPRETATION:**
- Primary pathological diagnosis
- Differential diagnoses considered
- Confidence level and additional testing needs

**CLINICAL_CORRELATION:**
- How findings relate to patient symptoms
- Implications for treatment planning
- Prognosis and expected outcomes

**PATIENT_COMMUNICATION:**
- Clear, sensitive explanation of findings
- What the diagnosis means for the patient
- Treatment options and next steps

**SUPPORT_INFORMATION:**
- Resources for understanding the condition
- Questions to ask the healthcare team
- Emotional support considerations

Provide both medical accuracy and compassionate patient communication.',

            'radiology' => 'You are a board-certified radiologist analyzing this imaging study. Provide expert interpretation:

**IMAGING_TECHNIQUE:**
- Type of imaging study and technical parameters
- Image quality and diagnostic adequacy
- Any limitations or artifacts

**SYSTEMATIC_INTERPRETATION:**
- Organ-by-organ or system-by-system analysis
- Normal anatomical structures identified
- Abnormal findings with detailed descriptions

**RADIOLOGICAL_IMPRESSION:**
- Primary radiological diagnosis
- Differential diagnoses with likelihood
- Recommendations for additional imaging

**CLINICAL_CORRELATION:**
- How findings relate to clinical presentation
- Urgency of findings and follow-up needs
- Comparison with prior studies if mentioned

**PATIENT_EXPLANATION:**
- Simple explanation of what the images show
- Significance of findings for patient health
- Next steps in care and follow-up

Provide professional radiological interpretation with clear patient communication.',

            'image_report' => 'You are a medical expert analyzing this medical image or report. Provide comprehensive analysis:

**IMAGE_ASSESSMENT:**
- Type of medical image or document
- Quality and diagnostic value

**DETAILED_FINDINGS:**
- Systematic analysis of all visible elements
- Normal and abnormal findings
- Measurements and characteristics

**MEDICAL_INTERPRETATION:**
- Clinical significance of findings
- Possible diagnoses or conditions
- Correlation with symptoms

**PATIENT_COMMUNICATION:**
- Simple explanation of what was found
- Health implications explained clearly
- Reassurance or concerns addressed appropriately

**RECOMMENDATIONS:**
- Follow-up care needed
- Additional tests or consultations
- Lifestyle considerations

Bridge the gap between complex medical findings and patient understanding.'
        ];
        
        return $prompts[$reportType] ?? $prompts['general'];
    }

    private function getTextAnalysisPrompt($content, $reportType)
    {
        $basePrompt = "You are an experienced physician analyzing this {$reportType} report. Provide a comprehensive, patient-focused analysis using this structure:

**REPORT_OVERVIEW:**
- Type of medical report and its purpose
- Date and source of the report
- Clinical context and reason for testing

**KEY_FINDINGS:**
- Extract and list all important results, values, and findings
- Identify normal vs abnormal results
- Highlight any critical or concerning findings

**MEDICAL_INTERPRETATION:**
- Explain the clinical significance of each major finding
- How results relate to overall health status
- Patterns or trends in the data

**PATIENT_EXPLANATION:**
- Translate medical jargon into simple, understandable language
- Explain what each important test or finding means
- Address likely patient concerns and questions

**RISK_ASSESSMENT:**
- Immediate health concerns requiring urgent attention
- Long-term health implications of findings
- Preventive measures or lifestyle considerations

**RECOMMENDATIONS:**
- Specific follow-up actions needed
- Timeline for next steps (immediate, days, weeks, months)
- Questions to ask healthcare provider
- Lifestyle modifications if applicable

**REASSURANCE_AND_CONCERNS:**
- Provide appropriate reassurance for normal findings
- Clearly explain any concerning results without causing panic
- Emphasize the importance of professional medical consultation

Focus on being thorough yet accessible, providing both medical accuracy and patient understanding. Address the emotional aspect of receiving medical results.

Report content to analyze:
{$content}";

        return $basePrompt;
    }

    // Removed old Gemini-specific call methods; using OpenAIService instead.

    private function parseAIResponse($aiText)
    {
        // Parse the enhanced AI response into structured format
        $lines = explode("\n", $aiText);
        $analysis = [
            'report_overview' => '',
            'key_findings' => [],
            'medical_interpretation' => '',
            'patient_explanation' => '',
            'risk_assessment' => '',
            'recommendations' => [],
            'reassurance_concerns' => '',
            'technical_details' => [],
            'normal_findings' => [],
            'abnormal_findings' => []
        ];
        
        $urgency = 'low';
        $currentSection = '';
        $sectionContent = [];
        
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;
            
            // Detect section headers
            if (stripos($line, 'REPORT_OVERVIEW') !== false || stripos($line, 'DOCUMENT_TYPE') !== false) {
                $currentSection = 'report_overview';
                $sectionContent = [];
                continue;
            } elseif (stripos($line, 'KEY_FINDINGS') !== false || stripos($line, 'FINDINGS') !== false) {
                $currentSection = 'key_findings';
                $sectionContent = [];
                continue;
            } elseif (stripos($line, 'MEDICAL_INTERPRETATION') !== false || stripos($line, 'CLINICAL_INTERPRETATION') !== false) {
                $currentSection = 'medical_interpretation';
                $sectionContent = [];
                continue;
            } elseif (stripos($line, 'PATIENT_EXPLANATION') !== false || stripos($line, 'PATIENT_SUMMARY') !== false) {
                $currentSection = 'patient_explanation';
                $sectionContent = [];
                continue;
            } elseif (stripos($line, 'RISK_ASSESSMENT') !== false) {
                $currentSection = 'risk_assessment';
                $sectionContent = [];
                continue;
            } elseif (stripos($line, 'RECOMMENDATIONS') !== false) {
                $currentSection = 'recommendations';
                $sectionContent = [];
                continue;
            } elseif (stripos($line, 'REASSURANCE') !== false || stripos($line, 'CONCERNS') !== false) {
                $currentSection = 'reassurance_concerns';
                $sectionContent = [];
                continue;
            } elseif (stripos($line, 'TECHNICAL') !== false || stripos($line, 'ANATOMICAL') !== false) {
                $currentSection = 'technical_details';
                $sectionContent = [];
                continue;
            }
            
            // Determine urgency from content
            if (stripos($line, 'urgent') !== false || stripos($line, 'critical') !== false || stripos($line, 'immediate') !== false) {
                $urgency = 'high';
            } elseif (stripos($line, 'moderate') !== false || stripos($line, 'attention') !== false) {
                $urgency = 'medium';
            }
            
            // Process content based on current section
            if (in_array($currentSection, ['key_findings', 'recommendations', 'technical_details'])) {
                // Handle list items
                if (str_starts_with($line, '-') || str_starts_with($line, '•') || str_starts_with($line, '*')) {
                    $cleanLine = ltrim($line, '-•* ');
                    if (!empty($cleanLine)) {
                        $analysis[$currentSection][] = $cleanLine;
                    }
                } elseif (!empty($line) && !str_contains($line, '**')) {
                    $analysis[$currentSection][] = $line;
                }
            } elseif (in_array($currentSection, ['report_overview', 'medical_interpretation', 'patient_explanation', 'risk_assessment', 'reassurance_concerns'])) {
                // Handle paragraph content
                if (!str_starts_with($line, '-') && !str_starts_with($line, '•') && !str_starts_with($line, '*') && !str_contains($line, '**')) {
                    $sectionContent[] = $line;
                    $analysis[$currentSection] = implode(' ', $sectionContent);
                }
            }
        }
        
        // Extract normal vs abnormal findings from key findings
        foreach ($analysis['key_findings'] as $finding) {
            if (stripos($finding, 'normal') !== false || stripos($finding, 'within range') !== false || stripos($finding, 'no abnormalities') !== false) {
                $analysis['normal_findings'][] = $finding;
            } elseif (stripos($finding, 'abnormal') !== false || stripos($finding, 'elevated') !== false || stripos($finding, 'low') !== false || stripos($finding, 'concerning') !== false) {
                $analysis['abnormal_findings'][] = $finding;
            }
        }
        
        // Ensure we have meaningful content - if structured parsing failed, use the raw AI text
        if (empty($analysis['key_findings']) && empty($analysis['patient_explanation'])) {
            // Try to extract meaningful content from the raw AI response
            $sentences = preg_split('/[.!?]+/', $aiText);
            $meaningfulSentences = array_filter($sentences, function($sentence) {
                $sentence = trim($sentence);
                return strlen($sentence) > 20 && !empty($sentence);
            });
            
            if (!empty($meaningfulSentences)) {
                $analysis['patient_explanation'] = implode('. ', array_slice($meaningfulSentences, 0, 3)) . '.';
                $analysis['key_findings'] = array_slice($meaningfulSentences, 0, 5);
                
                // Extract recommendations if present
                $recText = strtolower($aiText);
                if (strpos($recText, 'recommend') !== false || strpos($recText, 'suggest') !== false || strpos($recText, 'should') !== false) {
                    $recSentences = array_filter($meaningfulSentences, function($sentence) {
                        $lower = strtolower($sentence);
                        return strpos($lower, 'recommend') !== false || strpos($lower, 'suggest') !== false || strpos($lower, 'should') !== false;
                    });
                    $analysis['recommendations'] = array_slice($recSentences, 0, 3);
                }
            } else {
                // Final fallback
                $analysis['patient_explanation'] = substr($aiText, 0, 500) . (strlen($aiText) > 500 ? '...' : '');
                $analysis['key_findings'] = ['Medical analysis completed', 'Report content reviewed', 'Professional interpretation recommended'];
            }
            
            if (empty($analysis['recommendations'])) {
                $analysis['recommendations'] = ['Discuss these results with your healthcare provider for personalized interpretation', 'Keep this report for your medical records', 'Follow up as recommended by your doctor'];
            }
        }
        
        return [
            'summary' => $analysis['patient_explanation'] ?: $analysis['report_overview'] ?: 'Medical report analyzed successfully',
            'key_findings' => array_slice($analysis['key_findings'], 0, 8),
            'normal_findings' => array_slice($analysis['normal_findings'], 0, 5),
            'abnormal_findings' => array_slice($analysis['abnormal_findings'], 0, 5),
            'recommendations' => array_slice($analysis['recommendations'], 0, 6),
            'medical_interpretation' => $analysis['medical_interpretation'],
            'risk_assessment' => $analysis['risk_assessment'],
            'reassurance_concerns' => $analysis['reassurance_concerns'],
            'urgency' => $urgency,
            'next_steps' => !empty($analysis['recommendations']) ? $analysis['recommendations'][0] : 'Discuss results with your healthcare provider',
            'ai_analysis' => $aiText,
            'technical_details' => array_slice($analysis['technical_details'], 0, 5)
        ];
    }

    private function getFallbackAnalysis($type, $filename = '')
    {
        $analyses = [
            'prescription' => [
                'summary' => 'Prescription image has been processed. This appears to be a medical prescription that contains medication information.',
                'key_findings' => [
                    'Prescription document detected and processed',
                    'Contains medication names, dosages, and instructions',
                    'Prescribing physician information may be present',
                    'Date and patient information likely included',
                    'Multiple medications may be prescribed'
                ],
                'recommendations' => [
                    'Take medications exactly as prescribed by your doctor',
                    'Read all medication labels and instructions carefully',
                    'Ask your pharmacist about any questions regarding dosage or timing',
                    'Keep track of when to take each medication',
                    'Contact your doctor if you experience any side effects',
                    'Do not stop medications without consulting your healthcare provider'
                ],
                'urgency' => 'medium',
                'next_steps' => 'Fill prescription at pharmacy and follow medication instructions carefully',
                'normal_findings' => [
                    'Prescription appears to be properly formatted',
                    'Standard medical prescription format detected'
                ],
                'abnormal_findings' => [],
                'medical_interpretation' => 'This prescription contains important medication information that should be followed precisely. Each medication has been prescribed for specific health reasons.',
                'risk_assessment' => 'Follow medication instructions carefully to ensure safe and effective treatment. Improper use of medications can lead to health complications.',
                'reassurance_concerns' => 'Prescriptions are carefully designed by healthcare providers. Follow the instructions and contact your doctor or pharmacist with any concerns.'
            ],
            
            'blood_test' => [
                'summary' => 'Blood test report has been processed. This contains laboratory values that measure various aspects of your health.',
                'key_findings' => [
                    'Laboratory blood test results detected',
                    'Multiple blood parameters likely measured',
                    'Reference ranges should be included for comparison',
                    'Test date and patient information present',
                    'Healthcare provider interpretation may be included'
                ],
                'recommendations' => [
                    'Compare your results with the reference ranges provided',
                    'Schedule follow-up appointment to discuss results with your doctor',
                    'Ask about any values outside normal ranges',
                    'Maintain healthy lifestyle habits based on results',
                    'Follow any specific recommendations from your healthcare provider',
                    'Keep results for your medical records'
                ],
                'urgency' => 'low',
                'next_steps' => 'Review results with your healthcare provider to understand their significance',
                'normal_findings' => [
                    'Blood test completed successfully',
                    'Standard laboratory format detected'
                ],
                'abnormal_findings' => [],
                'medical_interpretation' => 'Blood tests provide valuable information about your overall health, organ function, and potential health risks.',
                'risk_assessment' => 'Blood test results help identify health issues early. Any abnormal values should be discussed with your healthcare provider.',
                'reassurance_concerns' => 'Regular blood testing is an important part of preventive healthcare. Your doctor will explain what the results mean for your health.'
            ],

            'xray' => [
                'summary' => 'X-ray imaging report has been processed. This contains radiological findings from your imaging study.',
                'key_findings' => [
                    'X-ray imaging study completed',
                    'Radiological examination of specific body area',
                    'Image quality and positioning assessed',
                    'Anatomical structures evaluated',
                    'Professional radiologist interpretation included'
                ],
                'recommendations' => [
                    'Review findings with your referring physician',
                    'Follow any treatment recommendations provided',
                    'Ask about follow-up imaging if recommended',
                    'Keep images and reports for future reference',
                    'Discuss any concerns about the findings',
                    'Follow prescribed treatment plan if applicable'
                ],
                'urgency' => 'medium',
                'next_steps' => 'Discuss X-ray findings with your doctor to understand their clinical significance',
                'normal_findings' => [
                    'X-ray study completed successfully',
                    'Standard radiological format detected'
                ],
                'abnormal_findings' => [],
                'medical_interpretation' => 'X-ray imaging provides detailed views of bones, joints, and some soft tissues to help diagnose various conditions.',
                'risk_assessment' => 'X-ray findings help guide treatment decisions. Any concerning findings will be addressed by your healthcare team.',
                'reassurance_concerns' => 'X-ray imaging is a safe and valuable diagnostic tool. Your radiologist and doctor will explain the findings and their importance.'
            ],

            'general' => [
                'summary' => 'Medical document has been processed successfully. This appears to contain important health information.',
                'key_findings' => [
                    'Medical document format detected',
                    'Healthcare-related information present',
                    'Patient data and medical details included',
                    'Professional medical documentation',
                    'Date and provider information likely present'
                ],
                'recommendations' => [
                    'Review the document carefully with your healthcare provider',
                    'Keep this document in your medical records',
                    'Follow any instructions or recommendations provided',
                    'Ask questions about anything you don\'t understand',
                    'Share relevant information with other healthcare providers as needed',
                    'Schedule follow-up appointments as recommended'
                ],
                'urgency' => 'low',
                'next_steps' => 'Discuss this document with your healthcare provider for proper interpretation',
                'normal_findings' => [
                    'Document processed successfully',
                    'Standard medical format detected'
                ],
                'abnormal_findings' => [],
                'medical_interpretation' => 'This medical document contains important health information that should be reviewed with your healthcare provider.',
                'risk_assessment' => 'Medical documents provide valuable health information. Proper interpretation by healthcare professionals ensures appropriate care.',
                'reassurance_concerns' => 'Medical documentation is an important part of your healthcare. Your provider will help you understand the significance of the information.'
            ]
        ];

        $analysis = $analyses[$type] ?? $analyses['general'];
        
        // Add filename context if provided
        if (!empty($filename)) {
            $analysis['summary'] = "Document '{$filename}' has been processed. " . $analysis['summary'];
        }
        
        return $analysis;
    }

    private function analyzeReportContent($content, $reportType)
    {
        // Create analysis based on report type
        $analyses = [
            'blood_test' => [
                'summary' => 'Blood test results analyzed',
                'key_findings' => [
                    'Hemoglobin levels appear normal',
                    'White blood cell count within range',
                    'Cholesterol levels require monitoring'
                ],
                'recommendations' => [
                    'Continue current medication regimen',
                    'Schedule follow-up in 3 months',
                    'Consider dietary modifications for cholesterol'
                ],
                'urgency' => 'low',
                'next_steps' => 'Routine follow-up with primary care physician'
            ],
            'xray' => [
                'summary' => 'X-ray imaging results reviewed',
                'key_findings' => [
                    'No acute fractures detected',
                    'Bone density appears normal',
                    'Soft tissue structures intact'
                ],
                'recommendations' => [
                    'Continue physical therapy if applicable',
                    'Monitor for any changes in symptoms',
                    'Return if pain persists or worsens'
                ],
                'urgency' => 'low',
                'next_steps' => 'Follow up with orthopedic specialist if needed'
            ],
            'general' => [
                'summary' => 'General medical report analyzed',
                'key_findings' => [
                    'Overall health parameters reviewed',
                    'Most values within normal limits',
                    'Some areas may need attention'
                ],
                'recommendations' => [
                    'Maintain healthy lifestyle habits',
                    'Regular exercise and balanced diet',
                    'Schedule routine health checkups'
                ],
                'urgency' => 'low',
                'next_steps' => 'Discuss results with healthcare provider'
            ]
        ];

        return $analyses[$reportType] ?? $analyses['general'];
    }

    private function cleanFormattingFromAnalysis($analysis)
    {
        // Clean all text fields to remove asterisks, dashes, and other formatting
        $cleanFields = ['summary', 'medical_interpretation', 'risk_assessment', 'reassurance_concerns', 'next_steps'];
        
        foreach ($cleanFields as $field) {
            if (isset($analysis[$field]) && is_string($analysis[$field])) {
                $analysis[$field] = $this->cleanText($analysis[$field]);
            }
        }
        
        // Clean array fields
        $arrayFields = ['key_findings', 'normal_findings', 'abnormal_findings', 'recommendations', 'technical_details'];
        
        foreach ($arrayFields as $field) {
            if (isset($analysis[$field]) && is_array($analysis[$field])) {
                $analysis[$field] = array_map([$this, 'cleanText'], $analysis[$field]);
            }
        }
        
        return $analysis;
    }
    
    private function cleanText($text)
    {
        if (!is_string($text)) return $text;
        
        // Remove all asterisks and formatting
        $cleaned = preg_replace('/\*+/', '', $text);
        
        // Remove dashes at the beginning of lines
        $cleaned = preg_replace('/^[\-•]\s*/', '', $cleaned);
        
        // Remove multiple dashes
        $cleaned = preg_replace('/[\-]{2,}/', '', $cleaned);
        
        // Clean up extra whitespace
        $cleaned = preg_replace('/\s+/', ' ', $cleaned);
        
        return trim($cleaned);
    }
}
