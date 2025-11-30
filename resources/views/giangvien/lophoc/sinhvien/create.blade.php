@extends('layouts.giangvien')

@section('title', 'Thêm Sinh viên - ' . $lopHoc->ten_lop)

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-user-plus me-2"></i>Thêm Sinh viên vào Lớp
                        </h5>
                        <a href="{{ route('giangvien.lophoc.sinhvien.index', $lopHoc->ma_lop) }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>Quay lại
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Thông tin lớp -->
                    <div class="alert alert-info mb-4">
                        <h6 class="alert-heading mb-2">
                            <i class="fas fa-info-circle me-2"></i>Thông tin lớp học
                        </h6>
                        <p class="mb-1"><strong>Mã lớp:</strong> {{ $lopHoc->ma_lop }}</p>
                        <p class="mb-1"><strong>Tên lớp:</strong> {{ $lopHoc->ten_lop }}</p>
                        <p class="mb-1"><strong>Sĩ số hiện tại:</strong> {{ $lopHoc->so_luong_sv_hien_tai }}/{{ $lopHoc->so_luong_sv }}</p>
                        <p class="mb-0"><strong>Chỗ trống:</strong> <span class="badge bg-success">{{ $lopHoc->so_luong_sv - $lopHoc->so_luong_sv_hien_tai }}</span></p>
                    </div>

                    <!-- Form tìm kiếm sinh viên -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Tìm kiếm Sinh viên</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="search-keyword" 
                                   placeholder="Nhập mã SV, tên, email hoặc SĐT...">
                            <button class="btn btn-primary" type="button" id="btn-search">
                                <i class="fas fa-search me-1"></i>Tìm kiếm
                            </button>
                        </div>
                        <small class="text-muted">Tìm kiếm sinh viên đã có tài khoản trong hệ thống</small>
                    </div>

                    <!-- Kết quả tìm kiếm -->
                    <div id="search-results" class="mb-4" style="display: none;">
                        <h6 class="mb-3">Kết quả tìm kiếm:</h6>
                        <div id="results-list" class="list-group"></div>
                    </div>

                    <!-- Form thêm sinh viên -->
                    <form action="{{ route('giangvien.lophoc.sinhvien.store', $lopHoc->ma_lop) }}" method="POST" id="add-student-form">
                        @csrf
                        <div class="mb-3">
                            <label for="ma_sinhvien" class="form-label">Mã Sinh viên <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('ma_sinhvien') is-invalid @enderror" 
                                   id="ma_sinhvien" name="ma_sinhvien" value="{{ old('ma_sinhvien') }}" 
                                   placeholder="Chọn sinh viên từ kết quả tìm kiếm" required readonly>
                            @error('ma_sinhvien')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Thông tin sinh viên được chọn -->
                        <div id="selected-student-info" class="alert alert-success" style="display: none;">
                            <h6 class="alert-heading mb-2">
                                <i class="fas fa-check-circle me-2"></i>Thông tin sinh viên được chọn
                            </h6>
                            <div id="student-details">
                                <!-- Thông tin sẽ được hiển thị ở đây -->
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('giangvien.lophoc.sinhvien.index', $lopHoc->ma_lop) }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Hủy
                            </a>
                            <button type="submit" class="btn btn-primary" id="btn-submit" disabled>
                                <i class="fas fa-user-plus me-2"></i>Thêm vào lớp
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
    const searchKeyword = document.getElementById('search-keyword');
    const btnSearch = document.getElementById('btn-search');
    const searchResults = document.getElementById('search-results');
    const resultsList = document.getElementById('results-list');
    const maSinhVien = document.getElementById('ma_sinhvien');
    const selectedStudentInfo = document.getElementById('selected-student-info');
    const studentDetails = document.getElementById('student-details');
    const btnSubmit = document.getElementById('btn-submit');

    // Tìm kiếm sinh viên
    btnSearch.addEventListener('click', function() {
        const keyword = searchKeyword.value.trim();
        
        if (keyword.length < 2) {
            alert('Vui lòng nhập ít nhất 2 ký tự để tìm kiếm');
            return;
        }

        // Hiển thị loading
        resultsList.innerHTML = '<div class="text-center py-3"><i class="fas fa-spinner fa-spin me-2"></i>Đang tìm kiếm...</div>';
        searchResults.style.display = 'block';

        // Gọi API tìm kiếm - SỬA: Thêm debug
        fetch(`{{ route('giangvien.lophoc.sinhvien.timkiem', $lopHoc->ma_lop) }}?keyword=${encodeURIComponent(keyword)}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log('Kết quả tìm kiếm:', data); // Debug console
            if (data.length > 0) {
                let html = '';
                data.forEach(student => {
                    html += `
                        <div class="list-group-item list-group-item-action student-item" 
                             data-ma-sv="${student.ma_sinhvien}" 
                             data-ten="${student.ten}" 
                             data-email="${student.email}" 
                             data-sdt="${student.so_dien_thoai}" 
                             data-lop="${student.lop}">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">${student.ten}</h6>
                                    <p class="mb-1 small text-muted">
                                        <strong>Mã SV:</strong> ${student.ma_sinhvien} | 
                                        <strong>Email:</strong> ${student.email} | 
                                        <strong>SĐT:</strong> ${student.so_dien_thoai}
                                    </p>
                                    <small class="text-muted">Lớp: ${student.lop || 'Chưa có'}</small>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary select-student">
                                    <i class="fas fa-check me-1"></i>Chọn
                                </button>
                            </div>
                        </div>
                    `;
                });
                resultsList.innerHTML = html;
            } else {
                resultsList.innerHTML = '<div class="text-center py-3 text-muted">Không tìm thấy sinh viên phù hợp</div>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            resultsList.innerHTML = '<div class="text-center py-3 text-danger">Có lỗi xảy ra khi tìm kiếm: ' + error.message + '</div>';
        });
    });

    // Chọn sinh viên
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('select-student') || e.target.closest('.select-student')) {
            const studentItem = e.target.closest('.student-item');
            const maSV = studentItem.dataset.maSv;
            const ten = studentItem.dataset.ten;
            const email = studentItem.dataset.email;
            const sdt = studentItem.dataset.sdt;
            const lop = studentItem.dataset.lop;

            // Cập nhật form
            maSinhVien.value = maSV;
            
            // Hiển thị thông tin sinh viên
            studentDetails.innerHTML = `
                <p class="mb-1"><strong>Mã SV:</strong> ${maSV}</p>
                <p class="mb-1"><strong>Họ tên:</strong> ${ten}</p>
                <p class="mb-1"><strong>Email:</strong> ${email}</p>
                <p class="mb-1"><strong>SĐT:</strong> ${sdt}</p>
                <p class="mb-0"><strong>Lớp:</strong> ${lop || 'Chưa có'}</p>
            `;
            selectedStudentInfo.style.display = 'block';
            
            // Kích hoạt nút submit
            btnSubmit.disabled = false;
            
            // Ẩn kết quả tìm kiếm
            searchResults.style.display = 'none';
        }
    });

    // Cho phép nhấn Enter để tìm kiếm
    searchKeyword.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            btnSearch.click();
        }
    });
});
</script>
@endpush