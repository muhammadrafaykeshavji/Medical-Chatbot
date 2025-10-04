<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pricing - AI HealthCare Pro</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        :root {
            /* Backgrounds */
            --color-bg-dark: #0f172a;   /* slate-900 */
            --color-bg-blue-dark: #1e3a8a; /* blue-900 */
            --color-card-bg: rgba(255, 255, 255, 0.1);
            --color-card-border: rgba(255, 255, 255, 0.2);

            /* Text */
            --color-text-primary: #ffffff;
            --color-text-secondary: #d1d5db; /* gray-300 */
            --color-text-muted: #9ca3af; /* gray-400 */
            --color-text-lightblue: #dbeafe; /* blue-100 */

            /* Primary Gradient */
            --gradient-primary: linear-gradient(to right, #2563eb, #4f46e5);
            --gradient-primary-hover: linear-gradient(to right, #1d4ed8, #3730a3);
            
            /* Extra */
            --color-star: #facc15; /* yellow-400 */
            --color-border-blue: #60a5fa; /* blue-400 */
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', sans-serif;
            background: var(--color-bg-dark);
            color: var(--color-text-primary);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

       
        /* Pricing Section */
        .pricing-section {
            padding: 8rem 0 4rem;
            background: var(--color-bg-dark);
        }

        .section-header {
            text-align: center;
            margin-bottom: 4rem;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
            background: linear-gradient(to right, #fff, #a5b4fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .section-subtitle {
            color: var(--color-text-secondary);
            font-size: 1.1rem;
            max-width: 600px;
            margin: 0 auto;
        }

        .pricing-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .pricing-card {
            background: var(--color-card-bg);
            border: 1px solid var(--color-card-border);
            border-radius: 1rem;
            padding: 2.5rem 2rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .pricing-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .pricing-card.popular {
            border: 1px solid var(--color-border-blue);
            box-shadow: 0 0 0 1px var(--color-border-blue);
        }

        .popular-tag {
            position: absolute;
            top: 1rem;
            right: -2rem;
            background: var(--gradient-primary);
            color: white;
            padding: 0.25rem 2rem;
            transform: rotate(45deg);
            font-size: 0.75rem;
            font-weight: 600;
        }

        .pricing-header {
            margin-bottom: 2rem;
            text-align: center;
        }

        .pricing-name {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .pricing-price {
            font-size: 3rem;
            font-weight: 800;
            margin: 1rem 0;
            background: linear-gradient(to right, #60a5fa, #818cf8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .pricing-duration {
            color: var(--color-text-muted);
            font-size: 0.9rem;
        }

        .pricing-features {
            margin: 2rem 0;
        }

        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
            color: var(--color-text-secondary);
        }

        .feature-item i {
            color: #4ade80;
            margin-right: 0.75rem;
            font-size: 1.1rem;
        }

        .pricing-btn {
            display: block;
            width: 100%;
            text-align: center;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            margin-top: 1.5rem;
        }

        .btn-outline {
            background: transparent;
            border: 1px solid var(--color-border-blue);
            color: var(--color-border-blue);
        }

        .btn-outline:hover {
            background: rgba(96, 165, 250, 0.1);
        }

        .btn-primary {
            background: var(--gradient-primary);
            color: white;
            border: none;
        }

        .btn-primary:hover {
            background: var(--gradient-primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
        }

        /* FAQ Section */
        .faq-section {
            padding: 4rem 0;
            background: var(--color-bg-blue-dark);
        }

        .faq-container {
            max-width: 800px;
            margin: 0 auto;
        }

        .faq-item {
            background: var(--color-card-bg);
            border-radius: 0.75rem;
            margin-bottom: 1rem;
            overflow: hidden;
            border: 1px solid var(--color-card-border);
        }

        .faq-question {
            width: 100%;
            text-align: left;
            background: none;
            border: none;
            padding: 1.25rem 1.5rem;
            font-size: 1.05rem;
            font-weight: 600;
            color: var(--color-text-primary);
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .faq-answer {
            padding: 0 1.5rem;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease, padding 0.3s ease;
            color: var(--color-text-secondary);
        }

        .faq-item.active .faq-answer {
            padding: 0 1.5rem 1.5rem;
            max-height: 300px;
        }

        .faq-icon {
            transition: transform 0.3s ease;
        }

        .faq-item.active .faq-icon {
            transform: rotate(180deg);
        }

       
        /* Responsive */
        @media (max-width: 768px) {
            .pricing-grid {
                grid-template-columns: 1fr;
                max-width: 400px;
                margin-left: auto;
                margin-right: auto;
            }

            .nav-links {
                display: none;
            }

            .mobile-menu {
                display: none;
                position: absolute;
                top: 70px;
                left: 0;
                right: 0;
                background: #0f172a;
                padding: 1rem;
                flex-direction: column;
                gap: 1rem;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            }

            .mobile-menu.show {
                display: flex;
            }

            .mobile-toggle {
                display: block;
            }
        }

        @media (min-width: 769px) {
            .mobile-menu {
                display: none;
            }
        }
    </style>
</head>
<body>
    @include('master.header')
    
    <!-- Breadcrumb -->
    <section class="breadcrumb-section" style="background: linear-gradient(135deg, #0f172a, #1e1b4b); padding: 3rem 0; margin-top: 70px;">
        <div class="container">
            <div class="breadcrumb-content" style="color: white; text-align: center;">
                <h1 style="font-size: 2.5rem; font-weight: 800; margin-bottom: 1rem; background: linear-gradient(to right, #3b82f6, #60a5fa); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; letter-spacing: -0.5px;">Pricing Plans</h1>
                <nav aria-label="breadcrumb" style="display: flex; justify-content: center; align-items: center; gap: 0.5rem; font-size: 1rem; color: var(--color-text-muted);">
                    <a href="/" style="color: #e2e8f0; text-decoration: none; transition: all 0.3s ease; display: flex; align-items: center;" onmouseover="this.style.color='#60a5fa'" onmouseout="this.style.color='#e2e8f0'">
                        <i class="fas fa-home" style="margin-right: 0.5rem;"></i>Home
                    </a>
                    <span style="color: #64748b; font-weight: 600;">/</span>
                    <span style="color: #60a5fa; font-weight: 500;">Pricing</span>
                </nav>
            </div>
        </div>
    </section>

    <!-- Mobile Menu -->
    <div class="mobile-menu" id="mobileMenu">
        <a href="/">Home</a>
        <a href="services">Services</a>
        <a href="pricing" class="active">Pricing</a>
        <a href="contact">Contact</a>
        @auth
            <a href="{{ route('dashboard') }}">Dashboard</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-link" style="background: none; border: none; cursor: pointer; padding: 0; font: inherit; color: var(--color-text-secondary);">
                    {{ __('Log Out') }}
                </button>
            </form>
        @else
            <a href="{{ route('login') }}" class="btn btn-login" style="display: block; text-align: center; margin-top: 1rem;">Log in</a>
            <a href="{{ route('register') }}" class="btn btn-signup" style="display: block; text-align: center; margin-top: 0.5rem;">Sign up</a>
        @endauth
    </div>

    <!-- Pricing Section -->
    <section class="pricing-section" style="padding: 3rem 0 4rem;">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Simple, Transparent Pricing</h2>
                <p class="section-subtitle">Choose the perfect plan for your needs. No hidden fees, cancel anytime.</p>
            </div>

            <div class="pricing-grid">
                <!-- Basic Plan -->
                <div class="pricing-card">
                    <div class="pricing-header">
                        <h3 class="pricing-name">Basic</h3>
                        <div class="pricing-price">$0</div>
                        <p class="pricing-duration">Free forever</p>
                    </div>
                    <div class="pricing-features">
                        <div class="feature-item">
                            <i class="fas fa-check"></i>
                            <span>Basic health insights</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-check"></i>
                            <span>5 reports per month</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-check"></i>
                            <span>Email support</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-check"></i>
                            <span>Basic analytics</span>
                        </div>
                    </div>
                    <a href="#" class="pricing-btn btn-outline">Get Started</a>
                </div>

                <!-- Pro Plan (Popular) -->
                <div class="pricing-card popular">
                    <div class="popular-tag">Popular</div>
                    <div class="pricing-header">
                        <h3 class="pricing-name">Pro</h3>
                        <div class="pricing-price">$29</div>
                        <p class="pricing-duration">per month</p>
                    </div>
                    <div class="pricing-features">
                        <div class="feature-item">
                            <i class="fas fa-check"></i>
                            <span>Everything in Basic</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-check"></i>
                            <span>Unlimited reports</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-check"></i>
                            <span>Priority support</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-check"></i>
                            <span>Advanced analytics</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-check"></i>
                            <span>API access</span>
                        </div>
                    </div>
                    <a href="#" class="pricing-btn btn-primary">Start Free Trial</a>
                </div>

                <!-- Enterprise Plan -->
                <div class="pricing-card">
                    <div class="pricing-header">
                        <h3 class="pricing-name">Enterprise</h3>
                        <div class="pricing-price">Custom</div>
                        <p class="pricing-duration">Tailored for your needs</p>
                    </div>
                    <div class="pricing-features">
                        <div class="feature-item">
                            <i class="fas fa-check"></i>
                            <span>Everything in Pro</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-check"></i>
                            <span>Dedicated account manager</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-check"></i>
                            <span>Custom integrations</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-check"></i>
                            <span>On-premise deployment</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-check"></i>
                            <span>24/7 support</span>
                        </div>
                    </div>
                    <a href="#" class="pricing-btn btn-outline">Contact Sales</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Get Started CTA Section -->
    <section class="get-started-cta" style="padding: 5rem 0; background: linear-gradient(135deg, #1e40af, #3b82f6); position: relative; overflow: hidden;">
        <div class="container" style="position: relative; z-index: 2;">
            <div class="cta-content" style="text-align: center; max-width: 800px; margin: 0 auto; color: white;">
                <h2 style="font-size: 2.5rem; font-weight: 700; margin-bottom: 1.5rem; line-height: 1.3;">
                    Ready to get started with our AI solutions?
                </h2>
                <p style="font-size: 1.25rem; margin-bottom: 2rem; opacity: 0.9; line-height: 1.6;">
                    Join thousands of healthcare professionals who trust our AI platform for accurate and reliable medical assistance.
                </p>
                <div class="cta-buttons" style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                    <a href="{{ route('register') }}" class="btn btn-primary" style="background: white; color: #3b82f6; padding: 0.875rem 2rem; border-radius: 8px; font-weight: 600; text-decoration: none; transition: all 0.3s ease; border: 2px solid white; font-size: 1.1rem;">
                        Sign Up Now
                    </a>
                    <a href="#contact" class="btn btn-outline" style="background: transparent; color: white; padding: 0.875rem 2rem; border-radius: 8px; font-weight: 600; text-decoration: none; transition: all 0.3s ease; border: 2px solid rgba(255, 255, 255, 0.3); font-size: 1.1rem;">
                        Contact Sales
                    </a>
                </div>
            </div>
        </div>
        <!-- Decorative Elements -->
        <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; border-radius: 50%; background: rgba(255, 255, 255, 0.1);"></div>
        <div style="position: absolute; bottom: -80px; left: -50px; width: 250px; height: 250px; border-radius: 50%; background: rgba(255, 255, 255, 0.05);"></div>
    </section>


   

    <!-- Footer -->
    @include('master.footer')

    <script>
        // Mobile menu toggle
        const mobileMenuButton = document.getElementById('mobileMenuButton');
        const mobileMenu = document.getElementById('mobileMenu');
        const menuIcon = document.getElementById('menuIcon');

        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('show');
            menuIcon.classList.toggle('fa-bars');
            menuIcon.classList.toggle('fa-times');
        });

        // FAQ accordion
        const faqItems = document.querySelectorAll('.faq-item');

        faqItems.forEach(item => {
            const question = item.querySelector('.faq-question');
            
            question.addEventListener('click', () => {
                const isActive = item.classList.contains('active');
                
                // Close all items
                faqItems.forEach(faqItem => {
                    faqItem.classList.remove('active');
                });
                
                // Open current item if it was closed
                if (!isActive) {
                    item.classList.add('active');
                }
            });
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
                    if (mobileMenu.classList.contains('show')) {
                        mobileMenu.classList.remove('show');
                        menuIcon.classList.remove('fa-times');
                        menuIcon.classList.add('fa-bars');
                    }
                }
            });
        });
    </script>
</body>
</html>