<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wellness & Health Risk Engine</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        @keyframes pulseGlow {
            0%, 100% { box-shadow: 0 0 15px rgba(168, 85, 247, 0.2); }
            50% { box-shadow: 0 0 30px rgba(168, 85, 247, 0.5); }
        }
        .live-glow-card {
            animation: pulseGlow 4s infinite ease-in-out;
        }
    </style>
</head>
<body class="bg-black text-slate-100 min-h-screen p-6 relative overflow-x-hidden bg-[radial-gradient(ellipse_80%_80%_at_50%_-20%,rgba(120,119,198,0.25),rgba(255,255,255,0))]">

    <!-- Background Ambient Glow -->
    <div class="absolute top-10 left-1/4 w-96 h-96 bg-purple-900/30 rounded-full blur-[120px] -z-10 pointer-events-none"></div>
    <div class="absolute top-1/2 right-10 w-80 h-80 bg-violet-900/20 rounded-full blur-[100px] -z-10 pointer-events-none"></div>

    <div class="max-w-6xl mx-auto space-y-8">
        
        <!-- 1. MAIN GLOBAL NAVIGATION BAR (Links to AI Controller Pages) -->
        <div class="flex flex-col md:flex-row justify-between items-center border-b border-purple-900/50 pb-5 gap-4">
            <div>
                <div class="flex items-center space-x-3">
                    <h1 class="text-3xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-purple-400 via-fuchsia-300 to-indigo-300">
                        🌙 Wellness & Health Engine
                    </h1>
                    <span class="flex items-center space-x-1.5 bg-purple-950/80 border border-purple-500/40 text-purple-300 text-xs px-3 py-1 rounded-full font-medium shadow-[0_0_10px_rgba(168,85,247,0.3)]">
                        <span class="w-2 h-2 rounded-full bg-fuchsia-400 animate-ping"></span>
                        <span>AI CORE ONLINE</span>
                    </span>
                </div>
                <p class="text-purple-300/70 text-sm mt-1">Real-time physiological risk evaluation & cognitive focus forecasting.</p>
            </div>

            <!-- AI Navigation Hub -->
            <div class="flex bg-black/80 p-1.5 rounded-2xl border border-purple-900/60 shadow-[0_5px_20px_rgba(0,0,0,0.8)] gap-2">
                <a href="{{ route('wellness.dashboard') }}" class="px-4 py-2 rounded-xl text-xs font-bold bg-gradient-to-r from-purple-600 to-fuchsia-600 text-white shadow-[0_0_15px_rgba(168,85,247,0.5)]">
                    📊 Dashboard
                </a>
                <a href="{{ route('ai.coach') }}" class="px-4 py-2 rounded-xl text-xs font-semibold text-purple-300 hover:text-white transition-all">
                    🤖 AI Recovery Coach
                </a>
                <a href="{{ route('ai.anomaly') }}" class="px-4 py-2 rounded-xl text-xs font-semibold text-purple-300 hover:text-white transition-all">
                    🔍 Anomaly Scanner
                </a>
            </div>
        </div>

        <!-- 2. DASHBOARD SUB-TAB SWITCHER (Charts Navigation) -->
        <div class="flex justify-center md:justify-start bg-purple-950/30 p-1.5 rounded-xl border border-purple-900/40 gap-2 max-w-fit">
            <button id="tab-caffeine-btn" onclick="switchTab('caffeine')" class="px-4 py-1.5 rounded-lg text-xs font-bold transition-all duration-300 bg-purple-600 text-white">
                ⚡ Caffeine & Hydration
            </button>
            <button id="tab-sleep-btn" onclick="switchTab('sleep')" class="px-4 py-1.5 rounded-lg text-xs font-semibold transition-all duration-300 text-purple-300/70 hover:text-purple-200">
                🛌 Sleep Tracker
            </button>
            <button id="tab-cognitive-btn" onclick="switchTab('cognitive')" class="px-4 py-1.5 rounded-lg text-xs font-semibold transition-all duration-300 text-purple-300/70 hover:text-purple-200">
                🧠 Cognitive Forecast
            </button>
        </div>

        <!-- Success Flash Message -->
        @if(session('success'))
            <div class="bg-purple-950/80 border border-purple-500/60 text-purple-200 p-4 rounded-xl text-sm shadow-[0_0_20px_rgba(168,85,247,0.2)] flex items-center space-x-2">
                <span>✨</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- DYNAMIC HEALTH RISK WARNING ALERTS -->
        @if(count($healthWarnings) > 0)
            <div class="space-y-3">
                @foreach($healthWarnings as $warning)
                    <div class="p-4 rounded-xl border bg-black/80 shadow-[0_0_20px_rgba(225,29,72,0.2)] flex items-start space-x-3 
                        {{ $warning['type'] === 'caffeine' ? 'border-rose-500/70 text-rose-200' : '' }}
                        {{ $warning['type'] === 'sleep' ? 'border-amber-500/70 text-amber-200' : '' }}
                        {{ $warning['type'] === 'water' ? 'border-cyan-500/70 text-cyan-200' : '' }}">
                        <div class="text-sm leading-relaxed">
                            <span class="font-bold text-md block mb-0.5">{{ $warning['title'] }}</span>
                            <span>{{ $warning['message'] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- ========================================== -->
        <!-- TAB 1: CAFFEINE, HYDRATION & RISK GRAPHS  -->
        <!-- ========================================== -->
        <div id="tab-caffeine" class="space-y-8 transition-all duration-500">
            
            <!-- Stat Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Water Card -->
                <div class="bg-gradient-to-b from-purple-950/40 to-black/80 border border-purple-800/40 p-6 rounded-2xl shadow-[0_10px_30px_rgba(0,0,0,0.8)] hover:-translate-y-1.5 transition-all duration-300">
                    <span class="text-xs uppercase text-purple-400 font-semibold tracking-widest">Water Intake</span>
                    <div class="text-3xl font-black text-purple-100 mt-2">{{ number_format($todayWater) }} <span class="text-lg">mL</span></div>
                    <div class="text-xs {{ $todayWater < 2000 ? 'text-cyan-400 font-bold' : 'text-purple-300/60' }} mt-1">
                        {{ $todayWater < 2000 ? '⚠️ Under 2,000 mL (Kidney Strain)' : 'Target Met (≥2,000 mL)' }}
                    </div>
                </div>

                <!-- Sleep Readiness Score Card -->
                <div class="bg-gradient-to-b from-purple-950/40 to-black/80 border border-purple-800/40 p-6 rounded-2xl shadow-[0_10px_30px_rgba(0,0,0,0.8)] hover:-translate-y-1.5 transition-all duration-300">
                    <span class="text-xs uppercase text-purple-400 font-semibold tracking-widest">Tonight Sleep Readiness</span>
                    <div class="text-3xl font-black text-emerald-400 mt-2">{{ $readinessScore }}%</div>
                    <div class="text-xs text-purple-300/60 mt-1">11 PM Projected Caffeine: <span class="text-fuchsia-300 font-bold">{{ $bedtimeCaffeine }} mg</span></div>
                </div>

                <!-- Live Countdown Clearance Clock -->
                <div class="bg-gradient-to-b from-purple-950/40 to-black/80 border border-purple-800/40 p-6 rounded-2xl shadow-[0_10px_30px_rgba(0,0,0,0.8)] hover:-translate-y-1.5 transition-all duration-300">
                    <span class="text-xs uppercase text-purple-400 font-semibold tracking-widest">Caffeine Clearance Clock</span>
                    <div id="clearance-clock" class="text-3xl font-black text-fuchsia-300 mt-2 font-mono">
                        {{ $clearanceTimestamp ? 'Calculating...' : 'Cleared (<50mg)' }}
                    </div>
                    <div class="text-xs text-purple-300/60 mt-1">Time until safe bedtime threshold (&lt;50 mg)</div>
                </div>
            </div>

            <!-- GRAPH 1: Caffeine Decay Graph -->
            <div class="bg-gradient-to-b from-purple-950/30 via-black/90 to-black border border-purple-800/50 p-6 rounded-2xl shadow-[0_15px_40px_rgba(0,0,0,0.9)] live-glow-card">
                <div class="mb-4">
                    <h2 class="text-lg font-bold text-purple-200">⚡ Pharmacokinetic Caffeine Decay Curve</h2>
                    <p class="text-xs text-purple-300/60">5.7-hour exponential half-life projection up to 11 PM bedtime.</p>
                </div>
                <div class="h-64">
                    <canvas id="caffeineChart"></canvas>
                </div>
            </div>

            <!-- GRAPH 2: 7-Day Health Risk Trend Graph -->
            <div class="bg-gradient-to-b from-purple-950/30 via-black/90 to-black border border-purple-800/50 p-6 rounded-2xl shadow-[0_15px_40px_rgba(0,0,0,0.9)]">
                <div class="mb-4 flex justify-between items-center">
                    <div>
                        <h2 class="text-lg font-bold text-fuchsia-300">📊 7-Day Cumulative Health & Disruption Risk Trend</h2>
                        <p class="text-xs text-purple-300/60">Combines Sleep (&lt;8h), Caffeine (&gt;150mg), and Hydration (&lt;2L) risk scores across the last 7 days.</p>
                    </div>
                    <span class="text-xs bg-rose-950 border border-rose-600/50 text-rose-300 px-3 py-1 rounded-lg font-semibold">
                        7-DAY AUDIT
                    </span>
                </div>
                <div class="h-64">
                    <canvas id="weeklyRiskChart"></canvas>
                </div>
            </div>

            <!-- Hydration Form -->
            <form action="{{ route('hydration.store') }}" method="POST" class="bg-black/70 border border-purple-900/50 p-6 rounded-2xl shadow-[0_10px_30px_rgba(0,0,0,0.8)] space-y-4 max-w-2xl mx-auto">
                @csrf
                <h3 class="text-md font-bold text-fuchsia-300 border-b border-purple-900/40 pb-2">💧 Log Intake Event</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs text-purple-300/70 mb-1">Time Consumed</label>
                        <input type="time" name="consumed_at" value="{{ date('H:i') }}" class="w-full bg-purple-950/30 border border-purple-800/50 rounded-xl p-2.5 text-sm text-purple-100 focus:outline-none focus:ring-2 focus:ring-fuchsia-500">
                    </div>

                    <div>
                        <label class="block text-xs text-purple-300/70 mb-1">Water Intake (mL)</label>
                        <input type="number" name="water_ml" placeholder="e.g. 500" class="w-full bg-purple-950/30 border border-purple-800/50 rounded-xl p-2.5 text-sm text-purple-100 placeholder-purple-700/60 focus:outline-none focus:ring-2 focus:ring-fuchsia-500">
                    </div>

                    <div>
                        <label class="block text-xs text-purple-300/70 mb-1">Caffeine (mg)</label>
                        <input type="number" name="caffeine_mg" placeholder="e.g. 150" class="w-full bg-purple-950/30 border border-purple-800/50 rounded-xl p-2.5 text-sm text-purple-100 placeholder-purple-700/60 focus:outline-none focus:ring-2 focus:ring-fuchsia-500">
                    </div>
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-fuchsia-600 to-purple-600 hover:from-fuchsia-500 hover:to-purple-500 text-white font-semibold py-2.5 rounded-xl text-sm transition-all shadow-[0_0_15px_rgba(217,70,239,0.4)]">Save Intake Log</button>
            </form>
        </div>

        <!-- ========================================== -->
        <!-- TAB 2: SLEEP TRACKER PROFILE               -->
        <!-- ========================================== -->
        <div id="tab-sleep" class="space-y-8 hidden transition-all duration-500">
            
            @if($correlationInsight)
                <div class="max-w-2xl mx-auto p-5 rounded-2xl border bg-black/80 shadow-[0_0_25px_rgba(168,85,247,0.3)]
                    {{ $correlationInsight['type'] === 'warning' ? 'border-rose-500/60 text-rose-200' : 'border-emerald-500/60 text-emerald-200' }}">
                    <h4 class="font-bold text-md mb-1">{{ $correlationInsight['title'] }}</h4>
                    <p class="text-xs leading-relaxed opacity-90">{{ $correlationInsight['message'] }}</p>
                </div>
            @endif

            <!-- Sleep Stat Card -->
            <div class="max-w-2xl mx-auto bg-gradient-to-b from-purple-950/40 to-black/80 border border-purple-800/40 p-8 rounded-2xl shadow-[0_10px_30px_rgba(0,0,0,0.8)] text-center">
                <span class="text-xs uppercase text-purple-400 font-semibold tracking-widest">Sleep Recorded Today</span>
                <div class="text-5xl font-black text-purple-100 mt-3">
                    {{ $todaySleep ? $todaySleep->hours_slept . ' hrs' : 'No Log Today' }}
                </div>
                <div class="text-sm text-purple-300/70 mt-2">
                    Quality Rating: <span class="text-purple-200 font-bold text-lg">{{ $todaySleep->quality_rating ?? '-' }}/5 ⭐</span>
                    @if($todaySleep)
                        <span class="mx-2">•</span> Wakeup Mood: <span class="text-fuchsia-300 font-bold">{{ $todaySleep->wakeup_mood }}</span>
                    @endif
                </div>
            </div>

            <!-- Sleep Form with Dropdown -->
            <form action="{{ route('sleep.store') }}" method="POST" class="bg-black/70 border border-purple-900/50 p-6 rounded-2xl shadow-[0_10px_30px_rgba(0,0,0,0.8)] space-y-4 max-w-2xl mx-auto">
                @csrf
                <h3 class="text-md font-bold text-purple-300 border-b border-purple-900/40 pb-2">🛌 Log Sleep Session</h3>
                
                <div>
                    <label class="block text-xs text-purple-300/70 mb-1">Hours Slept</label>
                    <input type="number" step="0.1" name="hours_slept" placeholder="e.g. 7.5" required class="w-full bg-purple-950/30 border border-purple-800/50 rounded-xl p-2.5 text-sm text-purple-100 placeholder-purple-700/60 focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>

                <div>
                    <label class="block text-xs text-purple-300/70 mb-1">Quality Rating (1 to 5)</label>
                    <select name="quality_rating" required class="w-full bg-purple-950/30 border border-purple-800/50 rounded-xl p-2.5 text-sm text-purple-100 focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="5" class="bg-black text-purple-200">5 - Excellent (Restful Sleep)</option>
                        <option value="4" class="bg-black text-purple-200">4 - Good</option>
                        <option value="3" class="bg-black text-purple-200">3 - Moderate</option>
                        <option value="2" class="bg-black text-purple-200">2 - Poor</option>
                        <option value="1" class="bg-black text-purple-200">1 - Terrible</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs text-purple-300/70 mb-1">Wakeup Mood</label>
                    <select name="wakeup_mood" required class="w-full bg-purple-950/30 border border-purple-800/50 rounded-xl p-2.5 text-sm text-purple-100 focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="Refreshed & Energized" class="bg-black text-purple-200">⚡ Refreshed & Energized</option>
                        <option value="Calm & Rested" class="bg-black text-purple-200">😌 Calm & Rested</option>
                        <option value="Okay / Neutral" class="bg-black text-purple-200">😐 Okay / Neutral</option>
                        <option value="Tired & Groggy" class="bg-black text-purple-200">🥱 Tired & Groggy</option>
                        <option value="Exhausted" class="bg-black text-purple-200">😫 Exhausted</option>
                    </select>
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white font-semibold py-2.5 rounded-xl text-sm transition-all shadow-[0_0_15px_rgba(168,85,247,0.4)]">Save Sleep Log</button>
            </form>
        </div>

        <!-- ========================================== -->
        <!-- TAB 3: COGNITIVE PERFORMANCE FORECAST      -->
        <!-- ========================================== -->
        <div id="tab-cognitive" class="space-y-8 hidden transition-all duration-500">
            
            <!-- Focus Metrics Banner -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gradient-to-b from-purple-950/40 to-black/80 border border-purple-800/40 p-6 rounded-2xl shadow-[0_10px_30px_rgba(0,0,0,0.8)]">
                    <span class="text-xs uppercase text-purple-400 font-semibold tracking-widest">Peak Focus Window</span>
                    <div class="text-3xl font-black text-indigo-300 mt-2">{{ $peakFocusTime }}</div>
                    <div class="text-xs text-purple-300/60 mt-1">Predicted Peak Brain Energy: <span class="text-fuchsia-300 font-bold">{{ $peakFocusScore }}%</span></div>
                </div>

                <div class="bg-gradient-to-b from-purple-950/40 to-black/80 border border-purple-800/40 p-6 rounded-2xl shadow-[0_10px_30px_rgba(0,0,0,0.8)]">
                    <span class="text-xs uppercase text-purple-400 font-semibold tracking-widest">Dehydration Penalty</span>
                    <div class="text-3xl font-black {{ $hydrationPenalty > 0 ? 'text-rose-400' : 'text-emerald-400' }} mt-2">
                        -{{ $hydrationPenalty }}% Focus
                    </div>
                    <div class="text-xs text-purple-300/60 mt-1">
                        {{ $hydrationPenalty > 0 ? 'Drinking <2,000 mL water reduces cognitive capacity.' : 'Optimal hydration level maintained.' }}
                    </div>
                </div>
            </div>

            <!-- GRAPH 3: 24-Hour Cognitive Energy Curve -->
            <div class="bg-gradient-to-b from-purple-950/30 via-black/90 to-black border border-purple-800/50 p-6 rounded-2xl shadow-[0_15px_40px_rgba(0,0,0,0.9)] live-glow-card">
                <div class="mb-4 flex justify-between items-center">
                    <div>
                        <h2 class="text-lg font-bold text-indigo-300">🧠 24-Hour Cognitive Focus & Alertness Forecast</h2>
                        <p class="text-xs text-purple-300/60">Algorithmic neuro-performance prediction factoring sleep baseline, caffeine spikes, and hydration levels.</p>
                    </div>
                    <span class="text-xs bg-indigo-950 border border-indigo-500/50 text-indigo-300 px-3 py-1 rounded-lg font-semibold">
                        NEURO MODEL
                    </span>
                </div>
                <div class="h-72">
                    <canvas id="cognitiveChart"></canvas>
                </div>
            </div>
        </div>

    </div>

    <!-- Complete JavaScript -->
    <script>
        // Tab Switcher
        function switchTab(tab) {
            localStorage.setItem('activeWellnessTab', tab);

            const caffeineTab = document.getElementById('tab-caffeine');
            const sleepTab = document.getElementById('tab-sleep');
            const cognitiveTab = document.getElementById('tab-cognitive');

            const caffeineBtn = document.getElementById('tab-caffeine-btn');
            const sleepBtn = document.getElementById('tab-sleep-btn');
            const cognitiveBtn = document.getElementById('tab-cognitive-btn');

            caffeineTab.classList.add('hidden');
            sleepTab.classList.add('hidden');
            cognitiveTab.classList.add('hidden');

            caffeineBtn.className = "px-4 py-1.5 rounded-lg text-xs font-semibold text-purple-300/70 hover:text-purple-200";
            sleepBtn.className = "px-4 py-1.5 rounded-lg text-xs font-semibold text-purple-300/70 hover:text-purple-200";
            cognitiveBtn.className = "px-4 py-1.5 rounded-lg text-xs font-semibold text-purple-300/70 hover:text-purple-200";

            if (tab === 'caffeine') {
                caffeineTab.classList.remove('hidden');
                caffeineBtn.className = "px-4 py-1.5 rounded-lg text-xs font-bold bg-purple-600 text-white";
            } else if (tab === 'sleep') {
                sleepTab.classList.remove('hidden');
                sleepBtn.className = "px-4 py-1.5 rounded-lg text-xs font-bold bg-purple-600 text-white";
            } else if (tab === 'cognitive') {
                cognitiveTab.classList.remove('hidden');
                cognitiveBtn.className = "px-4 py-1.5 rounded-lg text-xs font-bold bg-purple-600 text-white";
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            const savedTab = localStorage.getItem('activeWellnessTab') || 'caffeine';
            switchTab(savedTab);
        });

        // Live Countdown Clock
        const clearanceTarget = "{{ $clearanceTimestamp }}";
        if (clearanceTarget) {
            const targetTime = new Date(clearanceTarget).getTime();
            const clockElem = document.getElementById('clearance-clock');

            const timerInterval = setInterval(() => {
                const now = new Date().getTime();
                const distance = targetTime - now;

                if (distance < 0) {
                    clearInterval(timerInterval);
                    clockElem.innerText = "Cleared (<50mg)";
                } else {
                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    const pad = (n) => n < 10 ? '0' + n : n;
                    clockElem.innerText = `${pad(hours)}h ${pad(minutes)}m ${pad(seconds)}s`;
                }
            }, 1000);
        }

        // CHART 1: Caffeine Decay Chart
        const curveData = @json($caffeineCurve);
        const labels1 = curveData.map(item => item.time);
        const dataValues1 = curveData.map(item => item.caffeine_mg);

        const ctx1 = document.getElementById('caffeineChart').getContext('2d');
        const gradient1 = ctx1.createLinearGradient(0, 0, 0, 300);
        gradient1.addColorStop(0, 'rgba(168, 85, 247, 0.5)');
        gradient1.addColorStop(1, 'rgba(168, 85, 247, 0.0)');

        new Chart(ctx1, {
            type: 'line',
            data: {
                labels: labels1,
                datasets: [
                    {
                        label: 'Active Caffeine (mg)',
                        data: dataValues1,
                        borderColor: '#c084fc',
                        backgroundColor: gradient1,
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3,
                        pointBackgroundColor: '#e879f9',
                        pointBorderColor: '#ffffff',
                        pointHoverRadius: 7
                    },
                    {
                        label: 'Disruption Limit (50 mg)',
                        data: Array(labels1.length).fill(50),
                        borderColor: '#f43f5e',
                        borderDash: [6, 6],
                        borderWidth: 2,
                        pointRadius: 0
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true, grid: { color: 'rgba(168, 85, 247, 0.1)' }, ticks: { color: '#c084fc' } },
                    x: { grid: { color: 'rgba(168, 85, 247, 0.1)' }, ticks: { color: '#c084fc' } }
                },
                plugins: { legend: { labels: { color: '#e9d5ff', font: { weight: 'bold' } } } }
            }
        });

        // CHART 2: 7-Day Weekly Risk Trend Graph
        const weeklyData = @json($weeklyRiskData);
        const labels2 = weeklyData.map(item => item.day);
        const dataValues2 = weeklyData.map(item => item.risk_score);

        const ctx2 = document.getElementById('weeklyRiskChart').getContext('2d');
        new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: labels2,
                datasets: [{
                    label: 'Cumulative Health Risk Index (%)',
                    data: dataValues2,
                    backgroundColor: 'rgba(217, 70, 239, 0.6)',
                    borderColor: '#e879f9',
                    borderWidth: 2,
                    borderRadius: 8,
                    hoverBackgroundColor: '#f43f5e'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true, max: 100, grid: { color: 'rgba(168, 85, 247, 0.1)' }, ticks: { color: '#c084fc' } },
                    x: { grid: { color: 'rgba(168, 85, 247, 0.1)' }, ticks: { color: '#c084fc' } }
                },
                plugins: { legend: { labels: { color: '#e9d5ff', font: { weight: 'bold' } } } }
            }
        });

        // CHART 3: 24-HOUR COGNITIVE FOCUS CURVE GRAPH
        const cogData = @json($cognitiveCurve);
        const labels3 = cogData.map(item => item.time);
        const dataValues3 = cogData.map(item => item.focus_score);

        const ctx3 = document.getElementById('cognitiveChart').getContext('2d');
        const gradient3 = ctx3.createLinearGradient(0, 0, 0, 300);
        gradient3.addColorStop(0, 'rgba(129, 140, 248, 0.6)');
        gradient3.addColorStop(1, 'rgba(129, 140, 248, 0.0)');

        new Chart(ctx3, {
            type: 'line',
            data: {
                labels: labels3,
                datasets: [{
                    label: 'Predicted Brain Focus / Alertness Score (%)',
                    data: dataValues3,
                    borderColor: '#818cf8',
                    backgroundColor: gradient3,
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointBackgroundColor: '#a5b4fc',
                    pointBorderColor: '#ffffff',
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true, max: 100, grid: { color: 'rgba(168, 85, 247, 0.1)' }, ticks: { color: '#818cf8' } },
                    x: { grid: { color: 'rgba(168, 85, 247, 0.1)' }, ticks: { color: '#818cf8' } }
                },
                plugins: { legend: { labels: { color: '#e0e7ff', font: { weight: 'bold' } } } }
            }
        });
    </script>
</body>
</html>