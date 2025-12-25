// ========== AUTO HIDE ALERT ==========
document.addEventListener("DOMContentLoaded", function () {
    const alerts = document.querySelectorAll(".alert");

    alerts.forEach((alert) => {
        setTimeout(() => {
            alert.style.transition = "opacity 0.5s";
            alert.style.opacity = "0";
            setTimeout(() => {
                alert.remove();
            }, 500);
        }, 5000);
    });
});

// ========== DATE PICKER (Simple) ==========
// Untuk input check_in di halaman booking
const checkInInput = document.getElementById("check_in");

if (checkInInput) {
    // Set minimum date to today
    const today = new Date().toISOString().split("T")[0];
    checkInInput.setAttribute("min", today);

    // Bisa pakai library seperti flatpickr untuk date picker yang lebih bagus
    // Untuk sekarang, kita pakai HTML5 date input
    checkInInput.addEventListener("click", function () {
        // Split untuk 2 tanggal (check in - check out)
        // Ini contoh sederhana, bisa ditingkatkan dengan library
    });
}

// ========== SMOOTH SCROLL ==========
document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute("href"));
        if (target) {
            target.scrollIntoView({
                behavior: "smooth",
                block: "start",
            });
        }
    });
});

// ========== FORM VALIDATION ==========
const bookingForms = document.querySelectorAll(".booking-form");

bookingForms.forEach((form) => {
    form.addEventListener("submit", function (e) {
        const guestName = form.querySelector('input[name="guest_name"]');
        const phone = form.querySelector('input[name="phone"]');

        if (guestName && guestName.value.trim() === "") {
            e.preventDefault();
            alert("Please enter your name");
            guestName.focus();
            return false;
        }

        if (phone && phone.value.trim() === "") {
            e.preventDefault();
            alert("Please enter your phone number");
            phone.focus();
            return false;
        }
    });
});

// ========== MOBILE MENU TOGGLE (Bonus) ==========
const createMobileMenu = () => {
    const navbar = document.querySelector(".navbar .container");
    const navMenu = document.querySelector(".nav-menu");

    // Create hamburger button
    const hamburger = document.createElement("button");
    hamburger.className = "hamburger";
    hamburger.innerHTML = "☰";
    hamburger.style.cssText = `
        display: none;
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
        color: #ff7b00;
    `;

    // Insert before nav-actions
    const navActions = document.querySelector(".nav-actions");
    navbar.insertBefore(hamburger, navActions);

    // Toggle menu on click
    hamburger.addEventListener("click", () => {
        navMenu.classList.toggle("active");
    });

    // Show hamburger on mobile
    window.addEventListener("resize", () => {
        if (window.innerWidth <= 768) {
            hamburger.style.display = "block";
        } else {
            hamburger.style.display = "none";
            navMenu.classList.remove("active");
        }
    });

    // Initial check
    if (window.innerWidth <= 768) {
        hamburger.style.display = "block";
    }
};

// Initialize mobile menu
createMobileMenu();

// ========== BOOKING PRICE CALCULATOR ==========
// Untuk menghitung total harga berdasarkan tanggal
const calculateBookingPrice = () => {
    const checkInInput = document.querySelector('input[name="check_in"]');
    const checkOutInput = document.querySelector('input[name="check_out"]');
    const priceElement = document.querySelector(".price .amount");

    if (checkInInput && checkOutInput && priceElement) {
        const updatePrice = () => {
            const checkIn = new Date(checkInInput.value);
            const checkOut = new Date(checkOutInput.value);

            if (checkIn && checkOut && checkOut > checkIn) {
                const days = Math.ceil(
                    (checkOut - checkIn) / (1000 * 60 * 60 * 24)
                );
                const pricePerNight = parseInt(
                    priceElement.textContent.replace(/[^0-9]/g, "")
                );
                const totalPrice = days * pricePerNight;

                // Update display (optional)
                console.log(
                    `Total: ${days} nights = Rp ${totalPrice.toLocaleString()}`
                );
            }
        };

        checkInInput.addEventListener("change", updatePrice);
        checkOutInput.addEventListener("change", updatePrice);
    }
};

calculateBookingPrice();

console.log("Hotel Booking System loaded successfully! 🏨");