<?php declare(strict_types=1);
// lib/php/plugins/sshm.php 20230703 - 20250117
// Copyright (C) 2015-2025 Mark Constable <markc@renta.net> (AGPL-3.0)

class Plugins_Sshm extends Plugin
{

    public array $inp = [
        'name'      => '',
        'host'      => '',
        'port'      => '22',
        'user'      => 'root',
        'skey'      => 'none',
        'key_name'  => '',
        'key_cmnt'  => '',
        'key_pass'  => '',
    ];

    public function create(): string
    {
        elog(__METHOD__);

        if (util::is_post()) {
            util::run('sshm create ' . implode(' ', $this->inp));
            util::relist();
            return '';
        }
        
        $output = util::run('sshm key_list');
        $this->inp['keys'] = $output ? explode("\n", $output) : [];
        return $this->g->t->create($this->inp);
    }

    public function update(): string
    {
        elog(__METHOD__);

        if (util::is_post()) {
            util::run('sshm create ' . implode(' ', $this->inp));
            util::relist();
            return '';
        }
        
        $output = util::run('sshm read ' . $this->inp['name']);
        $host_data = $output ? explode("\n", $output) : [];
        $inp = array_combine(
            array_keys($this->inp),
            array_map(fn($k, $i) => $host_data[$i] ?? '', array_keys($this->inp), array_keys($this->inp))
        );
        $keys_output = util::run('sshm key_list');
        $inp['keys'] = $keys_output ? explode("\n", $keys_output) : [];
        return $this->g->t->update($inp);
    }

    public function delete(): string
    {
        elog(__METHOD__);

        if (util::is_post()) {
            util::run('sshm delete ' . $this->inp['name']);
            util::relist();
            return '';
        }
        return $this->g->t->delete($this->inp);
    }

    public function list(): string
    {
        elog(__METHOD__);

        $output = util::run('sshm list');
        return $this->g->t->list(['ary' => $output ? explode("\n", $output) : []]);
    }

    public function help(): string
    {
        elog(__METHOD__);

        return $this->g->t->help(
            $this->inp['name'],
            util::run('sshm help ' . escapeshellarg($this->inp['name']))
        );
    }

    public function key_create(): string
    {
        elog(__METHOD__);

        if (util::is_post()) {
            util::run(
                'sshm key_create ' .
                $this->inp['key_name'] . ' ' .
                $this->inp['key_cmnt'] . ' ' .
                $this->inp['key_pass']
            );
            util::relist('key_list');
            return '';
        }
        return $this->g->t->key_create($this->inp);
    }

    protected function key_read(): string
    {
        elog(__METHOD__);

        return $this->g->t->key_read(
            $this->inp['skey'],
            shell_exec('sshm key_read ' . $this->inp['skey'])
        );
    }

    public function key_delete(): string
    {
        elog(__METHOD__);

        if (util::is_post()) {
            util::run('sshm key_delete ' . $this->inp['key_name']);
            util::relist('key_list');
            return '';
        }
        return $this->g->t->key_delete($this->inp);
    }

    public function key_list(): string
    {
        elog(__METHOD__);

        $output = util::run('sshm key_list all');
        return $this->g->t->key_list(['ary' => $output ? explode("\n", $output) : [], 'err' => 0]);
    }
}

?>
