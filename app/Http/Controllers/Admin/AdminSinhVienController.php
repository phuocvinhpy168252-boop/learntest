<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SinhVien;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminSinhVienController extends Controller
{
    /**
     * Hiển thị danh sách sinh viên
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        $sinhViens = SinhVien::when($search, function ($query, $search) {
            return $query->where('ma_sinhvien', 'like', "%{$search}%")
                        ->orWhere('ten', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('lop', 'like', "%{$search}%");
        })
        ->orderBy('created_at', 'desc')
        ->paginate(10);

        return view('admin.sinhvien.index', compact('sinhViens', 'search'));
    }

    /**
     * Hiển thị form thêm sinh viên
     */
    public function createSV()
    {
        // Tạo mã sinh viên tự động
        $lastSinhVien = SinhVien::orderBy('ma_sinhvien', 'desc')->first();
        $nextMaSV = 'SV001';
        
        if ($lastSinhVien && !empty($lastSinhVien->ma_sinhvien)) {
            $lastNumber = intval(substr($lastSinhVien->ma_sinhvien, 2));
            $nextNumber = $lastNumber + 1;
            $nextMaSV = 'SV' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        }

        return view('admin.sinhvien.createSV', compact('nextMaSV'));
    }

    /**
     * Lưu sinh viên mới
     */
    public function storeSV(Request $request)
    {
        $request->validate([
            'ten' => 'required',
            'email' => 'required|email|unique:sinhvien,email|unique:users,email',
            'so_dien_thoai' => 'required',
            'password' => 'required|min:6|confirmed',
            'lop' => 'required',
            'khoa' => 'required',
        ]);

        DB::beginTransaction();

        try {
            // Tạo mã sinh viên tự động
            $lastSinhVien = SinhVien::orderBy('ma_sinhvien', 'desc')->first();
            $maSinhVien = 'SV001';
            
            if ($lastSinhVien && !empty($lastSinhVien->ma_sinhvien)) {
                $lastNumber = intval(substr($lastSinhVien->ma_sinhvien, 2));
                $nextNumber = $lastNumber + 1;
                $maSinhVien = 'SV' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
            }

            // 1. Tạo bản ghi trong bảng sinhvien TRƯỚC (để trigger hoạt động)
            $sinhVien = SinhVien::create([
                'ma_sinhvien' => $maSinhVien,
                'ten' => $request->ten,
                'email' => $request->email,
                'so_dien_thoai' => $request->so_dien_thoai,
                'dia_chi' => $request->dia_chi,
                'ngay_sinh' => $request->ngay_sinh,
                'gioi_tinh' => $request->gioi_tinh,
                'lop' => $request->lop,
                'khoa' => $request->khoa,
                'loai_tai_khoan' => 'sinhvien',
                'trang_thai' => 'hoat_dong',
                'password' => Hash::make($request->password),
            ]);

            DB::commit();

            return redirect()->route('admin.sinhvien.index')
                ->with('success', 'Thêm sinh viên thành công! Mã sinh viên: ' . $maSinhVien);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Hiển thị form sửa sinh viên
     */
    public function edit($ma_sinhvien)
    {
        $sinhVien = SinhVien::where('ma_sinhvien', $ma_sinhvien)->firstOrFail();
        return view('admin.sinhvien.edit', compact('sinhVien'));
    }

    /**
     * Cập nhật thông tin sinh viên
     */
    public function update(Request $request, $ma_sinhvien)
    {
        $sinhVien = SinhVien::where('ma_sinhvien', $ma_sinhvien)->firstOrFail();

        $request->validate([
            'ten' => 'required',
            'email' => 'required|email|unique:sinhvien,email,' . $ma_sinhvien . ',ma_sinhvien',
            'so_dien_thoai' => 'required',
            'lop' => 'required',
            'khoa' => 'required',
        ]);

        DB::beginTransaction();

        try {
            // Lấy email cũ trước khi cập nhật
            $oldEmail = $sinhVien->email;

            // Cập nhật bảng sinhvien
            $sinhVien->update([
                'ten' => $request->ten,
                'email' => $request->email,
                'so_dien_thoai' => $request->so_dien_thoai,
                'dia_chi' => $request->dia_chi,
                'ngay_sinh' => $request->ngay_sinh,
                'gioi_tinh' => $request->gioi_tinh,
                'lop' => $request->lop,
                'khoa' => $request->khoa,
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

            return redirect()->route('admin.sinhvien.index')
                ->with('success', 'Cập nhật thông tin sinh viên thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Xóa sinh viên
     */
    public function destroy($ma_sinhvien)
    {
        $sinhVien = SinhVien::where('ma_sinhvien', $ma_sinhvien)->firstOrFail();

        DB::beginTransaction();

        try {
            // Lấy email trước khi xóa
            $email = $sinhVien->email;

            // Xóa từ bảng sinhvien
            $sinhVien->delete();

            // Xóa từ bảng users
            $user = User::where('email', $email)->first();
            if ($user) {
                $user->delete();
            }

            DB::commit();

            return redirect()->route('admin.sinhvien.index')
                ->with('success', 'Xóa sinh viên thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.sinhvien.index')
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Vô hiệu hóa tài khoản sinh viên
     */
    public function disable($ma_sinhvien)
    {
        $sinhVien = SinhVien::where('ma_sinhvien', $ma_sinhvien)->firstOrFail();

        DB::beginTransaction();

        try {
            // Cập nhật trạng thái trong bảng sinhvien
            $sinhVien->update([
                'trang_thai' => 'vo_hieu_hoa'
            ]);

            // Cập nhật trạng thái trong bảng users
            $user = User::where('email', $sinhVien->email)->first();
            if ($user) {
                $user->update([
                    'trang_thai' => 'vo_hieu_hoa'
                ]);
            }

            DB::commit();

            return redirect()->route('admin.sinhvien.index')
                ->with('success', 'Vô hiệu hóa tài khoản sinh viên thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.sinhvien.index')
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Kích hoạt lại tài khoản sinh viên
     */
    public function enable($ma_sinhvien)
    {
        $sinhVien = SinhVien::where('ma_sinhvien', $ma_sinhvien)->firstOrFail();

        DB::beginTransaction();

        try {
            // Cập nhật trạng thái trong bảng sinhvien
            $sinhVien->update([
                'trang_thai' => 'hoat_dong'
            ]);

            // Cập nhật trạng thái trong bảng users
            $user = User::where('email', $sinhVien->email)->first();
            if ($user) {
                $user->update([
                    'trang_thai' => 'hoat_dong'
                ]);
            }

            DB::commit();

            return redirect()->route('admin.sinhvien.index')
                ->with('success', 'Kích hoạt tài khoản sinh viên thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.sinhvien.index')
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Hiển thị chi tiết sinh viên
     */
    public function show($ma_sinhvien)
    {
        $sinhVien = SinhVien::where('ma_sinhvien', $ma_sinhvien)->firstOrFail();
        return view('admin.sinhvien.show', compact('sinhVien'));
    }
}