<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Register - Medi AI</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  
  <!-- Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Inter', sans-serif;
    }

    body {
      min-height: 100vh;
      background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%);
      color: #fff;
      display: flex;
      flex-direction: column;
      line-height: 1.6;
    }

    .container {
      width: 100%;
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 1rem;
    }

    main {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 6rem 0;
    }

    .register-container {
      display: grid;
      grid-template-columns: 1fr 1fr;
      align-items: center;
      gap: 4rem;
      width: 100%;
    }

    .register-welcome {
      max-width: 500px;
      padding: 1rem;
    }

    .register-welcome h2 {
      font-size: 2.5rem;
      font-weight: 700;
      margin-bottom: 1.5rem;
      line-height: 1.2;
    }

    .register-welcome h2 span {
      color: #22d3ee;
      display: block;
    }

    .register-welcome p {
      color: rgba(255, 255, 255, 0.8);
      margin-bottom: 2rem;
      font-size: 1.1rem;
      line-height: 1.7;
    }

    .register-welcome img {
      width: 100%;
      border-radius: 1rem;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
      transition: 0.3s;
    }

    .register-welcome img:hover {
      transform: translateY(-5px);
    }

    .register-card {
      background: rgba(20, 22, 34, 0.9);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 1rem;
      padding: 2.5rem;
      width: 100%;
      max-width: 700px;
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
      margin: 2rem -4rem;
    }

    .register-card h3 {
      font-size: 1.75rem;
      font-weight: 600;
      margin-bottom: 1.5rem;
      color: #fff;
    }

    .register-input-box {
      margin-bottom: 1.25rem;
      position: relative;
    }

    .register-input {
      width: 100%;
      padding: 0.85rem 1rem;
      border-radius: 0.75rem;
      background: rgba(15, 18, 32, 0.7);
      border: 1px solid rgba(255, 255, 255, 0.1);
      color: #fff;
      font-size: 1rem;
      outline: none;
      transition: 0.3s;
    }

    .register-input:focus {
      border-color: #22d3ee;
      box-shadow: 0 0 0 3px rgba(34, 211, 238, 0.2);
    }

    .register-btn {
      width: 100%;
      padding: 0.9rem 0;
      margin-top: 0.5rem;
      border: none;
      border-radius: 0.75rem;
      background: linear-gradient(45deg, #2563eb, #06b6d4);
      color: #fff;
      font-weight: 600;
      font-size: 1rem;
      cursor: pointer;
      transition: 0.3s;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .register-btn:hover {
      background: linear-gradient(45deg, #1e40af, #0891b2);
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    .register-footer {
      text-align: center;
      margin-top: 1.5rem;
      font-size: 0.9rem;
      color: rgba(255, 255, 255, 0.6);
    }

    .register-footer a {
      color: #22d3ee;
      text-decoration: none;
      font-weight: 500;
      transition: 0.2s;
    }

    .register-footer a:hover {
      color: #67e8f9;
      text-decoration: underline;
    }

    .register-error {
      background: rgba(239, 68, 68, 0.1);
      border: 1px solid rgba(239, 68, 68, 0.3);
      color: #fca5a5;
      padding: 0.75rem 1rem;
      border-radius: 0.5rem;
      font-size: 0.9rem;
      margin-bottom: 1.25rem;
      text-align: center;
    }

    .terms-agreement {
      display: flex;
      align-items: flex-start;
      gap: 0.75rem;
      margin: 1.5rem 0;
      padding: 1rem;
      background: rgba(255, 255, 255, 0.05);
      border-radius: 0.75rem;
    }

    .terms-agreement input[type="checkbox"] {
      margin-top: 0.25rem;
    }

    .terms-agreement label {
      font-size: 0.9rem;
      color: rgba(255, 255, 255, 0.7);
      line-height: 1.5;
    }

    .terms-agreement a {
      color: #22d3ee;
      text-decoration: none;
      font-weight: 500;
    }

    .terms-agreement a:hover {
      text-decoration: underline;
    }

    @media (max-width: 1024px) {
      .register-container {
        gap: 2rem;
      }
      
      .register-welcome h2 {
        font-size: 2rem;
      }
    }

    @media (max-width: 768px) {
      main {
        padding: 3rem 0;
      }
      
      .register-container {
        grid-template-columns: 1fr;
        text-align: center;
        max-width: 500px;
      }
      
      .register-welcome {
        margin: 0 auto 2rem;
      }
      
      .register-welcome h2 {
        font-size: 1.8rem;
      }
      
      .register-welcome p {
        font-size: 1rem;
      }
      
      .register-card {
        margin: 0 auto;
        padding: 2rem;
      }
    }

    @media (max-width: 480px) {
      .register-card {
        padding: 1.5rem;
      }
      
      .register-welcome h2 {
        font-size: 1.6rem;
      }
    }
  </style>
</head>
<body>
  @include('master.header')

  <main class="py-16">
    <div class="container">
      <div class="register-container">
        <!-- Left Welcome Section -->
        <div class="register-welcome">
          <h2>Welcome to <br><span>Your Healthcare Hub</span></h2>
          <p>Create an account to access your personalized health dashboard, track your medical history, and get AI-powered health insights.</p>
          <img src="{{ asset('upload.png') }}" alt="Medi AI Dashboard" class="mt-6">
        </div>

        <!-- Right Register Card -->
        <div class="register-card">
          <h3>Create Account</h3>

          {{-- Error Message --}}
          @if($errors->any())
            <div class="register-error">
              {{ $errors->first() }}
            </div>
          @endif

          <!-- Social Registration -->
          @if(config('services.google.client_id') && config('services.github.client_id'))
            <div class="mb-6">
              <div class="relative">
                <div class="absolute inset-0 flex items-center">
                  <div class="w-full border-t border-gray-600"></div>
                </div>
                <div class="relative flex justify-center text-sm mb-4">
                  <span class="px-2 bg-[#141626] text-gray-400">Or sign up with</span>
                </div>
              </div>
              
              <div class="grid grid-cols-2 gap-3">
                <a href="{{ route('social.login', 'google') }}" 
                   class="flex items-center justify-center py-2 px-4 border border-gray-600 rounded-lg text-gray-300 hover:bg-gray-700 transition-colors">
                  <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                  </svg>
                  Google
                </a>
                
                <a href="{{ route('social.login', 'github') }}" 
                   class="flex items-center justify-center py-2 px-4 border border-gray-600 rounded-lg text-gray-300 hover:bg-gray-700 transition-colors">
                  <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                  </svg>
                  GitHub
                </a>
              </div>
            </div>
          @else
            <!-- OAuth Setup Notice -->
            <div class="mb-6 p-4 bg-blue-900 bg-opacity-30 border border-blue-700 rounded-lg">
              <div class="flex items-start">
                <svg class="w-5 h-5 text-blue-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                  <h4 class="text-sm font-semibold text-blue-300 mb-1">Social Registration Available</h4>
                  <p class="text-xs text-blue-200">
                    To enable Google & GitHub registration, configure OAuth credentials in your <code class="bg-blue-900 bg-opacity-50 px-1 rounded">.env</code> file.
                  </p>
                </div>
              </div>
            </div>
          @endif

          <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <!-- Name -->
            <div class="register-input-box">
              <label for="name" class="block text-sm font-medium text-gray-300 mb-1">Full Name</label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                  </svg>
                </div>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus autocomplete="name"
                       class="register-input pl-10"
                       placeholder="Enter your full name">
              </div>
              @error('name')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
              @enderror
            </div>

            <!-- Email Address -->
            <div class="register-input-box">
              <label for="email" class="block text-sm font-medium text-gray-300 mb-1">Email Address</label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                  </svg>
                </div>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email"
                       class="register-input pl-10"
                       placeholder="Enter your email address">
              </div>
              @error('email')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
              @enderror
            </div>

            <!-- Password -->
            <div class="register-input-box">
              <label for="password" class="block text-sm font-medium text-gray-300 mb-1">Password</label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                  </svg>
                </div>
                <input id="password" name="password" type="password" required autocomplete="new-password"
                       class="register-input pl-10"
                       placeholder="Create a strong password">
              </div>
              @error('password')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
              @enderror
            </div>

            <!-- Confirm Password -->
            <div class="register-input-box">
              <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-1">Confirm Password</label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>
                </div>
                <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                       class="register-input pl-10"
                       placeholder="Confirm your password">
              </div>
              @error('password_confirmation')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
              @enderror
            </div>

            <!-- Terms Agreement -->
            <div class="terms-agreement">
              <input id="terms" name="terms" type="checkbox" required 
                     class="rounded border-gray-600 text-blue-500 focus:ring-blue-400 focus:ring-offset-0 transition-colors bg-gray-700">
              <label for="terms">
                I agree to the <a href="#" class="hover:underline">Terms of Service</a> 
                and <a href="#" class="hover:underline">Privacy Policy</a>. 
                I understand this is for informational purposes only and not a substitute for professional medical advice.
              </label>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="register-btn">
              Create Account
            </button>

            <!-- Login Link -->
            <div class="register-footer">
              Already have an account? 
              <a href="{{ route('login') }}" class="hover:underline">Sign in here</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </main>

  @include('master.footer')
  
  <!-- Scripts -->
  @stack('scripts')
</body>
</html>
