<?php

namespace App\Http\Controllers;

use App\Models\LopHoc;
use App\Models\BaiGiang;
use App\Models\SinhVien;
use App\Models\DangKyLop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SinhVienController extends Controller
{
    public function dashboard()
    {
        // Lấy thông tin sinh viên đang đăng nhập
        $user = Auth::user();
        $sinhVien = SinhVien::where('ma_sinhvien', $user->ma_nguoi_dung)->first();
        
        // Lấy số lượng lớp học sinh viên đã đăng ký
        $soLopHoc = DangKyLop::where('ma_sinhvien', $user->ma_nguoi_dung)
            ->where('trang_thai', 'dang_hoc')
            ->count();

        return view('sinhvien.dashboard', compact('sinhVien', 'soLopHoc'));
    }

    public function khoaHoc()
    {
        // Lấy thông tin sinh viên đang đăng nhập
        $user = Auth::user();
        $sinhVien = SinhVien::where('ma_sinhvien', $user->ma_nguoi_dung)->first();
        
        // Lấy danh sách lớp học mà sinh viên đã đăng ký
        $lopHocs = LopHoc::whereHas('dangKyLop', function($query) use ($user) {
            $query->where('ma_sinhvien', $user->ma_nguoi_dung)
                ->where('trang_thai', 'dang_hoc');
        })
        ->with(['monHoc', 'giangVien'])
        ->get();

        return view('sinhvien.khoahoc', compact('lopHocs', 'sinhVien'));
    }

    public function baiGiang($ma_lop)
    {
        $user = Auth::user();
        
        // Kiểm tra sinh viên có trong lớp không
        $daDangKy = DangKyLop::where('ma_sinhvien', $user->ma_nguoi_dung)
            ->where('ma_lop', $ma_lop)
            ->where('trang_thai', 'dang_hoc')
            ->exists();

        if (!$daDangKy) {
            return redirect()->route('sinhvien.khoahoc')
                ->with('error', 'Bạn chưa đăng ký lớp học này!');
        }

        $lopHoc = LopHoc::with(['monHoc', 'giangVien'])->where('ma_lop', $ma_lop)->firstOrFail();
        
        $baiGiangs = BaiGiang::where('ma_lop', $ma_lop)
            ->where('trang_thai', 'active')
            ->orderBy('thu_tu')
            ->get();

        return view('sinhvien.baigiang', compact('lopHoc', 'baiGiangs'));
    }

    public function chiTietBaiGiang($ma_lop, $id)
    {
        $user = Auth::user();
        
        // Kiểm tra sinh viên có trong lớp không
        $daDangKy = DangKyLop::where('ma_sinhvien', $user->ma_nguoi_dung)
            ->where('ma_lop', $ma_lop)
            ->where('trang_thai', 'dang_hoc')
            ->exists();

        if (!$daDangKy) {
            return redirect()->route('sinhvien.khoahoc')
                ->with('error', 'Bạn chưa đăng ký lớp học này!');
        }

        $lopHoc = LopHoc::with(['monHoc', 'giangVien'])->where('ma_lop', $ma_lop)->firstOrFail();
        
        $baiGiang = BaiGiang::where('ma_lop', $ma_lop)
            ->where('id', $id)
            ->where('trang_thai', 'active')
            ->firstOrFail();

        // Lấy tất cả bài giảng cho sidebar
        $baiGiangs = BaiGiang::where('ma_lop', $ma_lop)
            ->where('trang_thai', 'active')
            ->orderBy('thu_tu')
            ->get();

        // Lấy bài giảng tiếp theo và trước đó
        $baiGiangTruoc = BaiGiang::where('ma_lop', $ma_lop)
            ->where('thu_tu', '<', $baiGiang->thu_tu)
            ->where('trang_thai', 'active')
            ->orderBy('thu_tu', 'desc')
            ->first();

        $baiGiangTiepTheo = BaiGiang::where('ma_lop', $ma_lop)
            ->where('thu_tu', '>', $baiGiang->thu_tu)
            ->where('trang_thai', 'active')
            ->orderBy('thu_tu', 'asc')
            ->first();

        return view('sinhvien.chitiet-baigiang', compact(
            'lopHoc', 
            'baiGiang', 
            'baiGiangs',
            'baiGiangTruoc', 
            'baiGiangTiepTheo'
        ));
    }
}