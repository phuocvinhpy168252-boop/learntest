@extends('layouts.app')

@section('title', 'Dashboard Sinh Viên')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <!-- Welcome Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-body bg-primary text-white rounded-3">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h3 class="mb-2">Chào mừng, {{ Auth::user()->hoten }}!</h3>
                            <p class="mb-0">Chúc bạn một ngày học tập hiệu quả</p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <i class="fas fa-graduation-cap fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm border-success">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title text-muted">Khóa học đang học</h6>
                                    <h3 class="text-success">{{ $soLopHoc ?? 0 }}</h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-book fa-2x text-success"></i>
                                </div>
                            </div>
                            <a href="{{ route('sinhvien.khoahoc') }}" class="btn btn-sm btn-outline-success mt-3">Xem chi tiết</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm border-info">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title text-muted">Bài tập chưa làm</h6>
                                    <h3 class="text-info">0</h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-tasks fa-2x text-info"></i>
                                </div>
                            </div>
                            <a href="#" class="btn btn-sm btn-outline-info mt-3">Xem bài tập</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm border-warning">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title text-muted">Điểm trung bình</h6>
                                    <h3 class="text-warning">-</h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-chart-line fa-2x text-warning"></i>
                                </div>
                            </div>
                            <a href="#" class="btn btn-sm btn-outline-warning mt-3">Xem kết quả</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Courses -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-clock me-2"></i>Khóa học của tôi
                            </h5>
                            <a href="{{ route('sinhvien.khoahoc') }}" class="btn btn-sm btn-primary">Xem tất cả</a>
                        </div>
                        <div class="card-body">
                            @if($soLopHoc > 0)
                                <div class="alert alert-success">
                                    <i class="fas fa-check-circle me-2"></i>
                                    Bạn đang tham gia <strong>{{ $soLopHoc }} khóa học</strong>. 
                                    <a href="{{ route('sinhvien.khoahoc') }}" class="alert-link">Xem chi tiết tại đây</a>
                                </div>
                                <p class="mb-0">Truy cập vào menu <strong>"Khóa học của tôi"</strong> trên thanh điều hướng để xem danh sách đầy đủ các khóa học bạn đã đăng ký.</p>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">Chưa có khóa học nào</h5>
                                    <p class="text-muted mb-3">Bạn chưa đăng ký khóa học nào. Hãy liên hệ với giảng viên để được thêm vào lớp học.</p>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('sinhvien.khoahoc') }}" class="btn btn-primary">
                                            <i class="fas fa-refresh me-1"></i>Kiểm tra lại
                                        </a>
                                        <a href="#" class="btn btn-outline-secondary">
                                            <i class="fas fa-question-circle me-1"></i>Hướng dẫn
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="fas fa-rocket me-2"></i>Thao tác nhanh
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <a href="{{ route('sinhvien.khoahoc') }}" class="btn btn-outline-primary w-100 h-100 py-3">
                                        <i class="fas fa-book fa-2x mb-2"></i><br>
                                        Khóa học của tôi
                                    </a>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <a href="#" class="btn btn-outline-info w-100 h-100 py-3">
                                        <i class="fas fa-tasks fa-2x mb-2"></i><br>
                                        Bài tập
                                    </a>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <a href="#" class="btn btn-outline-success w-100 h-100 py-3">
                                        <i class="fas fa-chart-line fa-2x mb-2"></i><br>
                                        Kết quả học tập
                                    </a>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <a href="#" class="btn btn-outline-warning w-100 h-100 py-3">
                                        <i class="fas fa-calendar fa-2x mb-2"></i><br>
                                        Lịch học
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection