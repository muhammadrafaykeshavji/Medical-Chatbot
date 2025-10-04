<?php

namespace App\Http\Controllers;

use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Services\OpenAIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    private OpenAIService $aiService;

    public function __construct(OpenAIService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function index()
    {
        $conversations = Auth::user()->chatConversations()
            ->with('latestMessage')
            ->orderBy('last_message_at', 'desc')
            ->get();

        return view('chat.index', compact('conversations'));
    }

    public function show(ChatConversation $conversation)
    {
        // Ensure user owns this conversation
        if ($conversation->user_id !== Auth::id()) {
            abort(403);
        }

        $messages = $conversation->messages()
            ->orderBy('created_at', 'asc')
            ->get();

        return view('chat.show', compact('conversation', 'messages'));
    }

    public function store(Request $request): JsonResponse
    {
        try {
            // Validate input
            $request->validate([
                'message' => 'required|string|max:1000',
                // limit to enum values in migration
                'type' => 'nullable|in:general,symptom_check,health_advice'
            ]);

            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please log in to use the chat feature.'
                ], 401);
            }

            $userMessage = $request->message;
            // Default to a valid enum value defined in migration
            $type = in_array($request->type, ['general','symptom_check','health_advice'])
                ? $request->type
                : 'general';

            Log::info('Processing chat message', ['user_id' => $user->id, 'message' => $userMessage]);

            // Create or get conversation
            $conversation = ChatConversation::firstOrCreate([
                'user_id' => $user->id,
                'type' => $type,
                'is_active' => true,
            ], [
                'title' => $this->generateConversationTitle($userMessage),
                'last_message_at' => now(),
            ]);

            // Update conversation timestamp
            $conversation->update(['last_message_at' => now()]);

            // Store user message
            $userMessageRecord = ChatMessage::create([
                'conversation_id' => $conversation->id,
                'sender_type' => 'user',
                'message' => $userMessage,
            ]);

            // Build recent conversation context (last 10 messages)
            $contextMessages = $conversation->messages()
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
                ->reverse()
                ->map(function ($m) {
                    return [
                        'sender_type' => $m->sender_type,
                        'message' => $m->message,
                    ];
                })
                ->values()
                ->toArray();

            // Generate AI response via service (falls back internally if API unavailable)
            $serviceResult = $this->aiService->generateMedicalResponse($userMessage, $contextMessages, $type);
            $aiResponseText = $serviceResult['response'] ?? 'I\'m sorry, I\'m having trouble responding right now. Please try again.';

            // Store AI message
            $aiMessageRecord = ChatMessage::create([
                'conversation_id' => $conversation->id,
                'sender_type' => 'ai',
                'message' => $aiResponseText,
            ]);

            Log::info('Chat message processed successfully', [
                'conversation_id' => $conversation->id,
                'user_message_id' => $userMessageRecord->id,
                'ai_message_id' => $aiMessageRecord->id
            ]);

            return response()->json([
                'success' => true,
                'conversation_id' => $conversation->id,
                'user_message' => $userMessageRecord,
                'ai_message' => $aiMessageRecord,
                'metadata' => $serviceResult['metadata'] ?? null,
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Chat validation error', ['errors' => $e->errors()]);
            
            return response()->json([
                'success' => false,
                'error' => 'Validation failed',
                'message' => 'Please check your input and try again.'
            ], 422);

        } catch (\Exception $e) {
            Log::error('Chat error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Server error',
                'message' => 'Sorry, I encountered an error. Please try again.'
            ], 500);
        }
    }

    public function newConversation()
    {
        return view('chat.new');
    }

    private function generateConversationTitle(string $message): string
    {
        // Generate a title from the first message (truncated)
        $title = substr($message, 0, 50);
        if (strlen($message) > 50) {
            $title .= '...';
        }
        return $title;
    }

    // Fallback method removed; delegated to GeminiAIService
}
