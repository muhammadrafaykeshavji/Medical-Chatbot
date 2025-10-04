<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - Medi AI</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('logo-1.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('logo-1.png') }}">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 bg-slate-800 border-r border-slate-700">
            <div class="flex flex-col h-full">
                <!-- Logo -->
                <div class="flex items-center gap-2 px-6 py-5 border-b border-slate-700">
                    <div class="w-10 h-10 flex items-center justify-center">
                        <img src="{{ asset('logo-1.png') }}" alt="">
                    </div>
                    <span class="text-xl font-bold text-white">Medi AI</span>
                </div>
                
                <!-- Navigation -->
                <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 text-blue-400 bg-blue-500/10 rounded-lg">
                        <i class="fas fa-home w-5"></i>
                        <span>Dashboard</span>
                    </a>
                    
                    <a href="{{ route('chat.new') }}" class="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
                        <i class="fas fa-comment-medical w-5"></i>
                        <span>AI Chat</span>
                    </a>
                    
                    <a href="{{ route('symptom-checker.create') }}" class="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
                        <i class="fas fa-stethoscope w-5"></i>
                        <span>Symptom Checker</span>
                    </a>
                    
                    <a href="{{ route('health-metrics.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
                        <i class="fas fa-chart-line w-5"></i>
                        <span>Health Metrics</span>
                    </a>
                    
                    <a href="{{ route('doctors.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
                        <i class="fas fa-user-md w-5"></i>
                        <span>Find Doctors</span>
                    </a>
                    
                    <a href="{{ route('reports.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
                        <i class="fas fa-file-medical w-5"></i>
                        <span>Report Analyzer</span>
                    </a>
                    
                    <a href="{{ route('health-plans.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
                        <i class="fas fa-clipboard-list w-5"></i>
                        <span>Health Plan</span>
                    </a>
                </nav>
                
                <!-- Bottom Section -->
                <div class="px-4 py-4 border-t border-slate-700">
                <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors w-full text-left">
            <i class="fas fa-sign-out-alt w-5"></i>
            <span>Logout</span>
        </button>
         </form>
                    
                    
                    <div class="flex items-center gap-3 px-3 py-2.5 mt-2">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=3b82f6&color=fff" alt="User" class="w-8 h-8 rounded-full">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-white">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-slate-400">Pro Plan</p>
                        </div>
                    </div>
                </div>
            </div>
        </aside>
        
        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto bg-slate-900">
            <!-- Header -->
            <header class="bg-slate-800 border-b border-slate-700 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-white">Welcome back, {{ explode(' ', Auth::user()->name)[0] }}!</h1>
                        <p class="text-slate-400 text-sm mt-1">Your AI-powered healthcare assistant is ready to help you with health inquiries, symptom checking, and health tracking.</p>
                    </div>
                    <div class="flex items-center gap-4">
                       
                        <div class="text-slate-300">
                            
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors w-full text-left">
            <i class="fas fa-sign-out-alt w-5"></i>
            <span>Logout</span>
        </button>
    </form>
                    </div>
                </div>
            </header>
            
            <!-- Content -->
            <div class="p-6">
                <!-- Action Cards Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    <!-- AI Chat Assistant Card -->
                    <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 hover:border-blue-500 transition-colors">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-12 h-12 bg-blue-500/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-comment-medical text-blue-400 text-xl"></i>
                            </div>
                        </div>
                        <h3 class="text-lg font-semibold text-white mb-2">AI Chat Assistant</h3>
                        <p class="text-slate-400 text-sm mb-4">Start Conversation</p>
                        <p class="text-slate-500 text-xs mb-4">Chat with our AI for health-related questions and guidance.</p>
                        <a href="{{ route('chat.new') }}" class="text-blue-400 text-sm font-medium hover:text-blue-300 transition-colors">
                            Start Chatting →
                        </a>
                    </div>
                    
                    <!-- Symptom Checker Card -->
                    <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 hover:border-pink-500 transition-colors">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-12 h-12 bg-pink-500/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-stethoscope text-pink-400 text-xl"></i>
                            </div>
                        </div>
                        <h3 class="text-lg font-semibold text-white mb-2">Symptom Checker</h3>
                        <p class="text-slate-400 text-sm mb-4">Check Symptoms</p>
                        <p class="text-slate-500 text-xs mb-4">Get AI-powered analysis of your symptoms and health concerns.</p>
                        <a href="{{ route('symptom-checker.create') }}" class="text-pink-400 text-sm font-medium hover:text-pink-300 transition-colors">
                            Analyze Symptoms →
                        </a>
                    </div>
                    
                    <!-- Find Doctors Card -->
                    <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 hover:border-purple-500 transition-colors">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-12 h-12 bg-purple-500/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-user-md text-purple-400 text-xl"></i>
                            </div>
                        </div>
                        <h3 class="text-lg font-semibold text-white mb-2">Find Doctors</h3>
                        <p class="text-slate-400 text-sm mb-4">Search Healthcare Professionals</p>
                        <p class="text-slate-500 text-xs mb-4">Find qualified doctors and specialists in your area.</p>
                        <a href="{{ route('doctors.index') }}" class="text-purple-400 text-sm font-medium hover:text-purple-300 transition-colors">
                            Find Doctors →
                        </a>
                    </div>
                    
                    <!-- Report Analyzer Card -->
                    <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 hover:border-orange-500 transition-colors">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-12 h-12 bg-orange-500/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-file-medical text-orange-400 text-xl"></i>
                            </div>
                        </div>
                        <h3 class="text-lg font-semibold text-white mb-2">Report Analyzer</h3>
                        <p class="text-slate-400 text-sm mb-4">Analyze Medical Reports</p>
                        <p class="text-slate-500 text-xs mb-4">Upload and get AI-powered analysis of your medical reports and lab results.</p>
                        <a href="{{ route('reports.index') }}" class="text-orange-400 text-sm font-medium hover:text-orange-300 transition-colors">
                            Analyze Reports →
                        </a>
                    </div>
                    
                    <!-- Health Plan Generator Card -->
                    <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 hover:border-green-500 transition-colors">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-12 h-12 bg-green-500/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-clipboard-list text-green-400 text-xl"></i>
                            </div>
                        </div>
                        <h3 class="text-lg font-semibold text-white mb-2">Health Plan Generator</h3>
                        <p class="text-slate-400 text-sm mb-4">Create Personal Health Plan</p>
                        <p class="text-slate-500 text-xs mb-4">Generate personalized health and wellness plans based on your profile.</p>
                        <a href="{{ route('health-plans.index') }}" class="text-green-400 text-sm font-medium hover:text-green-300 transition-colors">
                            Generate Plan →
                        </a>
                    </div>
                    
                    <!-- Health Metrics Tracking Card -->
                    <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 hover:border-cyan-500 transition-colors">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-12 h-12 bg-cyan-500/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-chart-line text-cyan-400 text-xl"></i>
                            </div>
                        </div>
                        <h3 class="text-lg font-semibold text-white mb-2">Health Metrics Tracking</h3>
                        <p class="text-slate-400 text-sm mb-4">Track Metrics</p>
                        <p class="text-slate-500 text-xs mb-4">Monitor your blood pressure, weight, medications, and more.</p>
                        <a href="{{ route('health-metrics.index') }}" class="text-cyan-400 text-sm font-medium hover:text-cyan-300 transition-colors">
                            View Health Data →
                        </a>
                    </div>
                </div>
                
                <!-- Quick Overview Section -->
                <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 mb-6">
                    <h2 class="text-xl font-semibold text-white mb-6">Quick Overview</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- AI Chat Active -->
                        <div class="bg-slate-900 rounded-lg p-4 border border-slate-700">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-blue-500/20 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-comment-medical text-blue-400"></i>
                                    </div>
                                    <div>
                                        <p class="text-slate-400 text-xs">AI Chat</p>
                                        <p class="text-white font-semibold">Active</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Symptoms Check -->
                        <div class="bg-slate-900 rounded-lg p-4 border border-slate-700">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-pink-500/20 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-stethoscope text-pink-400"></i>
                                    </div>
                                    <div>
                                        <p class="text-slate-400 text-xs">Symptoms</p>
                                        <p class="text-white font-semibold">Check</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Health Data Track -->
                        <div class="bg-slate-900 rounded-lg p-4 border border-slate-700">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-green-500/20 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-chart-line text-green-400"></i>
                                    </div>
                                    <div>
                                        <p class="text-slate-400 text-xs">Health Data</p>
                                        <p class="text-white font-semibold">Track</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
