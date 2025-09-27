<?php

namespace App\Http\Controllers;

use App\Models\SymptomCheck;
use App\Services\GeminiAIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class SymptomCheckerController extends Controller
{
    private GeminiAIService $geminiService;

    public function __construct(GeminiAIService $geminiService)
    {
        $this->geminiService = $geminiService;
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
        $request->validate([
            'symptoms' => 'required|array|min:1',
            'symptoms.*' => 'string|max:100',
            'description' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();

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
    }

    public function show(SymptomCheck $symptomCheck)
    {
        // Ensure user owns this symptom check
        if ($symptomCheck->user_id !== Auth::id()) {
            abort(403);
        }

        return view('symptom-checker.show', compact('symptomCheck'));
    }

    private function analyzeSymptoms(array $symptoms, string $description = ''): array
    {
        // Create a prompt for symptom analysis
        $systemPrompt = "You are a medical AI assistant. Analyze the following symptoms and provide:
        1. Possible conditions (general information only)
        2. Urgency level (low, medium, high, emergency)
        3. Recommendations for care
        4. Whether to see a doctor (true/false)
        
        IMPORTANT: Always recommend seeing a healthcare professional for proper diagnosis. This is for informational purposes only.
        
        Format your response as JSON with keys: analysis, urgency_level, recommendations, doctor_recommended";

        $userInput = "Symptoms: " . implode(', ', $symptoms);
        if ($description) {
            $userInput .= "\nDescription: " . $description;
        }

        try {
            // For now, we'll create a simple analysis
            // In a real implementation, you would call the Gemini AI service
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
        } catch (\Exception $e) {
            return [
                'analysis' => ['error' => 'Unable to analyze symptoms at this time'],
                'urgency_level' => 'medium',
                'recommendations' => 'Please consult with a healthcare professional for proper evaluation.',
                'doctor_recommended' => true,
            ];
        }
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
        
        if (strpos($primarySymptom, 'back pain') !== false || strpos($primarySymptom, 'back') !== false) {
            return [
                'Muscle strain or sprain (most common cause)',
                'Poor posture or ergonomic issues',
                'Herniated or bulging disc',
                'Sciatica (nerve compression)',
                'Kidney stones or infection (if flank pain)',
                'Arthritis or degenerative changes'
            ];
        }
        
        if (strpos($primarySymptom, 'headache') !== false) {
            return [
                'Tension headache (most common)',
                'Migraine',
                'Dehydration',
                'Eye strain',
                'Sinus congestion',
                'Stress or lack of sleep'
            ];
        }
        
        return [
            'Multiple conditions could cause these symptoms',
            'Professional medical evaluation needed for accurate diagnosis',
            'Symptom patterns help determine underlying cause'
        ];
    }

    private function getGeneralAdvice(array $symptoms): string
    {
        $primarySymptom = strtolower($symptoms[0] ?? '');
        
        if (strpos($primarySymptom, 'back pain') !== false) {
            return 'For back pain: Apply ice for first 48 hours, then heat. Gentle stretching, over-the-counter pain relievers, and avoiding bed rest can help. Seek immediate care if you experience numbness, tingling, or weakness in legs.';
        }
        
        if (strpos($primarySymptom, 'headache') !== false) {
            return 'For headaches: Rest in a quiet, dark room. Stay hydrated, apply cold compress to forehead. Over-the-counter pain relievers may help. Seek immediate care for sudden, severe headaches or those with fever, stiff neck, or vision changes.';
        }
        
        return 'Monitor your symptoms carefully. Note any changes in severity, duration, or new symptoms. This analysis is for informational purposes only and should not replace professional medical advice.';
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

    private function getSymptomSummary(array $symptoms, string $description): string
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
