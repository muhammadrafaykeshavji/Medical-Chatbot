@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-blue-900 to-slate-900">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-4xl font-bold text-white mb-2">Health Plans</h1>
                <p class="text-slate-300 text-lg">Manage your personalized health and wellness plans</p>
            </div>
            <a href="{{ route('health-plans.create') }}" class="bg-gradient-to-r from-green-600 to-emerald-600 text-white px-6 py-3 rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-200 font-medium">
                <i class="fas fa-plus mr-2"></i>Create New Plan
            </a>
        </div>

        @if($healthPlans->count() > 0)
            <!-- Plans Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @foreach($healthPlans as $plan)
                    <div class="bg-slate-800/50 backdrop-blur-lg rounded-xl p-6 border border-slate-700 hover:border-green-500 transition-all duration-200">
                        <!-- Plan Header -->
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-12 h-12 bg-green-500/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-clipboard-list text-green-400 text-xl"></i>
                            </div>
                            <span class="px-3 py-1 rounded-full text-xs font-medium {{ $plan->status_badge }}">
                                {{ ucfirst($plan->status) }}
                            </span>
                        </div>

                        <!-- Plan Content -->
                        <h3 class="text-xl font-semibold text-white mb-2">{{ $plan->title }}</h3>
                        <p class="text-slate-400 text-sm mb-4 line-clamp-2">{{ $plan->description ?? 'No description provided' }}</p>

                        <!-- Plan Details -->
                        <div class="space-y-2 mb-4">
                            <div class="flex items-center text-sm text-slate-300">
                                <i class="fas fa-calendar-alt text-blue-400 mr-2 w-4"></i>
                                <span>Started: {{ $plan->start_date->format('M d, Y') }}</span>
                            </div>
                            @if($plan->end_date)
                                <div class="flex items-center text-sm text-slate-300">
                                    <i class="fas fa-flag-checkered text-purple-400 mr-2 w-4"></i>
                                    <span>Ends: {{ $plan->end_date->format('M d, Y') }}</span>
                                </div>
                            @endif
                            <div class="flex items-center text-sm text-slate-300">
                                <i class="fas fa-clock text-yellow-400 mr-2 w-4"></i>
                                <span>Duration: {{ $plan->duration }}</span>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        @if($plan->status !== 'completed')
                            <div class="mb-4">
                                <div class="flex justify-between text-sm text-slate-300 mb-1">
                                    <span>Progress</span>
                                    <span>{{ $plan->progress_percentage }}%</span>
                                </div>
                                <div class="w-full bg-slate-700 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-green-500 to-emerald-500 h-2 rounded-full transition-all duration-300" 
                                         style="width: {{ $plan->progress_percentage }}%"></div>
                                </div>
                            </div>
                        @endif

                        <!-- Goals -->
                        <div class="mb-4">
                            <p class="text-sm font-medium text-slate-300 mb-2">Goals:</p>
                            <div class="flex flex-wrap gap-1">
                                @foreach($plan->goals as $goal)
                                    <span class="px-2 py-1 bg-green-500/20 text-green-400 text-xs rounded-full">
                                        {{ ucfirst(str_replace('_', ' ', $goal)) }}
                                    </span>
                                @endforeach
                            </div>
                        </div>

                        <!-- Action Button -->
                        <a href="{{ route('health-plans.show', $plan) }}" 
                           class="inline-block w-full text-center bg-gradient-to-r from-green-600 to-emerald-600 text-white px-4 py-2 rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-200 font-medium">
                            View Details
                        </a>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="flex justify-center">
                {{ $healthPlans->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="w-24 h-24 bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-clipboard-list text-4xl text-slate-400"></i>
                </div>
                <h3 class="text-2xl font-semibold text-white mb-4">No Health Plans Yet</h3>
                <p class="text-slate-400 mb-8 max-w-md mx-auto">Create your first personalized health plan to start your wellness journey with AI-powered recommendations.</p>
                <a href="{{ route('health-plans.create') }}" class="bg-gradient-to-r from-green-600 to-emerald-600 text-white px-8 py-3 rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-200 font-medium">
                    <i class="fas fa-plus mr-2"></i>Create Your First Plan
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
