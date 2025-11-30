@extends('layouts.admin')

@section('title', 'Thêm Giảng Viên')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Thêm Giảng Viên Mới</h5>
                        <a href="{{ route('admin.giangvien.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Quay lại
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.giangvien.storeGV') }}" method="POST" id="createGVForm">
                        @csrf

                        <div class="row">
                            <!-- Mã giảng viên (tự động) -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="ma_giangvien" class="form-label">Mã giảng viên</label>
                                    <input type="text" class="form-control bg-light" 
                                           id="ma_giangvien" value="{{ $nextMaGV ?? 'GV001' }}" readonly>
                                    <small class="text-muted">Mã giảng viên được tạo tự động</small>
                                    <input type="hidden" name="ma_giangvien" value="{{ $nextMaGV ?? 'GV001' }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="ten" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('ten') is-invalid @enderror" 
                                           id="ten" name="ten" value="{{ old('ten') }}" required>
                                    @error('ten')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="so_dien_thoai" class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('so_dien_thoai') is-invalid @enderror" 
                                           id="so_dien_thoai" name="so_dien_thoai" value="{{ old('so_dien_thoai') }}" required>
                                    @error('so_dien_thoai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Thêm phần mật khẩu -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label">Mật khẩu <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           id="password" name="password" required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" 
                                           id="password_confirmation" name="password_confirmation" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="ngay_sinh" class="form-label">Ngày sinh <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('ngay_sinh') is-invalid @enderror" 
                                           id="ngay_sinh" name="ngay_sinh" value="{{ old('ngay_sinh') }}" required>
                                    @error('ngay_sinh')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="gioi_tinh" class="form-label">Giới tính <span class="text-danger">*</span></label>
                                    <select class="form-select @error('gioi_tinh') is-invalid @enderror" 
                                            id="gioi_tinh" name="gioi_tinh" required>
                                        <option value="">Chọn giới tính</option>
                                        <option value="nam" {{ old('gioi_tinh') == 'nam' ? 'selected' : '' }}>Nam</option>
                                        <option value="nu" {{ old('gioi_tinh') == 'nu' ? 'selected' : '' }}>Nữ</option>
                                        <option value="khac" {{ old('gioi_tinh') == 'khac' ? 'selected' : '' }}>Khác</option>
                                    </select>
                                    @error('gioi_tinh')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="trinh_do" class="form-label">Trình độ <span class="text-danger">*</span></label>
                                    <select class="form-select @error('trinh_do') is-invalid @enderror" 
                                            id="trinh_do" name="trinh_do" required>
                                        <option value="">Chọn trình độ</option>
                                        <option value="Cử nhân" {{ old('trinh_do') == 'Cử nhân' ? 'selected' : '' }}>Cử nhân</option>
                                        <option value="Thạc sĩ" {{ old('trinh_do') == 'Thạc sĩ' ? 'selected' : '' }}>Thạc sĩ</option>
                                        <option value="Tiến sĩ" {{ old('trinh_do') == 'Tiến sĩ' ? 'selected' : '' }}>Tiến sĩ</option>
                                        <option value="Phó Giáo sư" {{ old('trinh_do') == 'Phó Giáo sư' ? 'selected' : '' }}>Phó Giáo sư</option>
                                        <option value="Giáo sư" {{ old('trinh_do') == 'Giáo sư' ? 'selected' : '' }}>Giáo sư</option>
                                    </select>
                                    @error('trinh_do')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                            <div class="mb-3">
                                <label for="mon_day" class="form-label">Môn dạy <span class="text-danger">*</span></label>
                                <select class="form-select @error('mon_day') is-invalid @enderror" 
                                        id="mon_day" name="mon_day" required>
                                    <option value="">Chọn môn dạy</option>
                                    @foreach($monHocs as $monHoc)
                                        <option value="{{ $monHoc->ten_mon_hoc }}" 
                                            {{ old('mon_day') == $monHoc->ten_mon_hoc ? 'selected' : '' }}>
                                            {{ $monHoc->ten_mon_hoc }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('mon_day')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="dia_chi" class="form-label">Địa chỉ</label>
                                    <input type="text" class="form-control @error('dia_chi') is-invalid @enderror" 
                                           id="dia_chi" name="dia_chi" value="{{ old('dia_chi') }}"
                                           placeholder="Địa chỉ liên hệ">
                                    @error('dia_chi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Loại tài khoản (ẩn, mặc định là giangvien) -->
                            <input type="hidden" name="loai_tai_khoan" value="giangvien">
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.giangvien.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Hủy
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Thêm giảng viên
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set max date for birth date (18 years ago)
    const today = new Date();
    const maxDate = new Date(today.getFullYear() - 18, today.getMonth(), today.getDate());
    document.getElementById('ngay_sinh').max = maxDate.toISOString().split('T')[0];

    // Form validation
    const form = document.getElementById('createGVForm');
    form.addEventListener('submit', function(e) {
        let valid = true;
        
        // Basic validation
        const requiredFields = form.querySelectorAll('[required]');
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                valid = false;
                field.classList.add('is-invalid');
            }
        });

        // Password validation
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('password_confirmation').value;
        
        if (password !== confirmPassword) {
            e.preventDefault();
            alert('Mật khẩu xác nhận không khớp!');
            valid = false;
        }
        
        if (password.length < 6) {
            e.preventDefault();
            alert('Mật khẩu phải có ít nhất 6 ký tự!');
            valid = false;
        }

        if (!valid) {
            e.preventDefault();
        }
    });

    // Remove invalid class when user starts typing
    const inputs = form.querySelectorAll('input, select');
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            if (this.value.trim()) {
                this.classList.remove('is-invalid');
            }
        });
        
        input.addEventListener('change', function() {
            if (this.value.trim()) {
                this.classList.remove('is-invalid');
            }
        });
    });
});
</script>

<style>
.form-label {
    font-weight: 600;
    margin-bottom: 0.5rem;
}
.alert-info {
    background-color: #e7f3ff;
    border-color: #b3d9ff;
}
.bg-light {
    background-color: #f8f9fa !important;
}
</style>
@endpush