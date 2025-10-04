@extends('layouts.app')

@section('content')
<style>
/* RTL Support Styles */
.rtl-mode {
    direction: rtl;
}

.rtl-mode .flex.items-start.space-x-3 {
    flex-direction: row-reverse;
}

.rtl-mode .space-x-3 > * + * {
    margin-left: 0;
    margin-right: 0.75rem;
}

/* RTL message styling */
.rtl-message {
    direction: rtl !important;
    text-align: right !important;
}

.rtl-message * {
    direction: rtl !important;
    text-align: right !important;
}

/* RTL list styling */
.rtl-message div[class*="ml-"] {
    margin-left: 0 !important;
    margin-right: 1.5rem !important;
}

/* RTL numbering */
.rtl-message ol, .rtl-message ul {
    direction: rtl;
    text-align: right;
}
</style>
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-blue-900 to-slate-900">
    <div class="container mx-auto px-4 py-8">
        <div class="text-center mb-8">
            @guest
            <div class="mt-4 p-4 bg-yellow-500/20 border border-yellow-500 rounded-lg max-w-2xl mx-auto">
                <div class="flex items-center justify-center space-x-2">
                    <i class="fas fa-info-circle text-yellow-400"></i>
                    <p class="text-yellow-200">
                        Please <a href="{{ route('login') }}" class="text-yellow-300 underline font-medium">log in</a> or 
                        <a href="{{ route('register') }}" class="text-yellow-300 underline font-medium">create an account</a> to use the AI chat feature.
                    </p>
                </div>
            </div>
            @endguest
        </div>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto">
            <div class="bg-slate-800/50 backdrop-blur-lg rounded-xl border border-slate-700 overflow-hidden">
                <!-- Medical AI Assistant Header -->
                <div class="p-6 border-b border-slate-700 text-center">
                    <div class="flex items-center justify-center space-x-3 mb-3">
                        <div class="text-blue-400 text-4xl">
                            <i class="fas fa-user-md"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-white">Medical AI Assistant</h3>
                    </div>
                    <p class="text-slate-300">Ask me any medical or health-related questions. I can help with symptoms, health advice, medical information, and wellness guidance.</p>
                    <div class="mt-4 text-sm text-yellow-300 bg-yellow-500/10 border border-yellow-500/30 rounded-lg p-3">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Note:</strong> I only answer medical and health-related questions. For other topics, please consult appropriate resources.
                    </div>
                </div>

                <!-- Chat Controls -->
                <div class="px-6 py-4 border-b border-slate-700 bg-slate-800/30">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <button id="new-chat-btn" class="flex items-center space-x-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors">
                                <i class="fas fa-plus"></i>
                                <span>New Chat</span>
                            </button>
                            <button id="clear-chat-btn" class="flex items-center space-x-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
                                <i class="fas fa-trash"></i>
                                <span>Clear Chat</span>
                            </button>
                        </div>
                        <div class="flex items-center space-x-4">
                            <!-- Language Detection Status -->
                            <div id="language-status" class="hidden flex items-center space-x-2 px-3 py-1 bg-slate-700 rounded-lg">
                                <i class="fas fa-language text-cyan-400"></i>
                                <span id="detected-language" class="text-sm text-cyan-300"></span>
                            </div>
                            <!-- Language Selection -->
                            <div class="relative">
                                <select id="language-selector" class="w-full h-full opacity-0 absolute inset-0 cursor-pointer">
                                    <option value="auto">🌐 Auto-Detect</option>
                                    <option value="en">🇺🇸 English</option>
                                    <option value="ar">🇸🇦 العربية</option>
                                    <option value="ur">🇵🇰 اردو</option>
                                    <option value="fa">🇮🇷 فارسی</option>
                                    <option value="es">🇪🇸 Español</option>
                                    <option value="fr">🇫🇷 Français</option>
                                    <option value="de">🇩🇪 Deutsch</option>
                                    <option value="it">🇮🇹 Italiano</option>
                                    <option value="pt">🇵🇹 Português</option>
                                    <option value="ru">🇷🇺 Русский</option>
                                    <option value="zh">🇨🇳 中文</option>
                                    <option value="ja">🇯🇵 日本語</option>
                                    <option value="ko">🇰🇷 한국어</option>
                                    <option value="hi">🇮🇳 हिंदी</option>
                                    <option value="tr">🇹🇷 Türkçe</option>
                                </select>
                                <div class="flex items-center space-x-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors pointer-events-none">
                                    <i class="fas fa-globe text-white"></i>
                                    <span class="text-white font-medium">Languages</span>
                                    <i class="fas fa-chevron-down text-white ml-2"></i>
                                </div>
                            </div>
                            <button id="chat-history-btn" class="flex items-center space-x-2 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition-colors">
                                <i class="fas fa-history"></i>
                                <span>Chat History</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Chat Messages Area -->
                <div id="chat-messages" class="h-96 overflow-y-auto p-6 bg-slate-900/30">
                    <div class="flex items-start space-x-3 mb-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center">
                                <i class="fas fa-robot text-white"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="bg-slate-700 rounded-lg p-4 shadow-sm">
                                <p class="text-slate-200">Hello! I'm your AI medical assistant. I'm here to help with your health questions and provide general medical information. Please note that I cannot replace professional medical advice. How can I assist you today?</p>
                            </div>
                            <span class="text-xs text-slate-400 mt-2 block">Just now</span>
                        </div>
                    </div>
                </div>

                <!-- Message Input -->
                <div class="border-t border-slate-700 p-6 bg-slate-800/50">
                    <form id="chat-form" class="flex space-x-4">
                        @csrf
                        <div class="flex-1">
                            <textarea 
                                id="message-input" 
                                name="message" 
                                rows="3" 
                                class="w-full bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none" 
                                placeholder="Type your health question here..."
                                required
                            ></textarea>
                        </div>
                        <div class="flex flex-col space-y-2">
                            <button 
                                type="submit" 
                                id="send-button"
                                class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-medium py-3 px-6 rounded-lg transition-all duration-200 disabled:opacity-50"
                            >
                                <i class="fas fa-paper-plane mr-2"></i>Send
                            </button>
                            <button 
                                type="button" 
                                id="voice-button"
                                class="bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-medium py-3 px-6 rounded-lg transition-all duration-200"
                                title="Voice Input"
                            >
                                <i class="fas fa-microphone" id="voice-icon"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Disclaimer -->
            <div class="mt-6 p-4 bg-yellow-500/20 border border-yellow-500 rounded-lg">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-yellow-400 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-yellow-300">Medical Disclaimer</h3>
                        <p class="text-sm text-yellow-200 mt-1">
                            This AI assistant provides general health information and should not replace professional medical advice, diagnosis, or treatment. Always consult with qualified healthcare providers for medical concerns.
                        </p>
                    </div>
                </div>
            </div>
            </div>

            <!-- Chat History Modal -->
            <div id="chat-history-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50">
                <div class="flex items-center justify-center min-h-screen p-4">
                    <div class="bg-slate-800 rounded-xl border border-slate-700 max-w-4xl w-full max-h-[80vh] overflow-hidden">
                        <div class="p-6 border-b border-slate-700">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xl font-semibold text-white">Chat History</h3>
                                <button id="close-history-modal" class="text-slate-400 hover:text-white">
                                    <i class="fas fa-times text-xl"></i>
                                </button>
                            </div>
                        </div>
                        <div class="p-6 overflow-y-auto max-h-96">
                            <div id="chat-history-list" class="space-y-4">
                                <!-- Chat history items will be populated here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatForm = document.getElementById('chat-form');
    const messageInput = document.getElementById('message-input');
    const sendButton = document.getElementById('send-button');
    const chatMessages = document.getElementById('chat-messages');
    const voiceButton = document.getElementById('voice-button');
    const voiceIcon = document.getElementById('voice-icon');
    const newChatBtn = document.getElementById('new-chat-btn');
    const clearChatBtn = document.getElementById('clear-chat-btn');
    const chatHistoryBtn = document.getElementById('chat-history-btn');
    const chatHistoryModal = document.getElementById('chat-history-modal');
    const closeHistoryModal = document.getElementById('close-history-modal');
    const chatHistoryList = document.getElementById('chat-history-list');
    const languageSelector = document.getElementById('language-selector');
    const languageStatus = document.getElementById('language-status');
    const detectedLanguageSpan = document.getElementById('detected-language');
    
    let isRecording = false;
    let recognition = null;
    let currentChatId = generateChatId();
    let chatHistory = JSON.parse(localStorage.getItem('medicalChatHistory') || '[]');
    let currentMessages = JSON.parse(localStorage.getItem('currentChatMessages') || '[]');
    let selectedLanguage = localStorage.getItem('selectedLanguage') || 'auto';
    let detectedLanguage = null;
    
    // Helper functions
    function generateChatId() {
        return 'chat_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
    }
    
    function saveChatToStorage() {
        localStorage.setItem('currentChatMessages', JSON.stringify(currentMessages));
    }
    
    // Language-related functions
    function initializeLanguageSelector() {
        languageSelector.value = selectedLanguage;
        updateLanguageStatus();
    }
    
    function updateLanguageStatus() {
        if (selectedLanguage === 'auto') {
            if (detectedLanguage) {
                languageStatus.classList.remove('hidden');
                detectedLanguageSpan.textContent = `Detected: ${getLanguageName(detectedLanguage)}`;
            } else {
                languageStatus.classList.add('hidden');
            }
        } else {
            languageStatus.classList.remove('hidden');
            detectedLanguageSpan.textContent = `Selected: ${getLanguageName(selectedLanguage)}`;
        }
    }
    
    function getLanguageName(code) {
        const languages = {
            'en': 'English',
            'ar': 'العربية',
            'ur': 'اردو', 
            'fa': 'فارسی',
            'es': 'Español',
            'fr': 'Français',
            'de': 'Deutsch',
            'it': 'Italiano',
            'pt': 'Português',
            'ru': 'Русский',
            'zh': '中文',
            'ja': '日本語',
            'ko': '한국어',
            'hi': 'हिंदी',
            'tr': 'Türkçe'
        };
        return languages[code] || code;
    }
    
    function applyRTLLayout(isRTL) {
        const chatContainer = document.querySelector('.max-w-7xl');
        const messageInput = document.getElementById('message-input');
        const chatMessages = document.getElementById('chat-messages');
        
        if (isRTL) {
            chatContainer.style.direction = 'rtl';
            messageInput.style.direction = 'rtl';
            messageInput.style.textAlign = 'right';
            chatMessages.style.direction = 'rtl';
            
            // Add RTL class to body for global RTL styling
            document.body.classList.add('rtl-mode');
        } else {
            chatContainer.style.direction = 'ltr';
            messageInput.style.direction = 'ltr';
            messageInput.style.textAlign = 'left';
            chatMessages.style.direction = 'ltr';
            
            // Remove RTL class from body
            document.body.classList.remove('rtl-mode');
        }
    }
    
    function isRTLLanguage(langCode) {
        return ['ar', 'ur', 'fa'].includes(langCode);
    }
    
    function detectRTLContent(text) {
        // RTL Unicode ranges for Arabic, Persian, and Urdu
        const rtlRegex = /[\u0590-\u05FF\u0600-\u06FF\u0750-\u077F\u08A0-\u08FF\uFB50-\uFDFF\uFE70-\uFEFF]/;
        return rtlRegex.test(text);
    }
    
    function applyMessageRTL(messageElement, isRTL) {
        if (isRTL) {
            messageElement.classList.add('rtl-message');
        } else {
            messageElement.classList.remove('rtl-message');
        }
    }
    
    function loadChatFromStorage() {
        currentMessages = JSON.parse(localStorage.getItem('currentChatMessages') || '[]');
        displayStoredMessages();
    }
    
    function displayStoredMessages() {
        // Clear current display except initial message
        const initialMessage = chatMessages.querySelector('.flex.items-start.space-x-3.mb-4');
        chatMessages.innerHTML = '';
        if (initialMessage) {
            chatMessages.appendChild(initialMessage);
        }
        
        // Display stored messages
        currentMessages.forEach(msg => {
            addMessage(msg.message, msg.sender, false);
        });
    }
    
    function saveCurrentChatToHistory(autoSave = false) {
        if (currentMessages.length === 0) {
            if (!autoSave) {
                alert('No messages to save!');
            }
            return false;
        }
        
        const chatTitle = autoSave ? 
            `Chat ${new Date().toLocaleString()}` : 
            (prompt('Enter a title for this chat:') || `Chat ${new Date().toLocaleString()}`);
            
        const chatData = {
            id: currentChatId,
            title: chatTitle,
            messages: [...currentMessages],
            timestamp: new Date().toISOString(),
            chatType: 'general'
        };
        
        chatHistory.unshift(chatData);
        localStorage.setItem('medicalChatHistory', JSON.stringify(chatHistory));
        
        if (!autoSave) {
            alert('Chat saved successfully!');
        }
        return true;
    }
    
    function clearCurrentChat() {
        if (confirm('Are you sure you want to clear the current chat? This will delete all messages in the current conversation.')) {
            // Clear messages from current session
            currentMessages = [];
            localStorage.removeItem('currentChatMessages');
            
            // Also update the saved version in chat history if it exists
            const existingChatIndex = chatHistory.findIndex(chat => chat.id === currentChatId);
            if (existingChatIndex !== -1) {
                // Update the existing chat in history with cleared messages
                chatHistory[existingChatIndex].messages = [];
                chatHistory[existingChatIndex].timestamp = new Date().toISOString(); // Update timestamp
                localStorage.setItem('medicalChatHistory', JSON.stringify(chatHistory));
            }
            
            // Reset chat display to initial state (but keep same chat session)
            chatMessages.innerHTML = `
                <div class="flex items-start space-x-3 mb-4">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center">
                            <i class="fas fa-robot text-white"></i>
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="bg-slate-700 rounded-lg p-4 shadow-sm">
                            <p class="text-slate-200">Hello! I'm your AI medical assistant. I'm here to help with your health questions and provide general medical information. Please note that I cannot replace professional medical advice. How can I assist you today?</p>
                        </div>
                        <span class="text-xs text-slate-400 mt-2 block">Just now</span>
                    </div>
                </div>
            `;
        }
    }
    
    function startNewChat() {
        // Auto-save current chat if it has messages
        if (currentMessages.length > 0) {
            saveCurrentChatToHistory(true); // Auto-save without prompt
        }
        
        // Clear and start new chat
        currentMessages = [];
        localStorage.removeItem('currentChatMessages');
        currentChatId = generateChatId();
        
        // Reset chat display to initial state
        chatMessages.innerHTML = `
            <div class="flex items-start space-x-3 mb-4">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center">
                        <i class="fas fa-robot text-white"></i>
                    </div>
                </div>
                <div class="flex-1">
                    <div class="bg-slate-700 rounded-lg p-4 shadow-sm">
                        <p class="text-slate-200">Hello! I'm your AI medical assistant. I'm here to help with your health questions and provide general medical information. Please note that I cannot replace professional medical advice. How can I assist you today?</p>
                    </div>
                    <span class="text-xs text-slate-400 mt-2 block">Just now</span>
                </div>
            </div>
        `;
    }
    
    function showChatHistory() {
        chatHistoryList.innerHTML = '';
        
        if (chatHistory.length === 0) {
            chatHistoryList.innerHTML = `
                <div class="text-center py-8">
                    <i class="fas fa-history text-4xl text-slate-400 mb-4"></i>
                    <p class="text-slate-400">No chat history found</p>
                </div>
            `;
        } else {
            chatHistory.forEach((chat, index) => {
                const chatItem = document.createElement('div');
                chatItem.className = 'bg-slate-700 rounded-lg p-4 cursor-pointer hover:bg-slate-600 transition-colors';
                chatItem.innerHTML = `
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <h4 class="text-white font-medium">${chat.title}</h4>
                            <p class="text-slate-400 text-sm">${new Date(chat.timestamp).toLocaleString()}</p>
                            <p class="text-slate-500 text-xs mt-1">${chat.messages.length} messages • ${chat.chatType}</p>
                        </div>
                        <div class="flex space-x-2">
                            <button onclick="loadChatFromHistory(${index})" class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded">
                                Load
                            </button>
                            <button onclick="deleteChatFromHistory(${index})" class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-sm rounded">
                                Delete
                            </button>
                        </div>
                    </div>
                `;
                chatHistoryList.appendChild(chatItem);
            });
        }
        
        chatHistoryModal.classList.remove('hidden');
    }
    
    // Load chat from history
    window.loadChatFromHistory = function(index) {
        const selectedChat = chatHistory[index];
        if (confirm(`Load chat "${selectedChat.title}"? Current chat will be lost if not saved.`)) {
            currentMessages = [...selectedChat.messages];
            currentChatId = selectedChat.id;
            
            // Chat type is now always 'general' - no need to set UI element
            
            displayStoredMessages();
            saveChatToStorage();
            chatHistoryModal.classList.add('hidden');
        }
    };
    
    // Delete chat from history
    window.deleteChatFromHistory = function(index) {
        if (confirm('Are you sure you want to delete this chat?')) {
            chatHistory.splice(index, 1);
            localStorage.setItem('medicalChatHistory', JSON.stringify(chatHistory));
            showChatHistory(); // Refresh the list
        }
    };
    
    // Event listeners for chat controls
    newChatBtn.addEventListener('click', startNewChat);
    clearChatBtn.addEventListener('click', clearCurrentChat);
    chatHistoryBtn.addEventListener('click', showChatHistory);
    closeHistoryModal.addEventListener('click', () => chatHistoryModal.classList.add('hidden'));
    
    // Language selector event listener
    languageSelector.addEventListener('change', function() {
        selectedLanguage = this.value;
        localStorage.setItem('selectedLanguage', selectedLanguage);
        updateLanguageStatus();
        
        // Apply RTL layout if needed
        const isRTL = selectedLanguage !== 'auto' && isRTLLanguage(selectedLanguage);
        applyRTLLayout(isRTL);
        
        // Reapply RTL to existing messages if language changed
        const existingMessages = chatMessages.querySelectorAll('.bg-green-600, .bg-slate-700');
        existingMessages.forEach(messageElement => {
            const messageText = messageElement.textContent || messageElement.innerText;
            const shouldBeRTL = isRTL || detectRTLContent(messageText);
            applyMessageRTL(messageElement, shouldBeRTL);
        });
    });
    
    // Close modal when clicking outside
    chatHistoryModal.addEventListener('click', (e) => {
        if (e.target === chatHistoryModal) {
            chatHistoryModal.classList.add('hidden');
        }
    });
    
    // Load existing chat on page load
    loadChatFromStorage();
    
    // Initialize language selector
    initializeLanguageSelector();
    
    // Function to format medical responses
    function formatMedicalResponse(text) {
        return text
            // Convert **bold** to <strong>
            .replace(/\*\*(.*?)\*\*/g, '<strong class="font-semibold text-white">$1</strong>')
            // Convert line breaks to <br>
            .replace(/\n/g, '<br>')
            // Convert emoji sections to styled divs
            .replace(/^(🌡️|💊|⚠️|🩺|🏥|🌟|🧘|🏃|🤧|💧|🧊|🤢)(.+?)(?=^[🌡️💊⚠️🩺🏥🌟🧘🏃🤧💧🧊🤢]|$)/gm, 
                '<div class="mb-3"><span class="text-xl mr-2">$1</span><span class="font-medium text-cyan-300">$2</span></div>')
            // Convert bullet points to proper list items
            .replace(/^- (.+)$/gm, '<div class="ml-6 mb-1 text-slate-300">• $1</div>');
    }
    
    // Initialize speech recognition
    if ('webkitSpeechRecognition' in window || 'SpeechRecognition' in window) {
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        recognition = new SpeechRecognition();
        recognition.continuous = false;
        recognition.interimResults = false;
        recognition.lang = 'en-US';
        
        recognition.onstart = function() {
            isRecording = true;
            voiceIcon.className = 'fas fa-stop';
            voiceButton.classList.remove('from-purple-600', 'to-pink-600');
            voiceButton.classList.add('from-red-600', 'to-red-700');
            voiceButton.title = 'Stop Recording';
        };
        
        recognition.onresult = function(event) {
            const transcript = event.results[0][0].transcript;
            messageInput.value = transcript;
            messageInput.focus();
        };
        
        recognition.onend = function() {
            isRecording = false;
            voiceIcon.className = 'fas fa-microphone';
            voiceButton.classList.remove('from-red-600', 'to-red-700');
            voiceButton.classList.add('from-purple-600', 'to-pink-600');
            voiceButton.title = 'Voice Input';
        };
        
        recognition.onerror = function(event) {
            console.error('Speech recognition error:', event.error);
            isRecording = false;
            voiceIcon.className = 'fas fa-microphone';
            voiceButton.classList.remove('from-red-600', 'to-red-700');
            voiceButton.classList.add('from-purple-600', 'to-pink-600');
            voiceButton.title = 'Voice Input';
            
            if (event.error === 'not-allowed') {
                alert('Microphone access denied. Please allow microphone access to use voice input.');
            }
        };
        
        voiceButton.addEventListener('click', function() {
            if (isRecording) {
                recognition.stop();
            } else {
                recognition.start();
            }
        });
    } else {
        // Hide voice button if not supported
        voiceButton.style.display = 'none';
        console.log('Speech recognition not supported in this browser');
    }
    
    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const message = messageInput.value.trim();
        if (!message) return;
        
        // Add user message
        addMessage(message, 'user');
        
        // Clear input
        messageInput.value = '';
        
        // Show typing indicator
        showTypingIndicator();
        
        // Check if user is authenticated first
        @guest
        hideTypingIndicator();
        addMessage('Please <a href="{{ route('login') }}" class="text-cyan-400 underline font-medium">log in</a> to use the AI chat feature. You can also <a href="{{ route('register') }}" class="text-cyan-400 underline font-medium">create a free account</a> if you don\'t have one.', 'assistant');
        return;
        @endguest

        // Send to server
        fetch('{{ route("chat.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                message: message,
                // must match enum values allowed by backend
                type: 'general',
                language: selectedLanguage,
                detected_language: detectedLanguage
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            hideTypingIndicator();
            console.log('Server response:', data); // Debug log
            console.log('Response structure:', JSON.stringify(data, null, 2)); // Detailed debug
            
            // Handle language detection response
            if (data.detected_language && selectedLanguage === 'auto') {
                detectedLanguage = data.detected_language;
                updateLanguageStatus();
                
                // Apply RTL layout if detected language is RTL
                if (isRTLLanguage(detectedLanguage)) {
                    applyRTLLayout(true);
                }
            }
            
            if (data.success && data.ai_message && data.ai_message.message) {
                console.log('Using ai_message.message:', data.ai_message.message);
                addMessage(data.ai_message.message, 'assistant');
            } else if (data.response) {
                console.log('Using data.response:', data.response);
                addMessage(data.response, 'assistant');
            } else if (data.success === false && data.message) {
                console.log('Using error message:', data.message);
                addMessage(data.message, 'assistant');
            } else {
                console.log('No valid response found, showing generic error');
                addMessage('Sorry, I encountered an error. Please try again.', 'assistant');
            }
        })
        .catch(error => {
            hideTypingIndicator();
            console.error('Chat error:', error); // Debug log
            
            // Check if it's an authentication error
            if (error.message.includes('401') || error.message.includes('403')) {
                addMessage('Please log in to use the chat feature. <a href="/login" class="text-cyan-400 underline">Click here to log in</a>.', 'assistant');
            } else if (error.message.includes('419')) {
                addMessage('Session expired. Please refresh the page and try again.', 'assistant');
            } else {
                addMessage('Sorry, I encountered an error. Please try again. If the problem persists, please refresh the page.', 'assistant');
            }
        });
    });
    
    // Keyboard shortcuts
    messageInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            chatForm.dispatchEvent(new Event('submit'));
        }
    });
    
    // Auto-resize textarea
    messageInput.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 120) + 'px';
    });
    
    function addMessage(message, sender, saveToStorage = true) {
        const messageDiv = document.createElement('div');
        messageDiv.className = 'flex items-start space-x-3 mb-4';
        
        // Detect if message contains RTL content
        const isRTLContent = detectRTLContent(message) || (selectedLanguage !== 'auto' && isRTLLanguage(selectedLanguage)) || (detectedLanguage && isRTLLanguage(detectedLanguage));
        
        // Save message to current chat if not loading from storage
        if (saveToStorage) {
            currentMessages.push({
                message: message,
                sender: sender,
                timestamp: new Date().toISOString()
            });
            saveChatToStorage();
        }
        
        if (sender === 'user') {
            messageDiv.className = 'flex items-start space-x-3 mb-4 justify-end';
            messageDiv.innerHTML = `
                <div class="bg-green-600 rounded-lg p-4 shadow-sm max-w-xs order-1">
                    <p class="text-white">${message}</p>
                    <span class="text-xs text-green-200 mt-2 block">Just now</span>
                </div>
                <div class="flex-shrink-0 order-2">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-white"></i>
                    </div>
                </div>
            `;
        } else {
            // Format the message to handle markdown-style formatting
            const formattedMessage = formatMedicalResponse(message);
            
            messageDiv.innerHTML = `
                <div class="flex items-start space-x-3 mb-4">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center">
                            <i class="fas fa-robot text-white"></i>
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="bg-slate-700 rounded-lg p-4 shadow-sm">
                            <div class="text-slate-200 medical-response">${formattedMessage}</div>
                        </div>
                        <span class="text-xs text-slate-400 mt-2 block">Just now</span>
                    </div>
                </div>
            `;
        }
        
        // Append the message to the chat container
        chatMessages.appendChild(messageDiv);
        
        // Apply RTL styling to the message if needed
        if (isRTLContent) {
            const messageContent = messageDiv.querySelector('.bg-green-600, .bg-slate-700');
            if (messageContent) {
                applyMessageRTL(messageContent, true);
            }
        }
        
        // Scroll to bottom
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
    
    function showTypingIndicator() {
        const typingDiv = document.createElement('div');
        typingDiv.id = 'typing-indicator';
        typingDiv.className = 'flex items-start space-x-3 mb-4';
        typingDiv.innerHTML = `
            <div class="flex-shrink-0">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center">
                    <i class="fas fa-robot text-white"></i>
                </div>
            </div>
            <div class="flex-1">
                <div class="bg-slate-700 rounded-lg p-4 shadow-sm">
                    <div class="flex space-x-1">
                        <div class="w-2 h-2 bg-slate-400 rounded-full animate-bounce"></div>
                        <div class="w-2 h-2 bg-slate-400 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                        <div class="w-2 h-2 bg-slate-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                    </div>
                </div>
            </div>
        `;
        chatMessages.appendChild(typingDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
    
    function hideTypingIndicator() {
        const typingIndicator = document.getElementById('typing-indicator');
        if (typingIndicator) {
            typingIndicator.remove();
        }
    }
});
</script>
@endsection
