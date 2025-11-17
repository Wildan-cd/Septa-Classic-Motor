/**
 * Catalog Page JavaScript
 * Handles dropdown menus and delete modal
 */

// Toggle dropdown menu
function toggleMenu(id) {
    const menu = document.getElementById(`menu-${id}`);
    const allMenus = document.querySelectorAll('[id^="menu-"]');
    
    // Close all other menus
    allMenus.forEach(m => {
        if (m.id !== `menu-${id}`) {
            m.classList.remove('show');
        }
    });
    
    // Toggle current menu
    menu.classList.toggle('show');
}

// Show delete modal
function deleteProduct(id) {
    const modal = document.getElementById('deleteModal');
    const form = document.getElementById('deleteForm');
    
    // Set form action
    form.action = `/admin/catalog/${id}`;
    
    // Show modal
    modal.classList.add('show');
    
    // Close menu
    const menu = document.getElementById(`menu-${id}`);
    if (menu) {
        menu.classList.remove('show');
    }
}

// Close delete modal
function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    modal.classList.remove('show');
}

// Close menus when clicking outside
document.addEventListener('click', function(event) {
    const isMenuButton = event.target.closest('.menu-toggle');
    const isMenuContent = event.target.closest('.dropdown-menu');
    
    if (!isMenuButton && !isMenuContent) {
        const allMenus = document.querySelectorAll('.dropdown-menu');
        allMenus.forEach(menu => {
            menu.classList.remove('show');
        });
    }
});

// Close modal when clicking outside
const deleteModal = document.getElementById('deleteModal');
if (deleteModal) {
    deleteModal.addEventListener('click', function(event) {
        if (event.target === this) {
            closeDeleteModal();
        }
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