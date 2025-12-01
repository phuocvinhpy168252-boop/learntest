<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaiKiemTra extends Model
{
    use HasFactory;

    protected $table = 'bai_kiem_tra';
    protected $primaryKey = 'id';

    protected $fillable = [
        'ma_bai_kiem_tra',
        'ma_lop',
        'tieu_de',
        'mo_ta',
        'loai_bai_kiem_tra',
        'thoi_gian_lam_bai',
        'so_cau_hoi',
        'diem_toi_da',
        'thoi_gian_bat_dau',
        'thoi_gian_ket_thuc',
        'trang_thai',
        'cau_hinh'
    ];

    protected $casts = [
        'thoi_gian_bat_dau' => 'datetime',
        'thoi_gian_ket_thuc' => 'datetime',
        'cau_hinh' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Quan hệ với lớp học
     */
    public function lopHoc()
    {
        return $this->belongsTo(LopHoc::class, 'ma_lop', 'ma_lop');
    }

    /**
     * Quan hệ với câu hỏi
     */
    public function cauHois()
    {
        return $this->hasMany(CauHoi::class, 'ma_bai_kiem_tra', 'ma_bai_kiem_tra');
    }

    /**
     * Scope lấy bài kiểm tra đang hoạt động
     */
    public function scopeDangHoatDong($query)
    {
        return $query->whereIn('trang_thai', ['cho_cong_bo', 'dang_dien_ra']);
    }

    /**
     * Scope lấy bài kiểm tra theo lớp
     */
    public function scopeTheoLop($query, $ma_lop)
    {
        return $query->where('ma_lop', $ma_lop);
    }

    /**
     * Kiểm tra bài kiểm tra có đang diễn ra không
     */
    public function isDangDienRa()
    {
        return $this->trang_thai === 'dang_dien_ra';
    }

    /**
     * Kiểm tra bài kiểm tra có thể chỉnh sửa không
     */
    public function coTheChinhSua()
    {
        return in_array($this->trang_thai, ['cho_cong_bo']);
    }

    /**
     * Lấy text trạng thái
     */
    public function getTrangThaiText()
    {
        $texts = [
            'cho_cong_bo' => 'Chờ công bố',
            'dang_dien_ra' => 'Đang diễn ra',
            'da_ket_thuc' => 'Đã kết thúc',
            'da_huy' => 'Đã hủy'
        ];
        return $texts[$this->trang_thai] ?? 'Không xác định';
    }

    /**
     * Lấy text loại bài kiểm tra
     */
    public function getLoaiText()
    {
        $texts = [
            'trac_nghiem' => 'Trắc nghiệm',
            'tu_luan' => 'Tự luận',
            'hop' => 'Hỗn hợp'
        ];
        return $texts[$this->loai_bai_kiem_tra] ?? 'Không xác định';
    }

    /**
     * Lấy badge color cho trạng thái
     */
    public function getBadgeColor()
    {
        $colors = [
            'cho_cong_bo' => 'warning',
            'dang_dien_ra' => 'success',
            'da_ket_thuc' => 'secondary',
            'da_huy' => 'danger'
        ];
        return $colors[$this->trang_thai] ?? 'secondary';
    }

    /**
     * Lấy icon cho loại bài kiểm tra
     */
    public function getIcon()
    {
        $icons = [
            'trac_nghiem' => 'fas fa-list-ol',
            'tu_luan' => 'fas fa-edit',
            'hop' => 'fas fa-blender'
        ];
        return $icons[$this->loai_bai_kiem_tra] ?? 'fas fa-question-circle';
    }
    // Thêm vào BaiKiemTra.php
/**
 * Tính tổng điểm tối đa từ các câu hỏi
 */
public function tinhTongDiemCauHoi()
{
    return $this->cauHois()->sum('diem');
}

/**
 * Kiểm tra điểm tối đa có khớp với tổng điểm câu hỏi không
 */
public function kiemTraDiemToiDa()
{
    $tongDiemCauHoi = $this->tinhTongDiemCauHoi();
    return abs($this->diem_toi_da - $tongDiemCauHoi) < 0.01;
}

/**
 * Lấy cấu hình dạng mảng
 */
public function getCauHinhArray()
{
    $cauHinh = json_decode($this->cau_hinh, true);
    return is_array($cauHinh) ? $cauHinh : [];
}

/**
 * Kiểm tra xem bài kiểm tra có thể bắt đầu không
 */
public function coTheBatDau()
{
    $now = now();
    return $this->thoi_gian_bat_dau <= $now && 
           $this->thoi_gian_ket_thuc >= $now &&
           $this->trang_thai === 'dang_dien_ra';
}
// Thêm vào class BaiKiemTra, sau phương thức getIcon()
/**
 * Lấy danh sách cấu hình từ json
 */
public function getCauHinhAttribute($value)
{
    return json_decode($value, true) ?? [];
}

/**
 * Set cấu hình vào json
 */
public function setCauHinhAttribute($value)
{
    $this->attributes['cau_hinh'] = is_array($value) ? json_encode($value) : $value;
}

/**
 * Scope lấy bài kiểm tra có thể chỉnh sửa
 */
public function scopeCoTheChinhSua($query)
{
    return $query->where('trang_thai', 'cho_cong_bo');
}

/**
 * Scope lấy bài kiểm tra theo mã
 */
public function scopeTheoMa($query, $ma_bai_kiem_tra)
{
    return $query->where('ma_bai_kiem_tra', $ma_bai_kiem_tra);
}
}