<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Health Dashboard - AskBeerus</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-[#0b0a10] text-white min-h-screen font-sans p-6">
    <div class="max-w-7xl mx-auto">
        
        <!-- Header & Top Navigation Bar -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 border-b border-purple-900/40 pb-5 gap-4">
            <h1 class="text-3xl font-bold flex items-center gap-3 tracking-wide">
                <span class="text-2xl">📊</span> Health Hub
            </h1>
            <nav class="flex flex-wrap gap-2 bg-gray-900/90 p-1.5 rounded-xl border border-purple-900/50 shadow-inner">
                <a href="{{ route('dashboard') }}" class="px-3 py-2 text-xs bg-purple-600 rounded-lg text-white font-medium shadow">📊 Dashboard</a>
                <a href="{{ route('sleep.page') }}" class="px-3 py-2 text-xs text-gray-400 hover:text-white transition">😴 Sleep</a>
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

        <!-- Biological Readiness Score -->
        <div class="mb-8 p-6 bg-gradient-to-r from-purple-950/60 via-gray-900 to-gray-900 rounded-2xl border border-purple-800/50 shadow-2xl">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4 border-b border-purple-900/30 pb-4">
                <div>
                    <h2 class="text-lg font-bold flex items-center gap-2 text-purple-200">
                        <span>⚡</span> Daily Biological Readiness Score
                    </h2>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $readinessAdvice }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-xs font-semibold px-3 py-1 bg-purple-900/80 border border-purple-600 text-purple-200 rounded-full">
                        {{ $readinessStatus }}
                    </span>
                    <span class="text-3xl font-black text-purple-300">{{ $readinessScore }}%</span>
                </div>
            </div>

            <div class="w-full bg-black/60 rounded-full h-3 mb-6 p-0.5 border border-purple-900/40">
                <div class="bg-gradient-to-r from-purple-600 to-emerald-400 h-2 rounded-full transition-all duration-500" style="width: {{ $readinessScore }}%"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-black/40 p-3.5 rounded-xl border border-amber-900/40 flex items-center gap-3">
                    <div class="text-2xl">☕</div>
                    <div>
                        <div class="text-[10px] text-gray-400 uppercase font-semibold">Caffeine Cutoff Window</div>
                        <div class="text-sm font-bold text-amber-300">{{ $caffeineCutoff }}</div>
                    </div>
                </div>

                <div class="bg-black/40 p-3.5 rounded-xl border border-emerald-900/40 flex items-center gap-3">
                    <div class="text-2xl">🧠</div>
                    <div>
                        <div class="text-[10px] text-gray-400 uppercase font-semibold">Peak Cognitive Hours</div>
                        <div class="text-sm font-bold text-emerald-300">{{ $peakEnergyWindow }}</div>
                    </div>
                </div>

                <div class="bg-black/40 p-3.5 rounded-xl border border-indigo-900/40 flex items-center gap-3">
                    <div class="text-2xl">🌙</div>
                    <div>
                        <div class="text-[10px] text-gray-400 uppercase font-semibold">Target Bedtime Target</div>
                        <div class="text-sm font-bold text-indigo-300">{{ $idealBedtime }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Biological Health Risk Assessment -->
        <div class="mb-8 p-6 bg-gray-900/90 rounded-2xl border border-red-900/40 shadow-2xl">
            <h2 class="text-lg font-bold flex items-center gap-2 mb-4 text-red-400">
                <span>🛡️</span> Biological Health Risk Assessment
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-black/50 p-4 rounded-xl border {{ $kidneyRiskLevel == 'HIGH' ? 'border-red-600/50 bg-red-950/20' : 'border-gray-800' }}">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-semibold text-blue-300 flex items-center gap-2">🫘 Kidney Filtration Risk</span>
                        <span class="text-[10px] font-bold px-2.5 py-0.5 rounded border {{ $kidneyRiskLevel == 'HIGH' ? 'bg-red-900/80 text-red-200 border-red-500' : 'bg-green-900/80 text-green-200 border-green-500' }}">
                            {{ $kidneyRiskLevel }} RISK
                        </span>
                    </div>
                    <p class="text-xs text-gray-300 leading-relaxed">{{ $kidneyRiskMsg }}</p>
                </div>

                <div class="bg-black/50 p-4 rounded-xl border {{ $heartRiskLevel == 'ELEVATED' ? 'border-amber-600/50 bg-amber-950/20' : 'border-gray-800' }}">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-semibold text-red-300 flex items-center gap-2">❤️ Cardiac Strain & Vascular Alert</span>
                        <span class="text-[10px] font-bold px-2.5 py-0.5 rounded border {{ $heartRiskLevel == 'ELEVATED' ? 'bg-amber-900/80 text-amber-200 border-amber-500' : 'bg-green-900/80 text-green-200 border-green-500' }}">
                            {{ $heartRiskLevel }}
                        </span>
                    </div>
                    <p class="text-xs text-gray-300 leading-relaxed">{{ $heartRiskMsg }}</p>
                </div>
            </div>
        </div>

        <!-- 🧠 MOOD & COGNITIVE BRAIN FUNCTION SECTION (EMBEDDED) -->
        <div class="mb-10 bg-gray-900/80 p-6 rounded-2xl border border-purple-900/30 shadow-xl">
            <h2 class="text-xl font-bold text-purple-200 mb-2 flex items-center gap-2">
                <span>🧠</span> Mood & Brain Function Analyzer
            </h2>
            <p class="text-xs text-gray-400 mb-6">
                Overlays sleep duration against predicted focus score (%) and mood stability score.
            </p>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Mood Form -->
                <div class="bg-black/40 p-5 rounded-xl border border-pink-900/30">
                    <div class="mb-3 text-xs">
                        Derived State: <strong class="text-pink-300">{{ $todayMood }}</strong>
                    </div>
                    <form action="{{ route('log.mood') }}" method="POST" class="space-y-3">
                        @csrf
                        <label class="block text-xs font-semibold text-gray-400">Override Today's Mood</label>
                        <select name="mood_state" class="w-full bg-black/60 border border-pink-800/50 rounded-xl p-2.5 text-xs text-white focus:outline-none focus:border-pink-500">
                            <option value="⚡ Energetic & Driven">⚡ Energetic & Driven</option>
                            <option value="😊 Balanced & Calm" selected>😊 Balanced & Calm</option>
                            <option value="🧘 Focused & Flowing">🧘 Focused & Flowing</option>
                            <option value="🥱 Anxious / Stressed">🥱 Anxious / Stressed</option>
                            <option value="😴 Brain-Fogged / Fatigued">😴 Brain-Fogged / Fatigued</option>
                        </select>
                        <button type="submit" class="w-full bg-pink-600 hover:bg-pink-500 text-white font-bold py-2 rounded-xl text-xs transition">
                            + Update Mood
                        </button>
                    </form>
                </div>

                <!-- Brain Chart -->
                <div class="lg:col-span-2 relative h-64 w-full">
                    <canvas id="brainChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Today's Metric Stat Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
            <div class="bg-gray-900/80 p-6 rounded-2xl border border-purple-900/30 shadow-xl">
                <div class="text-gray-400 text-xs font-semibold uppercase mb-1">Today's Sleep</div>
                <div class="text-3xl font-bold text-purple-300 mb-2">{{ $todaySleep ? $todaySleep->hours_slept : 0 }} hrs</div>
                <a href="{{ route('sleep.page') }}" class="text-xs text-purple-400 hover:underline flex items-center gap-1">Log Sleep Record →</a>
            </div>

            <div class="bg-gray-900/80 p-6 rounded-2xl border border-blue-900/30 shadow-xl">
                <div class="text-gray-400 text-xs font-semibold uppercase mb-1">Today's Hydration</div>
                <div class="text-3xl font-bold text-blue-400 mb-2">{{ number_format($todayWater) }} mL</div>
                <a href="{{ route('hydration.page') }}" class="text-xs text-blue-400 hover:underline flex items-center gap-1">Log Hydration →</a>
            </div>

            <div class="bg-gray-900/80 p-6 rounded-2xl border border-emerald-900/30 shadow-xl">
                <div class="text-gray-400 text-xs font-semibold uppercase mb-1">Predicted Focus Score</div>
                <div class="text-3xl font-bold text-emerald-400 mb-2">{{ $todayFocusScore }}%</div>
                <span class="text-xs text-emerald-400">Calculated from sleep quality</span>
            </div>

            <div class="bg-gray-900/80 p-6 rounded-2xl border border-pink-900/30 shadow-xl">
                <div class="text-gray-400 text-xs font-semibold uppercase mb-1">Derived Mood State</div>
                <div class="text-lg font-bold text-pink-400 mb-2">{{ $todayMood }}</div>
                <span class="text-xs text-pink-400">Emotional balance index</span>
            </div>
        </div>

    </div>

    <!-- Brain Chart Script -->
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