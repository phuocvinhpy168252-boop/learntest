// Auth form functionality
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('.auth-form');
    const passwordToggles = document.querySelectorAll('.password-toggle');
    const passwordInputs = document.querySelectorAll('input[type="password"]');
    const passwordStrengthInputs = document.querySelectorAll('input[name="password"]');

    // Password toggle functionality
    passwordToggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
            const target = this.getAttribute('data-target');
            const passwordInput = document.querySelector(`input[name="${target}"]`);
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                this.innerHTML = '<i class="fas fa-eye-slash"></i>';
            } else {
                passwordInput.type = 'password';
                this.innerHTML = '<i class="fas fa-eye"></i>';
            }
        });
    });

    // Password strength indicator
    passwordStrengthInputs.forEach(input => {
        input.addEventListener('input', function() {
            const strengthBar = this.parentElement.querySelector('.strength-bar');
            if (!strengthBar) return;

            const password = this.value;
            let strength = 0;

            // Check password strength
            if (password.length >= 8) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;

            // Update strength bar
            strengthBar.className = 'strength-bar';
            if (password.length === 0) {
                strengthBar.style.width = '0%';
            } else if (strength <= 1) {
                strengthBar.className += ' strength-weak';
            } else if (strength <= 2) {
                strengthBar.className += ' strength-medium';
            } else {
                strengthBar.className += ' strength-strong';
            }
        });
    });

    // Form validation
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            let isValid = true;
            const inputs = this.querySelectorAll('input[required], select[required]');

            inputs.forEach(input => {
                if (!input.value.trim()) {
                    showError(input, 'Trường này là bắt buộc');
                    isValid = false;
                } else {
                    clearError(input);
                }

                // Email validation
                if (input.type === 'email' && input.value) {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(input.value)) {
                        showError(input, 'Email không hợp lệ');
                        isValid = false;
                    }
                }

                // Phone validation
                if (input.name === 'sdt' && input.value) {
                    const phoneRegex = /^(0|\+84)[3|5|7|8|9][0-9]{8}$/;
                    if (!phoneRegex.test(input.value)) {
                        showError(input, 'Số điện thoại không hợp lệ');
                        isValid = false;
                    }
                }

                // Password confirmation
                if (input.name === 'password_confirmation' && input.value) {
                    const password = this.querySelector('input[name="password"]').value;
                    if (password !== input.value) {
                        showError(input, 'Mật khẩu xác nhận không khớp');
                        isValid = false;
                    }
                }
            });

            if (!isValid) {
                e.preventDefault();
                // Add shake animation to invalid fields
                const errorInputs = this.querySelectorAll('.error-input');
                errorInputs.forEach(input => {
                    input.classList.add('shake');
                    setTimeout(() => input.classList.remove('shake'), 500);
                });
            } else {
                // Show loading state
                const submitBtn = this.querySelector('.submit-btn');
                submitBtn.classList.add('loading');
            }
        });
    });

    // Real-time validation
    const realTimeInputs = document.querySelectorAll('input, select');
    realTimeInputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value.trim()) {
                this.classList.add('success-input');
                
                // Specific validations
                if (this.type === 'email' && this.value) {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(this.value)) {
                        showError(this, 'Email không hợp lệ');
                    } else {
                        clearError(this);
                    }
                }
            } else {
                this.classList.remove('success-input');
            }
        });

        input.addEventListener('input', function() {
            if (this.classList.contains('error-input')) {
                clearError(this);
            }
        });
    });

    function showError(input, message) {
        clearError(input);
        input.classList.add('error-input');
        
        let errorElement = document.createElement('div');
        errorElement.className = 'error-message';
        errorElement.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
        
        input.parentElement.appendChild(errorElement);
    }

    function clearError(input) {
        input.classList.remove('error-input');
        const errorElement = input.parentElement.querySelector('.error-message');
        if (errorElement) {
            errorElement.remove();
        }
    }
});