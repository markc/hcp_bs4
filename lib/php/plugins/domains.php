<?php declare(strict_types=1);
// lib/php/plugins/domains.php 20150101 - 201250117
// Copyright (C) 2015-2025 Mark Constable <markc@renta.net> (AGPL-3.0)

class Plugins_Domains extends Plugin
{
    protected ?object $dbh = null;
    protected string $tbl = 'domains';
    protected array $in = [
        'name'          => '',
        'master'        => '',
        'last_check'    => '',
        'disabled'      => 0,
        'type'          => '',
        'notified_serial' => '',
        'account'       => '',
        'increment'     => 0,
        'ip'            => '',
        'ns1'           => '',
        'ns2'           => '',
    ];

    public function __construct(Theme $t)
    {
        elog(__METHOD__);

        if (isset($t->g->dns['db']['type']) && $t->g->dns['db']['type']) {
            $this->dbh = new db($t->g->dns['db']);
        }
        parent::__construct($t);
    }

    protected function create(): string
    {
        elog(__METHOD__);

        if (util::is_post()) {
            $domain = $_POST['domain'] ?? '';
            $ip = $_POST['ip'] ?? '';
            $ns1 = $_POST['ns1'] ?? '';
            $ns2 = $_POST['ns2'] ?? '';
            $mxhost = $_POST['mxhost'] ?? '';
            $spfip = $_POST['spfip'] ?? '';

            if (!$domain || !$ip || !$ns1 || !$ns2) {
                return $this->t->create($this->in);
            }

            util::exe("addpdns $domain $ip $ns1 $ns2 $mxhost $spfip");
            return $this->t->create($this->in);
        }
        return $this->t->create($this->in);
    }

    protected function create2(): string
    {
        elog(__METHOD__);

        if (util::is_post()) {
            $domain = $_POST['domain'] ?? '';
            $type = $_POST['type'] ?? '';
            $master = $_POST['master'] ?? '';
            $prio = $_POST['prio'] ?? 0;
            $ttl = $_POST['ttl'] ?? 3600;
            $a = $_POST['a'] ?? '';
            $mx = $_POST['mx'] ?? '';

            if (!$domain) {
                return $this->t->create($this->in);
            }

            $created = date('Y-m-d H:i:s');
            $disable = 0;

            $soa = $this->g->dns['soa'] ?? [];
            $soa_buf =
                ($soa['primary'] ?? '') . $domain . ' ' .
                ($soa['email'] ?? '') . $domain . '. ' .
                date('Ymd') . '00' . ' ' .
                ($soa['refresh'] ?? '') . ' ' .
                ($soa['retry'] ?? '') . ' ' .
                ($soa['expire'] ?? '') . ' ' .
                ($soa['ttl'] ?? '');

            $did = db::create([
                'name'    => $domain,
                'master'  => $type === 'SLAVE' ? $master : '',
                'type'    => $type ?: 'MASTER',
                'updated' => $created,
                'created' => $created,
            ]);

            if ($type === 'SLAVE') {
                util::log('Created DNS Zone: ' . $domain, 'success');
                util::redirect($this->cfg['self'] . '?o=' . $this->g->in['o'] . '&m=list');
                return '';
            }

            $sql = "
 INSERT INTO `records` (
        content, created, disabled, domain_id, name, prio, ttl, type, updated
) VALUES (
        :content, :created, :disabled, :did, :domain, :prio, :ttl, :type, :updated
)";

            $records = [
                ['content' => $soa_buf, 'type' => 'SOA', 'domain' => $domain],
                ['content' => $ns1 . $domain, 'type' => 'NS', 'domain' => $domain],
                ['content' => $ns2 . $domain, 'type' => 'NS', 'domain' => $domain],
                ['content' => $a, 'type' => 'A', 'domain' => $domain],
                ['content' => $a, 'type' => 'A', 'domain' => 'cdn.' . $domain],
                ['content' => $a, 'type' => 'A', 'domain' => 'www.' . $domain],
                ['content' => $mx . $domain, 'type' => 'MX', 'domain' => $domain],
            ];

            foreach ($records as $record) {
                db::qry($sql, [
                    'content' => $record['content'],
                    'created' => $created,
                    'did'     => $did,
                    'disabled'=> $disable,
                    'domain'  => $record['domain'],
                    'prio'    => $prio,
                    'ttl'     => $ttl,
                    'type'    => $record['type'],
                    'updated' => $created,
                ]);
            }

            util::log('Created DNS Zone: ' . $domain, 'success');
            util::redirect($this->cfg['self'] . '?o=' . $this->g->in['o'] . '&m=list');
        }
        return $this->t->create($this->in);
    }

    protected function update(): string
    {
        elog(__METHOD__);

        if ($this->in['increment']) {
            $sql = "
 SELECT content as soa
   FROM records
  WHERE type='SOA'
    AND domain_id=:did";

            $oldsoa = explode(' ', db::qry($sql, ['did' => $this->g->in['i']], 'col'));
            if (count($oldsoa) < 7) {
                return 'Invalid SOA record';
            }

            $primary  = $oldsoa[0];
            $email    = $oldsoa[1];
            $serial   = $oldsoa[2];
            $refresh  = $oldsoa[3];
            $retry    = $oldsoa[4];
            $expire   = $oldsoa[5];
            $ttl      = $oldsoa[6];

            $today = date('Ymd');
            $serial_day = substr($serial, 0, 8);
            $serial_rev = substr($serial, -2);

            $serial = ($serial_day == $today)
                ? "$today" . sprintf("%02d", (int)$serial_rev + 1)
                : "$today" . "00";

            $soa =
                $primary . ' ' .
                $email . ' ' .
                $serial . ' ' .
                $refresh . ' ' .
                $retry . ' ' .
                $expire . ' ' .
                $ttl;

            $sql = "
 UPDATE records SET
        ttl     = :ttl,
        content = :soa,
        updated = :updated
  WHERE type = 'SOA'
    AND domain_id = :did";

            $res = db::qry($sql, [
                'did' => $this->g->in['i'],
                'soa' => $soa,
                'ttl' => $ttl,
                'updated' => date('Y-m-d H:i:s'),
            ]);

            if ($this->in['increment']) {
                return $serial;
            }

            util::log('Updated DNS domain ID ' . $this->g->in['i'], 'success');
            util::redirect($this->g->cfg['self'] . '?o=' . $this->g->in['o'] . '&m=list');
            return '';

        } elseif (util::is_post() && $this->g->in['i']) {
            $dom = db::read('name,type,master', 'id', $this->g->in['i'], '', 'one');
            if (!is_array($dom)) {
                return 'Error reading domain';
            }

            if ($dom['type'] === 'SLAVE') {
                return $this->t->update($dom);
            }

            $sql = "
 SELECT content as soa
   FROM records
  WHERE type='SOA'
    AND domain_id=:did";

            $soa = db::qry($sql, ['did' => $this->g->in['i']], 'one');
            if (!is_array($soa)) {
                return 'Error reading SOA record';
            }

            return $this->t->update(array_merge($dom, $soa));
        }
        return 'Error updating item';
    }

    protected function delete(): string
    {
        elog(__METHOD__);

        if ($this->g->in['i']) {
            $sql = "
 DELETE FROM `records`
  WHERE  domain_id = :id";

            $res1 = db::qry($sql, ['id' => $this->g->in['i']]);
            $res2 = db::delete([['id', '=', $this->g->in['i']]]);

            util::log('Deleted DNS zone ID: ' . $this->g->in['i'], 'success');
            util::redirect($this->g->cfg['self'] . '?o=' . $this->g->in['o'] . '&m=list');
            return '';
        }
        return 'Error deleting item';
    }

    protected function list(): string
    {
        elog(__METHOD__);

        if ($this->g->in['x'] === 'json') {
            $columns = [
                ['dt' => 0, 'db' => 'name', 'formatter' => function(string $d, array $row): string {
                    return ($row['type'] !== 'SLAVE') ? '
                    <a href="?o=records&m=list&i=' . $row['id'] . '" title="Update Domain SOA">
                      <b>' . $d . '</b></a>' : '<b>' . $d . '</b>';
                }],
                ['dt' => 1, 'db' => 'type'],
                ['dt' => 2, 'db' => 'records'],
                ['dt' => 3, 'db' => 'soa', 'formatter' => function(string $d, array $row): string {
                    $soa = explode(' ', $row['soa']);
                    return ($row['type'] !== 'SLAVE') ? '
        <a class="serial" href="?o=domains&m=update&i=' . $row['id'] . '" title="Update Serial">' . ($soa[2] ?? '') . '</a>' : ($soa[2] ?? '');
                }],
                ['dt' => 4, 'db' => 'id', 'formatter' => function(string $d, array $row): string {
                    return '
                    <a href="" class="shwho" data-toggle="modal" data-target="#shwhomodal" title="Show Domain Info" data-rowid="' . $d . '" data-rowname="' . $row['name'] . '">
                      <i class="fas fa-info-circle fa-fw cursor-pointer"></i></a>
                    <a href="" class="delete" data-toggle="modal" data-target="#removemodal" title="Remove Domain ID: ' . $d . '" data-rowid="' . $d . '" data-rowname="' . $row['name'] . '">
                      <i class="fas fa-trash fa-fw cursor-pointer text-danger"></i></a>';
                }],
                ['dt' => 5, 'db' => 'updated'],
            ];
            return json_encode(db::simple($_GET, 'domains_view2', 'id', $columns), JSON_PRETTY_PRINT);
        }
        return $this->t->list([]);
    }

    protected function shwho(): string
    {
        elog(__METHOD__);
        return (string)shell_exec('sudo shwho ' . $this->in['name']);
    }

    protected function incsoa(): string
    {
        elog(__METHOD__);
        return (string)shell_exec('sudo incsoa ' . $this->in['name']);
    }
}
