<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class GeminiAIService
{
    private Client $client;
    private string $apiKey;
    private string $model;
    private string $apiBase;
    private string $apiVersion;

    public function __construct()
    {
        $this->client = new Client([
            'verify' => false, // Disable SSL verification for local development (Guzzle level)
            'timeout' => 30,
            'http_errors' => false, // Do not throw on non-2xx; we'll handle gracefully
            'curl' => [
                CURLOPT_SSL_VERIFYPEER => false, // cURL layer verify off
                CURLOPT_SSL_VERIFYHOST => 0,
            ],
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);
        $this->apiKey = config('services.gemini.api_key');
        // Use a widely available default; can be overridden via env
        $this->model = config('services.gemini.model', 'gemini-1.5-flash-latest');
        $this->apiVersion = config('services.gemini.api_version', 'v1');
        $this->apiBase = 'https://generativelanguage.googleapis.com/' . $this->apiVersion . '/models/';
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
                    'temperature' => 0.6,
                    'topK' => 40,
                    'topP' => 0.95,
                    'maxOutputTokens' => (int) (config('services.gemini.max_tokens', 2048)),
                ]
            ];

            $endpoint = $this->apiBase . $this->model . ':generateContent?key=' . $this->apiKey;
            $response = $this->client->post($endpoint, [
                'json' => $payload,
                'headers' => [
                    'Content-Type' => 'application/json',
                ]
            ]);

            $raw = (string) $response->getBody();
            $data = json_decode($raw, true);
            
            // If API returned an error block but not an exception, fallback gracefully
            if (!$response->getStatusCode() || $response->getStatusCode() >= 400) {
                Log::error('Gemini AI API HTTP Error: ' . $response->getStatusCode() . ' Body: ' . $raw);
                // If the model is not found/unsupported, retry once with a known compatible model
                if ($response->getStatusCode() === 404 || (isset($data['error']['code']) && (int)$data['error']['code'] === 404)) {
                    $fallbackModel = 'gemini-1.5-flash-latest';
                    // Try same model with "-latest" suffix if not present
                    $latestModel = str_ends_with($this->model, '-latest') ? $this->model : ($this->model . '-latest');
                    if ($latestModel !== $this->model) {
                        $latestUrl = $this->apiBase . $latestModel + ':generateContent?key=' . $this->apiKey;
                        $latestTry = $this->client->post($latestUrl, [
                            'json' => $payload,
                            'headers' => [
                                'Content-Type' => 'application/json',
                            ]
                        ]);
                        $latestRaw = (string) $latestTry->getBody();
                        $latestData = json_decode($latestRaw, true);
                        if ($latestTry->getStatusCode() < 400 && isset($latestData['candidates'][0]['content']['parts'][0]['text'])) {
                            return [
                                'success' => true,
                                'response' => $latestData['candidates'][0]['content']['parts'][0]['text'],
                                'metadata' => [
                                    'model' => $latestModel,
                                    'timestamp' => now(),
                                    'note' => 'auto-suffix-latest',
                                ]
                            ];
                        }
                        Log::error('Gemini AI -latest retry failed: ' . $latestTry->getStatusCode() . ' Body: ' . $latestRaw);
                    }
                    // If current API version is v1beta, first attempt switching to v1 with same model
                    if ($this->apiVersion !== 'v1') {
                        $v1Url = 'https://generativelanguage.googleapis.com/v1/models/' . $this->model . ':generateContent?key=' . $this->apiKey;
                        $v1Try = $this->client->post($v1Url, [
                            'json' => $payload,
                            'headers' => [
                                'Content-Type' => 'application/json',
                            ]
                        ]);
                        $v1Raw = (string) $v1Try->getBody();
                        $v1Data = json_decode($v1Raw, true);
                        if ($v1Try->getStatusCode() < 400 && isset($v1Data['candidates'][0]['content']['parts'][0]['text'])) {
                            return [
                                'success' => true,
                                'response' => $v1Data['candidates'][0]['content']['parts'][0]['text'],
                                'metadata' => [
                                    'model' => $this->model,
                                    'timestamp' => now(),
                                    'note' => 'auto-version-fallback-to-v1',
                                ]
                            ];
                        }
                        Log::error('Gemini AI v1 retry failed: ' . $v1Try->getStatusCode() . ' Body: ' . $v1Raw);
                    }
                    if ($this->model !== $fallbackModel) {
                        $retryUrl = $this->apiBase . $fallbackModel . ':generateContent?key=' . $this->apiKey;
                        $retry = $this->client->post($retryUrl, [
                            'json' => $payload,
                            'headers' => [
                                'Content-Type' => 'application/json',
                            ]
                        ]);
                        $retryRaw = (string) $retry->getBody();
                        $retryData = json_decode($retryRaw, true);
                        if ($retry->getStatusCode() < 400 && isset($retryData['candidates'][0]['content']['parts'][0]['text'])) {
                            return [
                                'success' => true,
                                'response' => $retryData['candidates'][0]['content']['parts'][0]['text'],
                                'metadata' => [
                                    'model' => $fallbackModel,
                                    'timestamp' => now(),
                                    'note' => 'auto-model-fallback',
                                ]
                            ];
                        }
                        // Include retry diagnostics in metadata if it also failed
                        Log::error('Gemini AI retry failed: ' . $retry->getStatusCode() . ' Body: ' . $retryRaw);
                    }
                }
                $fallback = $this->getFallbackResponse($userMessage, $chatType);
                $fallback['metadata'] = array_merge($fallback['metadata'] ?? [], [
                    'model' => $this->model . '-fallback-http',
                    'http_status' => $response->getStatusCode(),
                    'http_body' => mb_substr($raw, 0, 500),
                    'timestamp' => now(),
                ]);
                return $fallback;
            }
            
            if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                return [
                    'success' => true,
                    'response' => $data['candidates'][0]['content']['parts'][0]['text'],
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
            Log::error('Gemini AI API Error: ' . $e->getMessage());
            Log::error('Gemini AI API Response: ' . $e->getResponse()?->getBody());
            
            // Check if it's an API key issue
            if ($e->getCode() === 403 || strpos($e->getMessage(), 'API key') !== false || strpos($e->getMessage(), 'API_KEY_INVALID') !== false) {
                return $this->getFallbackResponse($userMessage, $chatType);
            }
            
            // For other API errors, also use fallback
            return $this->getFallbackResponse($userMessage, $chatType);
        }
    }

    /**
     * Get medical system prompt
     */
    private function getMedicalSystemPrompt(string $chatType = 'medical'): string
    {
        return "You are an intelligent Medical AI Assistant. Your role is to provide comprehensive medical and health-related assistance while filtering out non-medical questions.

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

RESPONSE APPROACH:
- For symptoms: Ask clarifying questions (onset, duration, severity, triggers) and provide general guidance
- For health advice: Give comprehensive lifestyle recommendations and self-care strategies
- For medical information: Provide educational content in simple terms
- For wellness: Share preventive care tips and healthy lifestyle guidance

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
                'response' => "This could be an emergency. Please seek immediate medical care now.

🚑 Emergency symptoms can include chest pain, severe trouble breathing, signs of stroke (face drooping, arm weakness, speech difficulty), severe bleeding, confusion, fainting, or not waking. Call your local emergency number right away or go to the nearest emergency department.",
                'metadata' => ['source' => 'fallback', 'type' => 'emergency']
            ];
        }
        
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

        // Cold/Flu/Respiratory symptoms
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

        // Asthma / Wheeze / Shortness of breath (non-emergent)
        if (strpos($message, 'asthma') !== false || strpos($message, 'wheeze') !== false || (strpos($message, 'shortness of breath') !== false && strpos($message, 'severe') === false)) {
            return [
                'success' => true,
                'response' => 'Breathing issues can be concerning. Here are general steps:

🫁 Airway care:
- Sit upright, focus on slow, steady breaths
- Use your prescribed rescue inhaler (albuterol) if you have one, as directed
- Avoid known triggers (smoke, allergens, cold air, exertion)

📈 Monitor:
- Track symptoms, peak flow if applicable
- Seek urgent care if symptoms worsen or rescue inhaler is needed more often than usual

⚠️ Seek immediate care if: severe breathlessness, lips/face turning blue, cannot speak full sentences, or symptoms not improving with rescue inhaler.

This is general information only.',
                'metadata' => ['source' => 'fallback', 'type' => 'respiratory']
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

        // Constipation
        if (strpos($message, 'constipation') !== false || strpos($message, 'hard stool') !== false) {
            return [
                'success' => true,
                'response' => 'For constipation, consider:

🧴 Habits & diet:
- Increase fiber gradually (fruits, vegetables, whole grains)
- Hydrate well throughout the day
- Regular physical activity
- Don’t ignore the urge to go

💊 OTC options (follow labels):
- Bulk-forming fiber (psyllium)
- Stool softeners (docusate)
- Osmotic laxatives (polyethylene glycol) as directed

⚠️ Seek care if: severe abdominal pain, vomiting, blood in stool, weight loss, or symptoms persist >1–2 weeks. This is general information only.',
                'metadata' => ['source' => 'fallback', 'type' => 'constipation']
            ];
        }

        // Heartburn / GERD
        if (strpos($message, 'heartburn') !== false || strpos($message, 'acid reflux') !== false || strpos($message, 'gerd') !== false) {
            return [
                'success' => true,
                'response' => 'For heartburn/acid reflux:

🍽️ Lifestyle:
- Smaller meals, avoid late-night eating
- Limit triggers: spicy/fatty foods, chocolate, caffeine, alcohol, mint
- Elevate head of bed for nighttime symptoms
- Maintain healthy weight

💊 OTC options (follow labels):
- Antacids (calcium carbonate)
- H2 blockers (famotidine)
- Short courses of PPIs (esomeprazole/omeprazole) as directed

⚠️ Seek care if: trouble swallowing, weight loss, vomiting blood, black stools, chest pain, or persistent symptoms. This is general information only.',
                'metadata' => ['source' => 'fallback', 'type' => 'gerd']
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

        // Neurology red flags: thunderclap/worst headache, focal deficits, seizures
        if (strpos($message, 'thunderclap') !== false || strpos($message, 'worst headache') !== false || strpos($message, 'focal weakness') !== false || strpos($message, 'numbness on one side') !== false || strpos($message, 'facial droop') !== false || strpos($message, 'seizure') !== false) {
            return [
                'success' => true,
                'response' => 'Neurology red flags:

⚠️ Seek urgent care immediately for: thunderclap/worst-ever headache, new focal weakness or numbness on one side, facial droop, trouble speaking, new seizures, severe headache with neck stiffness/fever, or head injury with concerning symptoms. These may require emergency evaluation. This is general information only.',
                'metadata' => ['source' => 'fallback', 'type' => 'neuro_red_flags']
            ];
        }

        // Cardiology red flags: exertional chest pain, radiation, syncope
        if (strpos($message, 'exertional chest pain') !== false || strpos($message, 'chest pain on exertion') !== false || strpos($message, 'radiating to arm') !== false || strpos($message, 'radiates to jaw') !== false || strpos($message, 'syncope') !== false || strpos($message, 'passed out') !== false) {
            return [
                'success' => true,
                'response' => 'Cardiac warning signs:

⚠️ Exertional chest pain/pressure (especially with radiation to arm/jaw/back), shortness of breath, nausea/sweats, or syncope (passing out) can indicate urgent heart issues. Seek emergency care now. This is general information only.',
                'metadata' => ['source' => 'fallback', 'type' => 'cardio_red_flags']
            ];
        }

        // GI bleed indicators: melena, hematemesis, hematochezia
        if (strpos($message, 'black tarry stool') !== false || strpos($message, 'melena') !== false || strpos($message, 'vomiting blood') !== false || strpos($message, 'hematemesis') !== false || strpos($message, 'bright red blood in stool') !== false || strpos($message, 'hematochezia') !== false) {
            return [
                'success' => true,
                'response' => 'Possible gastrointestinal bleeding:

⚠️ Black, tarry stools (melena), vomiting blood (hematemesis), or bright red blood in stool (hematochezia) can be signs of GI bleeding. Seek urgent medical evaluation. Avoid NSAIDs/alcohol and maintain hydration if safe. This is general information only.',
                'metadata' => ['source' => 'fallback', 'type' => 'gi_bleed']
            ];
        }

        // Back/neck pain
        if (strpos($message, 'back pain') !== false || strpos($message, 'neck pain') !== false || strpos($message, 'sciatica') !== false) {
            return [
                'success' => true,
                'response' => 'For back/neck pain:

🧘‍♂️ Self-care:
- Relative rest; avoid heavy lifting/twisting initially
- Gentle stretching and gradual return to activity
- Heat/ice based on comfort

💊 OTC pain relief:
- Acetaminophen or ibuprofen as labeled; avoid exceeding max doses

⚠️ Seek care if: numbness/weakness, bowel/bladder issues, fever, unexplained weight loss, severe or persistent pain. This is general information only.',
                'metadata' => ['source' => 'fallback', 'type' => 'musculoskeletal']
            ];
        }

        // Skin: rash/acne/eczema
        if (strpos($message, 'rash') !== false || strpos($message, 'acne') !== false || strpos($message, 'eczema') !== false || strpos($message, 'psoriasis') !== false || strpos($message, 'hives') !== false) {
            return [
                'success' => true,
                'response' => 'Skin concerns—general care:

🧴 Basics:
- Gentle cleansing; avoid harsh scrubbing
- Moisturize regularly (fragrance-free)
- Identify and avoid triggers (fragrances, new detergents, known allergens)

💊 OTC options (follow labels):
- Hydrocortisone 1% cream for mild itchy/inflamed rashes (short term)
- Antihistamines for itch (e.g., cetirizine) if appropriate
- Acne: benzoyl peroxide or salicylic acid products

⚠️ Seek care if: spreading rapidly, signs of infection (pus, warmth), severe pain, fever, or if affecting eyes/face significantly. This is general information only.',
                'metadata' => ['source' => 'fallback', 'type' => 'dermatology']
            ];
        }

        // Eye issues: pink eye, red eye, irritation, floaters/flashers, vision loss
        if (strpos($message, 'pink eye') !== false || strpos($message, 'conjunctivitis') !== false || strpos($message, 'red eye') !== false || strpos($message, 'eye irritation') !== false || strpos($message, 'itchy eyes') !== false || strpos($message, 'floaters') !== false || strpos($message, 'flashes') !== false || strpos($message, 'vision loss') !== false || strpos($message, 'blurry vision') !== false) {
            return [
                'success' => true,
                'response' => 'Eye irritation/pink eye general care:

👁️ Self-care:
- Wash hands often; avoid touching/rubbing eyes
- Use clean warm compresses to soothe discomfort
- Artificial tears for dryness/irritation
- Avoid contact lenses until symptoms resolve

⚠️ Seek care if: severe pain, light sensitivity, pus-like discharge, injury/chemical exposure, or symptoms persist/worsen. Sudden new floaters/flashers or a curtain/shadow of vision, or sudden vision loss can be an emergency—seek urgent ophthalmologic evaluation. This is general information only.',
                'metadata' => ['source' => 'fallback', 'type' => 'eye']
            ];
        }

        // Ear/Sinus: earache, ear infection, sinusitis
        if (strpos($message, 'earache') !== false || strpos($message, 'ear pain') !== false || strpos($message, 'sinus') !== false || strpos($message, 'sinusitis') !== false || strpos($message, 'congestion') !== false || strpos($message, 'pressure in face') !== false) {
            return [
                'success' => true,
                'response' => 'Ear or sinus discomfort:

👂/👃 Self-care:
- Warm compress to ear/face; rest and hydration
- Saline nasal rinses; humidifier/steam inhalation
- OTC pain relievers as labeled; decongestants/antihistamines if appropriate

🩺 Helpful clues:
- Otitis externa (ear canal/"swimmer’s ear"): worse with pulling outer ear, canal tenderness, recent water exposure
- Otitis media (middle ear): deeper pain/pressure with recent URI, possible fever, reduced hearing

⚠️ Seek care if: high fever, severe pain, hearing loss, fluid draining from ear, swelling/redness behind ear, persistent symptoms >7–10 days, or severe facial pain. This is general information only.',
                'metadata' => ['source' => 'fallback', 'type' => 'ear_sinus']
            ];
        }

        // Dental: toothache, gum swelling, abscess
        if (strpos($message, 'toothache') !== false || strpos($message, 'tooth pain') !== false || strpos($message, 'gum swelling') !== false || strpos($message, 'dental') !== false || strpos($message, 'abscess') !== false) {
            return [
                'success' => true,
                'response' => 'Dental/tooth pain:

🦷 Self-care (short-term):
- Rinse with warm salt water; keep area clean
- Cold compress outside cheek for swelling/discomfort
- OTC pain relievers as labeled (avoid placing aspirin on gums)

⚠️ Seek urgent dental care for suspected abscess (throbbing pain, gum swelling, bad taste, fever), facial swelling, spreading redness, difficulty swallowing/breathing, trauma, or persistent severe pain. Dental infections can become serious. This is general information only.',
                'metadata' => ['source' => 'fallback', 'type' => 'dental']
            ];
        }

        // COVID-19 and similar respiratory viral illness
        if (strpos($message, 'covid') !== false || strpos($message, 'coronavirus') !== false || strpos($message, 'loss of taste') !== false || strpos($message, 'loss of smell') !== false) {
            return [
                'success' => true,
                'response' => 'COVID-19 guidance (general):

🧪 Testing/isolation guidance can change—check local public health recommendations.
🏠 Rest, hydration, fever reducers as labeled; monitor symptoms.
😷 Consider masking around others; improve ventilation.

⚠️ Seek urgent care if: trouble breathing, chest pain, confusion, blue lips/face, or dehydration. High-risk individuals should discuss antivirals promptly. This is general information only.',
                'metadata' => ['source' => 'fallback', 'type' => 'covid']
            ];
        }

        // Urinary / UTI
        if (strpos($message, 'uti') !== false || strpos($message, 'urinary') !== false || strpos($message, 'burning urination') !== false || strpos($message, 'pee pain') !== false || strpos($message, 'dysuria') !== false) {
            return [
                'success' => true,
                'response' => 'Possible urinary tract issues:

🚰 Self-care:
- Hydrate well; avoid bladder irritants (caffeine, alcohol)
- Consider urinary pain relief products as directed (phenazopyridine short term)

📌 Note: Antibiotics require clinician evaluation.

⚠️ Seek care if: fever, flank/back pain, nausea/vomiting, blood in urine, pregnancy, or symptoms persist/worsen. This is general information only.',
                'metadata' => ['source' => 'fallback', 'type' => 'urinary']
            ];
        }

        // Sexual health / STIs
        if (strpos($message, 'sti') !== false || strpos($message, 'std') !== false || strpos($message, 'unprotected sex') !== false || strpos($message, 'exposure') !== false) {
            return [
                'success' => true,
                'response' => 'Sexual health guidance:

🧪 Testing & prevention:
- Seek prompt STI testing after potential exposure per guidelines
- Use barrier protection consistently
- Discuss vaccinations (HPV, hepatitis B) with your clinician

⚠️ Seek care if: genital ulcers, discharge, pelvic/testicular pain, fever, or new severe symptoms. This is general information only.',
                'metadata' => ['source' => 'fallback', 'type' => 'sexual_health']
            ];
        }

        // Allergies
        if (strpos($message, 'allergy') !== false || strpos($message, 'allergic') !== false || strpos($message, 'hay fever') !== false) {
            return [
                'success' => true,
                'response' => 'For allergies:

🌼 Avoid triggers when possible; keep indoor air clean.
💊 OTC options (follow labels): non-drowsy antihistamines (cetirizine, loratadine), intranasal steroids (fluticasone), saline rinses.
⚠️ Seek immediate care for trouble breathing, tongue/lip swelling, or severe reactions. Carry prescribed epinephrine if indicated. This is general information only.',
                'metadata' => ['source' => 'fallback', 'type' => 'allergy']
            ];
        }

        // Diabetes / blood sugar
        if (strpos($message, 'diabetes') !== false || strpos($message, 'blood sugar') !== false || strpos($message, 'glucose') !== false || strpos($message, 'a1c') !== false || strpos($message, 'hypoglycemia') !== false) {
            return [
                'success' => true,
                'response' => 'Blood sugar basics:

🍎 Lifestyle:
- Balanced diet emphasizing whole foods; monitor carbohydrate portions
- Regular physical activity per ability and clinician advice
- Monitor glucose as directed; keep a log

⛑️ Low blood sugar (hypoglycemia) treatment if on insulin/sulfonylurea:
- 15g fast carbs (glucose tabs/juice), recheck in 15 minutes; repeat if needed; then a snack

⚠️ Seek urgent care for severe symptoms (confusion, seizures), very high sugars with vomiting, or signs of ketoacidosis. This is general information only.',
                'metadata' => ['source' => 'fallback', 'type' => 'diabetes']
            ];
        }

        // Blood pressure / Hypertension
        if (strpos($message, 'blood pressure') !== false || strpos($message, 'hypertension') !== false || strpos($message, 'high bp') !== false) {
            return [
                'success' => true,
                'response' => 'Blood pressure guidance:

📏 Monitoring:
- Check BP correctly (seated, back supported, feet on floor, arm at heart level)
- Keep a log to share with your clinician

🏃 Lifestyle:
- Reduce salt, maintain healthy weight, exercise regularly, limit alcohol, avoid smoking, manage stress

⚠️ Seek care for: readings persistently ≥180/120 with symptoms (chest pain, breathlessness, neuro symptoms). This is general information only.',
                'metadata' => ['source' => 'fallback', 'type' => 'hypertension']
            ];
        }

        // Cholesterol / Lipids
        if (strpos($message, 'cholesterol') !== false || strpos($message, 'ldl') !== false || strpos($message, 'hdl') !== false || strpos($message, 'triglyceride') !== false) {
            return [
                'success' => true,
                'response' => 'Cholesterol basics:

🥗 Lifestyle:
- Emphasize fruits, vegetables, whole grains, lean proteins, healthy fats (olive oil, nuts)
- Reduce saturated/trans fats and refined sugars
- Regular aerobic exercise; maintain healthy weight

🧪 Discuss with your clinician about targets based on your risk profile. This is general information only.',
                'metadata' => ['source' => 'fallback', 'type' => 'cholesterol']
            ];
        }

        // Mental health: anxiety/depression/stress/panic
        if (strpos($message, 'anxiety') !== false || strpos($message, 'depression') !== false || strpos($message, 'panic') !== false || strpos($message, 'stress') !== false) {
            return [
                'success' => true,
                'response' => 'Mental health support:

🧠 Coping strategies:
- Breathing exercises, grounding techniques, regular routines, adequate sleep, physical activity
- Social support; consider therapy resources

📞 Urgent help:
- If you have thoughts of harming yourself or others, seek immediate help from local emergency services or crisis hotlines in your region.

This is general information only.',
                'metadata' => ['source' => 'fallback', 'type' => 'mental_health']
            ];
        }

        // Women’s health: pregnancy, breastfeeding, menstruation
        if (strpos($message, 'pregnan') !== false || strpos($message, 'prenatal') !== false || strpos($message, 'breastfeeding') !== false || strpos($message, 'lactation') !== false || strpos($message, 'period') !== false || strpos($message, 'menstrual') !== false || strpos($message, 'pcos') !== false || strpos($message, 'menopause') !== false) {
            return [
                'success' => true,
                'response' => 'Women’s health guidance (general):

🤰 Pregnancy/prenatal:
- Prenatal vitamins with folic acid; regular prenatal care; avoid alcohol/tobacco

🍼 Breastfeeding:
- Hydration, proper latch support, frequent feeds; consult lactation resources as needed

🩸 Menstrual cramps/PCOS/menopause:
- Heat, gentle exercise; discuss options with clinician

⚠️ Seek care for: severe abdominal pain, heavy bleeding, fever, dehydration, or concerning symptoms. This is general information only.',
                'metadata' => ['source' => 'fallback', 'type' => 'womens_health']
            ];
        }

        // Men’s health
        if (strpos($message, 'prostate') !== false || strpos($message, 'erectile') !== false || strpos($message, 'testosterone') !== false) {
            return [
                'success' => true,
                'response' => 'Men’s health (general):

🧩 Lifestyle foundations: sleep, exercise, balanced diet, stress management, avoid tobacco, moderate alcohol.
🧪 Discuss screening and treatment options with your clinician based on symptoms and age.

This is general information only.',
                'metadata' => ['source' => 'fallback', 'type' => 'mens_health']
            ];
        }

        // Pediatrics / baby / child
        if (strpos($message, 'baby') !== false || strpos($message, 'infant') !== false || strpos($message, 'toddler') !== false || strpos($message, 'child') !== false || strpos($message, 'pediatric') !== false) {
            return [
                'success' => true,
                'response' => 'Pediatric guidance (general):

🍼 Care basics:
- Hydration, nutrition, rest; follow age-appropriate dosing if any OTC recommended by clinician
- Keep up with vaccination schedules; monitor growth and development

⚠️ Seek urgent care for: lethargy, trouble breathing, dehydration, persistent high fever, rash with fever, or if you’re worried. This is general information only.',
                'metadata' => ['source' => 'fallback', 'type' => 'pediatrics']
            ];
        }

        // Vaccines / travel health
        if (strpos($message, 'vaccine') !== false || strpos($message, 'vaccination') !== false || strpos($message, 'travel health') !== false || strpos($message, 'travel shots') !== false) {
            return [
                'success' => true,
                'response' => 'Vaccination & travel health:

🧭 Check recommended vaccines for your age/conditions and travel destination via official guidelines; plan travel vaccines 4–6 weeks before departure when possible.
Discuss with your clinician about routine and travel immunizations. This is general information only.',
                'metadata' => ['source' => 'fallback', 'type' => 'vaccines_travel']
            ];
        }

        // Sleep
        if (strpos($message, 'sleep') !== false || strpos($message, 'insomnia') !== false) {
            return [
                'success' => true,
                'response' => 'Sleep hygiene tips:

🛏️ Keep consistent sleep/wake times; wind-down routine; dark, quiet, cool room; limit screens/caffeine late; regular exercise earlier in the day. Discuss persistent insomnia with your clinician. This is general information only.',
                'metadata' => ['source' => 'fallback', 'type' => 'sleep']
            ];
        }

        // Nutrition / weight / diet
        if (strpos($message, 'diet') !== false || strpos($message, 'nutrition') !== false || strpos($message, 'weight') !== false || strpos($message, 'lose weight') !== false) {
            return [
                'success' => true,
                'response' => 'Healthy nutrition & weight guidance:

🥗 Emphasize whole foods, vegetables, lean proteins, whole grains, healthy fats; watch portions; plan meals; stay hydrated; prioritize sleep and physical activity. Consider seeing a registered dietitian for personalized advice. This is general information only.',
                'metadata' => ['source' => 'fallback', 'type' => 'nutrition']
            ];
        }

        // Exercise / fitness
        if (strpos($message, 'exercise') !== false || strpos($message, 'workout') !== false || strpos($message, 'fitness') !== false) {
            return [
                'success' => true,
                'response' => 'Fitness basics:

🏃 Aim for a mix of aerobic, strength, mobility training; progress gradually; warm-up/cool-down; listen to your body; rest adequately; consult your clinician if you have medical conditions. This is general information only.',
                'metadata' => ['source' => 'fallback', 'type' => 'fitness']
            ];
        }

        // Medication safety/general dosing guidance (no prescriptions)
        if (strpos($message, 'medicine') !== false || strpos($message, 'medication') !== false || strpos($message, 'drug') !== false || strpos($message, 'pill') !== false || strpos($message, 'ibuprofen') !== false || strpos($message, 'acetaminophen') !== false || strpos($message, 'paracetamol') !== false) {
            return [
                'success' => true,
                'response' => 'General medication safety:

💊 Over-the-counter examples (follow label instructions):
- Acetaminophen (Tylenol/paracetamol): avoid exceeding max daily dose; caution with liver disease and combination products
- Ibuprofen/NSAIDs: take with food; avoid if certain kidney/stomach/bleeding risks

🔎 Interactions & conditions vary: check with your pharmacist or clinician for personalized advice.
⚠️ Do not start/stop prescription meds without medical guidance. This is general information only.',
                'metadata' => ['source' => 'fallback', 'type' => 'medications']
            ];
        }

        // Lab test basics
        if (strpos($message, 'lab') !== false || strpos($message, 'test result') !== false || strpos($message, 'blood test') !== false || strpos($message, 'cbc') !== false || strpos($message, 'metabolic panel') !== false || strpos($message, 'vitamin d') !== false || strpos($message, 'thyroid') !== false || strpos($message, 'tsh') !== false) {
            return [
                'success' => true,
                'response' => 'Understanding lab tests (general):

🧪 Ranges vary by lab and person. Typical examples:
- CBC: hemoglobin/hematocrit reflect red cells; white cells relate to infection/inflammation; platelets help with clotting
- Metabolic panel: electrolytes, kidney (BUN/creatinine), liver enzymes; fasting status matters for some labs
- Thyroid (TSH ± T4): screens thyroid function

Discuss your exact numbers and symptoms with your clinician for accurate interpretation. This is general information only.',
                'metadata' => ['source' => 'fallback', 'type' => 'labs']
            ];
        }

        // Renal red flags: edema, oliguria, flank pain with fever
        if (strpos($message, 'swollen legs') !== false || strpos($message, 'leg swelling') !== false || strpos($message, 'edema') !== false || strpos($message, 'less urine') !== false || strpos($message, 'oliguria') !== false || (strpos($message, 'flank pain') !== false && (strpos($message, 'fever') !== false || strpos($message, 'chills') !== false))) {
            return [
                'success' => true,
                'response' => 'Kidney warning signs:

⚠️ Edema (leg/face swelling), markedly decreased urine output, or flank pain with fever/chills can signal urgent kidney/urinary issues (e.g., pyelonephritis, obstruction). Seek prompt medical care. This is general information only.',
                'metadata' => ['source' => 'fallback', 'type' => 'renal_red_flags']
            ];
        }

        // Hepatic red flags: jaundice, RUQ pain, confusion (possible encephalopathy)
        if (strpos($message, 'jaundice') !== false || strpos($message, 'yellow eyes') !== false || strpos($message, 'yellow skin') !== false || strpos($message, 'ruq pain') !== false || strpos($message, 'right upper quadrant pain') !== false || strpos($message, 'confusion') !== false && (strpos($message, 'liver') !== false || strpos($message, 'cirrhosis') !== false || strpos($message, 'hepatitis') !== false)) {
            return [
                'success' => true,
                'response' => 'Liver warning signs:

⚠️ Jaundice (yellow eyes/skin), severe right-upper-quadrant pain, or new confusion in someone with liver disease can be urgent. Seek medical evaluation. Avoid alcohol and hepatotoxic meds. This is general information only.',
                'metadata' => ['source' => 'fallback', 'type' => 'hepatic_red_flags']
            ];
        }

        // Endocrine red flags: thyroid storm/myxedema, diabetic emergencies
        if (strpos($message, 'thyroid storm') !== false || strpos($message, 'myxedema coma') !== false || strpos($message, 'very high sugar') !== false || strpos($message, 'dka') !== false || strpos($message, 'hhs') !== false) {
            return [
                'success' => true,
                'response' => 'Endocrine emergencies:

⚠️ Thyroid storm/myxedema coma require emergency care. Diabetic emergencies like DKA/HHS present with very high sugar, dehydration, nausea/vomiting, abdominal pain, confusion, or rapid breathing—seek urgent treatment. This is general information only.',
                'metadata' => ['source' => 'fallback', 'type' => 'endocrine_red_flags']
            ];
        }

        // Check if the question appears to be medical/health-related
        $medicalKeywords = ['health', 'medical', 'symptom', 'pain', 'doctor', 'medicine', 'treatment', 'disease', 'illness', 'wellness', 'fitness', 'nutrition', 'diet', 'exercise', 'sleep', 'stress', 'mental', 'physical', 'body', 'hospital', 'clinic', 'therapy', 'medication', 'prescription', 'diagnosis', 'condition', 'infection', 'fever', 'headache', 'stomach', 'chest', 'breathing', 'heart', 'blood', 'pressure', 'diabetes', 'cancer', 'allergy', 'vaccine', 'vitamin', 'supplement', 'injury', 'wound', 'skin', 'rash', 'cough', 'cold', 'flu', 'nausea', 'vomiting', 'diarrhea', 'constipation', 'fatigue', 'tired', 'energy', 'weight', 'pregnancy', 'birth', 'baby', 'child', 'elderly', 'senior', 'aging', 'kidney', 'renal', 'liver', 'hepatitis', 'thyroid', 'endocrine', 'rheumatology', 'arthritis', 'oncology', 'cancer pain', 'neurology', 'seizure', 'stroke', 'ophthalmology', 'eye', 'otitis', 'ear', 'dental', 'tooth', 'covid', 'conjunctivitis', 'sinusitis'];
        
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

        // Default medical response for when we can't determine specific type
        return $this->buildUniversalGuidance($userMessage);
    }

    /**
     * Universal, safe medical guidance builder used as final fallback
     */
    private function buildUniversalGuidance(string $userMessage): array
    {
        $intro = "I'm here to help with your health question. Here's structured guidance:";
        $sections = [
            'Scope' => [
                'Symptom triage and when to seek care',
                'Clear medical info in simple terms',
                'Evidence-based self-care and OTC safety',
                'Preventive care and risk‑reduction tips',
            ],
            'Clarifiers' => [
                'Onset, duration, pattern, severity (0–10)',
                'Triggers/relievers, recent illnesses, exposures, travel',
                'Key vitals if known (fever, heart rate, blood pressure)',
                'Relevant history (conditions, meds, allergies, pregnancy)',
            ],
            'RedFlags' => [
                'Chest pain, severe breathing trouble, stroke signs',
                'Severe bleeding, high fever with toxicity, confusion',
                'Signs of sepsis, dehydration, or rapidly worsening course',
            ],
            'NextSteps' => [
                'Immediate safety measures and home care where appropriate',
                'What to monitor and when to escalate',
                'Questions to ask your clinician and which clinic to contact',
            ],
            'Limits' => [
                "I don't diagnose or prescribe",
                'For serious symptoms, consult professionals',
                'For emergencies, call local emergency services immediately',
            ],
        ];

        $response = $intro;
        foreach ($sections as $title => $items) {
            $response .= "\n\n" . $this->formatBulletSection($title, $items);
        }
        $response .= "\n\nTell me your symptoms, severity, timeline, and any triggers so I can tailor advice to your situation.";

        return [
            'success' => true,
            'response' => $response,
            'metadata' => ['source' => 'fallback', 'type' => 'universal']
        ];
    }

    /** Format a bullet section line by line for clear expert responses */
    private function formatBulletSection(string $title, array $items): string
    {
        $out = $title . ':';
        foreach ($items as $item) {
            $out .= "\n- " . $item;
        }
        return $out;
    }
}
