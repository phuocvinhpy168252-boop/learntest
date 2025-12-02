@extends('layouts.giangvien')

@section('title', 'Thống kê bài giảng')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-chart-bar me-2"></i>Thống kê Bài giảng
        </h1>
        <div>
            <a href="{{ route('giangvien.baigiangcuatoi.index') }}" class="btn btn-secondary me-2">
                <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
            </a>
            <a href="{{ route('giangvien.lophoc.index') }}" class="btn btn-info">
                <i class="fas fa-users me-1"></i> Quản lý Lớp học
            </a>
        </div>
    </div>

    <!-- Navigation -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('giangvien.baigiangcuatoi.index') }}">Bài giảng của tôi</a>
            </li>
            <li class="breadcrumb-item active">Thống kê</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Tổng quan -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Tổng số bài giảng
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $tongBaiGiang }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book-open fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Video -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Bài giảng Video
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $thongKeTheoLoai->where('loai_bai_giang', 'video')->first()->so_luong ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-video fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- PDF -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Tài liệu PDF
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $thongKeTheoLoai->where('loai_bai_giang', 'pdf')->first()->so_luong ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-pdf fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Presentation -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Bài thuyết trình
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $thongKeTheoLoai->where('loai_bai_giang', 'presentation')->first()->so_luong ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-powerpoint fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Thống kê theo loại -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Phân bố theo loại bài giảng</h6>
                </div>
                <div class="card-body">
                    @if($thongKeTheoLoai->count() > 0)
                        <canvas id="loaiBaiGiangChart" width="400" height="200"></canvas>
                    @else
                        <p class="text-muted text-center py-4">Chưa có dữ liệu thống kê</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Bài giảng mới nhất -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0"><i class="fas fa-clock me-2"></i>Bài giảng mới nhất</h6>
                </div>
                <div class="card-body">
                    @if($baiGiangMoiNhat->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($baiGiangMoiNhat as $baiGiang)
                                <div class="list-group-item">
                                    <div class="d-flex align-items-center">
                                        <i class="{{ $baiGiang->getIcon() }} text-{{ $baiGiang->getBadgeColor() }} me-3"></i>
                                        <div class="flex-grow-1">
                                            <div class="fw-bold small">{{ Str::limit($baiGiang->tieu_de, 40) }}</div>
                                            <small class="text-muted">
                                                {{ $baiGiang->lopHoc->ten_lop ?? 'N/A' }} • 
                                                {{ $baiGiang->created_at->diffForHumans() }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center py-4">Chưa có bài giảng nào</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Thống kê theo lớp -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Thống kê theo lớp học</h6>
                </div>
                <div class="card-body">
                    @if($thongKeTheoLop->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Lớp học</th>
                                        <th>Môn học</th>
                                        <th>Số bài giảng</th>
                                        <th>Tỷ lệ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($thongKeTheoLop as $thongKe)
                                        <tr>
                                            <td>
                                                <a href="{{ route('giangvien.lophoc.show', $thongKe->ma_lop) }}">
                                                    {{ $thongKe->lopHoc->ten_lop ?? 'N/A' }}
                                                </a>
                                            </td>
                                            <td>{{ $thongKe->lopHoc->monHoc->ten_mon_hoc ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge bg-primary">{{ $thongKe->so_luong }}</span>
                                            </td>
                                            <td>
                                                @php
                                                    $tyLe = $tongBaiGiang > 0 ? ($thongKe->so_luong / $tongBaiGiang) * 100 : 0;
                                                @endphp
                                                <div class="progress" style="height: 20px;">
                                                    <div class="progress-bar" role="progressbar" 
                                                         style="width: {{ $tyLe }}%;" 
                                                         aria-valuenow="{{ $tyLe }}" 
                                                         aria-valuemin="0" 
                                                         aria-valuemax="100">
                                                        {{ number_format($tyLe, 1) }}%
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center py-4">Chưa có dữ liệu thống kê theo lớp</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if($thongKeTheoLoai->count() > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('loaiBaiGiangChart').getContext('2d');
        const data = {
            labels: {!! json_encode($thongKeTheoLoai->pluck('loai_bai_giang')->map(function($loai) {
                $texts = [
                    'video' => 'Video',
                    'pdf' => 'PDF', 
                    'document' => 'Tài liệu',
                    'presentation' => 'Thuyết trình',
                    'other' => 'Khác'
                ];
                return $texts[$loai] ?? $loai;
            })) !!},
            datasets: [{
                data: {!! json_encode($thongKeTheoLoai->pluck('so_luong')) !!},
                backgroundColor: [
                    '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'
                ]
            }]
        };
        
        new Chart(ctx, {
            type: 'pie',
            data: data,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    });
</script>
@endif
@endpush