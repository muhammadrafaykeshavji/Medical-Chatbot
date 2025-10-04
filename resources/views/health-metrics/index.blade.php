@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-blue-900 to-slate-900">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-4xl font-bold text-white mb-2">Health Metrics</h1>
                <p class="text-slate-300 text-lg">Track and monitor your health data over time</p>
            </div>
            <a href="{{ route('health-metrics.create') }}" class="bg-gradient-to-r from-cyan-600 to-blue-600 text-white px-6 py-3 rounded-lg hover:from-cyan-700 hover:to-blue-700 transition-all duration-200 font-medium">
                <i class="fas fa-plus mr-2"></i>Add New Metric
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-500/20 border border-green-500 text-green-300 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-slate-800/50 backdrop-blur-lg rounded-xl p-6 border border-slate-700">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-red-500/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-heartbeat text-red-400 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-slate-400 text-sm">Blood Pressure</p>
                        <p class="text-white text-xl font-semibold">
                            @if(isset($latestMetrics['blood_pressure']))
                                {{ $latestMetrics['blood_pressure']->systolic }}/{{ $latestMetrics['blood_pressure']->diastolic }}
                            @else
                                --/--
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-slate-800/50 backdrop-blur-lg rounded-xl p-6 border border-slate-700">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-500/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-weight text-blue-400 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-slate-400 text-sm">Weight</p>
                        <p class="text-white text-xl font-semibold">
                            @if(isset($latestMetrics['weight']))
                                {{ $latestMetrics['weight']->value }} {{ $latestMetrics['weight']->unit }}
                            @else
                                -- --
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-slate-800/50 backdrop-blur-lg rounded-xl p-6 border border-slate-700">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-500/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-thermometer-half text-green-400 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-slate-400 text-sm">Temperature</p>
                        <p class="text-white text-xl font-semibold">
                            @if(isset($latestMetrics['temperature']))
                                {{ $latestMetrics['temperature']->value }}{{ $latestMetrics['temperature']->unit }}
                            @else
                                -- --
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-slate-800/50 backdrop-blur-lg rounded-xl p-6 border border-slate-700">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-purple-500/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-heart text-purple-400 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-slate-400 text-sm">Heart Rate</p>
                        <p class="text-white text-xl font-semibold">
                            @if(isset($latestMetrics['heart_rate']))
                                {{ $latestMetrics['heart_rate']->value }} {{ $latestMetrics['heart_rate']->unit }}
                            @else
                                -- --
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter and Controls -->
        <div class="bg-slate-800/50 backdrop-blur-lg rounded-xl p-6 border border-slate-700 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Filter by Type</label>
                    <select id="metric-filter" class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                        <option value="">All Metrics</option>
                        <option value="blood_pressure">Blood Pressure</option>
                        <option value="blood_sugar">Blood Sugar</option>
                        <option value="weight">Weight</option>
                        <option value="heart_rate">Heart Rate</option>
                        <option value="temperature">Temperature</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Date Range</label>
                    <select id="date-filter" class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                        <option value="7">Last 7 days</option>
                        <option value="30">Last 30 days</option>
                        <option value="90">Last 3 months</option>
                        <option value="365">Last year</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">View</label>
                    <select id="view-filter" class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                        <option value="list">List View</option>
                        <option value="chart">Chart View</option>
                        <option value="summary">Summary View</option>
                    </select>
                </div>

                <div class="flex items-end">
                    <button id="apply-filter" class="w-full bg-gradient-to-r from-cyan-600 to-blue-600 text-white px-4 py-3 rounded-lg hover:from-cyan-700 hover:to-blue-700 transition-all duration-200 font-medium">
                        <i class="fas fa-filter mr-2"></i>Apply Filter
                    </button>
                </div>
            </div>
        </div>

        <!-- Metrics Display -->
        <div class="bg-slate-800/50 backdrop-blur-lg rounded-xl border border-slate-700 overflow-hidden">
            <div class="p-6">
                <h3 class="text-xl font-semibold text-white mb-6">Recent Health Metrics</h3>
                
                <!-- Dynamic Data Display -->
                @if($healthMetrics->count() > 0)
                    <div class="space-y-4" id="metrics-list">
                        @foreach($healthMetrics as $metric)
                            <div class="bg-slate-900 rounded-lg p-4 border border-slate-700 metric-item" data-type="{{ $metric->metric_type }}" data-date="{{ $metric->recorded_at->format('Y-m-d') }}">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-10 h-10 {{ $metric->metric_type === 'blood_pressure' ? 'bg-red-500/20' : ($metric->metric_type === 'weight' ? 'bg-blue-500/20' : ($metric->metric_type === 'blood_sugar' ? 'bg-purple-500/20' : ($metric->metric_type === 'heart_rate' ? 'bg-green-500/20' : 'bg-yellow-500/20'))) }} rounded-lg flex items-center justify-center">
                                            <i class="fas {{ $metric->metric_type === 'blood_pressure' ? 'fa-heartbeat text-red-400' : ($metric->metric_type === 'weight' ? 'fa-weight text-blue-400' : ($metric->metric_type === 'blood_sugar' ? 'fa-tint text-purple-400' : ($metric->metric_type === 'heart_rate' ? 'fa-heart text-green-400' : 'fa-thermometer-half text-yellow-400'))) }}"></i>
                                        </div>
                                        <div>
                                            <h4 class="text-white font-medium">{{ ucwords(str_replace('_', ' ', $metric->metric_type)) }}</h4>
                                            <p class="text-slate-400 text-sm">{{ $metric->recorded_at->format('M j, Y g:i A') }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        @if($metric->metric_type === 'blood_pressure')
                                            <p class="text-white text-lg font-semibold">{{ $metric->systolic }}/{{ $metric->diastolic }} {{ $metric->unit }}</p>
                                            <p class="text-green-400 text-sm">
                                                @if($metric->systolic <= 120 && $metric->diastolic <= 80)
                                                    Normal
                                                @elseif($metric->systolic <= 139 && $metric->diastolic <= 89)
                                                    Elevated
                                                @else
                                                    High
                                                @endif
                                            </p>
                                        @else
                                            <p class="text-white text-lg font-semibold">{{ $metric->value }} {{ $metric->unit }}</p>
                                            @if($metric->notes)
                                                <p class="text-slate-400 text-sm">{{ Str::limit($metric->notes, 30) }}</p>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                @if($metric->notes && $metric->metric_type !== 'blood_pressure')
                                    <div class="mt-3 pt-3 border-t border-slate-700">
                                        <p class="text-slate-300 text-sm"><strong>Notes:</strong> {{ $metric->notes }}</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($healthMetrics->hasPages())
                        <div class="mt-6">
                            {{ $healthMetrics->links() }}
                        </div>
                    @endif
                @else
                    <!-- Empty State for when no data exists -->
                    <div class="text-center py-12" id="empty-state">
                        <div class="w-24 h-24 bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-chart-line text-4xl text-slate-400"></i>
                        </div>
                        <h3 class="text-2xl font-semibold text-white mb-4">No Health Metrics Yet</h3>
                        <p class="text-slate-400 mb-8 max-w-md mx-auto">Start tracking your health by adding your first metric. Monitor blood pressure, weight, blood sugar, and more.</p>
                        <a href="{{ route('health-metrics.create') }}" class="bg-gradient-to-r from-cyan-600 to-blue-600 text-white px-8 py-3 rounded-lg hover:from-cyan-700 hover:to-blue-700 transition-all duration-200 font-medium">
                            <i class="fas fa-plus mr-2"></i>Add Your First Metric
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const metricFilter = document.getElementById('metric-filter');
    const dateFilter = document.getElementById('date-filter');
    const viewFilter = document.getElementById('view-filter');
    const applyFilterBtn = document.getElementById('apply-filter');
    const metricsList = document.getElementById('metrics-list');
    const metricItems = document.querySelectorAll('.metric-item');

    // Apply filters function
    function applyFilters() {
        const selectedType = metricFilter.value;
        const selectedDays = parseInt(dateFilter.value);
        const currentDate = new Date();
        const cutoffDate = new Date(currentDate.getTime() - (selectedDays * 24 * 60 * 60 * 1000));
        
        let visibleCount = 0;

        metricItems.forEach(item => {
            const itemType = item.dataset.type;
            const itemDate = new Date(item.dataset.date);
            
            let showItem = true;

            // Filter by type
            if (selectedType && itemType !== selectedType) {
                showItem = false;
            }

            // Filter by date
            if (itemDate < cutoffDate) {
                showItem = false;
            }

            // Show/hide item
            if (showItem) {
                item.style.display = 'block';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });

        // Show empty state if no items visible
        const emptyState = document.getElementById('empty-state');
        if (visibleCount === 0 && metricItems.length > 0) {
            if (!emptyState) {
                // Create temporary empty state for filtered results
                const tempEmpty = document.createElement('div');
                tempEmpty.className = 'text-center py-12';
                tempEmpty.id = 'temp-empty-state';
                tempEmpty.innerHTML = `
                    <div class="w-24 h-24 bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-filter text-4xl text-slate-400"></i>
                    </div>
                    <h3 class="text-2xl font-semibold text-white mb-4">No Metrics Match Your Filter</h3>
                    <p class="text-slate-400 mb-8 max-w-md mx-auto">Try adjusting your filter criteria to see more results.</p>
                    <button onclick="clearFilters()" class="bg-gradient-to-r from-cyan-600 to-blue-600 text-white px-8 py-3 rounded-lg hover:from-cyan-700 hover:to-blue-700 transition-all duration-200 font-medium">
                        <i class="fas fa-times mr-2"></i>Clear Filters
                    </button>
                `;
                metricsList.parentNode.appendChild(tempEmpty);
            }
            metricsList.style.display = 'none';
        } else {
            const tempEmpty = document.getElementById('temp-empty-state');
            if (tempEmpty) {
                tempEmpty.remove();
            }
            if (metricsList) {
                metricsList.style.display = 'block';
            }
        }
    }

    // Clear filters function
    window.clearFilters = function() {
        metricFilter.value = '';
        dateFilter.value = '7';
        viewFilter.value = 'list';
        applyFilters();
    };

    // Event listeners
    applyFilterBtn.addEventListener('click', applyFilters);
    
    // Auto-apply filters when dropdowns change
    metricFilter.addEventListener('change', applyFilters);
    dateFilter.addEventListener('change', applyFilters);

    // View filter functionality (placeholder for future chart/summary views)
    viewFilter.addEventListener('change', function() {
        const selectedView = this.value;
        // For now, just apply filters. In future, this could switch between list/chart/summary views
        applyFilters();
        
        if (selectedView === 'chart') {
            // Placeholder for chart view
            console.log('Chart view selected - feature coming soon!');
        } else if (selectedView === 'summary') {
            // Placeholder for summary view
            console.log('Summary view selected - feature coming soon!');
        }
    });

    // Initial filter application
    applyFilters();
});
</script>
@endsection
