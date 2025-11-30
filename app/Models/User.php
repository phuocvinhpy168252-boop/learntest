<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id';
    public $incrementing = true; // Đặt true vì id là AUTO_INCREMENT
    protected $keyType = 'int'; // Đặt int vì id là INT

    protected $fillable = [
        'ma_nguoi_dung', // Thêm ma_nguoi_dung vào fillable
        'hoten',
        'sdt',
        'email',
        'diachi',
        'loai_taikhoan',
        'password',
        'trang_thai'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public $timestamps = true;

    // Relationship với giảng viên
    public function giangVien()
    {
        return $this->belongsTo(GiangVien::class, 'ma_nguoi_dung', 'ma_giangvien');
    }

    // Relationship với sinh viên
    public function sinhVien()
    {
        return $this->belongsTo(SinhVien::class, 'ma_nguoi_dung', 'ma_sinhvien');
    }

    // Phương thức kiểm tra loại tài khoản
    public function isAdmin()
    {
        return $this->loai_taikhoan === 'admin';
    }

    public function isGiangVien()
    {
        return $this->loai_taikhoan === 'giangvien';
    }

    public function isSinhVien()
    {
        return $this->loai_taikhoan === 'sinhvien';
    }

    // Phương thức lấy thông tin chi tiết theo loại tài khoản
    public function getDetail()
    {
        if ($this->isGiangVien()) {
            return $this->giangVien;
        } elseif ($this->isSinhVien()) {
            return $this->sinhVien;
        }
        
        return $this; // Trả về chính user nếu là admin
    }
}