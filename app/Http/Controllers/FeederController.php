<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\Item;
use App\Models\MotorLog;
use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Services\MqttService;
use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\Facades\MQTT;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpMqtt\Client\Exceptions\MqttClientException;

class FeederController extends Controller
{
    protected $mqttService;

    public function __construct(MqttService $mqttService)
    {
        $this->mqttService = $mqttService;
    }

    public function feed(Request $request)
    {
        try {
            $dataToUpdate = [
                'status' => 1,
                'timestamp' => Carbon::now(),
            ];

            $motorStatus = MotorLog::first();
            if ($motorStatus) {
                $motorStatus->update($dataToUpdate);
            } else {
                $motorStatus = MotorLog::create($dataToUpdate);
            }

            Log::info('MotorLog created or updated:', $motorStatus->toArray());

            $this->mqttService->publish(
                'BnEsp32/MotorControl',
                json_encode($motorStatus->toArray())
            );

            session()->forget('duration');
            return back()->with('success', 'Permintaan untuk memberikan makanan telah dikirim.');
        } catch (\Exception $e) {
            Log::error('Error in FeederController feed: ' . $e->getMessage());
            return response()->json(['message' => 'Data gagal ditambahkan: ' . $e->getMessage()], 500);
        }
    }

    public function postFeed(Request $r)
    {

        $maxID = Schedule::max('id');
        $insertTable = new Schedule;

        $insertTable->id = $maxID + 1;
        $insertTable->frequency = $r->TIME;
        $insertTable->age = $r->AGE;
        $insertTable->pakan = $r->FEED;
        $insertTable->start_date = Carbon::parse(now())->format('Y-m-d');
        $insertTable->end_date = Carbon::parse(now()->addWeek())->format('Y-m-d');
        $insertTable->exactTime = date('H:i:s');

        $insertTable->save();

        return response()->json(['message' => 'Record Successfully Recorded']);
    }

    public function stop()
    {
        try {
            $motorStatus = MotorLog::first();

            if ($motorStatus && $motorStatus->status == 1) {

                if ($motorStatus->updated_at->isPast()) {
                    $durationInSeconds = $motorStatus->updated_at->diffInSeconds(Carbon::now());
                } else {
                    $durationInSeconds = 0;
                }

                $duration = $this->formatDuration($durationInSeconds);

                $motorStatus->update([
                    'status' => 0,
                    'timestamp' => Carbon::now(),
                ]);

                Log::info('Motor stopped. Duration: ' . $durationInSeconds . ' seconds');
            } else {
                return back()->with('info', 'Feeder sudah dalam keadaan mati.');
            }

            $this->mqttService->publish(
                'BnEsp32/MotorControl',
                json_encode($motorStatus->toArray())
            );

            $duration = $this->formatDuration(round($durationInSeconds));
            session(['duration' => $duration]);
            return back()->with([
                'success' => 'Proses pemberian makanan telah dihentikan.',
            ]);
        } catch (\Exception $e) {
            Log::error('Error in FeederController stop: ' . $e->getMessage());
            return response()->json(['message' => 'Data gagal ditambahkan: ' . $e->getMessage()], 500);
        }
    }



    private function formatDuration($seconds)
    {
        if ($seconds < 60) {
            return $seconds . ' Detik';
        } elseif ($seconds < 3600) {
            return floor($seconds / 60) . ' Menit ' . ($seconds % 60) . ' Detik';
        } else {
            return floor($seconds / 3600) . ' Jam ' . floor(($seconds % 3600) / 60) . ' Menit';
        }
    }

    // public function feedNow()
    // {
    //     $this->feed();
    //     // Schedule motor to turn off after specific duration
    //     $durationInSeconds = 10; // e.g., 10 seconds

    //     sleep($durationInSeconds);
    //     $this->stop();

    //     return response()->json(['message' => 'Feeding cycle completed']);
    // }

    public function scheduler(Request $r)
    {
        // Insert new log entry and get the last inserted ID
        $lastInsertId = DB::table('log_feed')->insertGetId([
            'date' => now()->format('Y-m-d'),          // Current date
            'time' => now()->format('H:i:s'),          // Current time
            'log' => 10,                               // Example log value
            'umur' => $r->AGE,                         // Chick age
            'berat' => $r->FEED,                       // Feed amount
            'interval' => $r->TIME,                    // Time interval
        ]);
    
        DB::table('log_feed')
            ->where('log', '!=', 1)
            ->where('id', '!=', $lastInsertId)
            ->update(['log' => 0]);

        // $feedData = [
        //     'feed' => $r->FEED,
        // ];

        // $this->mqttService->publish(
        //     'BnEsp32/Berat',
        //     json_encode($feedData)
        // );

        // $intervalData = [
        //     'interval' => $r->TIME,
        // ];

        // $this->mqttService->publish(
        //     'BnEsp32/Interval',
        //     json_encode($intervalData)
        // );

        return response()->json(['message' => 'Data saved successfully!']);
    }
}
