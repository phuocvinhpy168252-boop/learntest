@extends('layouts.app')

@section('title', 'Trang chủ - Hệ thống Giáo dục')

@section('styles')
<style>
    /* Additional styles for home page only */
    body {
        padding-top: 0;
    }
    
    .navbar-main {
        position: absolute;
        background: transparent !important;
        box-shadow: none;
    }
    
    .navbar-main.scrolled {
        position: fixed;
        background: rgba(255, 255, 255, 0.95) !important;
        box-shadow: 0 2px 20px rgba(0,0,0,0.1);
    }
    
    /* Hero Section */
    .hero-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 150px 0 100px;
        position: relative;
        overflow: hidden;
        margin-top: -76px;
    }
    
    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>');
        background-position: bottom;
        background-repeat: no-repeat;
        background-size: cover;
    }
    
    .hero-content {
        position: relative;
        z-index: 1;
    }
    
    /* Stats Section */
    .stats-card {
        background: white;
        border-radius: 15px;
        padding: 30px;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        transition: transform 0.3s ease;
        border: none;
        height: 100%;
    }
    
    .stats-card:hover {
        transform: translateY(-10px);
    }
    
    .stats-icon {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        font-size: 30px;
        color: white;
    }
    
    .stats-icon.classes {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }
    
    .stats-icon.teachers {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }
    
    .stats-icon.courses {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    }
    
    /* Course Cards */
    .course-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        border: none;
        height: 100%;
    }
    
    .course-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.12);
    }
    
    .course-image {
        height: 200px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 60px;
    }
    
    /* Teacher Cards */
    .teacher-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
    }
    
    .teacher-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.12);
    }
    
    .teacher-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        margin: 0 auto 20px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 40px;
        font-weight: bold;
    }
    
    .teacher-avatar.small {
        width: 40px;
        height: 40px;
        font-size: 16px;
    }
    
    /* Features */
    .feature-icon {
        width: 60px;
        height: 60px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
        font-size: 28px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    /* Section Titles */
    .section-title {
        position: relative;
        padding-bottom: 15px;
        margin-bottom: 40px;
        text-align: center;
    }
    
    .section-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 100px;
        height: 4px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 2px;
    }
    
    /* User Avatar */
    .user-avatar-sm {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: var(--gradient);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .hero-section {
            padding: 120px 0 80px;
            margin-top: -66px;
        }
        
        .course-image {
            height: 150px;
        }
        
        .teacher-avatar {
            width: 80px;
            height: 80px;
            font-size: 30px;
        }
    }
</style>
@endsection

@section('content')
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 hero-content">
                    <h1 class="display-4 fw-bold mb-4">Học tập thông minh, Tương lai rộng mở</h1>
                    <p class="lead mb-4">Khám phá hệ thống giáo dục trực tuyến với các khóa học chất lượng cao, giảng viên giàu kinh nghiệm và phương pháp học tập hiện đại.</p>
                    <div class="d-flex gap-3">
                        @auth
                            @if(auth()->user()->loai_taikhoan == 'sinhvien')
                                <a href="{{ route('sinhvien.dashboard') }}" class="btn btn-light btn-lg">
                                    <i class="fas fa-tachometer-alt me-2"></i>Vào học ngay
                                </a>
                            @elseif(auth()->user()->loai_taikhoan == 'giangvien')
                                <a href="{{ route('giangvien.dashboard') }}" class="btn btn-light btn-lg">
                                    <i class="fas fa-chalkboard-teacher me-2"></i>Bảng điều khiển
                                </a>
                            @endif
                        @else
                            <a href="{{ route('auth.register.form') }}" class="btn btn-light btn-lg">
                                <i class="fas fa-rocket me-2"></i>Bắt đầu học ngay
                            </a>
                            <a href="{{ route('login.form') }}" class="btn btn-outline-light btn-lg">
                                <i class="fas fa-play-circle me-2"></i>Xem demo
                            </a>
                        @endauth
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="text-center">
                        <div class="course-image rounded-4">
                            <i class="fas fa-laptop-code"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="stats-card">
                        <div class="stats-icon classes">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <h3 class="fw-bold">{{ $tongLopHoc }}+</h3>
                        <p class="text-muted mb-0">Lớp học đang mở</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card">
                        <div class="stats-icon teachers">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <h3 class="fw-bold">{{ $tongGiangVien }}+</h3>
                        <p class="text-muted mb-0">Giảng viên chuyên nghiệp</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card">
                        <div class="stats-icon courses">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <h3 class="fw-bold">{{ $tongMonHoc }}+</h3>
                        <p class="text-muted mb-0">Môn học đa dạng</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Courses -->
    <section id="courses" class="py-5 bg-light">
        <div class="container">
            <h2 class="section-title">Khóa học nổi bật</h2>
            <div class="row g-4">
                @forelse($lopHocNoiBat as $lop)
                <div class="col-lg-4 col-md-6">
                    <div class="course-card">
                        <div class="course-image">
                            <i class="fas fa-{{ $lop->monHoc->ten_mon_hoc == 'Tiếng Anh' ? 'language' : 'code' }}"></i>
                        </div>
                        <div class="p-4">
                            <h5 class="fw-bold mb-2">{{ $lop->ten_lop }}</h5>
                            <p class="text-muted mb-3">{{ $lop->mo_ta ?? 'Khóa học chất lượng cao' }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="teacher-avatar small me-2">
                                        {{ substr($lop->giangVien->ten, 0, 1) }}
                                    </div>
                                    <small>{{ $lop->giangVien->ten }}</small>
                                </div>
                                @auth
                                    @if(auth()->user()->loai_taikhoan == 'sinhvien')
                                        <a href="{{ route('sinhvien.lophoc.baigiang', $lop->ma_lop) }}" class="btn btn-sm btn-primary">Xem chi tiết</a>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>Chưa có khóa học nào
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Featured Teachers -->
    <section id="teachers" class="py-5">
        <div class="container">
            <h2 class="section-title">Đội ngũ giảng viên</h2>
            <div class="row g-4">
                @forelse($giangVienNoiBat as $giangVien)
                <div class="col-lg-3 col-md-6">
                    <div class="teacher-card">
                        <div class="teacher-avatar">
                            {{ substr($giangVien->ten, 0, 1) }}
                        </div>
                        <h5 class="fw-bold mb-2">{{ $giangVien->ten }}</h5>
                        <p class="text-muted mb-3">{{ $giangVien->mon_day ?? 'Giảng viên' }}</p>
                        <p class="text-primary fw-bold">{{ $giangVien->trinh_do ?? 'Thạc sĩ' }}</p>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>Chưa có giảng viên nào
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Features -->
    <section id="about" class="py-5 bg-light">
        <div class="container">
            <h2 class="section-title">Tại sao chọn chúng tôi?</h2>
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="text-center p-4">
                        <div class="feature-icon mx-auto">
                            <i class="fas fa-laptop"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Học mọi lúc mọi nơi</h5>
                        <p class="text-muted">Truy cập khoá học từ bất kỳ thiết bị nào, học tập linh hoạt theo thời gian của bạn.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="text-center p-4">
                        <div class="feature-icon mx-auto">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Theo dõi tiến độ</h5>
                        <p class="text-muted">Hệ thống theo dõi tiến độ học tập giúp bạn đánh giá và cải thiện hiệu quả học tập.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="text-center p-4">
                        <div class="feature-icon mx-auto">
                            <i class="fas fa-comments"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Tương tác đa chiều</h5>
                        <p class="text-muted">Tương tác trực tiếp với giảng viên và học viên khác thông qua diễn đàn và chat.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="text-center p-4">
                        <div class="feature-icon mx-auto">
                            <i class="fas fa-certificate"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Chứng chỉ công nhận</h5>
                        <p class="text-muted">Nhận chứng chỉ hoàn thành khóa học được công nhận bởi hệ thống giáo dục.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 text-white">
                    <h2 class="fw-bold mb-3">Sẵn sàng bắt đầu hành trình học tập?</h2>
                    <p class="mb-0">Đăng ký ngay hôm nay để nhận ưu đãi đặc biệt và trải nghiệm hệ thống học tập tốt nhất.</p>
                </div>
                <div class="col-lg-4 text-end">
                    @auth
                        @if(auth()->user()->loai_taikhoan == 'sinhvien')
                            <a href="{{ route('sinhvien.khoahoc') }}" class="btn btn-light btn-lg">
                                <i class="fas fa-book-open me-2"></i>Vào học ngay
                            </a>
                        @else
                            <a href="{{ route('login.form') }}" class="btn btn-light btn-lg">
                                <i class="fas fa-tachometer-alt me-2"></i>Vào hệ thống
                            </a>
                        @endif
                    @else
                        <a href="{{ route('auth.register.form') }}" class="btn btn-light btn-lg">
                            <i class="fas fa-user-plus me-2"></i>Đăng ký miễn phí
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
<script>
    // Handle navbar transparency for home page
    window.addEventListener('scroll', function() {
        const navbar = document.querySelector('.navbar-main');
        if (window.scrollY > 100) {
            navbar.classList.add('scrolled');
            navbar.style.background = 'rgba(255, 255, 255, 0.95)';
        } else {
            navbar.classList.remove('scrolled');
            navbar.style.background = 'transparent';
        }
    });
    
    // Initial check
    document.addEventListener('DOMContentLoaded', function() {
        const navbar = document.querySelector('.navbar-main');
        if (window.scrollY > 100) {
            navbar.classList.add('scrolled');
            navbar.style.background = 'rgba(255, 255, 255, 0.95)';
        }
    });
</script>
@endsection