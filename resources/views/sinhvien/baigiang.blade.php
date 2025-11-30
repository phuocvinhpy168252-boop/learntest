@extends('layouts.app')

@section('title', 'Bài giảng - ' . $lopHoc->ten_lop)

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('sinhvien.khoahoc') }}">Khóa học của tôi</a>
                            </li>
                            <li class="breadcrumb-item active">{{ $lopHoc->ten_lop }}</li>
                        </ol>
                    </nav>
                    
                    <div class="row mt-3">
                        <div class="col-md-8">
                            <h4 class="text-primary mb-2">{{ $lopHoc->ten_lop }}</h4>
                            <p class="text-muted mb-1">
                                <i class="fas fa-book me-1"></i>
                                {{ $lopHoc->monHoc->ten_mon_hoc ?? 'Chưa có môn học' }}
                            </p>
                            <p class="text-muted mb-1">
                                <i class="fas fa-user-tie me-1"></i>
                                Giảng viên: {{ $lopHoc->giangVien->ten ?? 'Chưa có GV' }}
                            </p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <div class="d-flex flex-column">
                                <span class="badge bg-success mb-2 fs-6">Đang học</span>
                                <small class="text-muted">Mã lớp: {{ $lopHoc->ma_lop }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Danh sách bài giảng -->
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-play-circle me-2"></i>Nội dung khóa học
                        <span class="badge bg-primary ms-2">{{ $baiGiangs->count() }} bài giảng</span>
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($baiGiangs->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($baiGiangs as $index => $baiGiang)
                            <div class="list-group-item">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                             style="width: 40px; height: 40px;">
                                            <span class="fw-bold">{{ $index + 1 }}</span>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="d-flex align-items-start">
                                            <i class="{{ $baiGiang->getIcon() }} text-primary mt-1 me-3 fs-5"></i>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1">{{ $baiGiang->tieu_de }}</h6>
                                                <p class="text-muted small mb-1">{{ $baiGiang->mo_ta }}</p>
                                                <div class="d-flex align-items-center">
                                                    <span class="badge bg-{{ $baiGiang->getBadgeColor() }} me-2">
                                                        {{ $baiGiang->getLoaiText() }}
                                                    </span>
                                                    <small class="text-muted">
                                                        <i class="fas fa-clock me-1"></i>
                                                        Bài {{ $baiGiang->thu_tu }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <a href="{{ route('sinhvien.lophoc.baigiang.chitiet', ['ma_lop' => $lopHoc->ma_lop, 'id' => $baiGiang->id]) }}" 
                                           class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-eye me-1"></i>Xem bài
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Chưa có bài giảng nào</h5>
                            <p class="text-muted">Giảng viên sẽ cập nhật bài giảng sớm nhất.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection