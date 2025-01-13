<?php

namespace App\Jobs;

use App\Models\Log as ModelsLog;
use App\Models\MotorLog;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;

class StoreMotorStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;

    /**
     * Create a new job instance.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
{
    DB::beginTransaction();

    try {
        if (isset($this->data['status']) && isset($this->data['timestamp']) && isset($this->data['log_id'])) {
            $motorStatus = MotorLog::firstOrNew(['id' => 1]);
            $logStatus = ModelsLog::find($this->data['log_id']);

            $motorStatus->fill([
                'status' => $this->data['status'],
                'timestamp' => $this->data['timestamp'],
            ]);

            $logStatus->update([
                'log' => 0
            ]);

            $motorStatus->save();

            DB::commit();

            Log::info('Motor status updated successfully:', $this->data);
        } else {
            Log::warning('Invalid data received for motor status: ' . json_encode($this->data));
        }
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error processing MQTT message: ' . $e->getMessage(), ['data' => $this->data]);
    }
}

}