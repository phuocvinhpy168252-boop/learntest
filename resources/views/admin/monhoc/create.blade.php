@extends('layouts.admin')

@section('title', 'Thêm Môn Học')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Thêm Môn Học Mới</h5>
                        <a href="{{ route('admin.monhoc.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Quay lại
                        </a>
                    </div>
                </div>
                <div class="card-body">
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

                    <form action="{{ route('admin.monhoc.store') }}" method="POST" id="createMonHocForm">
                        @csrf

                        <div class="row">
                            <!-- Mã môn học (tự động) -->
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="ma_mon_hoc" class="form-label">Mã môn học</label>
                                    <input type="text" class="form-control bg-light" 
                                           id="ma_mon_hoc" value="{{ $nextMaMonHoc ?? 'MH001' }}" readonly>
                                    <small class="text-muted">Mã môn học được tạo tự động</small>
                                </div>
                            </div>
                        </div>

                        <!-- Tên môn học -->
                        <div class="mb-3">
                            <label for="ten_mon_hoc" class="form-label">Tên môn học <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('ten_mon_hoc') is-invalid @enderror" 
                                   id="ten_mon_hoc" name="ten_mon_hoc" 
                                   value="{{ old('ten_mon_hoc') }}" 
                                   placeholder="Nhập tên môn học" required>
                            @error('ten_mon_hoc')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Mô tả -->
                        <div class="mb-3">
                            <label for="mo_ta" class="form-label">Mô tả môn học</label>
                            <textarea class="form-control @error('mo_ta') is-invalid @enderror" 
                                      id="mo_ta" name="mo_ta" rows="3" 
                                      placeholder="Mô tả ngắn gọn về môn học...">{{ old('mo_ta') }}</textarea>
                            <div class="form-text">Tối đa 500 ký tự</div>
                            @error('mo_ta')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.monhoc.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Hủy
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Thêm môn học
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
    const form = document.getElementById('createMonHocForm');
    const moTaTextarea = document.getElementById('mo_ta');
    
    // Giới hạn ký tự cho mô tả
    moTaTextarea.addEventListener('input', function() {
        if (this.value.length > 500) {
            this.value = this.value.substring(0, 500);
        }
    });

    // Form validation
    form.addEventListener('submit', function(e) {
        let valid = true;
        
        const requiredFields = form.querySelectorAll('[required]');
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                valid = false;
                field.classList.add('is-invalid');
            }
        });

        if (!valid) {
            e.preventDefault();
            alert('Vui lòng điền đầy đủ thông tin bắt buộc!');
        }
    });

    // Remove invalid class when user starts typing
    const inputs = form.querySelectorAll('input, select, textarea');
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
.bg-light {
    background-color: #f8f9fa !important;
}
</style>
@endpush