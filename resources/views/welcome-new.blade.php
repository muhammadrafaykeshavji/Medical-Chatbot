<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AI HealthCare Pro - Your Personal Health Assistant</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        :root {
            --primary: #2563eb;
            --primary-light: #3b82f6;
            --primary-dark: #1d4ed8;
            --accent: #4f46e5;
            --dark: #0f172a;
            --darker: #020617;
            --light: #f8fafc;
            --gray: #64748b;
            --light-gray: #e2e8f0;
            --gradient: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        html {
            scroll-behavior: smooth;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: var(--darker);
            color: #e2e8f0;
            min-height: 100vh;
            overflow-x: hidden;
            line-height: 1.6;
        }
        
        .font-space { 
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
        }
        
        /* Container */
        .container {
            width: 100%;
            margin: 0 auto;
            padding: 0 1rem;
        }
        
        @media (min-width: 640px) { .container { max-width: 640px; } }
        @media (min-width: 768px) { .container { max-width: 768px; } }
        @media (min-width: 1024px) { .container { max-width: 1024px; } }
        @media (min-width: 1280px) { .container { max-width: 1280px; } }
        @media (min-width: 1536px) { .container { max-width: 1536px; } }
        
        /* Glassmorphism Effect */
        .glass-effect {
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 12px;
        }
        
        .gradient-text {
            background: linear-gradient(90deg, #3b82f6, #8b5cf6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .gradient-bg {
            background: var(--gradient);
        }
        
        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
            text-decoration: none;
            border: none;
            cursor: pointer;
        }
        
        .btn-primary {
            background: var(--gradient);
            color: white;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
        }
        
        .btn-secondary {
            background: rgba(255, 255, 255, 0.05);
            color: #e2e8f0;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }
        
        /* Animations */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        .floating {
            animation: float 6s ease-in-out infinite;
        }
        
        /* Hero Section */
        .hero {
            padding: 8rem 0 6rem;
            position: relative;
            overflow: hidden;
        }
        
        .hero-badge {
            display: inline-block;
            background: rgba(59, 130, 246, 0.1);
            color: #60a5fa;
            font-size: 0.875rem;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            margin-bottom: 1.5rem;
        }
        
        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 1.5rem;
            background: linear-gradient(90deg, #3b82f6, #8b5cf6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .hero-description {
            font-size: 1.25rem;
            color: #94a3b8;
            max-width: 600px;
            margin-bottom: 2.5rem;
            line-height: 1.6;
        }
        
        .hero-actions {
            display: flex;
            gap: 1rem;
            margin-bottom: 3rem;
        }
        
        .hero-stats {
            display: flex;
            gap: 3rem;
        }
        
        .stat-item {
            text-align: center;
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            background: linear-gradient(90deg, #3b82f6, #8b5cf6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.25rem;
        }
        
        .stat-label {
            color: #94a3b8;
            font-size: 0.875rem;
        }
        
        /* Features Section */
        .section {
            padding: 6rem 0;
        }
        
        .section-header {
            text-align: center;
            max-width: 800px;
            margin: 0 auto 4rem;
        }
        
        .section-badge {
            display: inline-block;
            background: rgba(59, 130, 246, 0.1);
            color: #60a5fa;
            font-size: 0.875rem;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            margin-bottom: 1rem;
        }
        
        .section-title {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }
        
        .section-description {
            color: #94a3b8;
            font-size: 1.125rem;
            line-height: 1.6;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }
        
        .feature-card {
            background: rgba(30, 41, 59, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 1rem;
            padding: 2rem;
            transition: all 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
            border-color: rgba(59, 130, 246, 0.3);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .feature-icon {
            width: 3.5rem;
            height: 3.5rem;
            border-radius: 1rem;
            background: rgba(59, 130, 246, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
            color: #3b82f6;
        }
        
        .feature-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
        }
        
        .feature-description {
            color: #94a3b8;
            line-height: 1.6;
        }
        
        /* How It Works Section */
        .steps-container {
            max-width: 800px;
            margin: 0 auto;
            position: relative;
        }
        
        .step {
            display: flex;
            gap: 2rem;
            margin-bottom: 3rem;
            position: relative;
            z-index: 1;
        }
        
        .step:last-child {
            margin-bottom: 0;
        }
        
        .step-number {
            width: 3rem;
            height: 3rem;
            border-radius: 50%;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            flex-shrink: 0;
            position: relative;
            z-index: 2;
        }
        
        .step-content {
            padding-top: 0.5rem;
        }
        
        .step-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .step-description {
            color: #94a3b8;
            line-height: 1.6;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-actions {
                flex-direction: column;
            }
            
            .hero-stats {
                flex-direction: column;
                gap: 1.5rem;
            }
            
            .section {
                padding: 4rem 0;
            }
            
            .section-title {
                font-size: 2rem;
            }
            
            .step {
                flex-direction: column;
                gap: 1rem;
            }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 text-white min-h-screen">
    <!-- Animated Background Elements -->
    <div class="fixed inset-0 -z-10 overflow-hidden">
        <div class="absolute top-0 left-1/4 w-72 h-72 bg-blue-500/5 rounded-full mix-blend-multiply filter blur-3xl opacity-20"></div>
        <div class="absolute top-0 right-1/4 w-72 h-72 bg-purple-600/5 rounded-full mix-blend-multiply filter blur-3xl opacity-20"></div>
        <div class="absolute bottom-0 left-1/3 w-96 h-96 bg-indigo-600/5 rounded-full mix-blend-multiply filter blur-3xl opacity-20"></div>
    </div>

    <!-- Header -->
    <header class="border-b border-gray-800/50 bg-gray-900/80 backdrop-blur-md sticky top-0 z-50">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <!-- Logo -->
                <a href="/" class="flex items-center space-x-2">
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                        <i class="fas fa-robot text-white text-xl"></i>
                    </div>
                    <span class="text-xl font-bold bg-gradient-to-r from-blue-400 to-purple-500 bg-clip-text text-transparent">AI HealthCare Pro</span>
                </a>
                
                <!-- Desktop Navigation -->
                <nav class="hidden md:flex items-center space-x-8">
                    <a href="#features" class="text-gray-300 hover:text-white transition-colors">Features</a>
                    <a href="#how-it-works" class="text-gray-300 hover:text-white transition-colors">How It Works</a>
                    <a href="#pricing" class="text-gray-300 hover:text-white transition-colors">Pricing</a>
                    <a href="#contact" class="text-gray-300 hover:text-white transition-colors">Contact</a>
                </nav>
                
                <!-- Auth Buttons -->
                <div class="hidden md:flex items-center space-x-4">
                    <a href="/login" class="px-4 py-2 text-sm font-medium text-white hover:bg-gray-800 rounded-lg transition-colors">Log in</a>
                    <a href="/register" class="px-4 py-2 text-sm font-medium bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 rounded-lg transition-colors">Get Started</a>
                </div>
                
                <!-- Mobile Menu Button -->
                <button id="mobile-menu-button" class="md:hidden text-gray-400 hover:text-white focus:outline-none">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>
            
            <!-- Mobile Menu -->
            <div id="mobile-menu" class="md:hidden hidden mt-4 pb-4">
                <a href="#features" class="block py-2 text-gray-300 hover:text-white">Features</a>
                <a href="#how-it-works" class="block py-2 text-gray-300 hover:text-white">How It Works</a>
                <a href="#pricing" class="block py-2 text-gray-300 hover:text-white">Pricing</a>
                <a href="#contact" class="block py-2 text-gray-300 hover:text-white">Contact</a>
                <div class="mt-4 pt-4 border-t border-gray-800">
                    <a href="/login" class="block py-2 text-center text-white mb-2">Log in</a>
                    <a href="/register" class="block py-2 text-center bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg">Get Started</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <span class="hero-badge">AI-POWERED HEALTHCARE</span>
                <h1 class="hero-title">Your Personal AI Health Assistant</h1>
                <p class="hero-description">Experience the future of healthcare with our AI-powered platform. Get instant medical insights, symptom analysis, and personalized health recommendations 24/7.</p>
                
                <div class="flex justify-center gap-4">
                    <a href="/register" class="btn btn-primary">Get Started Free</a>
                    <a href="#how-it-works" class="btn btn-secondary">How It Works</a>
                </div>
                
                <div class="hero-stats mt-16">
                    <div class="stat-item">
                        <div class="stat-number">10K+</div>
                        <div class="stat-label">Active Users</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">98%</div>
                        <div class="stat-label">Accuracy</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">24/7</div>
                        <div class="stat-label">Support</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="section">
        <div class="container mx-auto px-4">
            <div class="section-header">
                <span class="section-badge">Features</span>
                <h2 class="section-title">Powerful Features for <span class="gradient-text">Better Health</span></h2>
                <p class="section-description">Our AI-powered platform offers comprehensive health solutions tailored to your needs</p>
            </div>
            
            <div class="features-grid">
                <!-- Feature 1 -->
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-stethoscope"></i>
                    </div>
                    <h3 class="feature-title">Symptom Checker</h3>
                    <p class="feature-description">Get instant analysis of your symptoms with our advanced AI diagnostic tool.</p>
                </div>
                
                <!-- Feature 2 -->
                <div class="feature-card">
                    <div class="feature-icon" style="background: rgba(168, 85, 247, 0.1); color: #a855f7;">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <h3 class="feature-title">Appointment Scheduling</h3>
                    <p class="feature-description">Book appointments with healthcare providers directly through our platform.</p>
                </div>
                
                <!-- Feature 3 -->
                <div class="feature-card">
                    <div class="feature-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                        <i class="fas fa-pills"></i>
                    </div>
                    <h3 class="feature-title">Medication Tracker</h3>
                    <p class="feature-description">Never miss a dose with our smart medication reminder system.</p>
                </div>
                
                <!-- Feature 4 -->
                <div class="feature-card">
                    <div class="feature-icon" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                        <i class="fas fa-heartbeat"></i>
                    </div>
                    <h3 class="feature-title">Health Monitoring</h3>
                    <p class="feature-description">Track your vital signs and health metrics in real-time.</p>
                </div>
                
                <!-- Feature 5 -->
                <div class="feature-card">
                    <div class="feature-icon" style="background: rgba(236, 72, 153, 0.1); color: #ec4899;">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3 class="feature-title">Health Analytics</h3>
                    <p class="feature-description">Get detailed insights and trends about your health data.</p>
                </div>
                
                <!-- Feature 6 -->
                <div class="feature-card">
                    <div class="feature-icon" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3 class="feature-title">Secure & Private</h3>
                    <p class="feature-description">Your health data is encrypted and protected with the highest security standards.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" class="section bg-gray-900/50">
        <div class="container mx-auto px-4">
            <div class="section-header">
                <span class="section-badge">How It Works</span>
                <h2 class="section-title">Get Started in <span class="gradient-text">3 Simple Steps</span></h2>
                <p class="section-description">Our platform makes it easy to take control of your health journey</p>
            </div>
            
            <div class="steps-container">
                <!-- Step 1 -->
                <div class="step">
                    <div class="step-number">1</div>
                    <div class="step-content">
                        <h3 class="step-title">Create Your Account</h3>
                        <p class="step-description">Sign up for free and complete your health profile to get personalized recommendations.</p>
                    </div>
                </div>
                
                <!-- Step 2 -->
                <div class="step">
                    <div class="step-number">2</div>
                    <div class="step-content">
                        <h3 class="step-title">Describe Your Symptoms</h3>
                        <p class="step-description">Chat with our AI assistant and provide details about how you're feeling.</p>
                    </div>
                </div>
                
                <!-- Step 3 -->
                <div class="step">
                    <div class="step-number">3</div>
                    <div class="step-content">
                        <h3 class="step-title">Get Insights & Recommendations</h3>
                        <p class="step-description">Receive instant analysis and personalized health recommendations.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-blue-900/30 to-purple-900/30">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl md:text-4xl font-bold mb-6">Ready to take control of your health?</h2>
            <p class="text-xl text-gray-300 mb-8 max-w-2xl mx-auto">Join thousands of users who trust our AI health assistant for their healthcare needs</p>
            <a href="/register" class="inline-block px-8 py-4 bg-white text-gray-900 font-semibold rounded-lg hover:bg-gray-100 transition-colors">
                Get Started for Free
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900/80 backdrop-blur-md border-t border-gray-800/50">
        <div class="container mx-auto px-4 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Company Info -->
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                            <i class="fas fa-robot text-white text-sm"></i>
                        </div>
                        <span class="text-xl font-bold bg-gradient-to-r from-blue-400 to-purple-500 bg-clip-text text-transparent">AI HealthCare</span>
                    </div>
                    <p class="text-gray-400 text-sm">Empowering better health decisions through artificial intelligence.</p>
                    <div class="flex space-x-4 mt-4">
                        <a href="#" class="text-gray-400 hover:text-white transition-colors"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors"><i class="fab fa-linkedin"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h3 class="text-white font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="#features" class="text-gray-400 hover:text-white transition-colors">Features</a></li>
                        <li><a href="#how-it-works" class="text-gray-400 hover:text-white transition-colors">How It Works</a></li>
                        <li><a href="#pricing" class="text-gray-400 hover:text-white transition-colors">Pricing</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">About Us</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Contact</a></li>
                    </ul>
                </div>
                
                <!-- Resources -->
                <div>
                    <h3 class="text-white font-semibold mb-4">Resources</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Blog</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Help Center</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">FAQ</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Privacy Policy</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Terms of Service</a></li>
                    </ul>
                </div>
                
                <!-- Contact -->
                <div>
                    <h3 class="text-white font-semibold mb-4">Contact Us</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li class="flex items-start">
                            <i class="fas fa-envelope mt-1 mr-2 text-blue-400"></i>
                            <span>support@aihealthcare.com</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-phone-alt mt-1 mr-2 text-blue-400"></i>
                            <span>+1 (555) 123-4567</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-map-marker-alt mt-1 mr-2 text-blue-400"></i>
                            <span>123 AI Street, Tech City, TC 12345</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-12 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-sm text-gray-500">© 2023 AI HealthCare Pro. All rights reserved.</p>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <a href="#" class="text-xs text-gray-500 hover:text-blue-400 transition-colors">Privacy Policy</a>
                    <a href="#" class="text-xs text-gray-500 hover:text-blue-400 transition-colors">Terms of Service</a>
                    <a href="#" class="text-xs text-gray-500 hover:text-blue-400 transition-colors">Cookie Policy</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <button id="back-to-top" class="fixed bottom-8 right-8 w-12 h-12 bg-blue-600 hover:bg-blue-700 rounded-full flex items-center justify-center text-white shadow-lg transition-all duration-300 opacity-0 invisible">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- Scripts -->
    <script>
        // Mobile Menu Toggle
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        
        if (mobileMenuButton && mobileMenu) {
            mobileMenuButton.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
            });
        }
        
        // Back to Top Button
        const backToTopButton = document.getElementById('back-to-top');
        
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                backToTopButton.classList.remove('opacity-0', 'invisible');
                backToTopButton.classList.add('opacity-100', 'visible');
            } else {
                backToTopButton.classList.remove('opacity-100', 'visible');
                backToTopButton.classList.add('opacity-0', 'invisible');
            }
        });
        
        backToTopButton.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
        
        // Smooth scrolling for anchor links
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
                    if (mobileMenu && !mobileMenu.classList.contains('hidden')) {
                        mobileMenu.classList.add('hidden');
                    }
                }
            });
        });
    </script>
</body>
</html>
