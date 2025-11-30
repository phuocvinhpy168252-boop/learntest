<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GiangVienController;
use App\Http\Controllers\Admin\AdminGVController;
use App\Http\Controllers\Admin\AdminSinhVienController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminMonHocController; // THÊM DÒNG NÀY

Route::get('/', function () {
    return view('auth.login');
});

// Các route authentication
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::get('register', [AuthController::class, 'showRegisterForm'])->name('register.form');
Route::post('register', [AuthController::class, 'register'])->name('register'); 
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

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
    Route::view('/sinhvien', 'sinhvien.sinhvien')->name('sinhvien.dashboard');

            // Giảng viên routes (cho giảng viên)
        Route::get('/giangvien', [GiangVienController::class, 'dashboard'])->name('giangvien.dashboard');
        Route::get('/giangvien/baigiang', [GiangVienController::class, 'baigiang'])->name('giangvien.baigiang');
        Route::get('/giangvien/baikiemtra', [GiangVienController::class, 'baikiemtra'])->name('giangvien.baikiemtra');
        Route::get('/giangvien/nganhang', [GiangVienController::class, 'nganhang'])->name('giangvien.nganhang');
        Route::get('/giangvien/ketqua', [GiangVienController::class, 'ketqua'])->name('giangvien.ketqua');
        Route::get('/giangvien/sinhvien', [GiangVienController::class, 'sinhvien'])->name('giangvien.sinhvien');

        // THÊM CÁC ROUTES QUẢN LÝ LỚP HỌC CHO GIẢNG VIÊN
        Route::prefix('giangvien/lophoc')->name('giangvien.lophoc.')->group(function () {
            Route::get('/', [\App\Http\Controllers\GiangVien\LopHocController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\GiangVien\LopHocController::class, 'create'])->name('create');
            Route::post('/store', [\App\Http\Controllers\GiangVien\LopHocController::class, 'store'])->name('store');
            Route::get('/{ma_lop}', [\App\Http\Controllers\GiangVien\LopHocController::class, 'show'])->name('show');
            Route::get('/{ma_lop}/edit', [\App\Http\Controllers\GiangVien\LopHocController::class, 'edit'])->name('edit');
            Route::put('/{ma_lop}', [\App\Http\Controllers\GiangVien\LopHocController::class, 'update'])->name('update');
            Route::delete('/{ma_lop}', [\App\Http\Controllers\GiangVien\LopHocController::class, 'destroy'])->name('destroy');
        });

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