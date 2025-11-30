<?php

namespace App\Http\Controllers\GiangVien;

use App\Http\Controllers\Controller;
use App\Models\LopHoc;
use App\Models\SinhVien;
use App\Models\DangKyLop;
use App\Models\GiangVien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LopHocSinhVienController extends Controller
{
    /**
     * Hiển thị danh sách sinh viên trong lớp
     */
    public function index($ma_lop)
    {
        $user = Auth::user();
        $giangVien = GiangVien::where('email', $user->email)->first();
        
        if (!$giangVien) {
            return redirect()->back()->with('error', 'Không tìm thấy thông tin giảng viên!');
        }

        $lopHoc = LopHoc::where('ma_lop', $ma_lop)
                        ->where('ma_giang_vien', $giangVien->ma_giangvien)
                        ->with(['monHoc', 'giangVien'])
                        ->firstOrFail();

        // SỬA: Sử dụng dangKyLop() thay vì dangKyLops()
        $sinhViens = SinhVien::whereHas('dangKyLop', function($query) use ($ma_lop) {
            $query->where('ma_lop', $ma_lop)
                ->where('trang_thai', 'dang_hoc');
        })->get();

        return view('giangvien.lophoc.sinhvien.index', compact('lopHoc', 'sinhViens'));
    }

    /**
     * Hiển thị form thêm sinh viên
     */
    public function create($ma_lop)
    {
        $user = Auth::user();
        $giangVien = GiangVien::where('email', $user->email)->first();
        
        if (!$giangVien) {
            return redirect()->back()->with('error', 'Không tìm thấy thông tin giảng viên!');
        }

        $lopHoc = LopHoc::where('ma_lop', $ma_lop)
                        ->where('ma_giang_vien', $giangVien->ma_giangvien)
                        ->firstOrFail();

        // Kiểm tra lớp còn chỗ trống không
        if (!$lopHoc->conChoTrong()) {
            return redirect()->route('giangvien.lophoc.sinhvien.index', $ma_lop)
                ->with('error', 'Lớp học đã đầy, không thể thêm sinh viên!');
        }

        return view('giangvien.lophoc.sinhvien.create', compact('lopHoc'));
    }

    /**
     * Tìm kiếm sinh viên
     */
    /**
 * Tìm kiếm sinh viên
 */
public function timKiemSinhVien(Request $request, $ma_lop)
{
    try {
        $keyword = $request->get('keyword');
        
        \Log::info('Bắt đầu tìm kiếm sinh viên', [
            'keyword' => $keyword,
            'ma_lop' => $ma_lop
        ]);

        // Kiểm tra lớp học có tồn tại không
        $lopHoc = LopHoc::where('ma_lop', $ma_lop)->first();
        if (!$lopHoc) {
            \Log::error('Lớp học không tồn tại: ' . $ma_lop);
            return response()->json(['error' => 'Lớp học không tồn tại'], 404);
        }

        $sinhViens = SinhVien::where(function($query) use ($keyword) {
            $query->where('ma_sinhvien', 'like', "%{$keyword}%")
                ->orWhere('ten', 'like', "%{$keyword}%")
                ->orWhere('email', 'like', "%{$keyword}%")
                ->orWhere('so_dien_thoai', 'like', "%{$keyword}%");
        })
        ->where('trang_thai', 'hoat_dong')
        ->whereDoesntHave('dangKyLop', function($query) use ($ma_lop) {
            $query->where('ma_lop', $ma_lop)
                ->where('trang_thai', 'dang_hoc');
        })
        ->select(['ma_sinhvien', 'ten', 'email', 'so_dien_thoai', 'lop', 'trang_thai'])
        ->limit(10)
        ->get();

        \Log::info('Kết quả tìm kiếm', [
            'keyword' => $keyword,
            'count' => $sinhViens->count(),
            'results' => $sinhViens->toArray()
        ]);

        return response()->json($sinhViens);

    } catch (\Exception $e) {
        \Log::error('Lỗi tìm kiếm sinh viên: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString()
        ]);
        return response()->json(['error' => 'Lỗi server: ' . $e->getMessage()], 500);
    }
}

    /**
     * Thêm sinh viên vào lớp
     */
    public function store(Request $request, $ma_lop)
    {
        $user = Auth::user();
        $giangVien = GiangVien::where('email', $user->email)->first();
        
        if (!$giangVien) {
            return redirect()->back()->with('error', 'Không tìm thấy thông tin giảng viên!');
        }

        $lopHoc = LopHoc::where('ma_lop', $ma_lop)
                        ->where('ma_giang_vien', $giangVien->ma_giangvien)
                        ->firstOrFail();

        $request->validate([
            'ma_sinhvien' => 'required|exists:sinhvien,ma_sinhvien'
        ]);

        // Kiểm tra lớp còn chỗ trống
        if (!$lopHoc->conChoTrong()) {
            return redirect()->back()
                ->with('error', 'Lớp học đã đầy, không thể thêm sinh viên!')
                ->withInput();
        }

        // Kiểm tra sinh viên đã đăng ký lớp chưa
        $daDangKy = DangKyLop::where('ma_sinhvien', $request->ma_sinhvien)
                            ->where('ma_lop', $ma_lop)
                            ->exists();

        if ($daDangKy) {
            return redirect()->back()
                ->with('error', 'Sinh viên đã có trong lớp học!')
                ->withInput();
        }

        DB::beginTransaction();

        try {
            // Thêm sinh viên vào lớp
            DangKyLop::create([
                'ma_sinhvien' => $request->ma_sinhvien,
                'ma_lop' => $ma_lop,
                'trang_thai' => 'dang_hoc'
            ]);

            // Cập nhật số lượng sinh viên hiện tại
            $lopHoc->increment('so_luong_sv_hien_tai');

            DB::commit();

            return redirect()->route('giangvien.lophoc.sinhvien.index', $ma_lop)
                ->with('success', 'Thêm sinh viên vào lớp học thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Xóa sinh viên khỏi lớp
     */
    public function destroy($ma_lop, $ma_sinhvien)
    {
        $user = Auth::user();
        $giangVien = GiangVien::where('email', $user->email)->first();
        
        if (!$giangVien) {
            return redirect()->back()->with('error', 'Không tìm thấy thông tin giảng viên!');
        }

        $lopHoc = LopHoc::where('ma_lop', $ma_lop)
                        ->where('ma_giang_vien', $giangVien->ma_giangvien)
                        ->firstOrFail();

        DB::beginTransaction();

        try {
            // Xóa đăng ký
            $deleted = DangKyLop::where('ma_sinhvien', $ma_sinhvien)
                    ->where('ma_lop', $ma_lop)
                    ->delete();

            if ($deleted) {
                // Cập nhật số lượng sinh viên hiện tại
                $lopHoc->decrement('so_luong_sv_hien_tai');
            }

            DB::commit();

            return redirect()->route('giangvien.lophoc.sinhvien.index', $ma_lop)
                ->with('success', 'Xóa sinh viên khỏi lớp học thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}