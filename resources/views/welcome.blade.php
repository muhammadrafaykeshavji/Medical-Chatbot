<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AI HealthCare Pro - Your Personal Health Assistant</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('logo-1.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('logo-1.png') }}">
    
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
        
        /* Glassmorphism Effect */
        .glass-effect {
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 12px;
        }
        
        .gradient-text {
            background: var(--gradient);
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
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        
        .float {
            animation: float 6s ease-in-out infinite;
        }
        
        .pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        
        
        
        /* Hero Section */
.hero {
    min-height: 100vh;
    display: flex;
    align-items: center;
    padding-top: 5rem;
    padding-bottom: 5rem;
    position: relative;
    overflow: hidden;
}

.hero::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle at center, rgba(59, 130, 246, 0.1) 0%, transparent 50%);
    z-index: -1;
    animation: pulse 8s ease-in-out infinite;
}

/* New Flex Layout for Text + Image */
.hero-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 2rem;
    flex-wrap: wrap;
}

.hero-text {
    flex: 1;
    max-width: 42rem;
}

.hero-image {
    flex: 1;
    display: flex;
    justify-content: center;
}

.hero-image img {
    width: 100%;
    height: auto;
    image-rendering: -webkit-optimize-contrast;
    image-rendering: crisp-edges;
    image-rendering: pixelated;
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
    -webkit-transform: translateZ(0);
    transform: translateZ(0);
}

/* Hero Text Styles */
.hero-badge {
    display: inline-block;
    background: rgba(59, 130, 246, 0.1);
    color: var(--primary-light);
    padding: 0.5rem 1rem;
    border-radius: 9999px;
    font-size: 0.875rem;
    font-weight: 500;
    margin-bottom: 1.5rem;
}

.hero-title {
    font-size: 3.75rem;
    font-weight: 800;
    line-height: 1.1;
    margin-bottom: 1.5rem;
    background: linear-gradient(to right, #fff, #a5b4fc);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.hero-description {
    font-size: 1.25rem;
    color: var(--gray);
    margin-bottom: 2.5rem;
    max-width: 36rem;
}

.hero-actions {
    display: flex;
    gap: 1rem;
    margin-bottom: 3rem;
}
.section {
    padding: 60px 20px;
}

.section-header {
    text-align: center;
    margin-bottom: 50px;
}

.step-row {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 50px;
    gap: 30px;
}

.step-row.reverse {
    flex-direction: row-reverse;
}
.step-image{
     background: rgba(15, 23, 42, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 1rem;
            padding: 1.5rem;
}

.step-image img {
    width: 100%;
    max-width: 400px;
    border-radius: 10px;
}

.step-content {
    max-width: 500px;
}
.circle{
width:48px; /* w-12 -> 3rem -> 48px */
height:48px; /* h-12 */
border-radius:9999px; /* rounded-full */
display:flex; /* flex */
align-items:center; /* items-center */
justify-content:center; /* justify-center */
color:#ffffff; /* text-white */
font-weight:700; /* font-bold */
font-size:1.25rem; /* text-xl ~ 1.25rem */
margin-right:1rem; /* mr-4 -> 1rem (16px) */
/* gradient matching from-blue-500 to-indigo-500 (approx) */
background: linear-gradient(90deg,#3b82f6 0%, #6366f1 100%);
/* For better rendering on older browsers: */
-webkit-background-clip: padding-box;
background-clip: padding-box;
margin-bottom: 10px;
}



@media (max-width: 768px) {
    .step-row, .step-row.reverse {
        flex-direction: column;
        text-align: center;
    }
}



/* Responsive Adjustments */
@media (max-width: 767px) {
    .hero-content {
        flex-direction: column;
        text-align: center;
    }

    .hero-title {
        font-size: 2.5rem;
    }

    .hero-actions {
        flex-direction: column;
    }

    .hero-actions .btn {
        width: 100%;
        justify-content: center;
    }
}

        .stat-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .stat-number {
            font-size: 1.875rem;
            font-weight: 700;
            background: var(--gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .stat-label {
            font-size: 0.875rem;
            color: var(--gray);
        }
        
        /* Features Section */
        .section {
            padding: 6rem 0;
        }
        
        .section-header {
            text-align: center;
            max-width: 48rem;
            margin: 0 auto 4rem;
        }
        
        .section-badge {
            display: inline-block;
            background: rgba(59, 130, 246, 0.1);
            color: var(--primary-light);
            padding: 0.5rem 1rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 1rem;
        }
        
        .section-title {
            font-size: 2.25rem;
            font-weight: 800;
            margin-bottom: 1rem;
            background: linear-gradient(to right, #fff, #a5b4fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .section-description {
            font-size: 1.125rem;
            color: var(--gray);
            line-height: 1.75;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(1, 1fr);
            gap: 1.5rem;
        }
        
        @media (min-width: 768px) {
            .features-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (min-width: 1024px) {
            .features-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }
        
        .feature-card {
            background: rgba(15, 23, 42, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 1rem;
            padding: 2rem;
            transition: all 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
            border-color: rgba(99, 102, 241, 0.3);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .feature-icon {
            width: 3rem;
            height: 3rem;
            border-radius: 0.75rem;
            background: rgba(79, 70, 229, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            color: var(--accent);
            font-size: 1.25rem;
        }
        
        .feature-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
            color: white;
        }
        
        .feature-description {
            color: var(--gray);
            line-height: 1.625;
        }
        
        /* How It Works Section */
        .steps-container {
            position: relative;
            max-width: 48rem;
            margin: 0 auto;
        }
        
        .step {
            position: relative;
            padding-left: 3.5rem;
            margin-bottom: 3rem;
        }
        
        .step:last-child {
            margin-bottom: 0;
        }
        
    
        
        
        
        .step-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: white;
        }
        
        .step-description {
            color: var(--gray);
            line-height: 1.625;
        }
        
        /* Testimonials Section */
        .testimonials-grid {
            display: grid;
            grid-template-columns: repeat(1, 1fr);
            gap: 1.5rem;
        }
        
        @media (min-width: 768px) {
            .testimonials-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (min-width: 1024px) {
            .testimonials-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }
        
        .testimonial-card {
            background: rgba(15, 23, 42, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 1rem;
            padding: 2rem;
        }
        
        .testimonial-rating {
            color: #f59e0b;
            margin-bottom: 1rem;
        }
        
        .testimonial-text {
            font-style: italic;
            margin-bottom: 1.5rem;
            color: var(--light-gray);
        }
        
        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .testimonial-avatar {
            width: 3rem;
            height: 3rem;
            border-radius: 9999px;
            object-fit: cover;
        }
        
        .testimonial-info h4 {
            font-weight: 600;
            color: white;
            margin-bottom: 0.25rem;
        }
        
        .testimonial-info p {
            color: var(--gray);
            font-size: 0.875rem;
        }
        
        /* CTA Section */
        .cta {
            background: #1b8fcf;
            padding: 4rem 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .cta::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle at center, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
            z-index: 0;
            animation: pulse 8s ease-in-out infinite;
        }
        
        .upload-box {
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.2);
      border-radius: 1rem;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
      padding: 3rem;
      text-align: center;
    }

    .upload-area {
      border: 2px dashed #60a5fa; /* blue-400 */
      border-radius: 1rem;
      padding: 3rem;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .upload-area:hover {
      background: rgba(255, 255, 255, 0.1);
    }

    .icon-box {
      width: 5rem;
      height: 5rem;
      background: linear-gradient(to right, #3b82f6, #4f46e5);
      border-radius: 1rem;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 1.5rem;
      transition: transform 0.3s ease;
    }

    .upload-area:hover .icon-box {
      transform: scale(1.1);
    }

    .icon-box svg {
      width: 2.5rem;
      height: 2.5rem;
      color: white;
    }

    .upload-btn {
      margin-top: 1rem;
      background: linear-gradient(to right, #2563eb, #4f46e5);
      color: white;
      padding: 1rem 2rem;
      border: none;
      border-radius: 0.75rem;
      font-weight: 600;
      font-size: 1.125rem;
      cursor: pointer;
      text-decoration: none;
      transition: all 0.3s ease;
      display: inline-block;
      box-shadow: 0 6px 15px rgba(37, 99, 235, 0.4);
    }

    .upload-btn:hover {
      background: linear-gradient(to right, #1d4ed8, #3730a3);
      transform: scale(1.05);
    }

    .upload-text {
      margin-top: 1rem;
      color: #d1d5db;
    }

   

    #font{
        font-size: 2.5rem; /* text-5xl */
      font-weight: 800; /* extrabold */
      color: #ffffff;
      margin-bottom: 1rem;
      text-align: center;
    }

    p.lead-text {
      font-size: 1.25rem; /* text-xl */
      margin-top: 1rem;
      color: #d1d5db; /* gray-300 */
      text-align: center;
    }
    .upload-subtext {
      margin-top: 0.5rem;
      font-size: 0.875rem;
      color: #9ca3af;
    }
        .cta-content {
            position: relative;
            z-index: 1;
            max-width: 48rem;
            margin: 0 auto;
        }
        
        .cta-title {
            font-size: 2.25rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            color: white;
        }
        
        .cta-description {
            font-size: 1.125rem;
            color: #e0e7ff;
            margin-bottom: 2.5rem;
            max-width: 40rem;
            margin-left: auto;
            margin-right: auto;
        }
        
        .cta-actions {
            display: flex;
            justify-content: center;
            gap: 1rem;
        }
        
        
        /* Responsive Adjustments */
        @media (max-width: 767px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-actions {
                flex-direction: column;
            }
            
            .hero-actions .btn {
                width: 100%;
                justify-content: center;
            }
            
            .hero-stats {
                flex-direction: column;
                gap: 1rem;
            }
            
            .section {
                padding: 4rem 0;
            }
            
            .section-title {
                font-size: 1.875rem;
            }
            
            .cta-title {
                font-size: 1.875rem;
            }
            
            .cta-actions {
                flex-direction: column;
            }
            
            .cta-actions .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    @include('master.header')
    

    <div class="h-16"></div>
    
    <!-- Hero Section -->
    <section id="home" class="hero">
    <div class="container">
        <div class="hero-content">
            <div class="hero-text">
                <span class="hero-badge">AI-Powered Healthcare</span>
                <h1 class="hero-title">Your Personal AI Health Assistant</h1>
                <p class="hero-description">
                    Get personalized health insights, track your wellness, and connect with healthcare professionals—all in one place, powered by artificial intelligence.
                </p>

                <div class="hero-actions">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn btn-primary">Go to Dashboard</a>
                    @else
                        <a href="{{ route('register') }}" class="btn btn-primary">Get Started for Free</a>
                    @endauth
                    <a href="#features" class="btn btn-secondary">Learn More</a>
                </div>
            </div>

            <div class="hero-image">
                <img src="{{ asset('hero.png') }}" alt="AI Healthcare" style="max-width: 500px; filter: drop-shadow(0 20px 40px rgba(59, 130, 246, 0.3));">
            </div>
        </div>
    </div>
</section>

    
    <!-- Features Section -->
    <section id="features" class="section">
        <div class="container">
            <div class="section-header">
                <span class="section-badge">Features</span>
                <h2 class="section-title">Powerful Features for Better Healthcare</h2>
                <p class="section-description">Our AI-powered platform offers a comprehensive suite of tools to help you take control of your health and wellness journey.</p>
            </div>
            
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-robot"></i>
                    </div>
                    <h3 class="feature-title">AI-Powered Diagnosis</h3>
                    <p class="feature-description">Get instant preliminary health assessments using our advanced AI algorithms trained on millions of data points.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-heartbeat"></i>
                    </div>
                    <h3 class="feature-title">Health Monitoring</h3>
                    <p class="feature-description">Track your vital signs, symptoms, and health metrics in real-time with our intuitive dashboard.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <h3 class="feature-title">Appointment Scheduling</h3>
                    <p class="feature-description">Book appointments with healthcare professionals and get reminders for your upcoming visits.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-pills"></i>
                    </div>
                    <h3 class="feature-title">Medication Tracker</h3>
                    <p class="feature-description">Never miss a dose with our smart medication reminders and tracking system.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3 class="feature-title">Health Analytics</h3>
                    <p class="feature-description">Gain valuable insights into your health trends and patterns with detailed analytics.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                    <h3 class="feature-title">Secure & Private</h3>
                    <p class="feature-description">Your health data is encrypted and protected with the highest security standards.</p>
                </div>
            </div>
        </div>
    </section>
    <div class="container">
    <!-- Heading Section -->
    <div class="row justify-content-center mb-5">
      <div class="col-lg-8 text-center">
        <h1 id="font">AI Medical Report Summarizer</h1>
        <p class="section-description">
          Get a summary of medical reports in seconds, read faster and understand better
        </p>
      </div>
    </div>

    <!-- Upload Box -->
    <div class="row justify-content-center">
      <div class="col-md-8 col-lg-6">
        <div class="upload-box">
          <div class="upload-area">
            <div class="icon-box">
              <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1M12 12V4m0 0l-4 4m4-4l4 4" />
              </svg>
            </div>

            <a href="/auth/signup" class="upload-btn">
              Upload Medical Document
            </a>

            <p class="upload-text">or Drag file here</p>
            <p class="upload-subtext">Supports PDF, DOC, DOCX files</p>
          </div>
        </div>
      </div>
    </div>
  </div>

    
    <!-- How It Works Section -->
    <section id="how-it-works" class="section">
    <div class="container">
        <div class="section-header">
            <span class="section-badge">How It Works</span>
            <h2 class="section-title">Simple Steps to Better Health</h2>
            <p class="section-description">Getting started with AI HealthCare Pro is quick and easy. Follow these simple steps to begin your journey to better health.</p>
        </div>

        <!-- Step 1 -->
        <div class="step-row">
            <div class="step-image">
                <img src="{{ asset('upload.png') }}" alt="Step 1">
            </div>
            <div class="step-content">
                <div class="circle">1</div>
                <h3 class="step-title">Create Your Account</h3>
                <p class="step-description">Sign up for a free account in just a few simple steps. No credit card required.</p>
            </div>
        </div>

        <!-- Step 2 -->
        <div class="step-row reverse">
            <div class="step-image">
                <img src="{{ asset('upload.png') }}" alt="Step 2">
            </div>
            <div class="step-content">
                <div class="circle">2</div>
                <h3 class="step-title">Complete Health Profile</h3>
                <p class="step-description">Answer a few questions about your health history, lifestyle, and goals to personalize your experience.</p>
            </div>
        </div>

        <!-- Step 3 -->
        <div class="step-row">
            <div class="step-image">
                <img src="{{ asset('upload.png') }}" alt="Step 3">
            </div>
            <div class="step-content">
            <div class="circle">3</div>
                <h3 class="step-title">Start Your Journey</h3>
                <p class="step-description">Access your personalized dashboard and begin exploring the features to improve your health and wellness.</p>
            </div>
        </div>
    </div>
</section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="section">
        <div class="container">
            <div class="section-header">
                <span class="section-badge">Testimonials</span>
                <h2 class="section-title">What Our Users Say</h2>
                <p class="section-description">Don't just take our word for it. Here's what our users have to say about their experience with AI HealthCare Pro.</p>
            </div>
            
            <div class="testimonials-grid">
                <div class="testimonial-card">
                    <div class="testimonial-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="testimonial-text">"This platform has completely transformed how I manage my health. The AI assistant is incredibly helpful and the insights are spot on."</p>
                    <div class="testimonial-author">
                        <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Sarah Johnson" class="testimonial-avatar">
                        <div class="testimonial-info">
                            <h4>Sarah Johnson</h4>
                            <p>Healthcare Professional</p>
                        </div>
                    </div>
                </div>
                
                <div class="testimonial-card">
                    <div class="testimonial-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="testimonial-text">"The health tracking features are amazing. I love how it gives me personalized recommendations based on my activity and vitals."</p>
                    <div class="testimonial-author">
                        <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Michael Chen" class="testimonial-avatar">
                        <div class="testimonial-info">
                            <h4>Michael Chen</h4>
                            <p>Fitness Enthusiast</p>
                        </div>
                    </div>
                </div>
                
                <div class="testimonial-card">
                    <div class="testimonial-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="testimonial-text">"As someone with a busy schedule, this app has been a game-changer for managing my health. The medication reminders are a lifesaver!"</p>
                    <div class="testimonial-author">
                        <img src="https://randomuser.me/api/portraits/women/68.jpg" alt="Emily Rodriguez" class="testimonial-avatar">
                        <div class="testimonial-info">
                            <h4>Emily Rodriguez</h4>
                            <p>Busy Professional</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- CTA Section -->
    <section class="section">
        <div class="container-fluid">
            <div class="cta">
                <div class="cta-content">
                    <h2 class="cta-title">Ready to Take Control of Your Health?</h2>
                    <p class="cta-description">Join thousands of users who are already experiencing better health with our AI-powered platform.</p>
                    <div class="cta-actions">
                        <a href="{{ route('register') }}" class="btn btn-primary">Get Started for Free</a>
                        <a href="#features" class="btn btn-secondary">Learn More</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Footer -->
   @include('master.footer')

    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobileMenuButton');
            const mobileMenu = document.getElementById('mobileMenu');
            const menuIcon = document.getElementById('menuIcon');
            
            // Toggle mobile menu
            function toggleMenu() {
                const isExpanded = mobileMenuButton.getAttribute('aria-expanded') === 'true' || false;
                mobileMenuButton.setAttribute('aria-expanded', !isExpanded);
                mobileMenu.classList.toggle('hidden');
                
                // Toggle between menu and close icon
                if (!isExpanded) {
                    menuIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />';
                } else {
                    menuIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />';
                }
            }
            
            // Toggle menu when button is clicked
            if (mobileMenuButton) {
                mobileMenuButton.addEventListener('click', function(e) {
                    e.stopPropagation();
                    toggleMenu();
                });
            }
            
            // Close menu when clicking outside
            document.addEventListener('click', function(event) {
                if (!mobileMenuButton.contains(event.target) && !mobileMenu.contains(event.target)) {
                    if (!mobileMenu.classList.contains('hidden')) {
                        toggleMenu();
                    }
                }
            });
            
            // Close menu when clicking on a link
            const mobileLinks = mobileMenu.querySelectorAll('a, button');
            mobileLinks.forEach(link => {
                link.addEventListener('click', () => {
                    if (!mobileMenu.classList.contains('hidden')) {
                        toggleMenu();
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
                        if (mobileMenu && !mobileMenu.classList.contains('hidden')) {
                            mobileMenu.classList.add('hidden');
                        }
                    }
                });
            });
            
            // Add animation on scroll
            const animateOnScroll = () => {
                const elements = document.querySelectorAll('.feature-card, .step, .testimonial-card');
                
                elements.forEach(element => {
                    const elementTop = element.getBoundingClientRect().top;
                    const windowHeight = window.innerHeight;
                    
                    if (elementTop < windowHeight - 100) {
                        element.style.opacity = '1';
                        element.style.transform = 'translateY(0)';
                    }
                });
            };
            
            // Initial check
            animateOnScroll();
            
            // Check on scroll
            window.addEventListener('scroll', animateOnScroll);
        });
    </script>
</body>
</html>
