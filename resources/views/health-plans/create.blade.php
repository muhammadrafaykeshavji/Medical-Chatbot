@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-blue-900 to-slate-900">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-white mb-4">Create Health Plan</h1>
            <p class="text-slate-300 text-lg">Generate a personalized health and wellness plan tailored to your goals</p>
        </div>

        <!-- Form -->
        <div class="max-w-4xl mx-auto">
            <div class="bg-slate-800/50 backdrop-blur-lg rounded-xl p-8 border border-slate-700">
                <form method="POST" action="{{ route('health-plans.store') }}" class="space-y-6">
                    @csrf

                    <!-- Plan Title -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Plan Title</label>
                        <input type="text" name="title" value="{{ old('title') }}" required
                               placeholder="e.g., My Weight Loss Journey, Fitness Challenge 2024"
                               class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        @error('title')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Description (Optional)</label>
                        <textarea name="description" rows="3" placeholder="Describe your health plan goals and motivation..."
                                  class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:ring-2 focus:ring-green-500 focus:border-transparent">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Health Goals -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-4">Select Your Health Goals</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @php
                                $goals = [
                                    'weight_loss' => ['icon' => 'fas fa-weight', 'label' => 'Weight Loss', 'color' => 'red'],
                                    'muscle_gain' => ['icon' => 'fas fa-dumbbell', 'label' => 'Muscle Gain', 'color' => 'blue'],
                                    'fitness' => ['icon' => 'fas fa-running', 'label' => 'General Fitness', 'color' => 'green'],
                                    'endurance' => ['icon' => 'fas fa-heartbeat', 'label' => 'Endurance', 'color' => 'purple'],
                                    'flexibility' => ['icon' => 'fas fa-leaf', 'label' => 'Flexibility', 'color' => 'yellow'],
                                    'stress_management' => ['icon' => 'fas fa-brain', 'label' => 'Stress Management', 'color' => 'indigo'],
                                    'nutrition' => ['icon' => 'fas fa-apple-alt', 'label' => 'Better Nutrition', 'color' => 'orange'],
                                    'sleep_quality' => ['icon' => 'fas fa-bed', 'label' => 'Sleep Quality', 'color' => 'cyan'],
                                    'general_wellness' => ['icon' => 'fas fa-heart', 'label' => 'General Wellness', 'color' => 'pink']
                                ];
                            @endphp

                            @foreach($goals as $key => $goal)
                                <label class="relative goal-option" data-goal="{{ $key }}">
                                    <input type="checkbox" name="goals[]" value="{{ $key }}" 
                                           class="sr-only goal-checkbox" {{ in_array($key, old('goals', [])) ? 'checked' : '' }}>
                                    <div class="goal-card bg-slate-700 border border-slate-600 rounded-lg p-4 cursor-pointer transition-all duration-200 hover:border-{{ $goal['color'] }}-400">
                                        <div class="flex items-center space-x-3">
                                            <i class="{{ $goal['icon'] }} text-{{ $goal['color'] }}-400 text-xl"></i>
                                            <span class="text-white font-medium">{{ $goal['label'] }}</span>
                                        </div>
                                        <div class="goal-indicator absolute top-2 right-2 w-5 h-5 border-2 border-slate-400 rounded-full">
                                            <i class="fas fa-check text-white text-xs absolute top-0.5 left-0.5 opacity-0"></i>
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        @error('goals')
                            <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Date Range -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Start Date</label>
                            <input type="date" name="start_date" value="{{ old('start_date', date('Y-m-d')) }}" required
                                   class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            @error('start_date')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">End Date (Optional)</label>
                            <input type="date" name="end_date" value="{{ old('end_date') }}"
                                   class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            @error('end_date')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex items-center justify-between pt-6">
                        <a href="{{ route('health-plans.index') }}" 
                           class="px-6 py-3 border border-slate-600 text-slate-300 rounded-lg hover:bg-slate-700 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="bg-gradient-to-r from-green-600 to-emerald-600 text-white px-8 py-3 rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-200 font-medium">
                            <i class="fas fa-magic mr-2"></i>Generate Health Plan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Goal selection functionality
    const goalOptions = document.querySelectorAll('.goal-option');
    const goalColors = {
        'weight_loss': 'red',
        'muscle_gain': 'blue', 
        'fitness': 'green',
        'endurance': 'purple',
        'flexibility': 'yellow',
        'stress_management': 'indigo',
        'nutrition': 'orange',
        'sleep_quality': 'cyan',
        'general_wellness': 'pink'
    };

    goalOptions.forEach(option => {
        const checkbox = option.querySelector('.goal-checkbox');
        const card = option.querySelector('.goal-card');
        const indicator = option.querySelector('.goal-indicator');
        const checkIcon = indicator.querySelector('.fas.fa-check');
        const goalKey = option.dataset.goal;
        const color = goalColors[goalKey];

        // Set initial state
        updateGoalAppearance(checkbox.checked, card, indicator, checkIcon, color);

        // Handle clicks
        option.addEventListener('click', function(e) {
            e.preventDefault();
            checkbox.checked = !checkbox.checked;
            updateGoalAppearance(checkbox.checked, card, indicator, checkIcon, color);
        });

        // Handle checkbox change (for form validation)
        checkbox.addEventListener('change', function() {
            updateGoalAppearance(this.checked, card, indicator, checkIcon, color);
        });
    });

    function updateGoalAppearance(isChecked, card, indicator, checkIcon, color) {
        if (isChecked) {
            // Selected state
            card.classList.remove('border-slate-600');
            card.classList.add(`border-${color}-500`, `bg-${color}-500/10`);
            
            indicator.classList.remove('border-slate-400');
            indicator.classList.add(`border-${color}-500`, `bg-${color}-500`);
            
            checkIcon.classList.remove('opacity-0');
            checkIcon.classList.add('opacity-100');
        } else {
            // Unselected state
            card.classList.add('border-slate-600');
            card.classList.remove(`border-${color}-500`, `bg-${color}-500/10`);
            
            indicator.classList.add('border-slate-400');
            indicator.classList.remove(`border-${color}-500`, `bg-${color}-500`);
            
            checkIcon.classList.add('opacity-0');
            checkIcon.classList.remove('opacity-100');
        }
    }

    // Form validation
    const form = document.querySelector('form');
    const submitBtn = form.querySelector('button[type="submit"]');
    
    form.addEventListener('submit', function(e) {
        const checkedGoals = document.querySelectorAll('.goal-checkbox:checked');
        if (checkedGoals.length === 0) {
            e.preventDefault();
            alert('Please select at least one health goal.');
            return false;
        }
        
        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Generating Health Plan...';
    });
});
</script>
@endsection
