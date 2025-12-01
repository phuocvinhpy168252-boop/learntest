@extends('layouts.giangvien')

@section('title', 'Chi tiết Bài kiểm tra - ' . $baiKiemTra->tieu_de)

@section('content')
<div class="container-fluid py-4">
    <!-- Navigation -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('giangvien.lophoc.baikiemtra.index', $lopHoc->ma_lop) }}">Bài kiểm tra</a>
            </li>
            <li class="breadcrumb-item active">Chi tiết</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8">
            <!-- Thông tin bài kiểm tra -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="{{ $baiKiemTra->getIcon() }} me-2"></i>
                            {{ $baiKiemTra->tieu_de }}
                        </h5>
                        <span class="badge bg-light text-dark">{{ $baiKiemTra->ma_bai_kiem_tra }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Thông tin cơ bản -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td width="40%"><strong>Loại bài kiểm tra:</strong></td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ $baiKiemTra->getLoaiText() }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Thời gian làm bài:</strong></td>
                                    <td>{{ $baiKiemTra->thoi_gian_lam_bai }} phút</td>
                                </tr>
                                <tr>
                                    <td><strong>Số câu hỏi:</strong></td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $baiKiemTra->so_cau_hoi }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Điểm tối đa:</strong></td>
                                    <td>{{ $baiKiemTra->diem_toi_da }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td width="40%"><strong>Thời gian bắt đầu:</strong></td>
                                    <td>{{ $baiKiemTra->thoi_gian_bat_dau ? $baiKiemTra->thoi_gian_bat_dau->format('d/m/Y H:i') : 'Chưa xác định' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Thời gian kết thúc:</strong></td>
                                    <td>{{ $baiKiemTra->thoi_gian_ket_thuc ? $baiKiemTra->thoi_gian_ket_thuc->format('d/m/Y H:i') : 'Chưa xác định' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Trạng thái:</strong></td>
                                    <td>
                                        <span class="badge bg-{{ $baiKiemTra->getBadgeColor() }}">
                                            {{ $baiKiemTra->getTrangThaiText() }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Ngày tạo:</strong></td>
                                    <td>{{ $baiKiemTra->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Mô tả -->
                    @if($baiKiemTra->mo_ta)
                    <div class="mb-4">
                        <h6>Mô tả:</h6>
                        <div class="border rounded p-3 bg-light">
                            {{ $baiKiemTra->mo_ta }}
                        </div>
                    </div>
                    @endif

                    <!-- Cấu hình -->
                    @if($baiKiemTra->cau_hinh)
                    <div class="mb-4">
                        <h6>Cấu hình:</h6>
                        <div class="row">
                            @php $config = $baiKiemTra->cau_hinh; @endphp
                            @if(isset($config['cho_phep_quay_lai']))
                            <div class="col-md-4">
                                <span class="badge bg-{{ $config['cho_phep_quay_lai'] ? 'success' : 'secondary' }}">
                                    <i class="fas fa-{{ $config['cho_phep_quay_lai'] ? 'check' : 'times' }} me-1"></i>
                                    Cho phép quay lại
                                </span>
                            </div>
                            @endif
                            @if(isset($config['hien_thi_diem']))
                            <div class="col-md-4">
                                <span class="badge bg-{{ $config['hien_thi_diem'] ? 'success' : 'secondary' }}">
                                    <i class="fas fa-{{ $config['hien_thi_diem'] ? 'check' : 'times' }} me-1"></i>
                                    Hiển thị điểm
                                </span>
                            </div>
                            @endif
                            @if(isset($config['ngau_nhien_cau_hoi']))
                            <div class="col-md-4">
                                <span class="badge bg-{{ $config['ngau_nhien_cau_hoi'] ? 'success' : 'secondary' }}">
                                    <i class="fas fa-{{ $config['ngau_nhien_cau_hoi'] ? 'check' : 'times' }} me-1"></i>
                                    Xáo trộn câu hỏi
                                </span>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('giangvien.lophoc.baikiemtra.index', $lopHoc->ma_lop) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Quay lại
                        </a>
                        <div>
                            <div class="btn-group btn-group-sm me-2">
                                <a href="{{ route('giangvien.lophoc.baikiemtra.cauhoi.index', [$lopHoc->ma_lop, $baiKiemTra->id]) }}" 
                                   class="btn btn-info" title="Quản lý Câu hỏi">
                                    <i class="fas fa-question-circle me-1"></i> Câu hỏi ({{ $baiKiemTra->so_cau_hoi }})
                                </a>
                            </div>

                            @if($baiKiemTra->coTheChinhSua())
                            <a href="{{ route('giangvien.lophoc.baikiemtra.edit', [$lopHoc->ma_lop, $baiKiemTra->id]) }}" 
                               class="btn btn-warning me-2">
                                <i class="fas fa-edit me-1"></i> Chỉnh sửa
                            </a>
                            @endif
                            @if($baiKiemTra->trang_thai === 'cho_cong_bo' && $baiKiemTra->so_cau_hoi > 0)
                            <form action="{{ route('giangvien.lophoc.baikiemtra.congbo', [$lopHoc->ma_lop, $baiKiemTra->id]) }}" 
                                  method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success me-2" 
                                        onclick="return confirm('Công bố bài kiểm tra này?')">
                                    <i class="fas fa-bullhorn me-1"></i> Công bố
                                </button>
                            </form>
                            @endif
                            <a href="{{ route('giangvien.lophoc.show', $lopHoc->ma_lop) }}" 
                               class="btn btn-info">
                                <i class="fas fa-users me-1"></i> Về Lớp học
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quản lý câu hỏi -->
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">
                            <i class="fas fa-question-circle me-2"></i>Quản lý Câu hỏi
                        </h6>
                        @if($baiKiemTra->coTheChinhSua())
                        <div>
                            <a href="{{ route('giangvien.lophoc.baikiemtra.cauhoi.import', [$lopHoc->ma_lop, $baiKiemTra->id]) }}" 
                               class="btn btn-sm btn-info me-2">
                                <i class="fas fa-file-excel me-1"></i> Nhập Excel
                            </a>
                            <a href="{{ route('giangvien.lophoc.baikiemtra.cauhoi.create', [$lopHoc->ma_lop, $baiKiemTra->id]) }}" 
                               class="btn btn-sm btn-success">
                                <i class="fas fa-plus me-1"></i> Thêm câu hỏi
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    @if($baiKiemTra->cauHois && $baiKiemTra->cauHois->count() > 0)
                        <div class="list-group">
                            @foreach($baiKiemTra->cauHois as $index => $cauHoi)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-2">Câu {{ $index + 1 }}: {{ $cauHoi->noi_dung }}</h6>
                                        
                                        @if($cauHoi->isTracNghiem())
                                            <div class="ms-3 mt-2">
                                                @php
                                                    $luaChon = $cauHoi->getDapAnList();
                                                    $dapAnDung = $cauHoi->getDapAnDung();
                                                @endphp
                                                @foreach($luaChon as $key => $giaTri)
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" disabled 
                                                           {{ $dapAnDung == $key ? 'checked' : '' }}>
                                                    <label class="form-check-label">
                                                        {{ chr(65 + $key) }}. {{ $giaTri }}
                                                        @if($dapAnDung == $key)
                                                        <span class="badge bg-success ms-2">Đáp án đúng</span>
                                                        @endif
                                                    </label>
                                                </div>
                                                @endforeach
                                            </div>
                                        
                                        @elseif($cauHoi->isTuLuan())
                                            <div class="ms-3 mt-2">
                                                @php
                                                    $dapAn = $cauHoi->cau_tra_loi_json['dap_an_mau'] ?? '';
                                                @endphp
                                                @if($dapAn)
                                                <div class="alert alert-info p-2">
                                                    <strong>Đáp án mẫu:</strong>
                                                    <div class="mt-1">{{ $dapAn }}</div>
                                                </div>
                                                @endif
                                            </div>
                                        @endif
                                        
                                        <div class="mt-2">
                                            <small class="text-muted">
                                                Điểm: <strong>{{ $cauHoi->diem }}</strong> | 
                                                Loại: <span class="badge bg-secondary">{{ $cauHoi->getLoaiText() }}</span>
                                            </small>
                                        </div>
                                    </div>
                                    @if($baiKiemTra->coTheChinhSua())
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('giangvien.lophoc.baikiemtra.cauhoi.edit', [$lopHoc->ma_lop, $baiKiemTra->id, $cauHoi->id]) }}" 
                                           class="btn btn-outline-warning" title="Chỉnh sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('giangvien.lophoc.baikiemtra.cauhoi.destroy', [$lopHoc->ma_lop, $baiKiemTra->id, $cauHoi->id]) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" 
                                                    title="Xóa"
                                                    onclick="return confirm('Xóa câu hỏi này?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-question-circle fa-2x mb-3"></i>
                            <p>Chưa có câu hỏi nào.</p>
                            @if($baiKiemTra->coTheChinhSua())
                            <a href="{{ route('giangvien.lophoc.baikiemtra.cauhoi.create', [$lopHoc->ma_lop, $baiKiemTra->id]) }}" 
                               class="btn btn-success">
                                <i class="fas fa-plus me-1"></i> Thêm câu hỏi đầu tiên
                            </a>
                            @else
                            <p class="text-warning">Bài kiểm tra này không thể thêm câu hỏi.</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Thông tin lớp học -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Thông tin lớp học</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td><strong>Mã lớp:</strong></td>
                            <td>{{ $lopHoc->ma_lop }}</td>
                        </tr>
                        <tr>
                            <td><strong>Tên lớp:</strong></td>
                            <td>{{ $lopHoc->ten_lop }}</td>
                        </tr>
                        <tr>
                            <td><strong>Môn học:</strong></td>
                            <td>{{ $lopHoc->monHoc->ten_mon_hoc ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Sĩ số:</strong></td>
                            <td>{{ $lopHoc->so_luong_sv_hien_tai }}</td>
                        </tr>
                    </table>
                    <a href="{{ route('giangvien.lophoc.show', $lopHoc->ma_lop) }}" 
                       class="btn btn-outline-info btn-sm w-100">
                        <i class="fas fa-external-link-alt me-1"></i> Xem lớp học
                    </a>
                </div>
            </div>

            <!-- Thống kê -->
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Thống kê</h6>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <div class="mb-3">
                            <i class="fas fa-question-circle fa-2x text-success mb-2"></i>
                            <h4>{{ $baiKiemTra->so_cau_hoi }}</h4>
                            <small class="text-muted">Tổng số câu hỏi</small>
                        </div>
                        <div class="mb-3">
                            <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                            <h4>{{ $baiKiemTra->thoi_gian_lam_bai }}</h4>
                            <small class="text-muted">Phút làm bài</small>
                        </div>
                        <div>
                            <i class="fas fa-star fa-2x text-primary mb-2"></i>
                            <h4>{{ $baiKiemTra->diem_toi_da }}</h4>
                            <small class="text-muted">Điểm tối đa</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection