<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LopHoc extends Model
{
    use HasFactory;

    protected $table = 'lop_hoc';
    
    protected $primaryKey = 'ma_lop';
    
    public $incrementing = false;
    
    protected $keyType = 'string';
    
    protected $fillable = [
        'ma_lop',
        'ten_lop',
        'ma_mon_hoc',
        'ma_giang_vien',
        'so_luong_sv',
        'so_luong_sv_hien_tai',
        'mo_ta',
        'phong_hoc',
        'thoi_gian_hoc',
        'trang_thai',
    ];

    /**
     * Quan hệ với môn học
     */
    public function monHoc()
    {
        return $this->belongsTo(MonHoc::class, 'ma_mon_hoc', 'ma_mon_hoc');
    }

    /**
     * Quan hệ với giảng viên
     */
    public function giangVien()
    {
        return $this->belongsTo(GiangVien::class, 'ma_giang_vien', 'ma_giangvien');
    }

    /**
     * Quan hệ với sinh viên (qua bảng đăng ký)
     */
    public function sinhViens()
    {
        return $this->belongsToMany(SinhVien::class, 'dang_ky_lop', 'ma_lop', 'ma_sinhvien')
                    ->withPivot('ngay_dang_ky', 'trang_thai')
                    ->withTimestamps();
    }

    /**
     * Quan hệ với bảng đăng ký
     */
    public function dangKyLop()
    {
        return $this->hasMany(DangKyLop::class, 'ma_lop', 'ma_lop');
    }

    /**
     * Scope cho lớp học đang mở đăng ký
     */
    public function scopeDangMo($query)
    {
        return $query->where('trang_thai', 'dang_mo');
    }

    /**
     * Kiểm tra lớp còn chỗ trống không
     */
    public function conChoTrong()
    {
        return $this->so_luong_sv_hien_tai < $this->so_luong_sv;
    }

    /**
     * Lấy số chỗ trống
     */
    public function getSoChoTrongAttribute()
    {
        return $this->so_luong_sv - $this->so_luong_sv_hien_tai;
    }

    /**
     * Kiểm tra sinh viên đã đăng ký lớp chưa
     */
    public function coSinhVien($ma_sinhvien)
    {
        return $this->dangKyLop()->where('ma_sinhvien', $ma_sinhvien)->exists();
    }
}