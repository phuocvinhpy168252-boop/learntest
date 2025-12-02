@extends('layouts.app')

@section('title', 'EduLearn - Hệ thống Giáo dục Trực tuyến Hàng đầu')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-bg-elements">
            <div class="floating-element"></div>
            <div class="floating-element"></div>
            <div class="floating-element"></div>
        </div>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 hero-content animate-slide-left">
                    <span class="hero-badge animate-pulse">
                        <i class="fas fa-star me-2"></i>Nền tảng học tập hàng đầu
                    </span>
                    <h1 class="hero-title">Học tập thông minh, <br>Tương lai rộng mở</h1>
                    <p class="hero-subtitle">
                        Khám phá hệ thống giáo dục trực tuyến với các khóa học chất lượng cao, 
                        giảng viên giàu kinh nghiệm và phương pháp học tập hiện đại.
                    </p>
                    <div class="hero-actions">
                        @auth
                            @if(auth()->user()->loai_taikhoan == 'sinhvien')
                                <a href="{{ route('sinhvien.khoahoc') }}" class="btn-hero-primary hover-lift">
                                    <i class="fas fa-rocket me-2"></i>Vào học ngay
                                </a>
                            @elseif(auth()->user()->loai_taikhoan == 'giangvien')
                                <a href="{{ route('giangvien.dashboard') }}" class="btn-hero-primary hover-lift">
                                    <i class="fas fa-chalkboard-teacher me-2"></i>Bảng điều khiển
                                </a>
                            @endif
                        @else
                            <a href="{{ route('auth.register.form') }}" class="btn-hero-primary hover-lift">
                                <i class="fas fa-rocket me-2"></i>Bắt đầu học ngay
                            </a>
                            <a href="{{ route('home') }}#courses" class="btn-hero-secondary hover-lift">
                                <i class="fas fa-search me-2"></i>Tìm khóa học
                            </a>
                        @endauth
                    </div>
                    <div class="hero-stats">
                        <div class="stat-box hover-lift">
                            <div class="stat-number">{{ $tongLopHoc }}+</div>
                            <div class="stat-label">Lớp học</div>
                        </div>
                        <div class="stat-box hover-lift">
                            <div class="stat-number">{{ $tongGiangVien }}+</div>
                            <div class="stat-label">Giảng viên</div>
                        </div>
                        <div class="stat-box hover-lift">
                            <div class="stat-number">{{ $tongMonHoc }}+</div>
                            <div class="stat-label">Môn học</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 hero-visual animate-slide-right delay-300">
                    <div class="hero-card-container hover-lift">
                        <div class="hero-card">
                            <div class="hero-card-header">
                                <i class="fas fa-laptop-code"></i>
                                <h4 class="mb-0 mt-2">Học trực tuyến</h4>
                                <p class="mb-0 opacity-75">Mọi lúc, mọi nơi</p>
                            </div>
                            <div class="hero-features">
                                <div class="hero-feature hover-lift">
                                    <i class="fas fa-video"></i>
                                    <div>
                                        <h6 class="mb-1">Bài giảng video</h6>
                                        <p class="mb-0 text-muted">Chất lượng cao, dễ hiểu</p>
                                    </div>
                                </div>
                                <div class="hero-feature hover-lift">
                                    <i class="fas fa-users"></i>
                                    <div>
                                        <h6 class="mb-1">Tương tác trực tiếp</h6>
                                        <p class="mb-0 text-muted">Với giảng viên và học viên</p>
                                    </div>
                                </div>
                                <div class="hero-feature hover-lift">
                                    <i class="fas fa-tasks"></i>
                                    <div>
                                        <h6 class="mb-1">Bài tập thực hành</h6>
                                        <p class="mb-0 text-muted">Cải thiện kỹ năng nhanh chóng</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Courses -->
    <section id="courses" class="courses-section">
        <div class="container">
            <div class="section-header animate-fade-up">
                <h2 class="section-title">Khóa học nổi bật</h2>
                <p class="section-subtitle">Khám phá các khóa học chất lượng cao được thiết kế bởi chuyên gia hàng đầu</p>
            </div>
            <div class="row g-4">
                @forelse($lopHocNoiBat as $index => $lop)
                <div class="col-lg-4 col-md-6 animate-fade-up delay-{{ ($index % 3) * 100 }}">
                    <div class="course-card hover-lift">
                        <div class="course-badge animate-pulse">Bán chạy</div>
                        <div class="course-media">
                            <div class="w-100 h-100 d-flex align-items-center justify-content-center" 
                                 style="background: linear-gradient(135deg, var(--primary-color), var(--accent-color));">
                                <i class="fas fa-{{ $lop->monHoc->ten_mon_hoc == 'Tiếng Anh' ? 'language' : 'code' }} text-white display-4"></i>
                            </div>
                            <div class="course-media-overlay">
                                <div class="course-duration">
                                    <i class="far fa-clock"></i>
                                    <span>12 tuần</span>
                                </div>
                            </div>
                        </div>
                        <div class="course-content">
                            <div class="course-category animate-pulse">{{ $lop->monHoc->ten_mon_hoc }}</div>
                            <h3 class="course-title">{{ $lop->ten_lop }}</h3>
                            <p class="course-description">{{ $lop->mo_ta ?? 'Khóa học chất lượng cao với phương pháp giảng dạy hiện đại' }}</p>
                            <div class="course-instructor hover-lift">
                                <div class="instructor-avatar animate-pulse">
                                    {{ substr($lop->giangVien->ten, 0, 1) }}
                                </div>
                                <div class="instructor-info">
                                    <h6>{{ $lop->giangVien->ten }}</h6>
                                    <small>Giảng viên {{ $lop->monHoc->ten_mon_hoc }}</small>
                                </div>
                            </div>
                            <div class="course-footer">
                                <div class="course-price">Miễn phí</div>
                                @auth
                                    @if(auth()->user()->loai_taikhoan == 'sinhvien')
                                        <a href="{{ route('sinhvien.lophoc.baigiang', $lop->ma_lop) }}" class="btn-gradient btn-sm hover-lift">
                                            Tham gia ngay
                                        </a>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center animate-fade-up">
                    <div class="alert alert-light hover-lift" role="alert">
                        <i class="fas fa-info-circle me-2"></i>Chưa có khóa học nào
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Featured Teachers -->
    <section id="teachers" class="teachers-section">
        <div class="container">
            <div class="section-header animate-fade-up">
                <h2 class="section-title">Đội ngũ giảng viên</h2>
                <p class="section-subtitle">Học tập cùng các giảng viên giàu kinh nghiệm và chuyên môn cao</p>
            </div>
            <div class="row g-4">
                @forelse($giangVienNoiBat as $index => $giangVien)
                <div class="col-lg-3 col-md-6 animate-fade-up delay-{{ ($index % 4) * 100 }}">
                    <div class="teacher-card hover-lift">
                        <div class="teacher-avatar-container">
                            <div class="teacher-avatar animate-pulse">
                                {{ substr($giangVien->ten, 0, 1) }}
                            </div>
                        </div>
                        <h4 class="teacher-name">{{ $giangVien->ten }}</h4>
                        <p class="teacher-title">{{ $giangVien->mon_day ?? 'Giảng viên chính' }}</p>
                        <div class="teacher-rating">
                            <i class="fas fa-star animate-pulse"></i>
                            <i class="fas fa-star animate-pulse delay-100"></i>
                            <i class="fas fa-star animate-pulse delay-200"></i>
                            <i class="fas fa-star animate-pulse delay-300"></i>
                            <i class="fas fa-star-half-alt animate-pulse delay-400"></i>
                            <span class="text-muted ms-1">(4.8)</span>
                        </div>
                        <div class="teacher-skills">
                            <span class="skill-tag hover-lift">Lập trình</span>
                            <span class="skill-tag hover-lift">AI</span>
                            <span class="skill-tag hover-lift">Data</span>
                        </div>
                        <div class="mt-3">
                            <span class="text-primary fw-bold">{{ $giangVien->trinh_do ?? 'Thạc sĩ Công nghệ' }}</span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center animate-fade-up">
                    <div class="alert alert-light hover-lift" role="alert">
                        <i class="fas fa-info-circle me-2"></i>Chưa có giảng viên nào
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Features -->
    <section id="about" class="features-section">
        <div class="container">
            <div class="section-header animate-fade-up">
                <h2 class="section-title">Tại sao chọn chúng tôi?</h2>
                <p class="section-subtitle">Trải nghiệm học tập đột phá với các tính năng độc quyền</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-3 col-md-6 animate-fade-up">
                    <div class="feature-card hover-lift">
                        <div class="feature-icon animate-pulse">
                            <i class="fas fa-laptop"></i>
                        </div>
                        <h5 class="feature-title">Học mọi lúc mọi nơi</h5>
                        <p class="feature-description">Truy cập khoá học từ bất kỳ thiết bị nào, học tập linh hoạt theo thời gian của bạn.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 animate-fade-up delay-100">
                    <div class="feature-card hover-lift">
                        <div class="feature-icon animate-pulse">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h5 class="feature-title">Theo dõi tiến độ</h5>
                        <p class="feature-description">Hệ thống theo dõi tiến độ học tập thông minh giúp bạn đánh giá và cải thiện hiệu quả học tập.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 animate-fade-up delay-200">
                    <div class="feature-card hover-lift">
                        <div class="feature-icon animate-pulse">
                            <i class="fas fa-comments"></i>
                        </div>
                        <h5 class="feature-title">Tương tác đa chiều</h5>
                        <p class="feature-description">Tương tác trực tiếp với giảng viên và học viên thông qua diễn đàn và phòng học ảo.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 animate-fade-up delay-300">
                    <div class="feature-card hover-lift">
                        <div class="feature-icon animate-pulse">
                            <i class="fas fa-certificate"></i>
                        </div>
                        <h5 class="feature-title">Chứng chỉ công nhận</h5>
                        <p class="feature-description">Nhận chứng chỉ hoàn thành khóa học được công nhận bởi hệ thống giáo dục.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-content animate-fade-up">
                <h2 class="cta-title">Sẵn sàng bắt đầu hành trình học tập?</h2>
                <p class="cta-subtitle">Đăng ký ngay hôm nay để nhận ưu đãi đặc biệt và trải nghiệm hệ thống học tập tốt nhất.</p>
                <div class="cta-buttons">
                    @auth
                        @if(auth()->user()->loai_taikhoan == 'sinhvien')
                            <a href="{{ route('sinhvien.khoahoc') }}" class="btn-light hover-lift">
                                <i class="fas fa-book-open me-2"></i>Vào học ngay
                            </a>
                        @else
                            <a href="{{ route('login.form') }}" class="btn-light hover-lift">
                                <i class="fas fa-tachometer-alt me-2"></i>Vào hệ thống
                            </a>
                        @endif
                    @else
                        <a href="{{ route('auth.register.form') }}" class="btn-light hover-lift">
                            <i class="fas fa-user-plus me-2"></i>Đăng ký miễn phí
                        </a>
                        <a href="{{ route('login.form') }}" class="btn-outline-light hover-lift">
                            <i class="fas fa-sign-in-alt me-2"></i>Đăng nhập
                        </a>
                    @endauth
                </div>
                <p class="mt-4 text-white opacity-75 hover-lift">
                    Đã có <strong>10,000+</strong> học viên tin tưởng lựa chọn
                </p>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Intersection Observer cho animations
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const animationClass = entry.target.dataset.animation || 'animate-fade-up';
                    entry.target.classList.add(animationClass);
                    entry.target.style.opacity = '1';
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -100px 0px'
        });

        // Quan sát tất cả các phần tử có class animate-*
        document.querySelectorAll('[class*="animate-"]').forEach(el => {
            observer.observe(el);
        });

        // Enhanced hover 3D effect cho cards
        document.querySelectorAll('.hover-lift').forEach(card => {
            card.addEventListener('mousemove', function(e) {
                if (this.classList.contains('course-card') || this.classList.contains('teacher-card') || this.classList.contains('feature-card')) {
                    const rect = this.getBoundingClientRect();
                    const x = e.clientX - rect.left;
                    const y = e.clientY - rect.top;
                    
                    const centerX = rect.width / 2;
                    const centerY = rect.height / 2;
                    
                    const rotateY = (x - centerX) / 20;
                    const rotateX = (centerY - y) / 20;
                    
                    this.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateZ(30px)`;
                    this.style.boxShadow = 'var(--shadow-xxl)';
                }
            });
            
            card.addEventListener('mouseleave', function() {
                if (this.classList.contains('course-card') || this.classList.contains('teacher-card') || this.classList.contains('feature-card')) {
                    this.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) translateZ(0)';
                    this.style.boxShadow = 'var(--shadow-md)';
                }
            });
        });

        // Parallax effect cho hero section
        window.addEventListener('scroll', function() {
            const scrolled = window.pageYOffset;
            const heroElements = document.querySelectorAll('.floating-element');
            
            heroElements.forEach((el, index) => {
                const speed = 0.1 + (index * 0.05);
                el.style.transform = `translateY(${scrolled * speed}px) rotate(${scrolled * 0.01}deg)`;
            });
            
            // Hero title animation on scroll
            const heroTitle = document.querySelector('.hero-title');
            if (heroTitle) {
                heroTitle.style.transform = `translateY(${scrolled * 0.1}px)`;
            }
        });
    });
</script>
@endsection