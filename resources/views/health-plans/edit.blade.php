@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-blue-900 to-slate-900">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-white mb-4">Edit Health Plan</h1>
            <p class="text-slate-300 text-lg">Update your health plan details and status</p>
        </div>

        <!-- Form -->
        <div class="max-w-2xl mx-auto">
            <div class="bg-slate-800/50 backdrop-blur-lg rounded-xl p-8 border border-slate-700">
                <form method="POST" action="{{ route('health-plans.update', $healthPlan) }}" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    <!-- Plan Title -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Plan Title</label>
                        <input type="text" name="title" value="{{ old('title', $healthPlan->title) }}" required
                               class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        @error('title')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Description</label>
                        <textarea name="description" rows="3"
                                  class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white focus:ring-2 focus:ring-green-500 focus:border-transparent">{{ old('description', $healthPlan->description) }}</textarea>
                        @error('description')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Status</label>
                        <select name="status" required class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="active" {{ old('status', $healthPlan->status) === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="paused" {{ old('status', $healthPlan->status) === 'paused' ? 'selected' : '' }}>Paused</option>
                            <option value="completed" {{ old('status', $healthPlan->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                        @error('status')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Notes</label>
                        <textarea name="notes" rows="4" placeholder="Add any notes about your progress or modifications..."
                                  class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:ring-2 focus:ring-green-500 focus:border-transparent">{{ old('notes', $healthPlan->notes) }}</textarea>
                        @error('notes')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex items-center justify-between pt-6">
                        <a href="{{ route('health-plans.show', $healthPlan) }}" 
                           class="px-6 py-3 border border-slate-600 text-slate-300 rounded-lg hover:bg-slate-700 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="bg-gradient-to-r from-green-600 to-emerald-600 text-white px-8 py-3 rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-200 font-medium">
                            <i class="fas fa-save mr-2"></i>Update Plan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
