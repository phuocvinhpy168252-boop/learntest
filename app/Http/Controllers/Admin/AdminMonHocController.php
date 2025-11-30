<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MonHoc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminMonHocController extends Controller
{
    /**
     * Hiển thị danh sách môn học
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        $monHocs = MonHoc::when($search, function ($query, $search) {
            return $query->where('ma_mon_hoc', 'like', "%{$search}%")
                        ->orWhere('ten_mon_hoc', 'like', "%{$search}%");
        })
        ->orderBy('created_at', 'desc')
        ->paginate(10);

        return view('admin.monhoc.index', compact('monHocs', 'search'));
    }

    /**
     * Hiển thị form thêm môn học
     */
    public function create()
    {
        // Tạo mã môn học tự động
        $lastMonHoc = MonHoc::orderBy('ma_mon_hoc', 'desc')->first();
        $nextMaMonHoc = 'MH001';
        
        if ($lastMonHoc) {
            $lastNumber = intval(substr($lastMonHoc->ma_mon_hoc, 2));
            $nextMaMonHoc = 'MH' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        }
        
        return view('admin.monhoc.create', compact('nextMaMonHoc'));
    }

    /**
     * Lưu môn học mới
     */
    public function store(Request $request)
    {
        $request->validate([
            'ten_mon_hoc' => 'required|string|max:255|unique:monhoc,ten_mon_hoc',
            'mo_ta' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();

        try {
            // Tạo mã môn học tự động
            $lastMonHoc = MonHoc::orderBy('ma_mon_hoc', 'desc')->first();
            $maMonHoc = 'MH001';
            
            if ($lastMonHoc) {
                $lastNumber = intval(substr($lastMonHoc->ma_mon_hoc, 2));
                $maMonHoc = 'MH' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
            }

            // Tạo môn học
            MonHoc::create([
                'ma_mon_hoc' => $maMonHoc,
                'ten_mon_hoc' => $request->ten_mon_hoc,
                'mo_ta' => $request->mo_ta,
                'trang_thai' => 'hoat_dong',
            ]);

            DB::commit();

            return redirect()->route('admin.monhoc.index')
                ->with('success', 'Thêm môn học thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Hiển thị form sửa môn học
     */
    public function edit($ma_mon_hoc)
    {
        $monHoc = MonHoc::where('ma_mon_hoc', $ma_mon_hoc)->firstOrFail();
        return view('admin.monhoc.edit', compact('monHoc'));
    }

    /**
     * Cập nhật môn học
     */
    public function update(Request $request, $ma_mon_hoc)
    {
        $monHoc = MonHoc::where('ma_mon_hoc', $ma_mon_hoc)->firstOrFail();

        $request->validate([
            'ten_mon_hoc' => 'required|string|max:255|unique:monhoc,ten_mon_hoc,' . $ma_mon_hoc . ',ma_mon_hoc',
            'mo_ta' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();

        try {
            $monHoc->update([
                'ten_mon_hoc' => $request->ten_mon_hoc,
                'mo_ta' => $request->mo_ta,
            ]);

            DB::commit();

            return redirect()->route('admin.monhoc.index')
                ->with('success', 'Cập nhật môn học thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Xóa môn học
     */
    public function destroy($ma_mon_hoc)
    {
        $monHoc = MonHoc::where('ma_mon_hoc', $ma_mon_hoc)->firstOrFail();

        DB::beginTransaction();

        try {
            $monHoc->delete();

            DB::commit();

            return redirect()->route('admin.monhoc.index')
                ->with('success', 'Xóa môn học thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.monhoc.index')
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Vô hiệu hóa môn học
     */
    public function disable($ma_mon_hoc)
    {
        $monHoc = MonHoc::where('ma_mon_hoc', $ma_mon_hoc)->firstOrFail();

        DB::beginTransaction();

        try {
            $monHoc->update([
                'trang_thai' => 'vo_hieu_hoa'
            ]);

            DB::commit();

            return redirect()->route('admin.monhoc.index')
                ->with('success', 'Vô hiệu hóa môn học thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.monhoc.index')
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Kích hoạt môn học
     */
    public function enable($ma_mon_hoc)
    {
        $monHoc = MonHoc::where('ma_mon_hoc', $ma_mon_hoc)->firstOrFail();

        DB::beginTransaction();

        try {
            $monHoc->update([
                'trang_thai' => 'hoat_dong'
            ]);

            DB::commit();

            return redirect()->route('admin.monhoc.index')
                ->with('success', 'Kích hoạt môn học thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.monhoc.index')
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}