<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mood Tracker - AskBeerus</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-[#0b0a10] text-white min-h-screen font-sans p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Navigation Bar -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 border-b border-purple-900/40 pb-5 gap-4">
            <h1 class="text-3xl font-bold flex items-center gap-3 tracking-wide">
                <span class="text-2xl">🧠</span> Mood Tracker
            </h1>
            <nav class="flex flex-wrap gap-2 bg-gray-900/90 p-1.5 rounded-xl border border-purple-900/50 shadow-inner">
                <a href="{{ route('dashboard') }}" class="px-3 py-2 text-xs text-gray-400 hover:text-white transition">📊 Dashboard</a>
                <a href="{{ route('sleep.page') }}" class="px-3 py-2 text-xs text-gray-400 hover:text-white transition">😴 Sleep</a>
                <a href="{{ route('hydration.page') }}" class="px-3 py-2 text-xs text-gray-400 hover:text-white transition">💧 Hydration</a>
                <a href="{{ route('mood.page') }}" class="px-3 py-2 text-xs bg-pink-600 rounded-lg text-white font-medium shadow">🧠 Mood</a>
                <a href="{{ route('cognitive.page') }}" class="px-3 py-2 text-xs text-gray-400 hover:text-white transition">⚡ Cognitive</a>
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
            <div class="bg-gray-900/80 p-6 rounded-2xl border border-pink-900/30 shadow-xl">
                <h2 class="text-lg font-bold text-pink-300 mb-4 flex items-center gap-2"><span>🧠</span> Log Today's Mood</h2>
                <div class="mb-4 p-3 bg-pink-950/40 rounded-xl border border-pink-800/30 text-xs">
                    Current Mood: <strong class="text-pink-300">{{ $todayMood }}</strong>
                </div>
                <form action="{{ route('log.mood') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 mb-1">Select State</label>
                        <select name="mood_state" class="w-full bg-black/60 border border-pink-800/50 rounded-xl p-3 text-sm text-white focus:outline-none focus:border-pink-500">
                            <option value="⚡ Energetic & Driven">⚡ Energetic & Driven</option>
                            <option value="😊 Balanced & Calm" selected>😊 Balanced & Calm</option>
                            <option value="🧘 Focused & Flowing">🧘 Focused & Flowing</option>
                            <option value="🥱 Anxious / Stressed">🥱 Anxious / Stressed</option>
                            <option value="😴 Brain-Fogged / Fatigued">😴 Brain-Fogged / Fatigued</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full bg-pink-600 hover:bg-pink-500 text-white font-bold py-3 rounded-xl text-sm transition shadow-lg">
                        + Update Mood
                    </button>
                </form>
            </div>

            <!-- Chart -->
            <div class="lg:col-span-2 bg-gray-900/80 p-6 rounded-2xl border border-pink-900/30 shadow-xl">
                <h2 class="text-lg font-bold text-pink-200 mb-4 flex items-center gap-2"><span>📈</span> 7-Day Mood Trend</h2>
                <div class="relative h-72 w-full">
                    <canvas id="moodChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script>
        new Chart(document.getElementById('moodChart').getContext('2d'), {
            type: 'line',
            data: {
                labels: @json($dates),
                datasets: [{
                    label: 'Mood Stability Index (1-5)',
                    data: @json($mood),
                    backgroundColor: 'rgba(236, 72, 153, 0.2)',
                    borderColor: 'rgba(236, 72, 153, 1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: { ticks: { color: '#9ca3af' }, grid: { color: 'rgba(255, 255, 255, 0.05)' } },
                    y: { ticks: { color: '#f472b6', stepSize: 1 }, grid: { color: 'rgba(255, 255, 255, 0.05)' }, min: 1, max: 5 }
                }
            }
        });
    </script>
</body>
</html>