<!-- resources/views/master/footer.blade.php -->

<style>
    
    footer {
        background: rgba(2, 6, 23, 0.8);
        border-top: 1px solid rgba(255, 255, 255, 0.05);
        padding: 4rem 0 2rem;
    }

    .footer-grid {
        display: grid;
        grid-template-columns: repeat(1, 1fr);
        gap: 3rem;
        margin-bottom: 4rem;
    }

    @media (min-width: 768px) {
        .footer-grid { grid-template-columns: repeat(2, 1fr); }
    }

    @media (min-width: 1024px) {
        .footer-grid { grid-template-columns: repeat(4, 1fr); }
    }

    .footer-logo {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 1.25rem;
        font-weight: 700;
        color: white;
        text-decoration: none;
        margin-bottom: 1.5rem;
    }

    .footer-description {
        color: #64748b; /* gray */
        margin-bottom: 1.5rem;
        line-height: 1.625;
    }

    .social-links {
        display: flex;
        gap: 1rem;
    }

    .social-link {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 0.5rem;
        background: rgba(255, 255, 255, 0.05);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #64748b;
        transition: all 0.2s ease;
    }

    .social-link:hover {
        background: #3b82f6; /* blue */
        color: white;
        transform: translateY(-2px);
    }

    .footer-heading {
        font-size: 1.125rem;
        font-weight: 600;
        color: white;
        margin-bottom: 1.5rem;
    }

    .footer-links {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .footer-link {
        color: #64748b;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .footer-link:hover {
        color: #3b82f6;
    }

    .footer-bottom {
        border-top: 1px solid rgba(255, 255, 255, 0.05);
        padding-top: 2rem;
        display: flex;
        flex-direction: column;
        gap: 1rem;
        align-items: center;
        justify-content: space-between;
    }

    @media (min-width: 768px) {
        .footer-bottom { flex-direction: row; }
    }

    .copyright {
        color: #64748b;
        font-size: 0.875rem;
    }

    .footer-legal {
        display: flex;
        gap: 1.5rem;
    }

    .footer-legal-link {
        color: #64748b;
        font-size: 0.875rem;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .footer-legal-link:hover {
        color: #3b82f6;
    }
</style>

<footer>
    <div class="container">
        <div class="footer-grid">
            <!-- Column 1 -->
            <div>
                <a href="#" class="footer-logo">
                    <i class="fas fa-heartbeat"></i> AI HealthCare Pro
                </a>
                <p class="footer-description">
                    Your personal AI-powered healthcare assistant providing insights, tracking, and more.
                </p>
                <div class="social-links">
                    <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>

            <!-- Column 2 -->
            <div>
                <h3 class="footer-heading">Product</h3>
                <div class="footer-links">
                    <a href="#features" class="footer-link">Features</a>
                    <a href="#how-it-works" class="footer-link">How It Works</a>
                    <a href="#pricing" class="footer-link">Pricing</a>
                </div>
            </div>

            <!-- Column 3 -->
            <div>
                <h3 class="footer-heading">Company</h3>
                <div class="footer-links">
                    <a href="#about" class="footer-link">About Us</a>
                    <a href="#blog" class="footer-link">Blog</a>
                    <a href="#careers" class="footer-link">Careers</a>
                </div>
            </div>

            <!-- Column 4 -->
            <div>
                <h3 class="footer-heading">Support</h3>
                <div class="footer-links">
                    <a href="#help" class="footer-link">Help Center</a>
                    <a href="#terms" class="footer-link">Terms of Service</a>
                    <a href="#privacy" class="footer-link">Privacy Policy</a>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <p class="copyright">© 2025 AI HealthCare Pro. All rights reserved.</p>
            <div class="footer-legal">
                <a href="#privacy" class="footer-legal-link">Privacy Policy</a>
                <a href="#terms" class="footer-legal-link">Terms & Conditions</a>
            </div>
        </div>
    </div>
</footer>
