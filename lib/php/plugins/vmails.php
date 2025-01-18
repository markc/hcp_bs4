<?php declare(strict_types=1);
// lib/php/plugins/vmails.php 20180826 - 20250117
// Copyright (C) 2015-2025 Mark Constable <markc@renta.net> (AGPL-3.0)

class Plugins_Vmails extends Plugin
{
    protected string $tbl = 'vmails';
    protected array $in = [
        'newpw'     => 0,
        'password'  => '',
        'shpw'      => 0,
        'user'      => '',
    ];

    /** @var array<string,mixed> */
    private array $columns = [
        ['dt' => null, 'db' => 'id'],
        ['dt' => 0, 'db' => 'user'],
        ['dt' => 1, 'db' => 'size_mail'],
        ['dt' => 2, 'db' => 'num_total'],
        ['dt' => 3, 'db' => null],
        ['dt' => 4, 'db' => 'updated']
    ];

    protected function create() : string
    {
        elog(__METHOD__);

        if (util::is_post()) {
            $email = (string)$this->in['user'];
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                util::log('Email address (' . $email . ') is invalid');
                $_POST = []; 
                return $this->read();
            }
            util::exe('addvmail ' . $email);
        }
        return util::relist();
    }

    protected function update() : string
    {
        elog(__METHOD__);

        $shpw = (bool)$this->in['shpw'];
        $newpw = (bool)$this->in['newpw'];
        $user = (string)$this->in['user'];
        $password = (string)$this->in['password'];

        if ($shpw) {
            return util::run("shpw $user");
        } elseif ($newpw) {
            return util::run("newpw");
        } elseif (util::is_post()) {
            $decodedPassword = html_entity_decode($password, ENT_QUOTES, 'UTF-8');
            if (util::chkpw($decodedPassword)) {
                util::exe("chpw $user '$decodedPassword'");
            }
        }
        return util::relist();
    }

    protected function delete() : string
    {
        elog(__METHOD__);

        if (util::is_post()) {
            $user = (string)$this->in['user'];
            util::exe('delvmail ' . $user);
        }
        return util::relist();
    }

    protected function list() : string
    {
        elog(__METHOD__);

        if ($this->g->in['x'] === 'json') {
            $columns = [
                ['dt' => null, 'db' => 'id'],
                ['dt' => 0, 'db' => 'user', 'formatter' => function(string $d, array $row) : string {
                    return '
                    <a href="" title="Change password for ' . htmlspecialchars($d) . '" data-id="' . htmlspecialchars((string)$row['id']) .  '" data-user="' . htmlspecialchars($d) .  '" data-toggle="modal" data-target="#updatemodal">
                      <b>' . htmlspecialchars($d) . ' </b></a>';
                }],
                ['dt' => 1, 'db' => 'size_mail', 'formatter' => function(string $d) : string { 
                    return util::numfmt(intval($d)); 
                }],
                ['dt' => 2, 'db' => 'num_total', 'formatter' => function(string $d) : string { 
                    return number_format(intval($d)); 
                }],
                ['dt' => 3, 'db' => null, 'formatter' => function(?string $d, array $row) : string {
                    return '<a href="" title="Remove this Mailbox" data-removeuser="' . htmlspecialchars((string)$row['user']) . '" data-toggle="modal" data-target="#removemodal">
                    <small><i class="bi bi-trash cursor-pointer text-danger"></i></small>
                  </a>';
                }],
                ['dt' => 4, 'db' => 'updated'],
            ];
            /** @var array<string,mixed> $_GET */
            return json_encode(db::simple($_GET, 'vmails_view', 'id', $columns), JSON_PRETTY_PRINT);
        }
        return $this->t->list([]);
    }
}

?>
