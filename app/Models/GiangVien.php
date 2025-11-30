<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class GiangVien extends Authenticatable
{
    use HasFactory;

    protected $table = 'giangvien';
    protected $primaryKey = 'ma_giangvien';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'ma_giangvien',
        'ten',
        'so_dien_thoai',
        'email',
        'dia_chi',
        'mon_day',
        'loai_tai_khoan',
        'password',
        'ngay_sinh',
        'gioi_tinh',
        'trinh_do',
        'trang_thai'
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
        return $this->hasOne(User::class, 'ma_nguoi_dung', 'ma_giangvien');
    }
}