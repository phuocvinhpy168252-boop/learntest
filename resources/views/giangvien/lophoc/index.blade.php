@extends('layouts.giangvien')

@section('title', 'Quản lý Lớp học')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Danh sách Lớp học</h5>
                    <a href="{{ route('giangvien.lophoc.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Tạo Lớp học mới
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Mã Lớp</th>
                                    <th>Tên Lớp</th>
                                    <th>Môn học</th>
                                    <th>Số SV / Tối đa</th>
                                    <th>Phòng học</th>
                                    <th>Thời gian</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lopHocs as $lop)
                                <tr>
                                    <td><strong>{{ $lop->ma_lop }}</strong></td>
                                    <td>{{ $lop->ten_lop }}</td>
                                    <td>{{ $lop->monHoc->ten_mon_hoc ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $lop->so_luong_sv_hien_tai < $lop->so_luong_sv ? 'success' : 'danger' }}">
                                            {{ $lop->so_luong_sv_hien_tai }} / {{ $lop->so_luong_sv }}
                                        </span>
                                    </td>
                                    <td>{{ $lop->phong_hoc }}</td>
                                    <td>{{ $lop->thoi_gian_hoc }}</td>
                                    <td>
                                        @if($lop->trang_thai == 'dang_mo')
                                            <span class="badge bg-success">Đang mở</span>
                                        @elseif($lop->trang_thai == 'dang_hoc')
                                            <span class="badge bg-primary">Đang học</span>
                                        @else
                                            <span class="badge bg-secondary">Kết thúc</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('giangvien.lophoc.show', $lop->ma_lop) }}" 
                                               class="btn btn-info" title="Xem chi tiết">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('giangvien.lophoc.edit', $lop->ma_lop) }}" 
                                               class="btn btn-warning" title="Chỉnh sửa">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('giangvien.lophoc.destroy', $lop->ma_lop) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger" 
                                                        title="Xóa"
                                                        onclick="return confirm('Bạn có chắc chắn muốn xóa lớp học này?')"
                                                        {{ $lop->so_luong_sv_hien_tai > 0 ? 'disabled' : '' }}>
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox fa-2x mb-3"></i>
                                        <br>
                                        Chưa có lớp học nào. 
                                        <a href="{{ route('giangvien.lophoc.create') }}">Tạo lớp học đầu tiên</a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Phân trang -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $lopHocs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection