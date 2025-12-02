# Hướng Dẫn Nhập Câu Hỏi từ File Excel

## Giới Thiệu
Tính năng nhập câu hỏi từ Excel cho phép giảng viên bulk import nhiều câu hỏi cùng một lúc, thay vì phải thêm từng câu một.

## Các Bước Sử Dụng

### Bước 1: Truy Cập Trang Nhập Excel
1. Đi đến **Bài kiểm tra** → Chọn một bài kiểm tra
2. Nhấn nút **"Quản lý Câu hỏi"**
3. Nhấn nút **"Nhập Excel"** (màu xanh lam)

Hoặc:
- Từ trang danh sách câu hỏi, nhấn **"Nhập Excel"**

### Bước 2: Tải File Mẫu
- Nhấn nút **"Tải File Mẫu Excel"** (màu xanh)
- Điều này sẽ tải file Excel có template sẵn với:
  - Sheet "Câu Hỏi" chứa cấu trúc cột
  - 4 ví dụ câu hỏi (trắc nghiệm, tự luận, đúng/sai)
  - Sheet "Hướng Dẫn" với chi tiết về cách điền

### Bước 3: Điền Dữ Liệu
Sử dụng file mẫu và thêm câu hỏi của bạn theo cấu trúc:

#### Cấu Trúc Cột:
| Cột | Tên | Mô Tả | Bắt Buộc |
|-----|-----|-------|---------|
| A | Loại Câu Hỏi | `trac_nghiem`, `tu_luan`, `dung_sai` | ✓ |
| B | Nội Dung | Văn bản câu hỏi | ✓ |
| C-F | Đáp Án 1-4 | Các lựa chọn trả lời | * |
| G | Đáp Án Đúng | Chỉ số hoặc giá trị đúng | * |
| H | Điểm | Số điểm cho câu hỏi | ✓ |
| I | Thứ Tự | Vị trí hiển thị (tùy chọn) | ✗ |

#### Chi Tiết Theo Loại:

**Trắc Nghiệm (trac_nghiem):**
```
Loại Câu Hỏi: trac_nghiem
Nội Dung: Câu hỏi của bạn?
Đáp Án 1: Lựa chọn A
Đáp Án 2: Lựa chọn B
Đáp Án 3: Lựa chọn C
Đáp Án 4: Lựa chọn D
Đáp Án Đúng: 1 (nếu A đúng), 2 (B đúng), 3 (C đúng), 4 (D đúng)
Điểm: 1
```

**Tự Luận (tu_luan):**
```
Loại Câu Hỏi: tu_luan
Nội Dung: Câu hỏi tự luận của bạn?
Đáp Án 1-4: (để trống hoặc có thể thêm đáp án mẫu)
Đáp Án Đúng: (để trống)
Điểm: 2
```

**Đúng/Sai (dung_sai):**
```
Loại Câu Hỏi: dung_sai
Nội Dung: Câu phát biểu đúng hay sai?
Đáp Án 1-4: (để trống)
Đáp Án Đúng: 0 (Sai) hoặc 1 (Đúng)
Điểm: 1
```

### Bước 4: Kiểm Tra và Lưu File
- Kiểm tra lại toàn bộ dữ liệu
- Lưu file với định dạng Excel (.xlsx) hoặc CSV (.csv)

### Bước 5: Tải File Lên
1. Nhấn **"Chọn file Excel"** hoặc kéo thả file vào khung
2. Chọn file đã chuẩn bị từ máy tính
3. Nhấn **"Nhập Câu Hỏi"**

## Kết Quả
- **Thành công**: Trang sẽ hiển thị số lượng câu hỏi đã nhập thành công
- **Có Lỗi**: Sẽ liệt kê các dòng có vấn đề, bạn có thể sửa lại và nhập lại
- Tất cả câu hỏi sẽ được thêm vào bài kiểm tra

## Lưu Ý Quan Trọng

1. **Lỗi Dữ Liệu**: 
   - Loại câu hỏi phải chính xác: `trac_nghiem`, `tu_luan`, `dung_sai`
   - Nội dung và điểm không được để trống
   - Câu trắc nghiệm cần đủ 4 đáp án
   - Đáp án đúng phải là số từ 1-4 (trắc nghiệm) hoặc 0-1 (đúng/sai)

2. **Mã Câu Hỏi**: 
   - Được tự động tạo (CH001, CH002, ...)
   - Không cần nhập trong file

3. **Lỗi Nhập Đôi**: 
   - Nếu có lỗi, bạn có thể nhập lại
   - Các câu hỏi đã nhập thành công sẽ được lưu

4. **Sao Lưu**:
   - Nên giữ file Excel gốc làm sao lưu

## Hỗ Trợ Format File
- ✓ Excel 2007+ (.xlsx)
- ✓ Excel 2003 (.xls)
- ✓ CSV (.csv)

## Ví Dụ File Excel
Xem file mẫu tải từ hệ thống - nó chứa toàn bộ ví dụ cần thiết.
