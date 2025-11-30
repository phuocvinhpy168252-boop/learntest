@extends('layouts.app')

@section('content')
<div class="auth-body">
    <div class="auth-container fade-in">
        <!-- Phần hình ảnh/giới thiệu -->
        <div class="auth-hero">
            <div class="hero-content">
                <div class="hero-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h1 class="hero-title">Chào Mừng Trở Lại</h1>
                <p class="hero-subtitle">
                    Đăng nhập để tiếp tục sử dụng hệ thống giáo dục của chúng tôi
                </p>
            </div>
        </div>

        <!-- Phần form đăng nhập -->
        <div class="auth-form-section">
            <div class="form-container slide-up">
                <div class="form-header">
                    <h2 class="form-title">Đăng Nhập</h2>
                    <p class="form-subtitle">Nhập thông tin đăng nhập của bạn</p>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                    </div>
                @endif

                <form class="auth-form" action="{{ route('login') }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <i class="fas fa-envelope form-icon"></i>
                        <input type="email" name="email" placeholder="Email" 
                               value="{{ old('email') }}" class="form-input" required>
                    </div>

                    <div class="form-group">
                        <i class="fas fa-lock form-icon"></i>
                        <input type="password" name="password" placeholder="Mật khẩu" 
                               class="form-input" required>
                        <button type="button" class="password-toggle" data-target="password">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>

                    <button type="submit" class="submit-btn">
                        Đăng Nhập
                    </button>

                    <div class="form-footer">
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="#" class="form-link">Quên mật khẩu?</a>
                            <p class="form-footer-text mb-0">
                                Chưa có tài khoản? 
                                <a href="{{ route('register.form') }}" class="form-link">Đăng ký ngay</a>
                            </p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection



@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility
    const toggleButtons = document.querySelectorAll('.password-toggle');
    
    toggleButtons.forEach(button => {
        button.addEventListener('click', function() {
            const target = this.getAttribute('data-target');
            const passwordInput = document.querySelector(`input[name="${target}"]`);
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });
});
</script>
@endpush