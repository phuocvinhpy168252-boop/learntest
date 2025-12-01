<?php

namespace App\Http\Controllers\GiangVien;

use App\Http\Controllers\Controller;
use App\Models\BaiKiemTra;
use App\Models\LopHoc;
use App\Models\GiangVien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BaiKiemTraQuanLyController extends Controller
{
    /**
     * Hiển thị danh sách tất cả bài kiểm tra của giảng viên
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $giangVien = GiangVien::where('email', $user->email)->first();
        
        if (!$giangVien) {
            return redirect()->route('login.form')->with('error', 'Không tìm thấy thông tin giảng viên');
        }

        // Lấy các lớp học của giảng viên
        $lopHocs = LopHoc::where('ma_giang_vien', $giangVien->ma_giangvien)
                         ->pluck('ma_lop')
                         ->toArray();

        // Query bài kiểm tra với tìm kiếm và lọc
        $query = BaiKiemTra::whereIn('ma_lop', $lopHocs)
                           ->with(['lopHoc', 'lopHoc.monHoc']);

        // Tìm kiếm theo tiêu đề
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('tieu_de', 'like', "%$search%")
                  ->orWhere('ma_bai_kiem_tra', 'like', "%$search%");
        }

        // Lọc theo trạng thái
        if ($request->has('trang_thai') && $request->trang_thai != '') {
            $query->where('trang_thai', $request->trang_thai);
        }

        // Lọc theo loại bài kiểm tra
        if ($request->has('loai_bai_kiem_tra') && $request->loai_bai_kiem_tra != '') {
            $query->where('loai_bai_kiem_tra', $request->loai_bai_kiem_tra);
        }

        // Lọc theo lớp học
        if ($request->has('ma_lop') && $request->ma_lop != '') {
            $query->where('ma_lop', $request->ma_lop);
        }

        $baiKiemTras = $query->orderBy('created_at', 'desc')->paginate(15);
        $totalBaiKiemTra = $query->count();

        // Lấy danh sách lớp học cho dropdown filter
        $danhSachLopHoc = LopHoc::where('ma_giang_vien', $giangVien->ma_giangvien)
                                ->orderBy('ten_lop')
                                ->get();

        // Thống kê
        $thongKe = [
            'tong' => BaiKiemTra::whereIn('ma_lop', $lopHocs)->count(),
            'cho_cong_bo' => BaiKiemTra::whereIn('ma_lop', $lopHocs)->where('trang_thai', 'cho_cong_bo')->count(),
            'dang_dien_ra' => BaiKiemTra::whereIn('ma_lop', $lopHocs)->where('trang_thai', 'dang_dien_ra')->count(),
            'da_ket_thuc' => BaiKiemTra::whereIn('ma_lop', $lopHocs)->where('trang_thai', 'da_ket_thuc')->count(),
        ];

        return view('giangvien.baikiemtra.quanly', compact(
            'baiKiemTras',
            'totalBaiKiemTra',
            'danhSachLopHoc',
            'giangVien',
            'thongKe'
        ));
    }
}
