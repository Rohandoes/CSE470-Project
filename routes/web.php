<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AICoachController;
use App\Http\Controllers\AIAnomalyController;
use App\Models\SleepLog;
use App\Models\HydrationLog;
use Carbon\Carbon;

// Root Redirect
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Helper Function for 7-Day Historical Data
function get7DayHistory() {
    $dates = []; $sleep = []; $sleepQuality = []; $water = []; $caffeine = []; 
    $cognitiveFocus = []; $moodScores = [];

    for ($i = 6; $i >= 0; $i--) {
        $date = Carbon::today()->subDays($i);
        $dates[] = $date->format('M d');

        $s = SleepLog::where('user_id', 1)->whereDate('created_at', $date)->latest()->first();
        $hrs = $s ? (float) $s->hours_slept : 6.0;
        $quality = $s ? (int) $s->quality_rating : 3;
        
        $sleep[] = $hrs;
        $sleepQuality[] = $quality;

        $rawFocus = ($hrs / 8.0) * ($quality / 5.0) * 100;
        $calculatedFocus = min(100, max(20, round($rawFocus)));
        $cognitiveFocus[] = $calculatedFocus;

        if ($calculatedFocus >= 80) {
            $moodScores[] = 5;
        } elseif ($calculatedFocus >= 60) {
            $moodScores[] = 4;
        } elseif ($calculatedFocus >= 40) {
            $moodScores[] = 3;
        } else {
            $moodScores[] = 2;
        }

        $w = HydrationLog::where('user_id', 1)->whereDate('created_at', $date)->sum('water_ml');
        $water[] = (float) $w;

        $c = HydrationLog::where('user_id', 1)->whereDate('created_at', $date)->sum('caffeine_mg');
        $caffeine[] = (float) $c;
    }

    return compact('dates', 'sleep', 'sleepQuality', 'water', 'caffeine', 'cognitiveFocus', 'moodScores');
}

// 1. ALL-IN-ONE DASHBOARD PAGE
Route::get('/dashboard', function () {
    $todaySleep = SleepLog::where('user_id', 1)->whereDate('created_at', Carbon::today())->latest()->first();
    $todayWater = HydrationLog::where('user_id', 1)->whereDate('created_at', Carbon::today())->sum('water_ml');
    $todayCaffeine = HydrationLog::where('user_id', 1)->whereDate('created_at', Carbon::today())->sum('caffeine_mg');
    $sleptHours = $todaySleep ? $todaySleep->hours_slept : 0;
    $sleepQuality = $todaySleep ? $todaySleep->quality_rating : 3;

    $todayFocusScore = min(100, max(20, round(($sleptHours / 8.0) * ($sleepQuality / 5.0) * 100)));
    $todayMood = session('today_mood', ($todayFocusScore >= 70 ? '😊 Balanced & Focused' : '😴 Fatigued / Brain Fog'));

    $sleepComponent = min(50, ($sleptHours / 8.0) * ($sleepQuality / 5.0) * 50);
    $waterComponent = min(30, ($todayWater / 2500.0) * 30);
    $caffeineModifier = ($todayCaffeine > 350) ? -15 : (($todayCaffeine > 200) ? 0 : 20);
    $readinessScore = min(100, max(15, round($sleepComponent + $waterComponent + $caffeineModifier)));

    if ($readinessScore >= 80) {
        $readinessStatus = "🔥 Peak Operating State";
        $readinessAdvice = "CNS is fully primed. Ideal window for high cognitive strain or complex tasks.";
    } elseif ($readinessScore >= 55) {
        $readinessStatus = "⚡ Moderate Readiness";
        $readinessAdvice = "Sufficient energy for daily output. Pace caffeine intake.";
    } else {
        $readinessStatus = "⚠️ High Recovery Demand";
        $readinessAdvice = "System under biological strain. Prioritize sleep tonight.";
    }

    $caffeineCutoff = "02:00 PM";
    $idealBedtime = ($sleptHours < 6) ? "10:00 PM (Recovery Schedule)" : "11:00 PM";
    $peakEnergyWindow = ($todayFocusScore >= 70) ? "09:30 AM - 01:30 PM" : "02:00 PM - 04:30 PM";

    $kidneyRiskLevel = ($todayWater < 1500) ? 'HIGH' : (($todayWater < 2500) ? 'MODERATE' : 'LOW');
    $kidneyRiskMsg = ($todayWater < 1500) 
        ? '⚠️ High Risk: Low hydration (<1500 mL) increases kidney filtration strain.' 
        : '✓ Optimal: Hydration levels support baseline kidney function.';

    $heartRiskLevel = ($todayCaffeine > 300 || $sleptHours < 5) ? 'ELEVATED' : 'LOW';
    $heartRiskMsg = ($todayCaffeine > 300 || $sleptHours < 5)
        ? '🚨 Cardiac Alert: Sleep deficit combined with caffeine intake elevates baseline vascular strain.'
        : '✓ Normal: Cardiac workload within healthy operating ranges.';

    $history = get7DayHistory();

    return view('dashboard', array_merge($history, compact(
        'todaySleep', 'todayWater', 'todayCaffeine', 'todayFocusScore', 'todayMood',
        'kidneyRiskLevel', 'kidneyRiskMsg', 'heartRiskLevel', 'heartRiskMsg',
        'readinessScore', 'readinessStatus', 'readinessAdvice', 'caffeineCutoff', 'idealBedtime', 'peakEnergyWindow'
    )));
})->name('dashboard');

// Redirect legacy route to Dashboard so old links never crash
Route::get('/mood-cognitive', function () {
    return redirect()->route('dashboard');
})->name('mood.cognitive.page');

// 2. SLEEP TRACKER PAGE
Route::get('/sleep', function () {
    return view('sleep', get7DayHistory());
})->name('sleep.page');

// 3. HYDRATION PAGE
Route::get('/hydration', function () {
    return view('hydration', get7DayHistory());
})->name('hydration.page');

// LOGGING ENDPOINTS
Route::post('/log/sleep', function (Request $request) {
    $request->validate(['hours_slept' => 'required|numeric', 'quality_rating' => 'required|integer']);
    SleepLog::create(['user_id' => 1, 'hours_slept' => $request->hours_slept, 'quality_rating' => $request->quality_rating]);
    return back()->with('success', 'Sleep logged successfully!');
})->name('log.sleep');

Route::post('/log/hydration', function (Request $request) {
    HydrationLog::create(['user_id' => 1, 'water_ml' => $request->water_ml ?? 0, 'caffeine_mg' => $request->caffeine_mg ?? 0]);
    return back()->with('success', 'Hydration logged successfully!');
})->name('log.hydration');

Route::post('/log/mood', function (Request $request) {
    session(['today_mood' => $request->mood_state]);
    return back()->with('success', 'Mood override saved!');
})->name('log.mood');

// ASKBEERUS AI ROUTES
Route::get('/ai/recovery-coach', [AICoachController::class, 'index'])->name('ai.coach');
Route::post('/ai/recovery-coach', [AICoachController::class, 'generatePlan'])->name('ai.coach.generate');
Route::get('/ai/anomaly-scanner', [AIAnomalyController::class, 'index'])->name('ai.anomaly');
Route::post('/ai/anomaly-scanner', [AIAnomalyController::class, 'runScan'])->name('ai.anomaly.scan');