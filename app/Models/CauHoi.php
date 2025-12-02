<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CauHoi extends Model
{
    use HasFactory;

    protected $table = 'cau_hoi';
    protected $primaryKey = 'id';

    protected $fillable = [
        'ma_cau_hoi',
        'ma_bai_kiem_tra',
        'noi_dung',
        'loai_cau_hoi',
        'dap_an',
        'cau_tra_loi_json',
        'diem',
        'thu_tu',
        'trang_thai'
    ];

    protected $casts = [
        'cau_tra_loi_json' => 'array',
        'diem' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Quan hệ với bài kiểm tra
     */
    /**
 * Quan hệ với bài kiểm tra
 */
    public function baiKiemTra()
    {
        return $this->belongsTo(BaiKiemTra::class, 'ma_bai_kiem_tra', 'ma_bai_kiem_tra');
    }

    /**
     * Scope lấy câu hỏi theo bài kiểm tra
     */
    public function scopeTheoBaiKiemTra($query, $ma_bai_kiem_tra)
    {
        return $query->where('ma_bai_kiem_tra', $ma_bai_kiem_tra);
    }

    /**
     * Scope sắp xếp theo thứ tự
     */
    public function scopeTheoThuTu($query)
    {
        return $query->orderBy('thu_tu')->orderBy('created_at');
    }

    /**
     * Lấy loại câu hỏi dạng text
     */
    public function getLoaiText()
    {
        $normalized = $this->normalizedLoai();
        $texts = [
            'trac_nghiem' => 'Trắc nghiệm',
            'tu_luan' => 'Tự luận',
            'dung_sai' => 'Đúng/Sai'
        ];
        return $texts[$normalized] ?? 'Không xác định';
    }

    /**
     * Lấy danh sách đáp án cho câu hỏi trắc nghiệm
     */
    public function getDapAnList()
    {
        if ($this->normalizedLoai() !== 'trac_nghiem') {
            return [];
        }

        $dapAn = $this->cau_tra_loi_json ?? [];
        return $dapAn['lua_chon'] ?? [];
    }

    /**
     * Lấy đáp án đúng
     */
    public function getDapAnDung()
    {
        if ($this->normalizedLoai() === 'trac_nghiem') {
            $dapAn = $this->cau_tra_loi_json ?? [];
            return $dapAn['dap_an_dung'] ?? null;
        }

        return $this->dap_an;
    }

    /**
     * Kiểm tra tính hợp lệ của câu hỏi
     */
    public function isValid()
    {
        if ($this->normalizedLoai() === 'trac_nghiem') {
            $dapAn = $this->cau_tra_loi_json ?? [];
            return isset($dapAn['lua_chon']) && 
                   count($dapAn['lua_chon']) >= 2 && 
                   isset($dapAn['dap_an_dung']);
        }

        return !empty(trim($this->noi_dung));
    }

    /**
     * Normalize stored loai_cau_hoi into canonical keys
     * Returns one of: trac_nghiem, tu_luan, dung_sai
     */
    public function normalizedLoai()
    {
        $val = strtolower(trim((string)$this->loai_cau_hoi));

        // map common variants to canonical keys
        $map = [
            'trac_nghiem' => ['trac_nghiem', 'trac-nghiem', 'tracnghiem', 'trac nghiem', 'trắc_nghiệm', 'trắc nghiệm', 'trắc-nghiệm'],
            'tu_luan' => ['tu_luan', 'tu-luan', 'tuluan', 'tự luận', 'tự_luan', 'tự-luận'],
            'dung_sai' => ['dung_sai', 'dung-sai', 'dungsai', 'đúng_sai', 'đúng/sai', 'đúng sai', 'dung/sai']
        ];

        foreach ($map as $key => $variants) {
            foreach ($variants as $v) {
                if ($val === $v) return $key;
            }
        }

        // If value already matches canonical or is empty
        if (in_array($val, ['trac_nghiem', 'tu_luan', 'dung_sai'])) {
            return $val;
        }

        // If stored value is ambiguous or empty, try to infer from other fields
        // Prefer explicit data over unknown stored string
        $json = $this->cau_tra_loi_json ?? null;

        if (is_array($json) && isset($json['lua_chon']) && count($json['lua_chon']) >= 2) {
            return 'trac_nghiem';
        }

        // dap_an numeric 0/1 indicates Dung/Sai
        if ($this->dap_an === 0 || $this->dap_an === 1 || (string)$this->dap_an === '0' || (string)$this->dap_an === '1') {
            return 'dung_sai';
        }

        // If there is an example answer in json, treat as tu_luan
        if (is_array($json) && isset($json['dap_an_mau'])) {
            return 'tu_luan';
        }

        return $this->loai_cau_hoi ?? '';
    }

    public function isTracNghiem()
    {
        return $this->normalizedLoai() === 'trac_nghiem';
    }

    public function isTuLuan()
    {
        return $this->normalizedLoai() === 'tu_luan';
    }

    public function isDungSai()
    {
        return $this->normalizedLoai() === 'dung_sai';
    }
    
}