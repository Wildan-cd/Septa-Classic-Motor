/**
 * Confirm Payment JavaScript
 * Handles payment confirmation with SweetAlert2 & WhatsApp Redirect
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // 1. ANIMASI & COPY AMOUNT (Fitur Tambahan)
    const qrisAmount = document.querySelector('.qris-amount strong');
    if (qrisAmount) {
        qrisAmount.style.cursor = 'pointer';
        qrisAmount.title = 'Click to copy amount';
        
        qrisAmount.addEventListener('click', function() {
            const amount = this.textContent.replace(/[^\d]/g, '');
            if (navigator.clipboard) {
                navigator.clipboard.writeText(amount).then(function() {
                    const originalText = qrisAmount.textContent;
                    qrisAmount.textContent = 'Copied!';
                    qrisAmount.style.color = '#10b981';
                    setTimeout(() => {
                        qrisAmount.textContent = originalText;
                        qrisAmount.style.color = '';
                    }, 2000);
                });
            }
        });
    }

    const qrisImage = document.querySelector('.qris-image');
    if (qrisImage) {
        qrisImage.style.transition = 'transform 0.3s';
        qrisImage.addEventListener('mouseenter', function() { this.style.transform = 'scale(1.05)'; });
        qrisImage.addEventListener('mouseleave', function() { this.style.transform = 'scale(1)'; });
    }

    // 2. LOGIKA TOMBOL KONFIRMASI (SweetAlert2)
    const paymentForm = document.getElementById('paymentForm');
    
    if (paymentForm) {
        paymentForm.addEventListener('submit', function(e) {
            e.preventDefault(); // Wajib: Tahan form agar tidak submit otomatis

            Swal.fire({
                title: 'Konfirmasi Pembayaran',
                text: "Apakah Anda sudah membayar via QRIS? Jika 'Ya', kami akan arahkan ke WhatsApp. Jika 'Batal', pesanan akan dibatalkan.",
                icon: 'question',
                showDenyButton: true, // Tombol Merah (Batal)
                showCancelButton: false, 
                confirmButtonText: 'Ya, Saya sudah bayar',
                denyButtonText: 'Batal / Keluar',
                confirmButtonColor: '#3085d6', // Biru
                denyButtonColor: '#d33', // Merah
            }).then((result) => {
                
                // SKENARIO 1: KLIK YA (SUDAH BAYAR)
                if (result.isConfirmed) {
                    
                    // A. Buka WhatsApp di Tab Baru (Ambil link dari variabel global waLink di Blade)
                    if (typeof waLink !== 'undefined') {
                        window.open(waLink, '_blank');
                    }

                    // B. Tampilkan Loading di Tab Web
                    Swal.fire({
                        title: 'Memproses...',
                        text: 'Sedang mengarahkan ke Beranda...',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });

                    // C. Submit Form ke Server (Update status jadi Pending & Redirect Home)
                    // Delay 1 detik agar browser sempat buka tab baru
                    setTimeout(() => {
                        paymentForm.submit(); 
                    }, 1000);
                } 
                
                // SKENARIO 2: KLIK BATAL (CANCELLED)
                else if (result.isDenied) {
                    Swal.fire({
                        title: 'Dibatalkan',
                        text: 'Pesanan Anda telah dibatalkan.',
                        icon: 'info',
                        timer: 1500,
                        showConfirmButton: false
                    });
                    
                    // Submit form pembatalan
                    const cancelForm = document.getElementById('cancelForm');
                    if(cancelForm) cancelForm.submit();
                }
            });
        });
    }
});