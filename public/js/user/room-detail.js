// Room Detail Page JavaScript

document.addEventListener('DOMContentLoaded', function() {
    console.log('Room detail JS loaded');

    // ===== IMAGE GALLERY =====
    
    // Change main image when thumbnail clicked
    window.changeMainImage = function(imageSrc, thumbnailElement) {
        const mainImage = document.getElementById('mainImage');
        if (mainImage) {
            console.log('Changing image to:', imageSrc);
            
            // Fade out
            mainImage.style.opacity = '0';
            
            // Update active thumbnail
            document.querySelectorAll('.thumbnail-wrapper').forEach(thumb => {
                thumb.classList.remove('active');
            });
            
            if (thumbnailElement) {
                thumbnailElement.classList.add('active');
            }
            
            // Change image and fade in
            setTimeout(() => {
                mainImage.src = imageSrc;
                mainImage.style.opacity = '1';
            }, 200);
        }
    };

    // ===== DATE VALIDATION =====
    
    const checkInInput = document.querySelector('input[name="check_in_date"]');
    const checkOutInput = document.querySelector('input[name="check_out_date"]');
    
    if (checkInInput && checkOutInput) {
        console.log('Date inputs found, adding validation');
        
        checkInInput.addEventListener('change', function() {
            const checkInDate = new Date(this.value);
            const minCheckOut = new Date(checkInDate);
            minCheckOut.setDate(minCheckOut.getDate() + 1);
            
            const minCheckOutStr = minCheckOut.toISOString().split('T')[0];
            checkOutInput.setAttribute('min', minCheckOutStr);
            
            // If check-out is before new min, update it
            if (checkOutInput.value && new Date(checkOutInput.value) <= checkInDate) {
                checkOutInput.value = minCheckOutStr;
            }
            
            console.log('Check-in changed to:', this.value);
            console.log('Check-out min set to:', minCheckOutStr);
        });
    }

    // ===== FORM SUBMISSION =====
    
    const bookingForm = document.querySelector('.booking-form');
    
    if (bookingForm) {
        console.log('Booking form found');
        
        bookingForm.addEventListener('submit', function(e) {
            console.log('Form submit triggered');
            
            // Validate dates
            const checkIn = new Date(checkInInput.value);
            const checkOut = new Date(checkOutInput.value);
            
            if (checkOut <= checkIn) {
                e.preventDefault();
                alert('Tanggal check-out harus setelah tanggal check-in');
                console.log('Form validation failed: invalid dates');
                return false;
            }
            
            console.log('Form validation passed, submitting...');
            // Form akan submit secara normal
        });
    }

    // ===== SUBMIT BUTTON DEBUG =====
    
    const submitButton = document.querySelector('.submit-button');
    
    if (submitButton) {
        console.log('Submit button found:', submitButton);
        console.log('Button type:', submitButton.tagName);
        console.log('Button disabled:', submitButton.disabled);
        
        // Check if it's a link or button
        if (submitButton.tagName === 'A') {
            console.log('Submit is a link, href:', submitButton.href);
        } else if (submitButton.tagName === 'BUTTON') {
            console.log('Submit is a button, type:', submitButton.type);
        }
    }

    // ===== SMOOTH SCROLL FOR BOOKING SECTION =====
    
    const bookingSection = document.querySelector('.booking-form-section');
    if (bookingSection && window.location.hash === '#book') {
        bookingSection.scrollIntoView({ behavior: 'smooth' });
    }
});