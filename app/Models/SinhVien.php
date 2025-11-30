<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class SinhVien extends Authenticatable
{
    use HasFactory;

    protected $table = 'sinhvien';
    protected $primaryKey = 'ma_sinhvien';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'ma_sinhvien',
        'ten',
        'so_dien_thoai',
        'email',
        'dia_chi',
        'ngay_sinh',
        'gioi_tinh',
        'lop',
        'loai_tai_khoan',
        'trang_thai',
        'password'
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'ngay_sinh' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationship với bảng users
    public function user()
    {
        return $this->hasOne(User::class, 'ma_nguoi_dung', 'ma_sinhvien');
    }

    /**
     * Relationship với lớp học (qua bảng đăng ký)
     */
    public function lopHocs()
    {
        return $this->belongsToMany(LopHoc::class, 'dang_ky_lop', 'ma_sinhvien', 'ma_lop')
                    ->withPivot('ngay_dang_ky', 'trang_thai')
                    ->withTimestamps();
    }

    /**
     * Relationship với bảng đăng ký
     */
    public function dangKyLop()
    {
        return $this->hasMany(DangKyLop::class, 'ma_sinhvien', 'ma_sinhvien');
    }

    /**
     * Kiểm tra sinh viên có đăng ký lớp không
     */
    public function daDangKyLop($ma_lop)
    {
        return $this->dangKyLop()->where('ma_lop', $ma_lop)->exists();
    }

    /**
     * Scope tìm kiếm sinh viên
     */
    public function scopeTimKiem($query, $keyword)
    {
        return $query->where(function($q) use ($keyword) {
            $q->where('ma_sinhvien', 'like', "%{$keyword}%")
              ->orWhere('ten', 'like', "%{$keyword}%")
              ->orWhere('email', 'like', "%{$keyword}%")
              ->orWhere('so_dien_thoai', 'like', "%{$keyword}%");
        });
    }

    /**
     * Scope sinh viên đang hoạt động
     */
    public function scopeDangHoatDong($query)
    {
        return $query->where('trang_thai', 'hoat_dong'); // SỬA: 'active' thành 'hoat_dong'
    }
}