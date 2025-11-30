<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MonHoc extends Model
{
    use HasFactory;

    protected $table = 'monhoc';
    protected $primaryKey = 'ma_mon_hoc';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'ma_mon_hoc',
        'ten_mon_hoc',
        'mo_ta',
        'trang_thai'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Scope để lấy môn học đang hoạt động
     */
    public function scopeHoatDong($query)
    {
        return $query->where('trang_thai', 'hoat_dong');
    }

    /**
     * Scope để tìm kiếm môn học
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('ma_mon_hoc', 'like', "%{$search}%")
                    ->orWhere('ten_mon_hoc', 'like', "%{$search}%");
    }
}