<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('AI Medical Assistant') }}
            </h2>
            <a href="{{ route('chat.new') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                🤖 New Conversation
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($conversations->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($conversations as $conversation)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow">
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-3">
                                <h3 class="text-lg font-medium text-gray-900">
                                    {{ $conversation->title ?? 'Untitled Conversation' }}
                                </h3>
                                <span class="text-xs bg-{{ $conversation->type === 'symptom_check' ? 'red' : ($conversation->type === 'health_advice' ? 'green' : 'blue') }}-100 text-{{ $conversation->type === 'symptom_check' ? 'red' : ($conversation->type === 'health_advice' ? 'green' : 'blue') }}-800 px-2 py-1 rounded">
                                    {{ ucfirst(str_replace('_', ' ', $conversation->type)) }}
                                </span>
                            </div>
                            
                            @if($conversation->latestMessage)
                            <p class="text-sm text-gray-600 mb-4">
                                {{ Str::limit($conversation->latestMessage->message, 100) }}
                            </p>
                            @endif
                            
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-gray-400">
                                    {{ $conversation->last_message_at?->diffForHumans() }}
                                </span>
                                <a href="{{ route('chat.show', $conversation) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    Continue Chat →
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl">🤖</span>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No conversations yet</h3>
                        <p class="text-gray-500 mb-4">Start your first conversation with our AI medical assistant.</p>
                        <a href="{{ route('chat.new') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Start New Chat
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
