<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class OpenAIService
{
    private Client $client;
    private string $apiKey;
    private string $model;
    private string $apiBase;

    public function __construct()
    {
        $this->client = new Client([
            'verify' => false, // Disable SSL verification for local development
            'timeout' => 60, // Increased timeout to 60 seconds
            'connect_timeout' => 10, // Connection timeout
            'read_timeout' => 60, // Read timeout
            'http_errors' => false, // Do not throw on non-2xx; we'll handle gracefully
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);
        $this->apiKey = config('services.openai.api_key');
        $this->model = config('services.openai.model', 'openai/gpt-5');
        $this->apiBase = 'https://openrouter.ai/api/v1';
    }

    /**
     * Generate AI response for medical chat
     */
    public function generateMedicalResponse(string $userMessage, array $context = [], string $chatType = 'medical'): array
    {
        // First check if API key is configured
        if (empty($this->apiKey)) {
            return $this->getFallbackResponse($userMessage, $chatType);
        }

        try {
            $systemPrompt = $this->getMedicalSystemPrompt($chatType);
            $conversationHistory = $this->formatConversationHistory($context);
            
            $messages = [
                ['role' => 'system', 'content' => $systemPrompt],
            ];
            
            // Add conversation history
            foreach ($context as $message) {
                $role = $message['sender_type'] === 'user' ? 'user' : 'assistant';
                $messages[] = [
                    'role' => $role,
                    'content' => $message['message']
                ];
            }
            
            // Add current user message
            $messages[] = ['role' => 'user', 'content' => $userMessage];

            $response = $this->client->post($this->apiBase . '/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => $this->model,
                    'messages' => $messages,
                    'temperature' => 0.6,
                    'max_tokens' => (int) (config('services.openai.max_tokens', 2048)),
                ]
            ]);

            $raw = (string) $response->getBody();
            $data = json_decode($raw, true);
            
            // If API returned an error block but not an exception, fallback gracefully
            if (!$response->getStatusCode() || $response->getStatusCode() >= 400) {
                Log::error('OpenAI API HTTP Error: ' . $response->getStatusCode() . ' Body: ' . $raw);
                $fallback = $this->getFallbackResponse($userMessage, $chatType);
                $fallback['metadata'] = array_merge($fallback['metadata'] ?? [], [
                    'model' => $this->model . '-fallback-http',
                    'http_status' => $response->getStatusCode(),
                    'http_body' => mb_substr($raw, 0, 500),
                    'timestamp' => now(),
                ]);
                return $fallback;
            }
            
            if (isset($data['choices'][0]['message']['content'])) {
                return [
                    'success' => true,
                    'response' => $data['choices'][0]['message']['content'],
                    'metadata' => [
                        'model' => $this->model,
                        'timestamp' => now(),
                    ]
                ];
            }

            return [
                'success' => true,
                'response' => 'I\'m sorry, I couldn\'t generate a full response just now. Here\'s some general guidance based on your message, and you can ask follow-up details if needed.\n\n' . $this->getFallbackResponse($userMessage, $chatType)['response'],
                'metadata' => [
                    'model' => $this->model . '-fallback-empty',
                    'timestamp' => now(),
                ]
            ];

        } catch (RequestException $e) {
            Log::error('OpenAI API Error: ' . $e->getMessage());
            Log::error('OpenAI API Response: ' . $e->getResponse()?->getBody());
            
            // Check if it's an API key issue
            if ($e->getCode() === 401 || strpos($e->getMessage(), 'API key') !== false || strpos($e->getMessage(), 'Unauthorized') !== false) {
                return $this->getFallbackResponse($userMessage, $chatType);
            }
            
            // For other API errors, also use fallback
            return $this->getFallbackResponse($userMessage, $chatType);
        }
    }

    public function analyzeText(string $prompt): array
    {
        if (empty($this->apiKey)) {
            return [
                'ai_analysis' => 'Unable to analyze - API key not configured. Please configure your OpenAI API key to enable text analysis.',
                'summary' => 'Text analysis unavailable - API configuration needed',
                'key_findings' => ['API key not configured'],
                'recommendations' => ['Configure OpenAI API key', 'Consult healthcare provider for report interpretation'],
                'urgency' => 'medium',
                'next_steps' => 'Contact system administrator to configure API access'
            ];
        }

        try {
            $result = $this->client->post($this->apiBase . '/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => $this->model,
                    'messages' => [
                        [
                            'role' => 'system', 
                            'content' => 'You are an expert medical AI assistant analyzing medical reports and documents. Provide comprehensive, structured analysis following the exact format requested. Be thorough, accurate, and include both technical medical details and patient-friendly explanations. Always include proper medical disclaimers.'
                        ],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'temperature' => 0.4,
                    'max_tokens' => 1500,
                ],
            ]);
            
            $statusCode = $result->getStatusCode();
            $responseBody = (string) $result->getBody();
            
            if ($statusCode >= 400) {
                Log::error("OpenAI Text API Error - Status: {$statusCode}, Body: " . substr($responseBody, 0, 500));
                return [
                    'ai_analysis' => 'Text analysis failed due to API error. Status: ' . $statusCode,
                    'summary' => 'Unable to analyze text due to technical issues',
                    'key_findings' => ['API request failed', 'Technical error occurred'],
                    'recommendations' => ['Try again later', 'Consult healthcare provider for report interpretation'],
                    'urgency' => 'medium',
                    'next_steps' => 'Retry analysis or seek professional medical evaluation'
                ];
            }
            
            $data = json_decode($responseBody, true);
            
            if (isset($data['error'])) {
                Log::error('OpenAI Text API Error: ' . json_encode($data['error']));
                return [
                    'ai_analysis' => 'API Error: ' . ($data['error']['message'] ?? 'Unknown error'),
                    'summary' => 'Text analysis failed',
                    'key_findings' => ['API error: ' . ($data['error']['type'] ?? 'unknown')],
                    'recommendations' => ['Check API configuration', 'Try again later'],
                    'urgency' => 'low',
                    'next_steps' => 'Retry or consult healthcare provider'
                ];
            }
            
            $analysisText = $data['choices'][0]['message']['content'] ?? '';
            
            if (empty($analysisText)) {
                return [
                    'ai_analysis' => 'No analysis content received from API',
                    'summary' => 'Empty response from text analysis',
                    'key_findings' => ['No content generated'],
                    'recommendations' => ['Try uploading document again', 'Ensure document contains readable medical text'],
                    'urgency' => 'low',
                    'next_steps' => 'Retry with different document or consult healthcare provider'
                ];
            }
            
            // Return the full analysis text for proper parsing
            return ['ai_analysis' => $analysisText];
            
        } catch (RequestException $e) {
            Log::error('OpenAI Text Analysis Error: ' . $e->getMessage());
            Log::error('OpenAI Text Analysis Response: ' . ($e->getResponse() ? $e->getResponse()->getBody() : 'No response'));
            
            return [
                'ai_analysis' => 'Text analysis failed: ' . $e->getMessage(),
                'summary' => 'Technical error during text analysis',
                'key_findings' => ['Network or API error occurred'],
                'recommendations' => ['Check internet connection', 'Try again later', 'Consult healthcare provider'],
                'urgency' => 'medium',
                'next_steps' => 'Retry analysis or seek professional medical evaluation'
            ];
        }
    }

    public function analyzeImage(string $imageBase64, string $mimeType, string $prompt): array
    {
        if (empty($this->apiKey)) {
            return [
                'ai_analysis' => 'Unable to analyze image - API key not configured. Please configure your OpenAI API key to enable image analysis.',
                'summary' => 'Image analysis unavailable - API configuration needed',
                'key_findings' => ['API key not configured'],
                'recommendations' => ['Configure OpenAI API key', 'Consult healthcare provider for image interpretation'],
                'urgency' => 'medium',
                'next_steps' => 'Contact system administrator to configure API access'
            ];
        }

        try {
            // Use GPT-4 Vision model for image analysis (more reliable than GPT-5)
            $visionModel = 'openai/gpt-4-vision-preview';
            $dataUri = 'data:' . $mimeType . ';base64,' . $imageBase64;
            
            $result = $this->client->post($this->apiBase . '/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => $visionModel,
                    'messages' => [
                        [
                            'role' => 'system', 
                            'content' => 'You are an expert medical AI assistant analyzing medical images. Provide comprehensive, structured analysis following the exact format requested. Be thorough, accurate, and include both technical medical details and patient-friendly explanations.'
                        ],
                        [
                            'role' => 'user',
                            'content' => [
                                [
                                    'type' => 'text', 
                                    'text' => $prompt . '\n\nPlease provide a detailed analysis following the structured format. Include specific observations, measurements if visible, and both technical and patient-friendly explanations. IMPORTANT: This analysis is for informational purposes only and should not replace professional medical evaluation.'
                                ],
                                [
                                    'type' => 'image_url', 
                                    'image_url' => [
                                        'url' => $dataUri,
                                        'detail' => 'high'
                                    ]
                                ],
                            ],
                        ],
                    ],
                    'temperature' => 0.3,
                    'max_tokens' => 1500,
                ],
            ]);
            
            $statusCode = $result->getStatusCode();
            $responseBody = (string) $result->getBody();
            
            if ($statusCode >= 400) {
                Log::error("OpenAI Vision API Error - Status: {$statusCode}, Body: " . substr($responseBody, 0, 500));
                return [
                    'ai_analysis' => 'Image analysis failed due to API error. Status: ' . $statusCode,
                    'summary' => 'Unable to analyze image due to technical issues',
                    'key_findings' => ['API request failed', 'Technical error occurred'],
                    'recommendations' => ['Try again later', 'Consult healthcare provider for image interpretation'],
                    'urgency' => 'medium',
                    'next_steps' => 'Retry analysis or seek professional medical evaluation'
                ];
            }
            
            $data = json_decode($responseBody, true);
            
            if (isset($data['error'])) {
                Log::error('OpenAI Vision API Error: ' . json_encode($data['error']));
                return [
                    'ai_analysis' => 'API Error: ' . ($data['error']['message'] ?? 'Unknown error'),
                    'summary' => 'Image analysis failed',
                    'key_findings' => ['API error: ' . ($data['error']['type'] ?? 'unknown')],
                    'recommendations' => ['Check API configuration', 'Try again later'],
                    'urgency' => 'low',
                    'next_steps' => 'Retry or consult healthcare provider'
                ];
            }
            
            $analysisText = $data['choices'][0]['message']['content'] ?? '';
            
            if (empty($analysisText)) {
                return [
                    'ai_analysis' => 'No analysis content received from API',
                    'summary' => 'Empty response from image analysis',
                    'key_findings' => ['No content generated'],
                    'recommendations' => ['Try uploading image again', 'Ensure image is clear and medical in nature'],
                    'urgency' => 'low',
                    'next_steps' => 'Retry with different image or consult healthcare provider'
                ];
            }
            
            // Return the full analysis text for proper parsing
            return ['ai_analysis' => $analysisText];
            
        } catch (RequestException $e) {
            Log::error('OpenAI Image Analysis Exception: ' . $e->getMessage());
            Log::error('OpenAI Image Analysis Response: ' . ($e->getResponse() ? $e->getResponse()->getBody() : 'No response'));
            
            return [
                'ai_analysis' => 'Image analysis failed: ' . $e->getMessage(),
                'summary' => 'Technical error during image analysis',
                'key_findings' => ['Network or API error occurred'],
                'recommendations' => ['Check internet connection', 'Try again later', 'Consult healthcare provider'],
                'urgency' => 'medium',
                'next_steps' => 'Retry analysis or seek professional medical evaluation'
            ];
        }
    }

    /**
     * Get medical system prompt based on chat type
     */
    private function getMedicalSystemPrompt(string $chatType = 'medical'): string
    {
        $basePrompt = "You are an intelligent Medical AI Assistant. Your role is to provide comprehensive medical and health-related assistance while filtering out non-medical questions.

CORE RESPONSIBILITIES:
- Answer ALL types of medical and health-related questions
- Provide general health information, wellness guidance, and lifestyle advice
- Help with symptom discussion and evaluation (ask clarifying questions when needed)
- Offer evidence-based health advice and self-care suggestions
- Explain medical concepts in simple, understandable terms
- Suggest over-the-counter remedies with proper safety information
- Provide preventive care tips and wellness strategies

MEDICAL QUESTION FILTERING:
- ONLY respond to medical, health, wellness, fitness, nutrition, mental health, and healthcare-related questions
- For NON-MEDICAL questions, respond with: \"I'm a medical AI assistant and can only help with health and medical questions. Please ask me about symptoms, health advice, medical information, wellness, or healthcare topics.\"

SAFETY PROTOCOLS:
- NEVER diagnose specific conditions or diseases
- NEVER recommend prescription medications
- Always include proper disclaimers about professional medical advice
- Recommend immediate medical attention for emergency symptoms
- Include dosage limits and safety warnings for OTC suggestions

EMERGENCY RED FLAGS (immediate medical attention needed):
- Severe chest pain, difficulty breathing, or signs of heart attack/stroke
- High fever with severe symptoms, signs of serious infection
- Severe bleeding, trauma, or suspected fractures
- Sudden severe headache, vision changes, or neurological symptoms
- Signs of severe allergic reactions or poisoning

COMMUNICATION STYLE:
- Be empathetic, supportive, and professional
- Use clear, simple language that patients can understand
- Provide evidence-based information when possible
- Be comprehensive but concise in responses
- Always maintain a caring, helpful tone

CRITICAL DISCLAIMER: This is not a substitute for professional medical advice, diagnosis, or treatment. Always consult qualified healthcare professionals for medical concerns, persistent symptoms, or before making significant health decisions.";

        // Customize based on chat type
        switch ($chatType) {
            case 'general':
                return $basePrompt . "\n\nFOCUS: Provide general health information, wellness guidance, and lifestyle advice. Emphasize health education and preventive care.";
            
            case 'symptom_check':
                return $basePrompt . "\n\nFOCUS: Help with symptom discussion by asking clarifying questions about onset, duration, severity, location, and triggers. Provide structured summaries and guidance on when to seek professional care.";
            
            case 'health_advice':
                return $basePrompt . "\n\nFOCUS: Offer evidence-based health advice tailored to the user's situation. Include lifestyle recommendations, self-care measures, OTC options with safety notes, and clear guidance on when professional care is needed.";
            
            default:
                return $basePrompt;
        }
    }

    /**
     * Format conversation history for context
     */
    private function formatConversationHistory(array $context): string
    {
        if (empty($context)) {
            return "This is the start of the conversation.";
        }

        $history = "";
        foreach ($context as $message) {
            $sender = $message['sender_type'] === 'user' ? 'User' : 'Assistant';
            $history .= "{$sender}: {$message['message']}\n";
        }

        return $history;
    }

    /**
     * Provide helpful fallback responses when API is unavailable
     */
    private function getFallbackResponse(string $userMessage, string $chatType = 'general'): array
    {
        $message = strtolower($userMessage);
        $trimmed = trim($message);

        // URGENT SAFETY TRIAGE (always check first)
        if (
            strpos($message, 'chest pain') !== false ||
            strpos($message, 'shortness of breath') !== false ||
            strpos($message, 'trouble breathing') !== false ||
            strpos($message, 'can\'t breathe') !== false ||
            strpos($message, 'face droop') !== false ||
            strpos($message, 'stroke') !== false ||
            strpos($message, 'slurred speech') !== false ||
            strpos($message, 'weakness on one side') !== false ||
            strpos($message, 'severe bleeding') !== false ||
            strpos($message, 'unconscious') !== false ||
            strpos($message, 'not waking') !== false
        ) {
            return [
                'success' => true,
                'response' => "This could be an emergency. Please seek immediate medical care now.\n\n🚑 Emergency symptoms can include chest pain, severe trouble breathing, signs of stroke (face drooping, arm weakness, speech difficulty), severe bleeding, confusion, fainting, or not waking. Call your local emergency number right away or go to the nearest emergency department.",
                'metadata' => ['source' => 'fallback', 'type' => 'emergency']
            ];
        }
        
        // Headache responses
        if (strpos($message, 'headache') !== false || strpos($message, 'head pain') !== false) {
            return [
                'success' => true,
                'response' => 'I understand you\'re experiencing a headache. Here\'s some general guidance:\n\n🌡️ **Immediate Relief:**\n- Rest in a quiet, dark room\n- Apply a cold compress to your forehead or back of neck\n- Stay hydrated - drink plenty of water\n- Try gentle neck and shoulder stretches\n\n💊 **Over-the-counter options:**\n- Acetaminophen (Tylenol) or ibuprofen (Advil) as directed on package\n- Follow dosage instructions carefully\n\n⚠️ **Seek immediate medical attention if:**\n- Sudden, severe headache unlike any you\'ve had before\n- Headache with fever, stiff neck, or rash\n- Headache after a head injury\n- Vision changes or difficulty speaking\n- Headache with confusion or weakness\n\nPlease consult a healthcare professional if headaches persist, worsen, or if you\'re concerned. This is general information only.',
                'metadata' => ['source' => 'fallback', 'type' => 'headache']
            ];
        }

        // Check if the question appears to be medical/health-related
        $medicalKeywords = ['health', 'medical', 'symptom', 'pain', 'doctor', 'medicine', 'treatment', 'disease', 'illness', 'wellness', 'fitness', 'nutrition', 'diet', 'exercise', 'sleep', 'stress', 'mental', 'physical', 'body', 'hospital', 'clinic', 'therapy', 'medication', 'prescription', 'diagnosis', 'condition', 'infection', 'fever', 'headache', 'stomach', 'chest', 'breathing', 'heart', 'blood', 'pressure', 'diabetes', 'cancer', 'allergy', 'vaccine', 'vitamin', 'supplement', 'injury', 'wound', 'skin', 'rash', 'cough', 'cold', 'flu', 'nausea', 'vomiting', 'diarrhea', 'constipation', 'fatigue', 'tired', 'energy', 'weight', 'pregnancy', 'birth', 'baby', 'child', 'elderly', 'senior', 'aging'];
        
        $isMedical = false;
        foreach ($medicalKeywords as $keyword) {
            if (strpos($message, $keyword) !== false) {
                $isMedical = true;
                break;
            }
        }
        
        // If not medical, return filter message
        if (!$isMedical) {
            return [
                'success' => true,
                'response' => 'I am a medical AI assistant and can only help with health and medical topics (symptoms, health advice, medical information, wellness, nutrition, fitness, mental health). Please rephrase your question to a medical topic and I will be happy to help.',
                'metadata' => ['source' => 'fallback', 'type' => 'non_medical_filter']
            ];
        }

        // Default medical response
        return [
            'success' => true,
            'response' => 'I\'m here to help with your health question. While I\'m currently experiencing technical difficulties, I can provide some general guidance:\n\n**For any health concerns:**\n- Monitor your symptoms carefully\n- Stay hydrated and get adequate rest\n- Seek professional medical advice for persistent or worsening symptoms\n- Call emergency services for severe or life-threatening symptoms\n\n**This is general information only and not a substitute for professional medical advice.**\n\nPlease try your question again, and I\'ll do my best to provide more specific guidance.',
            'metadata' => ['source' => 'fallback', 'type' => 'general_medical']
        ];
    }

    private function wrapAnalysis(string $aiText): array
    {
        return [
            'summary' => substr($aiText, 0, 200) . (strlen($aiText) > 200 ? '...' : ''),
            'key_findings' => [
                'Medical report analyzed successfully',
                'AI analysis completed',
                'Professional interpretation recommended'
            ],
            'recommendations' => [
                'Consult with healthcare provider for detailed interpretation',
                'Keep this report for medical records',
                'Follow up as recommended by your doctor'
            ],
            'urgency' => 'low',
            'next_steps' => 'Discuss results with healthcare provider',
            'ai_analysis' => $aiText,
        ];
    }
}
