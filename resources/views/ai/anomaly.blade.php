<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AskBeerus Anomaly Scanner</title>
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
                <a href="/ai/recovery-coach" class="px-4 py-2 text-sm text-gray-400 hover:text-white transition">🐱‍👤 AskBeerus Coach</a>
                <a href="/ai/anomaly-scanner" class="px-4 py-2 text-sm bg-purple-600 rounded-lg text-white font-medium shadow">🔍 Anomaly Scanner</a>
            </div>
        </div>

        <!-- Main Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            
            <!-- Left Column: Input Form -->
            <div class="bg-gray-900/80 p-6 rounded-2xl border border-purple-900/30 backdrop-blur-sm shadow-xl">
                <h2 class="text-xl font-bold flex items-center gap-2 mb-2 text-purple-200">
                    <span>🔍</span> Run AskBeerus Anomaly Scan
                </h2>
                <p class="text-xs text-gray-400 mb-6">
                    AskBeerus will scan multi-day health logs to spot biological anomalies and fatigue risks.
                </p>

                <form action="{{ route('ai.anomaly.scan') }}" method="POST" class="space-y-5">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-purple-300 mb-1.5 uppercase tracking-wider">Historical Range</label>
                        <select name="scan_range" class="w-full bg-black/60 border border-purple-800/50 rounded-xl p-3 text-sm text-white focus:outline-none focus:border-purple-500">
                            <option value="3" {{ (isset($inputs['scan_range']) && $inputs['scan_range'] == '3') ? 'selected' : '' }}>Last 3 Days</option>
                            <option value="7" {{ (isset($inputs['scan_range']) && $inputs['scan_range'] == '7') ? 'selected' : '' }}>Last 7 Days</option>
                            <option value="14" {{ (isset($inputs['scan_range']) && $inputs['scan_range'] == '14') ? 'selected' : '' }}>Last 14 Days</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-purple-300 mb-1.5 uppercase tracking-wider">Primary Health / Symptom Focus</label>
                        <input type="text" name="symptom_focus" value="{{ $inputs['symptom_focus'] ?? 'Brain Fog & Brain Fatigue' }}" placeholder="e.g. Brain Fog, Afternoon Crashes, Poor Recovery" class="w-full bg-black/60 border border-purple-800/50 rounded-xl p-3 text-sm text-white focus:outline-none focus:border-purple-500 placeholder-gray-600" required>
                    </div>

                    <button type="submit" class="w-full bg-purple-600 hover:bg-purple-500 text-white font-bold py-3.5 px-4 rounded-xl shadow-lg transition duration-200 flex items-center justify-center gap-2 text-sm tracking-wide">
                        🔍 Execute AskBeerus Scan
                    </button>
                </form>
            </div>

            <!-- Right Column: Results -->
            <div class="bg-gray-900/80 p-6 rounded-2xl border border-purple-800/50 backdrop-blur-sm shadow-xl flex flex-col justify-between">
                <div>
                    <h2 class="text-xl font-bold flex items-center gap-2 text-purple-200 mb-6">
                        <span>🚨</span> Detected Biological Anomalies
                    </h2>

                    @if(!empty($anomalies) && is_array($anomalies))
                        <div class="space-y-4">
                            @foreach($anomalies as $item)
                                <div class="bg-black/50 p-4 rounded-xl border border-purple-900/30">
                                    <div class="flex justify-between items-center mb-2">
                                        <h3 class="font-bold text-sm text-purple-300">{{ $item['title'] ?? 'Anomaly Detected' }}</h3>
                                        <span class="text-[10px] uppercase font-bold px-2 py-0.5 rounded border 
                                            {{ ($item['severity'] ?? '') == 'HIGH' ? 'bg-red-950/80 border-red-600 text-red-300' : 'bg-yellow-950/80 border-yellow-600 text-yellow-300' }}">
                                            {{ $item['severity'] ?? 'MEDIUM' }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-300 mb-3 leading-relaxed">{{ $item['details'] ?? '' }}</p>
                                    <div class="text-xs text-purple-400 bg-purple-950/40 p-2.5 rounded-lg border border-purple-800/30">
                                        💡 <strong>AskBeerus Advice:</strong> {{ $item['recommendation'] ?? '' }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-16 text-gray-500 text-sm">
                            Run a scan on the left to let AskBeerus analyze your historical trends.
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</body>
</html>