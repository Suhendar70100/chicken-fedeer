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

Schedule::command('log:reset-status')->dailyAt('00:00');

Schedule::call(function () {
    $now = Carbon::now();

    $logs = LogModel::where('log', 10) 
        ->get();

    $publishTimes = [];

    foreach ($logs as $log) {
        $logTime = Carbon::createFromTimeString($log->time);
        $interval = (int) $log->interval; 

        $publishTime = $logTime->addHours($interval)->subMinutes(3);

        $publishTimes[] = [
            'log' => $log,
            'publish_time' => $publishTime,
        ];
    }

    usort($publishTimes, function ($a, $b) {
        return $a['publish_time'] <=> $b['publish_time'];
    });

    foreach ($publishTimes as $item) {
        $log = $item['log'];
        $publishTime = $item['publish_time'];

        Log::info("Log ID: {$log->id}, Interval: {$interval}, Log Time: {$logTime}, Publish Time: {$publishTime}");

        if ($publishTime->isSameMinute($now) || $publishTime->isPast()) {
            PublishLogFeedJob::dispatch($log);
            Log::info("Log dengan ID {$log->id} dipublikasikan ke MQTT.");

            break;
        }
    }
})->everyMinute();