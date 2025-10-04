<nav x-data="{ open: false }" class="bg-gradient-to-r from-slate-900 via-blue-900 to-slate-900 shadow-xl backdrop-blur-lg border-b border-slate-700/50 relative z-40">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 hover-lift transition-all">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-user-md text-white text-xl"></i>
                        </div>
                        <div class="hidden sm:block">
                            <h1 class="text-xl font-bold bg-gradient-to-r from-blue-400 to-cyan-400 bg-clip-text text-transparent">
                                MediBot AI
                            </h1>
                            <p class="text-xs text-slate-300">Healthcare Assistant</p>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-2 sm:-my-px sm:ms-10 sm:flex items-center">
                    <a href="{{ route('dashboard') }}" 
                       class="flex items-center space-x-2 px-4 py-2 rounded-xl transition-all hover-lift {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-300 hover:bg-slate-700/50 hover:text-white' }}">
                        <i class="fas fa-chart-line text-lg"></i>
                        <span class="font-medium">Dashboard</span>
                    </a>
                    <a href="{{ route('chat.new') }}" 
                       class="flex items-center space-x-2 px-4 py-2 rounded-xl transition-all hover-lift {{ request()->routeIs('chat.*') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-300 hover:bg-slate-700/50 hover:text-white' }}">
                        <i class="fas fa-robot text-lg"></i>
                        <span class="font-medium">AI Chat</span>
                    </a>
                    <a href="{{ route('health-metrics.index') }}" 
                       class="flex items-center space-x-2 px-4 py-2 rounded-xl transition-all hover-lift {{ request()->routeIs('health-metrics.*') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-300 hover:bg-slate-700/50 hover:text-white' }}">
                        <i class="fas fa-heart text-lg"></i>
                        <span class="font-medium">Health Metrics</span>
                    </a>
                    <a href="{{ route('symptom-checker.create') }}" 
                       class="flex items-center space-x-2 px-4 py-2 rounded-xl transition-all hover-lift {{ request()->routeIs('symptom-checker.*') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-300 hover:bg-slate-700/50 hover:text-white' }}">
                        <i class="fas fa-stethoscope text-lg"></i>
                        <span class="font-medium">Symptom Checker</span>
                    </a>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center space-x-3 px-4 py-2 rounded-xl bg-slate-700/50 hover:bg-slate-600/50 transition-all hover-lift border border-slate-600/50">
                            <div class="w-10 h-10 bg-gradient-to-br from-pink-500 to-rose-500 rounded-full flex items-center justify-center shadow-lg">
                                <span class="text-white font-bold text-sm">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            </div>
                            <div class="text-left">
                                <div class="font-medium text-white">{{ Auth::user()->name }}</div>
                                <div class="text-xs text-slate-300">{{ Auth::user()->email }}</div>
                            </div>
                            <svg class="fill-current h-4 w-4 text-slate-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" 
                                           onclick="event.preventDefault();
                                           this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
                @else
                <div class="flex space-x-4">
                    <a href="{{ route('login') }}" class="px-4 py-2 rounded-xl bg-slate-700/50 hover:bg-slate-600/50 transition-all text-slate-300 hover:text-white border border-slate-600/50">
                        {{ __('Log in') }}
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="px-4 py-2 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 transition-all text-white shadow-lg">
                            {{ __('Register') }}
                        </a>
                    @endif
                </div>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-slate-300 hover:text-white hover:bg-slate-700/50 focus:outline-none focus:bg-slate-700/50 focus:text-white transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-slate-800/95 backdrop-blur-lg border-t border-slate-700/50">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('chat.new')" :active="request()->routeIs('chat.*')">
                {{ __('AI Chat') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('health-metrics.index')" :active="request()->routeIs('health-metrics.*')">
                {{ __('Health Metrics') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('symptom-checker.create')" :active="request()->routeIs('symptom-checker.*')">
                {{ __('Symptom Checker') }}
            </x-responsive-nav-link>
        </div>

        @auth
        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-slate-700/50">
            <div class="px-4">
                <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-slate-300">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
        @else
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('login')">
                {{ __('Login') }}
            </x-responsive-nav-link>
            @if (Route::has('register'))
                <x-responsive-nav-link :href="route('register')">
                    {{ __('Register') }}
                </x-responsive-nav-link>
            @endif
        </div>
        @endauth
    </div>
</nav>
