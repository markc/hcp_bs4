<?php declare(strict_types=1);
// lib/php/theme.php 20150101 - 20250117
// Copyright (C) 2015-2025 Mark Constable <markc@renta.net> (AGPL-3.0)

class Theme
{
    private string $buf = '';

    public function __construct(
        public readonly Config $g
    ) {
        elog(__METHOD__);
    }

    public function __toString(): string
    {
        elog(__METHOD__);
        return $this->buf;
    }

    public function log(): string
    {
        elog(__METHOD__);

        $alts = '';
        foreach (util::log() as $lvl => $msg) {
            $alts .= $msg ? sprintf('<p class="alert %s">%s</p>\n', $lvl, $msg) : '';
        }
        return $alts;
    }

    public function nav1(): string
    {
        elog(__METHOD__.' parent::nav1()');

        $o = '?o=' . $this->g->in['o'];
        $currentNav = $this->g->nav1[$_SESSION['gid'] ?? 'non'] ?? [];
        return sprintf(
            '<nav>%s</nav>',
            join('', array_map(
                fn(array $n): string => sprintf(
                    '<a%s href="%s">%s</a>',
                    $o === $n[1] ? ' class="active"' : '',
                    $n[1],
                    $n[0]
                ),
                $currentNav
            ))
        );
    }

    public function head(): string
    {
        elog(__METHOD__);

        return sprintf(
            '<header><h1><a href="%s">%s</a></h1>%s</header>',
            $this->g->cfg['self'],
            $this->g->out['head'],
            $this->g->out['nav1']
        );
    }

    public function main(): string
    {
        elog(__METHOD__);

        return sprintf(
            '<main>%s%s</main>',
            $this->g->out['log'],
            $this->g->out['main']
        );
    }

    public function foot(): string
    {
        elog(__METHOD__);

        return sprintf(
            '<footer class="text-center"><br><p><em><small>%s</small></em></p></footer>',
            $this->g->out['foot']
        );
    }

    public function end(): string
    {
        elog(__METHOD__);

        return sprintf(
            '<pre>%s</pre>',
            $this->g->out['end']
        );
    }

    public function html(): string
    {
        elog(__METHOD__);

        extract($this->g->out, EXTR_SKIP);
        return sprintf(
            '<!DOCTYPE html>
<html lang="en" darkmode>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>%s</title>%s%s
  </head>
  <body>%s%s%s%s
  </body>
</html>',
            $doc,
            $css,
            $js,
            $head,
            $main,
            $foot,
            $end
        );
    }

    public static function dropdown(
        array $ary,
        string $name,
        string $sel = '',
        string $label = '',
        string $class = '',
        string $extra = ''
    ): string {
        elog(__METHOD__);

        $opt = $label ? sprintf('<option value="">%s</option>', ucfirst($label)) : '';
        $buf = '';
        $c = $class ? sprintf(' class="%s"', $class) : '';

        foreach ($ary as $k => $v) {
            $t = str_replace('?t=', '', (string)$v[1]);
            $s = $sel === $t ? ' selected' : '';
            $buf .= sprintf(
                '<option value="%s"%s>%s</option>',
                $t,
                $s,
                $v[0]
            );
        }

        return sprintf(
            '<select%s name="%s" id="%s"%s>%s%s</select>',
            $c,
            $name,
            $name,
            $extra,
            $opt,
            $buf
        );
    }

    public function __call(string $name, array $args): string
    {
        elog(__METHOD__ . '() name = ' . $name);
        return sprintf('Theme::%s() not implemented', $name);
    }
}

?>
