@extends('layouts.admin')

@section('title', 'Dashboard - Hệ thống Giáo dục')
@section('page-title', 'Tổng quan')

@section('content')
<!-- Stats Grid -->
<div class="stats-grid">
    <div class="admin-card fade-in">
        <div class="card-header">
            <h3 class="card-title">Tổng học viên</h3>
            <div class="card-icon">
                <i class="fas fa-users"></i>
            </div>
        </div>
        <div class="card-value counter" data-target="15240">0</div>
        <div class="card-change change-positive">
            <i class="fas fa-arrow-up"></i>
            <span>12.5% so với tháng trước</span>
        </div>
    </div>

    <div class="admin-card fade-in" style="animation-delay: 0.1s">
        <div class="card-header">
            <h3 class="card-title">Khóa học</h3>
            <div class="card-icon" style="background: var(--gradient-secondary)">
                <i class="fas fa-book"></i>
            </div>
        </div>
        <div class="card-value counter" data-target="86">0</div>
        <div class="card-change change-positive">
            <i class="fas fa-arrow-up"></i>
            <span>3 khóa mới</span>
        </div>
    </div>

    <div class="admin-card fade-in" style="animation-delay: 0.2s">
        <div class="card-header">
            <h3 class="card-title">Doanh thu</h3>
            <div class="card-icon" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%)">
                <i class="fas fa-dollar-sign"></i>
            </div>
        </div>
        <div class="card-value">₫<span class="counter" data-target="245">0</span>M</div>
        <div class="card-change change-positive">
            <i class="fas fa-arrow-up"></i>
            <span>8.2% tăng trưởng</span>
        </div>
    </div>

    <div class="admin-card fade-in" style="animation-delay: 0.3s">
        <div class="card-header">
            <h3 class="card-title">Tỷ lệ hoàn thành</h3>
            <div class="card-icon" style="background: linear-gradient(135deg, #ec4899 0%, #db2777 100%)">
                <i class="fas fa-trophy"></i>
            </div>
        </div>
        <div class="card-value counter" data-target="94">0</div>
        <div class="card-change change-positive">
            <i class="fas fa-arrow-up"></i>
            <span>5% cải thiện</span>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="row">
    <div class="col-lg-8">
        <div class="chart-container slide-in-left">
            <div class="chart-header">
                <h3 class="chart-title">Thống kê học viên</h3>
                <div class="chart-actions">
                    <button class="btn-admin btn-admin-outline btn-sm">
                        <i class="fas fa-download"></i>
                        Xuất báo cáo
                    </button>
                </div>
            </div>
            <div class="chart-placeholder">
                <!-- Chart sẽ được tải bằng JavaScript -->
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="chart-container slide-in-right">
            <div class="chart-header">
                <h3 class="chart-title">Phân loại khóa học</h3>
            </div>
            <div class="chart-placeholder">
                <!-- Chart sẽ được tải bằng JavaScript -->
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="row mt-4">
    <div class="col-12">
        <div class="admin-card">
            <div class="table-header">
                <h3 class="table-title">Hoạt động gần đây</h3>
                <button class="btn-admin btn-admin-primary btn-sm">
                    <i class="fas fa-plus"></i>
                    Thêm mới
                </button>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Học viên</th>
                            <th>Khóa học</th>
                            <th>Thời gian</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="https://ui-avatars.com/api/?name=Nguyen+Van+A&background=6366f1&color=fff" 
                                         alt="Avatar" class="rounded-circle me-2" width="32">
                                    <span>Nguyễn Văn A</span>
                                </div>
                            </td>
                            <td>Lập trình Web Fullstack</td>
                            <td>2 giờ trước</td>
                            <td><span class="status-badge status-active">Đang học</span></td>
                            <td>
                                <button class="btn-admin btn-admin-outline btn-sm">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="https://ui-avatars.com/api/?name=Tran+Thi+B&background=f59e0b&color=fff" 
                                         alt="Avatar" class="rounded-circle me-2" width="32">
                                    <span>Trần Thị B</span>
                                </div>
                            </td>
                            <td>Thiết kế đồ họa</td>
                            <td>5 giờ trước</td>
                            <td><span class="status-badge status-pending">Chờ duyệt</span></td>
                            <td>
                                <button class="btn-admin btn-admin-outline btn-sm">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="https://ui-avatars.com/api/?name=Le+Van+C&background=10b981&color=fff" 
                                         alt="Avatar" class="rounded-circle me-2" width="32">
                                    <span>Lê Văn C</span>
                                </div>
                            </td>
                            <td>Khoa học dữ liệu</td>
                            <td>1 ngày trước</td>
                            <td><span class="status-badge status-active">Đang học</span></td>
                            <td>
                                <button class="btn-admin btn-admin-outline btn-sm">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection