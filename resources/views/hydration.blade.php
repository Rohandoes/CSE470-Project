<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hydration & Caffeine - AskBeerus</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-[#0b0a10] text-white min-h-screen font-sans p-6">
    <div class="max-w-7xl mx-auto">
        
        <!-- Navigation Bar -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 border-b border-purple-900/40 pb-5 gap-4">
            <h1 class="text-3xl font-bold flex items-center gap-3 tracking-wide">
                <span class="text-2xl">💧</span> Hydration & Caffeine Tracker
            </h1>
            <nav class="flex flex-wrap gap-2 bg-gray-900/90 p-1.5 rounded-xl border border-purple-900/50 shadow-inner">
                <a href="{{ route('dashboard') }}" class="px-3 py-2 text-xs text-gray-400 hover:text-white transition">📊 Dashboard</a>
                <a href="{{ route('sleep.page') }}" class="px-3 py-2 text-xs text-gray-400 hover:text-white transition">😴 Sleep</a>
                <a href="{{ route('hydration.page') }}" class="px-3 py-2 text-xs bg-blue-600 rounded-lg text-white font-medium shadow">💧 Hydration</a>
                <a href="{{ route('ai.coach') }}" class="px-3 py-2 text-xs text-purple-300 hover:text-purple-100 transition font-semibold">🐱‍👤 AskBeerus Coach</a>
                <a href="{{ route('ai.anomaly') }}" class="px-3 py-2 text-xs text-purple-300 hover:text-purple-100 transition font-semibold">🔍 Anomaly Scanner</a>
            </nav>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-900/50 border border-green-500/50 text-green-300 rounded-xl text-sm">
                ✓ {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Form -->
            <div class="bg-gray-900/80 p-6 rounded-2xl border border-blue-900/30 shadow-xl">
                <h2 class="text-lg font-bold text-blue-300 mb-4 flex items-center gap-2"><span>📝</span> Log Intake</h2>
                <form action="{{ route('log.hydration') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 mb-1">Water Intake (mL)</label>
                        <input type="number" name="water_ml" placeholder="e.g. 500" class="w-full bg-black/60 border border-blue-800/50 rounded-xl p-3 text-sm text-white focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 mb-1">Caffeine (mg)</label>
                        <input type="number" name="caffeine_mg" placeholder="e.g. 95" class="w-full bg-black/60 border border-blue-800/50 rounded-xl p-3 text-sm text-white focus:outline-none focus:border-blue-500">
                    </div>
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 rounded-xl text-sm transition shadow-lg">
                        + Save Intake
                    </button>
                </form>
            </div>

            <!-- Chart -->
            <div class="lg:col-span-2 bg-gray-900/80 p-6 rounded-2xl border border-blue-900/30 shadow-xl">
                <h2 class="text-lg font-bold text-blue-200 mb-4 flex items-center gap-2"><span>📈</span> 7-Day Hydration vs Caffeine History</h2>
                <div class="relative h-72 w-full">
                    <canvas id="hydrationChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script>
        new Chart(document.getElementById('hydrationChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: @json($dates),
                datasets: [
                    { label: 'Water (mL)', data: @json($water), backgroundColor: 'rgba(59, 130, 246, 0.6)', yAxisID: 'y' },
                    { label: 'Caffeine (mg)', data: @json($caffeine), backgroundColor: 'rgba(245, 158, 11, 0.8)', borderColor: 'rgba(245, 158, 11, 1)', type: 'line', yAxisID: 'y1' }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: { ticks: { color: '#9ca3af' }, grid: { color: 'rgba(255, 255, 255, 0.05)' } },
                    y: { position: 'left', title: { display: true, text: 'Water (mL)', color: '#60a5fa' }, ticks: { color: '#60a5fa' } },
                    y1: { position: 'right', title: { display: true, text: 'Caffeine (mg)', color: '#fbbf24' }, ticks: { color: '#fbbf24' }, grid: { drawOnChartArea: false } }
                }
            }
        });
    </script>
</body>
</html>