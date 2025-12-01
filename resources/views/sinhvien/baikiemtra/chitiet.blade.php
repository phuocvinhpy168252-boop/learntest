@extends('layouts.app')

@section('title', $baiKiemTra->tieu_de)

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
                    <li class="breadcrumb-item active">{{ $baiKiemTra->tieu_de }}</li>
                </ol>
            </nav>

            <!-- Thông tin bài kiểm tra -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-clipboard-list me-2"></i>{{ $baiKiemTra->tieu_de }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            @if($baiKiemTra->mo_ta)
                            <div class="mb-4">
                                <h6 class="text-primary">Mô tả:</h6>
                                <p class="mb-0">{{ $baiKiemTra->mo_ta }}</p>
                            </div>
                            @endif

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <h6 class="text-muted mb-2">
                                            <i class="fas fa-book me-2"></i>Môn học:
                                        </h6>
                                        <p class="mb-0">{{ $lopHoc->monHoc->ten_mon_hoc ?? 'Chưa có' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <h6 class="text-muted mb-2">
                                            <i class="fas fa-user-tie me-2"></i>Giảng viên:
                                        </h6>
                                        <p class="mb-0">{{ $lopHoc->giangVien->ten ?? 'Chưa có' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="text-primary mb-3">Thông tin kiểm tra:</h6>
                                    
                                    <div class="mb-2">
                                        <small class="text-muted">Loại:</small>
                                        <p class="mb-0">
                                            <span class="badge bg-info">
                                                <i class="{{ $baiKiemTra->getIcon() }} me-1"></i>
                                                {{ $baiKiemTra->getLoaiText() }}
                                            </span>
                                        </p>
                                    </div>
                                    
                                    <div class="mb-2">
                                        <small class="text-muted">Thời gian làm bài:</small>
                                        <p class="mb-0 fw-bold">{{ $baiKiemTra->thoi_gian_lam_bai }} phút</p>
                                    </div>
                                    
                                    <div class="mb-2">
                                        <small class="text-muted">Số câu hỏi:</small>
                                        <p class="mb-0 fw-bold">{{ $baiKiemTra->so_cau_hoi }}</p>
                                    </div>
                                    
                                    <div class="mb-2">
                                        <small class="text-muted">Điểm tối đa:</small>
                                        <p class="mb-0 fw-bold">{{ $baiKiemTra->diem_toi_da }}</p>
                                    </div>
                                    
                                    <div class="mb-2">
                                        <small class="text-muted">Thời gian bắt đầu:</small>
                                        <p class="mb-0">{{ $baiKiemTra->thoi_gian_bat_dau->format('H:i d/m/Y') }}</p>
                                    </div>
                                    
                                    <div class="mb-2">
                                        <small class="text-muted">Thời gian kết thúc:</small>
                                        <p class="mb-0">{{ $baiKiemTra->thoi_gian_ket_thuc->format('H:i d/m/Y') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Trạng thái và nút làm bài -->
            <div class="card shadow-sm">
                <div class="card-body">
                    @php
                        $now = now();
                        $canTakeTest = false;
                        $timeStatus = '';
                        $statusClass = 'secondary';
                        $statusText = 'Không xác định';
                        $timeRemaining = null;
                        
                        // Xác định trạng thái dựa trên ngày giờ
                        if ($baiKiemTra->thoi_gian_bat_dau > $now) {
                            // Chưa bắt đầu
                            $timeStatus = 'Bài kiểm tra sẽ bắt đầu vào ' . $baiKiemTra->thoi_gian_bat_dau->format('H:i') . ' ngày ' . $baiKiemTra->thoi_gian_bat_dau->format('d/m/Y');
                            $statusClass = 'info';
                            $statusText = 'Chưa bắt đầu';
                        } elseif ($baiKiemTra->thoi_gian_ket_thuc < $now) {
                            // Đã kết thúc
                            $timeStatus = 'Thời gian làm bài đã hết (kết thúc lúc ' . $baiKiemTra->thoi_gian_ket_thuc->format('H:i, ngày d/m/Y') . ')';
                            $statusClass = 'danger';
                            $statusText = 'Đã kết thúc';
                        } else {
                            // Đang mở (trong khoảng thời gian cho phép)
                            $timeStatus = 'Bài kiểm tra đang mở - Hãy làm bài ngay!';
                            $statusClass = 'success';
                            $statusText = 'Đang mở';
                            $timeRemaining = $baiKiemTra->thoi_gian_ket_thuc->diffInMinutes($now);
                            
                            if (!$daLam) {
                                $canTakeTest = true;
                            }
                        }
                    @endphp
                    
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5 class="mb-3">
                                <i class="fas fa-info-circle me-2"></i>Trạng thái:
                            </h5>
                            <div class="d-flex align-items-center mb-3">
                                <span class="badge bg-{{ $statusClass }} fs-6 me-3">{{ $statusText }}</span>
                                <p class="mb-0 text-muted">{{ $timeStatus }}</p>
                            </div>
                            
                            @if($timeRemaining !== null && $timeRemaining > 0)
                            <div class="alert alert-warning mb-3">
                                <i class="fas fa-hourglass-half me-2"></i>
                                <strong>Thời gian còn lại:</strong> {{ $timeRemaining }} phút
                            </div>
                            @endif
                            
                            @if($daLam)
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i>
                                <strong>Bạn đã hoàn thành bài kiểm tra này.</strong>
                                @if($ketQua && $ketQua->diem !== null)
                                <br><strong>Điểm:</strong> {{ $ketQua->diem }}/{{ $baiKiemTra->diem_toi_da }}
                                @endif
                            </div>
                            @endif
                        </div>
                        
                        <div class="col-md-4 text-md-end mt-3 mt-md-0">
                            @if($canTakeTest && !$daLam)
                            <a href="{{ route('sinhvien.lophoc.baikiemtra.lambai', [$lopHoc->ma_lop, $baiKiemTra->ma_bai_kiem_tra]) }}" 
                               class="btn btn-success btn-lg w-100 mb-2">
                                <i class="fas fa-play me-2"></i>
                                <span class="d-block fw-bold">Bắt đầu làm bài</span>
                            </a>
                            @elseif($daLam && $ketQua)
                            <a href="{{ route('sinhvien.lophoc.baikiemtra.ketqua', [$lopHoc->ma_lop, $baiKiemTra->ma_bai_kiem_tra]) }}" 
                               class="btn btn-primary btn-lg w-100 mb-2">
                                <i class="fas fa-chart-bar me-2"></i>
                                <span class="d-block fw-bold">Xem chi tiết kết quả</span>
                            </a>
                            @else
                            <button class="btn btn-secondary btn-lg w-100 mb-2" disabled>
                                <i class="fas fa-lock me-2"></i>
                                <span class="d-block fw-bold">Không thể làm bài</span>
                            </button>
                            @endif
                            
                            <a href="{{ route('sinhvien.lophoc.baikiemtra', $lopHoc->ma_lop) }}" 
                               class="btn btn-outline-secondary w-100">
                                <i class="fas fa-arrow-left me-2"></i>Quay lại danh sách
                            </a>
                        </div>
                    </div>
                    
                    @if($daLam && $ketQua)
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="text-primary mb-3">Thông tin làm bài:</h6>
                                    <div class="row">
                                        <div class="col-6">
                                            <small class="text-muted">Bắt đầu lúc:</small>
                                            <p class="mb-0">{{ $ketQua->thoi_gian_bat_dau->format('H:i d/m/Y') }}</p>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted">Nộp bài lúc:</small>
                                            <p class="mb-0">{{ $ketQua->thoi_gian_nop->format('H:i d/m/Y') }}</p>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <small class="text-muted">Thời gian làm bài:</small>
                                        <p class="mb-0 fw-bold">{{ $ketQua->thoi_gian_lam_bai }} phút</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Hướng dẫn -->
            @if($canTakeTest)
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>Hướng dẫn làm bài
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="mb-0">
                        <li>Bạn có <strong>{{ $baiKiemTra->thoi_gian_lam_bai }} phút</strong> để hoàn thành bài kiểm tra</li>
                        <li>Thời gian sẽ được tính từ khi bạn nhấn "Bắt đầu làm bài"</li>
                        <li>Bài làm sẽ tự động nộp khi hết thời gian</li>
                        <li>Không được mở lại trang nếu đã thoát ra</li>
                        <li>Đảm bảo kết nối internet ổn định trong quá trình làm bài</li>
                    </ul>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection