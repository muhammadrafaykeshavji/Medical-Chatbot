@extends('layouts.ai-theme')

@section('title', 'AI Health Dashboard')

@push('styles')
<style>
    /* Custom styles specific to the dashboard */
    .stat-card {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.03) 0%, rgba(255, 255, 255, 0.02) 100%);
        border: 1px solid rgba(59, 130, 246, 0.1);
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.2);
        border-color: rgba(59, 130, 246, 0.3);
    }
    
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(59, 130, 246, 0.2) 100%);
    }
    
    .stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        background: linear-gradient(90deg, #3b82f6, #60a5fa);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        line-height: 1.2;
    }
    
    .stat-label {
        color: #94a3b8;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }
    
    .trend-up {
        color: #10b981;
    }
    
    .trend-down {
        color: #ef4444;
    }
    
    .section-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #f8fafc;
        margin-bottom: 1.5rem;
        position: relative;
        padding-left: 1rem;
    }
    
    .section-title::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 4px;
        height: 1.25rem;
        background: linear-gradient(to bottom, #3b82f6, #60a5fa);
        border-radius: 2px;
    }
    
    .activity-item {
        padding: 1rem 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        transition: all 0.2s ease;
    }
    
    .activity-item:hover {
        background: rgba(255, 255, 255, 0.02);
        transform: translateX(5px);
    }
    
    .activity-time {
        font-size: 0.75rem;
        color: #64748b;
    }
    
    .activity-badge {
        font-size: 0.7rem;
        font-weight: 500;
        padding: 0.25rem 0.5rem;
        border-radius: 9999px;
    }
    
    .badge-primary {
        background: rgba(59, 130, 246, 0.15);
        color: #60a5fa;
    }
    
    .badge-success {
        background: rgba(16, 185, 129, 0.15);
        color: #34d399;
    }
    
    .badge-warning {
        background: rgba(245, 158, 11, 0.15);
        color: #fbbf24;
    }
    
    .badge-danger {
        background: rgba(239, 68, 68, 0.15);
        color: #f87171;
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Welcome Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-white mb-1">Welcome back, <span class="gradient-text">{{ Auth::user()->name ?? 'User' }}</span>!</h1>
            <p class="text-gray-400">Here's what's happening with your health today</p>
        </div>
        <div class="mt-4 md:mt-0">
            <div class="relative">
                <input type="text" placeholder="Search reports, patients..." class="w-full md:w-64 pl-10 pr-4 py-2.5 bg-gray-800/50 border border-gray-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm text-white placeholder-gray-500">
                <i class="fas fa-search absolute left-3 top-3 text-gray-500"></i>
            </div>
        </div>
    </div>
    
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Heart Rate Card -->
        <div class="card stat-card p-6">
            <div class="flex items-start justify-between">
                <div>
                    <div class="stat-icon text-blue-400 mb-4">
                        <i class="fas fa-heartbeat"></i>
                    </div>
                    <div class="stat-value">72 <span class="text-sm text-gray-400">bpm</span></div>
                    <div class="stat-label">Heart Rate</div>
                </div>
                <div class="text-right">
                    <div class="text-xs font-medium text-green-500 flex items-center justify-end">
                        <i class="fas fa-arrow-up mr-1"></i> 2.5%
                    </div>
                    <div class="text-xs text-gray-500 mt-1">vs last week</div>
                </div>
            </div>
        </div>
        
        <!-- Blood Pressure Card -->
        <div class="card stat-card p-6">
            <div class="flex items-start justify-between">
                <div>
                    <div class="stat-icon text-purple-400 mb-4">
                        <i class="fas fa-tint"></i>
                    </div>
                    <div class="stat-value">120/80 <span class="text-sm text-gray-400">mmHg</span></div>
                    <div class="stat-label">Blood Pressure</div>
                </div>
                <div class="text-right">
                    <div class="text-xs font-medium text-green-500 flex items-center justify-end">
                        <i class="fas fa-check-circle mr-1"></i> Normal
                    </div>
                    <div class="text-xs text-gray-500 mt-1">Last checked: 2h ago</div>
                </div>
            </div>
        </div>
        
        <!-- Steps Card -->
        <div class="card stat-card p-6">
            <div class="flex items-start justify-between">
                <div>
                    <div class="stat-icon text-green-400 mb-4">
                        <i class="fas fa-walking"></i>
                    </div>
                    <div class="stat-value">8,542 <span class="text-sm text-gray-400">steps</span></div>
                    <div class="stat-label">Daily Steps</div>
                </div>
                <div class="text-right">
                    <div class="text-xs font-medium text-green-500 flex items-center justify-end">
                        <i class="fas fa-arrow-up mr-1"></i> 15%
                    </div>
                    <div class="text-xs text-gray-500 mt-1">vs yesterday</div>
                </div>
            </div>
        </div>
        
        <!-- Sleep Card -->
        <div class="card stat-card p-6">
            <div class="flex items-start justify-between">
                <div>
                    <div class="stat-icon text-yellow-400 mb-4">
                        <i class="fas fa-moon"></i>
                    </div>
                    <div class="stat-value">7.2 <span class="text-sm text-gray-400">hours</span></div>
                    <div class="stat-label">Sleep Duration</div>
                </div>
                <div class="text-right">
                    <div class="text-xs font-medium text-red-500 flex items-center justify-end">
                        <i class="fas fa-arrow-down mr-1"></i> 0.8h
                    </div>
                    <div class="text-xs text-gray-500 mt-1">less than average</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Health Chart -->
        <div class="lg:col-span-2">
            <div class="card p-6 h-full">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="section-title">Health Overview</h2>
                    <div class="flex space-x-2">
                        <button class="px-3 py-1 text-xs font-medium rounded-full bg-blue-500/10 text-blue-400">Week</button>
                        <button class="px-3 py-1 text-xs font-medium rounded-full text-gray-400 hover:bg-gray-800/50">Month</button>
                        <button class="px-3 py-1 text-xs font-medium rounded-full text-gray-400 hover:bg-gray-800/50">Year</button>
                    </div>
                </div>
                <div class="h-80">
                    <canvas id="healthChart"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Recent Activity -->
        <div>
            <div class="card p-6 h-full">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="section-title">Recent Activity</h2>
                    <button class="text-xs text-blue-400 hover:text-blue-300">View All</button>
                </div>
                
                <div class="space-y-4">
                    <!-- Activity Item -->
                    <div class="activity-item">
                        <div class="flex justify-between items-start">
                            <div class="flex items-start space-x-3">
                                <div class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-400">
                                    <i class="fas fa-heartbeat"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-white">Heart rate alert</p>
                                    <p class="text-xs text-gray-400 mt-0.5">Your heart rate was elevated during workout</p>
                                </div>
                            </div>
                            <span class="text-xs text-gray-500">10 min ago</span>
                        </div>
                        <div class="mt-2 flex items-center justify-between">
                            <span class="activity-badge badge-warning">Warning</span>
                            <button class="text-xs text-blue-400 hover:text-blue-300">Details</button>
                        </div>
                    </div>
                    
                    <!-- Activity Item -->
                    <div class="activity-item">
                        <div class="flex justify-between items-start">
                            <div class="flex items-start space-x-3">
                                <div class="w-10 h-10 rounded-xl bg-green-500/10 flex items-center justify-center text-green-400">
                                    <i class="fas fa-walking"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-white">Step goal achieved</p>
                                    <p class="text-xs text-gray-400 mt-0.5">You've reached your daily step goal</p>
                                </div>
                            </div>
                            <span class="text-xs text-gray-500">2h ago</span>
                        </div>
                        <div class="mt-2 flex items-center justify-between">
                            <span class="activity-badge badge-success">Success</span>
                            <button class="text-xs text-blue-400 hover:text-blue-300">View</button>
                        </div>
                    </div>
                    
                    <!-- Activity Item -->
                    <div class="activity-item">
                        <div class="flex justify-between items-start">
                            <div class="flex items-start space-x-3">
                                <div class="w-10 h-10 rounded-xl bg-purple-500/10 flex items-center justify-center text-purple-400">
                                    <i class="fas fa-moon"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-white">Sleep analysis</p>
                                    <p class="text-xs text-gray-400 mt-0.5">Your sleep quality was 85% (Good)</p>
                                </div>
                            </div>
                            <span class="text-xs text-gray-500">8h ago</span>
                        </div>
                        <div class="mt-2 flex items-center justify-between">
                            <span class="activity-badge badge-primary">Info</span>
                            <button class="text-xs text-blue-400 hover:text-blue-300">Analyze</button>
                        </div>
                    </div>
                    
                    <!-- Activity Item -->
                    <div class="activity-item">
                        <div class="flex justify-between items-start">
                            <div class="flex items-start space-x-3">
                                <div class="w-10 h-10 rounded-xl bg-yellow-500/10 flex items-center justify-center text-yellow-400">
                                    <i class="fas fa-dumbbell"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-white">Workout completed</p>
                                    <p class="text-xs text-gray-400 mt-0.5">Morning run - 5.2 km in 28:15</p>
                                </div>
                            </div>
                            <span class="text-xs text-gray-500">Yesterday</span>
                        </div>
                        <div class="mt-2 flex items-center justify-between">
                            <span class="activity-badge badge-success">Completed</span>
                            <button class="text-xs text-blue-400 hover:text-blue-300">Details</button>
                        </div>
                    </div>
                    
                    <!-- Activity Item -->
                    <div class="activity-item pb-0 border-b-0">
                        <div class="flex justify-between items-start">
                            <div class="flex items-start space-x-3">
                                <div class="w-10 h-10 rounded-xl bg-red-500/10 flex items-center justify-center text-red-400">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-white">Irregular heartbeat</p>
                                    <p class="text-xs text-gray-400 mt-0.5">Detected irregular pattern during rest</p>
                                </div>
                            </div>
                            <span class="text-xs text-gray-500">2 days ago</span>
                        </div>
                        <div class="mt-2 flex items-center justify-between">
                            <span class="activity-badge badge-danger">Critical</span>
                            <button class="text-xs text-blue-400 hover:text-blue-300">Report</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bottom Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Medications -->
        <div class="lg:col-span-1">
            <div class="card p-6 h-full">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="section-title">Medications</h2>
                    <button class="text-xs text-blue-400 hover:text-blue-300">+ Add New</button>
                </div>
                
                <div class="space-y-4">
                    <!-- Medication Item -->
                    <div class="flex items-center p-3 bg-gray-800/30 rounded-xl">
                        <div class="w-10 h-10 rounded-lg bg-blue-500/10 flex items-center justify-center text-blue-400 mr-3">
                            <i class="fas fa-pills"></i>
                        </div>
                        <div class="flex-grow">
                            <h4 class="text-sm font-medium text-white">Metformin</h4>
                            <p class="text-xs text-gray-400">500mg · Twice daily</p>
                        </div>
                        <div class="text-right">
                            <div class="text-xs font-medium text-green-400">On time</div>
                            <div class="text-xs text-gray-500">Next: 8:00 PM</div>
                        </div>
                    </div>
                    
                    <!-- Medication Item -->
                    <div class="flex items-center p-3 bg-gray-800/30 rounded-xl">
                        <div class="w-10 h-10 rounded-lg bg-purple-500/10 flex items-center justify-center text-purple-400 mr-3">
                            <i class="fas fa-pills"></i>
                        </div>
                        <div class="flex-grow">
                            <h4 class="text-sm font-medium text-white">Lisinopril</h4>
                            <p class="text-xs text-gray-400">10mg · Once daily</p>
                        </div>
                        <div class="text-right">
                            <div class="text-xs font-medium text-yellow-400">Due soon</div>
                            <div class="text-xs text-gray-500">Next: 9:00 PM</div>
                        </div>
                    </div>
                    
                    <!-- Medication Item -->
                    <div class="flex items-center p-3 bg-gray-800/30 rounded-xl">
                        <div class="w-10 h-10 rounded-lg bg-green-500/10 flex items-center justify-center text-green-400 mr-3">
                            <i class="fas fa-pills"></i>
                        </div>
                        <div class="flex-grow">
                            <h4 class="text-sm font-medium text-white">Atorvastatin</h4>
                            <p class="text-xs text-gray-400">20mg · At bedtime</p>
                        </div>
                        <div class="text-right">
                            <div class="text-xs font-medium text-gray-400">Taken</div>
                            <div class="text-xs text-gray-500">Today, 10:00 PM</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Health Tips -->
        <div class="lg:col-span-2">
            <div class="card p-6 h-full">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="section-title">Health Tips</h2>
                    <button class="text-xs text-blue-400 hover:text-blue-300">View All</button>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Tip 1 -->
                    <div class="bg-gradient-to-br from-blue-900/30 to-blue-800/10 rounded-xl p-4 border border-blue-800/30">
                        <div class="flex items-center mb-3">
                            <div class="w-8 h-8 rounded-lg bg-blue-500/10 flex items-center justify-center text-blue-400 mr-2">
                                <i class="fas fa-moon"></i>
                            </div>
                            <h3 class="text-sm font-medium text-white">Sleep Better</h3>
                        </div>
                        <p class="text-xs text-gray-300 mb-3">Aim for 7-9 hours of quality sleep each night to improve recovery and cognitive function.</p>
                        <button class="text-xs text-blue-400 hover:text-blue-300">Read More</button>
                    </div>
                    
                    <!-- Tip 2 -->
                    <div class="bg-gradient-to-br from-green-900/30 to-green-800/10 rounded-xl p-4 border border-green-800/30">
                        <div class="flex items-center mb-3">
                            <div class="w-8 h-8 rounded-lg bg-green-500/10 flex items-center justify-center text-green-400 mr-2">
                                <i class="fas fa-apple-alt"></i>
                            </div>
                            <h3 class="text-sm font-medium text-white">Eat Healthy</h3>
                        </div>
                        <p class="text-xs text-gray-300 mb-3">Incorporate more fruits, vegetables, and whole grains into your diet for better nutrition.</p>
                        <button class="text-xs text-green-400 hover:text-green-300">Read More</button>
                    </div>
                    
                    <!-- Tip 3 -->
                    <div class="bg-gradient-to-br from-purple-900/30 to-purple-800/10 rounded-xl p-4 border border-purple-800/30">
                        <div class="flex items-center mb-3">
                            <div class="w-8 h-8 rounded-lg bg-purple-500/10 flex items-center justify-center text-purple-400 mr-2">
                                <i class="fas fa-water"></i>
                            </div>
                            <h3 class="text-sm font-medium text-white">Stay Hydrated</h3>
                        </div>
                        <p class="text-xs text-gray-300 mb-3">Drink at least 8 glasses of water daily to maintain optimal body function.</p>
                        <button class="text-xs text-purple-400 hover:text-purple-300">Read More</button>
                    </div>
                    
                    <!-- Tip 4 -->
                    <div class="bg-gradient-to-br from-yellow-900/30 to-yellow-800/10 rounded-xl p-4 border border-yellow-800/30">
                        <div class="flex items-center mb-3">
                            <div class="w-8 h-8 rounded-lg bg-yellow-500/10 flex items-center justify-center text-yellow-400 mr-2">
                                <i class="fas fa-running"></i>
                            </div>
                            <h3 class="text-sm font-medium text-white">Stay Active</h3>
                        </div>
                        <p class="text-xs text-gray-300 mb-3">Aim for at least 30 minutes of moderate exercise most days of the week.</p>
                        <button class="text-xs text-yellow-400 hover:text-yellow-300">Read More</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Initialize Health Chart
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('healthChart').getContext('2d');
        
        // Gradient for chart
        const gradient = ctx.createLinearGradient(0, 0, 0, 350);
        gradient.addColorStop(0, 'rgba(59, 130, 246, 0.15)');
        gradient.addColorStop(1, 'rgba(59, 130, 246, 0)');
        
        // Chart data
        const labels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        const data = {
            labels: labels,
            datasets: [
                {
                    label: 'Heart Rate',
                    data: [72, 75, 70, 68, 73, 71, 69],
                    borderColor: '#3b82f6',
                    backgroundColor: gradient,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#3b82f6',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    borderWidth: 2
                },
                {
                    label: 'Steps (in thousands)',
                    data: [4.2, 5.1, 6.3, 5.8, 7.2, 8.5, 9.2],
                    borderColor: '#10b981',
                    borderDash: [5, 5],
                    backgroundColor: 'transparent',
                    tension: 0.4,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#10b981',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    borderWidth: 2,
                    yAxisID: 'y1'
                }
            ]
        };
        
        // Chart config
        const config = {
            type: 'line',
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            color: '#9ca3af',
                            font: {
                                family: 'Inter',
                                size: 12
                            },
                            padding: 20,
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(30, 41, 59, 0.95)',
                        titleColor: '#f8fafc',
                        bodyColor: '#e2e8f0',
                        borderColor: 'rgba(100, 116, 139, 0.5)',
                        borderWidth: 1,
                        padding: 12,
                        boxShadow: '0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)',
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label === 'Heart Rate') {
                                    label += ': ' + context.parsed.y + ' bpm';
                                } else if (label === 'Steps (in thousands)') {
                                    label += ': ' + (context.parsed.y * 1000).toLocaleString() + ' steps';
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            color: '#64748b',
                            font: {
                                family: 'Inter',
                                size: 12
                            }
                        }
                    },
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        grid: {
                            color: 'rgba(100, 116, 139, 0.1)',
                            drawBorder: false
                        },
                        ticks: {
                            color: '#64748b',
                            font: {
                                family: 'Inter',
                                size: 12
                            },
                            callback: function(value) {
                                return value + ' bpm';
                            }
                        },
                        min: 60,
                        max: 80
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        grid: {
                            drawOnChartArea: false,
                            drawBorder: false
                        },
                        ticks: {
                            color: '#64748b',
                            font: {
                                family: 'Inter',
                                size: 12
                            },
                            callback: function(value) {
                                return value + 'k';
                            }
                        },
                        min: 0,
                        max: 10
                    }
                }
            }
        };
        
        // Create chart
        new Chart(ctx, config);
        
        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
    
    // Toggle dark/light mode (example functionality)
    function toggleDarkMode() {
        const html = document.documentElement;
        html.classList.toggle('dark');
        
        // Save preference to localStorage
        if (html.classList.contains('dark')) {
            localStorage.theme = 'dark';
        } else {
            localStorage.theme = 'light';
        }
    }
    
    // Check for saved user preference
    if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
</script>
@endpush
@endsection
