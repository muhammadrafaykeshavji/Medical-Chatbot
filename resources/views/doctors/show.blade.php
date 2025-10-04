@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-blue-900 to-slate-900">
    <div class="container mx-auto px-4 py-8">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('doctors.index') }}" class="text-blue-400 hover:text-blue-300 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Back to Doctors
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Doctor Profile -->
            <div class="lg:col-span-2">
                <div class="bg-slate-800/50 backdrop-blur-lg rounded-xl p-8 border border-slate-700">
                    <!-- Header -->
                    <div class="flex items-start space-x-6 mb-8">
                        <div class="w-24 h-24 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white text-3xl font-bold">
                            {{ substr($doctor->name, 0, 1) }}
                        </div>
                        <div class="flex-1">
                            <h1 class="text-3xl font-bold text-white mb-2">Dr. {{ $doctor->name }}</h1>
                            <p class="text-blue-400 text-lg mb-2">{{ $doctor->specialty }}</p>
                            <p class="text-slate-400 mb-4">{{ $doctor->qualification }}</p>
                            <div class="flex items-center space-x-6 text-sm">
                                <span class="flex items-center text-yellow-400">
                                    <i class="fas fa-star mr-1"></i>{{ $doctor->rating }}/5
                                </span>
                                <span class="flex items-center text-blue-400">
                                    <i class="fas fa-clock mr-1"></i>{{ $doctor->years_experience }} years
                                </span>
                                <span class="flex items-center {{ $doctor->is_available ? 'text-green-400' : 'text-red-400' }}">
                                    <i class="fas fa-circle mr-1 text-xs"></i>{{ $doctor->availability_status }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Bio -->
                    @if($doctor->bio)
                        <div class="mb-8">
                            <h3 class="text-xl font-semibold text-white mb-4">About</h3>
                            <p class="text-slate-300 leading-relaxed">{{ $doctor->bio }}</p>
                        </div>
                    @endif

                    <!-- Availability -->
                    @if($doctor->available_days)
                        <div class="mb-8">
                            <h3 class="text-xl font-semibold text-white mb-4">Availability</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-slate-400 mb-2">Available Days</p>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($doctor->available_days as $day)
                                            <span class="px-3 py-1 bg-blue-500/20 text-blue-400 text-sm rounded-full">
                                                {{ $day }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                                @if($doctor->available_from && $doctor->available_to)
                                    <div>
                                        <p class="text-sm text-slate-400 mb-2">Hours</p>
                                        <p class="text-white">
                                            {{ $doctor->available_from->format('g:i A') }} - 
                                            {{ $doctor->available_to->format('g:i A') }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Contact Information -->
                <div class="bg-slate-800/50 backdrop-blur-lg rounded-xl p-6 border border-slate-700">
                    <h3 class="text-lg font-semibold text-white mb-4">Contact Information</h3>
                    <div class="space-y-4">
                        @if($doctor->phone)
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-phone text-green-400 w-5"></i>
                                <div>
                                    <p class="text-sm text-slate-400">Phone</p>
                                    <p class="text-white">{{ $doctor->phone }}</p>
                                </div>
                            </div>
                        @endif

                        @if($doctor->email)
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-envelope text-blue-400 w-5"></i>
                                <div>
                                    <p class="text-sm text-slate-400">Email</p>
                                    <p class="text-white">{{ $doctor->email }}</p>
                                </div>
                            </div>
                        @endif

                        <div class="flex items-start space-x-3">
                            <i class="fas fa-map-marker-alt text-red-400 w-5 mt-1"></i>
                            <div>
                                <p class="text-sm text-slate-400">Location</p>
                                <p class="text-white">{{ $doctor->full_address }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Consultation Fee -->
                @if($doctor->consultation_fee)
                    <div class="bg-slate-800/50 backdrop-blur-lg rounded-xl p-6 border border-slate-700">
                        <h3 class="text-lg font-semibold text-white mb-4">Consultation Fee</h3>
                        <div class="text-center">
                            <p class="text-3xl font-bold text-green-400">${{ $doctor->consultation_fee }}</p>
                            <p class="text-slate-400 text-sm">per consultation</p>
                        </div>
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="space-y-3">
                    @if($doctor->phone)
                        <a href="tel:{{ $doctor->phone }}" 
                           class="block w-full bg-gradient-to-r from-green-600 to-emerald-600 text-white px-6 py-3 rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-200 font-medium text-center">
                            <i class="fas fa-phone mr-2"></i>Call Now
                        </a>
                    @endif

                    @if($doctor->email)
                        <a href="mailto:{{ $doctor->email }}" 
                           class="block w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-3 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 font-medium text-center">
                            <i class="fas fa-envelope mr-2"></i>Send Email
                        </a>
                    @endif

                    <button class="w-full bg-gradient-to-r from-purple-600 to-pink-600 text-white px-6 py-3 rounded-lg hover:from-purple-700 hover:to-pink-700 transition-all duration-200 font-medium">
                        <i class="fas fa-calendar-plus mr-2"></i>Book Appointment
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
