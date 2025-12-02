<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\GiangVienController;
use App\Http\Controllers\SinhVienController;
use App\Http\Controllers\Admin\AdminGVController;
use App\Http\Controllers\Admin\AdminSinhVienController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminMonHocController;

// Trang chủ công khai
Route::get('/', [HomeController::class, 'index'])->name('home');

// Route login mặc định
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login');

// DEBUG ROUTE - Thêm route debug để kiểm tra sinh viên
Route::get('/debug-sinhvien', function() {
    $sinhViens = \App\Models\SinhVien::all();
    return response()->json([
        'total' => $sinhViens->count(),
        'data' => $sinhViens
    ]);
});

// Các route authentication khác (giữ lại cho backward compatibility)
Route::get('/auth/login', [AuthController::class, 'showLoginForm'])->name('auth.login.form');
Route::post('/auth/login', [AuthController::class, 'login'])->name('auth.login');
Route::get('/auth/register', [AuthController::class, 'showRegisterForm'])->name('auth.register.form');
Route::post('/auth/register', [AuthController::class, 'register'])->name('auth.register'); 
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/logout', [AuthController::class, 'logoutGet'])->name('logout.get');

// Route tạm để tạo/reset admin
Route::get('/reset-admin', [AuthController::class, 'createAdminAccount']);

Route::get('/fix-gv-duplicate', function () {
    // Tìm tất cả mã GV trùng lặp trong bảng users
    $duplicateUsers = DB::table('users')
        ->select('ma_nguoi_dung', DB::raw('COUNT(*) as count'))
        ->where('ma_nguoi_dung', 'like', 'GV%')
        ->groupBy('ma_nguoi_dung')
        ->having('count', '>', 1)
        ->get();

    foreach ($duplicateUsers as $duplicate) {
        $users = DB::table('users')
            ->where('ma_nguoi_dung', $duplicate->ma_nguoi_dung)
            ->orderBy('id')
            ->get();
        
        // Giữ lại user đầu tiên, xóa các user sau
        for ($i = 1; $i < count($users); $i++) {
            DB::table('users')->where('id', $users[$i]->id)->delete();
        }
    }

    return "Đã fix lỗi trùng mã GV trong bảng users";
});

Route::middleware('auth')->group(function () {

     // Sinh viên routes
    Route::prefix('sinhvien')->name('sinhvien.')->group(function () {
        Route::get('/', [SinhVienController::class, 'khoaHoc'])->name('khoahoc');
        Route::get('/lophoc/{ma_lop}/baigiang', [SinhVienController::class, 'baiGiang'])->name('lophoc.baigiang');
        Route::get('/lophoc/{ma_lop}/baigiang/{id}', [SinhVienController::class, 'chiTietBaiGiang'])->name('lophoc.baigiang.chitiet');
        Route::get('/lophoc/{ma_lop}/baigiang/{id}/download', [SinhVienController::class, 'downloadBaiGiang'])->name('lophoc.baigiang.download');
        
        // Thêm route mới cho bài kiểm tra
        Route::get('/lophoc/{ma_lop}/baikiemtra', [SinhVienController::class, 'danhSachBaiKiemTra'])->name('lophoc.baikiemtra');
        Route::get('/lophoc/{ma_lop}/baikiemtra/{ma_bai_kiem_tra}', [SinhVienController::class, 'chiTietBaiKiemTra'])->name('lophoc.baikiemtra.chitiet');
        Route::get('/lophoc/{ma_lop}/baikiemtra/{ma_bai_kiem_tra}/lam-bai', [SinhVienController::class, 'lamBaiKiemTra'])->name('lophoc.baikiemtra.lambai');
        Route::post('/lophoc/{ma_lop}/baikiemtra/{ma_bai_kiem_tra}/nop-bai', [SinhVienController::class, 'nopBai'])->name('lophoc.baikiemtra.nopbai');
        Route::get('/lophoc/{ma_lop}/baikiemtra/{ma_bai_kiem_tra}/ket-qua', [SinhVienController::class, 'xemKetQua'])->name('lophoc.baikiemtra.ketqua');
    });

    // Giảng viên routes (cho giảng viên)
    Route::get('/giangvien', [GiangVienController::class, 'dashboard'])->name('giangvien.dashboard');
    Route::get('/giangvien/baikiemtra/quanly', [\App\Http\Controllers\GiangVien\BaiKiemTraQuanLyController::class, 'index'])->name('giangvien.baikiemtra.quanly');
    Route::get('/giangvien/baikiemtra', [GiangVienController::class, 'baikiemtra'])->name('giangvien.baikiemtra');
    Route::get('/giangvien/nganhang', [GiangVienController::class, 'nganhang'])->name('giangvien.nganhang');
    Route::get('/giangvien/ketqua', [GiangVienController::class, 'ketqua'])->name('giangvien.ketqua');
    Route::get('/giangvien/sinhvien', [GiangVienController::class, 'sinhvien'])->name('giangvien.sinhvien');

    // Routes bài giảng của tôi - THÊM MỚI
    Route::prefix('giangvien/baigiang-cua-toi')->name('giangvien.baigiangcuatoi.')->group(function () {
        Route::get('/', [\App\Http\Controllers\GiangVien\BaiGiangCuaToiController::class, 'index'])->name('index');
        Route::get('/thong-ke', [\App\Http\Controllers\GiangVien\BaiGiangCuaToiController::class, 'thongKe'])->name('thongke');
        Route::get('/{id}', [\App\Http\Controllers\GiangVien\BaiGiangCuaToiController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [\App\Http\Controllers\GiangVien\BaiGiangCuaToiController::class, 'edit'])->name('edit'); // THÊM
        Route::put('/{id}', [\App\Http\Controllers\GiangVien\BaiGiangCuaToiController::class, 'update'])->name('update'); // THÊM
        Route::delete('/{id}', [\App\Http\Controllers\GiangVien\BaiGiangCuaToiController::class, 'destroy'])->name('destroy'); // THÊM
    });

    // Route cũ cho bài giảng (redirect đến bài giảng của tôi)
    Route::get('/giangvien/baigiang', [\App\Http\Controllers\GiangVien\BaiGiangCuaToiController::class, 'index'])->name('giangvien.baigiang');

    // Routes quản lý lớp học cho giảng viên
    Route::prefix('giangvien/lophoc')->name('giangvien.lophoc.')->group(function () {
        Route::get('/', [\App\Http\Controllers\GiangVien\LopHocController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\GiangVien\LopHocController::class, 'create'])->name('create');
        Route::post('/store', [\App\Http\Controllers\GiangVien\LopHocController::class, 'store'])->name('store');
        Route::get('/{ma_lop}', [\App\Http\Controllers\GiangVien\LopHocController::class, 'show'])->name('show');
        Route::get('/{ma_lop}/edit', [\App\Http\Controllers\GiangVien\LopHocController::class, 'edit'])->name('edit');
        Route::put('/{ma_lop}', [\App\Http\Controllers\GiangVien\LopHocController::class, 'update'])->name('update');
        Route::delete('/{ma_lop}', [\App\Http\Controllers\GiangVien\LopHocController::class, 'destroy'])->name('destroy');

        // Quản lý sinh viên trong lớp học
        Route::prefix('{ma_lop}/sinhvien')->name('sinhvien.')->group(function () {
            Route::get('/', [\App\Http\Controllers\GiangVien\LopHocSinhVienController::class, 'index'])->name('index');
            Route::get('/them', [\App\Http\Controllers\GiangVien\LopHocSinhVienController::class, 'create'])->name('create');
            Route::post('/them', [\App\Http\Controllers\GiangVien\LopHocSinhVienController::class, 'store'])->name('store');
            Route::delete('/{ma_sinhvien}', [\App\Http\Controllers\GiangVien\LopHocSinhVienController::class, 'destroy'])->name('destroy');
            Route::get('/tim-kiem', [\App\Http\Controllers\GiangVien\LopHocSinhVienController::class, 'timKiemSinhVien'])->name('timkiem');
        });
    });

    // Routes quản lý bài giảng trong lớp học
    Route::prefix('giangvien/lophoc/{ma_lop}/baigiang')->name('giangvien.lophoc.baigiang.')->group(function () {
        Route::get('/', [\App\Http\Controllers\GiangVien\BaiGiangController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\GiangVien\BaiGiangController::class, 'create'])->name('create');
        Route::post('/store', [\App\Http\Controllers\GiangVien\BaiGiangController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [\App\Http\Controllers\GiangVien\BaiGiangController::class, 'edit'])->name('edit');
        Route::put('/{id}/update', [\App\Http\Controllers\GiangVien\BaiGiangController::class, 'update'])->name('update');
        Route::delete('/{id}/destroy', [\App\Http\Controllers\GiangVien\BaiGiangController::class, 'destroy'])->name('destroy');
    });
    // Routes quản lý bài kiểm tra
    Route::prefix('giangvien/lophoc/{ma_lop}/baikiemtra')->name('giangvien.lophoc.baikiemtra.')->group(function () {
        Route::get('/', [\App\Http\Controllers\GiangVien\BaiKiemTraController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\GiangVien\BaiKiemTraController::class, 'create'])->name('create');
        Route::post('/store', [\App\Http\Controllers\GiangVien\BaiKiemTraController::class, 'store'])->name('store');
        Route::get('/{id}', [\App\Http\Controllers\GiangVien\BaiKiemTraController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [\App\Http\Controllers\GiangVien\BaiKiemTraController::class, 'edit'])->name('edit');
        Route::put('/{id}/update', [\App\Http\Controllers\GiangVien\BaiKiemTraController::class, 'update'])->name('update');
        Route::delete('/{id}/destroy', [\App\Http\Controllers\GiangVien\BaiKiemTraController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/cong-bo', [\App\Http\Controllers\GiangVien\BaiKiemTraController::class, 'congBo'])->name('congbo');
    });
    // Trong routes/web.php, thêm route cho quản lý câu hỏi
    Route::prefix('giangvien/lophoc/{ma_lop}/baikiemtra/{id}/cauhoi')->name('giangvien.lophoc.baikiemtra.cauhoi.')->group(function () {
        Route::get('/', [\App\Http\Controllers\GiangVien\CauHoiController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\GiangVien\CauHoiController::class, 'create'])->name('create');
        Route::post('/store', [\App\Http\Controllers\GiangVien\CauHoiController::class, 'store'])->name('store');
        Route::get('/import', [\App\Http\Controllers\GiangVien\CauHoiImportController::class, 'showImportForm'])->name('import');
        Route::post('/import', [\App\Http\Controllers\GiangVien\CauHoiImportController::class, 'import'])->name('import.store');
        Route::get('/download-template', [\App\Http\Controllers\GiangVien\CauHoiImportController::class, 'downloadTemplate'])->name('download-template');
        Route::get('/{cauhoi_id}/edit', [\App\Http\Controllers\GiangVien\CauHoiController::class, 'edit'])->name('edit');
        Route::put('/{cauhoi_id}/update', [\App\Http\Controllers\GiangVien\CauHoiController::class, 'update'])->name('update');
        Route::delete('/{cauhoi_id}/destroy', [\App\Http\Controllers\GiangVien\CauHoiController::class, 'destroy'])->name('destroy');
    });

    // Admin routes
    Route::prefix('admin')->name('admin.')->group(function () {
        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        
        // Quản lý giảng viên - ĐẶT TRƯỚC các route có tham số
        Route::get('/giangvien', [AdminGVController::class, 'index'])->name('giangvien.index');
        Route::get('/giangvien/createGV', [AdminGVController::class, 'createGV'])->name('giangvien.createGV');
        Route::post('/giangvien/storeGV', [AdminGVController::class, 'storeGV'])->name('giangvien.storeGV');
        
        // Các route có tham số - ĐẶT SAU
        Route::get('/giangvien/{ma_giangvien}/edit', [AdminGVController::class, 'edit'])->name('giangvien.edit');
        Route::put('/giangvien/{ma_giangvien}', [AdminGVController::class, 'update'])->name('giangvien.update');
        Route::delete('/giangvien/{ma_giangvien}', [AdminGVController::class, 'destroy'])->name('giangvien.destroy');
        Route::patch('/giangvien/{ma_giangvien}/disable', [AdminGVController::class, 'disable'])->name('giangvien.disable');
        Route::patch('/giangvien/{ma_giangvien}/enable', [AdminGVController::class, 'enable'])->name('giangvien.enable');

        // Quản lý môn học
        Route::prefix('monhoc')->name('monhoc.')->group(function () {
            Route::get('/', [AdminMonHocController::class, 'index'])->name('index');
            Route::get('/create', [AdminMonHocController::class, 'create'])->name('create');
            Route::post('/store', [AdminMonHocController::class, 'store'])->name('store');
            Route::get('/{ma_mon_hoc}/edit', [AdminMonHocController::class, 'edit'])->name('edit');
            Route::put('/{ma_mon_hoc}/update', [AdminMonHocController::class, 'update'])->name('update');
            Route::delete('/{ma_mon_hoc}/destroy', [AdminMonHocController::class, 'destroy'])->name('destroy');
            Route::put('/{ma_mon_hoc}/disable', [AdminMonHocController::class, 'disable'])->name('disable');
            Route::put('/{ma_mon_hoc}/enable', [AdminMonHocController::class, 'enable'])->name('enable');
        });

        // Quản lý sinh viên
        Route::get('/sinhvien', [AdminSinhVienController::class, 'index'])->name('sinhvien.index');
        Route::get('/sinhvien/createSV', [AdminSinhVienController::class, 'createSV'])->name('sinhvien.createSV');
        Route::post('/sinhvien/storeSV', [AdminSinhVienController::class, 'storeSV'])->name('sinhvien.storeSV');        
        Route::get('/sinhvien/{ma_sinhvien}/edit', [AdminSinhVienController::class, 'edit'])->name('sinhvien.edit');
        Route::put('/sinhvien/{ma_sinhvien}', [AdminSinhVienController::class, 'update'])->name('sinhvien.update');
        Route::delete('/sinhvien/{ma_sinhvien}', [AdminSinhVienController::class, 'destroy'])->name('sinhvien.destroy');
        Route::patch('/sinhvien/{ma_sinhvien}/disable', [AdminSinhVienController::class, 'disable'])->name('sinhvien.disable');
        Route::patch('/sinhvien/{ma_sinhvien}/enable', [AdminSinhVienController::class, 'enable'])->name('sinhvien.enable');
        Route::get('/sinhvien/{ma_sinhvien}', [AdminSinhVienController::class, 'show'])->name('sinhvien.show');
    });
});