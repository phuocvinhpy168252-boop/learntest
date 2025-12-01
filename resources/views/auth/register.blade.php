@extends('layouts.app')

@section('title', 'Đăng ký')

@section('styles')
<style>
    .auth-container {
        max-width: 500px;
        margin: 3rem auto;
    }
    
    .auth-card {
        background: white;
        border-radius: 20px;
        padding: 2.5rem;
        box-shadow: 0 20px 60px rgba(0,0,0,0.1);
        border: 1px solid rgba(0,0,0,0.05);
    }
    
    .auth-header {
        text-align: center;
        margin-bottom: 2rem;
    }
    
    .auth-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        color: white;
        font-size: 2rem;
    }
    
    .auth-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 0.5rem;
    }
    
    .auth-subtitle {
        color: var(--secondary);
        margin-bottom: 0;
    }
    
    .form-group {
        position: relative;
        margin-bottom: 1.5rem;
    }
    
    .form-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--primary);
        font-size: 1.1rem;
    }
    
    .form-control,
    .form-select {
        padding-left: 3rem;
        padding-right: 3rem;
        height: 52px;
        border: 2px solid #eef2f7;
        border-radius: 12px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }
    
    .form-control:focus,
    .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(74, 108, 247, 0.1);
    }
    
    .form-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 16px 12px;
    }
    
    .password-toggle {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: var(--secondary);
        cursor: pointer;
        padding: 0;
    }
    
    .password-strength {
        height: 4px;
        background: #eef2f7;
        border-radius: 2px;
        margin-top: 0.5rem;
        overflow: hidden;
    }
    
    .strength-bar {
        height: 100%;
        width: 0%;
        background: #dc3545;
        border-radius: 2px;
        transition: width 0.3s ease;
    }
    
    .btn-auth {
        width: 100%;
        height: 52px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 12px;
        color: white;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s ease;
    }
    
    .btn-auth:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
    }
    
    .auth-footer {
        text-align: center;
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid #eef2f7;
    }
    
    .auth-link {
        color: var(--primary);
        text-decoration: none;
        font-weight: 500;
        transition: color 0.3s ease;
    }
    
    .auth-link:hover {
        color: var(--primary-dark);
    }
    
    .alert {
        border-radius: 12px;
        border: none;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
    }
    
    @media (max-width: 768px) {
        .auth-container {
            padding: 0 1rem;
        }
        
        .auth-card {
            padding: 2rem 1.5rem;
        }
    }
</style>
@endsection

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-icon">
                <i class="fas fa-user-plus"></i>
            </div>
            <h1 class="auth-title">Đăng Ký Tài Khoản</h1>
            <p class="auth-subtitle">Tạo tài khoản mới để bắt đầu học tập</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <div>
                        @foreach($errors->all() as $error)
                            <div class="mb-1">{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('auth.register') }}">
            @csrf
            
            <div class="form-group">
                <i class="fas fa-user form-icon"></i>
                <input type="text" name="hoten" class="form-control" 
                       placeholder="Họ và tên" value="{{ old('hoten') }}" required>
            </div>

            <div class="form-group">
                <i class="fas fa-phone form-icon"></i>
                <input type="text" name="sdt" class="form-control" 
                       placeholder="Số điện thoại" value="{{ old('sdt') }}" required>
            </div>

            <div class="form-group">
                <i class="fas fa-envelope form-icon"></i>
                <input type="email" name="email" class="form-control" 
                       placeholder="Email" value="{{ old('email') }}" required>
            </div>

            <div class="form-group">
                <i class="fas fa-map-marker-alt form-icon"></i>
                <input type="text" name="diachi" class="form-control" 
                       placeholder="Địa chỉ" value="{{ old('diachi') }}">
            </div>

            <div class="form-group">
                <i class="fas fa-user-tag form-icon"></i>
                <select name="loai_taikhoan" class="form-select" required>
                    <option value="">Chọn loại tài khoản</option>
                    <option value="giangvien" {{ old('loai_taikhoan') == 'giangvien' ? 'selected' : '' }}>Giảng viên</option>
                    <option value="sinhvien" {{ old('loai_taikhoan') == 'sinhvien' ? 'selected' : '' }}>Sinh viên</option>
                </select>
            </div>

            <div class="form-group">
                <i class="fas fa-lock form-icon"></i>
                <input type="password" name="password" class="form-control" 
                       placeholder="Mật khẩu" required onkeyup="checkPasswordStrength(this.value)">
                <button type="button" class="password-toggle" onclick="togglePassword(this)">
                    <i class="fas fa-eye"></i>
                </button>
                <div class="password-strength">
                    <div class="strength-bar" id="strengthBar"></div>
                </div>
            </div>

            <div class="form-group">
                <i class="fas fa-lock form-icon"></i>
                <input type="password" name="password_confirmation" class="form-control" 
                       placeholder="Xác nhận mật khẩu" required>
                <button type="button" class="password-toggle" onclick="togglePassword(this)">
                    <i class="fas fa-eye"></i>
                </button>
            </div>

            <button type="submit" class="btn btn-auth mb-3">
                Đăng Ký
            </button>

            <div class="auth-footer">
                <p class="mb-0">
                    Đã có tài khoản? 
                    <a href="{{ route('login.form') }}" class="auth-link">Đăng nhập ngay</a>
                </p>
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
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
    
    function checkPasswordStrength(password) {
        const strengthBar = document.getElementById('strengthBar');
        let strength = 0;
        
        if (password.length >= 8) strength += 25;
        if (password.match(/[a-z]+/)) strength += 25;
        if (password.match(/[A-Z]+/)) strength += 25;
        if (password.match(/[0-9]+/)) strength += 25;
        
        strengthBar.style.width = strength + '%';
        
        if (strength < 50) {
            strengthBar.style.background = '#dc3545';
        } else if (strength < 75) {
            strengthBar.style.background = '#ffc107';
        } else {
            strengthBar.style.background = '#28a745';
        }
    }
    
    // Auto-focus first input
    document.addEventListener('DOMContentLoaded', function() {
        const firstInput = document.querySelector('input[name="hoten"]');
        if (firstInput) {
            firstInput.focus();
        }
    });
</script>
@endsection