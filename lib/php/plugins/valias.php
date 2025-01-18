<?php declare(strict_types=1);
// lib/php/plugins/valias.php 20170225 - 20250117
// Copyright (C) 1995-2025 Mark Constable <markc@renta.net> (AGPL-3.0)

class Plugins_Valias extends Plugin
{
    protected string $tbl = 'valias';
    protected array $in = [
        'aid'    => 1,
        'hid'    => 1,
        'source' => '',
        'target' => '',
        'active' => 0,
    ];

    protected function create() : string
    {
        elog(__METHOD__);

        if (util::is_post()) {
            $active = (bool)($this->in['active'] ?? false);
            $source = (string)($this->in['source'] ?? '');
            $target = (string)($this->in['target'] ?? '');
            
            $sources = array_map(
                fn(string $s): string => trim($s), 
                preg_split("/( |,|;|\n)/", $source) ?: []
            );
            $targets = array_map(
                fn(string $s): string => trim($s), 
                preg_split("/( |,|;|\n)/", $target) ?: []
            );

            if (empty($sources[0])) {
                util::log('Alias source address is empty');
                $_POST = []; 
                return $this->t->create($this->in);
            }

            if (empty($targets[0])) {
                util::log('Alias target address is empty');
                $_POST = []; 
                return $this->t->create($this->in);
            }

            foreach ($sources as $s) {
                if (empty($s)) continue;
                $lhs = ''; 
                $rhs = '';
                if (strpos($s, '@') !== false) {
                    [$lhs, $rhs] = explode('@', $s);
                } else {
                    $rhs = $s;
                }

                $domain = idn_to_ascii($rhs);
                if ($domain === false) {
                    util::log('Invalid source domain: ' . $rhs);
                    $_POST = []; 
                    return $this->t->create($this->in);
                }

                $sql = "SELECT `id` FROM `vhosts` WHERE `domain` = :domain";
                $hid = db::qry($sql, ['domain' => $domain], 'col');

                if (!$hid) {
                    util::log($domain . ' does not exist as a local domain');
                    $_POST = []; 
                    return $this->t->create($this->in);
                }

                if ((!filter_var($s, FILTER_VALIDATE_EMAIL)) && !empty($lhs)) {
                    util::log('Alias source address is invalid');
                    $_POST = []; 
                    return $this->t->create($this->in);
                }

                $sql = "SELECT 1 FROM `valias` WHERE `source` = :catchall";
                $catchall = db::qry($sql, ['catchall' => '@'.$domain], 'col');

                if ($catchall !== 1) {
                    $sql = "SELECT `source` FROM `valias` WHERE `source` = :source";
                    $num_results = count(db::qry($sql, ['source' => $s]));

                    if ($num_results > 0) {
                        util::log($s . ' already exists as an alias');
                        $_POST = []; 
                        return $this->t->create($this->in);
                    }
                }

                $sql = "SELECT `user` FROM `vmails` WHERE `user` = :source";
                $num_results = count(db::qry($sql, ['source' => $s]));

                if ($num_results > 0) {
                    util::log($s . ' already exists as a regular mailbox');
                    $_POST = []; 
                    return $this->t->create($this->in);
                }

                foreach ($targets as $t) {
                    if (empty($t)) continue;
                    [$tlhs, $trhs] = explode('@', $t);

                    $tdomain = idn_to_ascii($trhs);
                    if ($tdomain === false) {
                        util::log('Invalid target domain: ' . $trhs);
                        $_POST = []; 
                        return $this->t->create($this->in);
                    }

                    if (!filter_var($t, FILTER_VALIDATE_EMAIL)) {
                        util::log('Alias target address is invalid');
                        $_POST = []; 
                        return $this->t->create($this->in);
                    }

                    if ($catchall !== 1 && $t === $s) {
                        util::log('Alias source and target addresses must not be the same');
                        $_POST = []; 
                        return $this->t->create($this->in);
                    }
                }

                $target_str = implode(',', $targets);
                $source_str = filter_var($s, FILTER_VALIDATE_EMAIL) ? $s : '@' . $domain;

                $sql = "
                    INSERT INTO `valias` (
                        `active`,
                        `hid`,
                        `source`,
                        `target`,
                        `updated`,
                        `created`
                    ) VALUES (
                        :active,
                        :hid,
                        :source,
                        :target,
                        :updated,
                        :created
                    )";

                $current_time = date('Y-m-d H:i:s');
                $result = db::qry($sql, [
                    'active'  => (int)$active,
                    'hid'     => (int)$hid,
                    'source'  => $source_str,
                    'target'  => $target_str,
                    'updated' => $current_time,
                    'created' => $current_time
                ]);
            }
            util::log('Alias added', 'success');
            util::ses('p', '', '1');
            util::redirect($this->g->cfg['self'] . '?o=' . $this->g->in['o'] . '&m=list');
            return '';
        }
        
        return $this->t->create($this->in);
    }

    protected function read() : string
    {
        elog(__METHOD__);

        return $this->t->update(db::read('*', 'id', $this->g->in['i'], '', 'one'));
    }

    protected function update() : string
    {
        elog(__METHOD__);

        if (util::is_post()) {
            $active = (bool)($this->in['active'] ?? false);
            $source = (string)($this->in['source'] ?? '');
            $target = (string)($this->in['target'] ?? '');
            
            $sources = array_map(
                fn(string $s): string => trim($s), 
                preg_split("/( |,|;|\n)/", $source) ?: []
            );
            $targets = array_map(
                fn(string $s): string => trim($s), 
                preg_split("/( |,|;|\n)/", $target) ?: []
            );

            if (empty($sources[0])) {
                util::log('Alias source address is empty');
                $_POST = []; 
                return $this->read();
            }

            if (empty($targets[0])) {
                util::log('Alias target address is empty');
                $_POST = []; 
                return $this->read();
            }

            foreach ($sources as $s) {
                if (empty($s)) continue;
                $lhs = ''; 
                $rhs = '';
                if (strpos($s, '@') !== false) {
                    [$lhs, $rhs] = explode('@', $s);
                } else {
                    $rhs = $s;
                }

                $domain = idn_to_ascii($rhs);
                if ($domain === false) {
                    util::log('Invalid source domain: ' . $rhs);
                    $_POST = []; 
                    return $this->read();
                }

                $sql = "SELECT `id` FROM `vhosts` WHERE `domain` = :domain";
                $hid = db::qry($sql, ['domain' => $domain], 'col');

                if (!$hid) {
                    util::log($domain . ' does not exist as a local domain');
                    $_POST = []; 
                    return $this->read();
                }

                if ((!filter_var($s, FILTER_VALIDATE_EMAIL)) && !empty($lhs)) {
                    util::log('Alias source address is invalid');
                    $_POST = []; 
                    return $this->read();
                }

                $sql = "SELECT 1 FROM `valias` WHERE `source` = :catchall";
                $catchall = db::qry($sql, ['catchall' => '@'.$domain], 'col');

                if ($catchall !== 1) {
                    $sql = "SELECT `user` FROM `vmails` WHERE `user` = :source";
                    $num_results = count(db::qry($sql, ['source' => $s]));

                    if ($num_results > 0) {
                        util::log($s . ' already exists as a regular mailbox');
                        $_POST = []; 
                        return $this->read();
                    }
                }

                foreach ($targets as $t) {
                    if (empty($t)) continue;
                    [$tlhs, $trhs] = explode('@', $t);

                    $tdomain = idn_to_ascii($trhs);
                    if ($tdomain === false) {
                        util::log('Invalid target domain: ' . $trhs);
                        $_POST = []; 
                        return $this->read();
                    }

                    if (!filter_var($t, FILTER_VALIDATE_EMAIL)) {
                        util::log('Alias target address is invalid');
                        $_POST = []; 
                        return $this->read();
                    }

                    if ($catchall !== 1 && $t === $s) {
                        util::log('Alias source and target addresses must not be the same');
                        $_POST = []; 
                        return $this->read();
                    }
                }

                $target_str = implode(',', $targets);
                $source_str = filter_var($s, FILTER_VALIDATE_EMAIL) ? $s : '@' . $domain;

                $sql = "SELECT `source` FROM `valias` WHERE `source` = :source";
                $exists = count(db::qry($sql, ['source' => $source_str]));

                if ($exists || count($sources) === 1) {
                    $sql = "
                        UPDATE `valias` SET
                            `active`  = :active,
                            `source`  = :source,
                            `target`  = :target,
                            `updated` = :updated
                        WHERE `id` = :id";

                    $result = db::qry($sql, [
                        'id'      => (int)$this->g->in['i'],
                        'active'  => (int)$active,
                        'source'  => $source_str,
                        'target'  => $target_str,
                        'updated' => date('Y-m-d H:i:s'),
                    ]);
                } else {
                    $sql = "
                        INSERT INTO `valias` (
                            `active`,
                            `hid`,
                            `source`,
                            `target`,
                            `updated`,
                            `created`
                        ) VALUES (
                            :active,
                            :hid,
                            :source,
                            :target,
                            :updated,
                            :created
                        )";

                    $current_time = date('Y-m-d H:i:s');
                    $result = db::qry($sql, [
                        'active'  => (int)$active,
                        'hid'     => (int)$hid,
                        'source'  => $source_str,
                        'target'  => $target_str,
                        'updated' => $current_time,
                        'created' => $current_time
                    ]);
                }
            }
            util::log('Changes to alias have been saved', 'success');
            util::relist();
            return '';
        }
        
        if ($this->g->in['i']) {
            return $this->read();
        }
        
        return 'Error updating item';
    }

    protected function list() : string
    {
        elog(__METHOD__);

        if (($this->g->in['x'] ?? '') === 'json') {
            $columns = [
                [
                    'dt' => 0, 
                    'db' => 'source', 
                    'formatter' => function(string $d, array $row): string {
                        return sprintf(
                            '<a href="?o=valias&m=update&i=%d" title="Update entry for %s"><b>%s</b></a>',
                            $row['id'],
                            htmlspecialchars($d),
                            htmlspecialchars($d)
                        );
                    }
                ],
                [
                    'dt' => 1, 
                    'db' => 'target', 
                    'formatter' => function(string $d): string { 
                        return str_replace(',', '<br>', htmlspecialchars($d)); 
                    }
                ],
                ['dt' => 2, 'db' => 'domain'],
                [
                    'dt' => 3, 
                    'db' => 'active', 
                    'formatter' => function(int $d): string {
                        return sprintf(
                            '<i class="fas %s"></i>',
                            $d ? 'fa-check text-success' : 'fa-times text-danger'
                        );
                    }
                ],
                ['dt' => 4, 'db' => 'id'],
                ['dt' => 5, 'db' => 'updated'],
            ];
            return json_encode(
                db::simple($_GET, 'valias_view', 'id', $columns), 
                JSON_PRETTY_PRINT
            );
        }
        return $this->t->list([]);
    }
}

?>
