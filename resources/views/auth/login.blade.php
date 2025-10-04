<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - Medi AI</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  
  
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

    .login-container {
      display: grid;
      grid-template-columns: 1fr 1fr;
      align-items: center;
      gap: 4rem;
      width: 100%;
    }

    .login-welcome{max-width:500px;padding:1rem;}
    .login-welcome h2{font-size:2.5rem;font-weight:700;margin-bottom:1.5rem;line-height:1.2;}
    .login-welcome h2 span{color:#22d3ee;display:block;}
    .login-welcome p{color:rgba(255,255,255,.8);margin-bottom:2rem;font-size:1.1rem;line-height:1.7;}
    .login-welcome img{width:100%;border-radius:1rem;box-shadow:0 10px 30px rgba(0,0,0,.3);transition:.3s;}
    .login-welcome img:hover{transform:translateY(-5px);}

    /* Card */
    .login-card{
      background:rgba(20,22,34,.9);backdrop-filter:blur(10px);
      border:1px solid rgba(255,255,255,.1);border-radius:1rem;
      padding:2.5rem;width:100%;max-width:420px;
      box-shadow:0 15px 35px rgba(0,0,0,.2);margin:2rem 0;
    }
    .login-card h3{font-size:1.75rem;font-weight:600;margin-bottom:1.5rem;}

    label{display:block;font-size:.9rem;color:rgba(255,255,255,.8);font-weight:500;margin-bottom:.5rem;}

    .login-input-box{margin-bottom:1.25rem;position:relative;}
    .login-input{
      width:100%;padding:.85rem 1rem;border-radius:.75rem;
      background:rgba(15,18,32,.7);border:1px solid rgba(255,255,255,.1);
      color:#fff;font-size:1rem;outline:none;transition:.3s;
    }
    .login-input:focus{border-color:#22d3ee;box-shadow:0 0 0 3px rgba(34,211,238,.2);}

    .login-btn{
      width:100%;padding:.9rem 0;margin-top:.5rem;border:none;
      border-radius:.75rem;background:linear-gradient(45deg,#2563eb,#06b6d4);
      color:#fff;font-weight:600;font-size:1rem;cursor:pointer;
      transition:.3s;text-transform:uppercase;letter-spacing:.5px;
    }
    .login-btn:hover{background:linear-gradient(45deg,#1e40af,#0891b2);transform:translateY(-2px);box-shadow:0 5px 15px rgba(0,0,0,.2);}

    .login-footer{text-align:center;margin-top:1.5rem;font-size:.9rem;color:rgba(255,255,255,.6);}
    .login-footer a{color:#22d3ee;text-decoration:none;font-weight:500;transition:.2s;}
    .login-footer a:hover{color:#67e8f9;text-decoration:underline;}

    .login-error{
      background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.3);
      color:#fca5a5;padding:.75rem 1rem;border-radius:.5rem;
      font-size:.9rem;margin-bottom:1.25rem;text-align:center;
    }

    /* Responsive */
    @media(max-width:1024px){
      .login-container{gap:2rem;padding:0 1.5rem;}
      .login-welcome h2{font-size:2rem;}
    }
    @media(max-width:768px){
      main{padding:3rem 1rem;}
      .login-container{grid-template-columns:1fr;text-align:center;max-width:500px;}
      .login-welcome{margin:0 auto 2rem;}
      .login-welcome h2{font-size:1.8rem;}
      .login-welcome p{font-size:1rem;}
      .login-card{margin:0 auto;padding:2rem;}
    }
    @media(max-width:480px){
      .login-card{padding:1.5rem;}
      .login-welcome h2{font-size:1.6rem;}
    }
  </style>
</head>
<body>
  @include('master.header')

  <main class="py-16">
    <div class="container">
      <div class="login-container">
        <!-- Left Welcome Section -->
        <div class="login-welcome">
          <h2>Welcome back to <br><span>Your Healthcare Hub</span></h2>
          <p>Sign in to access your dashboard, continue chats, and manage your health insights securely.</p>
          <img src="{{ asset('upload.png') }}" alt="Medi AI Dashboard" class="mt-6">
        </div>

        <!-- Right Login Card -->
        <div class="login-card">
          <h3>Sign in</h3>

          {{-- Error Message --}}
          @if($errors->any())
            <div class="login-error">
              {{ $errors->first() }}
            </div>
          @endif

          <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf
            <div>
              <label for="email" class="block text-sm font-medium text-gray-300 mb-1">Email address</label>
              <div class="login-input-box">
                <input id="email" type="email" name="email" class="login-input" value="{{ old('email') }}" required placeholder="Enter your email">
              </div>
            </div>

            <div>
              <label for="password" class="block text-sm font-medium text-gray-300 mb-1">Password</label>
              <div class="login-input-box">
                <input id="password" type="password" name="password" class="login-input" required placeholder="Enter your password">
              </div>
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-4 rounded-lg transition duration-200">
              Sign In
            </button>

            <div class="text-center text-sm text-gray-400">
              Don't have an account? 
              <a href="{{ route('register') }}" class="text-blue-400 hover:text-blue-300 font-medium">
                Sign Up
              </a>
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
