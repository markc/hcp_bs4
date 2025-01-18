<?php declare(strict_types=1);
// lib/php/plugin.php 20150101 - 20250116
// Copyright (C) 2015-2025 Mark Constable <markc@renta.net> (AGPL-3.0)

class Plugin
{
    protected string $buf = '';
    protected ?object $dbh = null;
    protected string $tbl = '';
    protected array $in = [];
    protected readonly Theme $t;
    protected readonly Config $g;

    public function __construct(Theme $t)
    {
        elog(__METHOD__);

        $this->t = $t;
        $this->g = $t->g;
        
        $o = $this->g->in['o'] ?? '';
        $m = $this->g->in['m'] ?? '';
        if (!util::is_usr() && ($o !== 'auth' || ($m !== 'list' && $m !== 'create' && $m !== 'resetpw'))) {
            util::redirect($this->g->cfg['self'] . '?o=auth');
        }
        $this->in = util::esc($this->in);
        if ($this->tbl) {
            if (!is_null($this->dbh))
                db::$dbh = $this->dbh;
            elseif (is_null(db::$dbh))
                db::$dbh = new db($t->g->db);
            db::$tbl = $this->tbl;
        }

        $this->buf .= $this->{$t->g->in['m']}();
    }

    public function __toString() : string
    {
        elog(__METHOD__);

        return $this->buf;
    }

    protected function create(): string
    {
        elog(__METHOD__);

        if (util::is_post()) {
            $currentTime = date('Y-m-d H:i:s');
            $this->in['updated'] = $currentTime;
            $this->in['created'] = $currentTime;
            $lid = db::create($this->in);
            util::log('Item number ' . $lid . ' created', 'success');
            util::relist();
            return '';
        }
        
        return $this->t->create($this->in);
    }

    protected function read(): string
    {
        elog(__METHOD__);

        return $this->t->read(db::read('*', 'id', $this->g->in['i'], '', 'one'));
    }

    protected function update(): string
    {
        elog(__METHOD__);

        if (util::is_post()) {
            $this->in['updated'] = date('Y-m-d H:i:s');
            $itemId = $this->g->in['i'] ?? 0;
            
            if (db::update($this->in, [['id', '=', $itemId]])) {
                util::log('Item number ' . $itemId . ' updated', 'success');
                util::relist();
                return '';
            }
            
            util::log('Error updating item.');
        }
        
        return $this->read();
    }

    protected function delete(): string
    {
        elog(__METHOD__);

        if (util::is_post()) {
            $itemId = $this->g->in['i'] ?? null;
            if ($itemId) {
                $res = db::delete([['id', '=', $itemId]]);
                util::log('Item number ' . $itemId . ' removed', 'success');
                util::relist();
                return '';
            }
        }
        
        return 'Error deleting item';
    }

    protected function list(): string
    {
        elog(__METHOD__);

        return $this->t->list(db::read('*', '', '', 'ORDER BY `updated` DESC'));
    }

    public function __call(string $name, array $args): string
    {
        elog(__METHOD__ . '() name = ' . $name . ', args = ' . var_export($args, true));
        
        return 'Plugin::' . $name . '() not implemented';
    }
}

?>
