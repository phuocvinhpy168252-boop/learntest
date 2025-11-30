<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GiangVien;
use App\Models\User;
use App\Models\MonHoc; // Thêm model MonHoc
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminGVController extends Controller
{
    /**
     * Hiển thị danh sách giảng viên
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        $giangViens = GiangVien::when($search, function ($query, $search) {
            return $query->where('ma_giangvien', 'like', "%{$search}%")
                        ->orWhere('ten', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('mon_day', 'like', "%{$search}%");
        })
        ->orderBy('created_at', 'desc')
        ->paginate(10);

        return view('admin.giangvien.index', compact('giangViens', 'search'));
    }

    public function createGV()
    {
        // Lấy mã giảng viên cuối cùng và tạo mã tiếp theo
        $lastGV = GiangVien::orderBy('ma_giangvien', 'desc')->first();
        $nextMaGV = 'GV001';
        
        if ($lastGV) {
            $lastNumber = intval(substr($lastGV->ma_giangvien, 2));
            $nextMaGV = 'GV' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        }

        // Lấy danh sách môn học từ bảng mon_hoc
        $monHocs = MonHoc::where('trang_thai', 'hoat_dong')
                         ->orderBy('ten_mon_hoc')
                         ->get();
        
        return view('admin.giangvien.create', compact('nextMaGV', 'monHocs'));
    }

    /**
     * Lưu giảng viên mới
     */
    public function storeGV(Request $request)
    {
        $request->validate([
            'ten' => 'required|string|max:255',
            'email' => 'required|email|unique:giangvien,email|unique:users,email',
            'so_dien_thoai' => 'required|string|max:20',
            'mon_day' => 'required|string|max:255',
            'trinh_do' => 'required|string|max:100',
            'ngay_sinh' => 'required|date',
            'gioi_tinh' => 'required|in:nam,nu,khac',
            'dia_chi' => 'nullable|string|max:255',
            'password' => 'required|string|min:6|confirmed',
        ]);

        DB::beginTransaction();

        try {
            // Tạo mã giảng viên tự động
            $lastGV = GiangVien::orderBy('ma_giangvien', 'desc')->first();
            $maGiangVien = 'GV001';
            
            if ($lastGV) {
                $lastNumber = intval(substr($lastGV->ma_giangvien, 2));
                $maGiangVien = 'GV' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
            }

            // Tạo mật khẩu từ input
            $password = Hash::make($request->password);

            // Tạo giảng viên trong bảng giangvien
            $giangVien = GiangVien::create([
                'ma_giangvien' => $maGiangVien,
                'ten' => $request->ten,
                'so_dien_thoai' => $request->so_dien_thoai,
                'email' => $request->email,
                'dia_chi' => $request->dia_chi,
                'mon_day' => $request->mon_day,
                'loai_tai_khoan' => 'giangvien',
                'password' => $password,
                'ngay_sinh' => $request->ngay_sinh,
                'gioi_tinh' => $request->gioi_tinh,
                'trinh_do' => $request->trinh_do,
                'trang_thai' => 'hoat_dong',
            ]);

            // Trigger sẽ tự động tạo user trong bảng users

            DB::commit();

            return redirect()->route('admin.giangvien.index')
                ->with('success', 'Thêm giảng viên thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit($ma_giangvien)
    {
        $giangVien = GiangVien::where('ma_giangvien', $ma_giangvien)->firstOrFail();
        
        // Lấy danh sách môn học từ bảng mon_hoc
        $monHocs = MonHoc::where('trang_thai', 'hoat_dong')
                         ->orderBy('ten_mon_hoc')
                         ->get();
        
        return view('admin.giangvien.edit', compact('giangVien', 'monHocs'));
    }

    /**
     * Cập nhật thông tin giảng viên
     */
    public function update(Request $request, $ma_giangvien)
    {
        $giangVien = GiangVien::where('ma_giangvien', $ma_giangvien)->firstOrFail();

        $request->validate([
            'ten' => 'required',
            'email' => 'required|email|unique:giangvien,email,' . $ma_giangvien . ',ma_giangvien',
            'so_dien_thoai' => 'required',
            'mon_day' => 'required',
            'trinh_do' => 'required',
            'ngay_sinh' => 'required|date',
            'gioi_tinh' => 'required|in:nam,nu,khac',
            'dia_chi' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            // Lấy email cũ trước khi cập nhật
            $oldEmail = $giangVien->email;

            // Cập nhật bảng giangvien
            $giangVien->update([
                'ten' => $request->ten,
                'email' => $request->email,
                'so_dien_thoai' => $request->so_dien_thoai,
                'dia_chi' => $request->dia_chi,
                'mon_day' => $request->mon_day,
                'ngay_sinh' => $request->ngay_sinh,
                'gioi_tinh' => $request->gioi_tinh,
                'trinh_do' => $request->trinh_do,
            ]);

            // Cập nhật bảng users
            $user = User::where('email', $oldEmail)->first();
            if ($user) {
                $user->update([
                    'hoten' => $request->ten,
                    'sdt' => $request->so_dien_thoai,
                    'email' => $request->email,
                    'diachi' => $request->dia_chi,
                ]);
            }

            DB::commit();

            return redirect()->route('admin.giangvien.index')
                ->with('success', 'Cập nhật thông tin giảng viên thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Xóa giảng viên
     */
    public function destroy($ma_giangvien)
    {
        $giangVien = GiangVien::where('ma_giangvien', $ma_giangvien)->firstOrFail();

        DB::beginTransaction();

        try {
            // Lấy email trước khi xóa
            $email = $giangVien->email;

            // Xóa từ bảng giangvien
            $giangVien->delete();

            // Xóa từ bảng users
            $user = User::where('email', $email)->first();
            if ($user) {
                $user->delete();
            }

            DB::commit();

            return redirect()->route('admin.giangvien.index')
                ->with('success', 'Xóa giảng viên thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.giangvien.index')
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Vô hiệu hóa tài khoản giảng viên
     */
    public function disable($ma_giangvien)
    {
        $giangVien = GiangVien::where('ma_giangvien', $ma_giangvien)->firstOrFail();

        DB::beginTransaction();

        try {
            // Cập nhật trạng thái trong bảng giangvien
            $giangVien->update([
                'trang_thai' => 'vo_hieu_hoa'
            ]);

            // Cập nhật trạng thái trong bảng users
            $user = User::where('email', $giangVien->email)->first();
            if ($user) {
                $user->update([
                    'trang_thai' => 'vo_hieu_hoa'
                ]);
            }

            DB::commit();

            return redirect()->route('admin.giangvien.index')
                ->with('success', 'Vô hiệu hóa tài khoản giảng viên thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.giangvien.index')
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Kích hoạt lại tài khoản giảng viên
     */
    public function enable($ma_giangvien)
    {
        $giangVien = GiangVien::where('ma_giangvien', $ma_giangvien)->firstOrFail();

        DB::beginTransaction();

        try {
            // Cập nhật trạng thái trong bảng giangvien
            $giangVien->update([
                'trang_thai' => 'hoat_dong'
            ]);

            // Cập nhật trạng thái trong bảng users
            $user = User::where('email', $giangVien->email)->first();
            if ($user) {
                $user->update([
                    'trang_thai' => 'hoat_dong'
                ]);
            }

            DB::commit();

            return redirect()->route('admin.giangvien.index')
                ->with('success', 'Kích hoạt tài khoản giảng viên thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.giangvien.index')
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Hiển thị chi tiết giảng viên
     */
    public function show($ma_giangvien)
    {
        $giangVien = GiangVien::where('ma_giangvien', $ma_giangvien)->firstOrFail();
        return view('admin.giangvien.show', compact('giangVien'));
    }
}