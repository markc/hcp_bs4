<?php declare(strict_types=1);
// lib/php/themes/bootstrap5/theme.php 20150101 - 20250119
// Copyright (C) 2015-2025 Mark Constable <markc@renta.net> (AGPL-3.0)

class Themes_Bootstrap5_Theme extends Theme {

    public function html() : string
    {
        elog(__METHOD__);

        $output = $this->g->out;
        extract($output, EXTR_SKIP);
        return <<<HTML
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>{$doc}</title>
        {$css}
        {$js}
    </head>
    <body>
        <svg xmlns="http://www.w3.org/2000/svg" class="d-none">
            <symbol id="bootstrap" viewBox="0 0 118 94">
                <title>Bootstrap</title>
                <path fill-rule="evenodd" clip-rule="evenodd" d="M24.509 0c-6.733 0-11.715 5.893-11.492 12.284.214 6.14-.064 14.092-2.066 20.577C8.943 39.365 5.547 43.485 0 44.014v5.972c5.547.529 8.943 4.649 10.951 11.153 2.002 6.485 2.28 14.437 2.066 20.577C12.794 88.106 17.776 94 24.51 94H93.5c6.733 0 11.714-5.893 11.491-12.284-.214-6.14.064-14.092 2.066-20.577 2.009-6.504 5.396-10.624 10.943-11.153v-5.972c-5.547-.529-8.934-4.649-10.943-11.153-2.002-6.484-2.28-14.437-2.066-20.577C105.214 5.894 100.233 0 93.5 0H24.508z"/>
            </symbol>
            <symbol id="home" viewBox="0 0 16 16">
                <path d="M8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4.5a.5.5 0 0 0 .5-.5v-4h2v4a.5.5 0 0 0 .5.5H14a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146zM2.5 14V7.707l5.5-5.5 5.5 5.5V14H10v-4a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v4H2.5z"/>
            </symbol>
            <symbol id="speedometer2" viewBox="0 0 16 16">
                <path d="M8 4a.5.5 0 0 1 .5.5V6a.5.5 0 0 1-1 0V4.5A.5.5 0 0 1 8 4zM3.732 5.732a.5.5 0 0 1 .707 0l.915.914a.5.5 0 1 1-.708.708l-.914-.915a.5.5 0 0 1 0-.707zM2 10a.5.5 0 0 1 .5-.5h1.586a.5.5 0 0 1 0 1H2.5A.5.5 0 0 1 2 10zm9.5 0a.5.5 0 0 1 .5-.5h1.5a.5.5 0 0 1 0 1H12a.5.5 0 0 1-.5-.5zm.754-4.246a.389.389 0 0 0-.527-.02L7.547 9.31a.91.91 0 1 0 1.302 1.258l3.434-4.297a.389.389 0 0 0-.029-.518z"/>
                <path fill-rule="evenodd" d="M0 10a8 8 0 1 1 15.547 2.661c-.442 1.253-1.845 1.602-2.932 1.25C11.309 13.488 9.475 13 8 13c-1.474 0-3.31.488-4.615.911-1.087.352-2.49.003-2.932-1.25A7.988 7.988 0 0 1 0 10zm8-7a7 7 0 0 0-6.603 9.329c.203.575.923.876 1.68.63C4.397 12.533 6.358 12 8 12s3.604.532 4.923.96c.757.245 1.477-.056 1.68-.631A7 7 0 0 0 8 3z"/>
            </symbol>
        </svg>

        <!-- Top Navigation -->
        <nav class="navbar navbar-expand fixed-top px-2 py-2">
            <div class="container-fluid px-0">
                <div class="d-flex align-items-center">
                    <button class="btn border-0" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarLeft">
                        <i class="bi bi-list"></i>
                    </button>
                    <a class="navbar-brand mb-0 ms-2" href="#">NetServa HCP</a>
                </div>
                <div class="d-flex align-items-center gap-1">
                    <div class="dropdown">
                        <button class="btn border-0 dropdown-toggle d-flex align-items-center" type="button" id="bd-theme" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-sun theme-icon-active"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="bd-theme-text">
                            <li>
                                <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="light">
                                    <i class="bi bi-sun me-2 opacity-50"></i>
                                    Light
                                </button>
                            </li>
                            <li>
                                <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark">
                                    <i class="bi bi-moon me-2 opacity-50"></i>
                                    Dark
                                </button>
                            </li>
                            <li>
                                <button type="button" class="dropdown-item d-flex align-items-center active" data-bs-theme-value="auto">
                                    <i class="bi bi-circle-half me-2 opacity-50"></i>
                                    Auto
                                </button>
                            </li>
                        </ul>
                    </div>
                    <button class="btn border-0" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarRight">
                        <i class="bi bi-list"></i>
                    </button>
                </div>
            </div>
        </nav>

        <main class="d-flex flex-nowrap" style="padding-top: 56px;">
            <!-- Left Sidebar -->
            <div class="d-flex flex-column flex-shrink-0 p-3 sidebar collapse position-sticky overflow-y-auto" style="width: 280px; top: 56px; max-height: calc(100vh - 56px);" id="sidebarLeft">
                {$nav1}
            </div>

            <div class="b-example-vr"></div>

            <!-- Main Content -->
            <div class="flex-grow-1 py-4">
                {$log}
                {$main}
                {$foot}
                {$end}
            </div>

            <div class="b-example-vr"></div>

            <!-- Right Sidebar -->
            <div class="d-flex flex-column flex-shrink-0 p-3 sidebar collapse position-sticky overflow-y-auto" style="width: 280px; top: 56px; max-height: calc(100vh - 56px);" id="sidebarRight">
                {$nav3}
            </div>
        </main>
    </body>
</html>
HTML;
    }

   public function css() : string 
    {
        elog(__METHOD__);

        $self = json_encode($this->g->cfg['self']);

        return <<<HTML
    <link href="favicon.ico" rel="icon" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://unpkg.com/bootstrap-table@1.22.1/dist/bootstrap-table.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="lib/css/hcp.css" rel="stylesheet">
HTML;
    }

    public function js() : string
    {
        elog(__METHOD__);

        return <<<HTML
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="lib/js/hcp.js"></script>
HTML;
    }
      
    public function nav1(array $a = []) : string
    {
        elog(__METHOD__);

        $a = isset($a[0]) ? $a : util::get_nav($this->g->nav1);
        $o = '?o=' . $this->g->in['o'];
        $t = '?t=' . util::ses('t');

        // Add Sites menu from nav2
        $sites = ['Sites', $this->g->nav2, 'bi bi-globe'];
        
        // Move Menu to bottom and rename to Links
        $menu = null;
        $navItems = '';
        foreach ($a as $n) {
            if ($n[0] === 'Menu') {
                $n[0] = 'Links';
                $menu = $n;
                continue;
            }
            if (is_array($n[1])) {
                $navItems .= $this->generateNavDropdown($n, $o, $t);
            } else {
                $navItems .= $this->generateNavItem($n, $o, $t);
            }
        }

        // Add Sites menu
        $navItems .= $this->generateNavDropdown($sites, $o, $t);
        
        // Add Links (formerly Menu) at the bottom
        if ($menu) {
            $navItems .= $this->generateNavDropdown($menu, $o, $t);
        }

        // Add user profile at bottom if logged in
        if (util::is_usr()) {
            $usr = $this->getUserNavItems();
            $navItems .= '<div class="mt-auto border-top pt-3">';
            $navItems .= '<div class="dropdown">';
            $navItems .= '<a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">';
            $navItems .= '<img src="https://github.com/mdo.png" alt="" width="32" height="32" class="rounded-circle me-2">';
            $navItems .= '<strong>' . $_SESSION['usr']['login'] . '</strong>';
            $navItems .= '</a>';
            $navItems .= '<ul class="dropdown-menu dropdown-menu-dark text-small shadow">';
            foreach ($usr as $item) {
                $icon = isset($item[2]) ? "<i class=\"{$item[2]}\"></i> " : '';
                $navItems .= "<li><a class=\"dropdown-item\" href=\"{$item[1]}\">{$icon}{$item[0]}</a></li>";
            }
            $navItems .= '</ul>';
            $navItems .= '</div>';
            $navItems .= '</div>';
        }

        return $navItems;
    }

    private function generateNavDropdown(array $n, string $o, string $t) : string
    {
        //elog(__METHOD__); // too noisy

        $id = strtolower(str_replace(' ', '', $n[0]));
        $icon = isset($n[2]) ? "<i class=\"{$n[2]}\"></i>" : '';
        $items = '';
        foreach ($n[1] as $subItem) {
            $items .= $this->generateNavItem($subItem, $o, $t, 'nav-link');
        }
        return <<<HTML
        <li class="nav-item">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="collapse" data-bs-target="#{$id}Submenu" aria-expanded="false">
                {$icon} {$n[0]}
            </a>
            <div class="collapse rounded-1 mx-2 mt-1" id="{$id}Submenu">
                <ul class="nav flex-column py-2">
                    {$items}
                </ul>
            </div>
        </li>
        HTML;
    }
 
    private function generateNavItem(array $n, string $o, string $t, string $class = 'nav-link') : string
    {
        //elog(__METHOD__); // too noisy

        $c = $o === $n[1] || $t === $n[1] ? ' active' : '';
        $i = isset($n[2]) ? "<i class=\"{$n[2]}\"></i> " : '';
        return "<li class=\"nav-item\"><a class=\"{$class}{$c}\" href=\"{$n[1]}\">{$i}{$n[0]}</a></li>";
    }
 
    public function head() : string
    {
        elog(__METHOD__);

        return <<<HTML
        HTML;
    }

    public function nav2() : string
    {
        elog(__METHOD__);

        return $this->nav_dropdown(['Sites', $this->g->nav2, 'bi bi-globe'], 'r');
    }

    public function nav3() : string
    {
        elog(__METHOD__);
        return '';
    }

    public function nav_dropdown(array $a = []) : string
    {
        elog(__METHOD__);

        $o = "?o=" . $this->g->in['o'];
        $i = isset($a[2]) ? "<i class=\"{$a[2]}\"></i> " : '';

        $dropdownItems = implode('', array_map(fn($n) => $this->generateDropdownItem($n, $o), $a[1]));

        return $this->generateDropdownHtml($a[0], $i, $dropdownItems);
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

    protected function modal(array $ary) : string
    {
        elog(__METHOD__);

        return $this->generateModalHtml($ary);
    }

    protected function modal_content(array $ary) : string
    {
        elog(__METHOD__);

        extract($ary);

        $action     = $action ?? '';
        $hidden     = $hidden ?? '';
        $lhs_cmd    = $this->generateModalCommand($lhs_cmd ?? '', 'danger', 'delete');
        $mid_cmd    = $this->generateModalCommand($mid_cmd ?? '', 'info', 'help');
        $footer     = $this->generateModalFooter($rhs_cmd ?? '', $lhs_cmd, $mid_cmd);
        $body       = $this->generateModalBody($body, $footer, $action, $hidden);

        return $this->generateModalContent($title, $body);
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

    private function generateDropdownItem(array $n, string $o) : string
    {
        // elog(__METHOD__); // too noisy

        $tmp = isset($n[3]) ? "?r={$this->g->in[$n[3]]}" : $o;
        $c = ($tmp === $n[1]) ? ' active' : '';
        $i = isset($n[2]) ? "<i class=\"{$n[2]}\"></i> " : '';
        return "<a class=\"dropdown-item{$c}\" href=\"{$n[1]}\">{$i}{$n[0]}</a>";
    }

    private function generateDropdownHtml(string $label, string $icon, string $items) : string
    {
        // elog(__METHOD__); // too noisy

        return <<<HTML
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">{$icon}{$label}</a>
          <div class="dropdown-menu shadow-sm border-0">{$items}</div>
        </li>
        HTML;
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
    private function generateModalHtml(array $ary) : string
    {
        elog(__METHOD__);

        $id = $ary['id'];
        $content = $this->modal_content($ary);
        return <<<HTML
        <div class="modal fade" id="{$id}" tabindex="-1" aria-labelledby="{$id}Label" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                {$content}
            </div>
        </div>
        HTML;
    }

    private function generateModalCommand(string $cmd, string $class, string $action) : string
    {
        elog(__METHOD__);

        if (empty($cmd)) {
            return '';
        }
        return "<button type=\"button\" class=\"btn btn-{$class} bslink\" data-bs-action=\"{$action}\">{$cmd}</button>";
    }

    private function generateModalFooter(string $rhs_cmd, string $lhs_cmd, string $mid_cmd) : string
    {
        elog(__METHOD__);

        if (empty($rhs_cmd)) {
            return '';
        }
        return <<<HTML
        <div class="modal-footer d-flex justify-content-between">
            {$lhs_cmd}
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            {$mid_cmd}
            <button type="submit" class="btn btn-primary">{$rhs_cmd}</button>
        </div>
        HTML;
    }

    private function generateModalBody(string $body, string $footer, string $action, string $hidden) : string
    {
        elog(__METHOD__);

        $bodyContent = "<div class=\"modal-body\">{$body}</div>";
        if (empty($footer)) {
            return $bodyContent;
        }
        return <<<HTML
        <form method="post" action="{$this->g->cfg['self']}">
            <input type="hidden" name="c" value="{$_SESSION['c']}">
            <input type="hidden" name="o" value="{$this->g->in['o']}">
            <input type="hidden" name="m" value="{$action}">
            <input type="hidden" name="i" value="{$this->g->in['i']}">
            {$hidden}
            {$bodyContent}
            {$footer}
        </form>
        HTML;
    }

    private function generateModalContent(string $title, string $body) : string
    {
        elog(__METHOD__);

        return <<<HTML
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="{$this->g->in['o']}ModalLabel">{$title}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {$body}
        </div>
        HTML;
    }
}
