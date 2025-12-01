<?php

namespace App\Http\Controllers\GiangVien;

use App\Http\Controllers\Controller;
use App\Models\LopHoc;
use App\Models\MonHoc;
use App\Models\GiangVien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LopHocController extends Controller
{
    /**
     * Hiển thị danh sách lớp học của giảng viên
     */
    public function index()
    {
        // Lấy thông tin giảng viên đang đăng nhập
        $user = Auth::user();
        $giangVien = GiangVien::where('email', $user->email)->first();
        
        if (!$giangVien) {
            return redirect()->back()->with('error', 'Không tìm thấy thông tin giảng viên!');
        }

        $lopHocs = LopHoc::where('ma_giang_vien', $giangVien->ma_giangvien)
                         ->with('monHoc')
                         ->orderBy('created_at', 'desc')
                         ->paginate(10);

        return view('giangvien.lophoc.index', compact('lopHocs', 'giangVien'));
    }

    /**
     * Hiển thị form tạo lớp học mới
     */
    public function create()
    {
        $user = Auth::user();
        $giangVien = GiangVien::where('email', $user->email)->first();
        
        if (!$giangVien) {
            return redirect()->back()->with('error', 'Không tìm thấy thông tin giảng viên!');
        }

        // Lấy danh sách môn học mà giảng viên có thể dạy
        $monHocs = MonHoc::where('trang_thai', 'hoat_dong')
                         ->orderBy('ten_mon_hoc')
                         ->get();

        // Tạo mã lớp tự động
        $lastLop = LopHoc::orderBy('ma_lop', 'desc')->first();
        $nextMaLop = 'LOP001';
        
        if ($lastLop) {
            $lastNumber = intval(substr($lastLop->ma_lop, 3));
            $nextMaLop = 'LOP' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        }

        return view('giangvien.lophoc.create', compact('monHocs', 'giangVien', 'nextMaLop'));
    }

    /**
     * Lưu lớp học mới
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $giangVien = GiangVien::where('email', $user->email)->first();
        
        if (!$giangVien) {
            return redirect()->back()->with('error', 'Không tìm thấy thông tin giảng viên!');
        }

        $request->validate([
            'ten_lop' => 'required|string|max:255',
            'ma_mon_hoc' => 'required|exists:monhoc,ma_mon_hoc',
            'so_luong_sv' => 'required|integer|min:1|max:100',
            'mo_ta' => 'nullable|string|max:500',
            'phong_hoc' => 'required|string|max:100',
            'thoi_gian_hoc' => 'required|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            // Tạo mã lớp tự động
            $lastLop = LopHoc::orderBy('ma_lop', 'desc')->first();
            $maLop = 'LOP001';
            
            if ($lastLop) {
                $lastNumber = intval(substr($lastLop->ma_lop, 3));
                $maLop = 'LOP' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
            }

            // Tạo lớp học
            LopHoc::create([
                'ma_lop' => $maLop,
                'ten_lop' => $request->ten_lop,
                'ma_mon_hoc' => $request->ma_mon_hoc,
                'ma_giang_vien' => $giangVien->ma_giangvien,
                'so_luong_sv' => $request->so_luong_sv,
                'so_luong_sv_hien_tai' => 0,
                'mo_ta' => $request->mo_ta,
                'phong_hoc' => $request->phong_hoc,
                'thoi_gian_hoc' => $request->thoi_gian_hoc,
                'trang_thai' => 'dang_mo',
            ]);

            DB::commit();

            return redirect()->route('giangvien.lophoc.index')
                ->with('success', 'Tạo lớp học thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Hiển thị chi tiết lớp học
     */
    /**
 * Hiển thị chi tiết lớp học
 */
public function show($ma_lop)
{
    $user = Auth::user();
    $giangVien = GiangVien::where('email', $user->email)->first();
    
    $lopHoc = LopHoc::where('ma_lop', $ma_lop)
                    ->where('ma_giang_vien', $giangVien->ma_giangvien)
                    ->with(['monHoc', 'giangVien'])
                    ->firstOrFail();

    // Lấy thống kê bài giảng
    $tongBaiGiang = \App\Models\BaiGiang::where('ma_lop', $ma_lop)->count();
    
    // Lấy bài giảng mới nhất
    $baiGiangMoiNhat = \App\Models\BaiGiang::where('ma_lop', $ma_lop)
                          ->orderBy('created_at', 'desc')
                          ->limit(3)
                          ->get();

    return view('giangvien.lophoc.show', compact(
        'lopHoc', 
        'tongBaiGiang', 
        'baiGiangMoiNhat'
    ));
}

    /**
     * Hiển thị form chỉnh sửa lớp học
     */
    public function edit($ma_lop)
    {
        $user = Auth::user();
        $giangVien = GiangVien::where('email', $user->email)->first();
        
        $lopHoc = LopHoc::where('ma_lop', $ma_lop)
                        ->where('ma_giang_vien', $giangVien->ma_giangvien)
                        ->firstOrFail();

        $monHocs = MonHoc::where('trang_thai', 'hoat_dong')
                         ->orderBy('ten_mon_hoc')
                         ->get();

        return view('giangvien.lophoc.edit', compact('lopHoc', 'monHocs'));
    }

    /**
     * Cập nhật lớp học
     */
    public function update(Request $request, $ma_lop)
    {
        $user = Auth::user();
        $giangVien = GiangVien::where('email', $user->email)->first();
        
        $lopHoc = LopHoc::where('ma_lop', $ma_lop)
                        ->where('ma_giang_vien', $giangVien->ma_giangvien)
                        ->firstOrFail();

        $request->validate([
            'ten_lop' => 'required|string|max:255',
            'ma_mon_hoc' => 'required|exists:monhoc,ma_mon_hoc',
            'so_luong_sv' => 'required|integer|min:1|max:100',
            'mo_ta' => 'nullable|string|max:500',
            'phong_hoc' => 'required|string|max:100',
            'thoi_gian_hoc' => 'required|string|max:255',
            'trang_thai' => 'required|in:dang_mo,dang_hoc,da_ket_thuc',
        ]);

        // Kiểm tra nếu số lượng SV hiện tại > số lượng SV mới
        if ($request->so_luong_sv < $lopHoc->so_luong_sv_hien_tai) {
            return redirect()->back()
                ->with('error', 'Số lượng sinh viên mới không được nhỏ hơn số lượng hiện tại!')
                ->withInput();
        }

        $lopHoc->update([
            'ten_lop' => $request->ten_lop,
            'ma_mon_hoc' => $request->ma_mon_hoc,
            'so_luong_sv' => $request->so_luong_sv,
            'mo_ta' => $request->mo_ta,
            'phong_hoc' => $request->phong_hoc,
            'thoi_gian_hoc' => $request->thoi_gian_hoc,
            'trang_thai' => $request->trang_thai,
        ]);

        return redirect()->route('giangvien.lophoc.index')
            ->with('success', 'Cập nhật lớp học thành công!');
    }

    /**
     * Xóa lớp học
     */
    public function destroy($ma_lop)
    {
        $user = Auth::user();
        $giangVien = GiangVien::where('email', $user->email)->first();
        
        $lopHoc = LopHoc::where('ma_lop', $ma_lop)
                        ->where('ma_giang_vien', $giangVien->ma_giangvien)
                        ->firstOrFail();

        // Kiểm tra nếu lớp đã có sinh viên thì không cho xóa
        if ($lopHoc->so_luong_sv_hien_tai > 0) {
            return redirect()->back()
                ->with('error', 'Không thể xóa lớp học đã có sinh viên đăng ký!');
        }

        $lopHoc->delete();

        return redirect()->route('giangvien.lophoc.index')
            ->with('success', 'Xóa lớp học thành công!');
    }
}