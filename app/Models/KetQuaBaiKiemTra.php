<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KetQuaBaiKiemTra extends Model
{
    use HasFactory;

    protected $table = 'ket_qua_bai_kiem_tra';
    protected $primaryKey = 'id';

    protected $fillable = [
        'ma_ket_qua',
        'ma_sinhvien',
        'ma_bai_kiem_tra',
        'ma_lop',
        'diem',
        'thoi_gian_bat_dau',
        'thoi_gian_nop',
        'thoi_gian_lam_bai',
        'chi_tiet_cau_tra_loi',
        'trang_thai'
    ];

    protected $casts = [
        'thoi_gian_bat_dau' => 'datetime',
        'thoi_gian_nop' => 'datetime',
        'chi_tiet_cau_tra_loi' => 'array',
        'diem' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Quan hệ với sinh viên
     */
    public function sinhVien()
    {
        return $this->belongsTo(SinhVien::class, 'ma_sinhvien', 'ma_sinhvien');
    }

    /**
     * Quan hệ với bài kiểm tra
     */
    public function baiKiemTra()
    {
        return $this->belongsTo(BaiKiemTra::class, 'ma_bai_kiem_tra', 'ma_bai_kiem_tra');
    }

    /**
     * Quan hệ với lớp học
     */
    public function lopHoc()
    {
        return $this->belongsTo(LopHoc::class, 'ma_lop', 'ma_lop');
    }

    /**
     * Scope lấy kết quả theo sinh viên và bài kiểm tra
     */
    public function scopeTheoSinhVienBaiKiemTra($query, $ma_sinhvien, $ma_bai_kiem_tra)
    {
        return $query->where('ma_sinhvien', $ma_sinhvien)
                    ->where('ma_bai_kiem_tra', $ma_bai_kiem_tra);
    }

    /**
     * Scope lấy kết quả theo lớp
     */
    public function scopeTheoLop($query, $ma_lop)
    {
        return $query->where('ma_lop', $ma_lop);
    }
}