@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-blue-900 to-slate-900">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-4xl font-bold text-white mb-2">{{ $healthPlan->title }}</h1>
                <p class="text-slate-300">{{ $healthPlan->description ?? 'No description provided' }}</p>
            </div>
            <div class="flex items-center space-x-4">
                <span class="px-4 py-2 rounded-full text-sm font-medium {{ $healthPlan->status_badge }}">
                    {{ ucfirst($healthPlan->status) }}
                </span>
                <a href="{{ route('health-plans.edit', $healthPlan) }}" 
                   class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Daily Activities -->
                <div class="bg-slate-800/50 backdrop-blur-lg rounded-xl p-6 border border-slate-700">
                    <h3 class="text-xl font-semibold text-white mb-4">
                        <i class="fas fa-calendar-day text-blue-400 mr-2"></i>Daily Activities
                    </h3>
                    <ul class="space-y-3">
                        @foreach($healthPlan->daily_activities as $activity)
                            <li class="flex items-center space-x-3">
                                <div class="w-2 h-2 bg-blue-400 rounded-full"></div>
                                <span class="text-slate-300">{{ $activity }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Exercise Plan -->
                <div class="bg-slate-800/50 backdrop-blur-lg rounded-xl p-6 border border-slate-700">
                    <h3 class="text-xl font-semibold text-white mb-4">
                        <i class="fas fa-dumbbell text-green-400 mr-2"></i>Exercise Plan
                    </h3>
                    <ul class="space-y-3">
                        @foreach($healthPlan->exercise_plan as $exercise)
                            <li class="flex items-center space-x-3">
                                <div class="w-2 h-2 bg-green-400 rounded-full"></div>
                                <span class="text-slate-300">{{ $exercise }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Dietary Recommendations -->
                <div class="bg-slate-800/50 backdrop-blur-lg rounded-xl p-6 border border-slate-700">
                    <h3 class="text-xl font-semibold text-white mb-4">
                        <i class="fas fa-apple-alt text-orange-400 mr-2"></i>Dietary Recommendations
                    </h3>
                    <ul class="space-y-3">
                        @foreach($healthPlan->dietary_recommendations as $recommendation)
                            <li class="flex items-center space-x-3">
                                <div class="w-2 h-2 bg-orange-400 rounded-full"></div>
                                <span class="text-slate-300">{{ $recommendation }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Plan Overview -->
                <div class="bg-slate-800/50 backdrop-blur-lg rounded-xl p-6 border border-slate-700">
                    <h3 class="text-lg font-semibold text-white mb-4">Plan Overview</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-slate-400">Start Date</p>
                            <p class="text-white font-medium">{{ $healthPlan->start_date->format('M d, Y') }}</p>
                        </div>
                        @if($healthPlan->end_date)
                            <div>
                                <p class="text-sm text-slate-400">End Date</p>
                                <p class="text-white font-medium">{{ $healthPlan->end_date->format('M d, Y') }}</p>
                            </div>
                        @endif
                        <div>
                            <p class="text-sm text-slate-400">Duration</p>
                            <p class="text-white font-medium">{{ $healthPlan->duration }}</p>
                        </div>
                    </div>
                </div>

                <!-- Goals -->
                <div class="bg-slate-800/50 backdrop-blur-lg rounded-xl p-6 border border-slate-700">
                    <h3 class="text-lg font-semibold text-white mb-4">Goals</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($healthPlan->goals as $goal)
                            <span class="px-3 py-1 bg-green-500/20 text-green-400 text-sm rounded-full">
                                {{ ucfirst(str_replace('_', ' ', $goal)) }}
                            </span>
                        @endforeach
                    </div>
                </div>

                <!-- Health Targets -->
                <div class="bg-slate-800/50 backdrop-blur-lg rounded-xl p-6 border border-slate-700">
                    <h3 class="text-lg font-semibold text-white mb-4">Health Targets</h3>
                    <ul class="space-y-2">
                        @foreach($healthPlan->health_targets as $target)
                            <li class="flex items-center space-x-2">
                                <i class="fas fa-target text-purple-400"></i>
                                <span class="text-slate-300 text-sm">{{ $target }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Weekly Activities -->
                <div class="bg-slate-800/50 backdrop-blur-lg rounded-xl p-6 border border-slate-700">
                    <h3 class="text-lg font-semibold text-white mb-4">Weekly Activities</h3>
                    <ul class="space-y-2">
                        @foreach($healthPlan->weekly_activities as $activity)
                            <li class="flex items-center space-x-2">
                                <i class="fas fa-calendar-week text-cyan-400"></i>
                                <span class="text-slate-300 text-sm">{{ $activity }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
