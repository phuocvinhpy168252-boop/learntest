<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaiGiang extends Model
{
    use HasFactory;

    protected $table = 'bai_giang';
    protected $primaryKey = 'id';

    protected $fillable = [
        'ma_bai_giang',
        'ma_lop',
        'tieu_de',
        'mo_ta',
        'loai_bai_giang',
        'duong_dan_file',
        'url_video',
        'thu_tu',
        'trang_thai'
    ];

    public function lopHoc()
    {
        return $this->belongsTo(LopHoc::class, 'ma_lop', 'ma_lop');
    }

    // Helper method để lấy icon theo loại bài giảng
    // Trong file BaiGiang.php, cập nhật phương thức getIcon()

// Helper method để lấy icon theo loại bài giảng
public function getIcon()
{
    switch ($this->loai_bai_giang) {
        case 'video':
            return 'fas fa-video';
        case 'pdf':
            return 'fas fa-file-pdf';
        case 'document':
            return 'fas fa-file-word';
        case 'presentation':
            return 'fas fa-file-powerpoint';
        default:
            return 'fas fa-file';
    }
}

    // Helper method để lấy màu badge theo loại
    public function getBadgeColor()
    {
        switch ($this->loai_bai_giang) {
            case 'video':
                return 'danger';
            case 'pdf':
                return 'danger';
            case 'document':
                return 'primary';
            case 'presentation':
                return 'warning';
            default:
                return 'secondary';
        }
    }

    // THÊM METHOD getLoaiText() BỊ THIẾU
    public function getLoaiText()
    {
        switch ($this->loai_bai_giang) {
            case 'video':
                return 'Video';
            case 'pdf':
                return 'PDF';
            case 'document':
                return 'Tài liệu';
            case 'presentation':
                return 'Bài thuyết trình';
            case 'other':
                return 'Khác';
            default:
                return 'Không xác định';
        }
    }
    // Thêm vào file BaiGiang.php, trong class BaiGiang

/**
 * Lấy ID video từ URL YouTube
 */
public function getYouTubeId()
{
    if (!$this->url_video) {
        return null;
    }
    
    $url = $this->url_video;
    
    // Pattern để lấy ID từ các dạng URL YouTube
    $patterns = [
        '/youtube\.com\/watch\?v=([^&]+)/',
        '/youtu\.be\/([^?]+)/',
        '/youtube\.com\/embed\/([^\/]+)/',
        '/youtube\.com\/v\/([^\/]+)/'
    ];
    
    foreach ($patterns as $pattern) {
        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }
    }
    
    return null;
}

/**
 * Kiểm tra có phải URL YouTube không
 */
public function isYouTubeVideo()
{
    return $this->url_video && (str_contains($this->url_video, 'youtube.com') || str_contains($this->url_video, 'youtu.be'));
}
}