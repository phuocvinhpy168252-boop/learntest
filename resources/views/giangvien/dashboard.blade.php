@extends('layouts.giangvien')

@section('title', 'Dashboard Giảng Viên')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <!-- Thống kê nhanh -->
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Tổng Lớp Học
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $tongLopHoc ?? 0 }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-users fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Lớp Đang Mở
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $lopDangMo ?? 0 }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-door-open fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        Tổng Sinh Viên
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $tongSinhVien ?? 0 }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-user-graduate fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Bài Giảng
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $tongBaiGiang ?? 0 }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-book fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Các chức năng chính -->
            <div class="row">
                <!-- Quản lý Bài giảng -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="icon-circle bg-primary text-white mb-3">
                                <i class="fas fa-book-open fa-2x"></i>
                            </div>
                            <h5 class="card-title">Quản lý Bài giảng</h5>
                            <p class="card-text text-muted small">Tạo và quản lý bài giảng, tài liệu học tập</p>
                            <div class="dropdown">
                                <button class="btn btn-outline-primary dropdown-toggle" type="button" 
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                    Lựa chọn
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('giangvien.baigiang') }}">
                                        <i class="fas fa-list me-2"></i>Danh sách bài giảng
                                    </a></li>
                                    <li><a class="dropdown-item" href="#">
                                        <i class="fas fa-plus me-2"></i>Tạo bài giảng mới
                                    </a></li>
                                    <li><a class="dropdown-item" href="#">
                                        <i class="fas fa-upload me-2"></i>Upload tài liệu
                                    </a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quản lý Bài kiểm tra -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="icon-circle bg-success text-white mb-3">
                                <i class="fas fa-clipboard-check fa-2x"></i>
                            </div>
                            <h5 class="card-title">Quản lý Bài kiểm tra</h5>
                            <p class="card-text text-muted small">Tạo và quản lý bài kiểm tra, đề thi</p>
                            <div class="dropdown">
                                <button class="btn btn-outline-success dropdown-toggle" type="button" 
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                    Lựa chọn
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('giangvien.baikiemtra') }}">
                                        <i class="fas fa-list me-2"></i>Danh sách bài kiểm tra
                                    </a></li>
                                    <li><a class="dropdown-item" href="#">
                                        <i class="fas fa-plus me-2"></i>Tạo bài kiểm tra mới
                                    </a></li>
                                    <li><a class="dropdown-item" href="#">
                                        <i class="fas fa-edit me-2"></i>Chấm điểm
                                    </a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ngân hàng câu hỏi -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="icon-circle bg-info text-white mb-3">
                                <i class="fas fa-database fa-2x"></i>
                            </div>
                            <h5 class="card-title">Ngân hàng câu hỏi</h5>
                            <p class="card-text text-muted small">Quản lý câu hỏi, tạo ngân hàng đề thi</p>
                            <div class="dropdown">
                                <button class="btn btn-outline-info dropdown-toggle" type="button" 
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                    Lựa chọn
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('giangvien.nganhang') }}">
                                        <i class="fas fa-list me-2"></i>Danh sách câu hỏi
                                    </a></li>
                                    <li><a class="dropdown-item" href="#">
                                        <i class="fas fa-plus me-2"></i>Thêm câu hỏi mới
                                    </a></li>
                                    <li><a class="dropdown-item" href="#">
                                        <i class="fas fa-tags me-2"></i>Phân loại câu hỏi
                                    </a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kết quả làm bài -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="icon-circle bg-warning text-white mb-3">
                                <i class="fas fa-chart-bar fa-2x"></i>
                            </div>
                            <h5 class="card-title">Kết quả làm bài</h5>
                            <p class="card-text text-muted small">Theo dõi và phân tích kết quả học tập</p>
                            <div class="dropdown">
                                <button class="btn btn-outline-warning dropdown-toggle" type="button" 
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                    Lựa chọn
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('giangvien.ketqua') }}">
                                        <i class="fas fa-list me-2"></i>Xem kết quả
                                    </a></li>
                                    <li><a class="dropdown-item" href="#">
                                        <i class="fas fa-chart-line me-2"></i>Thống kê
                                    </a></li>
                                    <li><a class="dropdown-item" href="#">
                                        <i class="fas fa-download me-2"></i>Xuất báo cáo
                                    </a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quản lý Sinh viên -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="icon-circle bg-danger text-white mb-3">
                                <i class="fas fa-user-graduate fa-2x"></i>
                            </div>
                            <h5 class="card-title">Quản lý Sinh viên</h5>
                            <p class="card-text text-muted small">Quản lý thông tin và điểm số sinh viên</p>
                            <div class="dropdown">
                                <button class="btn btn-outline-danger dropdown-toggle" type="button" 
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                    Lựa chọn
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('giangvien.sinhvien') }}">
                                        <i class="fas fa-list me-2"></i>Danh sách sinh viên
                                    </a></li>
                                    <li><a class="dropdown-item" href="#">
                                        <i class="fas fa-user-plus me-2"></i>Thêm sinh viên
                                    </a></li>
                                    <li><a class="dropdown-item" href="#">
                                        <i class="fas fa-table me-2"></i>Bảng điểm
                                    </a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quản lý Lớp học -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="icon-circle bg-secondary text-white mb-3">
                                <i class="fas fa-users fa-2x"></i>
                            </div>
                            <h5 class="card-title">Quản lý Lớp học</h5>
                            <p class="card-text text-muted small">Tạo và quản lý lớp học của bạn</p>
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary dropdown-toggle" type="button" 
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                    Lựa chọn
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('giangvien.lophoc.index') }}">
                                        <i class="fas fa-list me-2"></i>Danh sách lớp học
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ route('giangvien.lophoc.create') }}">
                                        <i class="fas fa-plus me-2"></i>Tạo lớp học mới
                                    </a></li>
                                    <li><a class="dropdown-item" href="#">
                                        <i class="fas fa-calendar-alt me-2"></i>Lịch giảng dạy
                                    </a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Điểm danh -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="icon-circle bg-dark text-white mb-3">
                                <i class="fas fa-clipboard-list fa-2x"></i>
                            </div>
                            <h5 class="card-title">Điểm danh</h5>
                            <p class="card-text text-muted small">Quản lý điểm danh sinh viên</p>
                            <div class="dropdown">
                                <button class="btn btn-outline-dark dropdown-toggle" type="button" 
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                    Lựa chọn
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">
                                        <i class="fas fa-qrcode me-2"></i>Điểm danh QR
                                    </a></li>
                                    <li><a class="dropdown-item" href="#">
                                        <i class="fas fa-list me-2"></i>Lịch sử điểm danh
                                    </a></li>
                                    <li><a class="dropdown-item" href="#">
                                        <i class="fas fa-chart-pie me-2"></i>Thống kê điểm danh
                                    </a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cài đặt -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="icon-circle bg-purple text-white mb-3">
                                <i class="fas fa-cogs fa-2x"></i>
                            </div>
                            <h5 class="card-title">Cài đặt</h5>
                            <p class="card-text text-muted small">Cài đặt tài khoản và hệ thống</p>
                            <div class="dropdown">
                                <button class="btn btn-outline-purple dropdown-toggle" type="button" 
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                    Lựa chọn
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">
                                        <i class="fas fa-user me-2"></i>Hồ sơ cá nhân
                                    </a></li>
                                    <li><a class="dropdown-item" href="#">
                                        <i class="fas fa-lock me-2"></i>Đổi mật khẩu
                                    </a></li>
                                    <li><a class="dropdown-item" href="#">
                                        <i class="fas fa-bell me-2"></i>Thông báo
                                    </a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lớp học gần đây -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-clock me-2"></i>Lớp học gần đây
                            </h5>
                            <a href="{{ route('giangvien.lophoc.index') }}" class="btn btn-sm btn-primary">
                                Xem tất cả
                            </a>
                        </div>
                        <div class="card-body">
                            @if(isset($lopHocGanDay) && $lopHocGanDay->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover">
                                        <thead>
                                            <tr>
                                                <th>Mã Lớp</th>
                                                <th>Tên Lớp</th>
                                                <th>Môn học</th>
                                                <th>Số SV</th>
                                                <th>Trạng thái</th>
                                                <th>Ngày tạo</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($lopHocGanDay as $lop)
                                            <tr>
                                                <td><strong>{{ $lop->ma_lop }}</strong></td>
                                                <td>{{ $lop->ten_lop }}</td>
                                                <td>{{ $lop->monHoc->ten_mon_hoc ?? 'N/A' }}</td>
                                                <td>{{ $lop->so_luong_sv_hien_tai }}/{{ $lop->so_luong_sv }}</td>
                                                <td>
                                                    @if($lop->trang_thai == 'dang_mo')
                                                        <span class="badge bg-success">Đang mở</span>
                                                    @elseif($lop->trang_thai == 'dang_hoc')
                                                        <span class="badge bg-primary">Đang học</span>
                                                    @else
                                                        <span class="badge bg-secondary">Kết thúc</span>
                                                    @endif
                                                </td>
                                                <td>{{ $lop->created_at->format('d/m/Y') }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center text-muted py-4">
                                    <i class="fas fa-inbox fa-2x mb-3"></i>
                                    <p>Chưa có lớp học nào. <a href="{{ route('giangvien.lophoc.create') }}">Tạo lớp học đầu tiên</a></p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.icon-circle {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}

.bg-purple {
    background-color: #6f42c1 !important;
}

.btn-outline-purple {
    color: #6f42c1;
    border-color: #6f42c1;
}

.btn-outline-purple:hover {
    background-color: #6f42c1;
    border-color: #6f42c1;
    color: white;
}

.card {
    transition: transform 0.2s;
}

.card:hover {
    transform: translateY(-5px);
}

.border-left-primary {
    border-left: 4px solid #4e73df;
}

.border-left-success {
    border-left: 4px solid #1cc88a;
}

.border-left-info {
    border-left: 4px solid #36b9cc;
}

.border-left-warning {
    border-left: 4px solid #f6c23e;
}
</style>
@endpush