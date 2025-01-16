<?php

use Carbon\Carbon;
use App\Models\Log as LogModel;
use Illuminate\Support\Facades\Log;
use App\Jobs\PublishLogFeedJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::command('log:reset-status')->dailyAt('18:00');

Schedule::call(function () {
    $now = Carbon::now();
    $log = LogModel::where('log', 10)->first();

    if ($log) {
        $logTime = Carbon::createFromTimeString($log->time);
        $interval = (int) $log->interval; 

        $publishTime = $logTime->addHours($interval)->subMinutes(3);

        Log::info("Log ID: {$log->id}, Interval: {$interval}, Log Time: {$logTime}, Publish Time: {$publishTime}");

        if ($publishTime->isSameMinute($now) || $publishTime->isPast()) {
            PublishLogFeedJob::dispatch($log);
            Log::info("Log dengan ID {$log->id} dipublikasikan ke MQTT.");
        }
    } else {
        Log::info("Tidak ada log dengan ID 10.");
    }
})->everyMinute();