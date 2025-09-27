<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center" data-aos="fade-down">
            <div>
                <h2 class="text-3xl font-bold bg-gradient-to-r from-purple-600 to-blue-600 bg-clip-text text-transparent">
                    Welcome back, {{ Auth::user()->name }}! 👋
                </h2>
                <p class="text-gray-600 mt-1">Your health journey continues here</p>
            </div>
            <div class="flex space-x-4">
                <a href="{{ route('chat.new') }}" class="gradient-bg-primary hover-lift text-white font-bold py-3 px-6 rounded-xl shadow-glow transition-all flex items-center space-x-2">
                    <span class="text-xl">🤖</span>
                    <span>New Chat</span>
                </a>
                <a href="{{ route('symptom-checker.create') }}" class="gradient-bg-success hover-lift text-white font-bold py-3 px-6 rounded-xl shadow-glow transition-all flex items-center space-x-2">
                    <span class="text-xl">🩺</span>
                    <span>Check Symptoms</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Health Overview Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Recent Health Metrics -->
                <div class="glass shadow-premium rounded-2xl hover-lift transition-all" data-aos="fade-up" data-aos-delay="100">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="w-16 h-16 gradient-bg-primary rounded-2xl flex items-center justify-center float">
                                    <span class="text-white text-2xl">📊</span>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900">Health Metrics</h3>
                                    <p class="text-gray-600">{{ $recentMetrics->count() }} recent entries</p>
                                </div>
                            </div>
                            <div class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                                {{ $recentMetrics->count() }}
                            </div>
                        </div>
                        <div class="mt-4 w-full bg-gray-200 rounded-full h-2">
                            <div class="gradient-bg-primary h-2 rounded-full" style="width: {{ min(($recentMetrics->count() / 10) * 100, 100) }}%"></div>
                        </div>
                    </div>
                </div>

                <!-- Active Medications -->
                <div class="glass shadow-premium rounded-2xl hover-lift transition-all" data-aos="fade-up" data-aos-delay="200">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="w-16 h-16 gradient-bg-success rounded-2xl flex items-center justify-center float">
                                    <span class="text-white text-2xl">💊</span>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900">Medications</h3>
                                    <p class="text-gray-600">{{ $activeMedications->count() }} active</p>
                                </div>
                            </div>
                            <div class="text-3xl font-bold bg-gradient-to-r from-green-600 to-blue-600 bg-clip-text text-transparent">
                                {{ $activeMedications->count() }}
                            </div>
                        </div>
                        <div class="mt-4 w-full bg-gray-200 rounded-full h-2">
                            <div class="gradient-bg-success h-2 rounded-full" style="width: {{ min(($activeMedications->count() / 5) * 100, 100) }}%"></div>
                        </div>
                    </div>
                </div>

                <!-- Recent Conversations -->
                <div class="glass shadow-premium rounded-2xl hover-lift transition-all" data-aos="fade-up" data-aos-delay="300">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="w-16 h-16 gradient-bg-secondary rounded-2xl flex items-center justify-center float">
                                    <span class="text-white text-2xl">💬</span>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900">AI Conversations</h3>
                                    <p class="text-gray-600">{{ $recentConversations->count() }} recent chats</p>
                                </div>
                            </div>
                            <div class="text-3xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">
                                {{ $recentConversations->count() }}
                            </div>
                        </div>
                        <div class="mt-4 w-full bg-gray-200 rounded-full h-2">
                            <div class="gradient-bg-secondary h-2 rounded-full" style="width: {{ min(($recentConversations->count() / 10) * 100, 100) }}%"></div>
                        </div>
                    </div>
                </div>

                <!-- Symptom Checks -->
                <div class="glass shadow-premium rounded-2xl hover-lift transition-all" data-aos="fade-up" data-aos-delay="400">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="w-16 h-16 bg-gradient-to-r from-red-500 to-orange-500 rounded-2xl flex items-center justify-center float">
                                    <span class="text-white text-2xl">🩺</span>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900">Symptom Checks</h3>
                                    <p class="text-gray-600">{{ $recentSymptomChecks->count() }} this month</p>
                                </div>
                            </div>
                            <div class="text-3xl font-bold bg-gradient-to-r from-red-600 to-orange-600 bg-clip-text text-transparent">
                                {{ $recentSymptomChecks->count() }}
                            </div>
                        </div>
                        <div class="mt-4 w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-gradient-to-r from-red-500 to-orange-500 h-2 rounded-full" style="width: {{ min(($recentSymptomChecks->count() / 5) * 100, 100) }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Health Metrics Charts -->
            @if(!empty($healthMetricsSummary))
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8" data-aos="fade-up" data-aos-delay="500">
                @foreach($healthMetricsSummary as $type => $data)
                <div class="glass shadow-premium rounded-2xl hover-lift transition-all">
                    <div class="p-8">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 
                                    @if($type === 'blood_pressure') bg-gradient-to-r from-red-500 to-pink-500
                                    @elseif($type === 'blood_sugar') bg-gradient-to-r from-green-500 to-emerald-500
                                    @elseif($type === 'weight') bg-gradient-to-r from-blue-500 to-cyan-500
                                    @elseif($type === 'heart_rate') bg-gradient-to-r from-purple-500 to-violet-500
                                    @else bg-gradient-to-r from-gray-500 to-slate-500
                                    @endif
                                    rounded-xl flex items-center justify-center">
                                    <span class="text-white text-xl">
                                        @if($type === 'blood_pressure') 🩸
                                        @elseif($type === 'blood_sugar') 🍯
                                        @elseif($type === 'weight') ⚖️
                                        @elseif($type === 'heart_rate') ❤️
                                        @else 🌡️
                                        @endif
                                    </span>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-bold text-gray-900">
                                        {{ ucfirst(str_replace('_', ' ', $type)) }}
                                    </h3>
                                    <p class="text-gray-600">Trend Analysis</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm text-gray-500">Latest</div>
                                <div class="text-lg font-bold text-gray-900">
                                    @if($type === 'blood_pressure' && isset($data['latest']))
                                        {{ $data['latest']->systolic }}/{{ $data['latest']->diastolic }}
                                    @elseif(isset($data['latest']))
                                        {{ $data['latest']->value }}
                                    @endif
                                    <span class="text-sm text-gray-500">{{ $data['latest']->unit ?? '' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="h-80 relative">
                            <canvas id="chart-{{ $type }}"></canvas>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            <!-- Recent Activity -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8" data-aos="fade-up" data-aos-delay="600">
                <!-- Recent Health Metrics -->
                <div class="glass shadow-premium rounded-2xl hover-lift transition-all">
                    <div class="p-8">
                        <div class="flex items-center space-x-3 mb-6">
                            <div class="w-12 h-12 gradient-bg-primary rounded-xl flex items-center justify-center">
                                <span class="text-white text-xl">📊</span>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900">Recent Health Metrics</h3>
                                <p class="text-gray-600">Your latest measurements</p>
                            </div>
                        </div>
                        @if($recentMetrics->count() > 0)
                            <div class="space-y-4">
                                @foreach($recentMetrics->take(5) as $metric)
                                <div class="flex justify-between items-center p-4 glass rounded-xl hover-lift transition-all">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-10 h-10 
                                            @if($metric->metric_type === 'blood_pressure') bg-gradient-to-r from-red-500 to-pink-500
                                            @elseif($metric->metric_type === 'blood_sugar') bg-gradient-to-r from-green-500 to-emerald-500
                                            @elseif($metric->metric_type === 'weight') bg-gradient-to-r from-blue-500 to-cyan-500
                                            @elseif($metric->metric_type === 'heart_rate') bg-gradient-to-r from-purple-500 to-violet-500
                                            @else bg-gradient-to-r from-gray-500 to-slate-500
                                            @endif
                                            rounded-lg flex items-center justify-center">
                                            <span class="text-white text-sm">
                                                @if($metric->metric_type === 'blood_pressure') 🩸
                                                @elseif($metric->metric_type === 'blood_sugar') 🍯
                                                @elseif($metric->metric_type === 'weight') ⚖️
                                                @elseif($metric->metric_type === 'heart_rate') ❤️
                                                @else 🌡️
                                                @endif
                                            </span>
                                        </div>
                                        <div>
                                            <span class="font-bold text-gray-900">{{ ucfirst(str_replace('_', ' ', $metric->metric_type)) }}</span>
                                            <span class="text-sm text-gray-500 block">{{ $metric->recorded_at->format('M d, Y') }}</span>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        @if($metric->metric_type === 'blood_pressure')
                                            <span class="text-xl font-bold text-gray-900">{{ $metric->systolic }}/{{ $metric->diastolic }}</span>
                                        @else
                                            <span class="text-xl font-bold text-gray-900">{{ $metric->value }}</span>
                                        @endif
                                        <span class="text-sm text-gray-500 block">{{ $metric->unit }}</span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div class="mt-6">
                                <a href="{{ route('health-metrics.index') }}" class="gradient-bg-primary text-white px-6 py-3 rounded-xl hover-lift transition-all inline-flex items-center space-x-2">
                                    <span>View all metrics</span>
                                    <span>→</span>
                                </a>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="w-20 h-20 gradient-bg-primary rounded-full flex items-center justify-center mx-auto mb-4 float">
                                    <span class="text-white text-3xl">📊</span>
                                </div>
                                <p class="text-gray-500 mb-4">No health metrics recorded yet.</p>
                                <a href="{{ route('health-metrics.create') }}" class="gradient-bg-primary text-white px-6 py-3 rounded-xl hover-lift transition-all inline-flex items-center space-x-2">
                                    <span>Add your first metric</span>
                                    <span>→</span>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Recent Conversations -->
                <div class="glass shadow-premium rounded-2xl hover-lift transition-all">
                    <div class="p-8">
                        <div class="flex items-center space-x-3 mb-6">
                            <div class="w-12 h-12 gradient-bg-secondary rounded-xl flex items-center justify-center">
                                <span class="text-white text-xl">🤖</span>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900">Recent AI Conversations</h3>
                                <p class="text-gray-600">Your chat history</p>
                            </div>
                        </div>
                        @if($recentConversations->count() > 0)
                            <div class="space-y-4">
                                @foreach($recentConversations->take(5) as $conversation)
                                <div class="p-4 glass rounded-xl hover-lift transition-all">
                                    <a href="{{ route('chat.show', $conversation) }}" class="block">
                                        <div class="flex justify-between items-start">
                                            <div class="flex-1">
                                                <div class="flex items-center space-x-3 mb-2">
                                                    <span class="font-bold text-gray-900">{{ $conversation->title ?? 'Untitled Conversation' }}</span>
                                                    <span class="text-xs 
                                                        @if($conversation->type === 'symptom_check') bg-gradient-to-r from-red-500 to-pink-500
                                                        @elseif($conversation->type === 'health_advice') bg-gradient-to-r from-green-500 to-emerald-500
                                                        @else bg-gradient-to-r from-blue-500 to-cyan-500
                                                        @endif
                                                        text-white px-3 py-1 rounded-full font-medium">
                                                        {{ ucfirst(str_replace('_', ' ', $conversation->type)) }}
                                                    </span>
                                                </div>
                                                @if($conversation->latestMessage)
                                                <p class="text-sm text-gray-600 mb-2">{{ Str::limit($conversation->latestMessage->message, 80) }}</p>
                                                @endif
                                            </div>
                                            <div class="text-right ml-4">
                                                <span class="text-xs text-gray-400">{{ $conversation->last_message_at?->diffForHumans() }}</span>
                                                <div class="mt-1">
                                                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full">Continue →</span>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                @endforeach
                            </div>
                            <div class="mt-6">
                                <a href="{{ route('chat.index') }}" class="gradient-bg-secondary text-white px-6 py-3 rounded-xl hover-lift transition-all inline-flex items-center space-x-2">
                                    <span>View all conversations</span>
                                    <span>→</span>
                                </a>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="w-20 h-20 gradient-bg-secondary rounded-full flex items-center justify-center mx-auto mb-4 float">
                                    <span class="text-white text-3xl">🤖</span>
                                </div>
                                <p class="text-gray-500 mb-4">No conversations yet.</p>
                                <a href="{{ route('chat.new') }}" class="gradient-bg-secondary text-white px-6 py-3 rounded-xl hover-lift transition-all inline-flex items-center space-x-2">
                                    <span>Start your first chat</span>
                                    <span>→</span>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
        // Initialize charts for health metrics
        @if(!empty($healthMetricsSummary))
        @foreach($healthMetricsSummary as $type => $data)
        (function() {
            const ctx = document.getElementById('chart-{{ $type }}').getContext('2d');
            const chartData = @json($data['data']);
            
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chartData.map(item => item.date),
                    datasets: [{
                        label: '{{ ucfirst(str_replace("_", " ", $type)) }}',
                        data: chartData.map(item => {
                            // Handle blood pressure differently
                            @if($type === 'blood_pressure')
                            return parseFloat(item.value.split('/')[0]); // Use systolic for chart
                            @else
                            return parseFloat(item.value);
                            @endif
                        }),
                        borderColor: 
                            @if($type === 'blood_pressure') 'rgb(239, 68, 68)'
                            @elseif($type === 'blood_sugar') 'rgb(34, 197, 94)'
                            @elseif($type === 'weight') 'rgb(59, 130, 246)'
                            @elseif($type === 'heart_rate') 'rgb(168, 85, 247)'
                            @else 'rgb(107, 114, 128)'
                            @endif,
                        backgroundColor: 
                            @if($type === 'blood_pressure') 'rgba(239, 68, 68, 0.1)'
                            @elseif($type === 'blood_sugar') 'rgba(34, 197, 94, 0.1)'
                            @elseif($type === 'weight') 'rgba(59, 130, 246, 0.1)'
                            @elseif($type === 'heart_rate') 'rgba(168, 85, 247, 0.1)'
                            @else 'rgba(107, 114, 128, 0.1)'
                            @endif,
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: false
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        })();
        @endforeach
        @endif
    </script>
    @endpush
</x-app-layout>
