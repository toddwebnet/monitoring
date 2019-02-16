<?php

namespace App\Http\Controllers;

use App\Models\Ips;
use App\Models\TopLogs;
use App\Services\TopParser;
use Illuminate\Http\Request;

class TopController extends Controller
{

    public function top(Request $request)
    {
        $top = str_replace('|', "\n", request()->input('top'));
        /** @var TopParser $topParser */
        $topParser = app()->make(TopParser::class);
        $logentry = $topParser->parse($top);

        $ip = $request->ip();
        if(Ips::where('ip', $ip)->count() == 0){
            Ips::create(['ip' => $ip]);
        }

        $data = [
            'ip' => $ip,
            'top' => json_encode($logentry),
            'cpu_usage' => $logentry['cpu']['usage'],
            'mem_usage'=> $logentry['mem']['usage'],
            'swap_usage'=> $logentry['swap']['usage'],
        ];
        TopLogs::create($data);

        return $logentry;
    }
}