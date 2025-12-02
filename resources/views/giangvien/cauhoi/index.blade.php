@extends('layouts.giangvien')

@section('title', 'Quản lý Câu hỏi - ' . $baiKiemTra->tieu_de)

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0">
                            <i class="fas fa-question-circle me-2"></i>Quản lý Câu hỏi
                        </h5>
                        <small class="text-muted">{{ $baiKiemTra->tieu_de }} - Tổng: {{ $cauHois->total() }} câu</small>
                    </div>
                    <div>
                        <a href="{{ route('giangvien.lophoc.baikiemtra.show', [$lopHoc->ma_lop, $baiKiemTra->id]) }}" 
                           class="btn btn-secondary me-2">
                            <i class="fas fa-arrow-left me-1"></i>Về bài kiểm tra
                        </a>
                        <a href="{{ route('giangvien.lophoc.baikiemtra.cauhoi.import', [$lopHoc->ma_lop, $baiKiemTra->id]) }}" 
                           class="btn btn-info me-2">
                            <i class="fas fa-file-excel me-1"></i>Nhập Excel
                        </a>
                        <a href="{{ route('giangvien.lophoc.baikiemtra.cauhoi.create', [$lopHoc->ma_lop, $baiKiemTra->id]) }}" 
                           class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Thêm Câu hỏi
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

                    @if($cauHois->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>STT</th>
                                        <th>Mã câu hỏi</th>
                                        <th>Nội dung</th>
                                        <th>Loại</th>
                                        <th>Điểm</th>
                                        <th>Thứ tự</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cauHois as $index => $cauHoi)
                                    <tr>
                                        <td>{{ ($cauHois->currentPage() - 1) * $cauHois->perPage() + $index + 1 }}</td>
                                        <td><strong>{{ $cauHoi->ma_cau_hoi }}</strong></td>
                                        <td>
                                            <div class="question-content">
                                                {!! nl2br(e(Str::limit($cauHoi->noi_dung, 100))) !!}
                                            </div>
                                            @if($cauHoi->isTracNghiem())
                                                <div class="mt-2">
                                                    @php
                                                        $luaChon = $cauHoi->getDapAnList();
                                                        $dapAnDung = $cauHoi->getDapAnDung();
                                                    @endphp
                                                    @foreach($luaChon as $key => $giaTri)
                                                        <small class="d-block">
                                                            {{ chr(65 + $key) }}. {{ $giaTri }}
                                                            @if($key == $dapAnDung)
                                                                <span class="badge bg-success ms-2">Đúng</span>
                                                            @endif
                                                        </small>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-info">
                                                {{ $cauHoi->getLoaiText() }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-warning">{{ $cauHoi->diem }}</span>
                                        </td>
                                        <td>{{ $cauHoi->thu_tu }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('giangvien.lophoc.baikiemtra.cauhoi.edit', [$lopHoc->ma_lop, $baiKiemTra->id, $cauHoi->id]) }}" 
                                                   class="btn btn-warning" title="Chỉnh sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('giangvien.lophoc.baikiemtra.cauhoi.destroy', [$lopHoc->ma_lop, $baiKiemTra->id, $cauHoi->id]) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" 
                                                            title="Xóa"
                                                            onclick="return confirm('Xóa câu hỏi này?')">
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

                        <div class="d-flex justify-content-center mt-4">
                            {{ $cauHois->links() }}
                        </div>
                    @else
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-question-circle fa-3x mb-3"></i>
                            <p>Chưa có câu hỏi nào.</p>
                            <a href="{{ route('giangvien.lophoc.baikiemtra.cauhoi.create', [$lopHoc->ma_lop, $baiKiemTra->id]) }}" 
                               class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Thêm Câu hỏi đầu tiên
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection