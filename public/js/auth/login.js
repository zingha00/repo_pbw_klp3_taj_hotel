// Login Page JavaScript

document.addEventListener("DOMContentLoaded", function () {
    // Elements
    const form = document.getElementById("login-form");
    const submitBtn = document.getElementById("submit-btn");
    const passwordInput = document.getElementById("password");
    const togglePassword = document.getElementById("toggle-password");
    const emailInput = document.getElementById("email");

    // Password Toggle Functionality
    if (togglePassword && passwordInput) {
        togglePassword.addEventListener("click", function (e) {
            e.preventDefault();
            e.stopPropagation();

            const currentType = passwordInput.getAttribute("type");
            const newType = currentType === "password" ? "text" : "password";
            passwordInput.setAttribute("type", newType);

            // Update icon SVG
            if (newType === "text") {
                // Eye slash icon (password visible)
                this.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                `;
                this.style.color = "#d4a574";
            } else {
                // Eye icon (password hidden)
                this.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                `;
                this.style.color = "";
            }
        });

        // Add cursor pointer style
        togglePassword.style.cursor = "pointer";
    }

    // Email Validation
    if (emailInput) {
        emailInput.addEventListener("blur", function () {
            const email = this.value;
            if (email && !isValidEmail(email)) {
                this.classList.add("error");
                showFieldError(this, "Format email tidak valid");
            } else {
                this.classList.remove("error");
                removeFieldError(this);
            }
        });

        // Clear error on input
        emailInput.addEventListener("input", function () {
            if (this.classList.contains("error")) {
                this.classList.remove("error");
                removeFieldError(this);
            }
        });
    }

    function isValidEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    // Helper Functions
    function showFieldError(field, message) {
        // Don't show duplicate errors
        const existingError = field.parentElement.querySelector(
            ".error-message:not([data-server-error])"
        );
        if (existingError) return;

        const errorDiv = document.createElement("div");
        errorDiv.className = "error-message";
        errorDiv.style.cssText =
            "color: #dc2626; font-size: 0.8rem; margin-top: 0.375rem; display: flex; align-items: center; gap: 0.25rem;";
        errorDiv.innerHTML = `
            <svg fill="currentColor" viewBox="0 0 20 20" style="width: 14px; height: 14px; flex-shrink: 0;">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            ${message}
        `;

        field.parentElement.appendChild(errorDiv);
    }

    function removeFieldError(field) {
        const errorMsg = field.parentElement.querySelector(
            ".error-message:not([data-server-error])"
        );
        if (errorMsg) {
            errorMsg.remove();
        }
    }

    // Form Submission
    if (form) {
        form.addEventListener("submit", function (e) {
            // Basic validation
            if (emailInput && !emailInput.value) {
                e.preventDefault();
                emailInput.focus();
                emailInput.classList.add("error");
                showFieldError(emailInput, "Email harus diisi");
                return false;
            }

            if (passwordInput && !passwordInput.value) {
                e.preventDefault();
                passwordInput.focus();
                passwordInput.classList.add("error");
                showFieldError(passwordInput, "Password harus diisi");
                return false;
            }

            // Validate email format
            if (
                emailInput &&
                emailInput.value &&
                !isValidEmail(emailInput.value)
            ) {
                e.preventDefault();
                emailInput.focus();
                emailInput.classList.add("error");
                showFieldError(emailInput, "Format email tidak valid");
                return false;
            }

            // Disable button and show loading
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = "Masuk...";
                submitBtn.style.opacity = "0.7";
            }
        });
    }

    // Auto-dismiss alerts
    const alerts = document.querySelectorAll(".login-alert");
    alerts.forEach((alert) => {
        setTimeout(() => {
            alert.style.transition = "opacity 0.3s ease, transform 0.3s ease";
            alert.style.opacity = "0";
            alert.style.transform = "translateY(-10px)";
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });

    // Prevent multiple form submissions
    let isSubmitting = false;
    if (form) {
        form.addEventListener("submit", function (e) {
            if (isSubmitting) {
                e.preventDefault();
                return false;
            }
            isSubmitting = true;
        });
    }

    // Add smooth focus effects
    const inputs = document.querySelectorAll(".form-input");
    inputs.forEach((input) => {
        input.addEventListener("focus", function () {
            this.style.transition = "all 0.2s ease";
        });
    });

    // Mark server errors
    const serverErrors = document.querySelectorAll(".error-message");
    serverErrors.forEach((error) => {
        error.setAttribute("data-server-error", "true");
    });

    // Enter key support
    if (emailInput) {
        emailInput.addEventListener("keypress", function (e) {
            if (e.key === "Enter" && passwordInput) {
                e.preventDefault();
                passwordInput.focus();
            }
        });
    }

    if (passwordInput) {
        passwordInput.addEventListener("keypress", function (e) {
            if (e.key === "Enter" && form) {
                e.preventDefault();
                form.submit();
            }
        });
    }
});
