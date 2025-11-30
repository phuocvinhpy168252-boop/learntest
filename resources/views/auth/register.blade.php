@extends('layouts.app')

@section('content')
<div class="auth-body">
    <div class="auth-container fade-in">
        <!-- Phần hình ảnh/giới thiệu -->
        <div class="auth-hero">
            <div class="hero-content">
                <div class="hero-icon">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <h1 class="hero-title">Chào Mừng Đến Với Hệ Thống</h1>
                <p class="hero-subtitle">
                    Tham gia cộng đồng học tập và giảng dạy của chúng tôi. 
                    Tạo tài khoản để truy cập vào tất cả các tính năng.
                </p>
            </div>
        </div>

        <!-- Phần form đăng ký -->
        <div class="auth-form-section">
            <div class="form-container slide-up">
                <div class="form-header">
                    <h2 class="form-title">Đăng Ký Tài Khoản</h2>
                    <p class="form-subtitle">Điền thông tin để tạo tài khoản mới</p>
                </div>

                <form class="auth-form" action="{{ route('register') }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <i class="fas fa-user form-icon"></i>
                        <input type="text" name="hoten" placeholder="Họ và tên" value="{{ old('hoten') }}" 
                               class="form-input" required>
                    </div>

                    <div class="form-group">
                        <i class="fas fa-phone form-icon"></i>
                        <input type="text" name="sdt" placeholder="Số điện thoại" value="{{ old('sdt') }}" 
                               class="form-input" required>
                    </div>

                    <div class="form-group">
                        <i class="fas fa-envelope form-icon"></i>
                        <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" 
                               class="form-input" required>
                    </div>

                    <div class="form-group">
                        <i class="fas fa-map-marker-alt form-icon"></i>
                        <input type="text" name="diachi" placeholder="Địa chỉ" value="{{ old('diachi') }}" 
                               class="form-input">
                    </div>

                    <div class="form-group">
                        <i class="fas fa-user-tag form-icon"></i>
                        <div class="select-wrapper">
                            <select name="loai_taikhoan" class="form-input form-select" required>
                                <option value="">Chọn loại tài khoản</option>
                                <option value="giangvien" {{ old('loai_taikhoan') == 'giangvien' ? 'selected' : '' }}>Giảng viên</option>
                                <option value="sinhvien" {{ old('loai_taikhoan') == 'sinhvien' ? 'selected' : '' }}>Sinh viên</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <i class="fas fa-lock form-icon"></i>
                        <input type="password" name="password" placeholder="Mật khẩu" 
                               class="form-input" required>
                        <button type="button" class="password-toggle" data-target="password">
                            <i class="fas fa-eye"></i>
                        </button>
                        <div class="password-strength">
                            <div class="strength-bar"></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <i class="fas fa-lock form-icon"></i>
                        <input type="password" name="password_confirmation" placeholder="Xác nhận mật khẩu" 
                               class="form-input" required>
                        <button type="button" class="password-toggle" data-target="password_confirmation">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>

                    <button type="submit" class="submit-btn">
                        Đăng Ký
                    </button>

                    <div class="form-footer">
                        <p class="form-footer-text">
                            Đã có tài khoản? 
                            <a href="{{ route('login') }}" class="form-link">Đăng nhập ngay</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
@vite(['resources/css/auth/auth.css'])
@endpush

@push('scripts')
<!-- Include Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

@vite(['resources/js/auth/auth.js'])
@endpush