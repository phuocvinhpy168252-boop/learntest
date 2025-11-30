<?php

namespace App\Http\Controllers\GiangVien;

use App\Http\Controllers\Controller;
use App\Models\BaiGiang;
use App\Models\LopHoc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BaiGiangController extends Controller
{
    public function index($ma_lop)
    {
        $user = Auth::user();
        $giangVien = \App\Models\GiangVien::where('email', $user->email)->first();
        
        $lopHoc = LopHoc::where('ma_lop', $ma_lop)
                        ->where('ma_giang_vien', $giangVien->ma_giangvien)
                        ->firstOrFail();

        $baiGiangs = BaiGiang::where('ma_lop', $ma_lop)
                            ->orderBy('thu_tu')
                            ->orderBy('created_at')
                            ->get();

        return view('giangvien.baigiang.index', compact('lopHoc', 'baiGiangs'));
    }

    public function create($ma_lop)
    {
        $user = Auth::user();
        $giangVien = \App\Models\GiangVien::where('email', $user->email)->first();
        
        $lopHoc = LopHoc::where('ma_lop', $ma_lop)
                        ->where('ma_giang_vien', $giangVien->ma_giangvien)
                        ->firstOrFail();

        return view('giangvien.baigiang.create', compact('lopHoc'));
    }

    public function store(Request $request, $ma_lop)
    {
        $user = Auth::user();
        $giangVien = \App\Models\GiangVien::where('email', $user->email)->first();
        
        $lopHoc = LopHoc::where('ma_lop', $ma_lop)
                        ->where('ma_giang_vien', $giangVien->ma_giangvien)
                        ->firstOrFail();

        $request->validate([
            'tieu_de' => 'required|string|max:255',
            'mo_ta' => 'nullable|string',
            'loai_bai_giang' => 'required|in:video,pdf,document,presentation,other',
            'file_upload' => 'nullable|file|max:10240', // 10MB max
            'url_video' => 'nullable|url',
            'thu_tu' => 'nullable|integer|min:0'
        ]);

        // Tạo mã bài giảng tự động
        $lastBaiGiang = BaiGiang::orderBy('ma_bai_giang', 'desc')->first();
        $maBaiGiang = 'BG001';
        if ($lastBaiGiang) {
            $lastNumber = intval(substr($lastBaiGiang->ma_bai_giang, 2));
            $maBaiGiang = 'BG' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        }

        $duongDanFile = null;
        if ($request->hasFile('file_upload')) {
            $file = $request->file('file_upload');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $duongDanFile = $file->storeAs('baigiang/' . $ma_lop, $fileName, 'public');
        }

        BaiGiang::create([
            'ma_bai_giang' => $maBaiGiang,
            'ma_lop' => $ma_lop,
            'tieu_de' => $request->tieu_de,
            'mo_ta' => $request->mo_ta,
            'loai_bai_giang' => $request->loai_bai_giang,
            'duong_dan_file' => $duongDanFile,
            'url_video' => $request->url_video,
            'thu_tu' => $request->thu_tu ?? 0,
            'trang_thai' => 'active'
        ]);

        return redirect()->route('giangvien.lophoc.baigiang.index', $ma_lop)
            ->with('success', 'Thêm bài giảng thành công!');
    }

    public function edit($ma_lop, $id)
    {
        $user = Auth::user();
        $giangVien = \App\Models\GiangVien::where('email', $user->email)->first();
        
        $lopHoc = LopHoc::where('ma_lop', $ma_lop)
                        ->where('ma_giang_vien', $giangVien->ma_giangvien)
                        ->firstOrFail();

        $baiGiang = BaiGiang::where('ma_lop', $ma_lop)
                            ->where('id', $id)
                            ->firstOrFail();

        return view('giangvien.baigiang.edit', compact('lopHoc', 'baiGiang'));
    }

    public function update(Request $request, $ma_lop, $id)
    {
        $user = Auth::user();
        $giangVien = \App\Models\GiangVien::where('email', $user->email)->first();
        
        $lopHoc = LopHoc::where('ma_lop', $ma_lop)
                        ->where('ma_giang_vien', $giangVien->ma_giangvien)
                        ->firstOrFail();

        $baiGiang = BaiGiang::where('ma_lop', $ma_lop)
                            ->where('id', $id)
                            ->firstOrFail();

        $request->validate([
            'tieu_de' => 'required|string|max:255',
            'mo_ta' => 'nullable|string',
            'loai_bai_giang' => 'required|in:video,pdf,document,presentation,other',
            'file_upload' => 'nullable|file|max:10240',
            'url_video' => 'nullable|url',
            'thu_tu' => 'nullable|integer|min:0'
        ]);

        $duongDanFile = $baiGiang->duong_dan_file;
        if ($request->hasFile('file_upload')) {
            // Xóa file cũ nếu tồn tại
            if ($duongDanFile && Storage::disk('public')->exists($duongDanFile)) {
                Storage::disk('public')->delete($duongDanFile);
            }

            $file = $request->file('file_upload');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $duongDanFile = $file->storeAs('baigiang/' . $ma_lop, $fileName, 'public');
        }

        $baiGiang->update([
            'tieu_de' => $request->tieu_de,
            'mo_ta' => $request->mo_ta,
            'loai_bai_giang' => $request->loai_bai_giang,
            'duong_dan_file' => $duongDanFile,
            'url_video' => $request->url_video,
            'thu_tu' => $request->thu_tu ?? 0
        ]);

        return redirect()->route('giangvien.lophoc.baigiang.index', $ma_lop)
            ->with('success', 'Cập nhật bài giảng thành công!');
    }

    public function destroy($ma_lop, $id)
    {
        $user = Auth::user();
        $giangVien = \App\Models\GiangVien::where('email', $user->email)->first();
        
        $lopHoc = LopHoc::where('ma_lop', $ma_lop)
                        ->where('ma_giang_vien', $giangVien->ma_giangvien)
                        ->firstOrFail();

        $baiGiang = BaiGiang::where('ma_lop', $ma_lop)
                            ->where('id', $id)
                            ->firstOrFail();

        // Xóa file vật lý nếu tồn tại
        if ($baiGiang->duong_dan_file && Storage::disk('public')->exists($baiGiang->duong_dan_file)) {
            Storage::disk('public')->delete($baiGiang->duong_dan_file);
        }

        $baiGiang->delete();

        return redirect()->route('giangvien.lophoc.baigiang.index', $ma_lop)
            ->with('success', 'Xóa bài giảng thành công!');
    }
}