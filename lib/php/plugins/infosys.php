<?php declare(strict_types=1);
// plugins/infosys.php 20170225 - 20250117
// Copyright (C) 2015-2025 Mark Constable <markc@renta.net> (AGPL-3.0)

class Plugins_InfoSys extends Plugin
{
    /**
     * @return array<string, string|int|float>
     */
    private function getMemoryInfo(): array
    {
        $memInfo = @file_get_contents('/proc/meminfo');
        if ($memInfo === false) {
            return ['MemTotal' => 0, 'MemFree' => 0, 'Cached' => 0, 'SReclaimable' => 0, 'Buffers' => 0];
        }
        
        $pmi = explode("\n", trim($memInfo));
        $mem = [];
        foreach ($pmi as $line) {
            if (str_contains($line, ':')) {
                list($k, $v) = explode(':', $line);
                list($mem[$k],) = explode(' ', trim($v));
            }
        }
        return $mem;
    }

    /**
     * @return array{user: float, nice: float, sys: float, idle: float}
     */
    private function getCpuStats(): array
    {
        $stat1 = @file('/proc/stat');
        if ($stat1 === false) {
            return ['user' => 0.0, 'nice' => 0.0, 'sys' => 0.0, 'idle' => 100.0];
        }

        sleep(1);
        $stat2 = @file('/proc/stat');
        if ($stat2 === false) {
            return ['user' => 0.0, 'nice' => 0.0, 'sys' => 0.0, 'idle' => 100.0];
        }

        $info1 = explode(" ", preg_replace("!cpu +!", "", $stat1[0]));
        $info2 = explode(" ", preg_replace("!cpu +!", "", $stat2[0]));
        
        $dif = [
            'user' => (float)($info2[0] ?? 0) - (float)($info1[0] ?? 0),
            'nice' => (float)($info2[1] ?? 0) - (float)($info1[1] ?? 0),
            'sys'  => (float)($info2[2] ?? 0) - (float)($info1[2] ?? 0),
            'idle' => (float)($info2[3] ?? 0) - (float)($info1[3] ?? 0)
        ];
        
        $total = array_sum($dif);
        if ($total === 0.0) {
            return ['user' => 0.0, 'nice' => 0.0, 'sys' => 0.0, 'idle' => 100.0];
        }

        return array_map(
            fn(float $y): float => round($y / $total * 100, 2),
            $dif
        );
    }

    public function list(): string
    {
        elog(__METHOD__);

        $cpu_name = 'Unknown CPU';
        $cpu_num = 0;
        $os = 'Unknown OS';

        // Get CPU info
        if (is_readable('/proc/cpuinfo')) {
            $cpuInfo = @file_get_contents('/proc/cpuinfo');
            if ($cpuInfo !== false) {
                $ret = preg_match_all('/model name.+/', $cpuInfo, $matches);
                if ($ret && isset($matches[0][0])) {
                    $parts = explode(': ', $matches[0][0]);
                    $cpu_name = $parts[1] ?? 'Unknown CPU';
                    $cpu_num = count($matches[0]);
                }
            }
        }

        // Get OS info
        if (is_readable('/etc/os-release')) {
            $osInfo = @file_get_contents('/etc/os-release');
            if ($osInfo !== false) {
                $tmp = explode("\n", trim($osInfo));
                $osr = [];
                foreach ($tmp as $line) {
                    if (str_contains($line, '=')) {
                        list($k, $v) = explode('=', $line);
                        $osr[$k] = trim($v, '" ');
                    }
                }
                $os = $osr['PRETTY_NAME'] ?? 'Unknown OS';
            }
        }

        // Get memory info
        $mem = $this->getMemoryInfo();
        
        // Get CPU stats
        $cpu = $this->getCpuStats();
        $cpu_all = sprintf(
            "User: %01.2f, System: %01.2f, Nice: %01.2f, Idle: %01.2f",
            $cpu['user'],
            $cpu['sys'],
            $cpu['nice'],
            $cpu['idle']
        );
        $cpu_pcnt = intval(round(100 - $cpu['idle']));

        // Get disk info
        $dt = (float)disk_total_space('/');
        $df = (float)disk_free_space('/');
        $du = $dt - $df;
        $dp = (int)floor(($du / ($dt ?: 1)) * 100);

        // Get memory usage
        $mt = (float)($mem['MemTotal'] ?? 0) * 1000;
        $mu = (float)(($mem['MemTotal'] ?? 0) - ($mem['MemFree'] ?? 0) - ($mem['Cached'] ?? 0) - ($mem['SReclaimable'] ?? 0) - ($mem['Buffers'] ?? 0)) * 1000;
        $mf = $mt - $mu;
        $mp = (int)floor(($mu / ($mt ?: 1)) * 100);

        // Get system info
        $hostname = (string)gethostname();
        $ip = gethostbyname($hostname);
        $hn = gethostbyaddr($ip);
        
        $kernel = 'Unknown';
        if (is_readable('/proc/version')) {
            $version = @file_get_contents('/proc/version');
            if ($version !== false) {
                $parts = explode(' ', trim($version));
                $kernel = $parts[2] ?? 'Unknown';
            }
        }

        $uptime = '0';
        $uptimeContent = @file_get_contents('/proc/uptime');
        if ($uptimeContent !== false) {
            $uptime = explode(' ', $uptimeContent)[0] ?? '0';
        }

        /** @var array<string, string|int|float> */
        return $this->t->list([
            'dsk_color' => $dp > 90 ? 'danger' : ($dp > 80 ? 'warning' : 'default'),
            'dsk_free'  => util::numfmtsi($df),
            'dsk_pcnt'  => $dp,
            'dsk_text'  => $dp > 5 ? (string)$dp . '%' : '',
            'dsk_total' => util::numfmtsi($dt),
            'dsk_used'  => util::numfmtsi($du),
            'mem_color' => $mp > 90 ? 'danger' : ($mp > 80 ? 'warning' : 'default'),
            'mem_free'  => util::numfmt($mf),
            'mem_pcnt'  => $mp,
            'mem_text'  => $mp > 5 ? (string)$mp . '%' : '',
            'mem_total' => util::numfmt($mt),
            'mem_used'  => util::numfmt($mu),
            'os_name'   => $os,
            'uptime'    => util::sec2time((int)$uptime),
            'loadav'    => implode(', ', sys_getloadavg()),
            'hostname'  => $hn,
            'host_ip'   => $ip,
            'kernel'    => $kernel,
            'cpu_all'   => $cpu_all,
            'cpu_name'  => $cpu_name,
            'cpu_num'   => $cpu_num,
            'cpu_color' => $cpu_pcnt > 90 ? 'danger' : ($cpu_pcnt > 80 ? 'warning' : 'default'),
            'cpu_pcnt'  => $cpu_pcnt,
            'cpu_text'  => $cpu_pcnt > 5 ? (string)$cpu_pcnt . '%' : '',
        ]);
    }
}

?>
