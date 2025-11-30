@extends('layouts.giangvien')

@section('title', 'Quản lý Bài giảng - ' . $lopHoc->ten_lop)

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0">
                            <i class="fas fa-book-open me-2"></i>Bài giảng - {{ $lopHoc->ten_lop }}
                        </h5>
                        <small class="text-muted">Mã lớp: {{ $lopHoc->ma_lop }}</small>
                    </div>
                    <div>
                        <a href="{{ route('giangvien.lophoc.index') }}" class="btn btn-secondary me-2">
                            <i class="fas fa-arrow-left me-1"></i>Quay lại
                        </a>
                        <a href="{{ route('giangvien.lophoc.baigiang.create', $lopHoc->ma_lop) }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Thêm Bài giảng
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

                    @if($baiGiangs->count() > 0)
                        <div class="row">
                            @foreach($baiGiangs as $baiGiang)
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <span class="badge bg-{{ $baiGiang->getBadgeColor() }}">
                                                <i class="{{ $baiGiang->getIcon() }} me-1"></i>
                                                {{ $baiGiang->getLoaiText() }}
                                            </span>
                                            <span class="text-muted small">#{{ $baiGiang->thu_tu }}</span>
                                        </div>
                                        <h6 class="card-title">{{ $baiGiang->tieu_de }}</h6>
                                        @if($baiGiang->mo_ta)
                                            <p class="card-text text-muted small">{{ Str::limit($baiGiang->mo_ta, 100) }}</p>
                                        @endif
                                        
                                        @if($baiGiang->duong_dan_file)
                                            <div class="mb-2">
                                                <small class="text-success">
                                                    <i class="fas fa-paperclip me-1"></i>
                                                    {{ basename($baiGiang->duong_dan_file) }}
                                                </small>
                                            </div>
                                        @endif

                                        @if($baiGiang->url_video)
                                            <div class="mb-2">
                                                <small class="text-info">
                                                    <i class="fas fa-link me-1"></i>
                                                    Video URL
                                                </small>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="card-footer bg-transparent">
                                        <div class="btn-group w-100">
                                            @if($baiGiang->duong_dan_file)
                                                <a href="{{ Storage::url($baiGiang->duong_dan_file) }}" 
                                                   target="_blank" class="btn btn-sm btn-outline-success">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            @endif
                                            <a href="{{ route('giangvien.lophoc.baigiang.edit', [$lopHoc->ma_lop, $baiGiang->id]) }}" 
                                               class="btn btn-sm btn-outline-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('giangvien.lophoc.baigiang.destroy', [$lopHoc->ma_lop, $baiGiang->id]) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                        onclick="return confirm('Xóa bài giảng này?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-book-open fa-3x mb-3"></i>
                            <p>Chưa có bài giảng nào.</p>
                            <a href="{{ route('giangvien.lophoc.baigiang.create', $lopHoc->ma_lop) }}" 
                               class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Thêm Bài giảng đầu tiên
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection