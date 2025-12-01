@extends('layouts.app')

@section('title', 'Bài kiểm tra - ' . $lopHoc->ten_lop)

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('sinhvien.khoahoc') }}">Khóa học của tôi</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('sinhvien.lophoc.baigiang', $lopHoc->ma_lop) }}">{{ $lopHoc->ten_lop }}</a>
                            </li>
                            <li class="breadcrumb-item active">Bài kiểm tra</li>
                        </ol>
                    </nav>
                    
                    <div class="row mt-3">
                        <div class="col-md-8">
                            <h4 class="text-primary mb-2">{{ $lopHoc->ten_lop }}</h4>
                            <p class="text-muted mb-1">
                                <i class="fas fa-book me-1"></i>
                                {{ $lopHoc->monHoc->ten_mon_hoc ?? 'Chưa có môn học' }}
                            </p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <div class="d-flex flex-column">
                                <span class="badge bg-success mb-2 fs-6">Bài kiểm tra</span>
                                <small class="text-muted">Mã lớp: {{ $lopHoc->ma_lop }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Danh sách bài kiểm tra -->
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-clipboard-list me-2"></i>Danh sách bài kiểm tra
                        <span class="badge bg-primary ms-2">{{ $baiKiemTras->count() }} bài</span>
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($baiKiemTras->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="30%">Tên bài kiểm tra</th>
                                        <th width="15%">Loại</th>
                                        <th width="15%">Thời gian</th>
                                        <th width="10%">Trạng thái</th>
                                        <th width="10%">Điểm</th>
                                        <th width="15%">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($baiKiemTras as $index => $bkt)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <strong>{{ $bkt->tieu_de }}</strong>
                                            @if($bkt->mo_ta)
                                            <small class="d-block text-muted">{{ Str::limit($bkt->mo_ta, 50) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-info">
                                                <i class="{{ $bkt->getIcon() }} me-1"></i>
                                                {{ $bkt->getLoaiText() }}
                                            </span>
                                        </td>
                                        <td>
                                            <small class="d-block">
                                                <i class="fas fa-clock me-1"></i>
                                                {{ $bkt->thoi_gian_lam_bai }} phút
                                            </small>
                                            <small class="d-block text-muted">
                                                {{ $bkt->thoi_gian_bat_dau->format('d/m/Y H:i') }}
                                            </small>
                                        </td>
                                        <td>
                                            @php
                                                $now = now();
                                                $statusClass = 'secondary';
                                                $statusText = 'Chưa mở';
                                                
                                                if ($bkt->trang_thai === 'dang_dien_ra') {
                                                    if ($now >= $bkt->thoi_gian_bat_dau && $now <= $bkt->thoi_gian_ket_thuc) {
                                                        $statusClass = 'success';
                                                        $statusText = 'Đang mở';
                                                    } else {
                                                        $statusClass = 'warning';
                                                        $statusText = 'Chưa đến giờ';
                                                    }
                                                } elseif ($bkt->trang_thai === 'da_ket_thuc') {
                                                    $statusClass = 'secondary';
                                                    $statusText = 'Đã kết thúc';
                                                } elseif ($bkt->trang_thai === 'cho_cong_bo') {
                                                    $statusClass = 'warning';
                                                    $statusText = 'Chờ công bố';
                                                }
                                            @endphp
                                            <span class="badge bg-{{ $statusClass }}">
                                                {{ $statusText }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($bkt->da_lam)
                                                <span class="fw-bold text-success">{{ $bkt->diem ?? 'Chưa có' }}</span>
                                                <small class="d-block text-muted">
                                                    {{ $bkt->thoi_gian_nop ? $bkt->thoi_gian_nop->format('H:i d/m') : '' }}
                                                </small>
                                            @else
                                                <span class="text-muted">Chưa làm</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('sinhvien.lophoc.baikiemtra.chitiet', [$lopHoc->ma_lop, $bkt->ma_bai_kiem_tra]) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            @php
                                                $canTakeTest = false;
                                                if ($bkt->trang_thai === 'dang_dien_ra' && 
                                                    !$bkt->da_lam && 
                                                    $now >= $bkt->thoi_gian_bat_dau && 
                                                    $now <= $bkt->thoi_gian_ket_thuc) {
                                                    $canTakeTest = true;
                                                }
                                            @endphp
                                            
                                            @if($canTakeTest)
                                            <a href="{{ route('sinhvien.lophoc.baikiemtra.lambai', [$lopHoc->ma_lop, $bkt->ma_bai_kiem_tra]) }}" 
                                               class="btn btn-sm btn-success">
                                                <i class="fas fa-play"></i>
                                            </a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Chưa có bài kiểm tra nào</h5>
                            <p class="text-muted">Giảng viên sẽ tạo bài kiểm tra sớm nhất.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection