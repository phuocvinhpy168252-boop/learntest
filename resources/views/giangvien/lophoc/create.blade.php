@extends('layouts.giangvien')

@section('title', 'Tạo Lớp học mới')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-plus-circle me-2"></i>Tạo Lớp học mới
                        </h5>
                        <a href="{{ route('giangvien.lophoc.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>Quay lại
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('giangvien.lophoc.store') }}" method="POST" id="createLopForm">
                        @csrf

                        <div class="row">
                            <!-- Mã lớp (tự động) -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="ma_lop" class="form-label">Mã lớp <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control bg-light" 
                                           id="ma_lop" value="{{ $nextMaLop }}" readonly>
                                    <small class="text-muted">Mã lớp được tạo tự động</small>
                                </div>
                            </div>

                            <!-- Tên lớp -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="ten_lop" class="form-label">Tên lớp <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('ten_lop') is-invalid @enderror" 
                                           id="ten_lop" name="ten_lop" value="{{ old('ten_lop') }}" 
                                           placeholder="Nhập tên lớp học" required>
                                    @error('ten_lop')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Môn học -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="ma_mon_hoc" class="form-label">Môn học <span class="text-danger">*</span></label>
                                    <select class="form-select @error('ma_mon_hoc') is-invalid @enderror" 
                                            id="ma_mon_hoc" name="ma_mon_hoc" required>
                                        <option value="">Chọn môn học</option>
                                        @foreach($monHocs as $monHoc)
                                            <option value="{{ $monHoc->ma_mon_hoc }}" 
                                                {{ old('ma_mon_hoc') == $monHoc->ma_mon_hoc ? 'selected' : '' }}>
                                                {{ $monHoc->ten_mon_hoc }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('ma_mon_hoc')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Số lượng sinh viên -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="so_luong_sv" class="form-label">Số lượng sinh viên <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('so_luong_sv') is-invalid @enderror" 
                                           id="so_luong_sv" name="so_luong_sv" value="{{ old('so_luong_sv', 30) }}" 
                                           min="1" max="100" required>
                                    <small class="text-muted">Số lượng tối đa: 100 sinh viên</small>
                                    @error('so_luong_sv')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Phòng học -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phong_hoc" class="form-label">Phòng học <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('phong_hoc') is-invalid @enderror" 
                                           id="phong_hoc" name="phong_hoc" value="{{ old('phong_hoc') }}" 
                                           placeholder="VD: A101, B201..." required>
                                    @error('phong_hoc')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Thời gian học -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="thoi_gian_hoc" class="form-label">Thời gian học <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('thoi_gian_hoc') is-invalid @enderror" 
                                           id="thoi_gian_hoc" name="thoi_gian_hoc" value="{{ old('thoi_gian_hoc') }}" 
                                           placeholder="VD: T2-4-6, 7h30-9h30" required>
                                    @error('thoi_gian_hoc')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Mô tả -->
                        <div class="mb-3">
                            <label for="mo_ta" class="form-label">Mô tả lớp học</label>
                            <textarea class="form-control @error('mo_ta') is-invalid @enderror" 
                                      id="mo_ta" name="mo_ta" rows="3" 
                                      placeholder="Mô tả về lớp học, nội dung giảng dạy...">{{ old('mo_ta') }}</textarea>
                            @error('mo_ta')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Thông tin giảng viên (chỉ hiển thị) -->
                        <div class="alert alert-info">
                            <h6 class="alert-heading mb-2">
                                <i class="fas fa-info-circle me-2"></i>Thông tin giảng viên
                            </h6>
                            <p class="mb-1"><strong>Mã GV:</strong> {{ $giangVien->ma_giangvien }}</p>
                            <p class="mb-0"><strong>Tên GV:</strong> {{ $giangVien->ten }}</p>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('giangvien.lophoc.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Hủy
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Tạo lớp học
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
    const form = document.getElementById('createLopForm');
    
    form.addEventListener('submit', function(e) {
        let valid = true;
        
        // Kiểm tra các trường bắt buộc
        const requiredFields = form.querySelectorAll('[required]');
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                valid = false;
                field.classList.add('is-invalid');
            }
        });

        // Kiểm tra số lượng sinh viên
        const soLuongSV = document.getElementById('so_luong_sv');
        if (soLuongSV.value < 1 || soLuongSV.value > 100) {
            valid = false;
            soLuongSV.classList.add('is-invalid');
        }

        if (!valid) {
            e.preventDefault();
            alert('Vui lòng kiểm tra lại thông tin!');
        }
    });

    // Xóa class invalid khi người dùng nhập
    const inputs = form.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
    });
});
</script>
@endpush