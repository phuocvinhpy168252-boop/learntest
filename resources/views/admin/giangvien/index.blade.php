@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h1>Quản lý Giảng viên</h1>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Danh sách Giảng viên</h5>
                        <a href="{{ route('admin.giangvien.createGV') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Thêm giảng viên
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Search Form -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <form action="{{ route('admin.giangvien.index') }}" method="GET">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Tìm kiếm theo mã, tên, email..." 
                                           name="search" value="{{ request('search') }}">
                                    <button class="btn btn-outline-secondary" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    @if(request('search'))
                                        <a href="{{ route('admin.giangvien.index') }}" class="btn btn-outline-danger">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>

                    @if($giangViens->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Mã GV</th>
                                        <th>Họ tên</th>
                                        <th>Email</th>
                                        <th>Số điện thoại</th>
                                        <th>Ngày sinh</th>
                                        <th>Giới tính</th>
                                        <th>Môn dạy</th>
                                        <th>Trình độ</th>
                                        <th>Địa chỉ</th>
                                        <th>Loại TK</th>
                                        <th>Trạng thái</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($giangViens as $gv)
                                    <tr>
                                        <td><strong>{{ $gv->ma_giangvien }}</strong></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-3">
                                                    <div class="avatar-title bg-primary text-white rounded-circle">
                                                        {{ substr($gv->ten, 0, 1) }}
                                                    </div>
                                                </div>
                                                <div class="min-width-200">
                                                    <div class="fw-bold text-truncate" title="{{ $gv->ten }}">
                                                        {{ $gv->ten }}
                                                    </div>
                                                    <small class="text-muted">Mã: {{ $gv->ma_giangvien }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-truncate" style="max-width: 200px;" title="{{ $gv->email }}">
                                                <i class="fas fa-envelope text-muted me-2"></i>
                                                {{ $gv->email }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-phone text-muted me-2"></i>
                                                {{ $gv->so_dien_thoai }}
                                            </div>
                                        </td>
                                        <td>
                                            @if($gv->ngay_sinh)
                                                <div class="text-nowrap">
                                                    <i class="fas fa-calendar text-muted me-2"></i>
                                                    {{ \Carbon\Carbon::parse($gv->ngay_sinh)->format('d/m/Y') }}
                                                </div>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($gv->gioi_tinh == 'nam')
                                                <span class="badge bg-primary">
                                                    <i class="fas fa-mars me-1"></i>Nam
                                                </span>
                                            @elseif($gv->gioi_tinh == 'nu')
                                                <span class="badge bg-pink text-white">
                                                    <i class="fas fa-venus me-1"></i>Nữ
                                                </span>
                                            @elseif($gv->gioi_tinh == 'khac')
                                                <span class="badge bg-secondary">
                                                    <i class="fas fa-transgender me-1"></i>Khác
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($gv->mon_day)
                                                <span class="badge bg-info text-white">{{ $gv->mon_day }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($gv->trinh_do)
                                                <span class="badge bg-warning text-dark">
                                                    <i class="fas fa-graduation-cap me-1"></i>{{ $gv->trinh_do }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($gv->dia_chi)
                                                <span class="text-truncate" style="max-width: 150px;" 
                                                      data-bs-toggle="tooltip" title="{{ $gv->dia_chi }}">
                                                    {{ Str::limit($gv->dia_chi, 30) }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                       <td>
                                            <span class="badge bg-primary">
                                                <i class="fas fa-chalkboard-teacher me-1"></i>Giảng viên
                                            </span>
                                        </td>
                                        <td>
                                            @if($gv->trang_thai == 'vo_hieu_hoa')
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-ban me-1"></i>Vô hiệu hóa
                                                </span>
                                            @else
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check-circle me-1"></i>Hoạt động
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-outline-primary" 
                                                        data-bs-toggle="tooltip" title="Xem chi tiết"
                                                        onclick="viewDetail('{{ $gv->ma_giangvien }}')">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <a href="{{ route('admin.giangvien.edit', $gv->ma_giangvien) }}" 
                                                   class="btn btn-sm btn-outline-warning"
                                                   data-bs-toggle="tooltip" title="Sửa thông tin">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @if($gv->trang_thai == 'vo_hieu_hoa')
                                                    <button type="button" class="btn btn-sm btn-outline-success"
                                                            data-bs-toggle="tooltip" title="Kích hoạt"
                                                            onclick="enableAccount('{{ $gv->ma_giangvien }}', '{{ $gv->ten }}')">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                @else
                                                    <button type="button" class="btn btn-sm btn-outline-secondary"
                                                            data-bs-toggle="tooltip" title="Vô hiệu hóa"
                                                            onclick="disableAccount('{{ $gv->ma_giangvien }}', '{{ $gv->ten }}')">
                                                        <i class="fas fa-ban"></i>
                                                    </button>
                                                @endif
                                                <button type="button" class="btn btn-sm btn-outline-danger"
                                                        data-bs-toggle="tooltip" title="Xóa"
                                                        onclick="confirmDelete('{{ $gv->ma_giangvien }}', '{{ $gv->ten }}')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-muted">
                                Hiển thị {{ $giangViens->firstItem() }} đến {{ $giangViens->lastItem() }} 
                                trong tổng số {{ $giangViens->total() }} giảng viên
                            </div>
                            {{ $giangViens->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Không có giảng viên nào</h5>
                            <p class="text-muted mb-4">Hãy thêm giảng viên đầu tiên vào hệ thống</p>
                            <a href="{{ route('admin.giangvien.createGV') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Thêm giảng viên
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa giảng viên <strong id="deleteName"></strong>?</p>
                <p class="text-danger">Hành động này không thể hoàn tác!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Xóa</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Disable Account Modal -->
<div class="modal fade" id="disableModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Vô hiệu hóa tài khoản</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn vô hiệu hóa tài khoản của giảng viên <strong id="disableName"></strong>?</p>
                <p class="text-warning">Giảng viên sẽ không thể đăng nhập vào hệ thống.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <form id="disableForm" method="POST" style="display: inline;">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-warning">Vô hiệu hóa</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Enable Account Modal -->
<div class="modal fade" id="enableModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kích hoạt tài khoản</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn kích hoạt tài khoản của giảng viên <strong id="enableName"></strong>?</p>
                <p class="text-success">Giảng viên sẽ có thể đăng nhập vào hệ thống lại.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <form id="enableForm" method="POST" style="display: inline;">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-success">Kích hoạt</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Initialize tooltips
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
})

// Delete confirmation
function confirmDelete(maGiangVien, ten) {
    document.getElementById('deleteName').textContent = ten;
    document.getElementById('deleteForm').action = `/admin/giangvien/${maGiangVien}`;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

// Disable account confirmation
function disableAccount(maGiangVien, ten) {
    document.getElementById('disableName').textContent = ten;
    document.getElementById('disableForm').action = `/admin/giangvien/${maGiangVien}/disable`;
    new bootstrap.Modal(document.getElementById('disableModal')).show();
}

// Enable account confirmation
function enableAccount(maGiangVien, ten) {
    document.getElementById('enableName').textContent = ten;
    document.getElementById('enableForm').action = `/admin/giangvien/${maGiangVien}/enable`;
    new bootstrap.Modal(document.getElementById('enableModal')).show();
}

// View detail (you can implement this as needed)
function viewDetail(maGiangVien) {
    // Redirect to detail page or show modal
    window.location.href = `/admin/giangvien/${maGiangVien}`;
}

// Search functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('input[name="search"]');
    const searchForm = document.querySelector('form');
    
    // Optional: Auto-submit after 500ms of typing stopped
    let searchTimeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            searchForm.submit();
        }, 500);
    });
});
</script>

<style>
.avatar-sm {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.avatar-title {
    font-size: 16px;
    font-weight: bold;
}
.table th {
    border-top: none;
    font-weight: 600;
    background-color: #2c3e50;
    color: white;
}
.btn-group .btn {
    margin-right: 2px;
}
.badge {
    font-size: 0.75em;
}
.bg-pink {
    background-color: #ff69b4 !important;
}
.table td {
    vertical-align: middle;
}
.text-truncate {
    display: inline-block;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.fw-bold {
    font-weight: 600 !important;
}
.min-width-200 {
    min-width: 200px;
    flex: 1;
}
/* Đảm bảo nội dung hiển thị trên 1 dòng */
.table td div:not(.btn-group) {
    white-space: nowrap;
}
/* Tooltip cho tên khi bị cắt */
[title] {
    cursor: help;
}
</style>
@endpush