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
