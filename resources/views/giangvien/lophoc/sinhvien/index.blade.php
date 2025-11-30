@extends('layouts.giangvien')

@section('title', 'Quản lý Sinh viên - ' . $lopHoc->ten_lop)

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0">
                            <i class="fas fa-user-graduate me-2"></i>Quản lý Sinh viên - {{ $lopHoc->ten_lop }}
                        </h5>
                        <small class="text-muted">Mã lớp: {{ $lopHoc->ma_lop }}</small>
                    </div>
                    <div>
                        <a href="{{ route('giangvien.lophoc.index') }}" class="btn btn-secondary me-2">
                            <i class="fas fa-arrow-left me-1"></i>Quay lại
                        </a>
                        <a href="{{ route('giangvien.lophoc.sinhvien.create', $lopHoc->ma_lop) }}" 
                           class="btn btn-primary" 
                           {{ !$lopHoc->conChoTrong() ? 'disabled' : '' }}>
                            <i class="fas fa-plus me-2"></i>Thêm Sinh viên
                        </a>
                    </div>
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

                    <!-- Thông tin lớp học -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">Thông tin lớp học</h6>
                                    <p class="mb-1"><strong>Môn học:</strong> {{ $lopHoc->monHoc->ten_mon_hoc ?? 'N/A' }}</p>
                                    <p class="mb-1"><strong>Số lượng:</strong> {{ $lopHoc->so_luong_sv_hien_tai }}/{{ $lopHoc->so_luong_sv }}</p>
                                    <p class="mb-0"><strong>Chỗ trống:</strong> {{ $lopHoc->so_luong_sv - $lopHoc->so_luong_sv_hien_tai }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">Trạng thái</h6>
                                    <p class="mb-1">
                                        <strong>Lớp:</strong> 
                                        @if($lopHoc->trang_thai == 'dang_mo')
                                            <span class="badge bg-success">Đang mở</span>
                                        @elseif($lopHoc->trang_thai == 'dang_hoc')
                                            <span class="badge bg-primary">Đang học</span>
                                        @else
                                            <span class="badge bg-secondary">Kết thúc</span>
                                        @endif
                                    </p>
                                    <p class="mb-0">
                                        <strong>Đăng ký:</strong> 
                                        @if($lopHoc->conChoTrong())
                                            <span class="badge bg-success">Còn chỗ</span>
                                        @else
                                            <span class="badge bg-danger">Đã đầy</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Danh sách sinh viên -->
                    @if($sinhViens->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Mã SV</th>
                                        <th>Họ tên</th>
                                        <th>Email</th>
                                        <th>SĐT</th>
                                        <th>Lớp</th>
                                        <th>Trạng thái</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sinhViens as $sv)
                                    <tr>
                                        <td><strong>{{ $sv->ma_sinhvien }}</strong></td>
                                        <td>{{ $sv->ten }}</td>
                                        <td>{{ $sv->email }}</td>
                                        <td>{{ $sv->so_dien_thoai }}</td>
                                        <td>{{ $sv->lop }}</td>
                                        <td>
                                            @if($sv->trang_thai == 'hoat_dong')
                                                <span class="badge bg-success">Hoạt động</span>
                                            @else
                                                <span class="badge bg-danger">Vô hiệu</span>
                                            @endif
                                        </td>
                                        <td>
                                            <form action="{{ route('giangvien.lophoc.sinhvien.destroy', [$lopHoc->ma_lop, $sv->ma_sinhvien]) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" 
                                                        onclick="return confirm('Xóa sinh viên {{ $sv->ten }} khỏi lớp học?')">
                                                    <i class="fas fa-trash"></i> Xóa
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-user-graduate fa-3x mb-3"></i>
                            <p>Chưa có sinh viên nào trong lớp học.</p>
                            @if($lopHoc->conChoTrong())
                                <a href="{{ route('giangvien.lophoc.sinhvien.create', $lopHoc->ma_lop) }}" 
                                   class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Thêm Sinh viên đầu tiên
                                </a>
                            @else
                                <button class="btn btn-secondary" disabled>Lớp học đã đầy</button>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection