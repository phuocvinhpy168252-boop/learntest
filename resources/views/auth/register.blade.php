@extends('layouts.app')

@section('title', 'Đăng ký')

@section('styles')
@vite(['resources/css/auth.css'])
@endsection

@section('content')
<div class="auth-floating-elements">
    <div class="auth-floating-element"></div>
    <div class="auth-floating-element"></div>
    <div class="auth-floating-element"></div>
</div>

<div class="auth-container animate-fade-up">
    <div class="auth-card hover-lift">
        <div class="auth-header">
            <div class="auth-icon animate-pulse">
                <i class="fas fa-user-plus"></i>
            </div>
            <h1 class="auth-title">Đăng Ký Tài Khoản</h1>
            <p class="auth-subtitle">Tham gia EduLearn và bắt đầu hành trình học tập của bạn ngay hôm nay!</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger hover-lift">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <div>
                        <strong>Vui lòng kiểm tra lại thông tin:</strong>
                        <ul class="mb-0 mt-1 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Sửa action route thành route('auth.register') -->
        <form method="POST" action="{{ route('auth.register') }}" id="registerForm">
            @csrf
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <i class="fas fa-user form-icon"></i>
                        <input type="text" name="hoten" class="form-control hover-lift" 
                               placeholder="Họ và tên" value="{{ old('hoten') }}" required autofocus>
                        <small class="form-text text-muted mt-1">Nhập họ tên đầy đủ của bạn</small>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <i class="fas fa-phone form-icon"></i>
                        <input type="tel" name="sdt" class="form-control hover-lift" 
                               placeholder="Số điện thoại" value="{{ old('sdt') }}" required>
                        <small class="form-text text-muted mt-1">VD: 0987654321</small>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <i class="fas fa-envelope form-icon"></i>
                <input type="email" name="email" class="form-control hover-lift" 
                       placeholder="Địa chỉ email" value="{{ old('email') }}" required>
                <small class="form-text text-muted mt-1">Chúng tôi sẽ gửi xác nhận đến email này</small>
            </div>

            <div class="form-group">
                <i class="fas fa-map-marker-alt form-icon"></i>
                <input type="text" name="diachi" class="form-control hover-lift" 
                       placeholder="Địa chỉ" value="{{ old('diachi') }}">
                <small class="form-text text-muted mt-1">Không bắt buộc</small>
            </div>

            <div class="form-group">
                <i class="fas fa-user-tag form-icon"></i>
                <select name="loai_taikhoan" class="form-select hover-lift" required>
                    <option value="" disabled {{ old('loai_taikhoan') ? '' : 'selected' }}>Chọn loại tài khoản</option>
                    <option value="giangvien" {{ old('loai_taikhoan') == 'giangvien' ? 'selected' : '' }}>👨‍🏫 Giảng viên</option>
                    <option value="sinhvien" {{ old('loai_taikhoan') == 'sinhvien' ? 'selected' : '' }}>🎓 Sinh viên</option>
                </select>
                <small class="form-text text-muted mt-1">Chọn vai trò của bạn trên nền tảng</small>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <i class="fas fa-lock form-icon"></i>
                        <input type="password" name="password" id="password" class="form-control hover-lift" 
                               placeholder="Mật khẩu" required onkeyup="checkPasswordStrength(this.value)">
                        <button type="button" class="password-toggle hover-lift" onclick="togglePassword(this)">
                            <i class="fas fa-eye"></i>
                        </button>
                        <div class="password-strength mt-2">
                            <div class="strength-bar" id="strengthBar"></div>
                        </div>
                        <small class="form-text text-muted mt-1">Ít nhất 8 ký tự, có chữ hoa và số</small>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <i class="fas fa-lock form-icon"></i>
                        <input type="password" name="password_confirmation" class="form-control hover-lift" 
                               placeholder="Xác nhận mật khẩu" required>
                        <button type="button" class="password-toggle hover-lift" onclick="togglePassword(this)">
                            <i class="fas fa-eye"></i>
                        </button>
                        <small class="form-text text-muted mt-1">Nhập lại mật khẩu để xác nhận</small>
                    </div>
                </div>
            </div>

            <div class="form-check mb-4">
                <input class="form-check-input hover-lift" type="checkbox" name="terms" id="terms" required>
                <label class="form-check-label" for="terms">
                    Tôi đồng ý với <a href="#" class="auth-link hover-lift">Điều khoản dịch vụ</a> và <a href="#" class="auth-link hover-lift">Chính sách bảo mật</a> của EduLearn
                </label>
            </div>

            <button type="submit" class="btn-gradient btn-auth hover-lift" id="registerButton">
                <i class="fas fa-user-plus me-2"></i>Tạo Tài Khoản
            </button>

            <div class="text-center my-3">
                <span class="text-muted">hoặc đăng ký với</span>
            </div>

            <div class="row g-2 mb-4">
                <div class="col-6">
                    <a href="#" class="btn btn-outline-primary hover-lift w-100">
                        <i class="fab fa-google me-2"></i>Google
                    </a>
                </div>
                <div class="col-6">
                    <a href="#" class="btn btn-outline-primary hover-lift w-100">
                        <i class="fab fa-facebook me-2"></i>Facebook
                    </a>
                </div>
            </div>

            <div class="auth-footer">
                <p class="mb-2">Đã có tài khoản EduLearn?</p>
                <!-- Sử dụng route('login.form') từ web.php -->
                <a href="{{ route('login.form') }}" class="auth-link hover-lift">
                    <i class="fas fa-sign-in-alt me-1"></i>Đăng nhập ngay
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function togglePassword(button) {
        const passwordInput = button.parentElement.querySelector('input');
        const icon = button.querySelector('i');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
            button.setAttribute('aria-label', 'Ẩn mật khẩu');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
            button.setAttribute('aria-label', 'Hiện mật khẩu');
        }
    }
    
    function checkPasswordStrength(password) {
        const strengthBar = document.getElementById('strengthBar');
        let strength = 0;
        const feedback = document.getElementById('passwordFeedback') || createFeedbackElement();
        
        // Reset feedback
        feedback.innerHTML = '';
        
        if (password.length >= 8) strength += 20;
        else feedback.innerHTML += '<div class="text-danger"><i class="fas fa-times me-1"></i>Ít nhất 8 ký tự</div>';
        
        if (password.match(/[a-z]+/)) strength += 20;
        else feedback.innerHTML += '<div class="text-danger"><i class="fas fa-times me-1"></i>Có chữ thường</div>';
        
        if (password.match(/[A-Z]+/)) strength += 20;
        else feedback.innerHTML += '<div class="text-danger"><i class="fas fa-times me-1"></i>Có chữ HOA</div>';
        
        if (password.match(/[0-9]+/)) strength += 20;
        else feedback.innerHTML += '<div class="text-danger"><i class="fas fa-times me-1"></i>Có ít nhất 1 số</div>';
        
        if (password.match(/[^a-zA-Z0-9]+/)) strength += 20;
        else feedback.innerHTML += '<div class="text-danger"><i class="fas fa-times me-1"></i>Có ký tự đặc biệt (tùy chọn)</div>';
        
        strengthBar.style.width = strength + '%';
        strengthBar.style.transition = 'all 0.3s ease';
        
        if (strength < 40) {
            strengthBar.style.background = 'var(--danger-color)';
            strengthBar.setAttribute('aria-label', 'Mật khẩu yếu');
        } else if (strength < 70) {
            strengthBar.style.background = 'var(--warning-color)';
            strengthBar.setAttribute('aria-label', 'Mật khẩu trung bình');
        } else {
            strengthBar.style.background = 'var(--secondary-color)';
            strengthBar.setAttribute('aria-label', 'Mật khẩu mạnh');
            feedback.innerHTML = '<div class="text-success"><i class="fas fa-check me-1"></i>Mật khẩu mạnh!</div>';
        }
    }
    
    function createFeedbackElement() {
        const passwordGroup = document.querySelector('#password').closest('.form-group');
        const feedback = document.createElement('div');
        feedback.id = 'passwordFeedback';
        feedback.className = 'mt-2 small';
        passwordGroup.appendChild(feedback);
        return feedback;
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('registerForm');
        const submitBtn = document.getElementById('registerButton');
        const termsCheckbox = document.getElementById('terms');
        
        // Auto-check password strength on page load if there's a value
        const passwordInput = document.getElementById('password');
        if (passwordInput && passwordInput.value) {
            checkPasswordStrength(passwordInput.value);
        }
        
        // Form submission loading state
        if (form && submitBtn) {
            form.addEventListener('submit', function(e) {
                if (!termsCheckbox.checked) {
                    e.preventDefault();
                    alert('Vui lòng đồng ý với điều khoản dịch vụ!');
                    termsCheckbox.focus();
                    return;
                }
                
                submitBtn.classList.add('loading');
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang tạo tài khoản...';
            });
        }
        
        // Real-time validation
        const inputs = form.querySelectorAll('input[required], select[required]');
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                validateField(this);
            });
            
            input.addEventListener('input', function() {
                if (this.type === 'password' && this.id === 'password') {
                    checkPasswordStrength(this.value);
                }
            });
        });
        
        function validateField(field) {
            const value = field.value.trim();
            const errorElement = field.nextElementSibling?.classList?.contains('text-danger') 
                ? field.nextElementSibling 
                : null;
            
            if (!value && field.required) {
                showError(field, 'Trường này là bắt buộc');
            } else {
                clearError(field);
                
                // Specific validations
                if (field.type === 'email' && value) {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(value)) {
                        showError(field, 'Email không hợp lệ');
                    }
                }
                
                if (field.name === 'sdt' && value) {
                    const phoneRegex = /^(0[3|5|7|8|9])+([0-9]{8})$/;
                    if (!phoneRegex.test(value)) {
                        showError(field, 'Số điện thoại không hợp lệ');
                    }
                }
            }
        }
        
        function showError(field, message) {
            clearError(field);
            field.classList.add('is-invalid');
            const error = document.createElement('div');
            error.className = 'invalid-feedback d-block mt-1';
            error.textContent = message;
            field.parentNode.appendChild(error);
        }
        
        function clearError(field) {
            field.classList.remove('is-invalid');
            const existingError = field.parentNode.querySelector('.invalid-feedback');
            if (existingError) {
                existingError.remove();
            }
        }
        
        // Password confirmation validation
        const passwordConfirm = document.querySelector('input[name="password_confirmation"]');
        if (passwordConfirm) {
            passwordConfirm.addEventListener('input', function() {
                const password = document.getElementById('password').value;
                if (this.value && password !== this.value) {
                    showError(this, 'Mật khẩu xác nhận không khớp');
                } else {
                    clearError(this);
                }
            });
        }
        
        // Add animation to select options
        const select = document.querySelector('select[name="loai_taikhoan"]');
        if (select) {
            select.addEventListener('change', function() {
                this.classList.add('selected');
                setTimeout(() => this.classList.remove('selected'), 300);
            });
        }
    });
</script>
@endsection