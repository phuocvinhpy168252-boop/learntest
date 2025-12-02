@extends('layouts.app')

@section('title', 'Đăng nhập')

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
                <i class="fas fa-sign-in-alt"></i>
            </div>
            <h1 class="auth-title">Đăng Nhập</h1>
            <p class="auth-subtitle">Chào mừng trở lại EduLearn! Hãy tiếp tục hành trình học tập của bạn.</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger hover-lift">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <div>
                        <strong>Có lỗi xảy ra:</strong>
                        <ul class="mb-0 mt-1 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success hover-lift">
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle me-2"></i>
                    <div>{{ session('success') }}</div>
                </div>
            </div>
        @endif

        @if(session('status'))
            <div class="alert alert-info hover-lift">
                <div class="d-flex align-items-center">
                    <i class="fas fa-info-circle me-2"></i>
                    <div>{{ session('status') }}</div>
                </div>
            </div>
        @endif

        <!-- Sửa action route thành route('login') -->
        <form method="POST" action="{{ route('login') }}" id="loginForm">
            @csrf
            
            <div class="form-group">
                <i class="fas fa-envelope form-icon"></i>
                <input type="email" name="email" class="form-control hover-lift" 
                       placeholder="Email của bạn" value="{{ old('email') }}" required autofocus>
                <small class="form-text text-muted mt-1">Nhập email bạn đã đăng ký</small>
            </div>

            <div class="form-group">
                <i class="fas fa-lock form-icon"></i>
                <input type="password" name="password" class="form-control hover-lift" 
                       placeholder="Mật khẩu" required>
                <button type="button" class="password-toggle hover-lift" onclick="togglePassword(this)">
                    <i class="fas fa-eye"></i>
                </button>
                <small class="form-text text-muted mt-1">Nhập mật khẩu của bạn</small>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input class="form-check-input hover-lift" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember">Ghi nhớ đăng nhập</label>
                </div>
                <!-- Có thể thêm route forgot password sau -->
                <a href="#" class="auth-link hover-lift">
                    <i class="fas fa-key me-1"></i>Quên mật khẩu?
                </a>
            </div>

            <button type="submit" class="btn-gradient btn-auth hover-lift" id="loginButton">
                <i class="fas fa-sign-in-alt me-2"></i>Đăng Nhập
            </button>

            <div class="text-center my-3">
                <span class="text-muted">hoặc đăng nhập với</span>
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
                <p class="mb-2">Chưa có tài khoản EduLearn?</p>
                <!-- Sử dụng route('auth.register.form') từ web.php -->
                <a href="{{ route('auth.register.form') }}" class="auth-link hover-lift">
                    <i class="fas fa-user-plus me-1"></i>Tạo tài khoản mới
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
    
    document.addEventListener('DOMContentLoaded', function() {
        const emailInput = document.querySelector('input[name="email"]');
        const form = document.getElementById('loginForm');
        const submitBtn = document.getElementById('loginButton');
        
        // Auto-focus email input
        if (emailInput) {
            emailInput.focus();
        }
        
        // Form submission loading state
        if (form && submitBtn) {
            form.addEventListener('submit', function() {
                submitBtn.classList.add('loading');
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang đăng nhập...';
            });
        }
        
        // Add input focus effects
        const inputs = document.querySelectorAll('.form-control');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('focused');
            });
            
            input.addEventListener('blur', function() {
                if (!this.value) {
                    this.parentElement.classList.remove('focused');
                }
            });
            
            // Check if input has value on page load
            if (input.value) {
                input.parentElement.classList.add('focused');
            }
        });
        
        // Add keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.key === 'Enter' && form) {
                form.submit();
            }
        });
    });
</script>
@endsection