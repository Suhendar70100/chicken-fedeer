<?php

namespace App\Jobs;

use App\Models\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log as LogFacade;
use App\Services\MqttService; // Import the MqttService

class PublishLogFeedJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    protected $log;
    protected $mqttService;

    public function __construct(Log $log, MqttService $mqttService)
    {
        $this->log = $log;
        $this->mqttService = $mqttService; // Inject MqttService
    }

    public function handle()
    {
        LogFacade::info('Log data:', [
            'id' => $this->log->id,
            'berat' => $this->log->berat,
            'interval' => $this->log->interval,
            'log_status' => $this->log->log, 
            'time' => $this->log->time,
        ]);

        // Prepare feed data
        $feedData = [
            'feed' => $this->log->berat,
        ];
        
        // Publish feed data using MqttService
        $this->mqttService->publish('BnEsp32/Berat', json_encode($feedData));

        // Prepare interval data
        $intervalData = [
            'id' => $this->log->id,
            'interval' => $this->log->interval,
        ];
        
        // Publish interval data using MqttService
        $this->mqttService->publish('BnEsp32/Interval', json_encode($intervalData));

        // Update log status
        $this->log->update(['log' => 1]);
    }
}