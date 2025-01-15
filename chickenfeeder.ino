#include <PubSubClient.h>
#include <WiFi.h>
#include <time.h>
#include <HTTPClient.h>
#include <ArduinoJson.h>

#define BUILTIN_LED 2

// Network credentials
const char* ssid = "";
const char* password = "";
const char* mqtt_server = "broker.hivemq.com";
const char* serverUrl = "https://rm786lts-8000.asse.devtunnels.ms/"; 

WiFiClient espClient;
PubSubClient client(espClient);

int motor1Pin1 = 27;
int motor2Pin1 = 26;
int enable1Pin = 14;

const int freq = 30000;
const int pwmChannel = 0;
const int resolution = 8;
int dutyCycle = 100;  

unsigned long motorLastRun = 0;
unsigned long motorRunDuration = 10000;  
unsigned long interval = 1000000;  
unsigned long second = 1000;
unsigned long currentMillis = millis();
bool motorRunning = false;          
unsigned long motorStartMillis = 0; 

int startHour = 6;  
int stopHour = 21; 
int localHour;

bool isMotorRunning() {
    return digitalRead(motor1Pin1) == HIGH || digitalRead(motor2Pin1) == HIGH;
}

void setup_wifi() {
    Serial.begin(115200);
    Serial.println();
    Serial.print("Connecting to ");
    Serial.println(ssid);

    WiFi.mode(WIFI_STA);
    WiFi.begin(ssid, password);

    while (WiFi.status() != WL_CONNECTED) {
        delay(500);
        Serial.print(".");
    }

    Serial.println("\nWiFi connected");
    Serial.println("IP address: ");
    Serial.println(WiFi.localIP());
}

void setup_time() {
    configTime(7 * 3600, 0, "pool.ntp.org", "time.nist.gov"); 
    Serial.println("Time synchronized");
}

bool hasNewSchedule = false; 

void callback(char* topic, byte* payload, unsigned int length) {
    StaticJsonDocument<200> doc;
    DeserializationError error = deserializeJson(doc, payload, length);
    if (error) {
        Serial.print("Failed to parse JSON: ");
        Serial.println(error.f_str());
        return;
    }

    if (strcmp(topic, "BnEsp32/Berat") == 0) {
        int value = doc["feed"].as<int>();
        Serial.print("Received weight value: ");
        Serial.println(value);
        if (value >= 1 && value <= 10) {
            motorRunDuration = value * 10 * second; 
            hasNewSchedule = true; 
            Serial.print("Updated motorRunDuration to: ");
            Serial.println(motorRunDuration / 1000); 
        } else {
            Serial.println("Invalid weight value received");
        }
    } else if (strcmp(topic, "BnEsp32/Interval") == 0) {
        int intervalValue = doc["interval"].as<int>();
        if (intervalValue >= 1 && intervalValue <= 6) {
            interval = intervalValue * 60 * 60 * second; 
            motorLastRun = currentMillis - interval; 
            hasNewSchedule = true; 
            Serial.print("Interval updated to: ");
            Serial.println(interval / 1000); 
        }
    }else if (strcmp(topic, "BnEsp32/MotorControl") == 0) {
        int status = doc["status"].as<int>();
        if (status == 1) {
            startMotor();
        } else if (status == 0) {
            stopMotor();
        }
        String timestamp = doc["timestamp"].as<String>();
        Serial.println("Timestamp: " + timestamp);
    }
}

void reconnect() {
    while (!client.connected()) {
        Serial.print("Attempting MQTT connection...");
        String clientId = "ESP32Client-";
        clientId += String(random(0xffff), HEX);
        if (client.connect(clientId.c_str())) {
            Serial.println("connected");
            client.publish("BnEsp32/Message", "Feeded!");
            client.subscribe("BnEsp32/MotorControl");
            client.subscribe("BnEsp32/Berat");
            client.subscribe("BnEsp32/Interval");
        } else {
            Serial.print("failed, rc=");
            Serial.print(client.state());
            Serial.println(" try again in 5 seconds");
            delay(5000);
        }
    }
}

void setup() {
    setup_wifi();
    client.setServer(mqtt_server, 1883);
    client.setCallback(callback);

    setup_time();

    pinMode(motor1Pin1, OUTPUT);
    pinMode(motor2Pin1, OUTPUT);
    pinMode(enable1Pin, OUTPUT);

    digitalWrite(motor1Pin1, LOW);
    digitalWrite(motor2Pin1, LOW);
    ledcWrite(pwmChannel, 0);

    ledcSetup(pwmChannel, freq, resolution); 
    ledcAttachPin(enable1Pin, pwmChannel);  
}

void startMotor() {
    digitalWrite(motor1Pin1, HIGH);
    digitalWrite(motor2Pin1, HIGH);
    ledcWrite(pwmChannel, dutyCycle);

    struct tm timeinfo;
    if (getLocalTime(&timeinfo)) {
        char timestamp[30];
        strftime(timestamp, sizeof(timestamp), "%Y-%m-%d %H:%M:%S", &timeinfo);

        StaticJsonDocument<200> jsonDoc;
        jsonDoc["status"] = 1;
        jsonDoc["timestamp"] = timestamp;
        char buffer[200];
        serializeJson(jsonDoc, buffer);

        client.publish("BnEsp32/MotorStatus", buffer);
        Serial.println("Motors started with timestamp: " + String(timestamp));
    } else {
        Serial.println("Failed to get local time for motor start.");
    }
}

void stopMotor() {
    digitalWrite(motor1Pin1, LOW);
    digitalWrite(motor2Pin1, LOW);
    ledcWrite(pwmChannel, 0);

    struct tm timeinfo;
    if (getLocalTime(&timeinfo)) {
        char timestamp[30];
        strftime(timestamp, sizeof(timestamp), "%Y-%m-%d %H:%M:%S", &timeinfo);

        StaticJsonDocument<200> jsonDoc;
        jsonDoc["status"] = 0;
        jsonDoc["timestamp"] = timestamp;
        char buffer[200];
        serializeJson(jsonDoc, buffer);

        client.publish("BnEsp32/MotorStatus", buffer);
        Serial.println("Motors stopped with timestamp: " + String(timestamp));
    } else {
        Serial.println("Failed to get local time for motor stop.");
    }
}

void loop() {
    currentMillis = millis();

    if (WiFi.status() != WL_CONNECTED) {
        Serial.println("WiFi disconnected. Reconnecting...");
        setup_wifi();
    }

    if (!client.connected()) {
        reconnect();
    }
    client.loop();

    static int lastPrintedHour = -1;

    struct tm timeinfo;
    if (getLocalTime(&timeinfo)) {
        localHour = timeinfo.tm_hour;

        if (localHour != lastPrintedHour) {
            lastPrintedHour = localHour;
            Serial.println("Local Hour: " + String(localHour));
        }
    }

    if (localHour >= startHour && localHour < stopHour) {
        if (!motorRunning && (currentMillis - motorLastRun >= interval)) {
            motorRunning = true;
            motorStartMillis = currentMillis;
            startMotor();
            Serial.println("Motor started based on interval.");
        }

        if (motorRunning && (currentMillis - motorStartMillis >= motorRunDuration)) {
            motorRunning = false;
            motorLastRun = currentMillis; 
            stopMotor();
            Serial.println("Motor stopped after duration.");
        }
    } else {
        if (motorRunning) {
            motorRunning = false;
            stopMotor();
        }

        static unsigned long lastCheck = 0;
        if (currentMillis - lastCheck > 5000) {
            Serial.println("Motor is inactive due to time restrictions.");
            lastCheck = currentMillis;
        }
    }
}

