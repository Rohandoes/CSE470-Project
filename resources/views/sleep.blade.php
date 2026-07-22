<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sleep Tracker - AskBeerus</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-[#0b0a10] text-white min-h-screen font-sans p-6">
    <div class="max-w-7xl mx-auto">
        
        <!-- Updated Navigation Bar -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 border-b border-purple-900/40 pb-5 gap-4">
            <h1 class="text-3xl font-bold flex items-center gap-3 tracking-wide">
                <span class="text-2xl">😴</span> Sleep Tracker
            </h1>
            <nav class="flex flex-wrap gap-2 bg-gray-900/90 p-1.5 rounded-xl border border-purple-900/50 shadow-inner">
                <a href="{{ route('dashboard') }}" class="px-3 py-2 text-xs text-gray-400 hover:text-white transition">📊 Dashboard</a>
                <a href="{{ route('sleep.page') }}" class="px-3 py-2 text-xs bg-purple-600 rounded-lg text-white font-medium shadow">😴 Sleep</a>
                <a href="{{ route('hydration.page') }}" class="px-3 py-2 text-xs text-gray-400 hover:text-white transition">💧 Hydration</a>
                <a href="{{ route('ai.coach') }}" class="px-3 py-2 text-xs text-purple-300 hover:text-purple-100 transition font-semibold">🐱‍👤 AskBeerus Coach</a>
                <a href="{{ route('ai.anomaly') }}" class="px-3 py-2 text-xs text-purple-300 hover:text-purple-100 transition font-semibold">🔍 Anomaly Scanner</a>
            </nav>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-900/50 border border-green-500/50 text-green-300 rounded-xl text-sm">
                ✓ {{ session('success') }}
            </div>
        @endif

        <!-- Main Grid: Form + Chart -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left Side: Log Sleep Form -->
            <div class="bg-gray-900/80 p-6 rounded-2xl border border-purple-900/30 shadow-xl">
                <h2 class="text-lg font-bold text-purple-300 mb-4 flex items-center gap-2">
                    <span>📝</span> Log Sleep Record
                </h2>
                <form action="{{ route('log.sleep') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 mb-1">Hours Slept</label>
                        <input type="number" step="0.1" name="hours_slept" placeholder="e.g. 7.5" class="w-full bg-black/60 border border-purple-800/50 rounded-xl p-3 text-sm text-white focus:outline-none focus:border-purple-500" required>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 mb-1">Quality Rating (1-5)</label>
                        <select name="quality_rating" class="w-full bg-black/60 border border-purple-800/50 rounded-xl p-3 text-sm text-white focus:outline-none focus:border-purple-500">
                            <option value="5">5 - Excellent</option>
                            <option value="4">4 - Good</option>
                            <option value="3" selected>3 - Average</option>
                            <option value="2">2 - Poor</option>
                            <option value="1">1 - Terrible</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full bg-purple-600 hover:bg-purple-500 text-white font-bold py-3 rounded-xl text-sm transition shadow-lg">
                        + Save Sleep Record
                    </button>
                </form>
            </div>

            <!-- Right Side: 7-Day History Chart -->
            <div class="lg:col-span-2 bg-gray-900/80 p-6 rounded-2xl border border-purple-900/30 shadow-xl flex flex-col justify-between">
                <div>
                    <h2 class="text-lg font-bold text-purple-200 mb-4 flex items-center gap-2">
                        <span>📈</span> 7-Day Sleep Duration History
                    </h2>
                    <div class="relative h-72 w-full">
                        <canvas id="sleepChart"></canvas>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Chart Setup -->
    <script>
        const ctx = document.getElementById('sleepChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($dates),
                datasets: [{
                    label: 'Hours Slept',
                    data: @json($sleep),
                    backgroundColor: 'rgba(168, 85, 247, 0.7)',
                    borderColor: 'rgba(168, 85, 247, 1)',
                    borderWidth: 1,
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: { ticks: { color: '#9ca3af' }, grid: { color: 'rgba(255, 255, 255, 0.05)' } },
                    y: { 
                        ticks: { color: '#c084fc' }, 
                        grid: { color: 'rgba(255, 255, 255, 0.05)' },
                        title: { display: true, text: 'Hours', color: '#c084fc' } 
                    }
                },
                plugins: { legend: { labels: { color: '#e5e7eb' } } }
            }
        });
    </script>
</body>
</html>