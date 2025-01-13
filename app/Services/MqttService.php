<?php

namespace App\Services;

use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;
use Illuminate\Support\Facades\Log;

class MqttService
{
    protected $client;
    protected $host;
    protected $port;
    protected $clientId;
    protected $username;
    protected $password;

    public function __construct()
    {
        $this->host = env('MQTT_HOST', 'broker.hivemq.com');
        $this->port = env('MQTT_PORT', 1883);
        $this->clientId = env('MQTT_CLIENT_ID', 'laravel-client-' . uniqid());
        $this->username = env('MQTT_USERNAME', '');
        $this->password = env('MQTT_PASSWORD', '');

        $this->client = new MqttClient($this->host, $this->port, $this->clientId);
    }

    public function connect()
{
    $connectionSettings = (new ConnectionSettings)
        ->setKeepAliveInterval(120)
        ->setConnectTimeout(10)
        ->setMaxReconnectAttempts(10)
        ->setDelayBetweenReconnectAttempts(5000);

    if (!empty($this->username)) {
        $connectionSettings->setUsername($this->username);
        if (!empty($this->password)) {
            $connectionSettings->setPassword($this->password);
        }
    }

    try {
        $this->client->connect($connectionSettings, true);
        Log::info('Connected to MQTT broker.');
    } catch (\Exception $e) {
        Log::error('Failed to connect to MQTT broker: ' . $e->getMessage());
        throw $e;
    }
}


    public function publish(string $topic, string $message, int $qos = 1, bool $retain = false)
    {
        try {
            if (!$this->client->isConnected()) {
                $this->connect();
            }
            $this->client->publish($topic, $message, $qos, $retain);
            // $this->client->disconnect();
            Log::info("Published message to topic {$topic}: {$message}");
        } catch (\Exception $e) {
            Log::error("Failed to publish message: " . $e->getMessage());
            throw $e;
        }
    }

    public function getClient(): MqttClient
    {
        return $this->client;
    }
}