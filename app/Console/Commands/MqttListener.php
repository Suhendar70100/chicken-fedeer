<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Jobs\StoreMotorStatusJob;
use App\Services\MqttService;

class MqttListener extends Command
{
    protected $signature = 'mqtt:listen';
    protected $description = 'Listen for MQTT messages and handle them';

    protected $mqttService;

    public function __construct(MqttService $mqttService)
    {
        parent::__construct();
        $this->mqttService = $mqttService;
    }

    public function handle()
    {
        try {
            // Subscribe to the topic
            $this->mqttService->subscribe('BnEsp32/MotorStatus1', function ($topic, $message) {
                $this->processMessage($topic, $message);
            });

            Log::info('Subscribed to topic: BnEsp32/MotorStatus1');
            $this->mqttService->loop();
        } catch (\Exception $e) {
            Log::error('Error in MQTT Listener: ' . $e->getMessage());
        }
    }

    private function processMessage(string $topic, string $message)
    {
        Log::info("Message received on topic '{$topic}': {$message}");

        $data = json_decode($message, true);
        if (is_array($data) && isset($data['status']) && isset($data['timestamp'])) {
            StoreMotorStatusJob::dispatch($data);
            Log::info('Dispatched job to store motor status: ' . json_encode($data));
        } else {
            Log::warning('Invalid data received: ' . $message);
        }
    }
}