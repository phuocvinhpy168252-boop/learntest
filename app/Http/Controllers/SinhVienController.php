<?php

namespace App\Http\Controllers;

use App\Models\LopHoc;
use App\Models\BaiGiang;
use App\Models\SinhVien;
use App\Models\DangKyLop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\BaiKiemTra;
use App\Models\KetQuaBaiKiemTra;
use App\Models\CauHoi;

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
    public function danhSachBaiKiemTra($ma_lop)
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
        
        // Lấy tất cả bài kiểm tra của lớp
        $baiKiemTras = BaiKiemTra::where('ma_lop', $ma_lop)
            ->where('trang_thai', '!=', 'da_huy')
            ->orderBy('thoi_gian_bat_dau', 'desc')
            ->get();

        // Kiểm tra xem sinh viên đã làm bài chưa
        foreach ($baiKiemTras as $baiKiemTra) {
            $baiKiemTra->da_lam = KetQuaBaiKiemTra::where('ma_sinhvien', $user->ma_nguoi_dung)
                ->where('ma_bai_kiem_tra', $baiKiemTra->ma_bai_kiem_tra)
                ->exists();
            
            if ($baiKiemTra->da_lam) {
                $ketQua = KetQuaBaiKiemTra::where('ma_sinhvien', $user->ma_nguoi_dung)
                    ->where('ma_bai_kiem_tra', $baiKiemTra->ma_bai_kiem_tra)
                    ->first();
                $baiKiemTra->diem = $ketQua->diem ?? null;
                $baiKiemTra->thoi_gian_nop = $ketQua->thoi_gian_nop ?? null;
            }
        }

        return view('sinhvien.baikiemtra.index', compact('lopHoc', 'baiKiemTras'));
    }

    /**
     * Xem chi tiết bài kiểm tra
     */
    public function chiTietBaiKiemTra($ma_lop, $ma_bai_kiem_tra)
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

        $lopHoc = LopHoc::where('ma_lop', $ma_lop)->firstOrFail();
        $baiKiemTra = BaiKiemTra::where('ma_lop', $ma_lop)
            ->where('ma_bai_kiem_tra', $ma_bai_kiem_tra)
            ->firstOrFail();

        // Kiểm tra đã làm bài chưa
        $daLam = KetQuaBaiKiemTra::where('ma_sinhvien', $user->ma_nguoi_dung)
            ->where('ma_bai_kiem_tra', $ma_bai_kiem_tra)
            ->exists();

        $ketQua = null;
        if ($daLam) {
            $ketQua = KetQuaBaiKiemTra::where('ma_sinhvien', $user->ma_nguoi_dung)
                ->where('ma_bai_kiem_tra', $ma_bai_kiem_tra)
                ->first();
        }

        return view('sinhvien.baikiemtra.chitiet', compact('lopHoc', 'baiKiemTra', 'daLam', 'ketQua'));
    }

    /**
     * Trang làm bài kiểm tra
     */
    public function lamBaiKiemTra($ma_lop, $ma_bai_kiem_tra)
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

        $lopHoc = LopHoc::where('ma_lop', $ma_lop)->firstOrFail();
        $baiKiemTra = BaiKiemTra::where('ma_lop', $ma_lop)
            ->where('ma_bai_kiem_tra', $ma_bai_kiem_tra)
            ->firstOrFail();

        // Kiểm tra điều kiện làm bài dựa trên thời gian thực
        $now = now();
        
        if ($now < $baiKiemTra->thoi_gian_bat_dau) {
            return redirect()->route('sinhvien.lophoc.baikiemtra.chitiet', [$ma_lop, $ma_bai_kiem_tra])
                ->with('error', 'Bài kiểm tra chưa bắt đầu!');
        }

        if ($now > $baiKiemTra->thoi_gian_ket_thuc) {
            return redirect()->route('sinhvien.lophoc.baikiemtra.chitiet', [$ma_lop, $ma_bai_kiem_tra])
                ->with('error', 'Bài kiểm tra đã hết thời gian làm bài!');
        }

        // Kiểm tra đã làm bài chưa
        $daLam = KetQuaBaiKiemTra::where('ma_sinhvien', $user->ma_nguoi_dung)
            ->where('ma_bai_kiem_tra', $ma_bai_kiem_tra)
            ->exists();

        if ($daLam) {
            return redirect()->route('sinhvien.lophoc.baikiemtra.chitiet', [$ma_lop, $ma_bai_kiem_tra])
                ->with('error', 'Bạn đã làm bài kiểm tra này rồi!');
        }

        // Lấy danh sách câu hỏi
        $cauHois = CauHoi::where('ma_bai_kiem_tra', $ma_bai_kiem_tra)
            ->where('trang_thai', 'active')
            ->orderBy('thu_tu')
            ->get();

        if ($cauHois->isEmpty()) {
            return redirect()->route('sinhvien.lophoc.baikiemtra.chitiet', [$ma_lop, $ma_bai_kiem_tra])
                ->with('error', 'Bài kiểm tra chưa có câu hỏi!');
        }

        // Xáo trộn câu hỏi nếu được cấu hình
        if ($baiKiemTra->cau_hinh && $baiKiemTra->cau_hinh['ngau_nhien_cau_hoi'] ?? false) {
            $cauHois = $cauHois->shuffle();
        }

        // Tính thời gian kết thúc
        $thoiGianKetThuc = $now->copy()->addMinutes($baiKiemTra->thoi_gian_lam_bai);
        if ($thoiGianKetThuc > $baiKiemTra->thoi_gian_ket_thuc) {
            $thoiGianKetThuc = $baiKiemTra->thoi_gian_ket_thuc;
        }

        return view('sinhvien.baikiemtra.lambai', compact(
            'lopHoc', 
            'baiKiemTra', 
            'cauHois',
            'thoiGianKetThuc'
        ));
    }

    /**
 * Nộp bài kiểm tra
 */
public function nopBai(Request $request, $ma_lop, $ma_bai_kiem_tra)
{
    $user = Auth::user();
    
    // Kiểm tra sinh viên có trong lớp không
    $daDangKy = DangKyLop::where('ma_sinhvien', $user->ma_nguoi_dung)
        ->where('ma_lop', $ma_lop)
        ->where('trang_thai', 'dang_hoc')
        ->exists();

    if (!$daDangKy) {
        return response()->json([
            'success' => false, 
            'message' => 'Bạn chưa đăng ký lớp học này!'
        ], 403);
    }

    $baiKiemTra = BaiKiemTra::where('ma_lop', $ma_lop)
        ->where('ma_bai_kiem_tra', $ma_bai_kiem_tra)
        ->first();

    if (!$baiKiemTra) {
        return response()->json([
            'success' => false, 
            'message' => 'Bài kiểm tra không tồn tại!'
        ], 404);
    }

    // Kiểm tra đã làm bài chưa
    $daLam = KetQuaBaiKiemTra::where('ma_sinhvien', $user->ma_nguoi_dung)
        ->where('ma_bai_kiem_tra', $ma_bai_kiem_tra)
        ->exists();

    if ($daLam) {
        return response()->json([
            'success' => false, 
            'message' => 'Bạn đã làm bài kiểm tra này rồi!'
        ], 400);
    }

    // Kiểm tra thời gian làm bài
    $now = now();
    if ($now < $baiKiemTra->thoi_gian_bat_dau) {
        return response()->json([
            'success' => false, 
            'message' => 'Bài kiểm tra chưa bắt đầu!'
        ], 400);
    }

    if ($now > $baiKiemTra->thoi_gian_ket_thuc) {
        return response()->json([
            'success' => false, 
            'message' => 'Bài kiểm tra đã hết thời gian làm bài!'
        ], 400);
    }

    try {
        // Lấy câu trả lời từ request
        $cauTraLois = $request->input('cau_tra_loi', []);
        
        // Kiểm tra nếu không có câu trả lời nào
        if (empty($cauTraLois)) {
            $cauTraLois = [];
        }
        
        // Lấy tất cả câu hỏi của bài kiểm tra
        $cauHois = CauHoi::where('ma_bai_kiem_tra', $ma_bai_kiem_tra)
            ->where('trang_thai', 'active')
            ->get();

        if ($cauHois->isEmpty()) {
            return response()->json([
                'success' => false, 
                'message' => 'Bài kiểm tra không có câu hỏi!'
            ], 400);
        }

        $tongDiem = 0;
        $chiTietCauTraLoi = [];
        $coTuLuanChuaCham = false;

        foreach ($cauHois as $cauHoi) {
            $diemCauHoi = 0;
            $traLoi = $cauTraLois[$cauHoi->id] ?? null;
            
            // Tính điểm theo loại câu hỏi
            if ($cauHoi->isTracNghiem()) {
                $dapAnDung = $cauHoi->getDapAnDung();
                if ($traLoi !== null && $traLoi !== '') {
                    $traLoiValue = (string)$traLoi;
                    $dapAnDungValue = $dapAnDung !== null ? (string)$dapAnDung : null;
                    
                    if ($traLoiValue === $dapAnDungValue) {
                        $diemCauHoi = floatval($cauHoi->diem);
                    }
                }
            } elseif ($cauHoi->isDungSai()) {
                $dapAnDung = $cauHoi->dap_an;
                if ($traLoi !== null && $traLoi !== '') {
                    $traLoiValue = (string)$traLoi;
                    $dapAnDungValue = $dapAnDung !== null ? (string)$dapAnDung : null;
                    
                    if ($traLoiValue === $dapAnDungValue) {
                        $diemCauHoi = floatval($cauHoi->diem);
                    }
                }
            } elseif ($cauHoi->isTuLuan()) {
                // Tự luận mặc định cho 0 điểm, cần giáo viên chấm sau
                $diemCauHoi = 0;
                $coTuLuanChuaCham = true;
                // Lưu câu trả lời tự luận
                $traLoi = trim($traLoi ?? '');
            }
            
            $tongDiem += $diemCauHoi;
            
            // Chuẩn bị chi tiết câu trả lời
            $chiTietCauTraLoi[] = [
                'cau_hoi_id' => $cauHoi->id,
                'ma_cau_hoi' => $cauHoi->ma_cau_hoi,
                'noi_dung' => $cauHoi->noi_dung,
                'loai_cau_hoi' => $cauHoi->loai_cau_hoi,
                'tra_loi' => $traLoi,
                'dap_an_dung' => $cauHoi->isTracNghiem() ? $cauHoi->getDapAnDung() : ($cauHoi->isDungSai() ? $cauHoi->dap_an : null),
                'diem' => $diemCauHoi,
                'diem_toi_da' => floatval($cauHoi->diem),
                'da_tra_loi' => $traLoi !== null && $traLoi !== ''
            ];
        }

        // Lấy thời gian làm bài từ form (tính bằng phút)
        $thoiGianLamPhut = intval($request->input('thoi_gian_lam', 0));
        
        // Nếu thời gian làm = 0, tính thời gian thực tế từ khi vào làm bài
        if ($thoiGianLamPhut <= 0) {
            $thoiGianLamPhut = 1;
        }
        
        // Chuyển đổi thời gian làm sang giây (cho trường thoi_gian_lam_bai trong DB)
        $thoiGianLamGiay = $thoiGianLamPhut * 60;
        
        // Tính thời gian bắt đầu (bây giờ trừ đi thời gian đã làm)
        $thoiGianBatDau = now()->subMinutes($thoiGianLamPhut);

        // Xác định trạng thái dựa trên loại câu hỏi
        // Trạng thái chỉ có thể là: 'dang_lam', 'da_hoan_thanh', 'da_cham_diem'
        $trangThai = 'da_hoan_thanh'; // Mặc định
        
        // Nếu có câu hỏi tự luận chưa chấm, vẫn để là 'da_hoan_thanh'
        // (Giảng viên sẽ chuyển sang 'da_cham_diem' sau khi chấm)
        // Không có trạng thái 'cho_cham_diem' trong database
        
        // Lưu kết quả
        $ketQua = new KetQuaBaiKiemTra();
        $ketQua->ma_ket_qua = 'KQ' . time() . rand(100, 999);
        $ketQua->ma_sinhvien = $user->ma_nguoi_dung;
        $ketQua->ma_bai_kiem_tra = $ma_bai_kiem_tra;
        $ketQua->ma_lop = $ma_lop;
        $ketQua->diem = $tongDiem;
        $ketQua->thoi_gian_bat_dau = $thoiGianBatDau;
        $ketQua->thoi_gian_nop = now();
        $ketQua->thoi_gian_lam_bai = $thoiGianLamGiay;
        $ketQua->chi_tiet_cau_tra_loi = json_encode($chiTietCauTraLoi, JSON_UNESCAPED_UNICODE);
        $ketQua->trang_thai = $trangThai;
        $ketQua->save();

        return response()->json([
            'success' => true,
            'message' => 'Nộp bài thành công!' . ($coTuLuanChuaCham ? ' Bài tự luận sẽ được giảng viên chấm điểm sau.' : ''),
            'diem' => $tongDiem,
            'redirect' => route('sinhvien.lophoc.baikiemtra.chitiet', [$ma_lop, $ma_bai_kiem_tra])
        ]);

    } catch (\Exception $e) {
        \Log::error('Lỗi khi nộp bài: ' . $e->getMessage(), [
            'user' => $user->ma_nguoi_dung,
            'bai_kiem_tra' => $ma_bai_kiem_tra,
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Có lỗi xảy ra khi nộp bài. Vui lòng thử lại! Error: ' . $e->getMessage()
        ], 500);
    }
}
    /**
     * Xem kết quả bài kiểm tra
     */
    public function xemKetQua($ma_lop, $ma_bai_kiem_tra)
    {
        $user = Auth::user();
        
        $lopHoc = LopHoc::where('ma_lop', $ma_lop)->firstOrFail();
        $baiKiemTra = BaiKiemTra::where('ma_lop', $ma_lop)
            ->where('ma_bai_kiem_tra', $ma_bai_kiem_tra)
            ->firstOrFail();

        $ketQua = KetQuaBaiKiemTra::where('ma_sinhvien', $user->ma_nguoi_dung)
            ->where('ma_bai_kiem_tra', $ma_bai_kiem_tra)
            ->firstOrFail();

        $chiTiet = json_decode($ketQua->chi_tiet_cau_tra_loi, true);

        return view('sinhvien.baikiemtra.ketqua', compact(
            'lopHoc',
            'baiKiemTra',
            'ketQua',
            'chiTiet'
        ));
    }
    
}