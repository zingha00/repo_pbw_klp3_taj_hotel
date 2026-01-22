// ===================================
// SMOOTH IMAGE LOADING
// ===================================
document.addEventListener('DOMContentLoaded', function() {
    
    // Lazy load images dengan smooth transition
    const images = document.querySelectorAll('.room-card-image img');
    
    images.forEach(img => {
        // Jika gambar sudah loaded (dari cache)
        if (img.complete) {
            img.classList.add('loaded');
        } else {
            // Load gambar dengan smooth transition
            img.addEventListener('load', function() {
                setTimeout(() => {
                    this.classList.add('loaded');
                }, 100);
            });
            
            // Fallback jika error
            img.addEventListener('error', function() {
                this.classList.add('loaded');
                console.log('Image failed to load:', this.src);
            });
        }
    });

    // Preload hero image
    const heroSection = document.querySelector('.hero-section');
    if (heroSection) {
        const heroImg = new Image();
        const bgUrl = window.getComputedStyle(heroSection).backgroundImage;
        const urlMatch = bgUrl.match(/url\(['"]?([^'"]+)['"]?\)/);
        
        if (urlMatch && urlMatch[1]) {
            heroImg.src = urlMatch[1];
            heroImg.onload = function() {
                heroSection.classList.add('loaded');
            };
        }
    }
});

// ===================================
// SCROLL FADE-IN ANIMATION
// ===================================
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const fadeInObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('visible');
        }
    });
}, observerOptions);

// Apply to room cards
document.querySelectorAll('.room-card').forEach((card, index) => {
    card.classList.add('scroll-fade-in');
    card.style.transitionDelay = `${index * 0.1}s`;
    fadeInObserver.observe(card);
});

// ===================================
// PARALLAX EFFECT ON HERO
// ===================================
window.addEventListener('scroll', function() {
    const scrolled = window.pageYOffset;
    const hero = document.querySelector('.hero-section');
    
    if (hero && scrolled < window.innerHeight) {
        hero.style.transform = `translateY(${scrolled * 0.5}px)`;
    }
});

// ===================================
// SMOOTH DATE PICKER VALIDATION
// ===================================
const checkInInput = document.querySelector('input[name="check_in"]');
const checkOutInput = document.querySelector('input[name="check_out"]');

if (checkInInput && checkOutInput) {
    // Set minimum date to today
    const today = new Date().toISOString().split('T')[0];
    checkInInput.setAttribute('min', today);
    checkOutInput.setAttribute('min', today);
    
    // Update checkout min date when checkin changes
    checkInInput.addEventListener('change', function() {
        const checkInDate = new Date(this.value);
        const nextDay = new Date(checkInDate);
        nextDay.setDate(nextDay.getDate() + 1);
        
        const minCheckout = nextDay.toISOString().split('T')[0];
        checkOutInput.setAttribute('min', minCheckout);
        
        // Auto-update checkout if it's before new checkin
        if (checkOutInput.value && new Date(checkOutInput.value) <= checkInDate) {
            checkOutInput.value = minCheckout;
        }
        
        // Add smooth feedback
        this.parentElement.classList.add('updated');
        setTimeout(() => {
            this.parentElement.classList.remove('updated');
        }, 300);
    });
}

// ===================================
// ROOM CARD HOVER EFFECTS
// ===================================
document.querySelectorAll('.room-card').forEach(card => {
    card.addEventListener('mouseenter', function() {
        // Add subtle scale to amenity icons
        this.querySelectorAll('.amenity-icon').forEach((icon, i) => {
            setTimeout(() => {
                icon.style.transform = 'scale(1.2)';
            }, i * 50);
        });
    });
    
    card.addEventListener('mouseleave', function() {
        this.querySelectorAll('.amenity-icon').forEach(icon => {
            icon.style.transform = 'scale(1)';
        });
    });
});

// ===================================
// BUTTON RIPPLE EFFECT
// ===================================
document.querySelectorAll('.btn-primary, .btn-book').forEach(button => {
    button.addEventListener('click', function(e) {
        const ripple = document.createElement('span');
        const rect = this.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = e.clientX - rect.left - size / 2;
        const y = e.clientY - rect.top - size / 2;
        
        ripple.style.width = ripple.style.height = size + 'px';
        ripple.style.left = x + 'px';
        ripple.style.top = y + 'px';
        ripple.classList.add('ripple');
        
        this.appendChild(ripple);
        
        setTimeout(() => {
            ripple.remove();
        }, 600);
    });
});

// ===================================
// FORM VALIDATION FEEDBACK
// ===================================
const searchForm = document.querySelector('.checkin-box form');
if (searchForm) {
    searchForm.addEventListener('submit', function(e) {
        const button = this.querySelector('.btn-primary');
        
        // Add loading state
        button.classList.add('loading');
        button.innerHTML = `
            <svg class="animate-spin" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10" opacity="0.25"></circle>
                <path d="M12 2a10 10 0 0 1 10 10" opacity="0.75"></path>
            </svg>
            Mencari...
        `;
    });
}

// ===================================
// SMOOTH SCROLL TO ROOMS SECTION
// ===================================
const viewAllBtn = document.querySelector('.btn-view-all');
if (viewAllBtn) {
    viewAllBtn.addEventListener('click', function(e) {
        // Add scale animation before redirect
        this.style.transform = 'scale(0.95)';
        setTimeout(() => {
            this.style.transform = 'scale(1)';
        }, 150);
    });
}

// ===================================
// PERFORMANCE OPTIMIZATION
// ===================================
// Debounce scroll events for better performance
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Apply debounce to scroll handler
const handleScroll = debounce(function() {
    const scrolled = window.pageYOffset;
    const hero = document.querySelector('.hero-section');
    
    if (hero && scrolled < window.innerHeight) {
        requestAnimationFrame(() => {
            hero.style.transform = `translateY(${scrolled * 0.5}px)`;
        });
    }
}, 10);

window.addEventListener('scroll', handleScroll);

// ===================================
// ADD CSS ANIMATION KEYFRAMES
// ===================================
const style = document.createElement('style');
