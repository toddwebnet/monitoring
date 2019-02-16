<?php

namespace Tests\Unit\Services;

use App\Services\TopParser;
use Tests\TestCase;

class TopParserTest extends TestCase
{
    public function testParse()
    {
        $topParser = new TopParser();

        $expectedResults = json_decode($this->getResultData(), true);
        $results = $topParser->parse($this->getTestData());
        foreach ($expectedResults as $key => $value) {
            $this->assertTrue(array_key_exists($key, $results));
            if (!is_array($value)) {
                $this->assertEquals($value, $results[$key]);
            } else {
                if (in_array($key, ['tasks', 'cpu', 'mem', 'swap'])) {
                    foreach ($value as $subKey => $subValue) {
                        $this->assertTrue(array_key_exists($subKey, $results[$key]));
                        $this->assertEquals($subValue, $results[$key][$subKey]);
                    }
                } else {
                    $this->assertEquals('procs', $key);
                    foreach ($value as $index => $collection) {
                        foreach ($collection as $subKey => $subValue) {
                            $this->assertTrue(array_key_exists($subKey, $results[$key][$index]));
                            $this->assertEquals($subValue, $results[$key][$index][$subKey]);
                        }
                    }
                }
            }
        }

    }

    private function getTestData()
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

    private function getResultData()
    {
        return "{\"ts\":\"02:30:26\",\"uptime\":\" 27 min\",\"num_users\":\"1 user\",\"avg_last_one\":\"0.11\",\"avg_last_five\":\"0.14\",\"avg_last_fifteen\":\"0.10\",\"tasks\":{\"total\":\"65\",\"running\":\"1\",\"sleeping\":\"40\",\"stopped\":\"0\",\"zombie\":\"0\"},\"cpu\":{\"user\":\"9.9\",\"system\":\"2.3\",\"nice\":\"0.0\",\"unused\":\"87.2\",\"waiting_io\":\"0.5\",\"hardware_interrupt\":\"0.0\",\"software_interrupt\":\"0.1\",\"stolen\":\"0.0\",\"usage\":12.8},\"mem\":{\"total\":443904000,\"free\":322588000,\"used\":28876000,\"buffer\":92440000,\"usage\":6.51},\"swap\":{\"total\":2097148000,\"free\":2097148000,\"used\":0,\"available\":355088000,\"usage\":0},\"procs\":[{\"pid\":1205,\"user\":\"jtodd\",\"priority\":20,\"nice\":0,\"vram\":8100,\"ram\":\"3224\",\"sram\":2828,\"status\":\"R\",\"cpu\":14.3,\"mem\":0.7,\"time\":0.11,\"cmd\":\"top\"},{\"pid\":1,\"user\":\"root\",\"priority\":20,\"nice\":0,\"vram\":27040,\"ram\":\"6036\",\"sram\":4864,\"status\":\"S\",\"cpu\":0,\"mem\":1.4,\"time\":3.72,\"cmd\":\"systemd\"},{\"pid\":2,\"user\":\"root\",\"priority\":20,\"nice\":0,\"vram\":0,\"ram\":\"0\",\"sram\":0,\"status\":\"S\",\"cpu\":0,\"mem\":0,\"time\":0,\"cmd\":\"kthreadd\"},{\"pid\":4,\"user\":\"root\",\"priority\":0,\"nice\":-20,\"vram\":0,\"ram\":\"0\",\"sram\":0,\"status\":\"I\",\"cpu\":0,\"mem\":0,\"time\":0,\"cmd\":\"kworker\/0:0H\"},{\"pid\":6,\"user\":\"root\",\"priority\":0,\"nice\":-20,\"vram\":0,\"ram\":\"0\",\"sram\":0,\"status\":\"I\",\"cpu\":0,\"mem\":0,\"time\":0,\"cmd\":\"mm_percpu_wq\"},{\"pid\":7,\"user\":\"root\",\"priority\":20,\"nice\":0,\"vram\":0,\"ram\":\"0\",\"sram\":0,\"status\":\"S\",\"cpu\":0,\"mem\":0,\"time\":0.64,\"cmd\":\"ksoftirqd\/0\"},{\"pid\":8,\"user\":\"root\",\"priority\":20,\"nice\":0,\"vram\":0,\"ram\":\"0\",\"sram\":0,\"status\":\"S\",\"cpu\":0,\"mem\":0,\"time\":0.01,\"cmd\":\"kdevtmpfs\"},{\"pid\":9,\"user\":\"root\",\"priority\":0,\"nice\":-20,\"vram\":0,\"ram\":\"0\",\"sram\":0,\"status\":\"I\",\"cpu\":0,\"mem\":0,\"time\":0,\"cmd\":\"netns\"},{\"pid\":11,\"user\":\"root\",\"priority\":20,\"nice\":0,\"vram\":0,\"ram\":\"0\",\"sram\":0,\"status\":\"S\",\"cpu\":0,\"mem\":0,\"time\":0,\"cmd\":\"khungtaskd\"},{\"pid\":12,\"user\":\"root\",\"priority\":20,\"nice\":0,\"vram\":0,\"ram\":\"0\",\"sram\":0,\"status\":\"S\",\"cpu\":0,\"mem\":0,\"time\":0,\"cmd\":\"oom_reaper\"},{\"pid\":13,\"user\":\"root\",\"priority\":0,\"nice\":-20,\"vram\":0,\"ram\":\"0\",\"sram\":0,\"status\":\"I\",\"cpu\":0,\"mem\":0,\"time\":0,\"cmd\":\"writeback\"},{\"pid\":14,\"user\":\"root\",\"priority\":20,\"nice\":0,\"vram\":0,\"ram\":\"0\",\"sram\":0,\"status\":\"S\",\"cpu\":0,\"mem\":0,\"time\":0,\"cmd\":\"kcompactd0\"},{\"pid\":15,\"user\":\"root\",\"priority\":0,\"nice\":-20,\"vram\":0,\"ram\":\"0\",\"sram\":0,\"status\":\"I\",\"cpu\":0,\"mem\":0,\"time\":0,\"cmd\":\"crypto\"},{\"pid\":16,\"user\":\"root\",\"priority\":0,\"nice\":-20,\"vram\":0,\"ram\":\"0\",\"sram\":0,\"status\":\"I\",\"cpu\":0,\"mem\":0,\"time\":0,\"cmd\":\"kblockd\"},{\"pid\":17,\"user\":\"root\",\"priority\":0,\"nice\":-20,\"vram\":0,\"ram\":\"0\",\"sram\":0,\"status\":\"I\",\"cpu\":0,\"mem\":0,\"time\":0,\"cmd\":\"watchdogd\"},{\"pid\":18,\"user\":\"root\",\"priority\":0,\"nice\":-20,\"vram\":0,\"ram\":\"0\",\"sram\":0,\"status\":\"I\",\"cpu\":0,\"mem\":0,\"time\":0,\"cmd\":\"rpciod\"},{\"pid\":19,\"user\":\"root\",\"priority\":0,\"nice\":-20,\"vram\":0,\"ram\":\"0\",\"sram\":0,\"status\":\"I\",\"cpu\":0,\"mem\":0,\"time\":0,\"cmd\":\"xprtiod\"},{\"pid\":22,\"user\":\"root\",\"priority\":20,\"nice\":0,\"vram\":0,\"ram\":\"0\",\"sram\":0,\"status\":\"S\",\"cpu\":0,\"mem\":0,\"time\":0,\"cmd\":\"kswapd0\"},{\"pid\":23,\"user\":\"root\",\"priority\":0,\"nice\":-20,\"vram\":0,\"ram\":\"0\",\"sram\":0,\"status\":\"I\",\"cpu\":0,\"mem\":0,\"time\":0,\"cmd\":\"nfsiod\"},{\"pid\":33,\"user\":\"root\",\"priority\":0,\"nice\":-20,\"vram\":0,\"ram\":\"0\",\"sram\":0,\"status\":\"I\",\"cpu\":0,\"mem\":0,\"time\":0,\"cmd\":\"kthrotld\"},{\"pid\":34,\"user\":\"root\",\"priority\":0,\"nice\":-20,\"vram\":0,\"ram\":\"0\",\"sram\":0,\"status\":\"I\",\"cpu\":0,\"mem\":0,\"time\":0,\"cmd\":\"iscsi_eh\"},{\"pid\":35,\"user\":\"root\",\"priority\":0,\"nice\":-20,\"vram\":0,\"ram\":\"0\",\"sram\":0,\"status\":\"I\",\"cpu\":0,\"mem\":0,\"time\":0,\"cmd\":\"dwc_otg\"},{\"pid\":36,\"user\":\"root\",\"priority\":0,\"nice\":-20,\"vram\":0,\"ram\":\"0\",\"sram\":0,\"status\":\"I\",\"cpu\":0,\"mem\":0,\"time\":0,\"cmd\":\"DWC Notificatio\"},{\"pid\":37,\"user\":\"root\",\"priority\":1,\"nice\":-19,\"vram\":0,\"ram\":\"0\",\"sram\":0,\"status\":\"S\",\"cpu\":0,\"mem\":0,\"time\":0,\"cmd\":\"vchiq-slot\/0\"},{\"pid\":38,\"user\":\"root\",\"priority\":1,\"nice\":-19,\"vram\":0,\"ram\":\"0\",\"sram\":0,\"status\":\"S\",\"cpu\":0,\"mem\":0,\"time\":0,\"cmd\":\"vchiq-recy\/0\"},{\"pid\":39,\"user\":\"root\",\"priority\":0,\"nice\":-20,\"vram\":0,\"ram\":\"0\",\"sram\":0,\"status\":\"S\",\"cpu\":0,\"mem\":0,\"time\":0,\"cmd\":\"vchiq-sync\/0\"},{\"pid\":40,\"user\":\"root\",\"priority\":20,\"nice\":0,\"vram\":0,\"ram\":\"0\",\"sram\":0,\"status\":\"S\",\"cpu\":0,\"mem\":0,\"time\":0,\"cmd\":\"vchiq-keep\/0\"},{\"pid\":41,\"user\":\"root\",\"priority\":10,\"nice\":-10,\"vram\":0,\"ram\":\"0\",\"sram\":0,\"status\":\"S\",\"cpu\":0,\"mem\":0,\"time\":0,\"cmd\":\"SMIO\"},{\"pid\":44,\"user\":\"root\",\"priority\":20,\"nice\":0,\"vram\":0,\"ram\":\"0\",\"sram\":0,\"status\":\"S\",\"cpu\":0,\"mem\":0,\"time\":1.12,\"cmd\":\"mmcqd\/0\"},{\"pid\":45,\"user\":\"root\",\"priority\":20,\"nice\":0,\"vram\":0,\"ram\":\"0\",\"sram\":0,\"status\":\"S\",\"cpu\":0,\"mem\":0,\"time\":0.08,\"cmd\":\"jbd2\/mmcblk0p2-\"},{\"pid\":46,\"user\":\"root\",\"priority\":0,\"nice\":-20,\"vram\":0,\"ram\":\"0\",\"sram\":0,\"status\":\"I\",\"cpu\":0,\"mem\":0,\"time\":0,\"cmd\":\"ext4-rsv-conver\"},{\"pid\":47,\"user\":\"root\",\"priority\":0,\"nice\":-20,\"vram\":0,\"ram\":\"0\",\"sram\":0,\"status\":\"I\",\"cpu\":0,\"mem\":0,\"time\":0,\"cmd\":\"ipv6_addrconf\"},{\"pid\":83,\"user\":\"root\",\"priority\":20,\"nice\":0,\"vram\":9612,\"ram\":\"4268\",\"sram\":3872,\"status\":\"S\",\"cpu\":0,\"mem\":1,\"time\":1.48,\"cmd\":\"systemd-journal\"},{\"pid\":102,\"user\":\"root\",\"priority\":20,\"nice\":0,\"vram\":14324,\"ram\":\"3096\",\"sram\":2500,\"status\":\"S\",\"cpu\":0,\"mem\":0.7,\"time\":0.75,\"cmd\":\"systemd-udevd\"},{\"pid\":108,\"user\":\"root\",\"priority\":20,\"nice\":0,\"vram\":0,\"ram\":\"0\",\"sram\":0,\"status\":\"I\",\"cpu\":0,\"mem\":0,\"time\":0.14,\"cmd\":\"kworker\/u2:2\"},{\"pid\":177,\"user\":\"systemd+\",\"priority\":20,\"nice\":0,\"vram\":17260,\"ram\":\"4052\",\"sram\":3604,\"status\":\"S\",\"cpu\":0,\"mem\":0.9,\"time\":0.25,\"cmd\":\"systemd-timesyn\"},{\"pid\":207,\"user\":\"avahi\",\"priority\":20,\"nice\":0,\"vram\":6384,\"ram\":\"3136\",\"sram\":2788,\"status\":\"S\",\"cpu\":0,\"mem\":0.7,\"time\":0.82,\"cmd\":\"avahi-daemon\"},{\"pid\":208,\"user\":\"message+\",\"priority\":20,\"nice\":0,\"vram\":6480,\"ram\":\"3472\",\"sram\":3068,\"status\":\"S\",\"cpu\":0,\"mem\":0.8,\"time\":0.41,\"cmd\":\"dbus-daemon\"},{\"pid\":212,\"user\":\"avahi\",\"priority\":20,\"nice\":0,\"vram\":6384,\"ram\":\"1556\",\"sram\":1252,\"status\":\"S\",\"cpu\":0,\"mem\":0.4,\"time\":0,\"cmd\":\"avahi-daemon\"},{\"pid\":217,\"user\":\"root\",\"priority\":20,\"nice\":0,\"vram\":5316,\"ram\":\"2568\",\"sram\":2324,\"status\":\"S\",\"cpu\":0,\"mem\":0.6,\"time\":0.08,\"cmd\":\"cron\"},{\"pid\":219,\"user\":\"root\",\"priority\":20,\"nice\":0,\"vram\":22848,\"ram\":\"2900\",\"sram\":2236,\"status\":\"S\",\"cpu\":0,\"mem\":0.7,\"time\":0.23,\"cmd\":\"rsyslogd\"},{\"pid\":222,\"user\":\"root\",\"priority\":20,\"nice\":0,\"vram\":7368,\"ram\":\"4288\",\"sram\":3884,\"status\":\"S\",\"cpu\":0,\"mem\":1,\"time\":0.2,\"cmd\":\"systemd-logind\"},{\"pid\":224,\"user\":\"nobody\",\"priority\":20,\"nice\":0,\"vram\":5284,\"ram\":\"2564\",\"sram\":2324,\"status\":\"S\",\"cpu\":0,\"mem\":0.6,\"time\":0.05,\"cmd\":\"thd\"},{\"pid\":229,\"user\":\"root\",\"priority\":0,\"nice\":-20,\"vram\":0,\"ram\":\"0\",\"sram\":0,\"status\":\"I\",\"cpu\":0,\"mem\":0,\"time\":0,\"cmd\":\"cfg80211\"},{\"pid\":281,\"user\":\"root\",\"priority\":0,\"nice\":-20,\"vram\":0,\"ram\":\"0\",\"sram\":0,\"status\":\"I\",\"cpu\":0,\"mem\":0,\"time\":0,\"cmd\":\"kworker\/0:1H\"},{\"pid\":341,\"user\":\"root\",\"priority\":20,\"nice\":0,\"vram\":2868,\"ram\":\"1544\",\"sram\":1208,\"status\":\"S\",\"cpu\":0,\"mem\":0.3,\"time\":0.13,\"cmd\":\"dhcpcd\"},{\"pid\":366,\"user\":\"root\",\"priority\":20,\"nice\":0,\"vram\":10188,\"ram\":\"5332\",\"sram\":4784,\"status\":\"S\",\"cpu\":0,\"mem\":1.2,\"time\":0.11,\"cmd\":\"sshd\"},{\"pid\":367,\"user\":\"root\",\"priority\":20,\"nice\":0,\"vram\":4180,\"ram\":\"1692\",\"sram\":1556,\"status\":\"S\",\"cpu\":0,\"mem\":0.4,\"time\":0.03,\"cmd\":\"agetty\"},{\"pid\":368,\"user\":\"root\",\"priority\":20,\"nice\":0,\"vram\":3956,\"ram\":\"2004\",\"sram\":1872,\"status\":\"S\",\"cpu\":0,\"mem\":0.5,\"time\":0.02,\"cmd\":\"agetty\"},{\"pid\":369,\"user\":\"root\",\"priority\":20,\"nice\":0,\"vram\":123456,\"ram\":\"21824\",\"sram\":18724,\"status\":\"S\",\"cpu\":0,\"mem\":4.9,\"time\":0.52,\"cmd\":\"apache2\"},{\"pid\":370,\"user\":\"www-data\",\"priority\":20,\"nice\":0,\"vram\":123480,\"ram\":\"5232\",\"sram\":2120,\"status\":\"S\",\"cpu\":0,\"mem\":1.2,\"time\":0,\"cmd\":\"apache2\"},{\"pid\":371,\"user\":\"www-data\",\"priority\":20,\"nice\":0,\"vram\":123480,\"ram\":\"5232\",\"sram\":2120,\"status\":\"S\",\"cpu\":0,\"mem\":1.2,\"time\":0,\"cmd\":\"apache2\"},{\"pid\":372,\"user\":\"www-data\",\"priority\":20,\"nice\":0,\"vram\":123480,\"ram\":\"5232\",\"sram\":2120,\"status\":\"S\",\"cpu\":0,\"mem\":1.2,\"time\":0,\"cmd\":\"apache2\"},{\"pid\":373,\"user\":\"www-data\",\"priority\":20,\"nice\":0,\"vram\":123480,\"ram\":\"5232\",\"sram\":2120,\"status\":\"S\",\"cpu\":0,\"mem\":1.2,\"time\":0,\"cmd\":\"apache2\"},{\"pid\":374,\"user\":\"www-data\",\"priority\":20,\"nice\":0,\"vram\":123480,\"ram\":\"5232\",\"sram\":2120,\"status\":\"S\",\"cpu\":0,\"mem\":1.2,\"time\":0,\"cmd\":\"apache2\"},{\"pid\":452,\"user\":\"root\",\"priority\":20,\"nice\":0,\"vram\":11508,\"ram\":\"5708\",\"sram\":4984,\"status\":\"S\",\"cpu\":0,\"mem\":1.3,\"time\":0.55,\"cmd\":\"sshd\"},{\"pid\":460,\"user\":\"jtodd\",\"priority\":20,\"nice\":0,\"vram\":9652,\"ram\":\"5512\",\"sram\":4832,\"status\":\"S\",\"cpu\":0,\"mem\":1.2,\"time\":0.27,\"cmd\":\"systemd\"},{\"pid\":463,\"user\":\"jtodd\",\"priority\":20,\"nice\":0,\"vram\":28704,\"ram\":\"2880\",\"sram\":1592,\"status\":\"S\",\"cpu\":0,\"mem\":0.6,\"time\":0,\"cmd\":\"(sd-pam)\"},{\"pid\":470,\"user\":\"jtodd\",\"priority\":20,\"nice\":0,\"vram\":11640,\"ram\":\"4136\",\"sram\":3372,\"status\":\"S\",\"cpu\":0,\"mem\":0.9,\"time\":0.95,\"cmd\":\"sshd\"},{\"pid\":473,\"user\":\"jtodd\",\"priority\":20,\"nice\":0,\"vram\":6824,\"ram\":\"4916\",\"sram\":2904,\"status\":\"S\",\"cpu\":0,\"mem\":1.1,\"time\":3.84,\"cmd\":\"bash\"},{\"pid\":586,\"user\":\"root\",\"priority\":20,\"nice\":0,\"vram\":0,\"ram\":\"0\",\"sram\":0,\"status\":\"I\",\"cpu\":0,\"mem\":0,\"time\":0.07,\"cmd\":\"kworker\/u2:0\"},{\"pid\":972,\"user\":\"root\",\"priority\":20,\"nice\":0,\"vram\":0,\"ram\":\"0\",\"sram\":0,\"status\":\"I\",\"cpu\":0,\"mem\":0,\"time\":0,\"cmd\":\"kworker\/0:1\"},{\"pid\":1094,\"user\":\"root\",\"priority\":20,\"nice\":0,\"vram\":0,\"ram\":\"0\",\"sram\":0,\"status\":\"I\",\"cpu\":0,\"mem\":0,\"time\":0.33,\"cmd\":\"kworker\/0:2\"},{\"pid\":1122,\"user\":\"root\",\"priority\":20,\"nice\":0,\"vram\":0,\"ram\":\"0\",\"sram\":0,\"status\":\"I\",\"cpu\":0,\"mem\":0,\"time\":0.02,\"cmd\":\"kworker\/u2:1\"},{\"pid\":1204,\"user\":\"root\",\"priority\":20,\"nice\":0,\"vram\":0,\"ram\":\"0\",\"sram\":0,\"status\":\"I\",\"cpu\":0,\"mem\":0,\"time\":0,\"cmd\":\"kworker\/0:0\"}]}";
    }
}