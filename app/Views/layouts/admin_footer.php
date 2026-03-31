            </main><!-- /admin-main -->
        </div><!-- /admin-content -->
    </div><!-- /admin-wrapper -->

    <!-- Bootstrap JS -->
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>

    <!-- Sidebar Toggle Script -->
    <script>
        (function() {
            'use strict';

            const sidebar = document.getElementById('adminSidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarOverlay = document.getElementById('sidebarOverlay');

            if (sidebarToggle && sidebar && sidebarOverlay) {
                // Toggle sidebar on mobile
                sidebarToggle.addEventListener('click', function() {
                    const isActive = sidebar.classList.toggle('active');
                    sidebarOverlay.classList.toggle('active');
                    sidebarToggle.setAttribute('aria-expanded', isActive);
                });

                // Close sidebar when clicking overlay
                sidebarOverlay.addEventListener('click', function() {
                    sidebar.classList.remove('active');
                    sidebarOverlay.classList.remove('active');
                    sidebarToggle.setAttribute('aria-expanded', 'false');
                });

                // Close sidebar on ESC key
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && sidebar.classList.contains('active')) {
                        sidebar.classList.remove('active');
                        sidebarOverlay.classList.remove('active');
                        sidebarToggle.setAttribute('aria-expanded', 'false');
                    }
                });
            }

            // Auto-close alerts after 5 seconds
            const alerts = document.querySelectorAll('.admin-alert, .alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
        })();
    </script>
</body>
</html>