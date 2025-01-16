<?php

namespace App\Jobs;

use App\Models\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use App\Services\MqttService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log as LogFacade; // Import facade Log

class PublishLogFeedJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    protected $log;

    public function __construct(Log $log)
    {
        $this->log = $log;
    }

    public function handle(MqttService $mqttService)
    {
        LogFacade::info('Log data:', [
            'id' => $this->log->id,
            'berat' => $this->log->berat,
            'interval' => $this->log->interval,
            'log_status' => $this->log->log, 
            'time' => $this->log->time,
        ]);

        $feedData = [
            'feed' => $this->log->berat,
        ];

        $mqttService->publish('BnEsp32/Berat', json_encode($feedData));

        $intervalData = [
            'id' => $this->log->id,
            'interval' => $this->log->interval,
        ];

        $mqttService->publish('BnEsp32/Interval', json_encode($intervalData));
        DB::table('log_feed')
        ->where('log', 1)
        ->update(['log' => 0]);
        $this->log->update(['log' => 1]);
    }
}