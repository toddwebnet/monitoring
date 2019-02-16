<?php

namespace App\Services;

class TopParser
{

    public function parse($topDoc)
    {


        $blob = [];
        $rowKeys = [];
        foreach (explode("\n", $topDoc) as $lineNumber => $line) {

            if ($lineNumber == 0 && strpos($line, 'load average') !== false) {
                $blob = array_merge($blob, $this->handleLine1($line));
            } elseif ($lineNumber == 1 && strpos($line, 'Tasks') !== false) {
                $blob = array_merge($blob, $this->handleLine2($line));
            } elseif ($lineNumber == 2 && strpos($line, 'Cpu(s)') !== false) {
                $blob = array_merge($blob, $this->handleLine3($line));
            } elseif ($lineNumber == 3 && strpos($line, 'Mem') !== false) {
                $blob = array_merge($blob, $this->handleLine45($line, 'mem'));
            } elseif ($lineNumber == 4 && strpos($line, 'Swap') !== false) {
                $blob = array_merge($blob, $this->handleLine45($line, 'swap'));
            } elseif (
                strpos($line, 'PID') !== false &&
                strpos($line, 'USER') !== false &&
                strpos($line, 'PR') !== false &&
                strpos($line, 'CPU') !== false
            ) {
                $rowKeys = $this->getHeaderKeys($line);
                $blob['procs'] = [];
            } elseif (trim($line) != '' && array_key_exists('procs', $blob)) {
                $blob['procs'][] = $this->handleRow($line, $rowKeys);
            }

        }
        return $blob;
    }


    private function handleLine1($line)
    {
        $block1 = explode(',', $line);
        $block2 = explode('up', $block1[0]);
        return [
            'ts' => trim(str_replace("top - ", "", $block2[0])),
            'uptime' => $block2[1],
            'num_users' => trim(str_replace('users', '',
                str_replace('users', '', $block1[1])
            )),
            'avg_last_one' => trim(str_replace('load average: ', '', $block1[2])),
            'avg_last_five' => trim($block1[3]),
            'avg_last_fifteen' => trim($block1[4]),
        ];
    }

    private function handleLine2($line)
    {
        $line = trim(str_replace("Tasks: ", "", $line));

        $tasks = [];
        foreach (explode(",", $line) as $block) {
            $block2 = explode(' ', trim($block));
            $tasks[$block2[1]] = $block2[0];
        }
        return ['tasks' => $tasks];
    }

    private function handleLine3($line)
    {
        $line = trim(str_replace("%Cpu(s): ", "", $line));
        $keys = [
            'us' => 'user',
            'sy' => 'system',
            'ni' => 'nice',
            'id' => 'unused',
            'wa' => 'waiting_io',
            'hi' => 'hardware_interrupt',
            'si' => 'software_interrupt',
            'st' => 'stolen',
        ];

        $cpu = [];
        foreach (explode(",", $line) as $block1) {
            $block2 = explode(" ", trim($block1));
            if (array_key_exists($block2[1], $keys)) {
                $cpu[$keys[$block2[1]]] = $block2[0];
            }
        }
        $cpu['usage'] = round(100-$cpu['unused'], 2);
        return ['cpu' => $cpu];
    }

    private function handleLine45($line, $type)
    {
        $line = str_replace($type, "", strtolower($line));
        $block1 = explode(":", $line);
        switch (strtolower(trim($block1[0]))) {
            case "kib":
                $multiplyer = '000';
                break;
            case "mib":
                $multiplyer = '000000';
                break;
            case "gib":
                $multiplyer = '000000000';
                break;
            default:
                $multiplyer = '';
        }
        $keys = [
            'total' => 'total',
            'free' => 'free',
            'used' => 'used',
            'buff/cache' => 'buffer',
            'avail' => 'available'
        ];
        $vals = [];

        foreach (explode(',', str_replace('.', ',', $block1[1])) as $entry) {

            $block2 = explode(' ', trim($entry));
            $key = (array_key_exists($block2[1], $keys)) ? $keys[$block2[1]] : $block2[1];
            $vals[$key] = (int)($this->removeNonNumeric($block2[0]) . $multiplyer);
        }

        $vals['usage'] = round((int)$vals['used']/(int)$vals['total']*100, 2);

        return [$type => $vals];
    }

    private function getHeaderKeys($line)
    {

        $transKeys = [
            'pr' => 'priority',
            'ni' => 'nice',
            'virt' => 'vram',
            'res' => 'ram',
            'shr' => 'sram',
            's' => 'status',
            'command' => 'cmd'
        ];
        $line = strtolower(str_replace(['%', '+'], '', $line));
        $keys = [];
        foreach (explode(' ', $line) as $block) {
            if (trim($block) != '') {
                $keys[] = array_key_exists($block, $transKeys) ? $transKeys[$block] : $block;
            }
        }
        return $keys;

    }

    private function handleRow($line, $keys)
    {
        $counter = 0;
        $vals = [];
        foreach (explode(' ', $line) as $block) {
            if (trim($block) != '') {
                if (!array_key_exists($counter, $keys)) {
                    $counter--;
                    $vals[$keys[$counter]] .= ' ' . $block;
                } else {
                    $vals[$keys[$counter]] = trim($block);
                }
                $counter++;
            }
        }

        return $this->cleanValElements($vals);
    }

    private function cleanValElements($vals)
    {
        $typeKeys = [
            'pid' => 'int',
            'user' => 'string',
            'priority' => 'int',
            'nice' => 'int',
            'vram' => 'int',
            'sram' => 'int',
            'status' => 'string',
            'cpu' => 'float',
            'mem' => 'float',
            'time' => 'seconds',
            'cmd' => 'string'
        ];
        foreach($vals as $key=>&$val){
            if(array_key_exists($key, $typeKeys)){
                switch($typeKeys[$key]){
                    case "int":
                        $val = (int)$val;
                        break;
                    case "float":
                        $val = floatval($val);
                        break;
                    case "seconds":
                        $times = explode(':', $val);
                        $val = floatval($times[0])*60*60 +
                            (floatval($times[0])*60) +
                            (floatval($times[1]));

                        break;

                }
            }
        }
        return $vals;
    }

    private function removeNonNumeric($string)
    {
        $numbers = str_split("0123456789");
        $letters = str_split($string);
        $string = '';
        foreach ($letters as $letter) {
            if (in_array($letter, $numbers)) {
                $string .= $letter;
            }
        }
        return $string;
    }

}