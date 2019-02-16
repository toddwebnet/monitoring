<?php

namespace App\Console\Commands;

use App\Models\TopLog;
use Illuminate\Console\Command;

class Maintenance extends Command
{
    protected $signature = 'maintenance';

    public function handle()
    {
        TopLog::where('created_at', '<', date('Y-m-d', strtotime('30 days ago')))->delete();
    }
}
