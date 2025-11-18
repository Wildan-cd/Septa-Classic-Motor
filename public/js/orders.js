/**
 * Orders List JavaScript
 * Handles order list interactions
 */

// View order detail
function viewOrderDetail(id) {
    window.location.href = `/admin/orders/${id}`;
}

// Select all checkboxes
const selectAllCheckbox = document.getElementById('selectAll');
if (selectAllCheckbox) {
    selectAllCheckbox.addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.orders-table tbody input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });
}

// Auto-hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert-success, .alert-error');
    
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.3s';
            alert.style.opacity = '0';
            
            setTimeout(() => {
                alert.remove();
            }, 300);
        }, 5000);
    });
});

// Handle date filter
const dateInputs = document.querySelectorAll('.date-input');
dateInputs.forEach(input => {
    input.addEventListener('change', function() {
        // Auto submit form when date changes (optional)
        // this.closest('form').submit();
    });
});