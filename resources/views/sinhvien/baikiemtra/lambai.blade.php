@extends('layouts.app')

@section('title', 'Làm bài kiểm tra: ' . $baiKiemTra->tieu_de)

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <!-- Timer và thông tin -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-danger text-white">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h5 class="mb-0">
                                <i class="fas fa-clock me-2"></i>Làm bài kiểm tra
                            </h5>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <div id="exam-timer" class="fw-bold fs-4">
                                {{ $baiKiemTra->thoi_gian_lam_bai }}:00
                            </div>
                            <small class="d-block">Thời gian còn lại</small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h4 class="text-primary mb-2">{{ $baiKiemTra->tieu_de }}</h4>
                            <p class="text-muted mb-1">
                                <i class="fas fa-book me-1"></i>
                                {{ $lopHoc->monHoc->ten_mon_hoc ?? 'Chưa có môn học' }}
                            </p>
                            <p class="text-muted mb-1">
                                <i class="fas fa-user-tie me-1"></i>
                                Giảng viên: {{ $lopHoc->giangVien->ten ?? 'Chưa có GV' }}
                            </p>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Loại:</span>
                                <span class="fw-bold">{{ $baiKiemTra->getLoaiText() }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Số câu:</span>
                                <span class="fw-bold">{{ $baiKiemTra->so_cau_hoi }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Điểm tối đa:</span>
                                <span class="fw-bold">{{ $baiKiemTra->diem_toi_da }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Thời gian làm bài:</span>
                                <span class="fw-bold">{{ $baiKiemTra->thoi_gian_lam_bai }} phút</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form làm bài -->
            <form id="exam-form" action="{{ route('sinhvien.lophoc.baikiemtra.nopbai', [$lopHoc->ma_lop, $baiKiemTra->ma_bai_kiem_tra]) }}" method="POST">
                @csrf
                <input type="hidden" name="thoi_gian_lam" id="thoi_gian_lam">
                
                <!-- Danh sách câu hỏi -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="fas fa-list-ol me-2"></i>Danh sách câu hỏi
                            <span class="badge bg-primary ms-2">{{ $cauHois->count() }} câu</span>
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Navigation câu hỏi -->
                        <div class="mb-4">
                            <div class="d-flex flex-wrap gap-2" id="question-nav">
                                @foreach($cauHois as $index => $cauHoi)
                                <button type="button" 
                                        class="btn btn-sm btn-outline-secondary question-nav-btn" 
                                        data-question="{{ $index + 1 }}">
                                    {{ $index + 1 }}
                                </button>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Nội dung câu hỏi -->
                        @foreach($cauHois as $index => $cauHoi)
                        <div class="question-container mb-4 p-3 border rounded" id="question-{{ $index + 1 }}" style="{{ $index > 0 ? 'display: none;' : '' }}">
                            <!-- Câu hỏi -->
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <h5 class="text-primary mb-0">
                                        <span class="badge bg-primary me-2">Câu {{ $index + 1 }}</span>
                                        {{ $cauHoi->noi_dung }}
                                    </h5>
                                    <span class="badge bg-info">
                                        {{ $cauHoi->diem }} điểm
                                    </span>
                                </div>
                                
                                <!-- Câu trả lời theo loại -->
                                @if($cauHoi->isTracNghiem())
                                    @php
                                        $luaChon = $cauHoi->getDapAnList();
                                        // Đảm bảo có đủ 4 lựa chọn
                                        if (empty($luaChon)) {
                                            $luaChon = [
                                                'Lựa chọn A',
                                                'Lựa chọn B', 
                                                'Lựa chọn C',
                                                'Lựa chọn D'
                                            ];
                                        } elseif (count($luaChon) < 4) {
                                            // Bổ sung lựa chọn còn thiếu
                                            $labels = ['A', 'B', 'C', 'D'];
                                            for ($i = count($luaChon); $i < 4; $i++) {
                                                $luaChon[$i] = 'Lựa chọn ' . $labels[$i];
                                            }
                                        }
                                    @endphp
                                    @if(!empty($luaChon))
                                    <div class="answer-container">
                                        @foreach($luaChon as $key => $giaTri)
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" 
                                                   type="radio" 
                                                   name="cau_tra_loi[{{ $cauHoi->id }}]" 
                                                   id="cau_{{ $cauHoi->id }}_{{ $key }}" 
                                                   value="{{ $key }}">
                                            <label class="form-check-label w-100" for="cau_{{ $cauHoi->id }}_{{ $key }}">
                                                <div class="p-3 border rounded bg-white" style="cursor: pointer; min-height: 50px; display: flex; align-items: center;">
                                                    <span class="badge bg-light text-dark me-3" style="min-width: 40px; text-align: center; font-size: 14px;">{{ chr(65 + $key) }}</span>
                                                    <span class="text-dark">{{ $giaTri }}</span>
                                                </div>
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                    @endif
                                    
                                @elseif($cauHoi->isDungSai())
                                <div class="answer-container">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" 
                                                       type="radio" 
                                                       name="cau_tra_loi[{{ $cauHoi->id }}]" 
                                                       id="cau_{{ $cauHoi->id }}_dung" 
                                                       value="1">
                                                <label class="form-check-label w-100" for="cau_{{ $cauHoi->id }}_dung">
                                                    <div class="p-3 border rounded text-center bg-success text-white">
                                                        <i class="fas fa-check me-2"></i>Đúng
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" 
                                                       type="radio" 
                                                       name="cau_tra_loi[{{ $cauHoi->id }}]" 
                                                       id="cau_{{ $cauHoi->id }}_sai" 
                                                       value="0">
                                                <label class="form-check-label w-100" for="cau_{{ $cauHoi->id }}_sai">
                                                    <div class="p-3 border rounded text-center bg-danger text-white">
                                                        <i class="fas fa-times me-2"></i>Sai
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                @elseif($cauHoi->isTuLuan())
                                <div class="answer-container">
                                    <textarea class="form-control" 
                                              name="cau_tra_loi[{{ $cauHoi->id }}]" 
                                              rows="6" 
                                              placeholder="Nhập câu trả lời của bạn tại đây..."></textarea>
                                    <small class="text-muted mt-2">Câu hỏi tự luận sẽ được giảng viên chấm điểm sau.</small>
                                </div>
                                @endif
                            </div>
                            
                            <!-- Navigation câu hỏi -->
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" 
                                        class="btn btn-outline-primary prev-question-btn" 
                                        {{ $index === 0 ? 'disabled' : '' }}>
                                    <i class="fas fa-arrow-left me-2"></i>Câu trước
                                </button>
                                
                                <div>
                                    <button type="button" 
                                            class="btn btn-outline-warning flag-question-btn" 
                                            data-question="{{ $index + 1 }}">
                                        <i class="fas fa-flag me-2"></i>Đánh dấu
                                    </button>
                                    
                                    @if($index < $cauHois->count() - 1)
                                    <button type="button" 
                                            class="btn btn-primary next-question-btn ms-2">
                                        Câu tiếp theo <i class="fas fa-arrow-right ms-2"></i>
                                    </button>
                                    @else
                                    <button type="button" 
                                            class="btn btn-success ms-2" 
                                            id="submit-exam-btn">
                                        <i class="fas fa-paper-plane me-2"></i>Nộp bài
                                    </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </form>
            
            <!-- Modal xác nhận nộp bài -->
            <div class="modal fade" id="submitModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-warning">
                            <h5 class="modal-title">
                                <i class="fas fa-exclamation-triangle me-2"></i>Xác nhận nộp bài
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p>Bạn có chắc chắn muốn nộp bài không?</p>
                            <p class="text-danger">
                                <i class="fas fa-info-circle me-2"></i>
                                Sau khi nộp bài, bạn không thể làm lại!
                            </p>
                            <div class="alert alert-info">
                                <i class="fas fa-clock me-2"></i>
                                Thời gian làm bài: <span id="final-time">0</span> phút
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <button type="button" class="btn btn-danger" id="confirm-submit">
                                <i class="fas fa-paper-plane me-2"></i>Nộp bài
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.question-container {
    background-color: #f8f9fa;
    border-left: 4px solid #007bff !important;
}

.form-check-input:checked + label > div {
    background-color: #e7f3ff !important;
    border-color: #007bff !important;
    border-width: 2px !important;
}

.form-check-label {
    cursor: pointer;
    user-select: none;
}

.answer-container .form-check-label > div {
    transition: all 0.2s ease;
}

.answer-container .form-check-label:hover > div {
    background-color: #f0f0f0 !important;
    border-color: #007bff !important;
}

#exam-timer {
    font-family: 'Courier New', monospace;
    background: #212529;
    padding: 10px 20px;
    border-radius: 5px;
    display: inline-block;
}

.time-warning {
    animation: blink 1s infinite;
}

@keyframes blink {
    0% { color: #dc3545; }
    50% { color: #ffc107; }
    100% { color: #dc3545; }
}

.question-nav-btn.active {
    background-color: #007bff;
    color: white;
}

.question-nav-btn.flagged {
    background-color: #ffc107;
    color: black;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Biến toàn cục
    let currentQuestion = 1;
    let totalQuestions = {{ $cauHois->count() }};
    let examTime = {{ $baiKiemTra->thoi_gian_lam_bai }} * 60; // Chuyển sang giây
    let timeSpent = 0;
    let timerInterval;
    let flaggedQuestions = new Set();
    let answeredQuestions = new Set();
    
    // Khởi tạo timer
    function initTimer() {
        const timerElement = document.getElementById('exam-timer');
        const startTime = new Date();
        
        timerInterval = setInterval(function() {
            timeSpent++;
            const remainingTime = examTime - timeSpent;
            
            if (remainingTime <= 0) {
                clearInterval(timerInterval);
                submitExam();
                return;
            }
            
            const minutes = Math.floor(remainingTime / 60);
            const seconds = remainingTime % 60;
            
            timerElement.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            
            // Cảnh báo khi còn 5 phút
            if (remainingTime <= 300) {
                timerElement.classList.add('time-warning');
            }
            
            // Lưu thời gian đã làm
            document.getElementById('thoi_gian_lam').value = Math.floor(timeSpent / 60);
        }, 1000);
    }
    
    // Chuyển câu hỏi
    function showQuestion(questionNumber) {
        // Ẩn tất cả câu hỏi
        document.querySelectorAll('.question-container').forEach(function(el) {
            el.style.display = 'none';
        });
        
        // Hiển thị câu hỏi được chọn
        document.getElementById(`question-${questionNumber}`).style.display = 'block';
        
        // Cập nhật nút navigation
        document.querySelectorAll('.question-nav-btn').forEach(function(btn) {
            btn.classList.remove('active');
        });
        document.querySelector(`.question-nav-btn[data-question="${questionNumber}"]`).classList.add('active');
        
        // Cập nhật biến hiện tại
        currentQuestion = questionNumber;
        
        // Cập nhật trạng thái nút điều hướng
        updateNavigationButtons();
    }
    
    // Cập nhật nút điều hướng
    function updateNavigationButtons() {
        document.querySelectorAll('.prev-question-btn').forEach(function(btn) {
            btn.disabled = currentQuestion === 1;
        });
        
        document.querySelectorAll('.next-question-btn').forEach(function(btn) {
            btn.style.display = currentQuestion === totalQuestions ? 'none' : 'inline-block';
        });
    }
    
    // Đánh dấu câu hỏi
    function flagQuestion(questionNumber) {
        const btn = document.querySelector(`.question-nav-btn[data-question="${questionNumber}"]`);
        
        if (flaggedQuestions.has(questionNumber)) {
            flaggedQuestions.delete(questionNumber);
            btn.classList.remove('flagged');
            btn.innerHTML = questionNumber;
        } else {
            flaggedQuestions.add(questionNumber);
            btn.classList.add('flagged');
            btn.innerHTML = `${questionNumber} <i class="fas fa-flag"></i>`;
        }
    }
    
    // Theo dõi câu trả lời đã chọn
    function trackAnswers() {
        document.querySelectorAll('input[type="radio"]:checked, textarea').forEach(function(el) {
            const questionId = el.name.match(/\[(\d+)\]/)[1];
            answeredQuestions.add(parseInt(questionId));
        });
    }
    
    // Nộp bài
    function submitExam() {
        // Hiển thị thời gian cuối cùng
        document.getElementById('final-time').textContent = Math.floor(timeSpent / 60);
        
        // Hiển thị modal xác nhận
        const modal = new bootstrap.Modal(document.getElementById('submitModal'));
        modal.show();
        
        // Xử lý khi đóng modal mà không nộp bài
        document.getElementById('submitModal').addEventListener('hidden.bs.modal', function() {
            // Timer tiếp tục chạy nếu người dùng hủy
            if (!timerInterval || !document.getElementById('thoi_gian_lam').value) {
                // Timer đã dừng, không cần khôi phục
            }
        }, { once: true });
    }
    
    // Xử lý sự kiện
    document.addEventListener('click', function(e) {
        // Navigation câu hỏi
        if (e.target.classList.contains('question-nav-btn')) {
            showQuestion(parseInt(e.target.dataset.question));
        }
        
        // Câu trước
        if (e.target.classList.contains('prev-question-btn')) {
            showQuestion(currentQuestion - 1);
        }
        
        // Câu tiếp theo
        if (e.target.classList.contains('next-question-btn')) {
            showQuestion(currentQuestion + 1);
        }
        
        // Đánh dấu câu hỏi
        if (e.target.classList.contains('flag-question-btn')) {
            flagQuestion(currentQuestion);
        }
        
        // Nút nộp bài
        if (e.target.id === 'submit-exam-btn') {
            submitExam();
        }
        
        // Xác nhận nộp bài
        if (e.target.id === 'confirm-submit') {
            clearInterval(timerInterval);
            trackAnswers();
            document.getElementById('exam-form').submit();
        }
    });
    
    // Theo dõi thay đổi câu trả lời
    document.querySelectorAll('input, textarea').forEach(function(el) {
        el.addEventListener('change', trackAnswers);
    });
    
    // Khởi tạo
    initTimer();
    updateNavigationButtons();
    
    // Ngăn chặn refresh trang
    window.addEventListener('beforeunload', function(e) {
        if (timerInterval) {
            e.preventDefault();
            e.returnValue = 'Bạn có chắc muốn rời khỏi trang? Bài làm của bạn có thể bị mất.';
        }
    });
});
</script>
@endsection