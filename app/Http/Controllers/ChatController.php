<?php

namespace App\Http\Controllers;

use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Services\GeminiAIService;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class ChatController extends Controller
{
    private GeminiAIService $geminiService;

    public function __construct(GeminiAIService $geminiService)
    {
        $this->geminiService = $geminiService;
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
        $request->validate([
            'message' => 'required|string|max:1000',
            'conversation_id' => 'nullable|exists:chat_conversations,id',
            'type' => 'in:general,symptom_check,health_advice'
        ]);

        $user = Auth::user();
        $conversationId = $request->conversation_id;
        $type = $request->type ?? 'general';

        // Create new conversation if none provided
        if (!$conversationId) {
            $conversation = ChatConversation::create([
                'user_id' => $user->id,
                'title' => $this->generateConversationTitle($request->message),
                'type' => $type,
                'is_active' => true,
                'last_message_at' => now(),
            ]);
            $conversationId = $conversation->id;
        } else {
            $conversation = ChatConversation::findOrFail($conversationId);
            
            // Ensure user owns this conversation
            if ($conversation->user_id !== $user->id) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
            
            $conversation->update(['last_message_at' => now()]);
        }

        // Store user message
        $userMessage = ChatMessage::create([
            'conversation_id' => $conversationId,
            'sender_type' => 'user',
            'message' => $request->message,
        ]);

        // Broadcast user message
        broadcast(new MessageSent($userMessage, $user));

        // Get conversation context for AI
        $context = $this->getConversationContext($conversationId);

        // Generate AI response
        $aiResponse = $this->geminiService->generateMedicalResponse(
            $request->message,
            $context
        );

        // Store AI message
        $aiMessage = ChatMessage::create([
            'conversation_id' => $conversationId,
            'sender_type' => 'ai',
            'message' => $aiResponse['response'],
            'metadata' => $aiResponse['metadata'] ?? null,
        ]);

        // Broadcast AI message
        broadcast(new MessageSent($aiMessage, $user));

        return response()->json([
            'success' => true,
            'conversation_id' => $conversationId,
            'user_message' => $userMessage,
            'ai_message' => $aiMessage,
        ]);
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

    private function getConversationContext(int $conversationId): array
    {
        return ChatMessage::where('conversation_id', $conversationId)
            ->orderBy('created_at', 'desc')
            ->limit(10) // Last 10 messages for context
            ->get()
            ->reverse()
            ->map(function ($message) {
                return [
                    'sender_type' => $message->sender_type,
                    'message' => $message->message,
                    'created_at' => $message->created_at,
                ];
            })
            ->toArray();
    }
}
