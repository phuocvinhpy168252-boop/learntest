@extends('layouts.app')

@section('title', 'Bài giảng - ' . $lopHoc->ten_lop)

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="card shadow-md mb-4">
                <div class="card-body">
                    <nav aria-label="breadcrumb" class="mb-3">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('sinhvien.khoahoc') }}" class="text-decoration-none hover-lift">Khóa học của tôi</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $lopHoc->ten_lop }}</li>
                        </ol>
                    </nav>
                    
                    <div class="row mt-3 align-items-center">
                        <div class="col-md-8">
                            <h4 class="text-primary mb-2 fw-bold">{{ $lopHoc->ten_lop }}</h4>
                            <div class="d-flex flex-wrap gap-3 mb-2">
                                <p class="text-muted mb-0">
                                    <i class="fas fa-book me-1"></i>
                                    {{ $lopHoc->monHoc->ten_mon_hoc ?? 'Chưa có môn học' }}
                                </p>
                                <p class="text-muted mb-0">
                                    <i class="fas fa-user-tie me-1"></i>
                                    Giảng viên: {{ $lopHoc->giangVien->ten ?? 'Chưa có GV' }}
                                </p>
                                <p class="text-muted mb-0">
                                    <i class="fas fa-calendar me-1"></i>
                                    Mã lớp: {{ $lopHoc->ma_lop }}
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <div class="d-flex flex-column align-items-end">
                                <span class="badge bg-success mb-2 px-3 py-2 fs-6 animate-pulse">
                                    <i class="fas fa-check-circle me-1"></i>Đang học
                                </span>
                                @php
                                    $tiLeHoanThanh = isset($tiLeHoanThanh) ? $tiLeHoanThanh : 0;
                                @endphp
                                <div class="progress w-100" style="height: 6px;">
                                    <div class="progress-bar" 
                                         style="width: {{ $tiLeHoanThanh }}%; 
                                                background: linear-gradient(90deg, var(--secondary-color), var(--primary-color));">
                                    </div>
                                </div>
                                <small class="text-muted mt-1">{{ $tiLeHoanThanh }}% hoàn thành</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Danh sách bài giảng -->
            <div class="card shadow-md hover-lift">
                <div class="card-header bg-light border-bottom-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0 fw-bold">
                                <i class="fas fa-play-circle me-2 text-primary"></i>Nội dung khóa học
                            </h5>
                            <p class="text-muted small mb-0 mt-1">
                                Tổng cộng {{ $baiGiangs->count() }} bài giảng
                            </p>
                        </div>
                        <div>
                            <span class="badge bg-primary px-3 py-2 fs-6">
                                {{ $baiGiangs->count() }} bài
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-0">
                    @if($baiGiangs->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($baiGiangs as $index => $baiGiang)
                            @php
                                // Xác định class badge dựa trên loại bài giảng
                                $badgeClass = 'bg-primary';
                                $icon = 'fas fa-file-alt';
                                $loaiText = 'Tài liệu';
                                
                                // Kiểm tra loại bài giảng và gán class tương ứng
                                if (isset($baiGiang->loai_bai_giang)) {
                                    switch(strtolower($baiGiang->loai_bai_giang)) {
                                        case 'video':
                                            $badgeClass = 'bg-danger';
                                            $icon = 'fas fa-video';
                                            $loaiText = 'Video';
                                            break;
                                        case 'pdf':
                                            $badgeClass = 'bg-warning text-dark';
                                            $icon = 'fas fa-file-pdf';
                                            $loaiText = 'PDF';
                                            break;
                                        case 'ppt':
                                        case 'powerpoint':
                                            $badgeClass = 'bg-info';
                                            $icon = 'fas fa-file-powerpoint';
                                            $loaiText = 'PowerPoint';
                                            break;
                                        case 'doc':
                                        case 'word':
                                            $badgeClass = 'bg-primary';
                                            $icon = 'fas fa-file-word';
                                            $loaiText = 'Word';
                                            break;
                                        default:
                                            $badgeClass = 'bg-secondary';
                                            $icon = 'fas fa-file-alt';
                                            $loaiText = 'Tài liệu';
                                    }
                                }
                                
                                // Kiểm tra xem sinh viên đã xem bài này chưa
                                $daXem = isset($baiGiang->da_xem) ? $baiGiang->da_xem : false;
                            @endphp
                            
                            <a href="{{ route('sinhvien.lophoc.baigiang.chitiet', ['ma_lop' => $lopHoc->ma_lop, 'id' => $baiGiang->id]) }}" 
                               class="list-group-item list-group-item-action border-bottom-0 py-3 px-4 hover-lift {{ $daXem ? 'bg-success-light' : '' }}"
                               style="transition: var(--transition-smooth);">
                                <div class="row align-items-center g-3">
                                    <div class="col-auto">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                             style="width: 50px; height: 50px; 
                                                    background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
                                                    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);">
                                            <span class="text-white fw-bold fs-5">{{ $index + 1 }}</span>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="d-flex align-items-start">
                                            <div class="flex-grow-1">
                                                <div class="d-flex align-items-center mb-2">
                                                    <i class="{{ $icon }} text-primary me-2 fs-5"></i>
                                                    <h6 class="mb-0 fw-bold">{{ $baiGiang->tieu_de }}</h6>
                                                    @if($daXem)
                                                        <span class="badge bg-success ms-2">
                                                            <i class="fas fa-check me-1"></i>Đã xem
                                                        </span>
                                                    @endif
                                                </div>
                                                @if($baiGiang->mo_ta)
                                                <p class="text-muted small mb-2">{{ $baiGiang->mo_ta }}</p>
                                                @endif
                                                <div class="d-flex flex-wrap gap-2">
                                                    <span class="badge {{ $badgeClass }} px-3 py-1">
                                                        {{ $loaiText }}
                                                    </span>
                                                    <span class="badge bg-light text-dark border px-3 py-1">
                                                        <i class="fas fa-clock me-1"></i>
                                                        Bài {{ $baiGiang->thu_tu ?? ($index + 1) }}
                                                    </span>
                                                    @if($baiGiang->thoi_luong)
                                                        <span class="badge bg-light text-dark border px-3 py-1">
                                                            <i class="fas fa-hourglass-half me-1"></i>
                                                            {{ $baiGiang->thoi_luong }} phút
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <span class="btn btn-outline-primary px-4 py-2">
                                            <i class="fas fa-eye me-2"></i>Xem bài
                                        </span>
                                    </div>
                                </div>
                            </a>
                            
                            <!-- Divider -->
                            @if(!$loop->last)
                            <div class="px-4">
                                <hr class="my-0" style="border-color: var(--border-light); opacity: 0.5;">
                            </div>
                            @endif
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5 my-4">
                            <div class="mb-4">
                                <i class="fas fa-file-alt fa-4x text-muted opacity-50 mb-3"></i>
                            </div>
                            <h5 class="text-muted fw-bold mb-2">Chưa có bài giảng nào</h5>
                            <p class="text-muted mb-4">Giảng viên sẽ cập nhật bài giảng sớm nhất.</p>
                            <a href="{{ route('sinhvien.khoahoc') }}" class="btn btn-outline-primary px-4">
                                <i class="fas fa-arrow-left me-2"></i>Quay lại khóa học
                            </a>
                        </div>
                    @endif
                </div>
                
                @if($baiGiangs->count() > 0)
                <div class="card-footer bg-light border-top-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small">
                                <i class="fas fa-info-circle me-1"></i>
                                Nhấp vào từng bài để xem chi tiết
                            </span>
                        </div>
                        <div class="text-end">
                            <span class="text-muted small">
                                Hiển thị {{ $baiGiangs->count() }} bài giảng
                            </span>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
/* Tận dụng các biến CSS có sẵn */
.bg-success-light {
    background: linear-gradient(to right, rgba(16, 185, 129, 0.05), transparent) !important;
    border-left: 4px solid var(--secondary-color) !important;
}

.bg-warning.text-dark {
    background-color: #f59e0b !important;
    color: #1e293b !important;
}

/* Sử dụng các biến CSS đã định nghĩa */
:root {
    --primary-color: #3b82f6;
    --primary-hover: #2563eb;
    --secondary-color: #10b981;
    --accent-color: #8b5cf6;
}

.fw-bold { font-weight: 700; }
</style>

@endsection