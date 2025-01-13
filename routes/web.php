<?php

use App\Models\Log;
use App\Models\MotorLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FeederController;
use App\Http\Controllers\StorageController;
use App\Http\Controllers\Api\MotorLogController;


Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/homepage', function () {
    $log = DB::table('log_feed')
        ->orderBy('id', 'desc')
        ->Paginate(12);

    $startDate = date('Y-m-d', strtotime('-6 days'));
    $endDate = date('Y-m-d');

    $chartData = DB::table('log_feed')
        ->select(DB::raw("
            DATE(date) as activity_date, 
            CASE 
                WHEN TIME_FORMAT(time, '%H:%i') >= '00:00' AND TIME_FORMAT(time, '%H:%i') < '04:00' THEN '00:00 - 03:59'
                WHEN TIME_FORMAT(time, '%H:%i') >= '04:00' AND TIME_FORMAT(time, '%H:%i') < '08:00' THEN '04:00 - 08:59'
                WHEN TIME_FORMAT(time, '%H:%i') >= '08:00' AND TIME_FORMAT(time, '%H:%i') < '12:00' THEN '08:00 - 11:59'
                WHEN TIME_FORMAT(time, '%H:%i') >= '12:00' AND TIME_FORMAT(time, '%H:%i') < '16:00' THEN '12:00 - 15:59'
                WHEN TIME_FORMAT(time, '%H:%i') >= '16:00' AND TIME_FORMAT(time, '%H:%i') < '20:00' THEN '16:00 - 19:59'
                ELSE '20:00 - 23:59'
            END AS time_group,
            COUNT(*) as activity_count
        "))
        ->whereBetween('date', [$startDate, $endDate])
        ->where('log', 1)
        ->groupBy('activity_date', 'time_group')
        ->orderBy('activity_date', 'asc')
        ->orderBy('time_group', 'asc')
        ->get()
        ->map(function ($item) {
            return [
                'date' => $item->activity_date,
                'time' => $item->time_group,
                'count' => $item->activity_count
            ];
        });

    return view('welcome', compact('log', 'chartData'));
});

Route::get('/feed', [FeederController::class, 'feed'])->name('feed');
Route::get('/stop', [FeederController::class, 'stop'])->name('stop');
Route::get('/motor/feed', [FeederController::class, 'feedNow']);
Route::post('/postFeed', [FeederController::class, 'postFeed']);
Route::get('/FeedingPage', function () {
    return view('FeedingPage');
});
Route::get('/StorageAmount', function () {
    return view('StorageAmount');
});
Route::get('/api', function () {
    return view('connect');
});
Route::post('/scheduler', [FeederController::class, 'scheduler'])->name('scheduler');
Route::post('/storage', [StorageController::class, 'storage'])->name('storage');
Route::post('/log_motor_status', [MotorLogController::class, 'store']);


Route::get('/status', function () {
    $status = MotorLog::find(1);
    return response()->json($status);
});

Route::get('/logs', function () {
    $logs = Log::all();
    return response()->json($logs);
});