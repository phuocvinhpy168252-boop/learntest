@extends('layouts.giangvien')

@section('title', 'Chỉnh sửa Bài kiểm tra - ' . $baiKiemTra->tieu_de)

@section('content')

<div class="container-fluid py-4"> <div class="row justify-content-center"> <div class="col-md-8"> <div class="card"> <div class="card-header bg-warning text-dark"> <div class="d-flex justify-content-between align-items-center"> <h5 class="card-title mb-0"> <i class="fas fa-edit me-2"></i>Chỉnh sửa Bài kiểm tra </h5> <a href="{{ route('giangvien.lophoc.baikiemtra.show', [$lopHoc->ma_lop, $baiKiemTra->id]) }}" class="btn btn-light btn-sm"> <i class="fas fa-arrow-left me-1"></i>Quay lại </a> </div> </div> <div class="card-body"> <form action="{{ route('giangvien.lophoc.baikiemtra.update', [$lopHoc->ma_lop, $baiKiemTra->id]) }}" method="POST" id="editBKTForm"> @csrf @method('PUT')
text
                    <div class="mb-3">
                        <label class="form-label">Mã bài kiểm tra</label>
                        <input type="text" class="form-control bg-light" 
                               value="{{ $baiKiemTra->ma_bai_kiem_tra }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="tieu_de" class="form-label">Tiêu đề bài kiểm tra <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('tieu_de') is-invalid @enderror" 
                               id="tieu_de" name="tieu_de" 
                               value="{{ old('tieu_de', $baiKiemTra->tieu_de) }}" 
                               placeholder="Nhập tiêu đề bài kiểm tra" required>
                        @error('tieu_de')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="mo_ta" class="form-label">Mô tả</label>
                        <textarea class="form-control @error('mo_ta') is-invalid @enderror" 
                                  id="mo_ta" name="mo_ta" rows="3" 
                                  placeholder="Mô tả về bài kiểm tra...">{{ old('mo_ta', $baiKiemTra->mo_ta) }}</textarea>
                        @error('mo_ta')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="loai_bai_kiem_tra" class="form-label">Loại bài kiểm tra <span class="text-danger">*</span></label>
                                <select class="form-select @error('loai_bai_kiem_tra') is-invalid @enderror" 
                                        id="loai_bai_kiem_tra" name="loai_bai_kiem_tra" required>
                                    <option value="">Chọn loại bài kiểm tra</option>
                                    <option value="trac_nghiem" {{ old('loai_bai_kiem_tra', $baiKiemTra->loai_bai_kiem_tra) == 'trac_nghiem' ? 'selected' : '' }}>Trắc nghiệm</option>
                                    <option value="tu_luan" {{ old('loai_bai_kiem_tra', $baiKiemTra->loai_bai_kiem_tra) == 'tu_luan' ? 'selected' : '' }}>Tự luận</option>
                                    <option value="hop" {{ old('loai_bai_kiem_tra', $baiKiemTra->loai_bai_kiem_tra) == 'hop' ? 'selected' : '' }}>Hỗn hợp</option>
                                </select>
                                @error('loai_bai_kiem_tra')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="thoi_gian_lam_bai" class="form-label">Thời gian làm bài (phút) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('thoi_gian_lam_bai') is-invalid @enderror" 
                                       id="thoi_gian_lam_bai" name="thoi_gian_lam_bai" 
                                       value="{{ old('thoi_gian_lam_bai', $baiKiemTra->thoi_gian_lam_bai) }}" 
                                       min="1" max="180" required>
                                @error('thoi_gian_lam_bai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="diem_toi_da" class="form-label">Điểm tối đa <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('diem_toi_da') is-invalid @enderror" 
                                       id="diem_toi_da" name="diem_toi_da" 
                                       value="{{ old('diem_toi_da', $baiKiemTra->diem_toi_da) }}" 
                                       min="1" max="100" required>
                                @error('diem_toi_da')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Số câu hỏi</label>
                                <input type="number" class="form-control bg-light" 
                                       value="{{ $baiKiemTra->so_cau_hoi }}" readonly>
                                <small class="text-muted">Đã có {{ $baiKiemTra->so_cau_hoi }} câu hỏi</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="thoi_gian_bat_dau" class="form-label">Thời gian bắt đầu <span class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control @error('thoi_gian_bat_dau') is-invalid @enderror" 
                                       id="thoi_gian_bat_dau" name="thoi_gian_bat_dau" 
                                       value="{{ old('thoi_gian_bat_dau', $baiKiemTra->thoi_gian_bat_dau ? $baiKiemTra->thoi_gian_bat_dau->format('Y-m-d\TH:i') : '') }}" 
                                       required>
                                @error('thoi_gian_bat_dau')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="thoi_gian_ket_thuc" class="form-label">Thời gian kết thúc <span class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control @error('thoi_gian_ket_thuc') is-invalid @enderror" 
                                       id="thoi_gian_ket_thuc" name="thoi_gian_ket_thuc" 
                                       value="{{ old('thoi_gian_ket_thuc', $baiKiemTra->thoi_gian_ket_thuc ? $baiKiemTra->thoi_gian_ket_thuc->format('Y-m-d\TH:i') : '') }}" 
                                       required>
                                @error('thoi_gian_ket_thuc')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Cấu hình nâng cao -->
                    @php
                        // Lấy cấu hình từ model
                        $config = $baiKiemTra->cau_hinh ?? [];
                    @endphp
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="fas fa-cog me-2"></i>Cấu hình nâng cao</h6>
                        </div>
                        <div class="card-body">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" 
                                    id="cho_phep_quay_lai" name="cho_phep_quay_lai"
                                    {{ isset($config['cho_phep_quay_lai']) && $config['cho_phep_quay_lai'] ? 'checked' : '' }}>
                                <label class="form-check-label" for="cho_phep_quay_lai">
                                    Cho phép quay lại câu hỏi trước
                                </label>
                            </div>
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" 
                                    id="hien_thi_diem" name="hien_thi_diem"
                                    {{ isset($config['hien_thi_diem']) && $config['hien_thi_diem'] ? 'checked' : '' }}>
                                <label class="form-check-label" for="hien_thi_diem">
                                    Hiển thị điểm sau khi làm bài
                                </label>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" 
                                    id="ngau_nhien_cau_hoi" name="ngau_nhien_cau_hoi"
                                    {{ isset($config['ngau_nhien_cau_hoi']) && $config['ngau_nhien_cau_hoi'] ? 'checked' : '' }}>
                                <label class="form-check-label" for="ngau_nhien_cau_hoi">
                                    Xáo trộn thứ tự câu hỏi
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Thông tin lớp học -->
                    <div class="alert alert-info">
                        <h6 class="alert-heading mb-2">
                            <i class="fas fa-info-circle me-2"></i>Thông tin lớp học
                        </h6>
                        <p class="mb-1"><strong>Mã lớp:</strong> {{ $lopHoc->ma_lop }}</p>
                        <p class="mb-1"><strong>Tên lớp:</strong> {{ $lopHoc->ten_lop }}</p>
                        <p class="mb-0"><strong>Môn học:</strong> {{ $lopHoc->monHoc->ten_mon_hoc ?? 'N/A' }}</p>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('giangvien.lophoc.baikiemtra.show', [$lopHoc->ma_lop, $baiKiemTra->id]) }}" 
                           class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Hủy
                        </a>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save me-2"></i>Cập nhật bài kiểm tra
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div> @endsection
@push('scripts')

<script> document.addEventListener('DOMContentLoaded', function() { const form = document.getElementById('editBKTForm'); const thoiGianBatDau = document.getElementById('thoi_gian_bat_dau'); const thoiGianKetThuc = document.getElementById('thoi_gian_ket_thuc'); // Update thoi_gian_ket_thuc min when thoi_gian_bat_dau changes thoiGianBatDau.addEventListener('change', function() { thoiGianKetThuc.min = this.value; }); form.addEventListener('submit', function(e) { let valid = true; // Validate thoi_gian_ket_thuc > thoi_gian_bat_dau if (thoiGianKetThuc.value <= thoiGianBatDau.value) { valid = false; thoiGianKetThuc.classList.add('is-invalid'); alert('Thời gian kết thúc phải sau thời gian bắt đầu!'); } if (!valid) { e.preventDefault(); } }); }); </script>
@endpush