@extends('layouts.app')

@section('title', 'Kết quả: ' . $baiKiemTra->tieu_de)

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('sinhvien.khoahoc') }}">Khóa học của tôi</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('sinhvien.lophoc.baigiang', $lopHoc->ma_lop) }}">{{ $lopHoc->ten_lop }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('sinhvien.lophoc.baikiemtra', $lopHoc->ma_lop) }}">Bài kiểm tra</a>
                    </li>
                    <li class="breadcrumb-item active">Kết quả</li>
                </ol>
            </nav>

            <!-- Thông tin kết quả -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Kết quả bài kiểm tra
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h4 class="text-primary mb-2">{{ $baiKiemTra->tieu_de }}</h4>
                            <p class="text-muted mb-1">
                                <i class="fas fa-book me-1"></i>
                                {{ $lopHoc->monHoc->ten_mon_hoc ?? 'Chưa có môn học' }}
                            </p>
                            <p class="text-muted mb-3">
                                <i class="fas fa-user-tie me-1"></i>
                                Giảng viên: {{ $lopHoc->giangVien->ten ?? 'Chưa có GV' }}
                            </p>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h6 class="text-muted mb-2">Điểm số</h6>
                                    <div class="display-4 fw-bold text-primary mb-2">
                                        {{ $ketQua->diem }}
                                    </div>
                                    <div class="text-muted">
                                        trên {{ $baiKiemTra->diem_toi_da }} điểm
                                    </div>
                                    @php
                                        $percentage = ($ketQua->diem / $baiKiemTra->diem_toi_da) * 100;
                                        $gradeClass = 'success';
                                        if ($percentage < 50) {
                                            $gradeClass = 'danger';
                                        } elseif ($percentage < 70) {
                                            $gradeClass = 'warning';
                                        }
                                    @endphp
                                    <div class="mt-2">
                                        <span class="badge bg-{{ $gradeClass }} fs-6">
                                            {{ number_format($percentage, 1) }}%
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thông tin chi tiết -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-clock me-2"></i>Thời gian làm bài
                            </h6>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Bắt đầu:</span>
                                <span>{{ $ketQua->thoi_gian_bat_dau->format('H:i d/m/Y') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Nộp bài:</span>
                                <span>{{ $ketQua->thoi_gian_nop->format('H:i d/m/Y') }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Tổng thời gian:</span>
                                <span class="fw-bold">{{ $ketQua->thoi_gian_lam_bai }} phút</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-clipboard-list me-2"></i>Thông tin bài kiểm tra
                            </h6>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Loại:</span>
                                <span>{{ $baiKiemTra->getLoaiText() }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Số câu:</span>
                                <span>{{ $baiKiemTra->so_cau_hoi }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Thời gian làm:</span>
                                <span>{{ $baiKiemTra->thoi_gian_lam_bai }} phút</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-chart-pie me-2"></i>Thống kê
                            </h6>
                            @php
                                $correct = 0;
                                $incorrect = 0;
                                $unmarked = 0;
                                
                                foreach ($chiTiet as $detail) {
                                    if ($detail['diem'] > 0) {
                                        $correct++;
                                    } elseif ($detail['tra_loi'] !== null && $detail['diem'] == 0) {
                                        $incorrect++;
                                    } else {
                                        $unmarked++;
                                    }
                                }
                            @endphp
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-success">Đúng:</span>
                                <span class="fw-bold">{{ $correct }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-danger">Sai:</span>
                                <span class="fw-bold">{{ $incorrect }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Chưa chấm:</span>
                                <span class="fw-bold">{{ $unmarked }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chi tiết từng câu hỏi -->
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-list-check me-2"></i>Chi tiết từng câu hỏi
                    </h5>
                </div>
                <div class="card-body">
                    @foreach($chiTiet as $index => $detail)
                    <div class="question-result mb-4 p-3 border rounded">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h6 class="text-primary mb-0">
                                <span class="badge bg-primary me-2">Câu {{ $index + 1 }}</span>
                                {{ $detail['noi_dung'] }}
                            </h6>
                            <div>
                                @if($detail['diem'] > 0)
                                <span class="badge bg-success">
                                    <i class="fas fa-check me-1"></i>{{ $detail['diem'] }}/{{ $detail['diem_toi_da'] }}
                                </span>
                                @elseif($detail['tra_loi'] !== null && $detail['diem'] == 0)
                                <span class="badge bg-danger">
                                    <i class="fas fa-times me-1"></i>0/{{ $detail['diem_toi_da'] }}
                                </span>
                                @else
                                <span class="badge bg-secondary">
                                    <i class="fas fa-question me-1"></i>Chờ chấm
                                </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <h6 class="text-muted mb-2">Câu trả lời của bạn:</h6>
                                    <div class="p-2 border rounded bg-light">
                                        @if($detail['tra_loi'] !== null)
                                            @if($detail['loai_cau_hoi'] == 'trac_nghiem' || $detail['loai_cau_hoi'] == 'dung_sai')
                                                {{ $detail['tra_loi'] == '1' ? 'Đúng' : ($detail['tra_loi'] == '0' ? 'Sai' : $detail['tra_loi']) }}
                                            @else
                                                {!! nl2br(e($detail['tra_loi'])) !!}
                                            @endif
                                        @else
                                            <span class="text-muted">Không trả lời</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <h6 class="text-muted mb-2">Đáp án đúng:</h6>
                                    <div class="p-2 border rounded bg-light">
                                        @if($detail['dap_an_dung'] !== null)
                                            @if($detail['loai_cau_hoi'] == 'trac_nghiem' || $detail['loai_cau_hoi'] == 'dung_sai')
                                                {{ $detail['dap_an_dung'] == '1' ? 'Đúng' : ($detail['dap_an_dung'] == '0' ? 'Sai' : $detail['dap_an_dung']) }}
                                            @else
                                                {{ $detail['dap_an_dung'] }}
                                            @endif
                                        @else
                                            <span class="text-muted">Giảng viên sẽ cung cấp đáp án</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        @if($detail['loai_cau_hoi'] == 'tu_luan')
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Câu hỏi tự luận sẽ được giảng viên chấm điểm và phản hồi sau.
                        </div>
                        @endif
                        
                        <div class="mt-2">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Loại: {{ $detail['loai_cau_hoi'] == 'trac_nghiem' ? 'Trắc nghiệm' : 
                                       ($detail['loai_cau_hoi'] == 'dung_sai' ? 'Đúng/Sai' : 'Tự luận') }}
                            </small>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="card-footer">
                    <a href="{{ route('sinhvien.lophoc.baikiemtra', $lopHoc->ma_lop) }}" 
                       class="btn btn-primary">
                        <i class="fas fa-arrow-left me-2"></i>Quay lại danh sách bài kiểm tra
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.question-result.correct {
    border-left: 4px solid #28a745 !important;
    background-color: #f8fff9;
}

.question-result.incorrect {
    border-left: 4px solid #dc3545 !important;
    background-color: #fff8f8;
}

.question-result.pending {
    border-left: 4px solid #6c757d !important;
    background-color: #f8f9fa;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Thêm class cho từng câu hỏi dựa trên kết quả
    document.querySelectorAll('.question-result').forEach(function(el, index) {
        const detail = @json($chiTiet)[index];
        
        if (detail.diem > 0) {
            el.classList.add('correct');
        } else if (detail.tra_loi !== null && detail.diem == 0) {
            el.classList.add('incorrect');
        } else {
            el.classList.add('pending');
        }
    });
});
</script>
@endsection