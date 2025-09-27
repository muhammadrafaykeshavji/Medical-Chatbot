<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $conversation->title ?? 'AI Conversation' }}
                </h2>
                <span class="text-sm text-gray-500">
                    {{ ucfirst(str_replace('_', ' ', $conversation->type)) }} • 
                    Started {{ $conversation->created_at->diffForHumans() }}
                </span>
            </div>
            <a href="{{ route('chat.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                ← Back to Chats
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Chat Interface -->
                    <div id="chat-container" class="border rounded-lg">
                        <!-- Chat Messages -->
                        <div id="chat-messages" class="h-96 overflow-y-auto p-4 bg-gray-50">
                            @foreach($messages as $message)
                            <div class="flex items-start space-x-3 mb-4">
                                @if($message->sender_type === 'user')
                                <div class="flex items-start space-x-3 mb-4 w-full justify-end">
                                    <div class="flex-1 text-right">
                                        <div class="bg-blue-100 rounded-lg p-3 shadow-sm inline-block max-w-xs lg:max-w-md">
                                            <p class="text-sm">{{ $message->message }}</p>
                                        </div>
                                        <span class="text-xs text-gray-500 mt-1 block">{{ $message->created_at->format('M d, Y H:i') }}</span>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 bg-gray-500 rounded-full flex items-center justify-center">
                                            <span class="text-white text-sm">👤</span>
                                        </div>
                                    </div>
                                </div>
                                @else
                                <div class="flex items-start space-x-3 mb-4 w-full">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                            <span class="text-white text-sm">🤖</span>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <div class="bg-white rounded-lg p-3 shadow-sm max-w-xs lg:max-w-md">
                                            <p class="text-sm">{{ $message->message }}</p>
                                        </div>
                                        <span class="text-xs text-gray-500 mt-1 block">{{ $message->created_at->format('M d, Y H:i') }}</span>
                                    </div>
                                </div>
                                @endif
                            </div>
                            @endforeach
                        </div>

                        <!-- Message Input -->
                        <div class="border-t p-4 bg-white">
                            <form id="chat-form" class="flex space-x-4">
                                @csrf
                                <input type="hidden" name="conversation_id" value="{{ $conversation->id }}">
                                <div class="flex-1">
                                    <textarea 
                                        id="message-input" 
                                        name="message" 
                                        rows="2" 
                                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                        placeholder="Continue the conversation..."
                                        required
                                    ></textarea>
                                </div>
                                <div class="flex flex-col space-y-2">
                                    <button 
                                        type="submit" 
                                        id="send-button"
                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded disabled:opacity-50"
                                    >
                                        Send
                                    </button>
                                    <button 
                                        type="button" 
                                        id="voice-button"
                                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
                                        title="Voice Input"
                                    >
                                        🎤
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Disclaimer -->
                    <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <span class="text-yellow-400">⚠️</span>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">Medical Disclaimer</h3>
                                <p class="text-sm text-yellow-700 mt-1">
                                    This AI assistant provides general health information and should not replace professional medical advice, diagnosis, or treatment.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        const conversationId = {{ $conversation->id }};
        let isRecording = false;
        let recognition = null;

        // Initialize speech recognition if available
        if ('webkitSpeechRecognition' in window || 'SpeechRecognition' in window) {
            const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
            recognition = new SpeechRecognition();
            recognition.continuous = false;
            recognition.interimResults = false;
            recognition.lang = 'en-US';

            recognition.onresult = function(event) {
                const transcript = event.results[0][0].transcript;
                document.getElementById('message-input').value = transcript;
                isRecording = false;
                updateVoiceButton();
            };

            recognition.onerror = function(event) {
                console.error('Speech recognition error:', event.error);
                isRecording = false;
                updateVoiceButton();
            };

            recognition.onend = function() {
                isRecording = false;
                updateVoiceButton();
            };
        } else {
            document.getElementById('voice-button').style.display = 'none';
        }

        // Voice button functionality
        document.getElementById('voice-button').addEventListener('click', function() {
            if (!recognition) return;

            if (isRecording) {
                recognition.stop();
            } else {
                recognition.start();
                isRecording = true;
                updateVoiceButton();
            }
        });

        function updateVoiceButton() {
            const button = document.getElementById('voice-button');
            if (isRecording) {
                button.textContent = '🔴';
                button.classList.add('bg-red-500', 'hover:bg-red-700');
                button.classList.remove('bg-gray-500', 'hover:bg-gray-700');
            } else {
                button.textContent = '🎤';
                button.classList.remove('bg-red-500', 'hover:bg-red-700');
                button.classList.add('bg-gray-500', 'hover:bg-gray-700');
            }
        }

        // Chat form submission
        document.getElementById('chat-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const messageInput = document.getElementById('message-input');
            const sendButton = document.getElementById('send-button');
            const message = messageInput.value.trim();
            
            if (!message) return;

            // Disable input and button
            messageInput.disabled = true;
            sendButton.disabled = true;
            sendButton.textContent = 'Sending...';

            // Add user message to chat
            addMessageToChat('user', message);
            messageInput.value = '';

            try {
                const response = await fetch('{{ route("chat.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        message: message,
                        conversation_id: conversationId,
                        type: '{{ $conversation->type }}'
                    })
                });

                const data = await response.json();

                if (data.success) {
                    addMessageToChat('ai', data.ai_message.message);
                } else {
                    addMessageToChat('ai', 'Sorry, I encountered an error. Please try again.');
                }
            } catch (error) {
                console.error('Error:', error);
                addMessageToChat('ai', 'Sorry, I encountered an error. Please try again.');
            } finally {
                // Re-enable input and button
                messageInput.disabled = false;
                sendButton.disabled = false;
                sendButton.textContent = 'Send';
                messageInput.focus();
            }
        });

        function addMessageToChat(sender, message) {
            const chatMessages = document.getElementById('chat-messages');
            const messageDiv = document.createElement('div');
            
            const isUser = sender === 'user';
            
            if (isUser) {
                messageDiv.innerHTML = `
                    <div class="flex items-start space-x-3 mb-4 w-full justify-end">
                        <div class="flex-1 text-right">
                            <div class="bg-blue-100 rounded-lg p-3 shadow-sm inline-block max-w-xs lg:max-w-md">
                                <p class="text-sm">${message}</p>
                            </div>
                            <span class="text-xs text-gray-500 mt-1 block">Just now</span>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-gray-500 rounded-full flex items-center justify-center">
                                <span class="text-white text-sm">👤</span>
                            </div>
                        </div>
                    </div>
                `;
            } else {
                messageDiv.innerHTML = `
                    <div class="flex items-start space-x-3 mb-4 w-full">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                <span class="text-white text-sm">🤖</span>
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="bg-white rounded-lg p-3 shadow-sm max-w-xs lg:max-w-md">
                                <p class="text-sm">${message}</p>
                            </div>
                            <span class="text-xs text-gray-500 mt-1 block">Just now</span>
                        </div>
                    </div>
                `;
            }

            chatMessages.appendChild(messageDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        // Auto-resize textarea
        document.getElementById('message-input').addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = this.scrollHeight + 'px';
        });

        // Enter to send (Shift+Enter for new line)
        document.getElementById('message-input').addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                document.getElementById('chat-form').dispatchEvent(new Event('submit'));
            }
        });

        // Scroll to bottom on page load
        document.addEventListener('DOMContentLoaded', function() {
            const chatMessages = document.getElementById('chat-messages');
            chatMessages.scrollTop = chatMessages.scrollHeight;
        });
    </script>
    @endpush
</x-app-layout>
