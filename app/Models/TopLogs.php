<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TopLogs extends Model
{
    protected $fillable = [
        'ip',
        'top',
        'cpu_usage',
        'mem_usage',
        'swap_usage',
    ];
}