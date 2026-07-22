<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cognitive Focus - AskBeerus</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-[#0b0a10] text-white min-h-screen font-sans p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Navigation Bar -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 border-b border-purple-900/40 pb-5 gap-4">
            <h1 class="text-3xl font-bold flex items-center gap-3 tracking-wide">
                <span class="text-2xl">⚡</span> Cognitive Focus Index
            </h1>
            <nav class="flex flex-wrap gap-2 bg-gray-900/90 p-1.5 rounded-xl border border-purple-900/50 shadow-inner">
                <a href="{{ route('dashboard') }}" class="px-3 py-2 text-xs text-gray-400 hover:text-white transition">📊 Dashboard</a>
                <a href="{{ route('sleep.page') }}" class="px-3 py-2 text-xs text-gray-400 hover:text-white transition">😴 Sleep</a>
                <a href="{{ route('hydration.page') }}" class="px-3 py-2 text-xs text-gray-400 hover:text-white transition">💧 Hydration</a>
                <a href="{{ route('mood.page') }}" class="px-3 py-2 text-xs text-gray-400 hover:text-white transition">🧠 Mood</a>
                <a href="{{ route('cognitive.page') }}" class="px-3 py-2 text-xs bg-emerald-600 rounded-lg text-white font-medium shadow">⚡ Cognitive</a>
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
            <div class="bg-gray-900/80 p-6 rounded-2xl border border-emerald-900/30 shadow-xl">
                <h2 class="text-lg font-bold text-emerald-300 mb-4 flex items-center gap-2"><span>⚡</span> Log Cognitive Focus</h2>
                <div class="mb-4 p-3 bg-emerald-950/40 rounded-xl border border-emerald-800/30 text-xs">
                    Current Focus: <strong class="text-emerald-300">{{ $todayCognitive }}</strong>
                </div>
                <form action="{{ route('log.cognitive') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 mb-1">Focus Score (0 - 100%)</label>
                        <input type="number" min="0" max="100" name="focus_score" placeholder="e.g. 85" class="w-full bg-black/60 border border-emerald-800/50 rounded-xl p-3 text-sm text-white focus:outline-none focus:border-emerald-500" required>
                    </div>
                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-500 text-white font-bold py-3 rounded-xl text-sm transition shadow-lg">
                        + Save Focus Score
                    </button>
                </form>
            </div>

            <!-- Chart -->
            <div class="lg:col-span-2 bg-gray-900/80 p-6 rounded-2xl border border-emerald-900/30 shadow-xl">
                <h2 class="text-lg font-bold text-emerald-200 mb-4 flex items-center gap-2"><span>📈</span> 7-Day Cognitive Performance Trend</h2>
                <div class="relative h-72 w-full">
                    <canvas id="cognitiveChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script>
        new Chart(document.getElementById('cognitiveChart').getContext('2d'), {
            type: 'line',
            data: {
                labels: @json($dates),
                datasets: [{
                    label: 'Focus Score (%)',
                    data: @json($cognitive),
                    backgroundColor: 'rgba(16, 185, 129, 0.2)',
                    borderColor: 'rgba(16, 185, 129, 1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: { ticks: { color: '#9ca3af' }, grid: { color: 'rgba(255, 255, 255, 0.05)' } },
                    y: { ticks: { color: '#34d399' }, grid: { color: 'rgba(255, 255, 255, 0.05)' }, min: 0, max: 100 }
                }
            }
        });
    </script>
</body>
</html>