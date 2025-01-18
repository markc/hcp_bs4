<?php declare(strict_types=1);
// lib/php/plugins/dkim.php 20180511 - 20250117
// Copyright (C) 2015-2025 Mark Constable <markc@renta.net> (AGPL-3.0)

class Plugins_Dkim extends Plugin
{
    /** @var array<string, string> */
    protected array $in;

    public function __construct(Theme $t)
    {
        $this->in = [
            'dnstxt'  => '',
            'domain'  => '',
            'keylen'  => '2048',
            'select'  => 'mail',
        ];
        parent::__construct($t);
    }

    public function create() : string
    {
        elog(__METHOD__);

        if (util::is_post()) {
            $domain = escapeshellarg($this->in['domain']);
            $select = escapeshellarg($this->in['select']);
            $keylen = escapeshellarg($this->in['keylen']);
            util::exe('dkim add ' . $domain . ' ' . $select . ' ' . $keylen);
        }
        util::redirect($this->cfg['self'] . '?o=' . $this->g->in['o'] . '&m=list');
        return ''; // Added return for type safety since redirect may fail
    }

    public function read() : string
    {
        elog(__METHOD__);

        /** @var array{0: string, 1: string} $parts */
        $parts = explode('._domainkey.', $this->in['dnstxt']);
        $domain = $parts[1] ?? ''; // Handle potential undefined index
        $domain_esc = escapeshellarg($domain);
        
        /** @var array<int, string> $retArr */
        $retArr = [];
        /** @var int $retVal */
        $retVal = 0;
        
        exec("sudo dkim show $domain_esc 2>&1", $retArr, $retVal);
        
        $buf = '
        <b>' . ($retArr[0] ?? '') . '</b><br>
        <div style="word-break:break-all;font-family:monospace;width:100%;">' . ($retArr[1] ?? '') . '</div>';
        
        return $this->t->read(['buf' => $buf, 'domain' => $domain]);
    }

    public function update() : string
    {
        elog(__METHOD__);

        util::redirect($this->cfg['self'] . '?o=' . $this->g->in['o'] . '&m=list');
        return ''; // Added return for type safety since redirect may fail
    }

    public function delete() : string
    {
        elog(__METHOD__);

        if (util::is_post()) {
            $domain = escapeshellarg($this->in['domain']);
            util::exe('dkim del ' . $domain);
        }
        util::redirect($this->cfg['self'] . '?o=' . $this->g->in['o'] . '&m=list');
        return ''; // Added return for type safety since redirect may fail
    }

    public function list() : string
    {
        elog(__METHOD__);

        $buf = '<p style="columns:350px 3;column-rule: 1px dotted #ddd;text-align:center;">';
        
        /** @var array<int, string> $retArr */
        $retArr = [];
        /** @var int $retVal */
        $retVal = 0;
        
        exec("sudo dkim list 2>&1", $retArr, $retVal);
        
        foreach($retArr as $line) {
            $buf .= '
            <a href="?o=dkim&m=read&dnstxt=' . htmlspecialchars($line, ENT_QUOTES) . '"><b>' . htmlspecialchars($line, ENT_QUOTES) . '</b></a>';
        }
        
        return $this->t->list(['buf' => $buf . '</p>']);
    }
}

?>
