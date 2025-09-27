<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Health Metrics') }}
            </h2>
            <a href="{{ route('health-metrics.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                📊 Add New Metric
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Filter and Summary -->
            <div class="mb-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <!-- Filter by Type -->
                        <div class="md:col-span-2">
                            <label for="metric-filter" class="block text-sm font-medium text-gray-700 mb-2">Filter by Type:</label>
                            <select id="metric-filter" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">All Metrics</option>
                                <option value="blood_pressure">Blood Pressure</option>
                                <option value="blood_sugar">Blood Sugar</option>
                                <option value="weight">Weight</option>
                                <option value="heart_rate">Heart Rate</option>
                                <option value="temperature">Temperature</option>
                            </select>
                        </div>

                        <!-- Quick Stats -->
                        <div class="md:col-span-3 grid grid-cols-3 gap-4">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-blue-600">{{ $healthMetrics->count() }}</div>
                                <div class="text-sm text-gray-500">Total Records</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-green-600">
                                    {{ $healthMetrics->where('recorded_at', '>=', now()->subDays(7))->count() }}
                                </div>
                                <div class="text-sm text-gray-500">This Week</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-purple-600">
                                    {{ $healthMetrics->groupBy('metric_type')->count() }}
                                </div>
                                <div class="text-sm text-gray-500">Metric Types</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($healthMetrics->count() > 0)
                <!-- Metrics Table -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Type
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Value
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Unit
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Recorded At
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Notes
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200" id="metrics-table-body">
                                    @foreach($healthMetrics as $metric)
                                    <tr class="metric-row" data-type="{{ $metric->metric_type }}">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <span class="w-3 h-3 rounded-full mr-2 
                                                    @if($metric->metric_type === 'blood_pressure') bg-red-400
                                                    @elseif($metric->metric_type === 'blood_sugar') bg-green-400
                                                    @elseif($metric->metric_type === 'weight') bg-blue-400
                                                    @elseif($metric->metric_type === 'heart_rate') bg-purple-400
                                                    @else bg-gray-400
                                                    @endif"></span>
                                                <span class="text-sm font-medium text-gray-900">
                                                    {{ ucfirst(str_replace('_', ' ', $metric->metric_type)) }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            @if($metric->metric_type === 'blood_pressure')
                                                <span class="font-semibold">{{ $metric->systolic }}/{{ $metric->diastolic }}</span>
                                            @else
                                                <span class="font-semibold">{{ $metric->value }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $metric->unit }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $metric->recorded_at->format('M d, Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            {{ $metric->notes ? Str::limit($metric->notes, 50) : '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('health-metrics.show', $metric) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                                <a href="{{ route('health-metrics.edit', $metric) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                                <form action="{{ route('health-metrics.destroy', $metric) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this metric?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $healthMetrics->links() }}
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Blood Pressure Chart -->
                    @php
                        $bloodPressureData = $healthMetrics->where('metric_type', 'blood_pressure')->take(10);
                    @endphp
                    @if($bloodPressureData->count() > 0)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Blood Pressure Trend</h3>
                            <div class="h-64">
                                <canvas id="blood-pressure-chart"></canvas>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Weight Chart -->
                    @php
                        $weightData = $healthMetrics->where('metric_type', 'weight')->take(10);
                    @endphp
                    @if($weightData->count() > 0)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Weight Trend</h3>
                            <div class="h-64">
                                <canvas id="weight-chart"></canvas>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl">📊</span>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No health metrics recorded yet</h3>
                        <p class="text-gray-500 mb-4">Start tracking your health by recording your first metric.</p>
                        <a href="{{ route('health-metrics.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Add First Metric
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        // Filter functionality
        document.getElementById('metric-filter').addEventListener('change', function() {
            const filterValue = this.value;
            const rows = document.querySelectorAll('.metric-row');
            
            rows.forEach(row => {
                if (filterValue === '' || row.dataset.type === filterValue) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Initialize charts
        @if(isset($bloodPressureData) && $bloodPressureData->count() > 0)
        (function() {
            const ctx = document.getElementById('blood-pressure-chart').getContext('2d');
            const data = @json($bloodPressureData->values()->map(function($metric) {
                return [
                    'date' => $metric->recorded_at->format('M d'),
                    'systolic' => $metric->systolic,
                    'diastolic' => $metric->diastolic
                ];
            }));
            
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.map(item => item.date),
                    datasets: [{
                        label: 'Systolic',
                        data: data.map(item => item.systolic),
                        borderColor: 'rgb(239, 68, 68)',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        tension: 0.4
                    }, {
                        label: 'Diastolic',
                        data: data.map(item => item.diastolic),
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: false,
                            min: 60,
                            max: 200
                        }
                    }
                }
            });
        })();
        @endif

        @if(isset($weightData) && $weightData->count() > 0)
        (function() {
            const ctx = document.getElementById('weight-chart').getContext('2d');
            const data = @json($weightData->values()->map(function($metric) {
                return [
                    'date' => $metric->recorded_at->format('M d'),
                    'value' => $metric->value
                ];
            }));
            
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.map(item => item.date),
                    datasets: [{
                        label: 'Weight',
                        data: data.map(item => item.value),
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
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
        @endif
    </script>
    @endpush
</x-app-layout>
