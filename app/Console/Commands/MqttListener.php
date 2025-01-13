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
        $client = $this->mqttService->getClient();
        $this->mqttService->connect();

        $client->subscribe('BnEsp32/MotorStatus', function (string $topic, string $message) {
            $this->processMessage($topic, $message);
        }, 1);

        Log::info('Subscribed to topic: BnEsp32/MotorStatus');

        while (true) {
            try {
                if (!$client->isConnected()) {
                    Log::warning('Connection lost. Attempting to reconnect...');
                    $this->mqttService->connect();
                }
        
                $client->loop(true, 1000);
                Log::info('MQTT loop running...');
            } catch (\Exception $e) {
                Log::error('Error in MQTT loop: ' . $e->getMessage());
                $this->mqttService->connect();
                sleep(1); 
            }
        }
        
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