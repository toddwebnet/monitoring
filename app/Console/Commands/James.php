<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class James extends Command
{
    protected $signature = 'james';

    public function handle()
    {
        $data = $this->testData();

        $blob = [];
        foreach (explode("\n", $data) as $lineNumber => $line) {

            if ($lineNumber == 0 && strpos($line, 'load average') !== false) {
                $blob = array_merge($blob, $this->handleLine1($line));
            } elseif ($lineNumber == 1 && strpos($line, 'Tasks') !== false) {
                $blob = array_merge($blob, $this->handleLine2($line));
            } elseif ($lineNumber == 2 && strpos($line, 'Cpu(s)') !== false) {
                $blob = array_merge($blob, $this->handleLine3($line));
            } elseif ($lineNumber == 3 && strpos($line, 'Mem') !== false) {
$this->line($line);
            }
        }
        // dump($blob);
    }

    public function handleLine1($line)
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

    public function handleLine2($line)
    {
        $line = trim(str_replace("Tasks: ", "", $line));

        $tasks = [];
        foreach (explode(",", $line) as $block) {
            $block2 = explode(' ', trim($block));
            $tasks[$block2[1]] = $block2[0];
        }
        return ['tasks' => $tasks];
    }

    public function handleLine3($line)
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
        return ['cpu' => $cpu];
    }


    private function removeNonNumeric($string){
        $numbers = "0123456789";
    }
    private function testData()
    {
        return "top - 02:30:26 up 27 min,  1 user,  load average: 0.11, 0.14, 0.10
Tasks:  65 total,   1 running,  40 sleeping,   0 stopped,   0 zombie
%Cpu(s):  9.9 us,  2.3 sy,  0.0 ni, 87.2 id,  0.5 wa,  0.0 hi,  0.1 si,  0.0 st
KiB Mem :   443904 total,   322588 free,    28876 used,    92440 buff/cache
KiB Swap:  2097148 total,  2097148 free,        0 used.   355088 avail Mem

  PID USER      PR  NI    VIRT    RES    SHR S %CPU %MEM     TIME+ COMMAND
 1205 jtodd     20   0    8100   3224   2828 R 14.3  0.7   0:00.11 top
    1 root      20   0   27040   6036   4864 S  0.0  1.4   0:03.72 systemd
    2 root      20   0       0      0      0 S  0.0  0.0   0:00.00 kthreadd
    4 root       0 -20       0      0      0 I  0.0  0.0   0:00.00 kworker/0:0H
    6 root       0 -20       0      0      0 I  0.0  0.0   0:00.00 mm_percpu_wq
    7 root      20   0       0      0      0 S  0.0  0.0   0:00.64 ksoftirqd/0
    8 root      20   0       0      0      0 S  0.0  0.0   0:00.01 kdevtmpfs
    9 root       0 -20       0      0      0 I  0.0  0.0   0:00.00 netns
   11 root      20   0       0      0      0 S  0.0  0.0   0:00.00 khungtaskd
   12 root      20   0       0      0      0 S  0.0  0.0   0:00.00 oom_reaper
   13 root       0 -20       0      0      0 I  0.0  0.0   0:00.00 writeback
   14 root      20   0       0      0      0 S  0.0  0.0   0:00.00 kcompactd0
   15 root       0 -20       0      0      0 I  0.0  0.0   0:00.00 crypto
   16 root       0 -20       0      0      0 I  0.0  0.0   0:00.00 kblockd
   17 root       0 -20       0      0      0 I  0.0  0.0   0:00.00 watchdogd
   18 root       0 -20       0      0      0 I  0.0  0.0   0:00.00 rpciod
   19 root       0 -20       0      0      0 I  0.0  0.0   0:00.00 xprtiod
   22 root      20   0       0      0      0 S  0.0  0.0   0:00.00 kswapd0
   23 root       0 -20       0      0      0 I  0.0  0.0   0:00.00 nfsiod
   33 root       0 -20       0      0      0 I  0.0  0.0   0:00.00 kthrotld
   34 root       0 -20       0      0      0 I  0.0  0.0   0:00.00 iscsi_eh
   35 root       0 -20       0      0      0 I  0.0  0.0   0:00.00 dwc_otg
   36 root       0 -20       0      0      0 I  0.0  0.0   0:00.00 DWC Notificatio
   37 root       1 -19       0      0      0 S  0.0  0.0   0:00.00 vchiq-slot/0
   38 root       1 -19       0      0      0 S  0.0  0.0   0:00.00 vchiq-recy/0
   39 root       0 -20       0      0      0 S  0.0  0.0   0:00.00 vchiq-sync/0
   40 root      20   0       0      0      0 S  0.0  0.0   0:00.00 vchiq-keep/0
   41 root      10 -10       0      0      0 S  0.0  0.0   0:00.00 SMIO
   44 root      20   0       0      0      0 S  0.0  0.0   0:01.12 mmcqd/0
   45 root      20   0       0      0      0 S  0.0  0.0   0:00.08 jbd2/mmcblk0p2-
   46 root       0 -20       0      0      0 I  0.0  0.0   0:00.00 ext4-rsv-conver
   47 root       0 -20       0      0      0 I  0.0  0.0   0:00.00 ipv6_addrconf
   83 root      20   0    9612   4268   3872 S  0.0  1.0   0:01.48 systemd-journal
  102 root      20   0   14324   3096   2500 S  0.0  0.7   0:00.75 systemd-udevd
  108 root      20   0       0      0      0 I  0.0  0.0   0:00.14 kworker/u2:2
  177 systemd+  20   0   17260   4052   3604 S  0.0  0.9   0:00.25 systemd-timesyn
  207 avahi     20   0    6384   3136   2788 S  0.0  0.7   0:00.82 avahi-daemon
  208 message+  20   0    6480   3472   3068 S  0.0  0.8   0:00.41 dbus-daemon
  212 avahi     20   0    6384   1556   1252 S  0.0  0.4   0:00.00 avahi-daemon
  217 root      20   0    5316   2568   2324 S  0.0  0.6   0:00.08 cron
  219 root      20   0   22848   2900   2236 S  0.0  0.7   0:00.23 rsyslogd
  222 root      20   0    7368   4288   3884 S  0.0  1.0   0:00.20 systemd-logind
  224 nobody    20   0    5284   2564   2324 S  0.0  0.6   0:00.05 thd
  229 root       0 -20       0      0      0 I  0.0  0.0   0:00.00 cfg80211
  281 root       0 -20       0      0      0 I  0.0  0.0   0:00.00 kworker/0:1H
  341 root      20   0    2868   1544   1208 S  0.0  0.3   0:00.13 dhcpcd
  366 root      20   0   10188   5332   4784 S  0.0  1.2   0:00.11 sshd
  367 root      20   0    4180   1692   1556 S  0.0  0.4   0:00.03 agetty
  368 root      20   0    3956   2004   1872 S  0.0  0.5   0:00.02 agetty
  369 root      20   0  123456  21824  18724 S  0.0  4.9   0:00.52 apache2
  370 www-data  20   0  123480   5232   2120 S  0.0  1.2   0:00.00 apache2
  371 www-data  20   0  123480   5232   2120 S  0.0  1.2   0:00.00 apache2
  372 www-data  20   0  123480   5232   2120 S  0.0  1.2   0:00.00 apache2
  373 www-data  20   0  123480   5232   2120 S  0.0  1.2   0:00.00 apache2
  374 www-data  20   0  123480   5232   2120 S  0.0  1.2   0:00.00 apache2
  452 root      20   0   11508   5708   4984 S  0.0  1.3   0:00.55 sshd
  460 jtodd     20   0    9652   5512   4832 S  0.0  1.2   0:00.27 systemd
  463 jtodd     20   0   28704   2880   1592 S  0.0  0.6   0:00.00 (sd-pam)
  470 jtodd     20   0   11640   4136   3372 S  0.0  0.9   0:00.95 sshd
  473 jtodd     20   0    6824   4916   2904 S  0.0  1.1   0:03.84 bash
  586 root      20   0       0      0      0 I  0.0  0.0   0:00.07 kworker/u2:0
  972 root      20   0       0      0      0 I  0.0  0.0   0:00.00 kworker/0:1
 1094 root      20   0       0      0      0 I  0.0  0.0   0:00.33 kworker/0:2
 1122 root      20   0       0      0      0 I  0.0  0.0   0:00.02 kworker/u2:1
 1204 root      20   0       0      0      0 I  0.0  0.0   0:00.00 kworker/0:0
 ";
    }
}
