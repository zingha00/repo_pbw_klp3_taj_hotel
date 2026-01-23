// Register Page JavaScript
document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("register-form");
    const submitBtn = document.getElementById("submit-btn");

    const passwordInput = document.getElementById("password");
    const passwordConfirmInput = document.getElementById(
        "password_confirmation"
    );

    const togglePassword = document.getElementById("toggle-password");
    const togglePasswordConfirm = document.getElementById(
        "toggle-password-confirmation"
    );

    const strengthIndicator = document.getElementById("password-strength");
    const strengthFill = document.getElementById("strength-fill");
    const strengthText = document.getElementById("strength-text");

    const nameInput = document.getElementById("name");
    const emailInput = document.getElementById("email");

    /* ==============================
       PASSWORD TOGGLE (FIXED)
    ============================== */
    function initPasswordToggle(input, toggle) {
        if (!input || !toggle) return;

        toggle.setAttribute("data-visible", "false");

        toggle.addEventListener("click", function (e) {
            e.preventDefault();

            const isVisible = toggle.getAttribute("data-visible") === "true";

            input.type = isVisible ? "password" : "text";
            toggle.setAttribute("data-visible", String(!isVisible));

            toggle.innerHTML = isVisible ? getEyeIcon() : getEyeOffIcon();
            toggle.style.color = !isVisible ? "#d4a574" : "";
        });
    }

    function getEyeIcon() {
        return `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M2.458 12C3.732 7.943 7.523 5 12 5
                     c4.478 0 8.268 2.943 9.542 7
                     -1.274 4.057-5.064 7-9.542 7
                     -4.477 0-8.268-2.943-9.542-7z" />
        `;
    }

    function getEyeOffIcon() {
        return `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M13.875 18.825A10.05 10.05 0 0112 19
                     c-4.478 0-8.268-2.943-9.543-7
                     a9.97 9.97 0 011.563-3.029
                     m5.858.908a3 3 0 114.243 4.243
                     M9.878 9.878l4.242 4.242
                     M9.88 9.88l-3.29-3.29
                     m7.532 7.532l3.29 3.29
                     M3 3l18 18" />
        `;
    }

    initPasswordToggle(passwordInput, togglePassword);
    initPasswordToggle(passwordConfirmInput, togglePasswordConfirm);

    /* ==============================
       PASSWORD STRENGTH
    ============================== */
    if (passwordInput && strengthIndicator) {
        passwordInput.addEventListener("input", function () {
            const password = this.value;

            if (!password) {
                strengthIndicator.style.display = "none";
                return;
            }

            strengthIndicator.style.display = "block";
            const strength = checkPasswordStrength(password);

            strengthFill.className = "strength-fill " + strength.level;
            strengthText.className = "strength-text " + strength.level;
            strengthText.textContent = strength.text;
        });
    }

    function checkPasswordStrength(password) {
        let score = 0;
        if (password.length >= 8) score++;
        if (password.length >= 12) score++;
        if (/[a-z]/.test(password)) score++;
        if (/[A-Z]/.test(password)) score++;
        if (/[0-9]/.test(password)) score++;
        if (/[^a-zA-Z0-9]/.test(password)) score++;

        if (score <= 2) return { level: "weak", text: "Password lemah" };
        if (score <= 4) return { level: "medium", text: "Password sedang" };
        return { level: "strong", text: "Password kuat" };
    }

    /* ==============================
       PASSWORD MATCH
    ============================== */
    function validatePasswordMatch() {
        if (!passwordConfirmInput.value) return;

        if (passwordInput.value !== passwordConfirmInput.value) {
            passwordConfirmInput.classList.add("error");
        } else {
            passwordConfirmInput.classList.remove("error");
        }
    }

    passwordConfirmInput?.addEventListener("input", validatePasswordMatch);
    passwordInput?.addEventListener("input", validatePasswordMatch);

    /* ==============================
       EMAIL VALIDATION
    ============================== */
    emailInput?.addEventListener("blur", function () {
        const valid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.value);
        this.classList.toggle("error", !valid && this.value !== "");
    });

    /* ==============================
       NAME FILTER
    ============================== */
    nameInput?.addEventListener("input", function () {
        this.value = this.value.replace(/[^a-zA-Z\s]/g, "");
    });

    /* ==============================
       FORM SUBMIT
    ============================== */
    let isSubmitting = false;
    form?.addEventListener("submit", function (e) {
        if (isSubmitting) {
            e.preventDefault();
            return;
        }
        isSubmitting = true;

        submitBtn.disabled = true;
        submitBtn.textContent = "Mendaftarkan...";
        submitBtn.style.opacity = "0.7";
    });
});
