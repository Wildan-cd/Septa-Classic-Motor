/**
 * Catalog Form JavaScript
 * Handles image preview functionality
 */

// Preview image before upload
function previewImage(event) {
    const file = event.target.files[0];
    
    if (file) {
        // Validate file type
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        if (!allowedTypes.includes(file.type)) {
            alert('File type not allowed. Please upload jpeg, jpg, or png image.');
            event.target.value = '';
            return;
        }
        
        // Validate file size (2MB)
        const maxSize = 2 * 1024 * 1024; // 2MB in bytes
        if (file.size > maxSize) {
            alert('File size too large. Maximum size is 2MB.');
            event.target.value = '';
            return;
        }
        
        // Show preview
        const reader = new FileReader();
        reader.onload = function(e) {
            const previewImg = document.getElementById('previewImg');
            const imagePreview = document.getElementById('imagePreview');
            
            previewImg.src = e.target.result;
            imagePreview.classList.add('show');
        }
        reader.readAsDataURL(file);
    }
}

// Remove image preview
function removePreview() {
    const fileInput = document.getElementById('gambar');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    
    // Reset file input
    fileInput.value = '';
    
    // Hide preview
    imagePreview.classList.remove('show');
    previewImg.src = '';
}

// Auto-hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert-error');
    
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