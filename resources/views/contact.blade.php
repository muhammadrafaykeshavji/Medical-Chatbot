<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Contact Us - AI HealthCare Pro</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
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
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: var(--darker);
            color: #e2e8f0;
            line-height: 1.6;
            margin: 0;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }
        
        /* Contact Section */
        .contact-section {
            padding: 8rem 0 4rem;
            background: var(--darker);
        }
        
        .section-header {
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            background: linear-gradient(to right, #3b82f6, #60a5fa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .section-subtitle {
            color: #9ca3af;
            font-size: 1.1rem;
            max-width: 700px;
            margin: 0 auto;
        }
        
        .contact-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }
        
        .contact-info,
        .contact-form {
            background: rgba(255, 255, 255, 0.03);
            border-radius: 1rem;
            padding: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        .contact-info h3 {
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            color: white;
        }
        
        .contact-method {
            display: flex;
            align-items: flex-start;
            margin-bottom: 1.5rem;
        }
        
        .contact-icon {
            width: 48px;
            height: 48px;
            background: rgba(59, 130, 246, 0.1);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            color: var(--primary-light);
            font-size: 1.25rem;
        }
        
        .contact-details h4 {
            font-size: 1.1rem;
            margin-bottom: 0.25rem;
            color: white;
        }
        
        .contact-details p, 
        .contact-details a {
            color: #9ca3af;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .contact-details a:hover {
            color: var(--primary-light);
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #d1d5db;
            font-weight: 500;
        }
        
        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 0.5rem;
            color: white;
            font-size: 1rem;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary-light);
        }
        
        textarea.form-control {
            min-height: 150px;
            resize: vertical;
        }
        
        .btn-submit {
            background: linear-gradient(to right, #2563eb, #4f46e5);
            color: white;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            width: 100%;
        }
        
        .btn-submit:hover {
            opacity: 0.9;
        }
        
        /* Map Section */
        .map-section {
            padding: 2rem 0;
        }
        
        .map-container {
            height: 400px;
            border-radius: 1rem;
            overflow: hidden;
        }
        
        .map-container iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .contact-container {
                grid-template-columns: 1fr;
            }
            
            .section-title {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header (Blade include) -->
    @include('master.header')

    <!-- Breadcrumb -->
    <section class="breadcrumb-section" style="background: linear-gradient(135deg, #0f172a, #1e1b4b); padding: 3rem 0; margin-top: 70px;">
        <div class="container">
            <div class="breadcrumb-content" style="color: white; text-align: center;">
                <h1 style="font-size: 2.5rem; font-weight: 800; margin-bottom: 1rem; background: linear-gradient(to right, #3b82f6, #60a5fa); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                    Contact Us
                </h1>
                <nav style="display: flex; justify-content: center; gap: 0.5rem; font-size: 1rem; color: #9ca3af;">
                    <a href="/" style="color: #e2e8f0; text-decoration: none;">
                        <i class="fas fa-home" style="margin-right: 0.5rem;"></i>Home
                    </a>
                    <span style="color: #64748b;">/</span>
                    <span style="color: #60a5fa;">Contact</span>
                </nav>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Get In Touch</h2>
                <p class="section-subtitle">Have questions? We're here to help. Reach out to us using the form below or through our contact information.</p>
            </div>
            
            <div class="contact-container">
                <div class="contact-info">
                    <h3>Contact Information</h3>
                    
                    <div class="contact-method">
                        <div class="contact-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="contact-details">
                            <h4>Our Location</h4>
                            <p>123 Medical Plaza, Suite 100<br>San Francisco, CA 94103</p>
                        </div>
                    </div>
                    
                    <div class="contact-method">
                        <div class="contact-icon">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div class="contact-details">
                            <h4>Phone Number</h4>
                            <p><a href="tel:+11234567890">+1 (123) 456-7890</a></p>
                        </div>
                    </div>
                    
                    <div class="contact-method">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="contact-details">
                            <h4>Email Address</h4>
                            <p><a href="mailto:info@mediai.com">info@mediai.com</a></p>
                        </div>
                    </div>
                    
                    <div class="contact-method">
                        <div class="contact-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="contact-details">
                            <h4>Working Hours</h4>
                            <p>Mon - Fri: 9:00 AM - 6:00 PM<br>Sat: 10:00 AM - 4:00 PM</p>
                        </div>
                    </div>
                </div>
                
                <div class="contact-form">
                    <form action="#" method="POST">
                        <div class="form-group">
                            <label for="name">Your Name</label>
                            <input type="text" id="name" name="name" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="subject">Subject</label>
                            <input type="text" id="subject" name="subject" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="message">Your Message</label>
                            <textarea id="message" name="message" class="form-control" required></textarea>
                        </div>
                        
                        <button type="submit" class="btn-submit">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Map Section -->
    <section class="map-section">
        <div class="container">
            <div class="map-container">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3619.907655151774!2d67.02107867442994!3d24.867003344997528!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3eb33e6b385fa553%3A0x2cda2c050449bb24!2sAnklesaria%20Hospital!5e0!3m2!1sen!2s!4v1759155061837!5m2!1sen!2s" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </section>
    

    @include('master.footer')
</body>
</html>
