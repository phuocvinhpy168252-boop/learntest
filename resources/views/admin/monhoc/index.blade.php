@extends('layouts.admin')

@section('title', 'Quản Lý Môn Học')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Danh Sách Môn Học</h5>
                        <a href="{{ route('admin.monhoc.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Thêm Môn Học
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Search Form -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <form action="{{ route('admin.monhoc.index') }}" method="GET">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="search" 
                                           placeholder="Tìm kiếm theo mã hoặc tên môn học..." 
                                           value="{{ request('search') }}">
                                    <button class="btn btn-outline-secondary" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    @if(request('search'))
                                        <a href="{{ route('admin.monhoc.index') }}" class="btn btn-outline-danger">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Mã Môn Học</th>
                                    <th>Tên Môn Học</th>
                                    <th>Mô Tả</th>
                                    <th>Trạng Thái</th>
                                    <th>Thao Tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($monHocs as $monHoc)
                                    <tr>
                                        <td><strong>{{ $monHoc->ma_mon_hoc }}</strong></td>
                                        <td>{{ $monHoc->ten_mon_hoc }}</td>
                                     
                                        <td>
                                            @if($monHoc->mo_ta)
                                                <span class="text-truncate" style="max-width: 200px;" 
                                                      title="{{ $monHoc->mo_ta }}">
                                                    {{ Str::limit($monHoc->mo_ta, 50) }}
                                                </span>
                                            @else
                                                <span class="text-muted">Không có mô tả</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($monHoc->trang_thai == 'hoat_dong')
                                                <span class="badge bg-success">Hoạt động</span>
                                            @else
                                                <span class="badge bg-danger">Vô hiệu hóa</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.monhoc.edit', $monHoc->ma_mon_hoc) }}" 
                                                   class="btn btn-sm btn-warning" title="Sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                
                                                @if($monHoc->trang_thai == 'hoat_dong')
                                                    <form action="{{ route('admin.monhoc.disable', $monHoc->ma_mon_hoc) }}" 
                                                          method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="btn btn-sm btn-secondary" 
                                                                title="Vô hiệu hóa"
                                                                onclick="return confirm('Bạn có chắc muốn vô hiệu hóa môn học này?')">
                                                            <i class="fas fa-ban"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <form action="{{ route('admin.monhoc.enable', $monHoc->ma_mon_hoc) }}" 
                                                          method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="btn btn-sm btn-success" 
                                                                title="Kích hoạt"
                                                                onclick="return confirm('Bạn có chắc muốn kích hoạt môn học này?')">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                
                                                <form action="{{ route('admin.monhoc.destroy', $monHoc->ma_mon_hoc) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" 
                                                            title="Xóa"
                                                            onclick="return confirm('Bạn có chắc muốn xóa môn học này?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            <i class="fas fa-book-open fa-2x mb-3"></i>
                                            <p>Không có môn học nào</p>
                                            <a href="{{ route('admin.monhoc.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus me-2"></i>Thêm Môn Học Đầu Tiên
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($monHocs->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-muted">
                                Hiển thị {{ $monHocs->firstItem() }} đến {{ $monHocs->lastItem() }} 
                                trong tổng số {{ $monHocs->total() }} môn học
                            </div>
                            <div>
                                {{ $monHocs->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.table th {
    font-weight: 600;
    border-bottom: 2px solid #dee2e6;
}
.btn-group .btn {
    margin: 0 2px;
}
.text-truncate {
    display: inline-block;
    max-width: 200px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
</style>
@endpush