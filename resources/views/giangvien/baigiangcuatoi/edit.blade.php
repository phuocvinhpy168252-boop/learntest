@extends('layouts.giangvien')

@section('title', 'Chỉnh sửa Bài giảng')

@section('content')
<div class="container-fluid py-4">
    <!-- Navigation -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('giangvien.baigiangcuatoi.index') }}">Bài giảng của tôi</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('giangvien.baigiangcuatoi.show', $baiGiang->id) }}">Chi tiết</a>
            </li>
            <li class="breadcrumb-item active">Chỉnh sửa</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-warning text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-edit me-2"></i>Chỉnh sửa Bài giảng
                        </h5>
                        <a href="{{ route('giangvien.baigiangcuatoi.show', $baiGiang->id) }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>Quay lại
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('giangvien.baigiangcuatoi.update', $baiGiang->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="tieu_de" class="form-label">Tiêu đề bài giảng <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('tieu_de') is-invalid @enderror" 
                                   id="tieu_de" name="tieu_de" value="{{ old('tieu_de', $baiGiang->tieu_de) }}" 
                                   placeholder="Nhập tiêu đề bài giảng" required>
                            @error('tieu_de')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="mo_ta" class="form-label">Mô tả</label>
                            <textarea class="form-control @error('mo_ta') is-invalid @enderror" 
                                      id="mo_ta" name="mo_ta" rows="3" 
                                      placeholder="Mô tả về bài giảng...">{{ old('mo_ta', $baiGiang->mo_ta) }}</textarea>
                            @error('mo_ta')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="loai_bai_giang" class="form-label">Loại bài giảng <span class="text-danger">*</span></label>
                                    <select class="form-select @error('loai_bai_giang') is-invalid @enderror" 
                                            id="loai_bai_giang" name="loai_bai_giang" required>
                                        <option value="">Chọn loại bài giảng</option>
                                        <option value="video" {{ old('loai_bai_giang', $baiGiang->loai_bai_giang) == 'video' ? 'selected' : '' }}>Video</option>
                                        <option value="pdf" {{ old('loai_bai_giang', $baiGiang->loai_bai_giang) == 'pdf' ? 'selected' : '' }}>PDF</option>
                                        <option value="document" {{ old('loai_bai_giang', $baiGiang->loai_bai_giang) == 'document' ? 'selected' : '' }}>Tài liệu</option>
                                        <option value="presentation" {{ old('loai_bai_giang', $baiGiang->loai_bai_giang) == 'presentation' ? 'selected' : '' }}>Bài thuyết trình</option>
                                        <option value="other" {{ old('loai_bai_giang', $baiGiang->loai_bai_giang) == 'other' ? 'selected' : '' }}>Khác</option>
                                    </select>
                                    @error('loai_bai_giang')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="thu_tu" class="form-label">Thứ tự hiển thị</label>
                                    <input type="number" class="form-control @error('thu_tu') is-invalid @enderror" 
                                           id="thu_tu" name="thu_tu" value="{{ old('thu_tu', $baiGiang->thu_tu) }}" 
                                           min="0" placeholder="0">
                                    <small class="text-muted">Số nhỏ hơn sẽ hiển thị trước</small>
                                    @error('thu_tu')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3" id="video-url-section">
                            <label for="url_video" class="form-label">URL Video</label>
                            <input type="url" class="form-control @error('url_video') is-invalid @enderror" 
                                   id="url_video" name="url_video" value="{{ old('url_video', $baiGiang->url_video) }}" 
                                   placeholder="https://www.youtube.com/watch?v=...">
                            <small class="text-muted">Dán link YouTube, Vimeo, hoặc video trực tiếp</small>
                            @error('url_video')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Thông tin hệ thống -->
                        <div class="alert alert-info">
                            <h6 class="alert-heading mb-2">
                                <i class="fas fa-info-circle me-2"></i>Thông tin hệ thống
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Mã bài giảng:</strong> {{ $baiGiang->ma_bai_giang }}</p>
                                    <p class="mb-1"><strong>Lớp học:</strong> {{ $baiGiang->lopHoc->ten_lop ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Ngày tạo:</strong> {{ $baiGiang->created_at->format('d/m/Y H:i') }}</p>
                                    <p class="mb-0"><strong>Lần cập nhật:</strong> {{ $baiGiang->updated_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- File hiện tại -->
                        @if($baiGiang->duong_dan_file)
                        <div class="alert alert-warning">
                            <h6 class="alert-heading mb-2">
                                <i class="fas fa-file me-2"></i>File hiện tại
                            </h6>
                            <div class="d-flex align-items-center">
                                <i class="{{ $baiGiang->getIcon() }} fa-2x text-{{ $baiGiang->getBadgeColor() }} me-3"></i>
                                <div class="flex-grow-1">
                                    <div class="fw-bold">{{ basename($baiGiang->duong_dan_file) }}</div>
                                    <small class="text-muted">{{ $baiGiang->duong_dan_file }}</small>
                                </div>
                                <a href="{{ Storage::disk('public')->url($baiGiang->duong_dan_file) }}" 
                                   target="_blank" class="btn btn-sm btn-outline-primary me-2">
                                    <i class="fas fa-download me-1"></i> Tải xuống
                                </a>
                            </div>
                            <small class="text-muted mt-2">Chú ý: Chỉnh sửa form này không thể thay đổi file đính kèm. Để thay đổi file, vui lòng chỉnh sửa từ trang quản lý lớp học.</small>
                        </div>
                        @endif

                        <div class="d-flex justify-content-between">
                            <div>
                                <a href="{{ route('giangvien.baigiangcuatoi.show', $baiGiang->id) }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-2"></i>Hủy
                                </a>
                                <a href="{{ route('giangvien.lophoc.baigiang.edit', [$baiGiang->ma_lop, $baiGiang->id]) }}" 
                                   class="btn btn-info">
                                    <i class="fas fa-edit me-2"></i>Chỉnh sửa chi tiết
                                </a>
                            </div>
                            <div>
                                <a href="{{ route('giangvien.lophoc.show', $baiGiang->ma_lop) }}" 
                                   class="btn btn-outline-primary me-2">
                                    <i class="fas fa-users me-2"></i>Về Lớp học
                                </a>
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-save me-2"></i>Cập nhật
                                </button>
                            </div>
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
    // Toggle hiển thị URL video
    const loaiBaiGiang = document.getElementById('loai_bai_giang');
    const videoUrlSection = document.getElementById('video-url-section');

    function toggleVideoSection() {
        if (loaiBaiGiang.value === 'video') {
            videoUrlSection.style.display = 'block';
        } else {
            videoUrlSection.style.display = 'none';
        }
    }

    loaiBaiGiang.addEventListener('change', toggleVideoSection);
    toggleVideoSection(); // Initialize
});
</script>
@endpush