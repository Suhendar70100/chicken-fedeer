<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Log as LogModel;

class ResetLogStatus extends Command
{
    protected $signature = 'log:reset-status';
    protected $description = 'Reset all log statuses to 10 at the start of a new day';

    public function handle()
    {
        LogModel::query()->update(['log' => 0]);

        $this->info('All log statuses have been reset to 0.');
    }
}