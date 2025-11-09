            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            // Auto-hide alerts after 5 seconds
            setTimeout(() => {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);

            // Confirm before delete actions
            function confirmDelete(message = 'Are you sure you want to delete this item?') {
                return confirm(message);
            }

            // Fallback: Initialize all dropdowns (in case markup/CSS interferes)
            document.addEventListener('DOMContentLoaded', function() {
                var dropdownElements = document.querySelectorAll('.dropdown-toggle');
                dropdownElements.forEach(function(el) {
                    new bootstrap.Dropdown(el);
                });
            });
        </script>
    </body>
</html>