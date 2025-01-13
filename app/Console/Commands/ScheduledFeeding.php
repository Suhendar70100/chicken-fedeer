<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\FeederController;

class ScheduledFeeding extends Command
{
    protected $signature = 'feeding:scheduled';
    protected $description = 'Perform scheduled feeding';

    protected $motorController;

    public function __construct(FeederController $motorController)
    {
        parent::__construct();
        $this->FeederController = $motorController;
    }
    public function handle()
    {
        $this->FeederController->feedNow();
        $this->info('Scheduled feeding completed successfully.');
    }
}
