@extends('layouts.giangvien')

@section('title', 'Thêm Bài giảng - ' . $lopHoc->ten_lop)

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-plus-circle me-2"></i>Thêm Bài giảng mới
                        </h5>
                        <a href="{{ route('giangvien.lophoc.baigiang.index', $lopHoc->ma_lop) }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>Quay lại
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('giangvien.lophoc.baigiang.store', $lopHoc->ma_lop) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="tieu_de" class="form-label">Tiêu đề bài giảng <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('tieu_de') is-invalid @enderror" 
                                   id="tieu_de" name="tieu_de" value="{{ old('tieu_de') }}" 
                                   placeholder="Nhập tiêu đề bài giảng" required>
                            @error('tieu_de')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="mo_ta" class="form-label">Mô tả</label>
                            <textarea class="form-control @error('mo_ta') is-invalid @enderror" 
                                      id="mo_ta" name="mo_ta" rows="3" 
                                      placeholder="Mô tả về bài giảng...">{{ old('mo_ta') }}</textarea>
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
                                        <option value="video" {{ old('loai_bai_giang') == 'video' ? 'selected' : '' }}>Video</option>
                                        <option value="pdf" {{ old('loai_bai_giang') == 'pdf' ? 'selected' : '' }}>PDF</option>
                                        <option value="document" {{ old('loai_bai_giang') == 'document' ? 'selected' : '' }}>Tài liệu</option>
                                        <option value="presentation" {{ old('loai_bai_giang') == 'presentation' ? 'selected' : '' }}>Bài thuyết trình</option>
                                        <option value="other" {{ old('loai_bai_giang') == 'other' ? 'selected' : '' }}>Khác</option>
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
                                           id="thu_tu" name="thu_tu" value="{{ old('thu_tu', 0) }}" 
                                           min="0" placeholder="0">
                                    <small class="text-muted">Số nhỏ hơn sẽ hiển thị trước</small>
                                    @error('thu_tu')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3" id="file-upload-section">
                            <label for="file_upload" class="form-label">Tải lên file</label>
                            <input type="file" class="form-control @error('file_upload') is-invalid @enderror" 
                                   id="file_upload" name="file_upload">
                            <small class="text-muted">Chấp nhận: PDF, Word, PowerPoint, ZIP (Tối đa 10MB)</small>
                            @error('file_upload')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3" id="video-url-section" style="display: none;">
                            <label for="url_video" class="form-label">URL Video</label>
                            <input type="url" class="form-control @error('url_video') is-invalid @enderror" 
                                   id="url_video" name="url_video" value="{{ old('url_video') }}" 
                                   placeholder="https://www.youtube.com/watch?v=...">
                            <small class="text-muted">Dán link YouTube, Vimeo, hoặc video trực tiếp</small>
                            @error('url_video')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info">
                            <h6 class="alert-heading mb-2">
                                <i class="fas fa-info-circle me-2"></i>Thông tin lớp học
                            </h6>
                            <p class="mb-1"><strong>Mã lớp:</strong> {{ $lopHoc->ma_lop }}</p>
                            <p class="mb-0"><strong>Tên lớp:</strong> {{ $lopHoc->ten_lop }}</p>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('giangvien.lophoc.baigiang.index', $lopHoc->ma_lop) }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Hủy
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Thêm bài giảng
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
    const loaiBaiGiang = document.getElementById('loai_bai_giang');
    const fileUploadSection = document.getElementById('file-upload-section');
    const videoUrlSection = document.getElementById('video-url-section');

    function toggleSections() {
        const selectedValue = loaiBaiGiang.value;
        
        if (selectedValue === 'video') {
            fileUploadSection.style.display = 'none';
            videoUrlSection.style.display = 'block';
        } else {
            fileUploadSection.style.display = 'block';
            videoUrlSection.style.display = 'none';
        }
    }

    loaiBaiGiang.addEventListener('change', toggleSections);
    toggleSections(); // Initialize on page load
});
</script>
@endpush