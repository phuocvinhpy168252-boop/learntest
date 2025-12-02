<?php

namespace App\Http\Controllers\GiangVien;

use App\Http\Controllers\Controller;
use App\Models\BaiKiemTra;
use App\Models\CauHoi;
use App\Models\GiangVien;
use App\Models\LopHoc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;
use PhpOffice\PhpSpreadsheet\Reader\Csv as CsvReader;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;

class CauHoiImportController extends Controller
{
    /**
     * Hiển thị form import câu hỏi
     */
    public function showImportForm($ma_lop, $id)
    {
        $user = Auth::user();
        $giangVien = GiangVien::where('email', $user->email)->first();
        
        $lopHoc = LopHoc::where('ma_lop', $ma_lop)
                        ->where('ma_giang_vien', $giangVien->ma_giangvien)
                        ->firstOrFail();

        $baiKiemTra = BaiKiemTra::where('ma_lop', $ma_lop)
                               ->where('id', $id)
                               ->firstOrFail();

        return view('giangvien.cauhoi.import', compact('lopHoc', 'baiKiemTra'));
    }

    /**
     * Download template Excel
     */
    public function downloadTemplate($ma_lop, $id)
{
    $user = Auth::user();
    $giangVien = GiangVien::where('email', $user->email)->first();
    
    $lopHoc = LopHoc::where('ma_lop', $ma_lop)
                    ->where('ma_giang_vien', $giangVien->ma_giangvien)
                    ->firstOrFail();

    $baiKiemTra = BaiKiemTra::where('ma_lop', $ma_lop)
                           ->where('id', $id)
                           ->firstOrFail();

    // Tạo spreadsheet
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Câu Hỏi');

    // Headers
    $headers = ['Loại Câu Hỏi', 'Nội Dung', 'Đáp Án 1', 'Đáp Án 2', 'Đáp Án 3', 'Đáp Án 4', 'Đáp Án Đúng', 'Điểm', 'Thứ Tự'];
    $row = 1;
    foreach ($headers as $col => $header) {
        $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col + 1);
        $sheet->setCellValue($colLetter . $row, $header);
    }

    // Style header
    $headerStyle = [
        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']],
        'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER],
    ];

    for ($col = 1; $col <= count($headers); $col++) {
        $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
        $sheet->getStyle($colLetter . '1')->applyFromArray($headerStyle);
    }

    // Sample data
    $sampleData = [
        ['trac_nghiem', 'Thủ đô của Việt Nam là gì?', 'Hà Nội', 'Hồ Chí Minh', 'Huế', 'Đà Nẵng', '1', '1', '1'],
        ['trac_nghiem', '2 + 2 = ?', '3', '4', '5', '6', '2', '1', '2'],
        ['tu_luan', 'Hãy giải thích công thức E=mc²', '', '', '', '', '', '2', '3'],
        ['dung_sai', 'Paris là thủ đô của Pháp', '', '', '', '', '1', '1', '4'],
    ];

    $row = 2;
    foreach ($sampleData as $rowData) {
        foreach ($rowData as $colIndex => $value) {
            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex + 1);
            $sheet->setCellValue($colLetter . $row, $value);
        }
        $row++;
    }

    // Set column widths
    $widths = [15, 40, 15, 15, 15, 15, 15, 10, 10];
    foreach ($widths as $i => $w) {
        $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i + 1);
        $sheet->getColumnDimension($colLetter)->setWidth($w);
    }

    // Wrap text
    $sheet->getStyle('B:B')->getAlignment()->setWrapText(true);

    // Add instructions sheet
    $instructionSheet = $spreadsheet->createSheet();
    $instructionSheet->setTitle('Hướng Dẫn');

    $instructions = [
        ['HƯỚNG DẪN NHẬP NGÂN HÀNG CÂU HỎI'],
        [],
        ['1. LOẠI CÂU HỎI:'],
        ['   - trac_nghiem: Câu trắc nghiệm (nhập 4 đáp án)'],
        ['   - tu_luan: Câu tự luận (để trống cột đáp án)'],
        ['   - dung_sai: Câu đúng/sai (0=Sai, 1=Đúng)'],
        [],
        ['2. NỘI DUNG:'],
        ['   - Nhập câu hỏi đầy đủ và rõ ràng'],
        [],
        ['3. ĐÁP ÁN:'],
        ['   - Trắc nghiệm: Nhập 4 đáp án (A, B, C, D)'],
        ['   - Tự luận: Để trống hoặc thêm đáp án mẫu'],
        ['   - Đúng/Sai: Nhập 0 hoặc 1 trong cột "Đáp Án Đúng"'],
        [],
        ['4. ĐÁP ÁN ĐÚNG:'],
        ['   - Trắc nghiệm: 1=Đáp án A, 2=Đáp án B, 3=Đáp án C, 4=Đáp án D'],
        ['   - Đúng/Sai: 0=Sai, 1=Đúng'],
        [],
        ['5. ĐIỂM:'],
        ['   - Nhập điểm của câu hỏi (0.5 - 10)'],
        [],
        ['6. THỨ TỰ:'],
        ['   - Để trống để sắp xếp tự động'],
    ];

    $row = 1;
    foreach ($instructions as $instruction) {
        foreach ($instruction as $colIndex => $value) {
            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex + 1);
            $instructionSheet->setCellValue($colLetter . $row, $value);
        }
        $row++;
    }

    // Save file
    $writer = new Xlsx($spreadsheet);
    $filename = 'Mau_Nhap_Cau_Hoi_' . $baiKiemTra->ma_bai_kiem_tra . '.xlsx';

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: no-cache');

    $writer->save('php://output');
    exit;
}


    /**
     * Import câu hỏi từ Excel
     */
    public function import(Request $request, $ma_lop, $id)
{
    \Log::info('=== Bắt đầu import câu hỏi ===', [
        'user_id' => Auth::id(),
        'ma_lop' => $ma_lop,
        'baikiemtra_id' => $id
    ]);

    $request->validate([
        'file' => 'required|file|mimes:xlsx,xls,csv',
    ]);

    $user = Auth::user();
    
    // DEBUG: Kiểm tra thông tin user
    \Log::info('User info', [
        'id' => $user->id,
        'email' => $user->email,
        'ma_nguoi_dung' => $user->ma_nguoi_dung ?? 'null',
        'loai_taikhoan' => $user->loai_taikhoan ?? 'null'
    ]);

    // Tìm giảng viên theo ma_nguoi_dung (vì trong DB, users.ma_nguoi_dung = giangvien.ma_giangvien)
    $giangVien = GiangVien::where('ma_giangvien', $user->ma_nguoi_dung)->first();
    
    if (!$giangVien) {
        \Log::error('Không tìm thấy giảng viên với ma_nguoi_dung: ' . ($user->ma_nguoi_dung ?? 'null'));
        return redirect()->back()
            ->with('error', 'Không tìm thấy thông tin giảng viên!');
    }

    \Log::info('Tìm thấy giảng viên', [
        'ma_giangvien' => $giangVien->ma_giangvien,
        'email' => $giangVien->email
    ]);

    // Kiểm tra lớp học thuộc về giảng viên
    $lopHoc = LopHoc::where('ma_lop', $ma_lop)
                   ->where('ma_giang_vien', $giangVien->ma_giangvien)
                   ->first();

    if (!$lopHoc) {
        \Log::error('Giảng viên không có quyền với lớp học', [
            'ma_giangvien' => $giangVien->ma_giangvien,
            'ma_lop' => $ma_lop
        ]);
        return redirect()->back()
            ->with('error', 'Bạn không có quyền truy cập lớp học này!');
    }

    $baiKiemTra = BaiKiemTra::where('ma_lop', $ma_lop)
                           ->where('id', $id)
                           ->first();

    if (!$baiKiemTra) {
        \Log::error('Không tìm thấy bài kiểm tra', [
            'ma_lop' => $ma_lop,
            'id' => $id
        ]);
        return redirect()->back()
            ->with('error', 'Không tìm thấy bài kiểm tra!');
    }

    \Log::info('Tìm thấy bài kiểm tra', [
        'ma_bai_kiem_tra' => $baiKiemTra->ma_bai_kiem_tra,
        'tieu_de' => $baiKiemTra->tieu_de
    ]);

    try {
        $file = $request->file('file');
        $ext = strtolower($file->getClientOriginalExtension());
        
        \Log::info('File info', [
            'name' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'extension' => $ext,
            'mime' => $file->getMimeType()
        ]);

        // Load file
        if ($ext === 'csv') {
            $reader = new CsvReader();
        } else {
            $reader = new XlsxReader();
        }
        
        // Tắt tính năng đọc phép tính để tăng tốc độ
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        \Log::info('Đọc file thành công', [
            'total_rows' => count($rows),
            'first_5_rows' => array_slice($rows, 0, 5)
        ]);

        $importedCount = 0;
        $errorCount = 0;
        $errors = [];

        DB::beginTransaction();

        // Bỏ qua header row (dòng đầu tiên)
        for ($i = 2; $i <= count($rows); $i++) {
            $row = $rows[$i - 1] ?? [];

            // Bỏ qua dòng trống
            if (empty($row[0]) && empty($row[1])) {
                continue;
            }

            try {
                $loaiCauHoi = trim($row[0] ?? '');
                $noiDung = trim($row[1] ?? '');
                $dapAn1 = trim($row[2] ?? '');
                $dapAn2 = trim($row[3] ?? '');
                $dapAn3 = trim($row[4] ?? '');
                $dapAn4 = trim($row[5] ?? '');
                $dapAnDung = trim($row[6] ?? '');
                $diem = floatval($row[7] ?? 1);
                $thuTu = intval($row[8] ?? 0);

                \Log::debug('Processing row', [
                    'row_num' => $i,
                    'loai_cau_hoi' => $loaiCauHoi,
                    'noi_dung' => $noiDung,
                    'diem' => $diem
                ]);

                // Validate
                if (!$loaiCauHoi || !$noiDung) {
                    $errors[] = "Dòng $i: Loại câu hỏi và nội dung không được để trống";
                    $errorCount++;
                    continue;
                }

                if (!in_array($loaiCauHoi, ['trac_nghiem', 'tu_luan', 'dung_sai'])) {
                    $errors[] = "Dòng $i: Loại câu hỏi không hợp lệ (phải là: trac_nghiem, tu_luan, dung_sai)";
                    $errorCount++;
                    continue;
                }

                // Tạo mã câu hỏi
                $lastCauHoi = CauHoi::orderBy('ma_cau_hoi', 'desc')->first();
                $maCauHoi = 'CH001';
                if ($lastCauHoi) {
                    $lastNumber = intval(substr($lastCauHoi->ma_cau_hoi, 2));
                    $maCauHoi = 'CH' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
                }

                $cauTraLoiJson = null;
                $dapAn = null;

                if ($loaiCauHoi === 'trac_nghiem') {
                    // Trắc nghiệm
                    if (!$dapAn1 || !$dapAn2) {
                        $errors[] = "Dòng $i: Câu trắc nghiệm cần ít nhất 2 đáp án";
                        $errorCount++;
                        continue;
                    }

                    // Tạo mảng lựa chọn với indices rõ ràng (0, 1, 2, 3)
                    $luaChon = [];
                    $luaChon[0] = $dapAn1;
                    $luaChon[1] = $dapAn2;
                    if ($dapAn3) $luaChon[2] = $dapAn3;
                    if ($dapAn4) $luaChon[3] = $dapAn4;

                    // Lọc bỏ các phần tử null
                    $luaChon = array_filter($luaChon, function($val) { return !is_null($val) && $val !== ''; });
                    // Normalize indices
                    $luaChon = array_values($luaChon);

                    $dapAnDungValue = intval($dapAnDung);
                    if ($dapAnDungValue < 1 || $dapAnDungValue > count($luaChon)) {
                        $errors[] = "Dòng $i: Đáp án đúng phải từ 1-" . count($luaChon);
                        $errorCount++;
                        continue;
                    }

                    // Pass array directly - Eloquent will handle JSON encoding via the cast
                    $cauTraLoiJson = [
                        'lua_chon' => $luaChon,
                        'dap_an_dung' => $dapAnDungValue - 1  // Convert to 0-based index
                    ];
                    
                    \Log::debug('Câu trắc nghiệm', [
                        'row_num' => $i,
                        'lua_chon' => $luaChon,
                        'dap_an_dung' => $dapAnDungValue - 1
                    ]);
                } elseif ($loaiCauHoi === 'tu_luan') {
                    // Tự luận - lưu đáp án mẫu vào cau_tra_loi_json
                    if ($dapAn1) {
                        // Pass array directly - Eloquent will handle JSON encoding via the cast
                        $cauTraLoiJson = [
                            'dap_an_mau' => $dapAn1
                        ];
                    }
                } elseif ($loaiCauHoi === 'dung_sai') {
                    // Đúng/Sai
                    $dapAnValue = intval($dapAnDung);
                    if ($dapAnValue !== 0 && $dapAnValue !== 1) {
                        $errors[] = "Dòng $i: Đáp án đúng/sai phải là 0 (Sai) hoặc 1 (Đúng)";
                        $errorCount++;
                        continue;
                    }
                    $dapAn = $dapAnValue;
                }

                // Tạo câu hỏi
                CauHoi::create([
                    'ma_cau_hoi' => $maCauHoi,
                    'ma_bai_kiem_tra' => $baiKiemTra->ma_bai_kiem_tra,
                    'noi_dung' => $noiDung,
                    'loai_cau_hoi' => $loaiCauHoi,
                    'dap_an' => $dapAn,
                    'cau_tra_loi_json' => $cauTraLoiJson,
                    'diem' => $diem,
                    'thu_tu' => $thuTu,
                    'trang_thai' => 'active'
                ]);

                $importedCount++;
                
                \Log::debug('Câu hỏi đã tạo', ['ma_cau_hoi' => $maCauHoi]);

            } catch (\Exception $e) {
                $errors[] = "Dòng $i: " . $e->getMessage();
                $errorCount++;
                \Log::error('Lỗi xử lý dòng ' . $i, [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }

        // Cập nhật số câu hỏi trong bài kiểm tra
        if ($importedCount > 0) {
            $baiKiemTra->increment('so_cau_hoi', $importedCount);
            \Log::info('Cập nhật số câu hỏi', [
                'so_cau_hoi_moi' => $baiKiemTra->so_cau_hoi
            ]);
        }

        DB::commit();

        \Log::info('Import hoàn thành', [
            'imported' => $importedCount,
            'errors' => $errorCount,
            'total_errors' => count($errors)
        ]);

        $message = "Nhập thành công $importedCount câu hỏi";
        if ($errorCount > 0) {
            $message .= " ($errorCount lỗi)";
        }

        $redirect = redirect()->route('giangvien.lophoc.baikiemtra.cauhoi.index', [$ma_lop, $id])
            ->with('success', $message);

        if ($errorCount > 0) {
            $redirect = $redirect->with('errors', $errors);
        }

        return $redirect;

    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Lỗi import', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return redirect()->back()
            ->with('error', 'Lỗi khi import: ' . $e->getMessage())
            ->withInput();
    }
}
}
