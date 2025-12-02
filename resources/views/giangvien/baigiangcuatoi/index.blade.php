@extends('layouts.giangvien')

@section('title', 'Bài giảng của tôi')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-book-open me-2"></i>Bài giảng của tôi
        </h1>
        <div>
            <a href="{{ route('giangvien.baigiangcuatoi.thongke') }}" class="btn btn-info">
                <i class="fas fa-chart-bar me-1"></i> Thống kê
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('giangvien.baigiangcuatoi.index') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Tìm kiếm bài giảng..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="loai_bai_giang" class="form-select">
                            <option value="">Tất cả loại</option>
                            <option value="video" {{ request('loai_bai_giang') == 'video' ? 'selected' : '' }}>Video</option>
                            <option value="pdf" {{ request('loai_bai_giang') == 'pdf' ? 'selected' : '' }}>PDF</option>
                            <option value="document" {{ request('loai_bai_giang') == 'document' ? 'selected' : '' }}>Tài liệu</option>
                            <option value="presentation" {{ request('loai_bai_giang') == 'presentation' ? 'selected' : '' }}>Thuyết trình</option>
                            <option value="other" {{ request('loai_bai_giang') == 'other' ? 'selected' : '' }}>Khác</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="ma_lop" class="form-select">
                            <option value="">Tất cả lớp</option>
                            @foreach($danhSachLopHoc as $lop)
                                <option value="{{ $lop->ma_lop }}" {{ request('ma_lop') == $lop->ma_lop ? 'selected' : '' }}>
                                    {{ $lop->ten_lop }} - {{ $lop->monHoc->ten_mon_hoc ?? 'N/A' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-1"></i> Lọc
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                Tổng cộng: <strong>{{ $totalBaiGiang }} bài giảng</strong>
                @if(request('search') || request('loai_bai_giang') || request('ma_lop'))
                    (đã lọc)
                    <a href="{{ route('giangvien.baigiangcuatoi.index') }}" class="btn btn-sm btn-outline-info ms-2">
                        <i class="fas fa-times me-1"></i> Xóa bộ lọc
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Bài giảng List -->
    <div class="card">
        <div class="card-body">
            @if($baiGiangs->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Mã bài giảng</th>
                                <th>Tiêu đề</th>
                                <th>Lớp học</th>
                                <th>Môn học</th>
                                <th>Loại</th>
                                <th>Thứ tự</th>
                                <th>Ngày tạo</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($baiGiangs as $baiGiang)
                                <tr>
                                    <td>
                                        <span class="badge bg-secondary">{{ $baiGiang->ma_bai_giang }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="{{ $baiGiang->getIcon() }} text-{{ $baiGiang->getBadgeColor() }} me-2"></i>
                                            <div>
                                                <strong>{{ Str::limit($baiGiang->tieu_de, 50) }}</strong>
                                                @if($baiGiang->mo_ta)
                                                    <br>
                                                    <small class="text-muted">{{ Str::limit($baiGiang->mo_ta, 70) }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('giangvien.lophoc.show', $baiGiang->ma_lop) }}" 
                                           class="text-decoration-none">
                                            {{ $baiGiang->lopHoc->ten_lop ?? 'N/A' }}
                                        </a>
                                    </td>
                                    <td>
                                        {{ $baiGiang->lopHoc->monHoc->ten_mon_hoc ?? 'N/A' }}
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $baiGiang->getBadgeColor() }}">
                                            {{ $baiGiang->getLoaiText() }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">{{ $baiGiang->thu_tu }}</span>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $baiGiang->created_at->format('d/m/Y') }}
                                        </small>
                                    </td>
                                    <!-- Trong phần bảng, cập nhật cột Thao tác -->
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('giangvien.baigiangcuatoi.show', $baiGiang->id) }}" 
                                            class="btn btn-outline-primary" title="Xem chi tiết">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('giangvien.baigiangcuatoi.edit', $baiGiang->id) }}" 
                                            class="btn btn-outline-warning" title="Chỉnh sửa">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('giangvien.lophoc.show', $baiGiang->ma_lop) }}" 
                                            class="btn btn-outline-info" title="Về lớp học">
                                                <i class="fas fa-users"></i>
                                            </a>
                                            <form action="{{ route('giangvien.baigiangcuatoi.destroy', $baiGiang->id) }}" 
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" 
                                                        title="Xóa"
                                                        onclick="return confirm('Bạn có chắc chắn muốn xóa bài giảng này?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Hiển thị {{ $baiGiangs->firstItem() }} - {{ $baiGiangs->lastItem() }} của {{ $baiGiangs->total() }} bài giảng
                    </div>
                    {{ $baiGiangs->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Chưa có bài giảng nào</h5>
                    <p class="text-muted">Bạn chưa tạo bài giảng nào hoặc không có bài giảng phù hợp với bộ lọc.</p>
                    <a href="{{ route('giangvien.lophoc.index') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Quản lý lớp học để tạo bài giảng
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection