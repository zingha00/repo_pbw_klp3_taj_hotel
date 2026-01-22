// ===========================
// RESERVATION PAGE SCRIPTS
// ===========================

document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide success/error messages after 5 seconds
    const alerts = document.querySelectorAll('.bg-green-50, .bg-red-50, .bg-blue-50');
    
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s ease-out';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });

    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('cancel-modal');
            if (modal && !modal.classList.contains('hidden')) {
                closeCancelModal();
            }
        }
    });

    // Prevent double submission
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="inline-block animate-spin mr-2">⚪</span> Memproses...';
            }
        });
    });
});

// Show cancel modal
function showCancelModal(bookingId) {
    const modal = document.getElementById('cancel-modal');
    const form = document.getElementById('cancel-form');
    form.action = `/bookings/${bookingId}/cancel`;
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

// Close cancel modal
function closeCancelModal() {
    const modal = document.getElementById('cancel-modal');
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
}