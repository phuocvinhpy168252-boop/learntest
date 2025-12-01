@extends('layouts.app')

@section('title', 'Khóa học của tôi')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-book me-2"></i>Khóa học của tôi
                    </h5>
                    <span class="badge bg-light text-primary fs-6">{{ $lopHocs->count() }} khóa học</span>
                </div>
                <div class="card-body">
                    @if($lopHocs->count() > 0)
                        <div class="row">
                            @foreach($lopHocs as $lop)
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card h-100 shadow-hover">
                                    <div class="card-header bg-light">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <span class="badge bg-success">Đang học</span>
                                            <small class="text-muted">Mã: {{ $lop->ma_lop }}</small>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <h6 class="card-title text-primary">{{ $lop->ten_lop }}</h6>
                                        <p class="card-text text-muted small mb-2">
                                            <i class="fas fa-book me-1"></i>
                                            {{ $lop->monHoc->ten_mon_hoc ?? 'Chưa có môn học' }}
                                        </p>
                                        <p class="card-text small mb-2">
                                            <i class="fas fa-user-tie me-1"></i>
                                            GV: {{ $lop->giangVien->ten ?? 'Chưa có GV' }}
                                        </p>
                                        <p class="card-text small mb-2">
                                            <i class="fas fa-users me-1"></i>
                                            Sĩ số: {{ $lop->so_luong_sv_hien_tai }}/{{ $lop->so_luong_sv }}
                                        </p>
                                        <p class="card-text small mb-3">
                                            <i class="fas fa-clock me-1"></i>
                                            {{ $lop->thoi_gian_hoc }}
                                        </p>
                                    </div>
                                    <div class="card-footer bg-transparent">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-grid" style="width:60%">
                                                <a href="{{ route('sinhvien.lophoc.baigiang', $lop->ma_lop) }}" 
                                                   class="btn btn-primary btn-sm">
                                                    <i class="fas fa-play-circle me-1"></i>Vào học
                                                </a>
                                            </div>
                                            <div class="text-end" style="width:40%">
                                                <?php $soBaiKiem = \App\Models\BaiKiemTra::where('ma_lop', $lop->ma_lop)->where('trang_thai', '!=', 'da_huy')->count(); ?>
                                                <a href="{{ route('sinhvien.lophoc.baikiemtra', $lop->ma_lop) }}" class="btn btn-outline-secondary btn-sm">
                                                    <i class="fas fa-clipboard-list me-1"></i>Bài kiểm tra
                                                    <span class="badge bg-primary ms-2">{{ $soBaiKiem }}</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Chưa có khóa học nào</h5>
                            <p class="text-muted mb-4">Bạn chưa đăng ký khóa học nào. Hãy liên hệ với giảng viên để được thêm vào lớp học.</p>
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('sinhvien.dashboard') }}" class="btn btn-primary">
                                    <i class="fas fa-arrow-left me-1"></i>Quay lại trang chủ
                                </a>
                                <a href="#" class="btn btn-outline-secondary">
                                    <i class="fas fa-question-circle me-1"></i>Hướng dẫn đăng ký
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.shadow-hover:hover {
    transform: translateY(-2px);
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
}
</style>
@endsection