// ===========================
// ADMIN BOOKING PAGE SCRIPTS
// ===========================

document.addEventListener('DOMContentLoaded', function() {
    // Initialize event listeners
    initializeEventListeners();
});

function initializeEventListeners() {
    // View detail buttons
    document.querySelectorAll('.btn-view-detail').forEach(btn => {
        btn.addEventListener('click', function() {
            viewDetail(this.getAttribute('data-booking-id'));
        });
    });

    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAllModals();
        }
    });

    // ✅ PERBAIKAN: Pasang event listener ke tombol Restore & Delete
    attachActionButtonListeners();
}

// ✅ FUNGSI BARU: Pasang event listener ke tombol aksi
function attachActionButtonListeners() {
    const restoreBtn = document.getElementById('restore-booking-btn');
    const deleteBtn = document.getElementById('permanent-delete-btn');
    
    if (restoreBtn) {
        // Remove existing listeners to prevent duplication
        restoreBtn.replaceWith(restoreBtn.cloneNode(true));
        const newRestoreBtn = document.getElementById('restore-booking-btn');
        
        newRestoreBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('🔄 Restore button clicked!');
            restoreBooking();
        });
        console.log('✅ Restore button listener attached');
    } else {
        console.warn('⚠️ Restore button not found');
    }
    
    if (deleteBtn) {
        // Remove existing listeners to prevent duplication
        deleteBtn.replaceWith(deleteBtn.cloneNode(true));
        const newDeleteBtn = document.getElementById('permanent-delete-btn');
        
        newDeleteBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('🗑️ Delete button clicked!');
            permanentDeleteBooking();
        });
        console.log('✅ Delete button listener attached');
    } else {
        console.warn('⚠️ Delete button not found');
    }
}

// ============================================
// BOOKING DETAIL FUNCTIONS
// ============================================

let currentBookingId = null;

function viewDetail(id) {
    currentBookingId = id;
    fetch(`/admin/bookings/${id}`)
        .then(r => r.json())
        .then(data => {
            populateDetailModal(data);
            document.getElementById('detail-modal').classList.remove('hidden');
        })
        .catch(err => {
            console.error('Error fetching booking details:', err);
            showAlert('error', 'Gagal memuat detail booking');
        });
}

function populateDetailModal(data) {
    document.getElementById('detail-booking-code').textContent = data.booking_code;
    document.getElementById('detail-room').textContent = data.room?.name || '-';
    document.getElementById('detail-guest-name').textContent = data.user_name;
    document.getElementById('detail-guest-email').textContent = data.user_email;
    document.getElementById('detail-checkin').textContent = data.check_in;
    document.getElementById('detail-checkout').textContent = data.check_out;
    document.getElementById('detail-guests').textContent = data.guests + ' orang';
    document.getElementById('detail-total').textContent = data.total_price;
    document.getElementById('detail-proof-image').src = data.payment?.proof || '';
}

function closeDetailModal() {
    document.getElementById('detail-modal').classList.add('hidden');
    currentBookingId = null;
}

// ============================================
// BOOKING CONFIRMATION FUNCTIONS
// ============================================

function jsonHeaders() {
    return {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    };
}

async function parseJsonOrThrow(response) {
    const contentType = response.headers.get('content-type') || '';
    const isJson = response.headers.get('content-type')?.startsWith('application/json');

    if (!response.ok) {
        // Kalau error tapi JSON, ambil message-nya
        if (isJson) {
            const errData = await response.json().catch(() => ({}));
            throw new Error(errData.message || `HTTP ${response.status}: ${response.statusText}`);
        }
        // Kalau error HTML
        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
    }

    // OK tapi bukan JSON (biasanya karena redirect HTML) => ini yang bikin notif salah
    if (!isJson) {
        throw new Error('Respon server bukan JSON. Pastikan controller mengembalikan JSON untuk request AJAX.');
    }

    return response.json();
}


function confirmBooking() {
    if (!currentBookingId) return;

    fetch(`/admin/bookings/${currentBookingId}/confirm`, {
        method: 'POST',
        headers: jsonHeaders()
    })
    .then(parseJsonOrThrow)
    .then(data => {
        if (data.success) {
            sessionStorage.setItem('booking_success', data.message || 'Booking berhasil dikonfirmasi!');
            closeDetailModal();
            location.reload();
        } else {
            closeDetailModal();
            showAlert('error', data.message || 'Gagal mengonfirmasi booking.');
        }
    })
    .catch(err => {
        sessionStorage.setItem('booking_error', err.message || 'Gagal mengonfirmasi booking. Silakan coba lagi.');
        closeDetailModal();
        location.reload();
    });
}


// ============================================
// BOOKING REJECTION FUNCTIONS
// ============================================

function showRejectModal() {
    if (!currentBookingId) return;
    document.getElementById('reject-booking-id').value = currentBookingId;
    document.getElementById('rejection-reason').value = '';
    document.getElementById('reject-modal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('reject-modal').classList.add('hidden');
}

function submitReject(e) {
    e.preventDefault();
    const id = document.getElementById('reject-booking-id').value;
    const reason = document.getElementById('rejection-reason').value;

    if (!reason.trim()) {
        showAlert('error', 'Alasan penolakan harus diisi');
        return;
    }

    fetch(`/admin/bookings/${id}/reject`, {
        method: 'POST',
        headers: jsonHeaders(),
        body: JSON.stringify({ rejection_reason: reason })
    })
    .then(parseJsonOrThrow)
    .then(data => {
        if (data.success) {
            sessionStorage.setItem('booking_success', data.message || 'Pembayaran berhasil ditolak!');
            closeRejectModal();
            closeDetailModal();
            location.reload();
        } else {
            closeRejectModal();
            closeDetailModal();
            showAlert('error', data.message || 'Gagal menolak booking.');
        }
    })
    .catch(err => {
        sessionStorage.setItem('booking_error', err.message || 'Terjadi kesalahan saat menolak booking. Silakan coba lagi.');
        closeRejectModal();
        closeDetailModal();
        location.reload();
    });
}


// ============================================
// BOOKING CANCELLATION FUNCTIONS
// ============================================

function openCancelModal(bookingId) {
    document.getElementById('cancel-booking-id').value = bookingId;
    document.getElementById('cancellation-reason').value = '';
    document.getElementById('char-count').textContent = '0';
    document.getElementById('cancel-modal').classList.remove('hidden');
    
    setTimeout(() => {
        document.getElementById('cancellation-reason').focus();
    }, 100);
    
    const textarea = document.getElementById('cancellation-reason');
    const charCount = document.getElementById('char-count');
    
    textarea.addEventListener('input', function() {
        const count = this.value.length;
        charCount.textContent = count;
        
        if (count > 450) {
            charCount.style.color = '#ef4444';
        } else if (count > 400) {
            charCount.style.color = '#f59e0b';
        } else {
            charCount.style.color = '#6b7280';
        }
    });
}

function closeCancelModal() {
    document.getElementById('cancel-modal').classList.add('hidden');
    document.getElementById('cancellation-reason').value = '';
    document.getElementById('char-count').textContent = '0';
    document.getElementById('char-count').style.color = '#6b7280';
    
    const submitBtn = document.getElementById('cancel-submit-btn');
    const btnText = document.getElementById('cancel-btn-text');
    const btnLoading = document.getElementById('cancel-btn-loading');
    
    submitBtn.disabled = false;
    btnText.classList.remove('hidden');
    btnLoading.classList.add('hidden');
}

function submitCancel(e) {
    e.preventDefault();

    const id = document.getElementById('cancel-booking-id').value;
    const reason = document.getElementById('cancellation-reason').value.trim();

    if (!reason) {
        showAlert('error', 'Alasan pembatalan harus diisi!');
        document.getElementById('cancellation-reason').focus();
        return;
    }

    if (reason.length < 10) {
        showAlert('error', 'Alasan pembatalan minimal 10 karakter!');
        document.getElementById('cancellation-reason').focus();
        return;
    }

    const submitBtn = document.getElementById('cancel-submit-btn');
    const btnText = document.getElementById('cancel-btn-text');
    const btnLoading = document.getElementById('cancel-btn-loading');

    submitBtn.disabled = true;
    btnText.classList.add('hidden');
    btnLoading.classList.remove('hidden');

    fetch(`/admin/bookings/${id}/cancel`, {
        method: 'POST',
        headers: jsonHeaders(),
        body: JSON.stringify({ cancellation_reason: reason })
    })
    .then(parseJsonOrThrow)
    .then(data => {
        if (data.success) {
            sessionStorage.setItem('booking_success', data.message || 'Booking berhasil dibatalkan!');
            closeCancelModal();
            location.reload();
        } else {
            closeCancelModal();
            showAlert('error', data.message || 'Terjadi kesalahan');
        }
    })
    .catch(err => {
        sessionStorage.setItem('booking_error', err.message || 'Terjadi kesalahan saat membatalkan booking. Silakan coba lagi.');
        closeCancelModal();
        location.reload();
    })
    .finally(() => {
        submitBtn.disabled = false;
        btnText.classList.remove('hidden');
        btnLoading.classList.add('hidden');
    });
}


// ============================================
// RESTORE & PERMANENT DELETE FUNCTIONS
// ============================================

let currentReasonBookingId = null;

function restoreBooking() {
    console.log('🔄 restoreBooking() called, currentReasonBookingId:', currentReasonBookingId);

    if (!currentReasonBookingId) {
        showAlert('error', 'ID booking tidak ditemukan. Silakan refresh halaman dan coba lagi.');
        return;
    }

    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        showAlert('error', 'CSRF token tidak ditemukan. Silakan refresh halaman.');
        return;
    }

    const restoreBtn = document.getElementById('restore-booking-btn');
    const originalBtnContent = restoreBtn ? restoreBtn.innerHTML : null;

    if (restoreBtn) {
        restoreBtn.disabled = true;
        restoreBtn.style.opacity = '0.7';
        restoreBtn.innerHTML = `
            <svg class="animate-spin w-4 h-4 inline mr-2" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Memproses...
        `;
    }

    fetch(`/admin/bookings/${currentReasonBookingId}/restore`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken.content,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(async (response) => {
        if (!response.ok) {
            const text = await response.text();
            console.error('❌ Restore error:', response.status, text);

            let errorMessage = `HTTP ${response.status}: ${response.statusText}`;
            switch (response.status) {
                case 404: errorMessage = 'Booking tidak ditemukan. Mungkin sudah dihapus.'; break;
                case 403: errorMessage = 'Tidak memiliki izin untuk melakukan aksi ini.'; break;
                case 419: errorMessage = 'Session expired. Silakan refresh halaman dan login kembali.'; break;
                case 422: errorMessage = 'Data tidak valid. Booking mungkin tidak dalam status yang tepat.'; break;
                case 500: errorMessage = 'Terjadi kesalahan server. Silakan coba lagi.'; break;
            }
            throw new Error(errorMessage);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // ✅ PERBAIKAN: Simpan success message ke sessionStorage
            sessionStorage.setItem('booking_success', data.message || 'Booking berhasil di-restore!');
            closeReasonModal();
            location.reload();
        } else {
            closeReasonModal();
            showAlert('error', data.message || 'Gagal me-restore booking.');
        }
    })
    .catch(err => {
        // ✅ PERBAIKAN: Simpan error message ke sessionStorage
        sessionStorage.setItem('booking_error', err.message || 'Terjadi kesalahan saat me-restore booking.');
        closeReasonModal();
        location.reload();
    })
    .finally(() => {
        if (restoreBtn && originalBtnContent) {
            restoreBtn.disabled = false;
            restoreBtn.style.opacity = '1';
            restoreBtn.innerHTML = originalBtnContent;
        }
    });
}

function quickRestoreBooking(id) {
    if (!id) {
        showAlert('error', 'ID booking tidak valid.');
        return;
    }

    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        showAlert('error', 'CSRF token tidak ditemukan. Silakan refresh halaman.');
        return;
    }

    // Konfirmasi dulu
    if (!confirm('Yakin ingin me-restore booking ini kembali ke status Terkonfirmasi?')) {
        return;
    }

    fetch(`/admin/bookings/${id}/restore`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken.content,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(async (response) => {
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            sessionStorage.setItem('booking_success', data.message || 'Booking berhasil di-restore!');
            location.reload();
        } else {
            showAlert('error', data.message || 'Gagal me-restore booking.');
        }
    })
    .catch(err => {
        sessionStorage.setItem('booking_error', err.message || 'Terjadi kesalahan saat me-restore booking.');
        location.reload();
    });
}

function permanentDeleteBooking() {
    console.log('🗑️ permanentDeleteBooking() called, currentReasonBookingId:', currentReasonBookingId);

    if (!currentReasonBookingId) {
        showAlert('error', 'ID booking tidak ditemukan');
        return;
    }

    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        showAlert('error', 'CSRF token tidak ditemukan. Silakan refresh halaman.');
        return;
    }

    const deleteBtn = document.getElementById('permanent-delete-btn');
    if (!deleteBtn) {
        showAlert('error', 'Tombol hapus tidak ditemukan di halaman.');
        return;
    }

    // ✅ Klik 2x untuk konfirmasi (tanpa popup)
    if (!deleteBtn.dataset.confirmed) {
        deleteBtn.dataset.confirmed = '1';
        const original = deleteBtn.innerHTML;
        deleteBtn.dataset.originalHtml = original;

        deleteBtn.innerHTML = `
            <span class="font-semibold">⚠️ Klik lagi untuk KONFIRMASI</span>
        `;

        showAlert('error', 'Mode konfirmasi aktif: klik tombol "Hapus Permanen" sekali lagi dalam 5 detik.');

        setTimeout(() => {
            if (deleteBtn.dataset.confirmed) {
                deleteBtn.removeAttribute('data-confirmed');
                if (deleteBtn.dataset.originalHtml) {
                    deleteBtn.innerHTML = deleteBtn.dataset.originalHtml;
                    deleteBtn.removeAttribute('data-original-html');
                }
            }
        }, 5000);

        return;
    }

    // Klik kedua: lanjut hapus
    deleteBtn.disabled = true;
    deleteBtn.innerHTML = `
        <svg class="animate-spin w-4 h-4 inline mr-2" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Menghapus...
    `;

    fetch(`/admin/bookings/${currentReasonBookingId}/force`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken.content,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(async (response) => {
        if (!response.ok) {
            const text = await response.text();
            console.error('❌ Delete error:', response.status, text);
            throw new Error(`Gagal menghapus (HTTP ${response.status}).`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // ✅ PERBAIKAN: Simpan success message ke sessionStorage
            sessionStorage.setItem('booking_success', data.message || 'Booking berhasil dihapus permanen!');
            closeReasonModal();
            location.reload();
        } else {
            closeReasonModal();
            showAlert('error', data.message || 'Gagal menghapus booking.');
        }
    })
    .catch(err => {
        // ✅ PERBAIKAN: Simpan error message ke sessionStorage
        sessionStorage.setItem('booking_error', err.message || 'Terjadi kesalahan saat menghapus booking.');
        closeReasonModal();
        location.reload();
    })
    .finally(() => {
        deleteBtn.disabled = false;
        deleteBtn.removeAttribute('data-confirmed');

        if (deleteBtn.dataset.originalHtml) {
            deleteBtn.innerHTML = deleteBtn.dataset.originalHtml;
            deleteBtn.removeAttribute('data-original-html');
        } else {
            deleteBtn.innerHTML = 'Hapus Permanen';
        }
    });
}

// ============================================
// ALERT SYSTEM
// ============================================

function showAlert(type, message) {
    const existingAlerts = document.querySelectorAll('.alert-message');
    existingAlerts.forEach(alert => alert.remove());

    const alertClass = type === 'success' ? 'bg-green-50 border-green-200 text-green-800' : 
                      type === 'error' ? 'bg-red-50 border-red-200 text-red-800' : 
                      'bg-blue-50 border-blue-200 text-blue-800';
    
    const alertHtml = `
        <div class="alert-message ${alertClass} border px-4 py-3 rounded-lg mb-6 flex items-center gap-3">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                ${type === 'success' ? 
                    '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />' :
                    '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />'
                }
            </svg>
            <span>${message}</span>
        </div>
    `;
    
    const contentArea = document.querySelector('.px-4.sm\\:px-6.lg\\:px-8.py-8');
    if (contentArea) {
        contentArea.insertAdjacentHTML('afterbegin', alertHtml);
        
        setTimeout(() => {
            const alert = contentArea.querySelector('.alert-message');
            if (alert) {
                alert.style.transition = 'opacity 0.5s ease-out';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }
        }, 5000);
    }
}

// ✅ PERBAIKAN BARU: Tampilkan alert dari sessionStorage setelah reload
function removeBladeAlerts() {
    // Hapus alert dari Blade (success/error) kalau ada
    document.querySelectorAll(
        '.alert-message,' +
        '.bg-green-50.border-green-200,' +
        '.bg-red-50.border-red-200'
    ).forEach(el => el.remove());
}

function showStoredAlert() {
    const successMsg = sessionStorage.getItem('booking_success');
    const errorMsg = sessionStorage.getItem('booking_error');

    // Bersihkan alert HTML lama
    document.querySelectorAll('.alert-message').forEach(el => el.remove());

    if (successMsg) {
        showAlert('success', successMsg);

        // 🔥 PENTING: sukses harus hapus error
        sessionStorage.removeItem('booking_success');
        sessionStorage.removeItem('booking_error');
        return;
    }

    if (errorMsg) {
        showAlert('error', errorMsg);
        sessionStorage.removeItem('booking_error');
    }
}


document.addEventListener('DOMContentLoaded', function () {
    // bersihkan error lama setiap load
    sessionStorage.removeItem('booking_error');

    initializeEventListeners();
    showStoredAlert();
});



// ============================================
// VIEW REASON FUNCTIONS
// ============================================

function viewReason(id) {
    console.log('🔍 viewReason called with ID:', id);
    
    currentReasonBookingId = id;
    console.log('📋 currentReasonBookingId set to:', currentReasonBookingId);
    
    if (!id || isNaN(id)) {
        console.error('❌ Invalid booking ID provided:', id);
        showAlert('error', 'ID booking tidak valid.');
        return;
    }
    
    const modal = document.getElementById('reason-modal');
    if (!modal) {
        console.error('❌ Reason modal not found');
        showAlert('error', 'Modal tidak ditemukan. Silakan refresh halaman.');
        return;
    }
    
    const titleElement = document.querySelector('#reason-modal h3');
    const contentElement = document.getElementById('reason-content');
    
    if (titleElement) titleElement.textContent = 'Memuat Data...';
    if (contentElement) {
        contentElement.innerHTML = `
            <div class="flex items-center justify-center py-8">
                <svg class="animate-spin w-6 h-6 text-red-500 mr-2" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-gray-600">Memuat alasan pembatalan...</span>
            </div>
        `;
    }
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // ✅ PENTING: Pasang ulang event listener setiap kali modal dibuka
    attachActionButtonListeners();
    
    fetch(`/admin/bookings/${id}`)
        .then(r => {
            if (!r.ok) throw new Error(`HTTP ${r.status}: ${r.statusText}`);
            return r.json();
        })
        .then(data => {
            let reason = '';
            let title = 'Alasan Pembatalan';
            
            if (data.error) {
                title = '⚠️ Error Database';
                reason = data.message || 'Terjadi kesalahan sistem';
                if (contentElement) {
                    contentElement.className = 'bg-yellow-50 border-l-4 border-yellow-400 rounded-lg p-4 text-gray-800 whitespace-pre-line min-h-[120px] max-h-64 overflow-y-auto custom-scrollbar';
                }
                hideActionButtons();
            } else if (data.status === 'cancelled') {
                // Booking dibatalkan - tampilkan tombol
                if (data.cancellation_reason) {
                    reason = data.cancellation_reason;
                    if (data.cancelled_at) {
                        reason += `\n\n📅 Dibatalkan pada: ${data.cancelled_at}`;
                    }
                } else if (data.rejection_reason) {
                    title = 'Alasan Penolakan (Booking Dibatalkan)';
                    reason = `Booking ditolak: ${data.rejection_reason}`;
                    if (data.cancellation_reason) {
                        reason += `\n\nKemudian dibatalkan: ${data.cancellation_reason}`;
                    }
                }
                
                if (contentElement) {
                    contentElement.className = 'bg-red-50 border-l-4 border-red-400 rounded-lg p-4 text-gray-800 whitespace-pre-line min-h-[120px] max-h-64 overflow-y-auto custom-scrollbar';
                }
                showActionButtons();
            } else {
                title = 'ℹ️ Informasi';
                reason = 'Tidak ada alasan yang tercatat.';
                if (contentElement) {
                    contentElement.className = 'bg-blue-50 border-l-4 border-blue-400 rounded-lg p-4 text-gray-800 whitespace-pre-line min-h-[120px] max-h-64 overflow-y-auto custom-scrollbar';
                }
                hideActionButtons();
            }
            
            if (titleElement) titleElement.textContent = title;
            if (contentElement) contentElement.textContent = reason;
        })
        .catch(err => {
            console.error('❌ Error fetching reason:', err);
            if (titleElement) titleElement.textContent = '⚠️ Error Koneksi';
            if (contentElement) {
                contentElement.textContent = `❌ Gagal memuat data: ${err.message}`;
                contentElement.className = 'bg-red-50 border-l-4 border-red-500 rounded-lg p-4 text-gray-800 whitespace-pre-line min-h-[120px] max-h-64 overflow-y-auto custom-scrollbar';
            }
            hideActionButtons();
        });
}

function showActionButtons() {
    const restoreBtn = document.getElementById('restore-booking-btn');
    const deleteBtn = document.getElementById('permanent-delete-btn');
    
    if (restoreBtn) restoreBtn.style.display = 'flex';
    if (deleteBtn) deleteBtn.style.display = 'flex';
}

function hideActionButtons() {
    const restoreBtn = document.getElementById('restore-booking-btn');
    const deleteBtn = document.getElementById('permanent-delete-btn');
    
    if (restoreBtn) restoreBtn.style.display = 'none';
    if (deleteBtn) deleteBtn.style.display = 'none';
}

function closeReasonModal() {
    const modal = document.getElementById('reason-modal');
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
    currentReasonBookingId = null;
}

// ============================================
// UTILITY FUNCTIONS
// ============================================

function closeAllModals() {
    const modals = ['detail-modal', 'cancel-modal', 'reject-modal', 'reason-modal'];
    modals.forEach(modalId => {
        const modal = document.getElementById(modalId);
        if (modal && !modal.classList.contains('hidden')) {
            modal.classList.add('hidden');
        }
    });
}

// Make functions globally available
window.quickRestoreBooking = quickRestoreBooking;
window.openCancelModal = openCancelModal;
window.closeCancelModal = closeCancelModal;
window.submitCancel = submitCancel;
window.viewDetail = viewDetail;
window.closeDetailModal = closeDetailModal;
window.confirmBooking = confirmBooking;
window.showRejectModal = showRejectModal;
window.closeRejectModal = closeRejectModal;
window.submitReject = submitReject;
window.viewReason = viewReason;
window.closeReasonModal = closeReasonModal;
window.restoreBooking = restoreBooking;
window.permanentDeleteBooking = permanentDeleteBooking;
window.showAlert = showAlert;

console.log('✅ Admin Booking JS Loaded Successfully');