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

// Confirm status change
const statusForm = document.querySelector('.status-form');
if (statusForm) {
    statusForm.addEventListener('submit', function(e) {
        const selectedStatus = this.querySelector('[name="status_pembayaran"]').value;
        if (!selectedStatus) {
            e.preventDefault();
            alert('Please select a status');
            return false;
        }
    });
}

// Save button functionality (can be customized)
const btnSave = document.querySelector('.btn-save');
if (btnSave) {
    btnSave.addEventListener('click', function() {
        alert('Order details saved!');
    });
}

// View profile button
const btnViewProfile = document.querySelector('.btn-view-profile');
if (btnViewProfile) {
    btnViewProfile.addEventListener('click', function() {
        alert('View customer profile feature coming soon!');
    });
}

// Download info button
const btnDownloadInfo = document.querySelector('.btn-download-info');
if (btnDownloadInfo) {
    btnDownloadInfo.addEventListener('click', function() {
        alert('Download order info feature coming soon!');
    });
}

// View map button
const btnViewMap = document.querySelector('.btn-view-map');
if (btnViewMap) {
    btnViewMap.addEventListener('click', function() {
        alert('View delivery map feature coming soon!');
    });
}