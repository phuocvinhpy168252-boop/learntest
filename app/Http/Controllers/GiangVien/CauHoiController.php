<?php

namespace App\Http\Controllers\GiangVien;

use App\Http\Controllers\Controller;
use App\Models\BaiKiemTra;
use App\Models\CauHoi;
use App\Models\LopHoc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CauHoiController extends Controller
{
    /**
     * Hiển thị danh sách câu hỏi của bài kiểm tra
     */
    public function index($ma_lop, $id)
    {
        $user = Auth::user();
        $giangVien = \App\Models\GiangVien::where('email', $user->email)->first();
        
        $lopHoc = LopHoc::where('ma_lop', $ma_lop)
                        ->where('ma_giang_vien', $giangVien->ma_giangvien)
                        ->firstOrFail();

        $baiKiemTra = BaiKiemTra::where('ma_lop', $ma_lop)
                               ->where('id', $id)
                               ->firstOrFail();

        $cauHois = CauHoi::where('ma_bai_kiem_tra', $baiKiemTra->ma_bai_kiem_tra)
                         ->theoThuTu()
                         ->paginate(20);

        return view('giangvien.cauhoi.index', compact('lopHoc', 'baiKiemTra', 'cauHois'));
    }

    /**
     * Hiển thị form tạo câu hỏi mới
     */
    public function create($ma_lop, $id)
    {
        $user = Auth::user();
        $giangVien = \App\Models\GiangVien::where('email', $user->email)->first();
        
        $lopHoc = LopHoc::where('ma_lop', $ma_lop)
                        ->where('ma_giang_vien', $giangVien->ma_giangvien)
                        ->firstOrFail();

        $baiKiemTra = BaiKiemTra::where('ma_lop', $ma_lop)
                               ->where('id', $id)
                               ->firstOrFail();

        return view('giangvien.cauhoi.create', compact('lopHoc', 'baiKiemTra'));
    }

    public function store(Request $request, $ma_lop, $id)
    {
        $user = Auth::user();
        $giangVien = \App\Models\GiangVien::where('email', $user->email)->first();
        
        $baiKiemTra = BaiKiemTra::where('ma_lop', $ma_lop)
                            ->where('id', $id)
                            ->firstOrFail();

        // Validation
        $validated = $request->validate([
            'noi_dung' => 'required|string',
            'loai_cau_hoi' => 'required|in:trac_nghiem,tu_luan,dung_sai',
            'diem' => 'required|numeric|min:0.5|max:10',
            'thu_tu' => 'nullable|integer|min:0',
            'lua_chon' => 'nullable|array',
            'dap_an_dung' => 'nullable|integer|min:0',
            'dap_an' => 'nullable|in:0,1',
            'dapAnMau' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            // Tạo mã câu hỏi tự động
            $lastCauHoi = CauHoi::orderBy('ma_cau_hoi', 'desc')->first();
            $maCauHoi = 'CH001';
            if ($lastCauHoi) {
                $lastNumber = intval(substr($lastCauHoi->ma_cau_hoi, 2));
                $maCauHoi = 'CH' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
            }

            // Xử lý dữ liệu cho câu hỏi
            $cauTraLoiJson = null;
            $dapAn = null;

            if ($request->loai_cau_hoi === 'trac_nghiem') {
                // Trắc nghiệm: Lưu đáp án và đáp án đúng
                $luaChon = array_filter($request->lua_chon ?? []);
                if (count($luaChon) >= 2) {
                    $luaChon = array_values($luaChon); // Re-index
                    // Pass array directly - Eloquent will handle JSON encoding via the cast
                    $cauTraLoiJson = [
                        'lua_chon' => $luaChon,
                        'dap_an_dung' => (int)($request->dap_an_dung ?? 0)
                    ];
                }
            } elseif ($request->loai_cau_hoi === 'tu_luan') {
                // Tự luận: Lưu đáp án mẫu
                if ($request->dapAnMau) {
                    $cauTraLoiJson = [
                        'dap_an_mau' => $request->dapAnMau
                    ];
                }
            } elseif ($request->loai_cau_hoi === 'dung_sai') {
                // Đúng/Sai: Lưu đáp án (0 = Sai, 1 = Đúng)
                $dapAn = (int)($request->dap_an ?? 0);
            }

            CauHoi::create([
                'ma_cau_hoi' => $maCauHoi,
                'ma_bai_kiem_tra' => $baiKiemTra->ma_bai_kiem_tra,
                'noi_dung' => $request->noi_dung,
                'loai_cau_hoi' => $request->loai_cau_hoi,
                'dap_an' => $dapAn,
                'cau_tra_loi_json' => $cauTraLoiJson,
                'diem' => (float)$request->diem,
                'thu_tu' => (int)($request->thu_tu ?? 0),
                'trang_thai' => 'active'
            ]);

            // Cập nhật số câu hỏi trong bài kiểm tra
            $baiKiemTra->increment('so_cau_hoi');

            DB::commit();

            return redirect()->route('giangvien.lophoc.baikiemtra.cauhoi.index', [$ma_lop, $id])
                ->with('success', 'Thêm câu hỏi thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Hiển thị form chỉnh sửa câu hỏi
     */
    public function edit($ma_lop, $id, $cauhoi_id)
    {
        $user = Auth::user();
        $giangVien = \App\Models\GiangVien::where('email', $user->email)->first();
        
        $lopHoc = LopHoc::where('ma_lop', $ma_lop)
                        ->where('ma_giang_vien', $giangVien->ma_giangvien)
                        ->firstOrFail();

        $baiKiemTra = BaiKiemTra::where('ma_lop', $ma_lop)
                               ->where('id', $id)
                               ->firstOrFail();

        $cauHoi = CauHoi::where('ma_bai_kiem_tra', $baiKiemTra->ma_bai_kiem_tra)
                        ->where('id', $cauhoi_id)
                        ->firstOrFail();

        return view('giangvien.cauhoi.edit', compact('lopHoc', 'baiKiemTra', 'cauHoi'));
    }

    /**
     * Cập nhật câu hỏi
     */
    public function update(Request $request, $ma_lop, $id, $cauhoi_id)
    {
        $user = Auth::user();
        $giangVien = \App\Models\GiangVien::where('email', $user->email)->first();
        
        $baiKiemTra = BaiKiemTra::where('ma_lop', $ma_lop)
                               ->where('id', $id)
                               ->firstOrFail();

        $cauHoi = CauHoi::where('ma_bai_kiem_tra', $baiKiemTra->ma_bai_kiem_tra)
                        ->where('id', $cauhoi_id)
                        ->firstOrFail();

        $request->validate([
            'noi_dung' => 'required|string',
            'loai_cau_hoi' => 'required|in:trac_nghiem,tu_luan,dung_sai',
            'diem' => 'required|numeric|min:0.5|max:10',
            'thu_tu' => 'nullable|integer|min:0',
        ]);

        $cauTraLoiJson = null;
        $dapAn = null;

        if ($request->loai_cau_hoi === 'trac_nghiem') {
            $request->validate([
                'lua_chon' => 'required|array|min:2',
                'lua_chon.*' => 'required|string',
                'dap_an_dung' => 'required|integer|min:0',
            ]);

            $luaChon = array_filter($request->lua_chon);
            $luaChon = array_values($luaChon); // Re-index
            // Pass array directly - Eloquent will handle JSON encoding via the cast
            $cauTraLoiJson = [
                'lua_chon' => $luaChon,
                'dap_an_dung' => (int)$request->dap_an_dung
            ];
        } elseif ($request->loai_cau_hoi === 'dung_sai') {
            $request->validate([
                'dap_an' => 'required|in:0,1',
            ]);
            $dapAn = (int)$request->dap_an;
        } else {
            if ($request->has('dapAnMau') && $request->dapAnMau) {
                $cauTraLoiJson = [
                    'dap_an_mau' => $request->dapAnMau
                ];
            }
        }

        $cauHoi->update([
            'noi_dung' => $request->noi_dung,
            'loai_cau_hoi' => $request->loai_cau_hoi,
            'dap_an' => $dapAn,
            'cau_tra_loi_json' => $cauTraLoiJson,
            'diem' => (float)$request->diem,
            'thu_tu' => (int)($request->thu_tu ?? 0)
        ]);

        return redirect()->route('giangvien.lophoc.baikiemtra.cauhoi.index', [$ma_lop, $id])
            ->with('success', 'Cập nhật câu hỏi thành công!');
    }

    /**
     * Xóa câu hỏi
     */
    public function destroy($ma_lop, $id, $cauhoi_id)
    {
        $user = Auth::user();
        $giangVien = \App\Models\GiangVien::where('email', $user->email)->first();
        
        $baiKiemTra = BaiKiemTra::where('ma_lop', $ma_lop)
                               ->where('id', $id)
                               ->firstOrFail();

        $cauHoi = CauHoi::where('ma_bai_kiem_tra', $baiKiemTra->ma_bai_kiem_tra)
                        ->where('id', $cauhoi_id)
                        ->firstOrFail();

        DB::beginTransaction();

        try {
            $cauHoi->delete();
            
            // Cập nhật số câu hỏi trong bài kiểm tra
            $baiKiemTra->decrement('so_cau_hoi');

            DB::commit();

            return redirect()->route('giangvien.lophoc.baikiemtra.cauhoi.index', [$ma_lop, $id])
                ->with('success', 'Xóa câu hỏi thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}