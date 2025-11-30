@extends('layouts.admin')

@section('title', 'Quản lý Sinh viên - Hệ thống Giáo dục')
@section('page-title', 'Quản lý Sinh viên')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="admin-card">
            <div class="card-header">
                <h3 class="card-title">Danh sách sinh viên</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.sinhvien.createSV') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-2"></i>Thêm sinh viên
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Search Form -->
                <form action="{{ route('admin.sinhvien.index') }}" method="GET" class="mb-3">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm theo mã, tên, email, lớp..." value="{{ request('search') }}">
                                <button type="submit" class="btn btn-outline-secondary">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Students Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Mã SV</th>
                                <th>Họ tên</th>
                                <th>Email</th>
                                <th>Số điện thoại</th>
                                <th>Lớp</th>
                                <th>Khóa</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sinhViens as $sv)
                                <tr>
                                    <td><strong>{{ $sv->ma_sinhvien }}</strong></td>
                                    <td>{{ $sv->ten }}</td>
                                    <td>{{ $sv->email }}</td>
                                    <td>{{ $sv->so_dien_thoai }}</td>
                                    <td>{{ $sv->lop }}</td>
                                    <td>{{ $sv->khoa }}</td>
                                    <td>
                                        @if($sv->trang_thai == 'hoat_dong')
                                            <span class="badge bg-success">Hoạt động</span>
                                        @else
                                            <span class="badge bg-danger">Vô hiệu hóa</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.sinhvien.show', $sv->ma_sinhvien) }}" class="btn btn-info" title="Xem chi tiết">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.sinhvien.edit', $sv->ma_sinhvien) }}" class="btn btn-warning" title="Sửa">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if($sv->trang_thai == 'hoat_dong')
                                                <a href="{{ route('admin.sinhvien.disable', $sv->ma_sinhvien) }}" class="btn btn-secondary" title="Vô hiệu hóa">
                                                    <i class="fas fa-ban"></i>
                                                </a>
                                            @else
                                                <a href="{{ route('admin.sinhvien.enable', $sv->ma_sinhvien) }}" class="btn btn-success" title="Kích hoạt">
                                                    <i class="fas fa-check"></i>
                                                </a>
                                            @endif
                                            <form action="{{ route('admin.sinhvien.destroy', $sv->ma_sinhvien) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger" title="Xóa" onclick="return confirm('Bạn có chắc chắn muốn xóa sinh viên này?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Không có sinh viên nào</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-3">
                    {{ $sinhViens->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection