@extends('layouts.giangvien')

@section('title', 'Quản lý Bài kiểm tra - ' . $lopHoc->ten_lop)

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0">
                            <i class="fas fa-clipboard-check me-2"></i>Bài kiểm tra - {{ $lopHoc->ten_lop }}
                        </h5>
                        <small class="text-muted">Mã lớp: {{ $lopHoc->ma_lop }}</small>
                    </div>
                    <div>
                        <a href="{{ route('giangvien.lophoc.show', $lopHoc->ma_lop) }}" class="btn btn-secondary me-2">
                            <i class="fas fa-arrow-left me-1"></i>Về lớp học
                        </a>
                        <a href="{{ route('giangvien.lophoc.baikiemtra.create', $lopHoc->ma_lop) }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Tạo Bài kiểm tra
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

                    @if($baiKiemTras->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Mã Bài KT</th>
                                        <th>Tiêu đề</th>
                                        <th>Loại</th>
                                        <th>Số câu</th>
                                        <th>Thời gian</th>
                                        <th>Thời hạn</th>
                                        <th>Trạng thái</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($baiKiemTras as $bkt)
                                    <tr>
                                        <td><strong>{{ $bkt->ma_bai_kiem_tra }}</strong></td>
                                        <td>
                                            <div>
                                                <strong>{{ $bkt->tieu_de }}</strong>
                                                @if($bkt->mo_ta)
                                                    <br>
                                                    <small class="text-muted">{{ Str::limit($bkt->mo_ta, 50) }}</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">
                                                <i class="{{ $bkt->getIcon() }} me-1"></i>
                                                {{ $bkt->getLoaiText() }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $bkt->so_cau_hoi }}</span>
                                        </td>
                                        <td>{{ $bkt->thoi_gian_lam_bai }} phút</td>
                                        <td>
                                            <small>
                                                {{ $bkt->thoi_gian_bat_dau ? $bkt->thoi_gian_bat_dau->format('d/m/Y H:i') : 'Chưa xác định' }}
                                            </small>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $bkt->getBadgeColor() }}">
                                                {{ $bkt->getTrangThaiText() }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('giangvien.lophoc.baikiemtra.show', [$lopHoc->ma_lop, $bkt->id]) }}" 
                                                   class="btn btn-info" title="Xem chi tiết">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($bkt->coTheChinhSua())
                                                <a href="{{ route('giangvien.lophoc.baikiemtra.edit', [$lopHoc->ma_lop, $bkt->id]) }}" 
                                                   class="btn btn-warning" title="Chỉnh sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('giangvien.lophoc.baikiemtra.destroy', [$lopHoc->ma_lop, $bkt->id]) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" 
                                                            title="Xóa"
                                                            onclick="return confirm('Xóa bài kiểm tra này?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                                @endif
                                                @if($bkt->trang_thai === 'cho_cong_bo' && $bkt->so_cau_hoi > 0)
                                                <form action="{{ route('giangvien.lophoc.baikiemtra.congbo', [$lopHoc->ma_lop, $bkt->id]) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success" 
                                                            title="Công bố"
                                                            onclick="return confirm('Công bố bài kiểm tra này?')">
                                                        <i class="fas fa-bullhorn"></i>
                                                    </button>
                                                </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Phân trang -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $baiKiemTras->links() }}
                        </div>
                    @else
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-clipboard-check fa-3x mb-3"></i>
                            <p>Chưa có bài kiểm tra nào.</p>
                            <a href="{{ route('giangvien.lophoc.baikiemtra.create', $lopHoc->ma_lop) }}" 
                               class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Tạo Bài kiểm tra đầu tiên
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection