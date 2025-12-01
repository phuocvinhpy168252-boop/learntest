<?php

namespace App\Http\Controllers\GiangVien;

use App\Http\Controllers\Controller;
use App\Models\BaiGiang;
use App\Models\LopHoc;
use App\Models\GiangVien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BaiGiangCuaToiController extends Controller
{
    /**
     * Hiển thị danh sách tất cả bài giảng của giảng viên
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $giangVien = GiangVien::where('email', $user->email)->first();
        
        if (!$giangVien) {
            return redirect()->back()->with('error', 'Không tìm thấy thông tin giảng viên!');
        }

        // Lấy các lớp học của giảng viên
        $lopHocs = LopHoc::where('ma_giang_vien', $giangVien->ma_giangvien)
                         ->pluck('ma_lop');

        // Query bài giảng với tìm kiếm và lọc
        $query = BaiGiang::whereIn('ma_lop', $lopHocs)
                        ->with('lopHoc.monHoc')
                        ->orderBy('created_at', 'desc');

        // Tìm kiếm theo tiêu đề
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('tieu_de', 'like', "%{$search}%")
                  ->orWhere('mo_ta', 'like', "%{$search}%")
                  ->orWhere('ma_bai_giang', 'like', "%{$search}%");
            });
        }

        // Lọc theo loại bài giảng
        if ($request->has('loai_bai_giang') && $request->loai_bai_giang != '') {
            $query->where('loai_bai_giang', $request->loai_bai_giang);
        }

        // Lọc theo lớp học
        if ($request->has('ma_lop') && $request->ma_lop != '') {
            $query->where('ma_lop', $request->ma_lop);
        }

        $baiGiangs = $query->paginate(10);
        $totalBaiGiang = $query->count();

        // Lấy danh sách lớp học cho dropdown filter
        $danhSachLopHoc = LopHoc::where('ma_giang_vien', $giangVien->ma_giangvien)
                                ->with('monHoc')
                                ->get();

        return view('giangvien.baigiangcuatoi.index', compact(
            'baiGiangs', 
            'totalBaiGiang',
            'danhSachLopHoc',
            'giangVien'
        ));
    }

    /**
     * Xem chi tiết bài giảng
     */
    public function show($id)
    {
        $user = Auth::user();
        $giangVien = GiangVien::where('email', $user->email)->first();
        
        if (!$giangVien) {
            return redirect()->back()->with('error', 'Không tìm thấy thông tin giảng viên!');
        }

        $baiGiang = BaiGiang::where('id', $id)
                           ->with('lopHoc.monHoc', 'lopHoc.giangVien')
                           ->firstOrFail();

        // Kiểm tra bài giảng có thuộc về giảng viên không
        $lopHocCuaGiangVien = LopHoc::where('ma_lop', $baiGiang->ma_lop)
                                   ->where('ma_giang_vien', $giangVien->ma_giangvien)
                                   ->exists();

        if (!$lopHocCuaGiangVien) {
            return redirect()->back()->with('error', 'Bạn không có quyền truy cập bài giảng này!');
        }

        return view('giangvien.baigiangcuatoi.show', compact('baiGiang'));
    }

    /**
     * Thống kê bài giảng
     */
    public function thongKe()
    {
        $user = Auth::user();
        $giangVien = GiangVien::where('email', $user->email)->first();
        
        if (!$giangVien) {
            return redirect()->back()->with('error', 'Không tìm thấy thông tin giảng viên!');
        }

        // Lấy các lớp học của giảng viên
        $lopHocs = LopHoc::where('ma_giang_vien', $giangVien->ma_giangvien)
                         ->pluck('ma_lop');

        // Thống kê tổng quan
        $tongBaiGiang = BaiGiang::whereIn('ma_lop', $lopHocs)->count();
        
        $thongKeTheoLoai = BaiGiang::whereIn('ma_lop', $lopHocs)
                                  ->selectRaw('loai_bai_giang, COUNT(*) as so_luong')
                                  ->groupBy('loai_bai_giang')
                                  ->get();

        // Bài giảng mới nhất
        $baiGiangMoiNhat = BaiGiang::whereIn('ma_lop', $lopHocs)
                                  ->with('lopHoc.monHoc')
                                  ->orderBy('created_at', 'desc')
                                  ->limit(5)
                                  ->get();

        // Thống kê theo lớp
        $thongKeTheoLop = BaiGiang::whereIn('ma_lop', $lopHocs)
                                 ->selectRaw('ma_lop, COUNT(*) as so_luong')
                                 ->groupBy('ma_lop')
                                 ->with('lopHoc.monHoc')
                                 ->get();

        return view('giangvien.baigiangcuatoi.thongke', compact(
            'tongBaiGiang',
            'thongKeTheoLoai',
            'baiGiangMoiNhat',
            'thongKeTheoLop',
            'giangVien'
        ));
    }
    public function edit($id)
    {
        $user = Auth::user();
        $giangVien = GiangVien::where('email', $user->email)->first();
        
        if (!$giangVien) {
            return redirect()->back()->with('error', 'Không tìm thấy thông tin giảng viên!');
        }

        $baiGiang = BaiGiang::where('id', $id)
                           ->with('lopHoc.monHoc', 'lopHoc.giangVien')
                           ->firstOrFail();

        // Kiểm tra bài giảng có thuộc về giảng viên không
        $lopHocCuaGiangVien = LopHoc::where('ma_lop', $baiGiang->ma_lop)
                                   ->where('ma_giang_vien', $giangVien->ma_giangvien)
                                   ->exists();

        if (!$lopHocCuaGiangVien) {
            return redirect()->back()->with('error', 'Bạn không có quyền chỉnh sửa bài giảng này!');
        }

        return view('giangvien.baigiangcuatoi.edit', compact('baiGiang'));
    }

    /**
     * Cập nhật bài giảng
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $giangVien = GiangVien::where('email', $user->email)->first();
        
        if (!$giangVien) {
            return redirect()->back()->with('error', 'Không tìm thấy thông tin giảng viên!');
        }

        $baiGiang = BaiGiang::where('id', $id)->firstOrFail();

        // Kiểm tra bài giảng có thuộc về giảng viên không
        $lopHocCuaGiangVien = LopHoc::where('ma_lop', $baiGiang->ma_lop)
                                   ->where('ma_giang_vien', $giangVien->ma_giangvien)
                                   ->exists();

        if (!$lopHocCuaGiangVien) {
            return redirect()->back()->with('error', 'Bạn không có quyền cập nhật bài giảng này!');
        }

        $request->validate([
            'tieu_de' => 'required|string|max:255',
            'mo_ta' => 'nullable|string',
            'loai_bai_giang' => 'required|in:video,pdf,document,presentation,other',
            'file_upload' => 'nullable|file|max:10240',
            'url_video' => 'nullable|url',
            'thu_tu' => 'nullable|integer|min:0'
        ]);

        $baiGiang->update([
            'tieu_de' => $request->tieu_de,
            'mo_ta' => $request->mo_ta,
            'loai_bai_giang' => $request->loai_bai_giang,
            'url_video' => $request->url_video,
            'thu_tu' => $request->thu_tu ?? 0
        ]);

        return redirect()->route('giangvien.baigiangcuatoi.show', $baiGiang->id)
            ->with('success', 'Cập nhật bài giảng thành công!');
    }

    /**
     * Xóa bài giảng
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $giangVien = GiangVien::where('email', $user->email)->first();
        
        if (!$giangVien) {
            return redirect()->back()->with('error', 'Không tìm thấy thông tin giảng viên!');
        }

        $baiGiang = BaiGiang::where('id', $id)->firstOrFail();

        // Kiểm tra bài giảng có thuộc về giảng viên không
        $lopHocCuaGiangVien = LopHoc::where('ma_lop', $baiGiang->ma_lop)
                                   ->where('ma_giang_vien', $giangVien->ma_giangvien)
                                   ->exists();

        if (!$lopHocCuaGiangVien) {
            return redirect()->back()->with('error', 'Bạn không có quyền xóa bài giảng này!');
        }

        // Xóa file vật lý nếu tồn tại
        if ($baiGiang->duong_dan_file && Storage::disk('public')->exists($baiGiang->duong_dan_file)) {
            Storage::disk('public')->delete($baiGiang->duong_dan_file);
        }

        $baiGiang->delete();

        return redirect()->route('giangvien.baigiangcuatoi.index')
            ->with('success', 'Xóa bài giảng thành công!');
    }
}