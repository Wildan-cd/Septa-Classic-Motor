/**
 * Checkout Page JavaScript
 * Handles form validation and submission
 */

document.addEventListener('DOMContentLoaded', function() {
    const checkoutForm = document.getElementById('checkoutForm');
    const placeOrderBtn = document.querySelector('.btn-place-order');

    // Form submission
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function(e) {
            // Validate form
            const requiredFields = checkoutForm.querySelectorAll('[required]');
            let isValid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.style.borderColor = '#ef4444';
                } else {
                    field.style.borderColor = '#e5e7eb';
                }
            });

            if (!isValid) {
                e.preventDefault();
                alert('Please fill in all required fields');
                return false;
            }

            // Show loading state
            if (placeOrderBtn) {
                placeOrderBtn.classList.add('loading');
                placeOrderBtn.disabled = true;
                placeOrderBtn.textContent = 'Processing...';
            }
        });
    }

    // Email validation
    const emailInput = document.getElementById('email_address');
    if (emailInput) {
        emailInput.addEventListener('blur', function() {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (this.value && !emailRegex.test(this.value)) {
                this.style.borderColor = '#ef4444';
                alert('Please enter a valid email address');
            } else {
                this.style.borderColor = '#e5e7eb';
            }
        });
    }

    // Phone validation
    const phoneInput = document.getElementById('phone_number');
    if (phoneInput) {
        phoneInput.addEventListener('input', function() {
            // Only allow numbers, spaces, +, -, and ()
            this.value = this.value.replace(/[^\d\s\+\-\(\)]/g, '');
        });
    }

    // Auto-hide alerts
    const alerts = document.querySelectorAll('.alert-error');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.3s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });

    // Save info checkbox
    const saveInfoCheckbox = document.getElementById('save_info');
    if (saveInfoCheckbox) {
        saveInfoCheckbox.addEventListener('change', function() {
            if (this.checked) {
                console.log('Customer info will be saved');
            }
        });
    }
});