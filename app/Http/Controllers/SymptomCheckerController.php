<?php

namespace App\Http\Controllers;

use App\Models\SymptomCheck;
use App\Services\OpenAIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class SymptomCheckerController extends Controller
{
    private OpenAIService $aiService;

    public function __construct(OpenAIService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function index()
    {
        $symptomChecks = Auth::user()->symptomChecks()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('symptom-checker.index', compact('symptomChecks'));
    }

    public function create()
    {
        return view('symptom-checker.create');
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'symptoms' => 'required|array|min:1',
                'symptoms.*' => 'string|max:100',
                'description' => 'nullable|string|max:1000',
            ]);

            $user = Auth::user();
            
            if (!$user) {
                \Log::error('User not authenticated in symptom checker');
                return response()->json([
                    'success' => false,
                    'error' => 'User not authenticated',
                ], 401);
            }

            // Create symptom check record
            $symptomCheck = SymptomCheck::create([
                'user_id' => $user->id,
                'symptoms' => $request->symptoms,
                'description' => $request->description,
                'urgency_level' => 'low', // Default, will be updated by AI
            ]);

            // Analyze symptoms with AI
            $analysis = $this->analyzeSymptoms($request->symptoms, $request->description);

            // Update symptom check with AI analysis
            $symptomCheck->update([
                'ai_analysis' => $analysis['analysis'] ?? null,
                'urgency_level' => $analysis['urgency_level'] ?? 'low',
                'recommendations' => $analysis['recommendations'] ?? null,
                'doctor_recommended' => $analysis['doctor_recommended'] ?? false,
            ]);

            return response()->json([
                'success' => true,
                'symptom_check' => $symptomCheck->fresh(),
                'analysis' => $analysis,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Symptom analysis error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'An error occurred while analyzing symptoms. Please try again.',
            ], 500);
        }
    }

    public function analyze(Request $request): JsonResponse
    {
        // This method is an alias for store - both do the same symptom analysis
        return $this->store($request);
    }

    public function show(SymptomCheck $symptomCheck)
    {
        // Ensure user owns this symptom check
        if ($symptomCheck->user_id !== Auth::id()) {
            abort(403);
        }

        return view('symptom-checker.show', compact('symptomCheck'));
    }

    private function analyzeSymptoms(array $symptoms, ?string $description = null): array
    {
        try {
            // Create a comprehensive prompt for AI symptom analysis
            $prompt = $this->buildSymptomAnalysisPrompt($symptoms, $description);
            
            // Get AI analysis
            $aiResponse = $this->aiService->analyzeText($prompt);
            
            // Parse the AI response
            if (isset($aiResponse['ai_analysis']) && !empty($aiResponse['ai_analysis'])) {
                $parsedAnalysis = $this->parseAISymptomAnalysis($aiResponse['ai_analysis'], $symptoms);
                if (!empty($parsedAnalysis)) {
                    return $parsedAnalysis;
                }
            }
            
            \Log::info('AI symptom analysis failed, using fallback', ['symptoms' => $symptoms]);
            
        } catch (\Exception $e) {
            \Log::error('AI symptom analysis error: ' . $e->getMessage());
        }
        
        // Fallback to rule-based analysis if AI fails
        return $this->getFallbackSymptomAnalysis($symptoms, $description);
    }

    private function buildSymptomAnalysisPrompt(array $symptoms, ?string $description): string
    {
        $symptomsText = implode(', ', $symptoms);
        $descriptionText = $description ? "\nPatient description: " . $description : '';
        
        return "You are an experienced medical AI assistant. Analyze these symptoms with clinical depth and provide a comprehensive, personalized assessment:

PATIENT PRESENTATION:
Symptoms: {$symptomsText}{$descriptionText}

Provide a detailed medical analysis using the following structure:

**SUMMARY:**
Provide a 2-3 sentence clinical summary that:
- Describes the symptom constellation and its significance
- Identifies the primary medical concern
- Notes any concerning patterns or red flags
- Avoid generic statements - be specific to these exact symptoms

**POSSIBLE_CONDITIONS:**
List 5-7 differential diagnoses in order of clinical likelihood:
- Include specific medical conditions (not just categories)
- Provide brief pathophysiology for each condition
- Explain why each condition fits the symptom pattern
- Include prevalence/likelihood percentages when relevant
- Consider both common and serious causes

**URGENCY_LEVEL:**
Classify as: emergency, high, medium, or low
- Provide clinical reasoning for the urgency classification
- Consider worst-case scenarios and time-sensitive conditions
- Factor in symptom severity, duration, and progression

**IMMEDIATE_CARE:**
Provide 5-6 specific, actionable self-care measures:
- Include dosages and frequencies where appropriate
- Prioritize interventions by effectiveness
- Mention what to avoid or contraindications
- Include monitoring parameters (what to watch for)
- Be specific to the presenting symptoms

**WARNING_SIGNS:**
List 6-8 specific red flag symptoms requiring immediate medical attention:
- Include physiological parameters (vital signs, neurological signs)
- Specify timeframes (immediate vs within hours)
- Mention specific emergency scenarios
- Include when to call 911 vs urgent care vs ER

**RECOMMENDATIONS:**
Provide detailed next steps including:
- Specific timeframe for medical evaluation (hours/days)
- Type of healthcare provider (primary care, specialist, urgent care)
- What tests or evaluations might be needed
- Follow-up recommendations
- Lifestyle modifications if relevant

**DOCTOR_RECOMMENDED:**
Answer 'true' or 'false' with brief clinical justification

Be thorough, specific, and clinically accurate. Avoid generic advice. Tailor everything to the specific symptom presentation. Include appropriate medical disclaimers about professional evaluation.";
    }

    private function parseAISymptomAnalysis(string $aiText, array $symptoms): array
    {
        $analysis = [
            'summary' => '',
            'possible_conditions' => [],
            'immediate_care' => [],
            'general_advice' => '',
            'warning_signs' => []
        ];
        
        $urgencyLevel = 'medium';
        $recommendations = '';
        $doctorRecommended = true;
        
        $currentSection = '';
        $lines = explode("\n", $aiText);
        $sectionContent = [];
        
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;
            
            // Detect section headers
            if (stripos($line, 'SUMMARY') !== false) {
                $currentSection = 'summary';
                $sectionContent = [];
                continue;
            } elseif (stripos($line, 'POSSIBLE_CONDITIONS') !== false) {
                $currentSection = 'possible_conditions';
                $sectionContent = [];
                continue;
            } elseif (stripos($line, 'URGENCY_LEVEL') !== false) {
                $currentSection = 'urgency_level';
                $sectionContent = [];
                continue;
            } elseif (stripos($line, 'IMMEDIATE_CARE') !== false) {
                $currentSection = 'immediate_care';
                $sectionContent = [];
                continue;
            } elseif (stripos($line, 'WARNING_SIGNS') !== false) {
                $currentSection = 'warning_signs';
                $sectionContent = [];
                continue;
            } elseif (stripos($line, 'RECOMMENDATIONS') !== false) {
                $currentSection = 'recommendations';
                $sectionContent = [];
                continue;
            } elseif (stripos($line, 'DOCTOR_RECOMMENDED') !== false) {
                $currentSection = 'doctor_recommended';
                $sectionContent = [];
                continue;
            }
            
            // Process content based on current section
            if ($currentSection === 'summary') {
                if (!str_starts_with($line, '-') && !str_starts_with($line, '•') && !str_starts_with($line, '*')) {
                    $sectionContent[] = $line;
                }
                $analysis['summary'] = implode(' ', $sectionContent);
            } elseif ($currentSection === 'urgency_level') {
                $sectionContent[] = $line;
                $fullText = implode(' ', $sectionContent);
                if (stripos($fullText, 'emergency') !== false) {
                    $urgencyLevel = 'emergency';
                } elseif (stripos($fullText, 'high') !== false) {
                    $urgencyLevel = 'high';
                } elseif (stripos($fullText, 'low') !== false) {
                    $urgencyLevel = 'low';
                } else {
                    $urgencyLevel = 'medium';
                }
            } elseif ($currentSection === 'recommendations') {
                if (!str_starts_with($line, '-') && !str_starts_with($line, '•') && !str_starts_with($line, '*')) {
                    $sectionContent[] = $line;
                }
                $recommendations = implode(' ', $sectionContent);
            } elseif ($currentSection === 'doctor_recommended') {
                $sectionContent[] = $line;
                $fullText = implode(' ', $sectionContent);
                $doctorRecommended = stripos($fullText, 'true') !== false;
            } elseif (in_array($currentSection, ['possible_conditions', 'immediate_care', 'warning_signs'])) {
                // Handle both bullet points and regular text
                if (str_starts_with($line, '-') || str_starts_with($line, '•') || str_starts_with($line, '*')) {
                    $cleanLine = ltrim($line, '-•* ');
                    if (!empty($cleanLine)) {
                        $analysis[$currentSection][] = $cleanLine;
                    }
                } elseif (!empty($line) && !str_contains($line, '**')) {
                    // Add non-header lines as separate items
                    $analysis[$currentSection][] = $line;
                }
            }
        }
        
        // Create more detailed general advice from summary
        $analysis['general_advice'] = $analysis['summary'] ?: $this->getGeneralAdvice($symptoms);
        
        // Ensure we have meaningful content
        if (empty($analysis['summary']) && empty($analysis['possible_conditions']) && empty($analysis['immediate_care'])) {
            \Log::warning('AI parsing failed - insufficient content extracted', [
                'raw_text' => substr($aiText, 0, 500),
                'symptoms' => $symptoms
            ]);
            return [];
        }
        
        return [
            'analysis' => $analysis,
            'urgency_level' => $urgencyLevel,
            'recommendations' => $recommendations ?: $this->getRecommendations($urgencyLevel),
            'doctor_recommended' => $doctorRecommended,
        ];
    }

    private function getFallbackSymptomAnalysis(array $symptoms, ?string $description): array
    {
        $urgencyLevel = $this->determineUrgencyLevel($symptoms);
        $doctorRecommended = in_array($urgencyLevel, ['high', 'emergency']);

        return [
            'analysis' => [
                'summary' => $this->getSymptomSummary($symptoms, $description),
                'possible_conditions' => $this->getPossibleConditions($symptoms),
                'immediate_care' => $this->getImmediateCareAdvice($symptoms),
                'general_advice' => $this->getGeneralAdvice($symptoms),
                'warning_signs' => $this->getWarningSigns($symptoms),
            ],
            'urgency_level' => $urgencyLevel,
            'recommendations' => $this->getRecommendations($urgencyLevel),
            'doctor_recommended' => $doctorRecommended,
        ];
    }

    private function determineUrgencyLevel(array $symptoms): string
    {
        $emergencySymptoms = ['chest pain', 'difficulty breathing', 'severe bleeding', 'unconsciousness', 'stroke'];
        $highUrgencySymptoms = ['severe pain', 'high fever', 'persistent vomiting', 'severe back pain', 'numbness'];
        $mediumUrgencySymptoms = ['fever', 'pain', 'nausea', 'back pain', 'headache'];

        foreach ($symptoms as $symptom) {
            $lowerSymptom = strtolower($symptom);
            
            foreach ($emergencySymptoms as $emergency) {
                if (strpos($lowerSymptom, $emergency) !== false) {
                    return 'emergency';
                }
            }
            
            foreach ($highUrgencySymptoms as $high) {
                if (strpos($lowerSymptom, $high) !== false) {
                    return 'high';
                }
            }
            
            foreach ($mediumUrgencySymptoms as $medium) {
                if (strpos($lowerSymptom, $medium) !== false) {
                    return 'medium';
                }
            }
        }

        return 'low';
    }

    private function getPossibleConditions(array $symptoms): array
    {
        $primarySymptom = strtolower($symptoms[0] ?? '');
        $allSymptoms = implode(' ', array_map('strtolower', $symptoms));
        
        if (strpos($primarySymptom, 'back pain') !== false || strpos($primarySymptom, 'back') !== false) {
            return [
                'Mechanical back strain (85% of cases) - Overuse or sudden movement causing muscle/ligament injury',
                'Lumbar disc herniation (5-10%) - Disc material pressing on nerve roots, often with leg pain',
                'Facet joint dysfunction - Inflammation of spinal joints, worse with extension movements',
                'Sciatica/radiculopathy - Nerve compression causing pain radiating down the leg',
                'Spinal stenosis - Narrowing of spinal canal, more common in older adults',
                'Kidney stones or pyelonephritis - If pain is in flank area with urinary symptoms',
                'Ankylosing spondylitis - Inflammatory condition, morning stiffness, young adults'
            ];
        }
        
        if (strpos($primarySymptom, 'headache') !== false) {
            return [
                'Tension-type headache (90% of headaches) - Bilateral, band-like pressure, stress-related',
                'Migraine without aura (15-20% prevalence) - Unilateral, throbbing, with nausea/photophobia',
                'Cervicogenic headache - Originating from neck problems, unilateral, neck stiffness',
                'Medication overuse headache - From frequent analgesic use (>10 days/month)',
                'Cluster headache (rare) - Severe unilateral pain, seasonal pattern, autonomic symptoms',
                'Secondary headache - Sinusitis, hypertension, or intracranial pathology',
                'Dehydration headache - Bilateral, improves with fluid intake'
            ];
        }
        
        if (strpos($allSymptoms, 'fever') !== false) {
            return [
                'Viral upper respiratory infection - Most common cause of fever with respiratory symptoms',
                'Bacterial infection - Streptococcal pharyngitis, pneumonia, or urinary tract infection',
                'Influenza - Seasonal viral infection with systemic symptoms and high fever',
                'COVID-19 - SARS-CoV-2 infection with variable presentation and potential complications',
                'Gastroenteritis - Viral or bacterial, if accompanied by GI symptoms',
                'Drug fever - Medication-induced hyperthermia, consider recent medication changes'
            ];
        }
        
        // Multi-symptom analysis
        if (count($symptoms) > 2) {
            return [
                'Viral syndrome - Multiple non-specific symptoms suggesting viral infection',
                'Systemic inflammatory condition - Autoimmune or rheumatologic disorder',
                'Medication side effects - Consider recent medication changes or interactions',
                'Stress-related somatic symptoms - Physical manifestation of psychological stress',
                'Early presentation of serious condition - Requires careful monitoring and evaluation',
                'Metabolic disorder - Thyroid dysfunction, diabetes, or electrolyte imbalance'
            ];
        }
        
        return [
            'Symptom-specific differential diagnosis requires detailed clinical evaluation',
            'Multiple conditions can present with similar symptom patterns',
            'Professional medical assessment needed for accurate diagnosis and appropriate testing',
            'Consider both common and serious causes based on symptom severity and progression'
        ];
    }

    private function getGeneralAdvice(array $symptoms): string
    {
        $primarySymptom = strtolower($symptoms[0] ?? '');
        $symptomCount = count($symptoms);
        
        if (strpos($primarySymptom, 'back pain') !== false) {
            return "Back pain management requires a multi-faceted approach. Apply ice for 15-20 minutes every 2-3 hours during the first 48 hours to reduce inflammation, then switch to heat therapy to promote healing. Take over-the-counter anti-inflammatory medications like ibuprofen (400-600mg every 6-8 hours with food) or acetaminophen (500-1000mg every 6 hours). Gentle movement is crucial - avoid prolonged bed rest as it can worsen the condition. Perform gentle stretching exercises when tolerable, focusing on hamstring and hip flexor stretches.";
        }
        
        if (strpos($primarySymptom, 'headache') !== false) {
            return "Headache management should address both immediate relief and underlying triggers. Rest in a quiet, darkened room to minimize sensory stimulation. Maintain adequate hydration by drinking 8-10 glasses of water throughout the day, as dehydration is a common headache trigger. Apply a cold compress to the forehead or back of the neck for 15-20 minutes. Consider over-the-counter pain relievers: acetaminophen (500-1000mg) or ibuprofen (400-600mg), but avoid overuse which can cause rebound headaches.";
        }
        
        if (strpos($primarySymptom, 'fever') !== false) {
            return "Fever management focuses on comfort and monitoring. Stay well-hydrated with clear fluids, electrolyte solutions, or warm broths. Rest is essential for immune system recovery. Use acetaminophen (500-1000mg every 6 hours) or ibuprofen (400-600mg every 6-8 hours) to reduce fever and discomfort. Monitor temperature every 4-6 hours and watch for concerning symptoms like difficulty breathing, persistent high fever over 103°F, or signs of dehydration.";
        }
        
        if ($symptomCount > 1) {
            return "With multiple symptoms present, it's important to monitor the overall pattern and progression. Keep a detailed symptom diary noting onset, severity (1-10 scale), duration, and any triggering factors. Stay well-hydrated, get adequate rest, and avoid known triggers. The combination of symptoms you're experiencing warrants careful observation and may require professional medical evaluation to determine the underlying cause and appropriate treatment approach.";
        }
        
        return "Symptom management requires careful monitoring and appropriate self-care measures. Document symptom patterns, including timing, severity, and any associated factors. Maintain good hydration, adequate rest, and avoid activities that worsen symptoms. While self-care measures can provide relief, persistent or worsening symptoms warrant professional medical evaluation for proper diagnosis and treatment planning.";
    }

    private function getRecommendations(string $urgencyLevel): string
    {
        switch ($urgencyLevel) {
            case 'emergency':
                return '🚨 EMERGENCY: Seek immediate medical attention. Call emergency services (911) or go to the nearest emergency room immediately. Do not delay treatment.';
            case 'high':
                return '⚠️ HIGH PRIORITY: Contact your healthcare provider immediately or visit urgent care within 24 hours. This requires prompt medical evaluation.';
            case 'medium':
                return '📅 MODERATE: Schedule an appointment with your healthcare provider within the next 2-3 days. Monitor symptoms and seek earlier care if they worsen.';
            default:
                return '👀 MONITOR: Keep track of your symptoms. Consider consulting with a healthcare provider if symptoms persist beyond a few days, worsen, or if you develop new concerning symptoms.';
        }
    }

    private function getSymptomSummary(array $symptoms, ?string $description): string
    {
        $primarySymptom = $symptoms[0] ?? 'symptoms';
        $count = count($symptoms);
        
        if ($count > 1) {
            return "You're experiencing {$primarySymptom} along with " . ($count - 1) . " other symptom(s). " . 
                   ($description ? "Based on your description: \"" . substr($description, 0, 100) . "...\"" : "");
        }
        
        return "You're experiencing {$primarySymptom}. " . 
               ($description ? "Based on your description: \"" . substr($description, 0, 100) . "...\"" : "");
    }

    private function getImmediateCareAdvice(array $symptoms): array
    {
        $primarySymptom = strtolower($symptoms[0] ?? '');
        
        if (strpos($primarySymptom, 'back pain') !== false) {
            return [
                '🧊 Apply ice for 15-20 minutes every 2-3 hours for first 48 hours',
                '🔥 After 48 hours, switch to heat therapy',
                '💊 Over-the-counter pain relievers (ibuprofen, acetaminophen)',
                '🚶 Gentle movement - avoid bed rest',
                '🧘 Gentle stretching when pain allows'
            ];
        }
        
        if (strpos($primarySymptom, 'headache') !== false) {
            return [
                '🌙 Rest in a quiet, dark room',
                '💧 Stay hydrated - drink plenty of water',
                '🧊 Apply cold compress to forehead or neck',
                '💊 Over-the-counter pain relievers if needed',
                '😌 Practice relaxation techniques'
            ];
        }
        
        return [
            '😴 Get adequate rest',
            '💧 Stay well hydrated',
            '🌡️ Monitor your symptoms',
            '📝 Keep a symptom diary',
            '☎️ Contact healthcare provider if symptoms worsen'
        ];
    }

    private function getWarningSigns(array $symptoms): array
    {
        $primarySymptom = strtolower($symptoms[0] ?? '');
        
        if (strpos($primarySymptom, 'back pain') !== false) {
            return [
                '🚨 Numbness or tingling in legs',
                '🚨 Weakness in legs or feet',
                '🚨 Loss of bladder or bowel control',
                '🚨 Severe pain that doesn\'t improve with rest',
                '🚨 Pain after significant injury or trauma',
                '🚨 Fever with back pain'
            ];
        }
        
        if (strpos($primarySymptom, 'headache') !== false) {
            return [
                '🚨 Sudden, severe headache unlike any before',
                '🚨 Headache with fever and stiff neck',
                '🚨 Headache with vision changes',
                '🚨 Headache with confusion or difficulty speaking',
                '🚨 Headache after head injury',
                '🚨 Progressively worsening headache'
            ];
        }
        
        return [
            '🚨 Symptoms rapidly worsening',
            '🚨 Difficulty breathing',
            '🚨 Chest pain',
            '🚨 High fever (over 103°F)',
            '🚨 Severe pain',
            '🚨 Signs of infection'
        ];
    }
}
