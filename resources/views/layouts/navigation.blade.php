<nav x-data="{ open: false }" class="glass shadow-premium backdrop-blur-lg border-b border-white/20 relative z-40">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 hover-lift transition-all">
                        <div class="w-12 h-12 gradient-bg-primary rounded-xl flex items-center justify-center shadow-glow">
                            <span class="text-white text-2xl font-bold">🏥</span>
                        </div>
                        <div class="hidden sm:block">
                            <h1 class="text-xl font-bold bg-gradient-to-r from-purple-600 to-blue-600 bg-clip-text text-transparent">
                                MediBot AI
                            </h1>
                            <p class="text-xs text-gray-600">Healthcare Assistant</p>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-2 sm:-my-px sm:ms-10 sm:flex items-center">
                    <a href="{{ route('dashboard') }}" 
                       class="flex items-center space-x-2 px-4 py-2 rounded-xl transition-all hover-lift {{ request()->routeIs('dashboard') ? 'gradient-bg-primary text-white shadow-glow' : 'text-gray-700 hover:bg-white/50' }}">
                        <span class="text-lg">📊</span>
                        <span class="font-medium">Dashboard</span>
                    </a>
                    <a href="{{ route('chat.index') }}" 
                       class="flex items-center space-x-2 px-4 py-2 rounded-xl transition-all hover-lift {{ request()->routeIs('chat.*') ? 'gradient-bg-primary text-white shadow-glow' : 'text-gray-700 hover:bg-white/50' }}">
                        <span class="text-lg">🤖</span>
                        <span class="font-medium">AI Chat</span>
                    </a>
                    <a href="{{ route('health-metrics.index') }}" 
                       class="flex items-center space-x-2 px-4 py-2 rounded-xl transition-all hover-lift {{ request()->routeIs('health-metrics.*') ? 'gradient-bg-primary text-white shadow-glow' : 'text-gray-700 hover:bg-white/50' }}">
                        <span class="text-lg">❤️</span>
                        <span class="font-medium">Health Metrics</span>
                    </a>
                    <a href="{{ route('symptom-checker.index') }}" 
                       class="flex items-center space-x-2 px-4 py-2 rounded-xl transition-all hover-lift {{ request()->routeIs('symptom-checker.*') ? 'gradient-bg-primary text-white shadow-glow' : 'text-gray-700 hover:bg-white/50' }}">
                        <span class="text-lg">🩺</span>
                        <span class="font-medium">Symptom Checker</span>
                    </a>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center space-x-3 px-4 py-2 rounded-xl glass hover:bg-white/30 transition-all hover-lift">
                            <div class="w-10 h-10 gradient-bg-secondary rounded-full flex items-center justify-center">
                                <span class="text-white font-bold text-sm">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            </div>
                            <div class="text-left">
                                <div class="font-medium text-gray-800">{{ Auth::user()->name }}</div>
                                <div class="text-xs text-gray-600">{{ Auth::user()->email }}</div>
                            </div>
                            <svg class="fill-current h-4 w-4 text-gray-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
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
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('chat.index')" :active="request()->routeIs('chat.*')">
                {{ __('AI Chat') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('health-metrics.index')" :active="request()->routeIs('health-metrics.*')">
                {{ __('Health Metrics') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('symptom-checker.index')" :active="request()->routeIs('symptom-checker.*')">
                {{ __('Symptom Checker') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
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
    </div>
</nav>
