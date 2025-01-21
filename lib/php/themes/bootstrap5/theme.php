<?php declare(strict_types=1);
// lib/php/themes/bootstrap5/theme.php 20150101 - 20250121
// Copyright (C) 2015-2025 Mark Constable <markc@renta.net> (AGPL-3.0)

class Themes_Bootstrap5_Theme extends Theme {

    public function html() : string
    {
        elog(__METHOD__);

        extract($this->g->out, EXTR_SKIP);

        return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$doc}</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    {$css}
</head>
<body>
    {$head}
    <div class="sidebar left" id="leftSidebar">
        {$nav1}
    </div>
    <div class="sidebar right" id="rightSidebar">
        {$nav3}
        {$nav2}
    </div>
    <div class="main-content" id="main">
        <div class="container-fluid">
            <div class="row">
                <main class="col-md-12 pt-4">
                {$log}
                {$main}
                {$foot}
                </main>
            </div>
        </div>
    </div>
    {$end}
    {$js}
</body>
</html>
HTML;
    }

    public function nav1(array $a = []) : string
    {
        elog(__METHOD__);

        $a = isset($a[0]) ? $a : util::get_nav($this->g->nav1);
        $o = '?o=' . $this->g->in['o'];
        $t = '?t=' . util::ses('t');
        return join('', array_map(function ($n) use ($o, $t) {
            if (is_array($n[1])) return $this->nav_dropdown($n);
            $c = $o === $n[1] || $t === $n[1] ? ' active' : '';
            $i = isset($n[2]) ? '<i class="' . $n[2] . '"></i> ' : '';
            return '
            <ul class="nav flex-column">
                <li class="nav-item' . $c . '">
                    <a class="nav-link" href="' . $n[1] . '">' . $i . $n[0] . '</a>
                </li>
            </ul>';
        }, $a));
    }

    public function nav_dropdown(array $a = []) : string
    {
        elog(__METHOD__);

        $o = '?o=' . $this->g->in['o'];
        $i = isset($a[2]) ? '<i class="' . $a[2] . '"></i> ' : '';
        return '
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#' . $a[0] . 'Submenu" role="button" aria-expanded="false" aria-controls="' . $a[0] . 'Submenu">' .
                    $i . $a[0] . ' <i class="bi bi-chevron-right chevron-icon fw ms-auto"></i>
                </a>
                <div class="collapse submenu" id="' . $a[0] . 'Submenu">
                    <ul class="nav flex-column">' . join('', array_map(function ($n) use ($o) {
            $c = $o === $n[1] ? ' active' : '';
            $i = isset($n[2]) ? '<i class="' . $n[2] . '"></i> ' : '';
            return '
                        <li class="nav-item">
                            <a class="nav-link" href="' . $n[1] . '">' . $i . $n[0] . '</a>
                        </li>';
                }, $a[1])).'
                    </ul>
                </div>
            </li>
        </ul>';
    }

    public function nav2() : string
    {
        elog(__METHOD__);

        return $this->nav_dropdown($this->g->nav2);
    }

    public function nav3() : string
    {
        elog(__METHOD__);

        if (util::is_usr()) {
            $usr[] = ['Change Profile', '?o=accounts&m=read&i=' . $_SESSION['usr']['id'], 'bi bi-person-lines-fill fw'];
            $usr[] = ['Change Password', '?o=auth&m=update&i=' . $_SESSION['usr']['id'], 'bi bi-key fw'];
            $usr[] = ['Sign out', '?o=auth&m=delete', 'bi bi-box-arrow-left fw'];

            if (util::is_adm() && !util::is_acl(0)) $usr[] =
                ['Switch to sysadm', '?o=accounts&m=switch_user&i=' . $_SESSION['adm'], 'bi bi-user fw'];

            return $this->nav_dropdown([$_SESSION['usr']['login'], $usr, 'bi bi-person-circle fw']);
        } else return '';
    }

    public function css(): string
    {
        elog(__METHOD__);

        $self = json_encode($this->g->cfg['self']);
        return <<<HTML
        <link href="favicon.ico" type="image/x-icon" rel="icon">
        <link rel="prefetch" 
            href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/fonts/bootstrap-icons.woff2" 
            as="font" type="font/woff2" crossorigin />
        <link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">
        <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
        <link href="lib/css/hcp.css" rel="stylesheet">
    HTML;
    }

    public function js() : string
    {
        elog(__METHOD__);

        return <<<HTML
        <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        <script src="lib/js/hcp.js"></script>
    HTML;
    }

    public function head() : string
    {
        elog(__METHOD__);

        return <<<HTML
    <nav class="navbar navbar-dark bg-dark fixed-top navbar-height">
        <div class="container-fluid">
             <button class="btn btn-dark" id="leftSidebarToggle" type="button">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand" href="#">
                {$this->g->out['doc']}
            </a>
            <button class="btn btn-dark" id="rightSidebarToggle" type="button">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>
HTML;
    }

    public function main() : string
    {
        elog(__METHOD__);

        return "<main class=\"container\">{$this->g->out['log']}{$this->g->out['main']}</main>";
    }

    public function log() : string
    {
        elog(__METHOD__);

        $logs = '';
        foreach (util::log() as $lvl => $msg) {
            if (is_array($msg) && !empty($msg)) {
                foreach ($msg as $text) {
                    $logs .= $this->generateLogAlert($lvl, $text);
                }
            }
        }
        return $logs;
    }

    private function getUserNavItems() : array
    {
        elog(__METHOD__);

        $usr = [
            ['Change Profile', "?o=accounts&m=read&i={$_SESSION['usr']['id']}", 'bi bi-person'],
            ['Change Password', "?o=auth&m=update&i={$_SESSION['usr']['id']}", 'bi bi-key'],
            ['Sign out', '?o=auth&m=delete', 'bi bi-box-arrow-right']
        ];

        if (util::is_adm() && !util::is_acl(0)) {
            $usr[] = ['Switch to sysadm', "?o=accounts&m=switch_user&i={$_SESSION['adm']}", 'bi bi-person-fill'];
        }

        return $usr;
    }

    private function generateLogAlert(string $level, string $message) : string
    {
        elog(__METHOD__);

        $escapedMessage = htmlspecialchars($message);
        return <<<HTML
        <div class="row">
          <div class="col">
        <div class="alert alert-{$level} alert-dismissible fade show shadow-sm" role="alert">
          {$escapedMessage}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
          </div>
        </div>
        HTML;
    }
    protected function modal(array $ary) : string
    {
        elog(__METHOD__);
elog(var_export($ary, true));

        extract($ary);
        $hidden = isset($hidden) && $hidden ? $hidden : '';
        $footer = isset($footer) && $footer ? '
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">' . $footer . '</button>
        </div>' : '';
        
        return '
        <div class="modal fade" id="' . $id . '" tabindex="-1" role="dialog" aria-labelledby="' . $id . '" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">' . $title . '</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="post" action="' . $this->g->cfg['self'] . '">
                        <input type="hidden" name="c" value="' . $_SESSION['c'] . '">
                        <input type="hidden" name="o" value="' . $this->g->in['o'] . '">
                        <input type="hidden" name="m" value="' . $action . '">
                        <input type="hidden" name="i" value="' . $this->g->in['i'] . '">' . $hidden . '
                        <div class="modal-body">' . $body . '</div>' . 
                        $footer . '
                    </form>
                </div>
            </div>
        </div>';
    }
/*
    protected function modal(array $ary) : string
    {
elog(__METHOD__);

        extract($ary);
        $hidden = isset($hidden) && $hidden ? $hidden : '';
        $footer = isset($footer) && $footer ? '
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary">' . $footer . '</button>
                </div>' : '';

        return '
        <div class="modal fade" id="' . $id . '" tabindex="-1" role="dialog" aria-labelledby="' . $id . '" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">' . $title . '</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <form method="post" action="' . $this->g->cfg['self'] . '">
                <input type="hidden" name="c" value="' . $_SESSION['c'] . '">
                <input type="hidden" name="o" value="' . $this->g->in['o'] . '">
                <input type="hidden" name="m" value="' . $action . '">
                <input type="hidden" name="i" value="' . $this->g->in['i'] . '">' . $hidden . '
                <div class="modal-body">' . $body . '
                </div>' . $footer . '
              </form>
            </div>
          </div>
        </div>';
    }
*/

}
