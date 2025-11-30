<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DangKyLop extends Model
{
    use HasFactory;

    protected $table = 'dang_ky_lop';
    
    protected $fillable = [
        'ma_sinhvien',
        'ma_lop',
        'ngay_dang_ky',
        'trang_thai'
    ];

    protected $casts = [
        'ngay_dang_ky' => 'datetime',
    ];

    /**
     * Quan hệ với sinh viên
     */
    public function sinhVien()
    {
        return $this->belongsTo(SinhVien::class, 'ma_sinhvien', 'ma_sinhvien');
    }

    /**
     * Quan hệ với lớp học
     */
    public function lopHoc()
    {
        return $this->belongsTo(LopHoc::class, 'ma_lop', 'ma_lop');
    }

    /**
     * Scope lấy đăng ký đang học
     */
    public function scopeDangHoc($query)
    {
        return $query->where('trang_thai', 'dang_hoc');
    }

    /**
     * Scope lấy đăng ký theo lớp
     */
    public function scopeTheoLop($query, $ma_lop)
    {
        return $query->where('ma_lop', $ma_lop);
    }

    /**
     * Scope lấy đăng ký theo sinh viên
     */
    public function scopeTheoSinhVien($query, $ma_sinhvien)
    {
        return $query->where('ma_sinhvien', $ma_sinhvien);
    }
}