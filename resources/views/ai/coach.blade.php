<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Health Center - AskBeerus</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#0b0a10] text-white min-h-screen font-sans p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold flex items-center gap-3 tracking-wide">
                <span class="text-2xl">🐱‍👤</span> AI Health Center
            </h1>
            <div class="flex gap-2 bg-gray-900/90 p-1.5 rounded-xl border border-purple-900/50 shadow-inner">
                <a href="/dashboard" class="px-4 py-2 text-sm text-gray-400 hover:text-white transition">📊 Dashboard</a>
                <a href="/ai/recovery-coach" class="px-4 py-2 text-sm bg-purple-600 rounded-lg text-white font-medium shadow">🐱‍👤 AskBeerus Coach</a>
                <a href="/ai/anomaly-scanner" class="px-4 py-2 text-sm text-gray-400 hover:text-white transition">🔍 Anomaly Scanner</a>
            </div>
        </div>

        <!-- Main Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            
            <!-- Left Column: Input Form -->
            <div class="bg-gray-900/80 p-6 rounded-2xl border border-purple-900/30 backdrop-blur-sm shadow-xl">
                <h2 class="text-xl font-bold flex items-center gap-2 mb-2 text-purple-200">
                    <span>🎯</span> Submit Goal to AskBeerus
                </h2>
                <p class="text-xs text-gray-400 mb-6">
                    AskBeerus will combine your logged metrics 
                    (Sleep: {{ $todaySleep ? $todaySleep->hours_slept : 0 }}h, 
                    Water: {{ $todayWater }}mL, 
                    Caffeine: {{ $todayCaffeine }}mg) with your inputs below.
                </p>

                <form action="{{ route('ai.coach.generate') }}" method="POST" class="space-y-5">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-purple-300 mb-1.5 uppercase tracking-wider">Target Goal Today</label>
                        <select name="daily_goal" class="w-full bg-black/60 border border-purple-800/50 rounded-xl p-3 text-sm text-white focus:outline-none focus:border-purple-500">
                            <option value="Intense Study / Exam Prep" {{ (isset($inputs['daily_goal']) && $inputs['daily_goal'] == 'Intense Study / Exam Prep') ? 'selected' : '' }}>📚 Intense Study / Exam Prep</option>
                            <option value="Heavy Athletic Training" {{ (isset($inputs['daily_goal']) && $inputs['daily_goal'] == 'Heavy Athletic Training') ? 'selected' : '' }}>🏋️ Heavy Athletic Training</option>
                            <option value="Active Recovery / Rest Day" {{ (isset($inputs['daily_goal']) && $inputs['daily_goal'] == 'Active Recovery / Rest Day') ? 'selected' : '' }}>🛌 Active Recovery / Rest Day</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-purple-300 mb-1.5 uppercase tracking-wider">Current Energy / Stress Level</label>
                        <select name="stress_level" class="w-full bg-black/60 border border-purple-800/50 rounded-xl p-3 text-sm text-white focus:outline-none focus:border-purple-500">
                            <option value="High Energy, Low Stress" {{ (isset($inputs['stress_level']) && $inputs['stress_level'] == 'High Energy, Low Stress') ? 'selected' : '' }}>⚡ High Energy, Low Stress</option>
                            <option value="Moderate Energy, Moderate Stress" {{ (isset($inputs['stress_level']) && $inputs['stress_level'] == 'Moderate Energy, Moderate Stress') ? 'selected' : '' }}>⚖️ Moderate Energy, Moderate Stress</option>
                            <option value="Exhausted / High Burnout" {{ (isset($inputs['stress_level']) && $inputs['stress_level'] == 'Exhausted / High Burnout') ? 'selected' : '' }}>🥱 Exhausted / High Burnout</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-purple-300 mb-1.5 uppercase tracking-wider">Custom Question or Notes for AskBeerus <span class="text-gray-500 font-normal lowercase">(optional)</span></label>
                        <textarea name="custom_question" rows="3" placeholder="e.g. I have an exam at 4 PM, when should I drink my last coffee?" class="w-full bg-black/60 border border-purple-800/50 rounded-xl p-3 text-sm text-white focus:outline-none focus:border-purple-500 placeholder-gray-600">{{ $inputs['custom_question'] ?? '' }}</textarea>
                    </div>

                    <button type="submit" class="w-full bg-purple-600 hover:bg-purple-500 text-white font-bold py-3.5 px-4 rounded-xl shadow-lg transition duration-200 flex items-center justify-center gap-2 text-sm tracking-wide">
                        ✨ Consult AskBeerus AI Coach
                    </button>
                </form>
            </div>

            <!-- Right Column: AI Output -->
            <div class="bg-gray-900/80 p-6 rounded-2xl border border-purple-800/50 backdrop-blur-sm shadow-xl flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-bold flex items-center gap-2 text-purple-200">
                            <span>🐱‍👤</span> AskBeerus Action Plan
                        </h2>
                        <span class="bg-purple-900/60 border border-purple-500/30 text-purple-300 text-xs px-3 py-1 rounded-full font-semibold">
                            Confidence: {{ $aiCoachOutput['confidence'] ?? 0 }}%
                        </span>
                    </div>

                    @if(!empty($aiCoachOutput) && isset($aiCoachOutput['summary']))
                        <div class="mb-6 p-4 rounded-xl bg-purple-950/30 border border-purple-800/30 text-gray-200 leading-relaxed text-sm">
                            {{ $aiCoachOutput['summary'] }}
                        </div>

                        <h3 class="text-xs font-bold text-purple-400 tracking-wider uppercase mb-3">Custom Action Steps:</h3>
                        <ul class="space-y-3">
                            @foreach($aiCoachOutput['action_plan'] as $step)
                                <li class="flex items-start gap-2 text-sm text-gray-300 bg-black/40 p-3 rounded-xl border border-purple-900/20">
                                    <span class="text-purple-400 mt-0.5">➤</span>
                                    <span>{{ $step }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-center py-16 text-gray-500 text-sm">
                            Submit your daily goals on the left to generate your custom AskBeerus recovery plan.
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</body>
</html>