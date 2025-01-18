<?php declare(strict_types=1);
// plugins/processes.php 20170225 - 20250117
// Copyright (C) 2015-2025 Mark Constable <markc@renta.net> (AGPL-3.0)

class Plugins_Processes extends Plugin
{
    public function list() : string
    {
elog(__METHOD__);

        //return $this->t->list(['procs' => shell_exec('sudo processes')]);
        return $this->t->list(['procs' => shell_exec("ps -eo pcpu:4,rss:8,vsz:8,cmd --sort=-pcpu,-rss | grep -v ' 0 \['")]);
    }
}

?>
