@extends('layouts.giangvien')

@section('title', 'Thêm Câu hỏi - ' . $baiKiemTra->tieu_de)

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-plus-circle me-2"></i>Thêm Câu hỏi mới
                        </h5>
                        <a href="{{ route('giangvien.lophoc.baikiemtra.cauhoi.index', [$lopHoc->ma_lop, $baiKiemTra->id]) }}" 
                           class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Quay lại
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('giangvien.lophoc.baikiemtra.cauhoi.store', [$lopHoc->ma_lop, $baiKiemTra->id]) }}" 
                          method="POST" id="createCauHoiForm">
                        @csrf

                        <!-- Loại câu hỏi -->
                        <div class="mb-3">
                            <label class="form-label">Loại câu hỏi <span class="text-danger">*</span></label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="loai_cau_hoi" 
                                       id="typeTracNghiem" value="trac_nghiem" checked>
                                <label class="form-check-label" for="typeTracNghiem">
                                    <i class="fas fa-list-ol me-1"></i>Trắc nghiệm
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="loai_cau_hoi" 
                                       id="typeTuLuan" value="tu_luan">
                                <label class="form-check-label" for="typeTuLuan">
                                    <i class="fas fa-edit me-1"></i>Tự luận
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="loai_cau_hoi" 
                                       id="typeDungSai" value="dung_sai">
                                <label class="form-check-label" for="typeDungSai">
                                    <i class="fas fa-check-double me-1"></i>Đúng/Sai
                                </label>
                            </div>
                        </div>

                        <!-- Nội dung câu hỏi -->
                        <div class="mb-3">
                            <label for="noi_dung" class="form-label">Nội dung câu hỏi <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="noi_dung" name="noi_dung" rows="4" 
                                      placeholder="Nhập nội dung câu hỏi..." required></textarea>
                        </div>

                        <!-- Phần đáp án trắc nghiệm (chỉ hiển thị khi chọn trắc nghiệm) -->
                        <div id="tracNghiemSection">
                            <div class="mb-3">
                                <label class="form-label">Đáp án trắc nghiệm <span class="text-danger">*</span></label>
                                <div id="answersContainer">
                                    <div class="input-group mb-2">
                                        <div class="input-group-text">
                                            <input class="form-check-input mt-0" type="radio" name="dap_an_dung" value="0" checked>
                                        </div>
                                        <input type="text" class="form-control" name="lua_chon[]" placeholder="Đáp án A">
                                    </div>
                                    <div class="input-group mb-2">
                                        <div class="input-group-text">
                                            <input class="form-check-input mt-0" type="radio" name="dap_an_dung" value="1">
                                        </div>
                                        <input type="text" class="form-control" name="lua_chon[]" placeholder="Đáp án B">
                                    </div>
                                    <div class="input-group mb-2">
                                        <div class="input-group-text">
                                            <input class="form-check-input mt-0" type="radio" name="dap_an_dung" value="2">
                                        </div>
                                        <input type="text" class="form-control" name="lua_chon[]" placeholder="Đáp án C">
                                    </div>
                                    <div class="input-group mb-3">
                                        <div class="input-group-text">
                                            <input class="form-check-input mt-0" type="radio" name="dap_an_dung" value="3">
                                        </div>
                                        <input type="text" class="form-control" name="lua_chon[]" placeholder="Đáp án D">
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="addAnswerField()">
                                    <i class="fas fa-plus me-1"></i> Thêm đáp án
                                </button>
                            </div>
                        </div>

                        <!-- Phần đáp án tự luận (chỉ hiển thị khi chọn tự luận) -->
                        <div id="tuLuanSection" style="display: none;">
                            <div class="mb-3">
                                <label for="dapAnMau" class="form-label">Đáp án mẫu (tùy chọn)</label>
                                <textarea class="form-control" id="dapAnMau" name="dapAnMau" rows="4" 
                                          placeholder="Nhập đáp án mẫu cho câu hỏi tự luận..."></textarea>
                            </div>
                        </div>

                        <!-- Phần đáp án đúng/sai -->
                        <div id="dungSaiSection" style="display: none;">
                            <div class="mb-3">
                                <label class="form-label">Đáp án <span class="text-danger">*</span></label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="dap_an" value="1" id="dapAnDung">
                                    <label class="form-check-label" for="dapAnDung">
                                        <i class="fas fa-check text-success me-1"></i>Đúng
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="dap_an" value="0" id="dapAnSai">
                                    <label class="form-check-label" for="dapAnSai">
                                        <i class="fas fa-times text-danger me-1"></i>Sai
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Điểm và độ khó -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="diem" class="form-label">Điểm của câu hỏi <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="diem" name="diem" 
                                           min="0.5" max="10" step="0.5" value="1" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="thu_tu" class="form-label">Thứ tự (tùy chọn)</label>
                                    <input type="number" class="form-control" id="thu_tu" name="thu_tu" 
                                           min="0" placeholder="Để trống để tự động sắp xếp">
                                </div>
                            </div>
                        </div>

                        <!-- Thông tin bài kiểm tra -->
                        <div class="alert alert-info">
                            <strong>Bài kiểm tra:</strong> {{ $baiKiemTra->tieu_de }}<br>
                            <strong>Lớp học:</strong> {{ $lopHoc->ten_lop }}<br>
                            <strong>Số câu hỏi hiện tại:</strong> {{ $baiKiemTra->so_cau_hoi }}
                        </div>

                        <!-- Nút submit -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('giangvien.lophoc.baikiemtra.cauhoi.index', [$lopHoc->ma_lop, $baiKiemTra->id]) }}" 
                               class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i> Hủy
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-plus me-1"></i> Thêm câu hỏi
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
let answerIndex = 4;

function toggleQuestionType() {
    const loaiCauHoi = document.querySelector('input[name="loai_cau_hoi"]:checked').value;
    document.getElementById('tracNghiemSection').style.display = loaiCauHoi === 'trac_nghiem' ? 'block' : 'none';
    document.getElementById('tuLuanSection').style.display = loaiCauHoi === 'tu_luan' ? 'block' : 'none';
    document.getElementById('dungSaiSection').style.display = loaiCauHoi === 'dung_sai' ? 'block' : 'none';
}

function addAnswerField() {
    const container = document.getElementById('answersContainer');
    const newAnswer = document.createElement('div');
    newAnswer.className = 'input-group mb-2';
    newAnswer.innerHTML = `
        <div class="input-group-text">
            <input class="form-check-input mt-0" type="radio" name="dap_an_dung" value="${answerIndex}">
        </div>
        <input type="text" class="form-control" name="lua_chon[]" placeholder="Đáp án ${String.fromCharCode(65 + answerIndex)}">
        <button class="btn btn-outline-danger" type="button" onclick="removeAnswerField(this)">
            <i class="fas fa-times"></i>
        </button>
    `;
    container.appendChild(newAnswer);
    answerIndex++;
}

function removeAnswerField(button) {
    button.closest('.input-group').remove();
}

document.addEventListener('DOMContentLoaded', function() {
    // Toggle question type sections
    document.querySelectorAll('input[name="loai_cau_hoi"]').forEach(radio => {
        radio.addEventListener('change', toggleQuestionType);
    });

    // Form validation
    document.querySelector('#createCauHoiForm form, form').addEventListener('submit', function(e) {
        const loaiCauHoi = document.querySelector('input[name="loai_cau_hoi"]:checked').value;
        const noiDung = document.getElementById('noi_dung').value.trim();
        const diem = document.getElementById('diem').value;

        // Check nội dung
        if (!noiDung) {
            e.preventDefault();
            alert('Vui lòng nhập nội dung câu hỏi!');
            return;
        }

        // Check diem
        if (!diem || parseFloat(diem) < 0.5) {
            e.preventDefault();
            alert('Điểm câu hỏi phải từ 0.5 trở lên!');
            return;
        }

        // Validate dựa trên loại câu hỏi
        if (loaiCauHoi === 'trac_nghiem') {
            const answers = document.querySelectorAll('input[name="lua_chon[]"]');
            if (answers.length < 2) {
                e.preventDefault();
                alert('Câu hỏi trắc nghiệm cần ít nhất 2 đáp án!');
                return;
            }
            
            let hasEmpty = false;
            answers.forEach(input => {
                if (!input.value.trim()) {
                    hasEmpty = true;
                }
            });
            
            if (hasEmpty) {
                e.preventDefault();
                alert('Vui lòng điền hết tất cả đáp án!');
                return;
            }
        } else if (loaiCauHoi === 'dung_sai') {
            const dapAnDungSai = document.querySelector('input[name="dap_an"]:checked');
            if (!dapAnDungSai) {
                e.preventDefault();
                alert('Vui lòng chọn đáp án (Đúng/Sai)!');
                return;
            }
        }
    });
});
</script>
@endpush
