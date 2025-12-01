@extends('layouts.giangvien')

@section('title', 'Chi tiết bài giảng')

@section('content')
<div class="container-fluid py-4">
    <!-- Navigation -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('giangvien.baigiangcuatoi.index') }}">Bài giảng của tôi</a>
            </li>
            <li class="breadcrumb-item active">Chi tiết bài giảng</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8">
            <!-- Bài giảng Details -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="{{ $baiGiang->getIcon() }} me-2"></i>
                            {{ $baiGiang->tieu_de }}
                        </h5>
                        <span class="badge bg-light text-dark">{{ $baiGiang->ma_bai_giang }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Thông tin cơ bản -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td><strong>Lớp học:</strong></td>
                                    <td>
                                        <a href="{{ route('giangvien.lophoc.show', $baiGiang->ma_lop) }}">
                                            {{ $baiGiang->lopHoc->ten_lop ?? 'N/A' }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Môn học:</strong></td>
                                    <td>{{ $baiGiang->lopHoc->monHoc->ten_mon_hoc ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Loại bài giảng:</strong></td>
                                    <td>
                                        <span class="badge bg-{{ $baiGiang->getBadgeColor() }}">
                                            {{ $baiGiang->getLoaiText() }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td><strong>Thứ tự:</strong></td>
                                    <td>{{ $baiGiang->thu_tu }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Ngày tạo:</strong></td>
                                    <td>{{ $baiGiang->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Cập nhật:</strong></td>
                                    <td>{{ $baiGiang->updated_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Mô tả -->
                    @if($baiGiang->mo_ta)
                        <div class="mb-4">
                            <h6>Mô tả:</h6>
                            <div class="border rounded p-3 bg-light">
                                {{ $baiGiang->mo_ta }}
                            </div>
                        </div>
                    @endif

                    <!-- Nội dung bài giảng -->
                    <div class="mb-4">
                        <h6>Nội dung bài giảng:</h6>
                        
                        @if($baiGiang->isYouTubeVideo())
                            <!-- Video YouTube -->
                            <div class="ratio ratio-16x9 mb-3">
                                <iframe src="https://www.youtube.com/embed/{{ $baiGiang->getYouTubeId() }}" 
                                        title="{{ $baiGiang->tieu_de }}" 
                                        allowfullscreen>
                                </iframe>
                            </div>
                            <div class="text-center">
                                <a href="{{ $baiGiang->url_video }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-external-link-alt me-1"></i> Mở trên YouTube
                                </a>
                            </div>
                        @elseif($baiGiang->url_video)
                            <!-- Video khác -->
                            <div class="alert alert-info">
                                <i class="fas fa-video me-2"></i>
                                Video URL: 
                                <a href="{{ $baiGiang->url_video }}" target="_blank">{{ $baiGiang->url_video }}</a>
                            </div>
                        @endif

                        @if($baiGiang->duong_dan_file)
                            <!-- File đính kèm -->
                            <div class="mt-3">
                                <h6>File đính kèm:</h6>
                                <div class="d-flex align-items-center p-3 border rounded bg-light">
                                    <i class="{{ $baiGiang->getIcon() }} fa-2x text-{{ $baiGiang->getBadgeColor() }} me-3"></i>
                                    <div class="flex-grow-1">
                                        <div class="fw-bold">{{ basename($baiGiang->duong_dan_file) }}</div>
                                        <small class="text-muted">Đường dẫn: {{ $baiGiang->duong_dan_file }}</small>
                                    </div>
                                    <a href="{{ Storage::disk('public')->url($baiGiang->duong_dan_file) }}" 
                                       target="_blank" class="btn btn-primary btn-sm">
                                        <i class="fas fa-download me-1"></i> Tải xuống
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <!-- Cập nhật phần card-footer -->
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <div>
                            <a href="{{ route('giangvien.baigiangcuatoi.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
                            </a>
                        </div>
                        <div>
                            <a href="{{ route('giangvien.baigiangcuatoi.edit', $baiGiang->id) }}" 
                            class="btn btn-warning me-2">
                                <i class="fas fa-edit me-1"></i> Chỉnh sửa
                            </a>
                            <a href="{{ route('giangvien.lophoc.baigiang.edit', [$baiGiang->ma_lop, $baiGiang->id]) }}" 
                            class="btn btn-outline-warning me-2" title="Chỉnh sửa chi tiết (file)">
                                <i class="fas fa-file-edit me-1"></i> Chỉnh sửa File
                            </a>
                            <a href="{{ route('giangvien.lophoc.show', $baiGiang->ma_lop) }}" 
                            class="btn btn-info me-2">
                                <i class="fas fa-users me-1"></i> Về Lớp học
                            </a>
                            <form action="{{ route('giangvien.baigiangcuatoi.destroy', $baiGiang->id) }}" 
                                method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" 
                                        onclick="return confirm('Bạn có chắc chắn muốn xóa bài giảng này?')">
                                    <i class="fas fa-trash me-1"></i> Xóa
                                </button>
                            </form>
                        </div>
                    </div>
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
                    @if($baiGiang->lopHoc)
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td><strong>Mã lớp:</strong></td>
                                <td>{{ $baiGiang->lopHoc->ma_lop }}</td>
                            </tr>
                            <tr>
                                <td><strong>Tên lớp:</strong></td>
                                <td>{{ $baiGiang->lopHoc->ten_lop }}</td>
                            </tr>
                            <tr>
                                <td><strong>Phòng học:</strong></td>
                                <td>{{ $baiGiang->lopHoc->phong_hoc }}</td>
                            </tr>
                            <tr>
                                <td><strong>Thời gian:</strong></td>
                                <td>{{ $baiGiang->lopHoc->thoi_gian_hoc }}</td>
                            </tr>
                            <tr>
                                <td><strong>Sĩ số:</strong></td>
                                <td>
                                    {{ $baiGiang->lopHoc->so_luong_sv_hien_tai }}/{{ $baiGiang->lopHoc->so_luong_sv }}
                                </td>
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
                                    @endphp
                                    <span class="badge bg-{{ $statusColors[$baiGiang->lopHoc->trang_thai] ?? 'secondary' }}">
                                        {{ $baiGiang->lopHoc->trang_thai }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                        <a href="{{ route('giangvien.lophoc.show', $baiGiang->ma_lop) }}" 
                           class="btn btn-outline-info btn-sm w-100">
                            <i class="fas fa-external-link-alt me-1"></i> Xem lớp học
                        </a>
                    @else
                        <p class="text-muted">Không có thông tin lớp học</p>
                    @endif
                </div>
            </div>

            <!-- Các bài giảng khác trong lớp -->
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0"><i class="fas fa-list me-2"></i>Bài giảng khác trong lớp</h6>
                </div>
                <div class="card-body">
                    @php
                        $baiGiangKhac = \App\Models\BaiGiang::where('ma_lop', $baiGiang->ma_lop)
                            ->where('id', '!=', $baiGiang->id)
                            ->orderBy('thu_tu')
                            ->limit(5)
                            ->get();
                    @endphp
                    
                    @if($baiGiangKhac->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($baiGiangKhac as $bg)
                                <a href="{{ route('giangvien.baigiangcuatoi.show', $bg->id) }}" 
                                   class="list-group-item list-group-item-action">
                                    <div class="d-flex align-items-center">
                                        <i class="{{ $bg->getIcon() }} text-{{ $bg->getBadgeColor() }} me-2"></i>
                                        <div class="flex-grow-1">
                                            <div class="fw-bold small">{{ Str::limit($bg->tieu_de, 30) }}</div>
                                            <small class="text-muted">{{ $bg->getLoaiText() }}</small>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted small">Không có bài giảng khác trong lớp này</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection