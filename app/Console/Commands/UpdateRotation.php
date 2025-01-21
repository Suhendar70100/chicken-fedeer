<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Log as LogFeed;
use Illuminate\Support\Facades\Log;

class UpdateRotation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rotation:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update rotation based on time and interval for logs with log=1';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $currentTime = now();

        // Ambil semua data dengan log = 1
        $logs = LogFeed::where('log', 1)->get();

        foreach ($logs as $log) {
            $nextRotationTime = $this->calculateNextRotationTime($log->time, $log->interval, $log->rotasi);

            if ($currentTime->greaterThanOrEqualTo($nextRotationTime)) {
                $log->rotasi += 1; // Update nilai rotasi
                $log->save();

                Log::info("Updated rotation for log ID {$log->id} to {$log->rotasi}");
            }
        }
    }

    /**
     * Menghitung waktu rotasi berikutnya berdasarkan waktu awal, interval, dan rotasi saat ini.
     *
     * @param string $startTime
     * @param string $interval
     * @param int $currentRotation
     * @return \Carbon\Carbon
     */
    private function calculateNextRotationTime(string $startTime, string $interval, int $currentRotation)
    {
        $intervalMinutes = intval($interval) * 60; // Interval dalam menit
        return now()->setTimeFromTimeString($startTime)->addMinutes($intervalMinutes * ($currentRotation + 1));
    }
}
