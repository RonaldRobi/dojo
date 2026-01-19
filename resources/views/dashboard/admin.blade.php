<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Global Dashboard</h2>
                <p class="text-sm text-gray-500 mt-0.5">Overview of entire system and branches</p>
            </div>
            <div class="flex items-center gap-3">
                <!-- Last Synced Info -->
                <div class="hidden md:flex items-center text-sm text-gray-500">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span id="last-synced">Last synced: {{ now()->format('H:i:s') }}</span>
                </div>
                <!-- Sync Button -->
                <form action="{{ route('admin.dashboard.sync') }}" method="POST" id="syncForm">
                    @csrf
                    <button type="submit" id="syncButton" class="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg font-semibold hover:from-blue-700 hover:to-purple-700 transition-all shadow-md hover:shadow-lg transform hover:scale-105">
                        <svg class="w-5 h-5" id="syncIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        <span id="syncText">Sync Data</span>
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Date Time Display -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2 text-sm text-gray-600">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span class="font-medium">{{ now()->format('l, d F Y') }}</span>
                </div>
                <div class="flex items-center gap-2 text-sm text-gray-600">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-medium" id="current-time">{{ now()->format('H:i:s') }}</span>
                </div>
            </div>
        </div>

        @push('scripts')
        <script>
            // Update time every second
            function updateTime() {
                const now = new Date();
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                const seconds = String(now.getSeconds()).padStart(2, '0');
                const timeElement = document.getElementById('current-time');
                if (timeElement) {
                    timeElement.textContent = hours + ':' + minutes + ':' + seconds;
                }
            }
            setInterval(updateTime, 1000);

            // Sync Data Handler
            document.getElementById('syncForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                const syncButton = document.getElementById('syncButton');
                const syncIcon = document.getElementById('syncIcon');
                const syncText = document.getElementById('syncText');
                const lastSynced = document.getElementById('last-synced');
                
                // Disable button and show loading
                syncButton.disabled = true;
                syncButton.classList.add('opacity-75', 'cursor-not-allowed');
                syncIcon.classList.add('animate-spin');
                syncText.textContent = 'Syncing...';
                
                // Submit form via fetch
                fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success state
                        syncText.textContent = 'Synced!';
                        syncIcon.classList.remove('animate-spin');
                        
                        // Update last synced time
                        const now = new Date();
                        const timeStr = String(now.getHours()).padStart(2, '0') + ':' + 
                                       String(now.getMinutes()).padStart(2, '0') + ':' + 
                                       String(now.getSeconds()).padStart(2, '0');
                        lastSynced.textContent = 'Last synced: ' + timeStr;
                        
                        // Reload page after short delay
                        setTimeout(() => {
                            window.location.reload();
                        }, 500);
                    }
                })
                .catch(error => {
                    console.error('Sync error:', error);
                    syncText.textContent = 'Sync Failed';
                    syncIcon.classList.remove('animate-spin');
                    
                    // Reset button after delay
                    setTimeout(() => {
                        syncButton.disabled = false;
                        syncButton.classList.remove('opacity-75', 'cursor-not-allowed');
                        syncText.textContent = 'Sync Data';
                    }, 2000);
                });
            });
        </script>
        @endpush

        <!-- Welcome Banner -->
        <div class="relative overflow-hidden bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-700 rounded-2xl shadow-lg">
            <div class="absolute inset-0 bg-black opacity-10"></div>
            <div class="relative px-8 py-6 flex items-center justify-between">
                <div class="flex-1">
                    <h3 class="text-2xl font-bold text-white mb-2">Welcome to Global Dashboard</h3>
                    <p class="text-blue-100 text-sm max-w-2xl">Manage all dojo branches, monitor activities, and analyze performance in real-time from one centralized place.</p>
                    <div class="mt-4 flex gap-3">
                        <a href="{{ route('admin.dojos.index') }}" class="px-4 py-2 bg-white text-purple-600 rounded-lg font-semibold hover:bg-blue-50 transition-colors text-sm">
                            Manage Branches
                        </a>
                        <a href="{{ route('admin.reports.revenue') }}" class="px-4 py-2 bg-white bg-opacity-20 text-white rounded-lg font-semibold hover:bg-opacity-30 transition-colors text-sm">
                            View Reports
                        </a>
                    </div>
                </div>
                <div class="hidden lg:block ml-8">
                    <svg class="w-32 h-32 text-white opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- KPI Cards - 4 Columns -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Revenue Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="flex items-center text-green-500 text-sm font-semibold">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                        +15.3%
                    </div>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Total Revenue</p>
                    <p class="text-3xl font-bold text-gray-900">RM {{ number_format($stats['total_revenue'], 0) }}</p>
                    <p class="text-xs text-gray-500 mt-2">RM {{ number_format($stats['monthly_revenue'], 0) }} this month</p>
                    <!-- Mini Chart -->
                    <div class="mt-4 h-8">
                        <canvas class="mini-chart" data-color="#3b82f6" data-values="[40, 60, 80, 65, 90, 75]"></canvas>
                    </div>
                </div>
            </div>

            <!-- Total Members Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-gradient-to-br from-green-500 to-green-600 rounded-xl">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div class="flex items-center text-green-500 text-sm font-semibold">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                        +12.5%
                    </div>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Total Students</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_members']) }}</p>
                    <p class="text-xs text-gray-500 mt-2">{{ number_format($stats['active_members']) }} active</p>
                    <!-- Mini Chart -->
                    <div class="mt-4 h-8">
                        <canvas class="mini-chart" data-color="#10b981" data-values="[50, 70, 55, 85, 75, 90]"></canvas>
                    </div>
                </div>
            </div>

            <!-- Total Dojos Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <div class="flex items-center text-green-500 text-sm font-semibold">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                        +{{ round(($stats['active_dojos'] / max($stats['total_dojos'], 1)) * 100, 1) }}%
                    </div>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Total Branches</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_dojos']) }}</p>
                    <p class="text-xs text-gray-500 mt-2">{{ $stats['active_dojos'] }} active</p>
                    <!-- Mini Chart -->
                    <div class="mt-4 h-8">
                        <canvas class="mini-chart" data-color="#9333ea" data-values="[45, 65, 50, 80, 70, 85]"></canvas>
                    </div>
                </div>
            </div>

            <!-- Total Classes Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <div class="flex items-center text-green-500 text-sm font-semibold">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                        +{{ round(($stats['active_classes'] / max($stats['total_classes'], 1)) * 100, 1) }}%
                    </div>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Total Classes</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_classes']) }}</p>
                    <p class="text-xs text-gray-500 mt-2">{{ $stats['active_classes'] }} active</p>
                    <!-- Mini Chart -->
                    <div class="mt-4 h-8">
                        <canvas class="mini-chart" data-color="#f97316" data-values="[55, 75, 60, 90, 80, 95]"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts and Analytics Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Revenue Chart Section -->
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Revenue Overview</h3>
                        <p class="text-sm text-gray-500 mt-1">Overall system revenue</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-2xl font-bold text-gray-900">RM {{ number_format($stats['total_revenue'], 0) }}</span>
                        <span class="px-2 py-1 bg-green-100 text-green-700 rounded-lg text-xs font-semibold">+15.3%</span>
                    </div>
                </div>
                
                <!-- Revenue Chart -->
                <div class="h-64">
                    <canvas id="revenueChart"></canvas>
                </div>

                <!-- Stats Grid Below Chart -->
                <div class="grid grid-cols-3 gap-4 mt-6">
                    <div class="bg-blue-50 rounded-lg p-4 border border-blue-100">
                        <p class="text-xs font-medium text-blue-700 mb-1">This Month</p>
                        <p class="text-xl font-bold text-blue-900">RM {{ number_format($stats['monthly_revenue'], 0) }}</p>
                    </div>
                    <div class="bg-purple-50 rounded-lg p-4 border border-purple-100">
                        <p class="text-xs font-medium text-purple-700 mb-1">Pending</p>
                        <p class="text-xl font-bold text-purple-900">{{ $stats['pending_invoices'] }}</p>
                    </div>
                    <div class="bg-red-50 rounded-lg p-4 border border-red-100">
                        <p class="text-xs font-medium text-red-700 mb-1">Overdue</p>
                        <p class="text-xl font-bold text-red-900">{{ $stats['overdue_invoices'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions & Stats -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                    <div class="space-y-2">
                        <a href="{{ route('admin.users.create') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 rounded-lg transition-colors border border-gray-100">
                            <div class="p-2 bg-purple-100 rounded-lg">
                                <svg class="h-4 w-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </div>
                            <span>Add New User</span>
                        </a>
                        <a href="{{ route('admin.dojos.create') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 rounded-lg transition-colors border border-gray-100">
                            <div class="p-2 bg-blue-100 rounded-lg">
                                <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <span>Add New Dojo</span>
                        </a>
                        <a href="{{ route('admin.audit-logs.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 rounded-lg transition-colors border border-gray-100">
                            <div class="p-2 bg-gray-100 rounded-lg">
                                <svg class="h-4 w-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <span>View Audit Logs</span>
                        </a>
                        <a href="{{ route('admin.reports.revenue') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 rounded-lg transition-colors border border-gray-100">
                            <div class="p-2 bg-green-100 rounded-lg">
                                <svg class="h-4 w-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <span>View Reports</span>
                        </a>
                    </div>
                </div>

                <!-- Additional Stats -->
                <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
                    <h3 class="text-lg font-semibold mb-4">System Overview</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-indigo-100">Total Users</span>
                            <span class="text-2xl font-bold">{{ number_format($stats['total_users']) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-indigo-100">Active Users</span>
                            <span class="text-2xl font-bold">{{ number_format($stats['active_users']) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-indigo-100">Total Instructors</span>
                            <span class="text-2xl font-bold">{{ number_format($stats['total_instructors']) }}</span>
                        </div>
                        <div class="flex items-center justify-between border-t border-indigo-400 pt-4 mt-4">
                            <span class="text-indigo-100">Upcoming Events</span>
                            <span class="text-2xl font-bold">{{ $upcomingEvents->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Section: Recent Activity & Lists -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Recent Activity -->
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Activity</h3>
                    <a href="{{ route('admin.audit-logs.index') }}" class="text-sm text-purple-600 hover:text-purple-700 font-medium">View All →</a>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($recentAuditLogs as $log)
                        <div class="px-6 py-4 hover:bg-gray-50 transition-colors">
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-purple-500 to-blue-600 flex items-center justify-center text-white text-sm font-semibold">
                                        {{ strtoupper(substr(optional($log->user)->name ?? 'S', 0, 1)) }}
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-2 mb-1">
                                        <p class="text-sm text-gray-900">
                                            <span class="font-semibold">{{ optional($log->user)->name ?? 'System' }}</span>
                                            <span class="text-gray-500 mx-1">•</span>
                                            <span class="capitalize text-gray-700">{{ $log->action }}</span>
                                        </p>
                                        <span class="text-xs text-gray-400 whitespace-nowrap">{{ $log->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-xs text-gray-500 truncate">
                                        {{ \Illuminate\Support\Str::afterLast($log->model, '\\') }} #{{ $log->model_id }}
                                        @if($log->dojo)
                                            <span class="text-gray-400">•</span> {{ $log->dojo->name }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="text-sm text-gray-500">No recent activity</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Top Dojos & Upcoming Events -->
            <div class="space-y-6">
                <!-- Top Dojos -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-lg font-semibold text-gray-900">Top Dojos</h3>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @forelse($topDojos as $index => $dojo)
                            <div class="px-6 py-4 hover:bg-gray-50 transition-colors">
                                <div class="flex items-center gap-4">
                                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-gradient-to-br from-purple-500 to-blue-600 flex items-center justify-center text-white text-sm font-bold">
                                        {{ $index + 1 }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 truncate">{{ $dojo->name }}</p>
                                        <p class="text-xs text-gray-500 mt-0.5">{{ number_format($dojo->members_count) }} students</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="px-6 py-8 text-center text-sm text-gray-500">No data available</div>
                        @endforelse
                    </div>
                </div>

                <!-- Upcoming Events -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-lg font-semibold text-gray-900">Upcoming Events</h3>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @forelse($upcomingEvents as $event)
                            <div class="px-6 py-4 hover:bg-gray-50 transition-colors">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 truncate">{{ $event->name }}</p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{ optional($event->dojo)->name ?? 'N/A' }}
                                        </p>
                                        <p class="text-xs text-gray-400 mt-1">
                                            {{ $event->event_date ? $event->event_date->format('d M Y') : 'N/A' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="px-6 py-8 text-center text-sm text-gray-500">No upcoming events</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check if Chart.js is loaded
            if (typeof Chart === 'undefined') {
                console.error('Chart.js is not loaded');
                return;
            }

            // Revenue Chart
            const revenueCtx = document.getElementById('revenueChart');
            if (revenueCtx) {
                new Chart(revenueCtx, {
                    type: 'line',
                    data: {
                        labels: @json($revenueChartLabels ?? []),
                        datasets: [{
                            label: 'Revenue (Rp)',
                            data: @json($revenueChartData ?? []),
                            borderColor: 'rgb(99, 102, 241)',
                            backgroundColor: 'rgba(99, 102, 241, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 4,
                            pointBackgroundColor: 'rgb(99, 102, 241)',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointHoverRadius: 6,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                padding: 12,
                                titleFont: {
                                    size: 14,
                                    weight: 'bold'
                                },
                                bodyFont: {
                                    size: 13
                                },
                                callbacks: {
                                    label: function(context) {
                                        return 'RM ' + new Intl.NumberFormat('en-MY', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(context.parsed.y);
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)'
                                },
                                ticks: {
                                    callback: function(value) {
                                    if (value >= 1000000) {
                                        return 'RM ' + (value / 1000000).toFixed(2) + 'M';
                                    } else if (value >= 1000) {
                                        return 'RM ' + (value / 1000).toFixed(2) + 'K';
                                    }
                                    return 'RM ' + value.toFixed(2);
                                    }
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            }

            // Mini Charts for KPI Cards
            const miniCharts = document.querySelectorAll('.mini-chart');
            miniCharts.forEach((canvas) => {
                const ctx = canvas.getContext('2d');
                const data = JSON.parse(canvas.dataset.values || '[]');
                const color = canvas.dataset.color || '#3b82f6';
                
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.map((_, i) => ''),
                        datasets: [{
                            data: data,
                            backgroundColor: color,
                            borderColor: color,
                            borderWidth: 0,
                            borderRadius: 4,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                enabled: false
                            }
                        },
                        scales: {
                            y: {
                                display: false,
                                beginAtZero: true
                            },
                            x: {
                                display: false
                            }
                        }
                    }
                });
            });
        });
    </script>
    @endpush
</x-app-layout>
