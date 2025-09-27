<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Symptom Checker History') }}
            </h2>
            <a href="{{ route('symptom-checker.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                🩺 New Symptom Check
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($symptomChecks->count() > 0)
                <div class="space-y-6">
                    @foreach($symptomChecks as $check)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <h3 class="text-lg font-medium text-gray-900">
                                            Symptom Check
                                        </h3>
                                        <span class="px-2 py-1 text-xs rounded-full 
                                            @if($check->urgency_level === 'emergency') bg-red-100 text-red-800
                                            @elseif($check->urgency_level === 'high') bg-orange-100 text-orange-800
                                            @elseif($check->urgency_level === 'medium') bg-yellow-100 text-yellow-800
                                            @else bg-green-100 text-green-800
                                            @endif">
                                            {{ ucfirst($check->urgency_level) }} Priority
                                        </span>
                                        @if($check->doctor_recommended)
                                        <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">
                                            Doctor Recommended
                                        </span>
                                        @endif
                                    </div>
                                    
                                    <div class="mb-3">
                                        <h4 class="font-medium text-gray-700 mb-1">Symptoms:</h4>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($check->symptoms as $symptom)
                                            <span class="px-2 py-1 bg-gray-100 text-gray-700 text-sm rounded">{{ $symptom }}</span>
                                            @endforeach
                                        </div>
                                    </div>

                                    @if($check->description)
                                    <div class="mb-3">
                                        <h4 class="font-medium text-gray-700 mb-1">Description:</h4>
                                        <p class="text-gray-600 text-sm">{{ $check->description }}</p>
                                    </div>
                                    @endif

                                    @if($check->recommendations)
                                    <div class="mb-3">
                                        <h4 class="font-medium text-gray-700 mb-1">Recommendations:</h4>
                                        <p class="text-gray-600 text-sm">{{ $check->recommendations }}</p>
                                    </div>
                                    @endif
                                </div>
                                
                                <div class="text-right">
                                    <span class="text-sm text-gray-500">{{ $check->created_at->format('M d, Y H:i') }}</span>
                                    <div class="mt-2">
                                        <a href="{{ route('symptom-checker.show', $check) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                                            View Details →
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $symptomChecks->links() }}
                    </div>
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl">🩺</span>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No symptom checks yet</h3>
                        <p class="text-gray-500 mb-4">Use our AI-powered symptom checker to get health guidance.</p>
                        <a href="{{ route('symptom-checker.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Start Symptom Check
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
