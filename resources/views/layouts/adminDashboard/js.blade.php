<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Update UK time function
        function updateUKTime() {
            const options = {
                timeZone: 'Europe/London',
                weekday: 'short',
                day: 'numeric',
                month: 'short',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            };

            const now = new Date();
            const ukTime = now.toLocaleString('en-GB', options);
            document.getElementById('uk-time').textContent = ukTime;
        }

        // Update time immediately and then every second
        updateUKTime();
        setInterval(updateUKTime, 1000);

        // Toggle sidebar collapse
        const toggleBtn = document.querySelector('.toggle-btn');
        const sidebar = document.querySelector('.sidebar');
        const mainContent = document.querySelector('.main-content');

        toggleBtn.addEventListener('click', function() {
            sidebar.classList.toggle('sidebar-collapsed');
        });

        // Mobile menu toggle
        const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
        mobileMenuBtn.addEventListener('click', function() {
            sidebar.classList.toggle('active');
        });

        // Menu item with submenu click handler
        const menuItems = document.querySelectorAll('.menu-item');
        menuItems.forEach(item => {
            if (item.classList.contains('open')) {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    this.classList.toggle('open');
                });
            }
        });

        // User dropdown toggle
        const userProfile = document.querySelector('.user-profile');
        userProfile.addEventListener('click', function(e) {
            e.stopPropagation();
            this.classList.toggle('active');
        });

        // Sessions dropdown toggle
        const sessionsBtn = document.querySelector('.sessions-btn');
        sessionsBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            this.classList.toggle('active');
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', function() {
            userProfile.classList.remove('active');
            sessionsBtn.classList.remove('active');
        });

        // Prevent dropdown from closing when clicking inside
        document.querySelectorAll('.dropdown-menu, .sessions-menu').forEach(element => {
            element.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        });

        // Session logout buttons
        document.querySelectorAll('.session-logout').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                // Here you would typically make an AJAX call to log out that session
                const sessionItem = this.closest('.session-item');
                sessionItem.style.opacity = '0';
                setTimeout(() => {
                    sessionItem.remove();
                }, 300);
            });
        });



    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. First, handle the automatic expansion of parent menus with active submenu items
        const activeSubmenuItems = document.querySelectorAll('.submenu-item.active');

        activeSubmenuItems.forEach(activeItem => {
            // Find the parent submenu
            const submenu = activeItem.closest('.submenu');
            if (submenu) {
                // Find the preceding menu-item
                const menuItem = submenu.previousElementSibling;
                if (menuItem && menuItem.classList.contains('menu-item')) {
                    // Expand the parent menu
                    menuItem.classList.add('open');
                    submenu.style.maxHeight = submenu.scrollHeight + 'px';
                }
            }
        });

        // 2. Then add the click handlers for manual expansion/collapse
        const menuItems = document.querySelectorAll('.menu-item');

        menuItems.forEach(item => {
            const submenu = item.nextElementSibling;
            if (submenu && submenu.classList.contains('submenu')) {
                item.addEventListener('click', function(e) {
                    e.preventDefault();

                    // Close all other open submenus first
                    document.querySelectorAll('.menu-item.open').forEach(openItem => {
                        if (openItem !== item) {
                            openItem.classList.remove('open');
                            openItem.nextElementSibling.style.maxHeight = '0';
                        }
                    });

                    // Toggle this submenu
                    item.classList.toggle('open');
                    if (item.classList.contains('open')) {
                        submenu.style.maxHeight = submenu.scrollHeight + 'px';
                    } else {
                        submenu.style.maxHeight = '0';
                    }
                });
            }
        });
    });
    </script>
