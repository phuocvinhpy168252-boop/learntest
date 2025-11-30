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
}