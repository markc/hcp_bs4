<?php declare(strict_types=1);
// lib/php/config.php 20150101 - 20250116
// Copyright (C) 2015-2025 Mark Constable <markc@renta.net> (AGPL-3.0)

require_once __DIR__ . '/theme.php';

class Config {
    public ?Theme $t = null;
    public array $cfg = [
        'email' => 'admin@example.com',
        'admpw' => 'admin123',  // password: admin123
        'file'  => __DIR__ . DS . '.ht_conf.php', // settings override
        'hash'  => 'SHA512-CRYPT',
        'host'  => 'localhost',
        'perp'  => 25,
        'self'  => '/',
    ];

    public array $in = [
        'a'     => '',          // API (apiusr:apikey)
        'd'     => '',          // Domain (current)
        'g'     => null,        // Group/Category
        'i'     => null,        // Item or ID
        'l'     => '',          // Log (message)
        'm'     => 'list',      // Method (action)
        'o'     => 'home',      // Object (content)
        't'     => 'bootstrap5', // Theme
        'x'     => '',          // XHR (request)
    ];

    public array $out = [
        'doc'   => 'NetServa',
        'css'   => '',
        'log'   => '',
        'nav1'  => '',
        'nav2'  => '',
        'nav3'  => '',
        'head'  => 'NetServa',
        'main'  => 'Error: missing page!',
        'foot'  => 'Copyright (C) 2015-2025 Mark Constable (AGPL-3.0)',
        'js'    => '',
        'end'   => '',
    ];

    public array $db = [
        'host'  => '127.0.0.1', // DB site
        'name'  => 'sysadm',    // DB name
        'pass'  => 'lib' . DS . '.ht_pw', // MySQL password override
        'path'  => 'sysadm/sysadm.db', // SQLite DB
        'port'  => '3306',      // DB port
        'sock'  => '',          // '/run/mysqld/mysqld.sock',
        'type'  => 'sqlite',    // mysql | sqlite
        'user'  => 'sysadm',    // DB user
    ];

    public array $nav1 = [
        'non' => [
            ['Webmail',     '../',              'bi bi-envelope'],
            ['Phpmyadmin',  'phpmyadmin/',      'bi bi-globe'],
        ],
        'usr' => [
            ['Webmail',     '../',              'bi bi-envelope'],
            ['Phpmyadmin',  'phpmyadmin/',      'bi bi-globe'],
        ],
        'adm' => [
            ['Menu',        [
                ['Webmail',     '../',          'bi bi-envelope'],
                ['Phpmyadmin',  'phpmyadmin/',  'bi bi-globe'],
            ], 'bi bi-list'],
            ['Admin',       [
                ['Accounts',    '?o=accounts',  'bi bi-people'],
                ['Vhosts',      '?o=vhosts',    'bi bi-globe'],
                ['SSH Manager', '?o=sshm',      'bi bi-key'],
                ['Mailboxes',   '?o=vmails',    'bi bi-envelope'],
                ['Aliases',     '?o=valias',    'bi bi-envelope-at'],
                ['DKIM',        '?o=dkim',      'bi bi-person-vcard'],
                ['Domains',     '?o=domains',   'bi bi-server'],
            ], 'bi bi-gear'],
            ['Stats',       [
                ['Sys Info',    '?o=infosys',   'bi bi-speedometer'],
                ['Processes',   '?o=processes', 'bi bi-diagram-2'],
                ['Mail Info',   '?o=infomail',  'bi bi-envelope-at'],
                ['Mail Graph',  '?o=mailgraph', 'bi bi-envelope'],
            ], 'bi bi-graph-up'],
        ],
    ];

    public array $nav2 = [];

    public array $dns = [
        'a'     => '127.0.0.1',
        'mx'    => '',
        'ns1'   => 'ns1.',
        'ns2'   => 'ns2.',
        'prio'  => 0,
        'ttl'   => 300,
        'soa'   => [
            'primary' => 'ns1.',
            'email'   => 'admin.',
            'refresh' => 7200,
            'retry'   => 540,
            'expire'  => 604800,
            'ttl'     => 3600,
        ],
        'db' => [
            'host'  => '127.0.0.1', // Alt DNS DB site
            'name'  => 'pdns',      // Alt DNS DB name
            'pass'  => 'lib' . DS . '.ht_dns_pw', // MySQL DNS password override
            'path'  => 'sysadm/pdns.db', // DNS SQLite DB
            'port'  => '3306',      // Alt DNS DB port
            'sock'  => '',          // '/run/mysqld/mysqld.sock',
            'type'  => 'sqlite',    // mysql | sqlite | '' to disable
            'user'  => 'pdns',      // Alt DNS DB user
        ],
    ];

    public array $acl = [
        0 => 'SuperAdmin',
        1 => 'Administrator',
        2 => 'User',
        3 => 'Suspended',
        9 => 'Anonymous',
    ];
}
