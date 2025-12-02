<?php

namespace App\Http\Controllers\GiangVien;

use App\Http\Controllers\Controller;
use App\Models\BaiKiemTra;
use App\Models\LopHoc;
use App\Models\GiangVien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BaiKiemTraController extends Controller
{
    /**
     * Hiển thị danh sách bài kiểm tra của lớp
     */
    public function index($ma_lop)
    {
        $user = Auth::user();
        $giangVien = GiangVien::where('email', $user->email)->first();
        
        $lopHoc = LopHoc::where('ma_lop', $ma_lop)
                        ->where('ma_giang_vien', $giangVien->ma_giangvien)
                        ->with('monHoc')
                        ->firstOrFail();

        $baiKiemTras = BaiKiemTra::where('ma_lop', $ma_lop)
                                ->orderBy('created_at', 'desc')
                                ->paginate(10);

        return view('giangvien.baikiemtra.index', compact('lopHoc', 'baiKiemTras'));
    }

    /**
     * Hiển thị form tạo bài kiểm tra mới
     */
    public function create($ma_lop)
    {
        $user = Auth::user();
        $giangVien = GiangVien::where('email', $user->email)->first();
        
        $lopHoc = LopHoc::where('ma_lop', $ma_lop)
                        ->where('ma_giang_vien', $giangVien->ma_giangvien)
                        ->firstOrFail();

        // Tạo mã bài kiểm tra tự động
        $lastBaiKT = BaiKiemTra::orderBy('ma_bai_kiem_tra', 'desc')->first();
        $maBaiKT = 'BKT001';
        if ($lastBaiKT) {
            $lastNumber = intval(substr($lastBaiKT->ma_bai_kiem_tra, 3));
            $maBaiKT = 'BKT' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        }

        return view('giangvien.baikiemtra.create', compact('lopHoc', 'maBaiKT'));
    }

    /**
     * Lưu bài kiểm tra mới
     */
    /**
 * Lưu bài kiểm tra mới
 */
    public function store(Request $request, $ma_lop)
    {
        $user = Auth::user();
        $giangVien = GiangVien::where('email', $user->email)->first();
        
        $lopHoc = LopHoc::where('ma_lop', $ma_lop)
                        ->where('ma_giang_vien', $giangVien->ma_giangvien)
                        ->firstOrFail();

        $request->validate([
            'tieu_de' => 'required|string|max:255',
            'mo_ta' => 'nullable|string',
            'loai_bai_kiem_tra' => 'required|in:trac_nghiem,tu_luan,hop',
            'thoi_gian_lam_bai' => 'required|integer|min:1|max:180',
            'diem_toi_da' => 'required|integer|min:1|max:100',
            'thoi_gian_bat_dau' => 'required|date|after:now',
            'thoi_gian_ket_thuc' => 'required|date|after:thoi_gian_bat_dau',
        ]);

        DB::beginTransaction();

        try {
            // Tạo mã bài kiểm tra tự động
            $lastBaiKT = BaiKiemTra::orderBy('ma_bai_kiem_tra', 'desc')->first();
            $maBaiKT = 'BKT001';
            if ($lastBaiKT) {
                $lastNumber = intval(substr($lastBaiKT->ma_bai_kiem_tra, 3));
                $maBaiKT = 'BKT' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
            }

            // Tạo cấu hình
            $cauHinh = [
                'cho_phep_quay_lai' => $request->has('cho_phep_quay_lai') ? true : false,
                'hien_thi_diem' => $request->has('hien_thi_diem') ? true : false,
                'ngau_nhien_cau_hoi' => $request->has('ngau_nhien_cau_hoi') ? true : false,
            ];

            BaiKiemTra::create([
                'ma_bai_kiem_tra' => $maBaiKT,
                'ma_lop' => $ma_lop,
                'tieu_de' => $request->tieu_de,
                'mo_ta' => $request->mo_ta,
                'loai_bai_kiem_tra' => $request->loai_bai_kiem_tra,
                'thoi_gian_lam_bai' => $request->thoi_gian_lam_bai,
                'so_cau_hoi' => 0, // Sẽ cập nhật sau khi thêm câu hỏi
                'diem_toi_da' => $request->diem_toi_da,
                'thoi_gian_bat_dau' => $request->thoi_gian_bat_dau,
                'thoi_gian_ket_thuc' => $request->thoi_gian_ket_thuc,
                'trang_thai' => 'cho_cong_bo',
                'cau_hinh' => $cauHinh
            ]);

            DB::commit();

            return redirect()->route('giangvien.lophoc.baikiemtra.index', $ma_lop)
                ->with('success', 'Tạo bài kiểm tra thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Hiển thị chi tiết bài kiểm tra
     */
    public function show($ma_lop, $id)
    {
        $user = Auth::user();
        $giangVien = GiangVien::where('email', $user->email)->first();
        
        $lopHoc = LopHoc::where('ma_lop', $ma_lop)
                        ->where('ma_giang_vien', $giangVien->ma_giangvien)
                        ->firstOrFail();

        $baiKiemTra = BaiKiemTra::where('ma_lop', $ma_lop)
                               ->where('id', $id)
                               ->with(['lopHoc.monHoc', 'cauHois'])
                               ->firstOrFail();

        return view('giangvien.baikiemtra.show', compact('lopHoc', 'baiKiemTra'));
    }

    /**
     * Hiển thị form chỉnh sửa bài kiểm tra
     */
    public function edit($ma_lop, $id)
    {
        $user = Auth::user();
        $giangVien = GiangVien::where('email', $user->email)->first();
        
        $lopHoc = LopHoc::where('ma_lop', $ma_lop)
                        ->where('ma_giang_vien', $giangVien->ma_giangvien)
                        ->firstOrFail();

        $baiKiemTra = BaiKiemTra::where('ma_lop', $ma_lop)
                               ->where('id', $id)
                               ->firstOrFail();

        // Kiểm tra có thể chỉnh sửa không
        if (!$baiKiemTra->coTheChinhSua()) {
            return redirect()->back()
                ->with('error', 'Bài kiểm tra này không thể chỉnh sửa!');
        }

        return view('giangvien.baikiemtra.edit', compact('lopHoc', 'baiKiemTra'));
    }

    /**
     * Cập nhật bài kiểm tra
     */
    /**
 * Cập nhật bài kiểm tra
 */
    public function update(Request $request, $ma_lop, $id)
    {
        $user = Auth::user();
        $giangVien = GiangVien::where('email', $user->email)->first();
        
        $lopHoc = LopHoc::where('ma_lop', $ma_lop)
                        ->where('ma_giang_vien', $giangVien->ma_giangvien)
                        ->firstOrFail();

        $baiKiemTra = BaiKiemTra::where('ma_lop', $ma_lop)
                            ->where('id', $id)
                            ->firstOrFail();

        // Kiểm tra có thể chỉnh sửa không
        if (!$baiKiemTra->coTheChinhSua()) {
            return redirect()->back()
                ->with('error', 'Bài kiểm tra này không thể chỉnh sửa!');
        }

        $request->validate([
            'tieu_de' => 'required|string|max:255',
            'mo_ta' => 'nullable|string',
            'loai_bai_kiem_tra' => 'required|in:trac_nghiem,tu_luan,hop',
            'thoi_gian_lam_bai' => 'required|integer|min:1|max:180',
            'diem_toi_da' => 'required|integer|min:1|max:100',
            'thoi_gian_bat_dau' => 'required|date',
            'thoi_gian_ket_thuc' => 'required|date|after:thoi_gian_bat_dau',
        ]);

        // Tạo cấu hình
        $cauHinh = [
            'cho_phep_quay_lai' => $request->has('cho_phep_quay_lai') ? true : false,
            'hien_thi_diem' => $request->has('hien_thi_diem') ? true : false,
            'ngau_nhien_cau_hoi' => $request->has('ngau_nhien_cau_hoi') ? true : false,
        ];

        $baiKiemTra->update([
            'tieu_de' => $request->tieu_de,
            'mo_ta' => $request->mo_ta,
            'loai_bai_kiem_tra' => $request->loai_bai_kiem_tra,
            'thoi_gian_lam_bai' => $request->thoi_gian_lam_bai,
            'diem_toi_da' => $request->diem_toi_da,
            'thoi_gian_bat_dau' => $request->thoi_gian_bat_dau,
            'thoi_gian_ket_thuc' => $request->thoi_gian_ket_thuc,
            'cau_hinh' => $cauHinh
        ]);

        return redirect()->route('giangvien.lophoc.baikiemtra.show', [$ma_lop, $id])
            ->with('success', 'Cập nhật bài kiểm tra thành công!');
    }

    /**
     * Xóa bài kiểm tra
     */
    public function destroy($ma_lop, $id)
    {
        $user = Auth::user();
        $giangVien = GiangVien::where('email', $user->email)->first();
        
        $lopHoc = LopHoc::where('ma_lop', $ma_lop)
                        ->where('ma_giang_vien', $giangVien->ma_giangvien)
                        ->firstOrFail();

        $baiKiemTra = BaiKiemTra::where('ma_lop', $ma_lop)
                               ->where('id', $id)
                               ->firstOrFail();

        // Kiểm tra có thể xóa không
        if (!$baiKiemTra->coTheChinhSua()) {
            return redirect()->back()
                ->with('error', 'Bài kiểm tra này không thể xóa!');
        }

        $baiKiemTra->delete();

        return redirect()->route('giangvien.lophoc.baikiemtra.index', $ma_lop)
            ->with('success', 'Xóa bài kiểm tra thành công!');
    }

    /**
     * Công bố bài kiểm tra
     */
    public function congBo($ma_lop, $id)
    {
        $user = Auth::user();
        $giangVien = GiangVien::where('email', $user->email)->first();
        
        $baiKiemTra = BaiKiemTra::where('ma_lop', $ma_lop)
                               ->where('id', $id)
                               ->firstOrFail();

        // Kiểm tra có câu hỏi không
        if ($baiKiemTra->so_cau_hoi === 0) {
            return redirect()->back()
                ->with('error', 'Bài kiểm tra phải có ít nhất 1 câu hỏi!');
        }

        $baiKiemTra->update([
            'trang_thai' => 'dang_dien_ra'
        ]);

        return redirect()->back()
            ->with('success', 'Công bố bài kiểm tra thành công!');
    }
}