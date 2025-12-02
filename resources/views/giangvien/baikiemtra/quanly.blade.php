@extends('layouts.giangvien')

@section('title', 'Quản lý Bài kiểm tra')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-clipboard-check me-2"></i>Quản lý Bài kiểm tra
        </h1>
        <div>
            <div class="btn-group me-2">
                <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-plus me-1"></i> Tạo bài kiểm tra
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    @if($danhSachLopHoc->count() > 0)
                        @foreach($danhSachLopHoc as $lop)
                        <li>
                            <a class="dropdown-item" href="{{ route('giangvien.lophoc.baikiemtra.create', $lop->ma_lop) }}">
                                <i class="fas fa-plus me-2"></i>{{ $lop->ten_lop }}
                            </a>
                        </li>
                        @endforeach
                    @else
                        <li><span class="dropdown-item text-muted">Không có lớp học nào</span></li>
                    @endif
                </ul>
            </div>
            <a href="{{ route('giangvien.lophoc.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Quay lại
            </a>
        </div>
    </div>

    <!-- Thống kê nhanh -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Tổng cộng</h6>
                            <h3 class="mb-0">{{ $thongKe['tong'] }}</h3>
                        </div>
                        <i class="fas fa-list fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-dark-50 mb-1">Chờ công bố</h6>
                            <h3 class="mb-0">{{ $thongKe['cho_cong_bo'] }}</h3>
                        </div>
                        <i class="fas fa-clock fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Đang diễn ra</h6>
                            <h3 class="mb-0">{{ $thongKe['dang_dien_ra'] }}</h3>
                        </div>
                        <i class="fas fa-play-circle fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Đã kết thúc</h6>
                            <h3 class="mb-0">{{ $thongKe['da_ket_thuc'] }}</h3>
                        </div>
                        <i class="fas fa-check-circle fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-header">
            <h6 class="mb-0">Bộ lọc</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('giangvien.baikiemtra.quanly') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" 
                           placeholder="Tìm kiếm theo tiêu đề hoặc mã bài..."
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="trang_thai" class="form-select">
                        <option value="">-- Tất cả trạng thái --</option>
                        <option value="cho_cong_bo" {{ request('trang_thai') == 'cho_cong_bo' ? 'selected' : '' }}>Chờ công bố</option>
                        <option value="dang_dien_ra" {{ request('trang_thai') == 'dang_dien_ra' ? 'selected' : '' }}>Đang diễn ra</option>
                        <option value="da_ket_thuc" {{ request('trang_thai') == 'da_ket_thuc' ? 'selected' : '' }}>Đã kết thúc</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="ma_lop" class="form-select">
                        <option value="">-- Tất cả lớp học --</option>
                        @foreach($danhSachLopHoc as $lop)
                            <option value="{{ $lop->ma_lop }}" {{ request('ma_lop') == $lop->ma_lop ? 'selected' : '' }}>
                                {{ $lop->ten_lop }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-1"></i> Tìm
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Danh sách bài kiểm tra -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="card-title mb-0">
                    <i class="fas fa-list me-2"></i>Danh sách Bài kiểm tra
                </h5>
                <small class="text-muted">Tổng: {{ $totalBaiKiemTra }} bài kiểm tra</small>
            </div>
        </div>
        <div class="card-body">
            @if($baiKiemTras->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Mã</th>
                                <th>Tiêu đề</th>
                                <th>Lớp học</th>
                                <th>Loại</th>
                                <th>Câu hỏi</th>
                                <th>Trạng thái</th>
                                <th>Ngày tạo</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($baiKiemTras as $baiKT)
                            <tr>
                                <td>
                                    <span class="badge bg-secondary">{{ $baiKT->ma_bai_kiem_tra }}</span>
                                </td>
                                <td>
                                    <strong>{{ Str::limit($baiKT->tieu_de, 40) }}</strong>
                                </td>
                                <td>
                                    {{ $baiKT->lopHoc->ten_lop ?? 'N/A' }}
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $baiKT->getLoaiText() }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $baiKT->so_cau_hoi }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $baiKT->getBadgeColor() }}">
                                        {{ $baiKT->getTrangThaiText() }}
                                    </span>
                                </td>
                                <td>{{ $baiKT->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('giangvien.lophoc.baikiemtra.show', [$baiKT->ma_lop, $baiKT->id]) }}" 
                                           class="btn btn-info" title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($baiKT->coTheChinhSua())
                                        <a href="{{ route('giangvien.lophoc.baikiemtra.edit', [$baiKT->ma_lop, $baiKT->id]) }}" 
                                           class="btn btn-warning" title="Chỉnh sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @endif
                                        <a href="{{ route('giangvien.lophoc.baikiemtra.cauhoi.index', [$baiKT->ma_lop, $baiKT->id]) }}" 
                                           class="btn btn-success" title="Quản lý câu hỏi">
                                            <i class="fas fa-question-circle"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Phân trang -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Hiển thị {{ $baiKiemTras->firstItem() }} - {{ $baiKiemTras->lastItem() }} 
                        trong {{ $totalBaiKiemTra }} bài kiểm tra
                    </div>
                    {{ $baiKiemTras->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-clipboard-check fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Chưa có bài kiểm tra nào</h5>
                    <p class="text-muted">Hãy tạo bài kiểm tra mới trong các lớp học của bạn.</p>
                    <a href="{{ route('giangvien.lophoc.index') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Tạo bài kiểm tra
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
