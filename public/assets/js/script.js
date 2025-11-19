// Custom JavaScript for Jacaranda Libraries

document.addEventListener('DOMContentLoaded', function() {
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

    // Attach confirmDelete to all delete forms
    const deleteForms = document.querySelectorAll('form[onsubmit]');
    deleteForms.forEach(form => {
        if (form.getAttribute('onsubmit').includes('confirmDelete')) {
            form.onsubmit = () => confirmDelete();
        }
    });
});