<?php declare(strict_types=1);
// lib/php/themes/bootstrap5/theme.php 20150101 - 20250117
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
        <nav class="navbar navbar-expand border-bottom px-2 py-2">
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

        <main class="d-flex flex-nowrap">
            <!-- Left Sidebar -->
            <div class="d-flex flex-column flex-shrink-0 p-3 bg-body-tertiary collapse" style="width: 280px;" id="sidebarLeft">
                {$nav1}
            </div>

            <div class="b-example-divider b-example-vr"></div>

            <!-- Main Content -->
            <div class="flex-grow-1 p-3">
                {$log}
                {$main}
                {$foot}
                {$end}
            </div>

            <div class="b-example-divider b-example-vr"></div>

            <!-- Right Sidebar -->
            <div class="d-flex flex-column flex-shrink-0 p-3 bg-body-tertiary collapse" style="width: 280px;" id="sidebarRight">
                {$nav2}
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

//    <link href="lib/css/app.css" rel="stylesheet">
//    <link href="lib/css/dark-theme.css" rel="stylesheet">
//    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

/* temporary removal to see what the bootstrap5 default colors are

    <style>
    :root {
        --bg-main: #f3f4f6;
        --bg-nav: #ffffff;
        --bg-sidebar: #e8eaed;
        --bg-expanded-nav: #f0f1f3;
        --bg-input: #ffffff;
        --text-main: #000000;
        --text-nav: #000000;
        --text-sidebar: #333333;
    }
    
    [data-theme="dark"] {
        --bg-main: #2b2b2b;
        --bg-nav: #333333;
        --bg-sidebar: #252525;
        --bg-expanded-nav: #2d2d2d;
        --bg-input: #333333;
        --text-main: #ffffff;
        --text-nav: #ffffff;
        --text-sidebar: #e0e0e0;
    }

    input[type="text"],
    input[type="password"],
    input[type="email"],
    input[type="search"],
    input[type="number"],
    select,
    textarea {
        background-color: var(--bg-input);
        border: 1px solid rgba(128, 128, 128, 0.2);
        color: var(--text-main);
        padding: 0.375rem 0.75rem;
        border-radius: 0.25rem;
        transition: border-color 0.15s ease-in-out;
    }

    input[type="text"]:focus,
    input[type="password"]:focus,
    input[type="email"]:focus,
    input[type="search"]:focus,
    input[type="number"]:focus,
    select:focus,
    textarea:focus {
        border-color: rgba(128, 128, 128, 0.4);
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(128, 128, 128, 0.15);
    }

    body {
        background-color: var(--bg-main);
        color: var(--text-main);
        transition: background-color 0.3s ease-in-out, color 0.3s ease-in-out;
    }

    .navbar {
        background-color: var(--bg-nav) !important;
        border-color: rgba(128, 128, 128, 0.2) !important;
    }

    .navbar-brand, .navbar .btn-light {
        color: var(--text-nav) !important;
    }

    .navbar .btn-light {
        background-color: transparent !important;
    }

    .navbar .btn-light:hover {
        background-color: rgba(128, 128, 128, 0.1) !important;
    }

    .sidebar {
        min-height: 100vh;
        background-color: var(--bg-sidebar);
        width: 250px;
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        will-change: transform, width;
        overflow: hidden;
    }
    
    @media (min-width: 992px) {
        .sidebar:not(.show) {
            width: 0;
            padding: 0;
            margin: 0;
        }
    }
    
    @media (max-width: 991.98px) {
        .sidebar {
            position: fixed;
            top: 56px;
            left: 0;
            bottom: 0;
            z-index: 1000;
            width: 250px;
            transform: translateX(-100%);
        }
        
        .sidebar.show {
            transform: translateX(0);
        }
        
        .sidebar-right {
            left: auto !important;
            right: 0;
            transform: translateX(100%);
        }
        
        .sidebar-right.show {
            transform: translateX(0);
        }
    }
    
    .sidebar .nav-link {
        color: var(--text-sidebar);
        padding: 0.75rem 1rem;
        height: 2.75rem;
        line-height: 1.25rem;
        display: flex;
        align-items: center;
        white-space: nowrap;
        transition: all 0.3s ease-in-out;
        opacity: 1;
        width: 250px;
    }
    
    .sidebar:not(.show) .nav-link {
        opacity: 0;
        transform: translateX(-20px);
    }
    
    .sidebar .nav-link:hover {
        color: var(--text-main);
        background-color: rgba(128, 128, 128, 0.1);
    }
    
    .sidebar .nav-link.active {
        color: var(--text-main);
        background-color: rgba(128, 128, 128, 0.15);
    }
    
    .fwi {
        width: 1.5em;
        display: inline-block;
        text-align: center;
        margin-right: 0.5rem;
    }

    .table-responsive {
        width: 100%;
    }
    .table {
        width: 100% !important;
    }
    ul.pagination {
        padding-top: 1rem;
    }
    div.dataTables_wrapper div.dataTables_info {
        padding-top: 1.6rem;
    }
    </style>
    <script>
    window.hcpConfig = {
        selfUrl: {$self}
    };
    </script>
*/

        return <<<HTML
    <link href="favicon.ico" rel="icon" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://unpkg.com/bootstrap-table@1.22.1/dist/bootstrap-table.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* Theme-aware navbar styles */
        .navbar {
            background-color: var(--bs-body-bg);
            border-color: var(--bs-border-color);
        }

        .navbar .btn {
            color: var(--bs-body-color);
        }

        .navbar .btn:hover {
            background-color: var(--bs-tertiary-bg);
        }

        .navbar-brand {
            color: var(--bs-body-color);
        }

        .navbar-brand:hover {
            color: var(--bs-body-color);
        }

        /* Base styles */
        body {
            min-height: 100vh;
            min-height: -webkit-fill-available;
        }

        html {
            height: -webkit-fill-available;
        }

        main {
            height: 100vh;
            height: -webkit-fill-available;
            max-height: 100vh;
            overflow-x: auto;
            overflow-y: hidden;
        }

        .b-example-divider {
            width: 1.5rem;
            height: 100vh;
            background-color: rgba(0, 0, 0, .1);
            border: solid rgba(0, 0, 0, .15);
            border-width: 1px 0;
            box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
        }

        .b-example-vr {
            flex-shrink: 0;
            width: 1.5rem;
            height: 100vh;
        }

        .bi {
            vertical-align: -.125em;
            fill: currentColor;
        }

        .nav-flush .nav-link {
            border-radius: 0;
        }

        .btn-toggle {
            padding: .25rem .5rem;
            font-weight: 600;
            color: var(--bs-emphasis-color);
            background-color: transparent;
        }

        .btn-toggle:hover,
        .btn-toggle:focus {
            color: rgba(var(--bs-emphasis-color-rgb), .85);
            background-color: var(--bs-tertiary-bg);
        }

        .btn-toggle::before {
            width: 1.25em;
            line-height: 0;
            content: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='rgba%280,0,0,.5%29' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5 14l6-6-6-6'/%3e%3c/svg%3e");
            transition: transform .35s ease;
            transform-origin: .5em 50%;
        }

        [data-bs-theme="dark"] .btn-toggle::before {
            content: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='rgba%28255,255,255,.5%29' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5 14l6-6-6-6'/%3e%3c/svg%3e");
        }

        .btn-toggle[aria-expanded="true"] {
            color: rgba(var(--bs-emphasis-color-rgb), .85);
        }
        .btn-toggle[aria-expanded="true"]::before {
            transform: rotate(90deg);
        }

        .btn-toggle-nav a {
            padding: .1875rem .5rem;
            margin-top: .125rem;
            margin-left: 1.25rem;
        }
        .btn-toggle-nav a:hover,
        .btn-toggle-nav a:focus {
            background-color: var(--bs-tertiary-bg);
        }

        .scrollarea {
            overflow-y: auto;
        }

        .nav-link {
            color: var(--bs-emphasis-color);
            text-decoration: none;
        }

        .nav-link:hover,
        .nav-link:focus {
            color: rgba(var(--bs-emphasis-color-rgb), .85);
        }

        .nav-link.active {
            color: var(--bs-primary);
        }

        .sidebar {
            transition: transform .3s ease-in-out;
        }

        @media (max-width: 991.98px) {
            .sidebar {
                position: fixed;
                top: 56px;
                bottom: 0;
                z-index: 1000;
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .sidebar-right {
                transform: translateX(100%);
                right: 0;
                left: auto !important;
            }

            .sidebar-right.show {
                transform: translateX(0);
            }
        }

        .table-responsive {
            width: 100%;
        }
        .table {
            width: 100% !important;
        }
        ul.pagination {
            padding-top: 1rem;
        }
        div.dataTables_wrapper div.dataTables_info {
            padding-top: 1.6rem;
        }
    </style>
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
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle sidebar visibility based on screen size
        function updateSidebarVisibility() {
            const isDesktop = window.innerWidth >= 992;
            const sidebarLeft = document.getElementById('sidebarLeft');
            const sidebarRight = document.getElementById('sidebarRight');
            
            if (isDesktop) {
                sidebarLeft.classList.add('show');
                sidebarRight.classList.add('show');
            } else {
                sidebarLeft.classList.remove('show');
                sidebarRight.classList.remove('show');
            }
        }

        // Initial setup and window resize handling
        window.addEventListener('load', updateSidebarVisibility);
        window.addEventListener('resize', updateSidebarVisibility);

        // Handle left sidebar toggle state
        const sidebarLeftToggle = document.querySelector('[data-bs-target="#sidebarLeft"]');
        const sidebarLeft = document.getElementById('sidebarLeft');
        
        sidebarLeft.addEventListener('hidden.bs.collapse', () => {
            sidebarLeftToggle.classList.add('collapsed');
            sidebarLeft.classList.remove('show');
        });
        
        sidebarLeft.addEventListener('shown.bs.collapse', () => {
            sidebarLeftToggle.classList.remove('collapsed');
            sidebarLeft.classList.add('show');
        });

        // Handle right sidebar toggle state independently
        const sidebarRightToggle = document.querySelector('[data-bs-target="#sidebarRight"]');
        const sidebarRight = document.getElementById('sidebarRight');
        
        sidebarRight.addEventListener('hidden.bs.collapse', () => {
            sidebarRightToggle.classList.add('collapsed');
            sidebarRight.classList.remove('show');
        });
        
        sidebarRight.addEventListener('shown.bs.collapse', () => {
            sidebarRightToggle.classList.remove('collapsed');
            sidebarRight.classList.add('show');
        });

        // Theme toggling functionality
        const getStoredTheme = () => localStorage.getItem('theme');
        const setStoredTheme = (theme) => localStorage.setItem('theme', theme);

        const getPreferredTheme = () => {
            const storedTheme = getStoredTheme();
            if (storedTheme) {
                return storedTheme;
            }
            return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
        };

        const setTheme = (theme) => {
            if (theme === 'auto') {
                document.documentElement.setAttribute(
                    'data-bs-theme',
                    window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'
                );
            } else {
                document.documentElement.setAttribute('data-bs-theme', theme);
            }
        };

        setTheme(getPreferredTheme());

            const showActiveTheme = (theme, focus = false) => {
                const themeSwitcher = document.querySelector('#bd-theme');
                if (!themeSwitcher) {
                    return;
                }

                const activeThemeIcon = document.querySelector('.theme-icon-active');
                const btnToActive = document.querySelector('[data-bs-theme-value="' + theme + '"]');
                
                if (!btnToActive) {
                    return;
                }

                const svgOfActiveBtn = btnToActive.querySelector('i').className;

                document.querySelectorAll('[data-bs-theme-value]').forEach(element => {
                    element.classList.remove('active');
                    element.setAttribute('aria-pressed', 'false');
                });

                btnToActive.classList.add('active');
                btnToActive.setAttribute('aria-pressed', 'true');
                if (activeThemeIcon) {
                    activeThemeIcon.className = svgOfActiveBtn;
                }

                if (focus) {
                    themeSwitcher.focus();
                }
            };

        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
            const storedTheme = getStoredTheme();
            if (storedTheme !== 'light' && storedTheme !== 'dark') {
                setTheme(getPreferredTheme());
            }
        });

        document.querySelectorAll('[data-bs-theme-value]').forEach(toggle => {
            toggle.addEventListener('click', () => {
                const theme = toggle.getAttribute('data-bs-theme-value');
                setStoredTheme(theme);
                setTheme(theme);
                showActiveTheme(theme, true);
            });
        });

        showActiveTheme(getPreferredTheme());

        // Handle submenu toggling and content loading
        const submenuToggles = document.querySelectorAll('.sidebar .nav-link');
        submenuToggles.forEach(toggle => {
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const submenuId = this.getAttribute('data-bs-target');
                const submenu = submenuId ? document.querySelector(submenuId) : null;
    
                // Toggle submenu if it exists
                if (submenu) {
                    const isCurrentlyExpanded = this.getAttribute('aria-expanded') === 'true';
                    
                    // Close all submenus
                    submenuToggles.forEach(otherToggle => {
                        const otherSubmenuId = otherToggle.getAttribute('data-bs-target');
                        const otherSubmenu = otherSubmenuId ? document.querySelector(otherSubmenuId) : null;
                        if (otherSubmenu) {
                            otherSubmenu.classList.remove('show');
                            otherToggle.classList.add('collapsed');
                            otherToggle.setAttribute('aria-expanded', 'false');
                        }
                    });
    
                    // If the clicked submenu wasn't expanded, open it
                    if (!isCurrentlyExpanded) {
                        submenu.classList.add('show');
                        this.classList.remove('collapsed');
                        this.setAttribute('aria-expanded', 'true');
                    }
                }
    
                // Load content
                loadContent(this.getAttribute('href'));
            });
        });
    
        function loadContent(url) {
            fetch(url)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const mainContent = doc.querySelector('main').innerHTML;
                    document.querySelector('main').innerHTML = mainContent;
                    
                    // Reinitialize any scripts that need to run on the new content
                    reinitializeScripts();
                })
                .catch(error => console.error('Error loading content:', error));
        }
    
        function reinitializeScripts() {
            // Destroy existing DataTables
            if ($.fn.DataTable.isDataTable('.datatable')) {
                $('.datatable').DataTable().destroy();
            }
            
            // Reinitialize DataTables
            $('.datatable').DataTable({
                // Add your DataTables options here
                "pageLength": 25,
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
            });
            
            // Add any other script reinitializations here
        }
    
        // Initial script initialization
        reinitializeScripts();
    
        // Prevent Bootstrap's default collapse behavior
        const collapseElements = document.querySelectorAll('.sidebar .collapse');
        collapseElements.forEach(collapse => {
            collapse.addEventListener('show.bs.collapse', (e) => {
                e.preventDefault();
            });
            collapse.addEventListener('hide.bs.collapse', (e) => {
                e.preventDefault();
            });
        });
    });
    </script>
    HTML;
    }
      
    public function nav1(array $a = []) : string
    {
        elog(__METHOD__);

        $a = isset($a[0]) ? $a : util::get_nav($this->g->nav1);
        $o = '?o=' . $this->g->in['o'];
        $t = '?t=' . util::ses('t');

        $navItems = '';
        foreach ($a as $n) {
            if (is_array($n[1])) {
                $navItems .= $this->generateNavDropdown($n, $o, $t);
            } else {
                $navItems .= $this->generateNavItem($n, $o, $t);
            }
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
            <div class="collapse rounded-1 mx-2 mt-1" style="background-color: var(--bg-expanded-nav);" id="{$id}Submenu">
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

        if (!util::is_usr()) {
            return '';
        }

        $usr = $this->getUserNavItems();
        return $this->nav_dropdown([$_SESSION['usr']['login'], $usr, 'bi bi-person-fill']);
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
