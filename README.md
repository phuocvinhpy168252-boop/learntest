**Project**: `learntest`

Short description: Ứng dụng học tập nhỏ xây trên Laravel để quản lý giảng viên, sinh viên, môn học và lớp học. Project này dùng cấu trúc MVC tiêu chuẩn của Laravel, có khu vực quản trị và giao diện dành cho giảng viên/sinh viên.

**Features**
- **Quản lý Giảng viên**: model `GiangVien`, controller `GiangVienController` và các view trong `resources/views/giangvien`.
- **Quản lý Sinh viên**: model `SinhVien` và view trong `resources/views/sinhvien`.
- **Quản lý Môn học**: model `MonHoc`, controller admin `AdminMonHocController`, views trong `resources/views/admin/monhoc`.
- **Quản lý Lớp học**: model `LopHoc`, controller `LopHocController` (xem `app/Http/Controllers/GiangVien/LopHocController.php`) và views trong `resources/views/giangvien/lophoc`.
- **Xác thực cơ bản**: có `AuthController.php` và view đăng nhập/đăng ký tại `resources/views/auth`.
- **Giao diện**: layout chính nằm ở `resources/views/layouts` (ví dụ `admin.blade.php`, `giangvien.blade.php`).

**Thư mục quan trọng**
- **Controllers**: `app/Http/Controllers` (và `app/Http/Controllers/Admin`, `app/Http/Controllers/GiangVien`).
- **Models**: `app/Models` (`GiangVien.php`, `SinhVien.php`, `MonHoc.php`, `LopHoc.php`, `User.php`).
- **Views**: `resources/views`.
- **Migrations**: `database/migrations`.
- **Routes**: `routes/web.php` (web routes) và `routes/console.php`.

**Yêu cầu**
- PHP (phiên bản tương thích với Laravel trong project). 
- Composer.
- MySQL (hoặc MariaDB) — trong môi trường Windows thường dùng XAMPP.
- Node.js & npm (để build JS/CSS với Vite).

**Cài đặt & chạy (Windows — PowerShell)**
1. Cài dependencies PHP:

```powershell
cd c:\xampp\htdocs\learntest
composer install
```

2. Cấu hình environment: sao chép file môi trường và chỉnh `DB_*` trong `.env` theo MySQL của bạn:

```powershell
cp .env.example .env
php artisan key:generate
```

Mở `.env` và cập nhật `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` (thường `DB_HOST=127.0.0.1`, `DB_PORT=3306`).

3. Chạy migration và seeder (nếu có):

```powershell
php artisan migrate --seed
```

4. Cài node packages và build assets:

```powershell
npm install
npm run dev
```

5. Chạy server local:

```powershell
php artisan serve
```

Mở trình duyệt tại `http://127.0.0.1:8000` (hoặc URL hiển thị khi chạy lệnh trên).

**Thực thi và kiểm tra**
- Routes chính: xem `routes/web.php` để biết các URL hiện có.
- Kiểm tra controllers: `app/Http/Controllers` và `app/Http/Controllers/Admin` để biết logic CRUD.
- Views: `resources/views` — tìm `giangvien`, `sinhvien`, `admin` để xem giao diện.
- Tests: `tests/` (nếu bạn muốn mở rộng unit/feature tests).

**Ghi chú**
- Nếu bạn dùng XAMPP, đảm bảo MySQL đang chạy và thông tin kết nối `.env` chính xác.
- Nếu cần tạo user mặc định, kiểm tra `database/seeders/DatabaseSeeder.php`.

**License**: MIT

Nếu bạn muốn, tôi có thể: cập nhật README bằng tiếng Anh, thêm badge, hoặc thêm hướng dẫn seed dữ liệu cụ thể. Hãy cho biết bạn muốn thêm thông tin nào nữa.
<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
