<?php

namespace App\Console\Commands;

use App\Services\TopParser;
use Illuminate\Console\Command;

class James extends Command
{
    protected $signature = 'james';

    public function handle()
    {
     $topParser = new TopParser();
     dump($topParser->parse($this->testData()));
    }


}
