<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mood & Cognitive Brain Function - AskBeerus</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-[#0b0a10] text-white min-h-screen font-sans p-6">
    <div class="max-w-7xl mx-auto">
        
        <!-- Header & Unified Navigation Bar -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 border-b border-purple-900/40 pb-5 gap-4">
            <h1 class="text-3xl font-bold flex items-center gap-3 tracking-wide">
                <span class="text-2xl">🧠</span> Mood & Cognitive Function
            </h1>
            <nav class="flex flex-wrap gap-2 bg-gray-900/90 p-1.5 rounded-xl border border-purple-900/50 shadow-inner">
                <a href="{{ route('dashboard') }}" class="px-3 py-2 text-xs text-gray-400 hover:text-white transition">📊 Dashboard</a>
                <a href="{{ route('sleep.page') }}" class="px-3 py-2 text-xs text-gray-400 hover:text-white transition">😴 Sleep</a>
                <a href="{{ route('hydration.page') }}" class="px-3 py-2 text-xs text-gray-400 hover:text-white transition">💧 Hydration</a>
                <a href="{{ route('mood.cognitive.page') }}" class="px-3 py-2 text-xs bg-purple-600 rounded-lg text-white font-medium shadow">🧠 Mood & Brain Function</a>
                <a href="{{ route('ai.coach') }}" class="px-3 py-2 text-xs text-purple-300 hover:text-purple-100 transition font-semibold">🐱‍👤 AskBeerus Coach</a>
                <a href="{{ route('ai.anomaly') }}" class="px-3 py-2 text-xs text-purple-300 hover:text-purple-100 transition font-semibold">🔍 Anomaly Scanner</a>
            </nav>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-900/50 border border-green-500/50 text-green-300 rounded-xl text-sm">
                ✓ {{ session('success') }}
            </div>
        @endif

        <!-- Derived Metrics Banner -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-gray-900/80 p-5 rounded-2xl border border-purple-900/30 shadow-xl">
                <div class="text-gray-400 text-xs font-semibold uppercase mb-1">Today's Sleep Base</div>
                <div class="text-2xl font-bold text-purple-300 mb-1">{{ $sleptHours }} hrs</div>
                <div class="text-xs text-purple-400">Quality Rating: {{ $sleepQuality }}/5</div>
            </div>

            <div class="bg-gray-900/80 p-5 rounded-2xl border border-emerald-900/30 shadow-xl">
                <div class="text-gray-400 text-xs font-semibold uppercase mb-1">Predicted Focus Level</div>
                <div class="text-3xl font-bold text-emerald-400 mb-1">{{ $calculatedFocus }}%</div>
                <div class="text-xs text-emerald-300">Derived from sleep duration + quality</div>
            </div>

            <div class="bg-gray-900/80 p-5 rounded-2xl border border-pink-900/30 shadow-xl">
                <div class="text-gray-400 text-xs font-semibold uppercase mb-1">Derived Mood State</div>
                <div class="text-xl font-bold text-pink-400 mb-1">{{ $todayMood }}</div>
                <div class="text-xs text-pink-300">Correlated with focus & rest rating</div>
            </div>
        </div>

        <!-- Main Workspace: Chart + Override Form -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left Side: Manual Mood Override -->
            <div class="bg-gray-900/80 p-6 rounded-2xl border border-pink-900/30 shadow-xl">
                <h2 class="text-lg font-bold text-pink-300 mb-4 flex items-center gap-2"><span>🧠</span> Override Current Mood</h2>
                <form action="{{ route('log.mood') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 mb-1">Select Custom Mood</label>
                        <select name="mood_state" class="w-full bg-black/60 border border-pink-800/50 rounded-xl p-3 text-sm text-white focus:outline-none focus:border-pink-500">
                            <option value="⚡ Energetic & Driven">⚡ Energetic & Driven</option>
                            <option value="😊 Balanced & Calm" selected>😊 Balanced & Calm</option>
                            <option value="🧘 Focused & Flowing">🧘 Focused & Flowing</option>
                            <option value="🥱 Anxious / Stressed">🥱 Anxious / Stressed</option>
                            <option value="😴 Brain-Fogged / Fatigued">😴 Brain-Fogged / Fatigued</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full bg-pink-600 hover:bg-pink-500 text-white font-bold py-3 rounded-xl text-sm transition shadow-lg">
                        + Update Mood State
                    </button>
                </form>
            </div>

            <!-- Right Side: Dual-Axis Chart (Sleep vs Focus & Mood) -->
            <div class="lg:col-span-2 bg-gray-900/80 p-6 rounded-2xl border border-purple-900/30 shadow-xl">
                <h2 class="text-lg font-bold text-purple-200 mb-2 flex items-center gap-2">
                    <span>📊</span> Sleep Impact on Focus Level & Mood Stability
                </h2>
                <p class="text-xs text-gray-400 mb-6">
                    This chart shows how your sleep duration (hours) and quality determine your daily Focus Score (%) and Mood Stability Index.
                </p>
                <div class="relative h-72 w-full">
                    <canvas id="brainChart"></canvas>
                </div>
            </div>

        </div>
    </div>

    <!-- Chart Script -->
    <script>
        const ctx = document.getElementById('brainChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($dates),
                datasets: [
                    {
                        label: 'Sleep Duration (hrs)',
                        data: @json($sleep),
                        backgroundColor: 'rgba(168, 85, 247, 0.5)',
                        borderColor: 'rgba(168, 85, 247, 1)',
                        borderWidth: 1,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Focus Level (%)',
                        data: @json($cognitiveFocus),
                        backgroundColor: 'rgba(16, 185, 129, 0.3)',
                        borderColor: 'rgba(16, 185, 129, 1)',
                        borderWidth: 2,
                        type: 'line',
                        tension: 0.3,
                        yAxisID: 'y1'
                    },
                    {
                        label: 'Mood Index (1-5)',
                        data: @json($moodScores),
                        backgroundColor: 'rgba(236, 72, 153, 0.3)',
                        borderColor: 'rgba(236, 72, 153, 1)',
                        borderWidth: 2,
                        type: 'line',
                        tension: 0.3,
                        yAxisID: 'y2'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: { ticks: { color: '#9ca3af' }, grid: { color: 'rgba(255, 255, 255, 0.05)' } },
                    y: {
                        position: 'left',
                        title: { display: true, text: 'Sleep (hrs)', color: '#c084fc' },
                        ticks: { color: '#c084fc' },
                        grid: { color: 'rgba(255, 255, 255, 0.05)' }
                    },
                    y1: {
                        position: 'right',
                        title: { display: true, text: 'Focus Level (%)', color: '#34d399' },
                        ticks: { color: '#34d399' },
                        min: 0, max: 100,
                        grid: { drawOnChartArea: false }
                    },
                    y2: {
                        position: 'right',
                        display: false,
                        min: 1, max: 5
                    }
                },
                plugins: { legend: { labels: { color: '#e5e7eb' } } }
            }
        });
    </script>
</body>
</html>