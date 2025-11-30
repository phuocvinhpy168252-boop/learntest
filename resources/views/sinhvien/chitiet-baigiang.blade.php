@extends('layouts.app')

@section('title', $baiGiang->tieu_de)

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- Sidebar Navigation -->
        <div class="col-lg-3 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-list-ol me-1"></i>Nội dung khóa học
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach($baiGiangs as $bg)
                        <a href="{{ route('sinhvien.lophoc.baigiang.chitiet', ['ma_lop' => $lopHoc->ma_lop, 'id' => $bg->id]) }}" 
                           class="list-group-item list-group-item-action {{ $bg->id == $baiGiang->id ? 'active' : '' }}">
                            <div class="d-flex align-items-center">
                                <i class="{{ $bg->getIcon() }} me-2 {{ $bg->id == $baiGiang->id ? 'text-white' : 'text-primary' }}"></i>
                                <div class="flex-grow-1">
                                    <small class="d-block fw-bold">{{ $bg->tieu_de }}</small>
                                    <small class="{{ $bg->id == $baiGiang->id ? 'text-light' : 'text-muted' }}">
                                        Bài {{ $bg->thu_tu }} • {{ $bg->getLoaiText() }}
                                    </small>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('sinhvien.khoahoc') }}">Khóa học của tôi</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('sinhvien.lophoc.baigiang', $lopHoc->ma_lop) }}">{{ $lopHoc->ten_lop }}</a>
                    </li>
                    <li class="breadcrumb-item active">Bài {{ $baiGiang->thu_tu }}</li>
                </ol>
            </nav>

            <!-- Bài giảng chi tiết -->
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1 text-primary">{{ $baiGiang->tieu_de }}</h4>
                            <div class="d-flex align-items-center flex-wrap gap-2">
                                <span class="badge bg-{{ $baiGiang->getBadgeColor() }} fs-6">
                                    <i class="{{ $baiGiang->getIcon() }} me-1"></i>
                                    {{ $baiGiang->getLoaiText() }}
                                </span>
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i>Bài {{ $baiGiang->thu_tu }}
                                </small>
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>
                                    {{ $baiGiang->created_at->format('d/m/Y') }}
                                </small>
                            </div>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-cog"></i>
                            </button>
                            <ul class="dropdown-menu">
                                @if($baiGiang->duong_dan_file)
                                <li><a class="dropdown-item" href="{{ asset('storage/' . $baiGiang->duong_dan_file) }}" download><i class="fas fa-download me-2"></i>Tải xuống</a></li>
                                @endif
                                <li><a class="dropdown-item" href="#" onclick="window.print()"><i class="fas fa-print me-2"></i>In bài</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Mô tả bài giảng -->
                    @if($baiGiang->mo_ta)
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle me-2"></i>Mô tả</h6>
                        <p class="mb-0">{{ $baiGiang->mo_ta }}</p>
                    </div>
                    @endif

                    <!-- Nội dung bài giảng -->
                    <div class="lesson-content">
                        @if($baiGiang->loai_bai_giang == 'video' && $baiGiang->url_video)
                            <!-- Video YouTube -->
                            @if(str_contains($baiGiang->url_video, 'youtube.com') || str_contains($baiGiang->url_video, 'youtu.be'))
                            <div class="video-container mb-4">
                                <div class="ratio ratio-16x9">
                                    <iframe src="https://www.youtube.com/embed/{{ $baiGiang->getYouTubeId() }}" 
                                            frameborder="0" 
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                            allowfullscreen>
                                    </iframe>
                                </div>
                            </div>
                            @else
                            <!-- Video thông thường -->
                            <div class="video-container mb-4">
                                <div class="ratio ratio-16x9">
                                    <video controls style="width: 100%; height: 100%;">
                                        <source src="{{ $baiGiang->url_video }}" type="video/mp4">
                                        Trình duyệt của bạn không hỗ trợ video.
                                    </video>
                                </div>
                            </div>
                            @endif
                            
                        @elseif($baiGiang->loai_bai_giang == 'pdf' && $baiGiang->duong_dan_file)
                        <div class="pdf-viewer-container">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="text-danger">
                                    <i class="fas fa-file-pdf me-2"></i>Tài liệu PDF
                                </h5>
                                <a href="{{ asset('storage/' . $baiGiang->duong_dan_file) }}" 
                                   download 
                                   class="btn btn-danger btn-sm">
                                    <i class="fas fa-download me-1"></i>Tải xuống
                                </a>
                            </div>
                            <div class="pdf-viewer">
                                <iframe src="{{ asset('storage/' . $baiGiang->duong_dan_file) }}#toolbar=0" 
                                        width="100%" 
                                        height="600px"
                                        frameborder="0">
                                    Trình duyệt của bạn không hỗ trợ xem PDF. 
                                    <a href="{{ asset('storage/' . $baiGiang->duong_dan_file) }}" download>
                                        Tải xuống tại đây
                                    </a>
                                </iframe>
                            </div>
                        </div>
                        
                        @elseif($baiGiang->loai_bai_giang == 'document' && $baiGiang->duong_dan_file)
                        <div class="document-viewer-container">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="text-primary">
                                    <i class="fas fa-file-word me-2"></i>Tài liệu
                                </h5>
                                <a href="{{ asset('storage/' . $baiGiang->duong_dan_file) }}" 
                                   download 
                                   class="btn btn-primary btn-sm">
                                    <i class="fas fa-download me-1"></i>Tải xuống
                                </a>
                            </div>
                            <div class="document-viewer">
                                @if(in_array(pathinfo($baiGiang->duong_dan_file, PATHINFO_EXTENSION), ['doc', 'docx']))
                                <!-- Xem trực tiếp Word document sử dụng Microsoft Office Online -->
                                <iframe src="https://view.officeapps.live.com/op/embed.aspx?src={{ urlencode(asset('storage/' . $baiGiang->duong_dan_file)) }}" 
                                        width="100%" 
                                        height="600px" 
                                        frameborder="0">
                                    Trình duyệt của bạn không hỗ trợ xem tài liệu. 
                                    <a href="{{ asset('storage/' . $baiGiang->duong_dan_file) }}" download>
                                        Tải xuống tại đây
                                    </a>
                                </iframe>
                                @else
                                <!-- Hiển thị link download cho các định dạng khác -->
                                <div class="text-center py-5">
                                    <i class="fas fa-file-alt fa-3x text-primary mb-3"></i>
                                    <h5 class="text-primary">Tài liệu học tập</h5>
                                    <p class="text-muted">Nhấn vào nút bên dưới để tải xuống tài liệu</p>
                                    <a href="{{ asset('storage/' . $baiGiang->duong_dan_file) }}" 
                                       download 
                                       class="btn btn-primary btn-lg">
                                        <i class="fas fa-download me-2"></i>Tải xuống tài liệu
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        @elseif($baiGiang->loai_bai_giang == 'presentation' && $baiGiang->duong_dan_file)
                        <div class="presentation-viewer-container">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="text-warning">
                                    <i class="fas fa-file-powerpoint me-2"></i>Bài thuyết trình
                                </h5>
                                <a href="{{ asset('storage/' . $baiGiang->duong_dan_file) }}" 
                                   download 
                                   class="btn btn-warning btn-sm">
                                    <i class="fas fa-download me-1"></i>Tải xuống
                                </a>
                            </div>
                            <div class="presentation-viewer">
                                @if(in_array(pathinfo($baiGiang->duong_dan_file, PATHINFO_EXTENSION), ['ppt', 'pptx']))
                                <!-- Xem trực tiếp PowerPoint sử dụng Microsoft Office Online -->
                                <iframe src="https://view.officeapps.live.com/op/embed.aspx?src={{ urlencode(asset('storage/' . $baiGiang->duong_dan_file)) }}" 
                                        width="100%" 
                                        height="600px" 
                                        frameborder="0">
                                    Trình duyệt của bạn không hỗ trợ xem bài thuyết trình. 
                                    <a href="{{ asset('storage/' . $baiGiang->duong_dan_file) }}" download>
                                        Tải xuống tại đây
                                    </a>
                                </iframe>
                                @else
                                <!-- Hiển thị link download cho các định dạng khác -->
                                <div class="text-center py-5">
                                    <i class="fas fa-file-powerpoint fa-3x text-warning mb-3"></i>
                                    <h5 class="text-warning">Bài thuyết trình</h5>
                                    <p class="text-muted">Nhấn vào nút bên dưới để tải xuống bài thuyết trình</p>
                                    <a href="{{ asset('storage/' . $baiGiang->duong_dan_file) }}" 
                                       download 
                                       class="btn btn-warning btn-lg">
                                        <i class="fas fa-download me-2"></i>Tải xuống bài thuyết trình
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        @else
                        <div class="text-center py-5">
                            <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Nội dung bài giảng</h5>
                            <p class="text-muted">Nội dung bài giảng sẽ được cập nhật sớm nhất.</p>
                            @if($baiGiang->duong_dan_file)
                            <a href="{{ asset('storage/' . $baiGiang->duong_dan_file) }}" 
                               download 
                               class="btn btn-outline-primary">
                                <i class="fas fa-download me-2"></i>Tải xuống tài liệu
                            </a>
                            @endif
                        </div>
                        @endif
                    </div>

                    <!-- Navigation -->
                    <div class="row mt-5">
                        <div class="col">
                            @if($baiGiangTruoc)
                            <a href="{{ route('sinhvien.lophoc.baigiang.chitiet', ['ma_lop' => $lopHoc->ma_lop, 'id' => $baiGiangTruoc->id]) }}" 
                               class="btn btn-outline-primary">
                                <i class="fas fa-arrow-left me-2"></i>Bài trước: {{ $baiGiangTruoc->tieu_de }}
                            </a>
                            @endif
                        </div>
                        <div class="col text-end">
                            @if($baiGiangTiepTheo)
                            <a href="{{ route('sinhvien.lophoc.baigiang.chitiet', ['ma_lop' => $lopHoc->ma_lop, 'id' => $baiGiangTiepTheo->id]) }}" 
                               class="btn btn-primary">
                                Bài tiếp theo: {{ $baiGiangTiepTheo->tieu_de }} <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                            @else
                            <button class="btn btn-success">
                                <i class="fas fa-check me-2"></i>Hoàn thành khóa học
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.video-container {
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.lesson-content {
    min-height: 400px;
}

.pdf-viewer, .document-viewer, .presentation-viewer {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.list-group-item.active {
    background: linear-gradient(45deg, #007bff, #0056b3);
    border-color: #007bff;
}

.shadow-hover {
    transition: all 0.3s ease;
}

.shadow-hover:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.15) !important;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .pdf-viewer iframe,
    .document-viewer iframe,
    .presentation-viewer iframe {
        height: 400px !important;
    }
}
</style>
@endsection