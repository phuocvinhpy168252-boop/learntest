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
}