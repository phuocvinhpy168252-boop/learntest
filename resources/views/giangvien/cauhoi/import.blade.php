@extends('layouts.giangvien')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h2>Nhập Ngân Hàng Câu Hỏi từ Excel</h2>
            <p class="text-muted">Bài kiểm tra: <strong>{{ $baiKiemTra->tieu_de }}</strong></p>
        </div>
        <div class="col-auto">
            <a href="{{ route('giangvien.lophoc.baikiemtra.cauhoi.index', [$lopHoc->ma_lop, $baiKiemTra->id]) }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <h5>Lỗi xảy ra:</h5>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <h5>{{ session('success') }}</h5>
        @if (session('errors') && count(session('errors')) > 0)
        <hr>
        <p><strong>Chi tiết lỗi:</strong></p>
        <ul class="mb-0">
            @foreach (session('errors') as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        @endif
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row">
        <!-- Template Download Section -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-download"></i> Tải File Mẫu</h5>
                </div>
                <div class="card-body">
                    <p>Tải file mẫu Excel để xem định dạng đúng và có thể dùng làm template:</p>
                    <ul class="list-unstyled">
                        <li><i class="bi bi-check-circle text-success"></i> File mẫu với câu hỏi ví dụ</li>
                        <li><i class="bi bi-check-circle text-success"></i> Sheet hướng dẫn chi tiết</li>
                        <li><i class="bi bi-check-circle text-success"></i> Định dạng cột chuẩn</li>
                    </ul>
                    <a href="{{ route('giangvien.lophoc.baikiemtra.cauhoi.download-template', [$lopHoc->ma_lop, $baiKiemTra->id]) }}" class="btn btn-info btn-lg w-100 mt-3">
                        <i class="bi bi-download"></i> Tải File Mẫu Excel
                    </a>
                </div>
            </div>
        </div>

        <!-- File Upload Section -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-upload"></i> Tải File Lên</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('giangvien.lophoc.baikiemtra.cauhoi.import.store', [$lopHoc->ma_lop, $baiKiemTra->id]) }}" method="POST" enctype="multipart/form-data" id="importForm">
                        @csrf

                        <div class="mb-3">
                            <label for="file" class="form-label">Chọn file Excel</label>
                            <div class="dropzone" id="dropZone">
                                <input type="file" id="file" name="file" accept=".xlsx,.xls,.csv" class="form-control" required>
                                <div class="text-center py-4" id="dropZoneText">
                                    <i class="bi bi-cloud-arrow-up display-4"></i>
                                    <p class="mt-2">Kéo thả file hoặc click để chọn</p>
                                    <small class="text-muted">Hỗ trợ: .xlsx, .xls, .csv</small>
                                </div>
                            </div>
                            @error('file')
                            <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div id="fileInfo" style="display: none;" class="alert alert-info">
                            <small>File chọn: <span id="fileName"></span></small>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="bi bi-upload"></i> Nhập Câu Hỏi
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Instructions Section -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-info-circle"></i> Hướng Dẫn Nhập</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>1. Loại Câu Hỏi</h6>
                            <ul class="small">
                                <li><code>trac_nghiem</code> - Câu trắc nghiệm (4 đáp án)</li>
                                <li><code>tu_luan</code> - Câu tự luận</li>
                                <li><code>dung_sai</code> - Câu đúng/sai</li>
                            </ul>

                            <h6 class="mt-3">2. Cột Đáp Án (Cột C-F)</h6>
                            <ul class="small">
                                <li>Trắc nghiệm: Nhập 4 đáp án</li>
                                <li>Tự luận: Để trống</li>
                                <li>Đúng/Sai: Để trống</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6>3. Đáp Án Đúng (Cột G)</h6>
                            <ul class="small">
                                <li>Trắc nghiệm: 1, 2, 3 hoặc 4</li>
                                <li>Đúng/Sai: 0 (Sai) hoặc 1 (Đúng)</li>
                            </ul>

                            <h6 class="mt-3">4. Điểm (Cột H)</h6>
                            <ul class="small">
                                <li>Nhập số điểm (ví dụ: 1, 0.5, 2)</li>
                            </ul>
                        </div>
                    </div>

                    <hr>

                    <h6 class="mt-3">Ví dụ Định Dạng File:</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered small">
                            <thead class="table-light">
                                <tr>
                                    <th>Loại Câu Hỏi</th>
                                    <th>Nội Dung</th>
                                    <th>Đáp Án 1</th>
                                    <th>Đáp Án 2</th>
                                    <th>Đáp Án 3</th>
                                    <th>Đáp Án 4</th>
                                    <th>Đáp Án Đúng</th>
                                    <th>Điểm</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>trac_nghiem</td>
                                    <td>Thủ đô của Việt Nam là gì?</td>
                                    <td>Hà Nội</td>
                                    <td>HCM</td>
                                    <td>Huế</td>
                                    <td>Đà Nẵng</td>
                                    <td>1</td>
                                    <td>1</td>
                                </tr>
                                <tr>
                                    <td>tu_luan</td>
                                    <td>Giải thích khái niệm...</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>2</td>
                                </tr>
                                <tr>
                                    <td>dung_sai</td>
                                    <td>Paris là thủ đô của Pháp</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>1</td>
                                    <td>1</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.dropzone {
    border: 2px dashed #dee2e6;
    border-radius: 0.375rem;
    padding: 0;
    cursor: pointer;
    transition: all 0.3s ease;
}

.dropzone:hover {
    border-color: #0d6efd;
    background-color: #f8f9ff;
}

.dropzone.dragover {
    border-color: #0d6efd;
    background-color: #f8f9ff;
}

.dropzone input[type="file"] {
    display: none;
}

#dropZoneText {
    display: block;
}

#dropZoneText.hidden {
    display: none;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('file');
    const dropZoneText = document.getElementById('dropZoneText');
    const fileInfo = document.getElementById('fileInfo');
    const fileName = document.getElementById('fileName');

    // Click to select
    dropZone.addEventListener('click', () => fileInput.click());

    // Drag and drop
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('dragover');
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('dragover');
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('dragover');
        fileInput.files = e.dataTransfer.files;
        updateFileInfo();
    });

    // File input change
    fileInput.addEventListener('change', updateFileInfo);

    function updateFileInfo() {
        if (fileInput.files.length > 0) {
            const file = fileInput.files[0];
            fileName.textContent = file.name + ' (' + (file.size / 1024).toFixed(2) + ' KB)';
            fileInfo.style.display = 'block';
            dropZoneText.classList.add('hidden');
        } else {
            fileInfo.style.display = 'none';
            dropZoneText.classList.remove('hidden');
        }
    }
});
</script>
@endsection
