<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI HealthCare Pro - {{ $title ?? 'Dashboard' }}</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom Styles -->
    <style>
        :root {
            --primary: #2563eb;
            --primary-light: #3b82f6;
            --primary-dark: #1d4ed8;
            --accent: #3b82f6;
            --dark: #0f172a;
            --darker: #020617;
            --light: #f8fafc;
            --gray: #64748b;
            --light-gray: #e2e8f0;
            --card-bg: rgba(255, 255, 255, 0.03);
            --card-border: rgba(255, 255, 255, 0.05);
            --card-hover: rgba(255, 255, 255, 0.05);
        }
        
        body { 
            font-family: 'Inter', sans-serif;
            background: radial-gradient(ellipse at top left, #0f172a 0%, #020617 100%);
            color: #e2e8f0;
            min-height: 100vh;
        }
        
        .font-space { font-family: 'Space Grotesk', sans-serif; }
        
        /* Glassmorphism Effect */
        .glass-effect {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        /* Card Styles */
        .card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 16px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2), 0 10px 10px -5px rgba(0, 0, 0, 0.1);
            border-color: rgba(59, 130, 246, 0.2);
        }
        
        /* Gradient Text */
        .gradient-text {
            background: linear-gradient(90deg, #3b82f6, #60a5fa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.03);
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: rgba(59, 130, 246, 0.3);
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(59, 130, 246, 0.5);
        }
        
        /* Animations */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
        
        .float-animation {
            animation: float 6s ease-in-out infinite;
        }
        
        /* Custom Checkbox */
        .custom-checkbox {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            width: 18px;
            height: 18px;
            border: 2px solid #3b82f6;
            border-radius: 4px;
            outline: none;
            cursor: pointer;
            position: relative;
            transition: all 0.2s;
        }
        
        .custom-checkbox:checked {
            background-color: #3b82f6;
        }
        
        .custom-checkbox:checked::after {
            content: '✓';
            position: absolute;
            color: white;
            font-size: 12px;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
    </style>
    
    @stack('styles')
</head>
<body class="min-h-screen overflow-x-hidden">
    <!-- Animated Background Elements -->
    <div class="fixed inset-0 -z-10 overflow-hidden">
        <div class="absolute top-0 left-1/4 w-72 h-72 bg-blue-500/5 rounded-full mix-blend-multiply filter blur-3xl opacity-20"></div>
        <div class="absolute top-0 right-1/4 w-72 h-72 bg-blue-600/5 rounded-full mix-blend-multiply filter blur-3xl opacity-20"></div>
        <div class="absolute bottom-0 left-1/2 w-72 h-72 bg-blue-700/5 rounded-full mix-blend-multiply filter blur-3xl opacity-20"></div>
    </div>

    <!-- Main Content -->
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="glass-effect border-b border-gray-800/50">
            <div class="container mx-auto px-4 py-4">
                <div class="flex items-center justify-between">
                    <!-- Logo -->
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-600 to-blue-400 flex items-center justify-center text-white shadow-lg">
                            <i class="fas fa-brain text-lg"></i>
                        </div>
                        <h1 class="text-xl font-bold text-white font-space">AI HealthCare Pro</h1>
                    </div>
                    
                    <!-- Navigation -->
                    <nav class="hidden md:flex items-center space-x-1">
                        <a href="#" class="px-4 py-2 text-sm font-medium text-blue-400 hover:bg-blue-900/30 rounded-lg transition-colors">Home</a>
                        <a href="#" class="px-4 py-2 text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-800/50 rounded-lg transition-colors">Features</a>
                        <a href="#" class="px-4 py-2 text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-800/50 rounded-lg transition-colors">Pricing</a>
                        <a href="#" class="px-4 py-2 text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-800/50 rounded-lg transition-colors">About</a>
                        <a href="#" class="px-4 py-2 text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-800/50 rounded-lg transition-colors">Contact</a>
                    </nav>
                    
                    <!-- Auth Buttons -->
                    <div class="flex items-center space-x-3">
                        <a href="#" class="px-4 py-2 text-sm font-medium text-blue-400 hover:text-white transition-colors hidden md:block">Login</a>
                        <a href="#" class="px-4 py-2 text-sm font-medium bg-gradient-to-r from-blue-600 to-blue-500 text-white rounded-lg hover:opacity-90 transition-opacity">Get Started</a>
                        
                        <!-- Mobile Menu Button -->
                        <button id="mobileMenuBtn" class="md:hidden p-2 text-gray-400 hover:text-white hover:bg-gray-800/50 rounded-lg transition-colors">
                            <i class="fas fa-bars text-lg"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Mobile Menu -->
            <div id="mobileMenu" class="md:hidden hidden bg-gray-900/95 backdrop-blur-lg border-t border-gray-800/50">
                <div class="container mx-auto px-4 py-3 flex flex-col space-y-2">
                    <a href="#" class="px-3 py-2 text-sm font-medium text-blue-400 rounded-lg">Home</a>
                    <a href="#" class="px-3 py-2 text-sm font-medium text-gray-300 hover:text-white rounded-lg">Features</a>
                    <a href="#" class="px-3 py-2 text-sm font-medium text-gray-300 hover:text-white rounded-lg">Pricing</a>
                    <a href="#" class="px-3 py-2 text-sm font-medium text-gray-300 hover:text-white rounded-lg">About</a>
                    <a href="#" class="px-3 py-2 text-sm font-medium text-gray-300 hover:text-white rounded-lg">Contact</a>
                    <div class="pt-2 mt-2 border-t border-gray-800/50">
                        <a href="#" class="block px-3 py-2 text-sm font-medium text-center text-blue-400 hover:text-white rounded-lg">Login</a>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-grow">
            @yield('content')
        </main>
        
        <!-- Footer -->
        <footer class="glass-effect border-t border-gray-800/50 mt-12">
            <div class="container mx-auto px-4 py-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <!-- Company Info -->
                    <div class="col-span-1">
                        <div class="flex items-center space-x-2 mb-4">
                            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-600 to-blue-400 flex items-center justify-center">
                                <i class="fas fa-brain text-white text-sm"></i>
                            </div>
                            <span class="text-lg font-bold text-white">AI HealthCare Pro</span>
                        </div>
                        <p class="text-sm text-gray-400">Revolutionizing healthcare with AI-powered solutions for better patient outcomes and medical insights.</p>
                        <div class="flex space-x-3 mt-4">
                            <a href="#" class="text-gray-400 hover:text-blue-400 transition-colors">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-blue-400 transition-colors">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-blue-400 transition-colors">
                                <i class="fab fa-github"></i>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Quick Links -->
                    <div class="col-span-1">
                        <h3 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Quick Links</h3>
                        <ul class="space-y-2">
                            <li><a href="#" class="text-sm text-gray-400 hover:text-blue-400 transition-colors">Home</a></li>
                            <li><a href="#" class="text-sm text-gray-400 hover:text-blue-400 transition-colors">Features</a></li>
                            <li><a href="#" class="text-sm text-gray-400 hover:text-blue-400 transition-colors">Pricing</a></li>
                            <li><a href="#" class="text-sm text-gray-400 hover:text-blue-400 transition-colors">About Us</a></li>
                            <li><a href="#" class="text-sm text-gray-400 hover:text-blue-400 transition-colors">Contact</a></li>
                        </ul>
                    </div>
                    
                    <!-- Resources -->
                    <div class="col-span-1">
                        <h3 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Resources</h3>
                        <ul class="space-y-2">
                            <li><a href="#" class="text-sm text-gray-400 hover:text-blue-400 transition-colors">Documentation</a></li>
                            <li><a href="#" class="text-sm text-gray-400 hover:text-blue-400 transition-colors">API Reference</a></li>
                            <li><a href="#" class="text-sm text-gray-400 hover:text-blue-400 transition-colors">Guides</a></li>
                            <li><a href="#" class="text-sm text-gray-400 hover:text-blue-400 transition-colors">Blog</a></li>
                            <li><a href="#" class="text-sm text-gray-400 hover:text-blue-400 transition-colors">Support</a></li>
                        </ul>
                    </div>
                    
                    <!-- Newsletter -->
                    <div class="col-span-1">
                        <h3 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Subscribe to our newsletter</h3>
                        <p class="text-sm text-gray-400 mb-4">Get the latest updates and news.</p>
                        <form class="space-y-2">
                            <input type="email" placeholder="Your email address" class="w-full px-4 py-2 bg-gray-800/50 border border-gray-700 rounded-lg text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                            <button type="submit" class="w-full px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-500 text-white text-sm font-medium rounded-lg hover:opacity-90 transition-opacity">Subscribe</button>
                        </form>
                    </div>
                </div>
                
                <!-- Copyright -->
                <div class="border-t border-gray-800/50 mt-8 pt-6 flex flex-col md:flex-row justify-between items-center">
                    <p class="text-xs text-gray-500">© 2023 AI HealthCare Pro. All rights reserved.</p>
                    <div class="flex space-x-6 mt-4 md:mt-0">
                        <a href="#" class="text-xs text-gray-500 hover:text-blue-400 transition-colors">Privacy Policy</a>
                        <a href="#" class="text-xs text-gray-500 hover:text-blue-400 transition-colors">Terms of Service</a>
                        <a href="#" class="text-xs text-gray-500 hover:text-blue-400 transition-colors">Cookie Policy</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Scripts -->
    <script>
        // Mobile menu toggle
        document.getElementById('mobileMenuBtn').addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobileMenu');
            mobileMenu.classList.toggle('hidden');
            
            // Toggle icon between bars and times
            const icon = this.querySelector('i');
            if (icon.classList.contains('fa-bars')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });
        
        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const mobileMenu = document.getElementById('mobileMenu');
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            
            if (!mobileMenu.contains(event.target) && !mobileMenuBtn.contains(event.target)) {
                mobileMenu.classList.add('hidden');
                const icon = mobileMenuBtn.querySelector('i');
                if (icon.classList.contains('fa-times')) {
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                }
            }
        });
        
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 80,
                        behavior: 'smooth'
                    });
                    
                    // Close mobile menu if open
                    const mobileMenu = document.getElementById('mobileMenu');
                    if (!mobileMenu.classList.contains('hidden')) {
                        mobileMenu.classList.add('hidden');
                        const icon = document.querySelector('#mobileMenuBtn i');
                        if (icon.classList.contains('fa-times')) {
                            icon.classList.remove('fa-times');
                            icon.classList.add('fa-bars');
                        }
                    }
                }
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>
