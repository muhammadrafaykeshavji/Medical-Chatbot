<?php

namespace App\Http\Controllers;

use App\Models\HealthPlan;
use App\Services\OpenAIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class HealthPlanController extends Controller
{
    private OpenAIService $aiService;

    public function __construct(OpenAIService $aiService)
    {
        $this->aiService = $aiService;
    }
    public function index()
    {
        $healthPlans = Auth::user()->healthPlans()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('health-plans.index', compact('healthPlans'));
    }

    public function create()
    {
        return view('health-plans.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'goals' => 'required|array|min:1',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'nullable|date|after:start_date',
        ]);

        // Generate personalized plan based on goals using AI
        $planData = $this->generateAIHealthPlan($request->goals, $request->all());

        $healthPlan = HealthPlan::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'goals' => $request->goals,
            'daily_activities' => $planData['daily_activities'],
            'weekly_activities' => $planData['weekly_activities'],
            'dietary_recommendations' => $planData['dietary_recommendations'],
            'exercise_plan' => $planData['exercise_plan'],
            'health_targets' => $planData['health_targets'],
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => 'active',
        ]);

        return redirect()->route('health-plans.show', $healthPlan)
            ->with('success', 'Health plan created successfully!');
    }

    public function show(HealthPlan $healthPlan)
    {
        // Ensure user owns this health plan
        if ($healthPlan->user_id !== Auth::id()) {
            abort(403);
        }

        return view('health-plans.show', compact('healthPlan'));
    }

    public function edit(HealthPlan $healthPlan)
    {
        // Ensure user owns this health plan
        if ($healthPlan->user_id !== Auth::id()) {
            abort(403);
        }

        return view('health-plans.edit', compact('healthPlan'));
    }

    public function update(Request $request, HealthPlan $healthPlan)
    {
        // Ensure user owns this health plan
        if ($healthPlan->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,completed,paused',
            'notes' => 'nullable|string',
        ]);

        $healthPlan->update($request->only(['title', 'description', 'status', 'notes']));

        return redirect()->route('health-plans.show', $healthPlan)
            ->with('success', 'Health plan updated successfully!');
    }

    private function generateAIHealthPlan($goals, $userData)
    {
        try {
            // Create a comprehensive prompt for AI health plan generation
            $prompt = $this->buildHealthPlanPrompt($goals, $userData);
            
            // Get AI-generated health plan
            $aiResponse = $this->aiService->analyzeText($prompt);
            
            // Parse the AI response into structured data
            if (isset($aiResponse['ai_analysis']) && !empty($aiResponse['ai_analysis'])) {
                $parsedPlan = $this->parseAIHealthPlan($aiResponse['ai_analysis']);
                if (!empty($parsedPlan)) {
                    return $parsedPlan;
                }
            }
            
            Log::info('AI health plan generation failed, using fallback', ['goals' => $goals]);
            
        } catch (\Exception $e) {
            Log::error('AI health plan generation error: ' . $e->getMessage());
        }
        
        // Fallback to template-based generation if AI fails
        return $this->generateHealthPlan($goals, $userData);
    }

    private function buildHealthPlanPrompt($goals, $userData)
    {
        $goalsText = implode(', ', $goals);
        $description = $userData['description'] ?? '';
        $title = $userData['title'] ?? '';
        
        // Create goal-specific context
        $goalContext = $this->getGoalSpecificContext($goals);
        
        return "You are an expert fitness coach, nutritionist, and wellness specialist creating a comprehensive, personalized health plan.

**CLIENT PROFILE:**
- Primary Goals: {$goalsText}
- Plan Title: {$title}
- Additional Context: {$description}
- Goal-Specific Focus: {$goalContext}

Create a detailed, actionable health plan that is:
- Evidence-based and scientifically sound
- Progressively structured for sustainable results
- Tailored to the specific goals mentioned
- Realistic and achievable for most fitness levels
- Comprehensive covering all aspects of health

**DAILY_ACTIVITIES:**
- Provide 6-8 specific daily habits and activities
- Include morning routines, nutrition timing, hydration goals
- Add mindfulness/wellness practices
- Include progress tracking activities
- Make each activity specific with measurable outcomes
- Consider goal-specific daily requirements

**WEEKLY_ACTIVITIES:**
- List 5-6 weekly routines and planning activities
- Include meal prep, progress assessments, and planning sessions
- Add variety activities to prevent boredom
- Include social/community aspects of wellness
- Focus on sustainability and long-term adherence
- Include recovery and rest planning

**DIETARY_RECOMMENDATIONS:**
- Provide 7-8 comprehensive dietary guidelines
- Include macronutrient ratios specific to goals
- Add meal timing and portion control strategies
- Include hydration recommendations
- Add supplement suggestions if appropriate
- Consider goal-specific nutritional needs
- Include practical meal planning tips

**EXERCISE_PLAN:**
- Create a detailed 7-day exercise schedule
- Include specific exercises, sets, reps, and duration
- Balance cardio, strength training, flexibility, and recovery
- Progress from beginner to intermediate levels
- Include alternative exercises for variety
- Match intensity and volume to specific goals
- Add warm-up and cool-down routines

**HEALTH_TARGETS:**
- List 6-8 specific, measurable health outcomes
- Include short-term (1-4 weeks) and long-term (3-6 months) targets
- Make targets SMART (Specific, Measurable, Achievable, Relevant, Time-bound)
- Include both physical and mental health metrics
- Align directly with stated goals
- Include progress milestones and checkpoints

Format your response with clear section headers exactly as shown above (use ** for bold headers). Be highly specific, include numbers/measurements where possible, and ensure all recommendations are safe, practical, and evidence-based. Focus on creating a plan that will genuinely help achieve the stated goals.";
    }
    
    private function getGoalSpecificContext($goals)
    {
        $contexts = [];
        
        if (in_array('weight_loss', $goals)) {
            $contexts[] = 'Caloric deficit, fat loss, metabolic health';
        }
        if (in_array('muscle_gain', $goals)) {
            $contexts[] = 'Protein synthesis, progressive overload, muscle hypertrophy';
        }
        if (in_array('fitness', $goals)) {
            $contexts[] = 'Cardiovascular health, overall conditioning, functional movement';
        }
        if (in_array('endurance', $goals)) {
            $contexts[] = 'Aerobic capacity, stamina building, cardiovascular efficiency';
        }
        if (in_array('flexibility', $goals)) {
            $contexts[] = 'Mobility, range of motion, injury prevention';
        }
        if (in_array('stress_management', $goals)) {
            $contexts[] = 'Mental wellness, cortisol regulation, relaxation techniques';
        }
        if (in_array('nutrition', $goals)) {
            $contexts[] = 'Nutritional optimization, healthy eating habits, meal planning';
        }
        if (in_array('sleep_quality', $goals)) {
            $contexts[] = 'Sleep hygiene, circadian rhythm, recovery optimization';
        }
        if (in_array('general_wellness', $goals)) {
            $contexts[] = 'Holistic health, lifestyle balance, preventive care';
        }
        
        return implode(', ', $contexts);
    }

    private function parseAIHealthPlan($aiText)
    {
        $sections = [
            'daily_activities' => [],
            'weekly_activities' => [],
            'dietary_recommendations' => [],
            'exercise_plan' => [],
            'health_targets' => []
        ];
        
        $currentSection = '';
        $lines = explode("\n", $aiText);
        
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;
            
            // Clean up asterisks and formatting
            $line = preg_replace('/\*+/', '', $line);
            $line = trim($line);
            if (empty($line)) continue;
            
            // Detect section headers
            if (stripos($line, 'DAILY_ACTIVITIES') !== false || stripos($line, 'DAILY ACTIVITIES') !== false) {
                $currentSection = 'daily_activities';
                continue;
            } elseif (stripos($line, 'WEEKLY_ACTIVITIES') !== false || stripos($line, 'WEEKLY ACTIVITIES') !== false) {
                $currentSection = 'weekly_activities';
                continue;
            } elseif (stripos($line, 'DIETARY_RECOMMENDATIONS') !== false || stripos($line, 'DIETARY RECOMMENDATIONS') !== false) {
                $currentSection = 'dietary_recommendations';
                continue;
            } elseif (stripos($line, 'EXERCISE_PLAN') !== false || stripos($line, 'EXERCISE PLAN') !== false) {
                $currentSection = 'exercise_plan';
                continue;
            } elseif (stripos($line, 'HEALTH_TARGETS') !== false || stripos($line, 'HEALTH TARGETS') !== false) {
                $currentSection = 'health_targets';
                continue;
            }
            
            // Add content to current section
            if (!empty($currentSection)) {
                // Handle bullet points
                if (strpos($line, '-') === 0 || strpos($line, '•') === 0 || strpos($line, '*') === 0) {
                    $cleanLine = ltrim($line, '-•* ');
                    $cleanLine = trim($cleanLine);
                    if (!empty($cleanLine)) {
                        $sections[$currentSection][] = $cleanLine;
                    }
                } 
                // Handle numbered lists
                elseif (preg_match('/^\d+\.?\s*(.+)$/', $line, $matches)) {
                    $cleanLine = trim($matches[1]);
                    if (!empty($cleanLine)) {
                        $sections[$currentSection][] = $cleanLine;
                    }
                }
                // Handle regular content lines (not headers)
                elseif (!empty($line) && 
                        !stripos($line, 'CLIENT PROFILE') && 
                        !stripos($line, 'Create a detailed') &&
                        !stripos($line, 'Format your response') &&
                        strlen($line) > 10) {
                    $sections[$currentSection][] = $line;
                }
            }
        }
        
        // Clean up sections and ensure minimum content
        foreach ($sections as $key => $section) {
            // Remove duplicates and empty entries
            $sections[$key] = array_unique(array_filter($section, function($item) {
                return !empty(trim($item)) && strlen(trim($item)) > 5;
            }));
            
            // Ensure we have at least some content in each section
            if (count($sections[$key]) < 2) {
                Log::warning("Insufficient content in section: {$key}", ['content' => $sections[$key]]);
                // Don't return empty - let fallback handle individual sections
            }
        }
        
        // Return parsed sections even if some are incomplete - fallback will fill gaps
        return $sections;
    }

    private function generateHealthPlan($goals, $userData)
    {
        $planTemplates = [
            'weight_loss' => [
                'daily_activities' => [
                    'Drink 8 glasses of water',
                    '30 minutes of cardio exercise',
                    'Track calorie intake',
                    'Take 10,000 steps',
                    'Eat 5 servings of fruits/vegetables'
                ],
                'weekly_activities' => [
                    'Strength training (3x per week)',
                    'Meal prep on Sundays',
                    'Weekly weigh-in',
                    'Review progress and adjust plan'
                ],
                'dietary_recommendations' => [
                    'Reduce caloric intake by 500 calories/day',
                    'Focus on lean proteins',
                    'Increase fiber intake',
                    'Limit processed foods',
                    'Eat smaller, frequent meals'
                ],
                'exercise_plan' => [
                    'Monday: 30 min cardio + 20 min strength',
                    'Tuesday: 45 min walking/jogging',
                    'Wednesday: 30 min cardio + 20 min strength',
                    'Thursday: Rest or light yoga',
                    'Friday: 30 min cardio + 20 min strength',
                    'Saturday: 60 min outdoor activity',
                    'Sunday: Rest or gentle stretching'
                ],
                'health_targets' => [
                    'Lose 1-2 pounds per week',
                    'Reduce body fat percentage',
                    'Improve cardiovascular fitness',
                    'Build lean muscle mass'
                ]
            ],
            'fitness' => [
                'daily_activities' => [
                    '45 minutes of exercise',
                    'Protein intake with each meal',
                    'Stay hydrated throughout day',
                    'Get 7-8 hours of sleep',
                    'Track workout progress'
                ],
                'weekly_activities' => [
                    'Strength training (4x per week)',
                    'Cardio sessions (3x per week)',
                    'Flexibility/mobility work',
                    'Assess and adjust workout intensity'
                ],
                'dietary_recommendations' => [
                    'Increase protein intake to 1g per lb body weight',
                    'Eat complex carbohydrates pre-workout',
                    'Post-workout protein within 30 minutes',
                    'Include healthy fats in diet',
                    'Time meals around workouts'
                ],
                'exercise_plan' => [
                    'Monday: Upper body strength training',
                    'Tuesday: HIIT cardio',
                    'Wednesday: Lower body strength training',
                    'Thursday: Steady-state cardio',
                    'Friday: Full body strength training',
                    'Saturday: Active recovery or sports',
                    'Sunday: Rest or yoga'
                ],
                'health_targets' => [
                    'Increase muscle mass',
                    'Improve strength by 20%',
                    'Enhance endurance',
                    'Better body composition'
                ]
            ],
            'general_wellness' => [
                'daily_activities' => [
                    '30 minutes of physical activity',
                    'Eat balanced meals',
                    'Practice mindfulness/meditation',
                    'Maintain good posture',
                    'Limit screen time before bed'
                ],
                'weekly_activities' => [
                    'Meal planning and prep',
                    'Social activities with friends/family',
                    'Outdoor activities',
                    'Health check-ins and journaling'
                ],
                'dietary_recommendations' => [
                    'Follow Mediterranean diet principles',
                    'Eat variety of colorful foods',
                    'Limit sugar and processed foods',
                    'Include omega-3 rich foods',
                    'Practice portion control'
                ],
                'exercise_plan' => [
                    'Mix of cardio and strength training',
                    'Daily walks or bike rides',
                    'Yoga or stretching sessions',
                    'Recreational sports or activities',
                    'Flexibility and balance work'
                ],
                'health_targets' => [
                    'Maintain healthy weight',
                    'Improve overall energy levels',
                    'Better sleep quality',
                    'Reduced stress levels'
                ]
            ]
        ];

        // Determine primary goal and return corresponding plan
        $primaryGoal = 'general_wellness';
        if (in_array('weight_loss', $goals)) {
            $primaryGoal = 'weight_loss';
        } elseif (in_array('fitness', $goals) || in_array('muscle_gain', $goals)) {
            $primaryGoal = 'fitness';
        }

        return $planTemplates[$primaryGoal];
    }
}
