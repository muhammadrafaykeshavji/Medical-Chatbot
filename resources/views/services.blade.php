<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Our Healthcare Services - AI HealthCare Pro</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    
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
        .section-title-1 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
            background: linear-gradient(to right, #fff, #a5b4fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-align: center;
        }

        .section-subtitle-1 {
            color: var(--color-text-secondary);
            font-size: 1.1rem;
            max-width: 600px;
            margin: 0 auto;
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

       

  /* Text */
  --color-text-primary: #ffffff;
  --color-text-secondary: #d1d5db; /* gray-300 */
  --color-text-muted: #9ca3af; /* gray-400 */
  --color-text-lightblue: #dbeafe; /* blue-100 */

  /* Primary Gradient */
  --gradient-primary: linear-gradient(to right, #2563eb, #4f46e5); /* blue-600 → indigo-600 */
  --gradient-primary-hover: linear-gradient(to right, #1d4ed8, #3730a3); /* hover */

  /* Accent Gradients */
  --gradient-blue-indigo: linear-gradient(to right, #3b82f6, #6366f1);
  --gradient-green-emerald: linear-gradient(to right, #22c55e, #10b981);
  --gradient-orange-red: linear-gradient(to right, #f97316, #ef4444);
  --gradient-indigo-purple: linear-gradient(to right, #6366f1, #8b5cf6);
  --gradient-blue-cyan: linear-gradient(to right, #3b82f6, #06b6d4);

  /* Extra */
  --color-star: #facc15; /* yellow-400 */
  --color-border-blue: #60a5fa; /* blue-400 */
  
  /* Backward compatibility */
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
    font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', sans-serif;
    background: var(--darker);
    color: #e2e8f0;
    min-height: 100vh;
    overflow-x: hidden;
    line-height: 1.6;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

.font-space { 
    font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    font-weight: 700;
    letter-spacing: -0.02em;
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

/* Breadcrumb */
.breadcrumb {
    padding: 1rem 0;
    font-size: 0.875rem;
    color: rgba(255, 255, 255, 0.8);
}

.breadcrumb a {
    color: #93c5fd;
    text-decoration: none;
    transition: color 0.2s;
}

.breadcrumb a:hover {
    color: #ffffff;
    text-decoration: underline;
}

.breadcrumb .separator {
    margin: 0 0.5rem;
    color: rgba(255, 255, 255, 0.5);
}

.breadcrumb .current {
    color: rgba(255, 255, 255, 0.8);
}

/* Hero Section */
.hero-section {
    position: relative;
    background: var(--gradient-primary);
    color: white;
    padding: 8rem 0 6rem;
    overflow: hidden;
}

.hero-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29-22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.05' fill-rule='evenodd'/%3E%3C/svg%3E");
    opacity: 0.5;
}

.hero-content {
    position: relative;
    z-index: 1;
    text-align: center;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1.5rem;
}

.hero-title {
    font-size: 3.5rem;
    font-weight: 800;
    line-height: 1.1;
    margin-bottom: 1.5rem;
    background: linear-gradient(to right, #fff, #e0e7ff);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.hero-description {
    font-size: 1.25rem;
    color: var(--color-text-secondary);
    max-width: 42rem;
    margin: 0 auto 2.5rem;
    line-height: 1.6;
}

/* Services Section */
.services-section {
    padding: 6rem 0;
    background-color: var(--darker);
}

.section-title {
    text-align: center;
    margin-bottom: 4rem;
}

.section-title h2 {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
    color: white;
}

.section-title p {
    color: var(--color-text-secondary);
    max-width: 42rem;
    margin: 0 auto;
}

.services-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    margin-top: 3rem;
}

.service-card {
    background: rgba(30, 41, 59, 0.7);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 1rem;
    padding: 2.5rem 2rem;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    backdrop-filter: blur(10px);
}

.service-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    border-color: rgba(99, 102, 241, 0.5);
}

.service-icon {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1.5rem;
    font-size: 1.75rem;
    background: rgba(99, 102, 241, 0.1);
    color: #6366f1;
}

.service-card h3 {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 1rem;
    color: white;
}

.service-card p {
    color: var(--color-text-secondary);
    margin-bottom: 1.5rem;
    line-height: 1.6;
}

.service-link {
    display: inline-flex;
    align-items: center;
    color: #60a5fa;
    font-weight: 500;
    text-decoration: none;
    transition: color 0.2s;
}

.service-link:hover {
    color: #3b82f6;
}

.service-link i {
    margin-left: 0.5rem;
    transition: transform 0.2s;
}

.service-link:hover i {
    transform: translateX(4px);
}
    </style>
</head>
<body>
    @include('master.header')

    <!-- Hero Section -->
    <section class="breadcrumb-section" style="background: linear-gradient(135deg, #0f172a, #1e1b4b); padding: 3rem 0; margin-top: 70px;">
        <div class="container">
            <div class="breadcrumb-content" style="color: white; text-align: center;">
                <h1 style="font-size: 2.5rem; font-weight: 800; margin-bottom: 1rem; background: linear-gradient(to right, #3b82f6, #60a5fa); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; letter-spacing: -0.5px;">Our Services</h1>
                <nav aria-label="breadcrumb" style="display: flex; justify-content: center; align-items: center; gap: 0.5rem; font-size: 1rem; color: var(--color-text-muted);">
                    <a href="/" style="color: #e2e8f0; text-decoration: none; transition: all 0.3s ease; display: flex; align-items: center;" onmouseover="this.style.color='#60a5fa'" onmouseout="this.style.color='#e2e8f0'">
                        <i class="fas fa-home" style="margin-right: 0.5rem;"></i>Home
                    </a>
                    <span style="color: #64748b; font-weight: 600;">/</span>
                    <span style="color: #60a5fa; font-weight: 500;">Services</span>
                </nav>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services-section">
        <div class="container">
            <div class="section-title">
                <h2>Our Medical Services</h2>
                <p>We offer a wide range of healthcare services to meet all your medical needs with the highest standards of care.</p>
            </div>
            
            <div class="services-grid">
                <!-- Service 1 -->
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-heartbeat"></i>
                    </div>
                    <h3>Cardiology</h3>
                    <p>Comprehensive heart care services including diagnostics, treatment, and preventive care for all cardiac conditions.</p>
                    <a href="#" class="service-link">Learn more <i class="fas fa-arrow-right"></i></a>
                </div>

                <!-- Service 2 -->
                <div class="service-card">
                    <div class="service-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                        <i class="fas fa-brain"></i>
                    </div>
                    <h3>Neurology</h3>
                    <p>Expert diagnosis and treatment for disorders of the nervous system, including the brain and spinal cord.</p>
                    <a href="#" class="service-link">Learn more <i class="fas fa-arrow-right"></i></a>
                </div>

                <!-- Service 3 -->
                <div class="service-card">
                    <div class="service-icon" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                        <i class="fas fa-bone"></i>
                    </div>
                    <h3>Orthopedics</h3>
                    <p>Specialized care for musculoskeletal system including bones, joints, ligaments, tendons, and muscles.</p>
                    <a href="#" class="service-link">Learn more <i class="fas fa-arrow-right"></i></a>
                </div>

                <!-- Service 4 -->
                <div class="service-card">
                    <div class="service-icon" style="background: rgba(139, 92, 246, 0.1); color: #8b5cf6;">
                        <i class="fas fa-tooth"></i>
                    </div>
                    <h3>Dental Care</h3>
                    <p>Complete dental services including preventive, cosmetic, and restorative treatments for all ages.</p>
                    <a href="#" class="service-link">Learn more <i class="fas fa-arrow-right"></i></a>
                </div>

                <!-- Service 5 -->
                <div class="service-card">
                    <div class="service-icon" style="background: rgba(239, 68, 68, 0.1); color: #ef4444;">
                        <i class="fas fa-eye"></i>
                    </div>
                    <h3>Ophthalmology</h3>
                    <p>Comprehensive eye care including vision testing, medical and surgical eye care, and management of eye disorders.</p>
                    <a href="#" class="service-link">Learn more <i class="fas fa-arrow-right"></i></a>
                </div>

                <!-- Service 6 -->
                <div class="service-card">
                    <div class="service-icon" style="background: rgba(236, 72, 153, 0.1); color: #ec4899;">
                        <i class="fas fa-baby"></i>
                    </div>
                    <h3>Pediatrics</h3>
                    <p>Comprehensive healthcare for infants, children, and adolescents focusing on their physical, emotional, and social health.</p>
                    <a href="#" class="service-link">Learn more <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </section>


           
          

<!-- Emergency CTA Section -->
<section class="py-16 bg-blue-600 text-white">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-6">Need Emergency Medical Care?</h2>
        <p class="text-xl text-blue-100 mb-8 max-w-3xl mx-auto">Our emergency department is open 24/7 to provide immediate medical attention for serious conditions.</p>
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="tel:+1234567890" class="bg-white text-blue-600 hover:bg-blue-50 font-semibold py-3 px-8 rounded-lg transition-colors duration-300">
                <i class="fas fa-phone-alt mr-2"></i> Call Now: (123) 456-7890
            </a>
            <a href="#contact" class="border-2 border-white text-white hover:bg-white hover:bg-opacity-10 font-semibold py-3 px-8 rounded-lg transition-colors duration-300">
                Contact Us
            </a>
        </div>
    </div>
</section>

<section class="why-choose-us" style="padding: 5rem 0; background-color: #0f172a;">
        <div class="container">
            <div class="section-header" style="text-align: center; margin-bottom: 4rem;">
                <h2 style="font-size: 2.5rem; font-weight: 700; margin-bottom: 1rem; background: linear-gradient(to right, #3b82f6, #60a5fa); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                    Why Choose Us
                </h2>
                <p style="color: #94a3b8; max-width: 700px; margin: 0 auto; font-size: 1.1rem; line-height: 1.6;">
                    We provide the best medical AI solutions with cutting-edge technology and exceptional support.
                </p>
            </div>

            <div class="features-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
                <!-- Feature 1 -->
                <div class="feature-card" style="background: rgba(255, 255, 255, 0.05); border-radius: 12px; padding: 2rem; transition: transform 0.3s ease, box-shadow 0.3s ease; border: 1px solid rgba(255, 255, 255, 0.1);">
                    <div class="feature-icon" style="width: 60px; height: 60px; background: linear-gradient(135deg, #3b82f6, #60a5fa); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 1.5rem;">
                        <i class="fas fa-shield-alt" style="font-size: 1.75rem; color: white;"></i>
                    </div>
                    <h3 style="font-size: 1.5rem; font-weight: 600; margin-bottom: 1rem; color: white;">Secure & Private</h3>
                    <p style="color: #94a3b8; line-height: 1.7; margin: 0;">
                        Your data is encrypted and protected with enterprise-grade security measures to ensure complete privacy and confidentiality.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="feature-card" style="background: rgba(255, 255, 255, 0.05); border-radius: 12px; padding: 2rem; transition: transform 0.3s ease, box-shadow 0.3s ease; border: 1px solid rgba(255, 255, 255, 0.1);">
                    <div class="feature-icon" style="width: 60px; height: 60px; background: linear-gradient(135deg, #3b82f6, #60a5fa); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 1.5rem;">
                        <i class="fas fa-headset" style="font-size: 1.75rem; color: white;"></i>
                    </div>
                    <h3 style="font-size: 1.5rem; font-weight: 600; margin-bottom: 1rem; color: white;">24/7 Support</h3>
                    <p style="color: #94a3b8; line-height: 1.7; margin: 0;">
                        Our dedicated support team is available around the clock to assist you with any questions or issues you may have.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="feature-card" style="background: rgba(255, 255, 255, 0.05); border-radius: 12px; padding: 2rem; transition: transform 0.3s ease, box-shadow 0.3s ease; border: 1px solid rgba(255, 255, 255, 0.1);">
                    <div class="feature-icon" style="width: 60px; height: 60px; background: linear-gradient(135deg, #3b82f6, #60a5fa); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 1.5rem;">
                        <i class="fas fa-rocket" style="font-size: 1.75rem; color: white;"></i>
                    </div>
                    <h3 style="font-size: 1.5rem; font-weight: 600; margin-bottom: 1rem; color: white;">Fast & Reliable</h3>
                    <p style="color: #94a3b8; line-height: 1.7; margin: 0;">
                        Experience lightning-fast response times and 99.9% uptime with our high-performance infrastructure.
                    </p>
                </div>
            </div>
        </div>
    </section>

     <!-- FAQ Section -->
     <section class="faq-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title-1">Frequently Asked Questions</h2>
                <p class="section-subtitle-1">Find answers to common questions about our pricing and plans.</p>
            </div>
            &nbsp;
            <div class="faq-container">
                <div class="faq-item">
                    <button class="faq-question">
                        What payment methods do you accept?
                        <i class="fas fa-chevron-down faq-icon"></i>
                    </button>
                    <div class="faq-answer">
                        We accept all major credit cards including Visa, Mastercard, American Express, and Discover. We also support payments via PayPal and bank transfers for enterprise customers.
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-question">
                        Can I change my plan later?
                        <i class="fas fa-chevron-down faq-icon"></i>
                    </button>
                    <div class="faq-answer">
                        Yes, you can upgrade or downgrade your plan at any time from your account settings. Changes will be prorated based on your billing cycle.
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-question">
                        Is there a free trial available?
                        <i class="fas fa-chevron-down faq-icon"></i>
                    </button>
                    <div class="faq-answer">
                        Yes, we offer a 14-day free trial for our Pro plan. No credit card is required to start your trial.
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-question">
                        How does the cancellation work?
                        <i class="fas fa-chevron-down faq-icon"></i>
                    </button>
                    <div class="faq-answer">
                        You can cancel your subscription at any time from your account settings. Your subscription will remain active until the end of your current billing period, and you won't be charged again.
                    </div>
                </div>
            </div>
        </div>
    </section>



    @include('master.footer')
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Smooth scrolling for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        window.scrollTo({
                            top: target.offsetTop - 80,
                            behavior: 'smooth'
                        });
                    }
                });
            });

            
            // Add active class to current nav item
            const currentLocation = location.href;
            const menuItems = document.querySelectorAll('.nav-links a');
            const menuLength = menuItems.length;
            
            for (let i = 0; i < menuLength; i++) {
                if (menuItems[i].href === currentLocation) {
                    menuItems[i].classList.add('active');
                }
            }
        });
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

    </script>
</body>
</html>
</body>
</html>