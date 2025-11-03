// Mobile Menu Toggle
// const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
// const nav = document.querySelector('.nav');

// if (mobileMenuBtn) {
//     mobileMenuBtn.addEventListener('click', () => {
//         nav.classList.toggle('active');
//         mobileMenuBtn.classList.toggle('active');
//     });
// }

mobileMenuBtn.addEventListener('click', function(e) {
    e.preventDefault();        // Prevent default behavior
    e.stopPropagation();      // Stop event bubbling
    
    nav.classList.toggle('active');
    mobileMenuBtn.classList.toggle('active');
    
    // Handle body scroll
    if (nav.classList.contains('active')) {
        document.body.style.overflow = 'hidden';
    } else {
        document.body.style.overflow = '';
    }
});





// Search Functionality
const searchBtn = document.querySelector('.search-btn');
const searchInput = document.querySelector('.search-box input');

if (searchBtn && searchInput) {
    searchBtn.addEventListener('click', (e) => {
        e.preventDefault();
        const query = searchInput.value.trim();
        if (query) {
            window.location.href = `/catalog?search=${encodeURIComponent(query)}`;
        }
    });

    searchInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            const query = searchInput.value.trim();
            if (query) {
                window.location.href = `/catalog?search=${encodeURIComponent(query)}`;
            }
        }
    });
}

// Smooth Scroll
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        const href = this.getAttribute('href');
        if (href !== '#' && document.querySelector(href)) {
            e.preventDefault();
            document.querySelector(href).scrollIntoView({
                behavior: 'smooth'
            });
        }
    });
});

// Header Scroll Effect
let lastScroll = 0;
const header = document.querySelector('.header');

window.addEventListener('scroll', () => {
    const currentScroll = window.pageYOffset;
    
    if (currentScroll > 100) {
        header.style.boxShadow = '0 2px 20px rgba(0,0,0,0.2)';
    } else {
        header.style.boxShadow = '0 2px 10px rgba(0,0,0,0.1)';
    }
    
    lastScroll = currentScroll;
});

// Product Card Animation on Scroll
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

// Observe product cards
document.querySelectorAll('.product-card').forEach((card, index) => {
    card.style.opacity = '0';
    card.style.transform = 'translateY(20px)';
    card.style.transition = `all 0.5s ease ${index * 0.1}s`;
    observer.observe(card);
});

// Observe category cards
document.querySelectorAll('.category-card').forEach((card, index) => {
    card.style.opacity = '0';
    card.style.transform = 'translateY(20px)';
    card.style.transition = `all 0.5s ease ${index * 0.1}s`;
    observer.observe(card);
});

// Shopping Cart Counter (if needed)
function updateCartCount() {
    const cartCount = localStorage.getItem('cartCount') || 0;
    const cartBtn = document.querySelector('.icon-btn');
    if (cartBtn && cartCount > 0) {
        let badge = cartBtn.querySelector('.cart-badge');
        if (!badge) {
            badge = document.createElement('span');
            badge.className = 'cart-badge';
            cartBtn.style.position = 'relative';
            cartBtn.appendChild(badge);
        }
        badge.textContent = cartCount;
    }
}

// Initialize cart count on page load
updateCartCount();

// Add to cart functionality (example)
document.querySelectorAll('.product-card').forEach(card => {
    card.addEventListener('click', (e) => {
        if (!e.target.classList.contains('add-to-cart-btn')) {
            // Navigate to product detail
            // window.location.href = `/product/${card.dataset.productId}`;
        }
    });
});

// Active Navbar
document.addEventListener('DOMContentLoaded', function() {
    const currentUrl = window.location.pathname;
    const navLinks = document.querySelectorAll('.nav-link');
    
    navLinks.forEach(link => {
        const linkHref = link.getAttribute('href');
        
        // Check if current URL matches
        if (currentUrl === linkHref || 
            (linkHref !== '/' && currentUrl.startsWith(linkHref))) {
            link.classList.add('active');
        }
    });
});