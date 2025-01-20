<?php

namespace App\Services;

use PhpMqtt\Client\Facades\MQTT;
use Illuminate\Support\Facades\Log;

class MqttService
{
    protected $client;

    public function __construct()
    {
        $this->client = MQTT::connection();
    }

    public function publish(string $topic, string $message, int $qos = 1, bool $retain = false)
    {
        try {
            if (!$this->client->isConnected()) {
                $this->client = MQTT::connection(); // Reconnect if not connected
            }
            $this->client->publish($topic, $message, $qos, $retain);
            Log::info("Published message to topic {$topic}: {$message}");
        } catch (\Exception $e) {
            Log::error("Failed to publish message: " . $e->getMessage());
            throw $e;
        }
    }

    public function subscribe(string $topic, callable $callback)
    {
        try {
            if (!$this->client->isConnected()) {
                $this->client = MQTT::connection(); // Reconnect if not connected
            }
            $this->client->subscribe($topic, $callback);
            Log::info("Subscribed to topic: {$topic}");
        } catch (\Exception $e) {
            Log::error("Failed to subscribe to topic: " . $e->getMessage());
        }
    }

    public function loop()
    {
        while (true) {
            try {
                if (!$this->client->isConnected()) {
                    $this->client = MQTT::connection(); // Reconnect if not connected
                }
                $this->client->loop();
            } catch (\Exception $e) {
                Log::error("Error in MQTT loop: " . $e->getMessage());
                sleep(1); // Wait before retrying
            }
            sleep(1); // Adjust the sleep time as necessary
        }
    }
}