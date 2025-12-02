@extends('layouts.giangvien')

@section('title', 'Chi tiết Lớp học - ' . $lopHoc->ten_lop)

@section('content')
<div class="container-fluid py-4">
    <!-- Navigation -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('giangvien.lophoc.index') }}">Quản lý Lớp học</a>
            </li>
            <li class="breadcrumb-item active">Chi tiết Lớp học</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8">
            <!-- Thông tin chung -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>Thông tin Lớp học
                        </h5>
                        <span class="badge bg-light text-dark">{{ $lopHoc->ma_lop }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td width="40%"><strong>Tên lớp:</strong></td>
                                    <td>{{ $lopHoc->ten_lop }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Môn học:</strong></td>
                                    <td>{{ $lopHoc->monHoc->ten_mon_hoc ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Phòng học:</strong></td>
                                    <td>{{ $lopHoc->phong_hoc }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Giảng viên:</strong></td>
                                    <td>{{ $lopHoc->giangVien->ten ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td width="40%"><strong>Sĩ số:</strong></td>
                                    <td>
                                        <span class="badge bg-{{ $lopHoc->so_luong_sv_hien_tai < $lopHoc->so_luong_sv ? 'success' : 'danger' }}">
                                            {{ $lopHoc->so_luong_sv_hien_tai }} / {{ $lopHoc->so_luong_sv }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Thời gian:</strong></td>
                                    <td>{{ $lopHoc->thoi_gian_hoc }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Trạng thái:</strong></td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'dang_mo' => 'success',
                                                'dang_hoc' => 'primary', 
                                                'da_ket_thuc' => 'secondary'
                                            ];
                                            $statusTexts = [
                                                'dang_mo' => 'Đang mở',
                                                'dang_hoc' => 'Đang học', 
                                                'da_ket_thuc' => 'Đã kết thúc'
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $statusColors[$lopHoc->trang_thai] ?? 'secondary' }}">
                                            {{ $statusTexts[$lopHoc->trang_thai] ?? $lopHoc->trang_thai }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Ngày tạo:</strong></td>
                                    <td>{{ $lopHoc->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($lopHoc->mo_ta)
                    <div class="mt-3">
                        <h6>Mô tả lớp học:</h6>
                        <div class="border rounded p-3 bg-light">
                            {{ $lopHoc->mo_ta }}
                        </div>
                    </div>
                    @endif
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('giangvien.lophoc.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Quay lại
                        </a>
                        <div>
                            <a href="{{ route('giangvien.lophoc.edit', $lopHoc->ma_lop) }}" 
                               class="btn btn-warning me-2">
                                <i class="fas fa-edit me-1"></i> Chỉnh sửa
                            </a>
                            <a href="{{ route('giangvien.lophoc.sinhvien.index', $lopHoc->ma_lop) }}" 
                               class="btn btn-info me-2">
                                <i class="fas fa-users me-1"></i> Quản lý Sinh viên
                            </a>
                            <a href="{{ route('giangvien.lophoc.baigiang.index', $lopHoc->ma_lop) }}" 
                               class="btn btn-success">
                                <i class="fas fa-book-open me-1"></i> Bài giảng
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thống kê nhanh -->
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Tổng số Bài giảng
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ $tongBaiGiang }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-book-open fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Sinh viên đang học
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ $lopHoc->so_luong_sv_hien_tai }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-user-graduate fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        Chỗ trống
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ $lopHoc->so_luong_sv - $lopHoc->so_luong_sv_hien_tai }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-chalkboard-teacher fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-bolt me-2"></i>Thao tác nhanh</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('giangvien.lophoc.baigiang.create', $lopHoc->ma_lop) }}" 
                           class="btn btn-success btn-sm">
                            <i class="fas fa-plus me-1"></i> Thêm Bài giảng
                        </a>
                        <a href="{{ route('giangvien.lophoc.sinhvien.create', $lopHoc->ma_lop) }}" 
                           class="btn btn-primary btn-sm {{ !$lopHoc->conChoTrong() ? 'disabled' : '' }}">
                            <i class="fas fa-user-plus me-1"></i> Thêm Sinh viên
                        </a>
                        <a href="{{ route('giangvien.baigiangcuatoi.index') }}?ma_lop={{ $lopHoc->ma_lop }}" 
                           class="btn btn-outline-info btn-sm">
                            <i class="fas fa-book me-1"></i> Xem trong Bài giảng của tôi
                        </a>
                    </div>
                </div>
            </div>

            <!-- Bài giảng mới nhất -->
            <div class="card mb-4">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0"><i class="fas fa-clock me-2"></i>Bài giảng mới nhất</h6>
                </div>
                <div class="card-body">
                    @if($baiGiangMoiNhat->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($baiGiangMoiNhat as $baiGiang)
                                <a href="{{ route('giangvien.lophoc.baigiang.index', $lopHoc->ma_lop) }}" 
                                   class="list-group-item list-group-item-action">
                                    <div class="d-flex align-items-center">
                                        <i class="{{ $baiGiang->getIcon() }} text-{{ $baiGiang->getBadgeColor() }} me-2"></i>
                                        <div class="flex-grow-1">
                                            <div class="fw-bold small">{{ Str::limit($baiGiang->tieu_de, 25) }}</div>
                                            <small class="text-muted">{{ $baiGiang->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                        <div class="text-center mt-2">
                            <a href="{{ route('giangvien.lophoc.baigiang.index', $lopHoc->ma_lop) }}" 
                               class="btn btn-sm btn-outline-warning">
                                Xem tất cả
                            </a>
                        </div>
                    @else
                        <p class="text-muted small text-center">Chưa có bài giảng nào</p>
                        <div class="text-center">
                            <a href="{{ route('giangvien.lophoc.baigiang.create', $lopHoc->ma_lop) }}" 
                               class="btn btn-sm btn-warning">
                                <i class="fas fa-plus me-1"></i> Thêm bài giảng
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Thông tin giảng viên -->
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0"><i class="fas fa-chalkboard-teacher me-2"></i>Giảng viên</h6>
                </div>
                <div class="card-body">
                    @if($lopHoc->giangVien)
                        <div class="text-center">
                            <div class="mb-3">
                                <i class="fas fa-user-circle fa-3x text-secondary"></i>
                            </div>
                            <h6 class="fw-bold">{{ $lopHoc->giangVien->ten }}</h6>
                            <p class="mb-1 small text-muted">Mã GV: {{ $lopHoc->giangVien->ma_giangvien }}</p>
                            <p class="mb-1 small text-muted">Email: {{ $lopHoc->giangVien->email }}</p>
                            <p class="mb-0 small text-muted">SĐT: {{ $lopHoc->giangVien->so_dien_thoai }}</p>
                        </div>
                    @else
                        <p class="text-muted text-center">Không có thông tin giảng viên</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Có thể thêm các script xử lý nếu cần
document.addEventListener('DOMContentLoaded', function() {
    console.log('Chi tiết lớp học đã tải xong');
});
</script>
@endpush