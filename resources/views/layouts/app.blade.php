<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        
        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('logo-1.png') }}">
        <link rel="shortcut icon" type="image/png" href="{{ asset('logo-1.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        <!-- FontAwesome Icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        
        <!-- Alpine.js -->
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
        
        <!-- Animate.css for smooth animations -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
        
        <!-- AOS (Animate On Scroll) -->
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
        <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
        
        <!-- Custom Premium Styles -->
        <style>
            /* Premium Gradient Backgrounds */
            .gradient-bg-primary {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            }
            .gradient-bg-secondary {
                background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            }
            .gradient-bg-success {
                background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            }
            .gradient-bg-medical {
                background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
            }
            
            /* Glass Morphism Effect */
            .glass {
                background: rgba(255, 255, 255, 0.25);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.18);
            }
            
            /* Premium Shadows */
            .shadow-premium {
                box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            }
            .shadow-glow {
                box-shadow: 0 0 20px rgba(59, 130, 246, 0.3);
            }
            
            /* Smooth Transitions */
            .transition-all {
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }
            
            /* Premium Hover Effects */
            .hover-lift:hover {
                transform: translateY(-5px);
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            }
            
            /* Custom Scrollbar */
            ::-webkit-scrollbar {
                width: 8px;
            }
            ::-webkit-scrollbar-track {
                background: #f1f5f9;
                border-radius: 10px;
            }
            ::-webkit-scrollbar-thumb {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                border-radius: 10px;
            }
            ::-webkit-scrollbar-thumb:hover {
                background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%);
            }
            
            /* Pulse Animation */
            @keyframes pulse-slow {
                0%, 100% { opacity: 1; }
                50% { opacity: 0.7; }
            }
            .pulse-slow {
                animation: pulse-slow 3s infinite;
            }
            
            /* Floating Animation */
            @keyframes float {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-10px); }
            }
            .float {
                animation: float 6s ease-in-out infinite;
            }
        </style>
        
        @stack('styles')
    </head>
    <body class="font-sans antialiased gradient-bg-medical">
        <div class="min-h-screen">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="glass shadow-premium">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                @yield('content')
            </main>
        </div>
        
        <!-- Initialize AOS -->
        <script>
            AOS.init({
                duration: 1000,
                once: true,
                offset: 100
            });
        </script>
        
        @stack('scripts')
    </body>
</html>