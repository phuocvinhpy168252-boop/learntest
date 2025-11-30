<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // THÊM DÒNG NÀY
use App\Models\GiangVien;
use App\Models\LopHoc;

class GiangVienController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $giangVien = GiangVien::where('email', $user->email)->first();
        
        if (!$giangVien) {
            return redirect()->back()->with('error', 'Không tìm thấy thông tin giảng viên!');
        }

        // Thống kê
        $tongLopHoc = LopHoc::where('ma_giang_vien', $giangVien->ma_giangvien)->count();
        $lopDangMo = LopHoc::where('ma_giang_vien', $giangVien->ma_giangvien)
                           ->where('trang_thai', 'dang_mo')
                           ->count();
        
        // Lớp học gần đây
        $lopHocGanDay = LopHoc::where('ma_giang_vien', $giangVien->ma_giangvien)
                              ->with('monHoc')
                              ->orderBy('created_at', 'desc')
                              ->take(5)
                              ->get();

        return view('giangvien.dashboard', compact(
            'tongLopHoc', 
            'lopDangMo', 
            'lopHocGanDay'
        ));
    }

    public function baigiang()
    {
        return view('giangvien.baigiang');
    }

    public function baikiemtra()
    {
        return view('giangvien.baikiemtra');
    }

    public function nganhang()
    {
        return view('giangvien.nganhang');
    }

    public function ketqua()
    {
        return view('giangvien.ketqua');
    }

    public function sinhvien()
    {
        return view('giangvien.sinhvien');
    }
}