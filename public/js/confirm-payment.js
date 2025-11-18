/**
 * Confirm Payment JavaScript
 * Handles payment confirmation
 */

document.addEventListener('DOMContentLoaded', function() {
    const confirmBtn = document.querySelector('.btn-confirm');
    const confirmForm = confirmBtn ? confirmBtn.closest('form') : null;

    // Form submission with confirmation
    if (confirmForm) {
        confirmForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Show confirmation dialog
            const confirmed = confirm(
                'Apakah Anda sudah melakukan pembayaran via QRIS?\n\n' +
                'Setelah mengkonfirmasi, Anda akan diarahkan ke WhatsApp untuk mengirim bukti pembayaran ke admin.'
            );
            
            if (confirmed) {
                // Show loading state
                confirmBtn.disabled = true;
                confirmBtn.textContent = 'Processing...';
                confirmBtn.style.opacity = '0.7';
                
                // Submit form
                this.submit();
            }
        });
    }

    // Copy payment amount on click (optional feature)
    const qrisAmount = document.querySelector('.qris-amount strong');
    if (qrisAmount) {
        qrisAmount.style.cursor = 'pointer';
        qrisAmount.title = 'Click to copy amount';
        
        qrisAmount.addEventListener('click', function() {
            const amount = this.textContent.replace(/[^\d]/g, '');
            
            if (navigator.clipboard) {
                navigator.clipboard.writeText(amount).then(function() {
                    // Show temporary success message
                    const originalText = qrisAmount.textContent;
                    qrisAmount.textContent = 'Copied!';
                    qrisAmount.style.color = '#10b981';
                    
                    setTimeout(function() {
                        qrisAmount.textContent = originalText;
                        qrisAmount.style.color = '';
                    }, 2000);
                });
            }
        });
    }

    // Add animation to QRIS code
    const qrisImage = document.querySelector('.qris-image');
    if (qrisImage) {
        qrisImage.style.transition = 'transform 0.3s';
        
        qrisImage.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.05)';
        });
        
        qrisImage.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    }
});