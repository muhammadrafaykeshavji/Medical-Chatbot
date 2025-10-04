<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Medi AI - Your Personal Health Assistant</title>
  
  <!-- Favicon -->
  <link rel="icon" type="image/png" href="{{ asset('logo-1.png') }}">
  <link rel="shortcut icon" type="image/png" href="{{ asset('logo-1.png') }}">
</head>
<body>
  <style>
    /* ========== VARIABLES ========== */
:root {
  --gradient: linear-gradient(90deg, #3b82f6, #06b6d4); /* Blue → Cyan */
  --light-gray: #e2e8f0;
  --primary-light: #60a5fa;
}

/* ========== HEADER ========== */
header {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 70px;
  background: rgba(2, 6, 23, 0.9);
  backdrop-filter: blur(16px);
  border-bottom: 1px solid rgba(255, 255, 255, 0.05);
  display: flex;
  align-items: center;
  z-index: 50;
}

.nav-container {
  display: flex;
  justify-content: space-between;
  align-items: center;
  width: 100%;
  height: 100%;
}

/* Logo */
.logo {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  font-size: 1.5rem;
  font-weight: 700;
  color: white;
  text-decoration: none;
}

.logo-icon {
  width: 2.5rem;
  height: 2.5rem;
  border-radius: 0.75rem;
  background: var(--gradient);
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
}

/* Navigation Links */
.nav-links {
  display: flex;
  gap: 2rem;
}

.nav-link {
  color: var(--light-gray);
  text-decoration: none;
  font-weight: 500;
  transition: color 0.2s ease;
}

.nav-link:hover {
  color: var(--primary-light);
}

/* Actions (Login / Signup) */
.nav-actions {
  display: flex;
  gap: 1rem;
}

.btn-login,
.btn-signup {
  font-size: 0.9rem;
  font-weight: 700;
  padding: 0.4rem 0.8rem;
  border-radius: 5px;
  text-decoration: none;
  display: inline-block;
  transition: all 0.3s ease;
}

.btn-login {
  color: var(--light-gray);
  border: 1px solid rgba(255, 255, 255, 0.1);
}

.btn-login:hover {
  background: rgba(255, 255, 255, 0.1);
}

.btn-signup {
  background: var(--gradient);
  color: #fff;
  border: none;
}

.btn-signup:hover {
  opacity: 0.9;
  transform: scale(1.05);
}

/* Mobile Toggle */
.mobile-toggle {
  display: none;
  background: none;
  border: none;
  color: #fff;
  font-size: 1.5rem;
  cursor: pointer;
}

/* Mobile Menu */
.mobile-menu {
  display: none;
  flex-direction: column;
  background: #0f172a;
  position: absolute;
  top: 70px;
  left: 0;
  width: 100%;
  padding: 1rem;
}

.mobile-menu a,
.mobile-menu button {
  color: #e0e7ff;
  text-decoration: none;
  padding: 0.5rem 0;
  border: none;
  background: none;
  text-align: left;
}

.mobile-menu.show {
  display: flex;
}

  </style>
  <header>
  <div class="container nav-container">
    <a href="/" class="logo"><img src="{{ asset('logo-1.png') }}" alt="logo" width="50px"></i>Medi Ai</a>
    <nav class="nav-links">
      <a href="/" class="nav-link">Home</a>
      <a href="services" class="nav-link">Services</a>
      <a href="pricing" class="nav-link">Pricing</a>
      <a href="contact" class="nav-link">Contact</a>
    </nav>
    <div class="nav-actions">
      @auth
        <a href="{{ route('dashboard') }}" class="btn-signup">Dashboard</a>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="btn-login">Logout</button>
        </form>
      @else
        <a href="{{ route('login') }}" class="btn-login">Log in</a>
        <a href="{{ route('register') }}" class="btn-signup">Sign up</a>
      @endauth
    </div>
    <button class="mobile-toggle" id="menuBtn"><i class="fas fa-bars"></i></button>
  </div>
  <div class="mobile-menu" id="mobileMenu">
    <a href="/">Home</a>
    <a href="services">Features</a>
    <a href="pricing">How It Works</a>
    <a href="contact">Testimonials</a>
    @auth
      <a href="{{ route('dashboard') }}">Dashboard</a>
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" style="background:none;border:none;color:#e0e7ff;text-align:left;width:100%;">Logout</button>
      </form>
    @else
      <a href="{{ route('login') }}">Log in</a>
      <a href="{{ route('register') }}">Sign up</a>
    @endauth
  </div>
</header>

</body>
</html>