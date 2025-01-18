<?php declare(strict_types=1);
// lib/php/plugins/vhosts.php 20250117
// Copyright (C) 2015-2025 Mark Constable <markc@renta.net> (AGPL-3.0)

class Plugins_Vhosts extends Plugin
{
    protected string $tbl = 'vhosts';

    /** @var array<string, int|string> */
    protected array $in = [
        'active'    => 0,
        'aid'       => 0,
        'aliases'   => 10,
        'diskquota' => 1000000000,
        'domain'    => '',
        'gid'       => 1000,
        'mailboxes' => 1,
        'mailquota' => 500000000,
        'uid'       => 1000,
        'uname'     => '',
        'cms'       => '',
        'ssl'       => '',
        'ip'        => '',
        'uuser'     => '',
    ];

    protected function create() : string
    {
        elog(__METHOD__);

        if (util::is_post()) {
            $domain = (string)($this->in['domain'] ?? '');
            $cms = (string)($this->in['cms'] ?? '');
            $ssl = (string)($this->in['ssl'] ?? '');
            $ip = (string)($this->in['ip'] ?? '');
            $uuser = (string)($this->in['uuser'] ?? '');

            if (file_exists('/home/u/' . $domain)) {
                util::log('/home/u/' . $domain . ' already exists', 'warning');
                $_POST = [];
                return $this->t->create($this->in);
            }

            $num_results = (int)db::read('COUNT(id)', 'domain', $domain, '', 'col');

            if ($num_results !== 0) {
                util::log('Domain already exists');
                $_POST = [];
                return $this->t->create($this->in);
            }

            $cms = ($cms === 'on') ? 'wp' : 'none';
            $ssl = ($ssl === 'on') ? 'self' : 'le';
            $vhost = $uuser ? $uuser . '@' . $domain : $domain;

            shell_exec("nohup sh -c 'sudo addvhost " . escapeshellarg($vhost) . " " . 
                      escapeshellarg($cms) . " " . escapeshellarg($ssl) . " " . 
                      escapeshellarg($ip) . "' > /tmp/addvhost.log 2>&1 &");
            
            util::log('Added ' . $domain . ', please wait another few minutes for the setup to complete', 'success');
            util::redirect($this->g->cfg['self'] . '?o=vhosts');
        }
        return $this->t->create($this->in);
    }

    protected function read() : string
    {
        elog(__METHOD__);
        $id = (int)($this->g->in['i'] ?? 0);
        return $this->t->update(db::read('*', 'id', $id, '', 'one'));
    }

    protected function update() : string
    {
        elog(__METHOD__);

        if (util::is_post()) {
            $id = (int)($this->g->in['i'] ?? 0);
            $active = (int)($this->in['active'] ?? 0);
            $aliases = (int)($this->in['aliases'] ?? 0);
            $diskquota = (int)($this->in['diskquota'] ?? 0) * 1000000;
            $mailboxes = (int)($this->in['mailboxes'] ?? 0);
            $mailquota = (int)($this->in['mailquota'] ?? 0) * 1000000;

            $domain = (string)db::read('domain', 'id', $id, '', 'col');

            if ($mailquota > $diskquota) {
                util::log('Mailbox quota exceeds disk quota');
                $_POST = [];
                return $this->read();
            }

            $sql = "
 UPDATE `vhosts` SET
        `active`    = :active,
        `aliases`   = :aliases,
        `diskquota` = :diskquota,
        `domain`    = :domain,
        `mailboxes` = :mailboxes,
        `mailquota` = :mailquota,
        `updated`   = :updated
  WHERE `id` = :id";

            /** @var array<string, int|string> */
            $params = [
                'id'        => $id,
                'active'    => $active,
                'aliases'   => $aliases,
                'diskquota' => $diskquota,
                'domain'    => $domain,
                'mailboxes' => $mailboxes,
                'mailquota' => $mailquota,
                'updated'   => date('Y-m-d H:i:s'),
            ];

            db::qry($sql, $params);

            util::log('Vhost ID ' . $id . ' updated', 'success');
            util::redirect($this->cfg['self'] . '?o=' . $this->g->in['o'] . '&m=list');
        } elseif ($this->g->in['i']) {
            return $this->read();
        }
        return 'Error updating item';
    }

    protected function delete() : string
    {
        elog(__METHOD__);

        if (util::is_post() && isset($this->g->in['i'])) {
            $id = (int)$this->g->in['i'];
            $domain = (string)db::read('domain', 'id', $id, '', 'col');
            
            if ($domain) {
                shell_exec("nohup sh -c 'sudo delvhost " . escapeshellarg($domain) . 
                          "' > /tmp/delvhost.log 2>&1 &");
                util::log('Removed ' . $domain, 'success');
                util::redirect($this->g->cfg['self'] . '?o=vhosts');
            } else {
                util::log('ERROR: domain does not exist');
            }
        }
        return 'Error deleting item';
    }

    protected function list() : string
    {
        elog(__METHOD__);

        if (($this->g->in['x'] ?? '') === 'json') {
            /** @var array<array{dt: int, db: string|null, formatter?: callable}> */
            $columns = [
                ['dt' => 0,  'db' => 'domain',      'formatter' => function(?string $d, array $row): string {
                    return '
                    <a class="editlink" href="?o=vhosts&m=update&i=' . ($row['id'] ?? '') . '" title="Update VHOST">
                      <b>' . ($row['domain'] ?? '') . '</b></a>';
                }],
                ['dt' => 1,  'db' => 'num_aliases'],
                ['dt' => 2,  'db' => null,          'formatter' => function(?string $d): string { return '/'; }],
                ['dt' => 3,  'db' => 'aliases'],
                ['dt' => 4,  'db' => 'num_mailboxes'],
                ['dt' => 5,  'db' => null,          'formatter' => function(?string $d): string { return '/'; }],
                ['dt' => 6,  'db' => 'mailboxes'],
                ['dt' => 7,  'db' => 'size_mpath',  'formatter' => function(?string $d): string { 
                    return util::numfmt(intval($d ?? '0')); 
                }],
                ['dt' => 8,  'db' => null,          'formatter' => function(?string $d): string { return '/'; }],
                ['dt' => 9,  'db' => 'mailquota',   'formatter' => function(?string $d): string { 
                    return util::numfmt(intval($d ?? '0')); 
                }],
                ['dt' => 10, 'db' => 'size_upath',  'formatter' => function(?string $d): string { 
                    return util::numfmt(intval($d ?? '0')); 
                }],
                ['dt' => 11, 'db' => null,          'formatter' => function(?string $d): string { return '/'; }],
                ['dt' => 12, 'db' => 'diskquota',   'formatter' => function(?string $d): string { 
                    return util::numfmt(intval($d ?? '0')); 
                }],
                ['dt' => 13, 'db' => 'active',      'formatter' => function(?string $d): string {
                    return '<i class="bi ' . (intval($d ?? '0') ? 'bi-check text-success' : 'bi-x text-danger') . '"></i>';
                }],
                ['dt' => 14, 'db' => 'id'],
                ['dt' => 15, 'db' => 'updated'],
            ];
            return json_encode(db::simple($_GET, 'vhosts_view', 'id', $columns), JSON_PRETTY_PRINT);
        }
        return $this->t->list([]);
    }
}
