    @extends('layouts.admin')

@push('styles')
@endpush

@section('title', 'Thêm Sinh viên - Hệ thống Giáo dục')
@section('page-title', 'Thêm Sinh viên')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="admin-card">
            <div class="card-header">
                <h3 class="card-title">Thông tin sinh viên mới</h3>
                <a href="{{ route('admin.sinhvien.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại
                </a>
            </div>
            <form action="{{ route('admin.sinhvien.storeSV') }}" method="POST" id="addStudentForm">
                @csrf
                
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Vui lòng kiểm tra lại thông tin đã nhập
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="row g-3">
                    <!-- Mã sinh viên (tự động) -->
                    <div class="col-md-6">
                        <label class="form-label">Mã sinh viên</label>
                        <input type="text" class="form-control bg-light" value="{{ $nextMaSV ?? 'SV001' }}" readonly>
                        <small class="text-muted">Mã sinh viên được tạo tự động</small>
                    </div>

                    <!-- Họ tên -->
                    <div class="col-md-6">
                        <label class="form-label">Họ tên <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('ten') is-invalid @enderror" 
                               name="ten" value="{{ old('ten') }}" required>
                        @error('ten')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="col-md-6">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Số điện thoại -->
                    <div class="col-md-6">
                        <label class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('so_dien_thoai') is-invalid @enderror" 
                               name="so_dien_thoai" value="{{ old('so_dien_thoai') }}" required>
                        @error('so_dien_thoai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Mật khẩu -->
                    <div class="col-md-6">
                        <label class="form-label">Mật khẩu <span class="text-danger">*</span></label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Xác nhận mật khẩu -->
                    <div class="col-md-6">
                        <label class="form-label">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="password_confirmation" required>
                    </div>

                    <!-- Ngày sinh -->
                    <div class="col-md-6">
                        <label class="form-label">Ngày sinh</label>
                        <input type="date" class="form-control @error('ngay_sinh') is-invalid @enderror" 
                               name="ngay_sinh" value="{{ old('ngay_sinh') }}">
                        @error('ngay_sinh')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Giới tính -->
                    <div class="col-md-6">
                        <label class="form-label">Giới tính</label>
                        <select class="form-select @error('gioi_tinh') is-invalid @enderror" name="gioi_tinh">
                            <option value="">Chọn giới tính</option>
                            <option value="nam" {{ old('gioi_tinh') == 'nam' ? 'selected' : '' }}>Nam</option>
                            <option value="nu" {{ old('gioi_tinh') == 'nu' ? 'selected' : '' }}>Nữ</option>
                            <option value="khac" {{ old('gioi_tinh') == 'khac' ? 'selected' : '' }}>Khác</option>
                        </select>
                        @error('gioi_tinh')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Lớp -->
                    <div class="col-md-6">
                        <label class="form-label">Lớp <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('lop') is-invalid @enderror" 
                               name="lop" value="{{ old('lop') }}" required>
                        @error('lop')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Khóa -->
                    <div class="col-md-6">
                        <label class="form-label">Khóa <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('khoa') is-invalid @enderror" 
                               name="khoa" value="{{ old('khoa') }}" required>
                        @error('khoa')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Địa chỉ -->
                    <div class="col-12">
                        <label class="form-label">Địa chỉ</label>
                        <textarea class="form-control @error('dia_chi') is-invalid @enderror" 
                                  name="dia_chi" rows="2">{{ old('dia_chi') }}</textarea>
                        @error('dia_chi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Loại tài khoản (ẩn, mặc định là sinhvien) -->
                    <input type="hidden" name="loai_tai_khoan" value="sinhvien">
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.sinhvien.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Hủy bỏ
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Thêm sinh viên
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const form = document.getElementById('addStudentForm');
    form.addEventListener('submit', function(e) {
        const password = document.querySelector('input[name="password"]').value;
        const confirmPassword = document.querySelector('input[name="password_confirmation"]').value;
        
        if (password !== confirmPassword) {
            e.preventDefault();
            alert('Mật khẩu xác nhận không khớp!');
            return false;
        }
        
        if (password.length < 6) {
            e.preventDefault();
            alert('Mật khẩu phải có ít nhất 6 ký tự!');
            return false;
        }
    });
});
</script>
@endpush