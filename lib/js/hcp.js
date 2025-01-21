document.addEventListener('DOMContentLoaded', function() {
    const leftSidebar = document.getElementById('leftSidebar');
    const rightSidebar = document.getElementById('rightSidebar');
    const mainContent = document.getElementById('main');
    const isMobile = window.innerWidth <= 768;

    // Handle left sidebar toggle
    document.getElementById('leftSidebarToggle').addEventListener('click', function() {
        if (isMobile) {
            leftSidebar.classList.toggle('show');
            // Close right sidebar when opening left sidebar on mobile
            rightSidebar.classList.remove('show');
        } else {
            leftSidebar.classList.toggle('collapsed');
            if (rightSidebar.classList.contains('collapsed')) {
                mainContent.classList.toggle('expanded-both');
                mainContent.classList.toggle('expanded-right');
            } else {
                mainContent.classList.toggle('expanded-left');
            }
        }
    });

    // Handle right sidebar toggle
    document.getElementById('rightSidebarToggle').addEventListener('click', function() {
        if (isMobile) {
            rightSidebar.classList.toggle('show');
            // Close left sidebar when opening right sidebar on mobile
            leftSidebar.classList.remove('show');
        } else {
            rightSidebar.classList.toggle('collapsed');
            if (leftSidebar.classList.contains('collapsed')) {
                mainContent.classList.toggle('expanded-both');
                mainContent.classList.toggle('expanded-left');
            } else {
                mainContent.classList.toggle('expanded-right');
            }
        }
    });

    // Close sidebars when clicking outside on mobile
    document.addEventListener('click', function(event) {
        if (isMobile) {
            const isClickInsideLeftSidebar = leftSidebar.contains(event.target);
            const isClickInsideRightSidebar = rightSidebar.contains(event.target);
            const isClickOnLeftToggle = event.target.closest('#leftSidebarToggle');
            const isClickOnRightToggle = event.target.closest('#rightSidebarToggle');

            if (!isClickInsideLeftSidebar && !isClickOnLeftToggle && leftSidebar.classList.contains('show')) {
                leftSidebar.classList.remove('show');
            }
            if (!isClickInsideRightSidebar && !isClickOnRightToggle && rightSidebar.classList.contains('show')) {
                rightSidebar.classList.remove('show');
            }
        }
    });

    // Handle window resize
    window.addEventListener('resize', function() {
        const newIsMobile = window.innerWidth <= 768;
        if (newIsMobile !== isMobile) {
            location.reload(); // Refresh page on mobile/desktop transition
        }
    });
});
