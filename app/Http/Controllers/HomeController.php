<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LopHoc;
use App\Models\GiangVien;
use App\Models\MonHoc;

class HomeController extends Controller
{
    /**
     * Hiển thị trang chủ
     */
    public function index()
    {
        // Lấy thông tin thống kê
        $tongLopHoc = LopHoc::where('trang_thai', 'dang_mo')->count();
        $tongGiangVien = GiangVien::where('trang_thai', 'hoat_dong')->count();
        $tongMonHoc = MonHoc::where('trang_thai', 'hoat_dong')->count();
        
        // Lấy lớp học nổi bật
        $lopHocNoiBat = LopHoc::where('trang_thai', 'dang_mo')
            ->with(['monHoc', 'giangVien'])
            ->limit(6)
            ->get();
            
        // Lấy giảng viên nổi bật
        $giangVienNoiBat = GiangVien::where('trang_thai', 'hoat_dong')
            ->limit(4)
            ->get();

        return view('home', compact(
            'tongLopHoc',
            'tongGiangVien',
            'tongMonHoc',
            'lopHocNoiBat',
            'giangVienNoiBat'
        ));
    }
}