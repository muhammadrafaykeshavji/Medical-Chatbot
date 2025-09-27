<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class GeminiAIService
{
    private Client $client;
    private string $apiKey;
    private string $baseUrl;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = config('services.gemini.api_key');
        $this->baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent';
    }

    /**
     * Generate AI response for medical chat
     */
    public function generateMedicalResponse(string $userMessage, array $context = []): array
    {
        // First check if API key is configured
        if (empty($this->apiKey)) {
            return $this->getFallbackResponse($userMessage);
        }

        try {
            $systemPrompt = $this->getMedicalSystemPrompt();
            $conversationHistory = $this->formatConversationHistory($context);
            
            $payload = [
                'contents' => [
                    [
                        'parts' => [
                            [
                                'text' => $systemPrompt . "\n\nConversation History:\n" . $conversationHistory . "\n\nUser: " . $userMessage
                            ]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.7,
                    'topK' => 40,
                    'topP' => 0.95,
                    'maxOutputTokens' => 1024,
                ]
            ];

            $response = $this->client->post($this->baseUrl . '?key=' . $this->apiKey, [
                'json' => $payload,
                'headers' => [
                    'Content-Type' => 'application/json',
                ]
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            
            if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                return [
                    'success' => true,
                    'response' => $data['candidates'][0]['content']['parts'][0]['text'],
                    'metadata' => [
                        'model' => 'gemini-pro',
                        'timestamp' => now(),
                    ]
                ];
            }

            return [
                'success' => false,
                'error' => 'No response generated',
                'response' => 'I apologize, but I\'m having trouble generating a response right now. Please try again.'
            ];

        } catch (RequestException $e) {
            Log::error('Gemini AI API Error: ' . $e->getMessage());
            Log::error('Gemini AI API Response: ' . $e->getResponse()?->getBody());
            
            // Check if it's an API key issue
            if ($e->getCode() === 403 || strpos($e->getMessage(), 'API key') !== false || strpos($e->getMessage(), 'API_KEY_INVALID') !== false) {
                return $this->getFallbackResponse($userMessage);
            }
            
            // For other API errors, also use fallback
            return $this->getFallbackResponse($userMessage);
        }
    }

    /**
     * Get medical system prompt
     */
    private function getMedicalSystemPrompt(): string
    {
        return "You are a helpful medical AI assistant. Your role is to provide general health information and guidance. 

IMPORTANT DISCLAIMERS:
- You are not a replacement for professional medical advice
- Always recommend consulting healthcare professionals for serious concerns
- Do not provide specific diagnoses or treatment recommendations
- Focus on general health education and wellness guidance

Guidelines:
- Be empathetic and supportive
- Provide evidence-based information when possible
- Suggest when to seek immediate medical attention
- Encourage healthy lifestyle choices
- Ask clarifying questions when needed";
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
    private function getFallbackResponse(string $userMessage): array
    {
        $message = strtolower($userMessage);
        
        // Headache responses
        if (strpos($message, 'headache') !== false || strpos($message, 'head pain') !== false) {
            return [
                'success' => true,
                'response' => 'I understand you\'re experiencing a headache. Here\'s some general guidance:

🌡️ **Immediate Relief:**
- Rest in a quiet, dark room
- Apply a cold compress to your forehead or back of neck
- Stay hydrated - drink plenty of water
- Try gentle neck and shoulder stretches

💊 **Over-the-counter options:**
- Acetaminophen (Tylenol) or ibuprofen (Advil) as directed on package
- Follow dosage instructions carefully

⚠️ **Seek immediate medical attention if:**
- Sudden, severe headache unlike any you\'ve had before
- Headache with fever, stiff neck, or rash
- Headache after a head injury
- Vision changes or difficulty speaking
- Headache with confusion or weakness

Please consult a healthcare professional if headaches persist, worsen, or if you\'re concerned. This is general information only.',
                'metadata' => ['source' => 'fallback', 'type' => 'headache']
            ];
        }

        // Cold/Flu symptoms
        if (strpos($message, 'cold') !== false || strpos($message, 'flu') !== false || strpos($message, 'cough') !== false || strpos($message, 'sore throat') !== false) {
            return [
                'success' => true,
                'response' => 'For cold and flu symptoms, here\'s some general guidance:

🤧 **Symptom Relief:**
- Get plenty of rest and sleep
- Stay hydrated with water, warm broths, or herbal teas
- Use a humidifier or breathe steam from a hot shower
- Gargle with warm salt water for sore throat
- Consider throat lozenges or warm honey

💊 **Over-the-counter options:**
- Pain relievers like acetaminophen or ibuprofen for aches
- Decongestants for stuffy nose (follow package directions)
- Cough drops or cough medicine if needed

⚠️ **Seek medical attention if:**
- Symptoms worsen after a few days
- High fever (over 103°F/39.4°C)
- Difficulty breathing or shortness of breath
- Severe headache or sinus pain
- Symptoms last more than 10 days

Remember, antibiotics don\'t help with viral infections like common colds. This is general information only.',
                'metadata' => ['source' => 'fallback', 'type' => 'cold_flu']
            ];
        }

        // Fever responses
        if (strpos($message, 'fever') !== false || strpos($message, 'temperature') !== false) {
            return [
                'success' => true,
                'response' => 'For fever management, here\'s some general guidance:

🌡️ **General Care:**
- Rest and stay hydrated
- Drink plenty of fluids (water, clear broths, electrolyte solutions)
- Dress lightly and keep room temperature comfortable
- Monitor temperature regularly

💊 **Fever Reducers:**
- Acetaminophen or ibuprofen as directed on package
- Follow age-appropriate dosing guidelines

⚠️ **Seek medical attention if:**
- Fever over 103°F (39.4°C)
- Fever lasting more than 3 days
- Difficulty breathing or chest pain
- Severe headache or neck stiffness
- Persistent vomiting or signs of dehydration
- Any concerning symptoms

For infants under 3 months, contact a healthcare provider for any fever. This is general information only.',
                'metadata' => ['source' => 'fallback', 'type' => 'fever']
            ];
        }

        // Stomach/Digestive issues
        if (strpos($message, 'stomach') !== false || strpos($message, 'nausea') !== false || strpos($message, 'vomiting') !== false || strpos($message, 'diarrhea') !== false) {
            return [
                'success' => true,
                'response' => 'For stomach and digestive issues, here\'s some general guidance:

🤢 **Immediate Care:**
- Rest and avoid solid foods temporarily
- Stay hydrated with small, frequent sips of clear fluids
- Try the BRAT diet when ready: Bananas, Rice, Applesauce, Toast
- Avoid dairy, caffeine, alcohol, and fatty foods

💧 **Hydration is key:**
- Water, clear broths, or electrolyte solutions
- Avoid sugary drinks which can worsen diarrhea
- Ice chips or popsicles if having trouble keeping fluids down

⚠️ **Seek medical attention if:**
- Severe dehydration (dizziness, dry mouth, little/no urination)
- Blood in vomit or stool
- High fever with stomach symptoms
- Severe abdominal pain
- Symptoms persist more than 24-48 hours
- Signs of severe dehydration

This is general information only. Consult a healthcare professional for persistent or severe symptoms.',
                'metadata' => ['source' => 'fallback', 'type' => 'digestive']
            ];
        }

        // Pain/Injury
        if (strpos($message, 'pain') !== false || strpos($message, 'hurt') !== false || strpos($message, 'injury') !== false || strpos($message, 'sprain') !== false) {
            return [
                'success' => true,
                'response' => 'For pain and minor injuries, here\'s some general guidance:

🧊 **RICE Method (for sprains/strains):**
- **Rest:** Avoid activities that cause pain
- **Ice:** Apply for 15-20 minutes every 2-3 hours for first 48 hours
- **Compression:** Use elastic bandage (not too tight)
- **Elevation:** Raise injured area above heart level when possible

💊 **Pain Management:**
- Over-the-counter pain relievers (acetaminophen, ibuprofen) as directed
- Follow package instructions for dosage
- Don\'t exceed recommended amounts

⚠️ **Seek immediate medical attention if:**
- Severe pain that doesn\'t improve with rest and medication
- Inability to move or bear weight on injured area
- Numbness, tingling, or loss of sensation
- Signs of infection (increased redness, warmth, swelling, pus)
- Suspected fracture or serious injury

This is general information only. For persistent or severe pain, consult a healthcare professional.',
                'metadata' => ['source' => 'fallback', 'type' => 'pain']
            ];
        }

        // General health response
        return [
            'success' => true,
            'response' => 'Thank you for reaching out about your health concern. While I\'m currently experiencing some technical difficulties with my AI service, I want to provide you with some general guidance:

🏥 **General Health Tips:**
- Monitor your symptoms and note any changes
- Stay hydrated and get adequate rest
- Maintain good hygiene practices
- Follow a balanced diet when possible

⚠️ **When to seek medical attention:**
- Symptoms are severe, persistent, or worsening
- You have concerning symptoms like difficulty breathing, chest pain, or severe pain
- You\'re unsure about the severity of your condition
- You have underlying health conditions

🩺 **Important reminders:**
- Trust your instincts about your health
- When in doubt, consult a healthcare professional
- For emergencies, call emergency services immediately
- This is general information and not a substitute for professional medical advice

I encourage you to consult with a healthcare professional for personalized advice about your specific situation.',
            'metadata' => ['source' => 'fallback', 'type' => 'general']
        ];
    }
}
