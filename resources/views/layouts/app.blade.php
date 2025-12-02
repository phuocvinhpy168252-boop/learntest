<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Hệ thống Giáo dục EduLearn')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    @vite(['resources/css/app.css'])
    
    <!-- Additional Styles -->
    @yield('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-main navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand animate-pulse" href="{{ route('home') }}">
                EduLearn
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }} animate-fade-up delay-100" 
                           href="{{ route('home') }}">Trang chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link animate-fade-up delay-200" href="{{ route('home') }}#courses">Khóa học</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link animate-fade-up delay-300" href="{{ route('home') }}#teachers">Giảng viên</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link animate-fade-up delay-400" href="{{ route('home') }}#about">Về chúng tôi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link animate-fade-up delay-500" href="{{ route('home') }}#contact">Liên hệ</a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center animate-fade-up delay-600" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <div class="me-2">
                                    <div class="user-avatar-sm animate-pulse">
                                        {{ strtoupper(substr(Auth::user()->hoten, 0, 1)) }}
                                    </div>
                                </div>
                                <span>{{ Auth::user()->hoten }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                @if(Auth::user()->loai_taikhoan == 'sinhvien')
                                    <li><a class="dropdown-item hover-lift" href="{{ route('sinhvien.khoahoc') }}"><i class="fas fa-book me-2"></i>Khóa học của tôi</a></li>
                                @elseif(Auth::user()->loai_taikhoan == 'giangvien')
                                    <li><a class="dropdown-item hover-lift" href="{{ route('giangvien.dashboard') }}"><i class="fas fa-tachometer-alt me-2"></i>Bảng điều khiển</a></li>
                                    <li><a class="dropdown-item hover-lift" href="{{ route('giangvien.lophoc.index') }}"><i class="fas fa-users me-2"></i>Lớp học</a></li>
                                @elseif(Auth::user()->loai_taikhoan == 'admin')
                                    <li><a class="dropdown-item hover-lift" href="{{ route('admin.dashboard') }}"><i class="fas fa-tachometer-alt me-2"></i>Quản trị</a></li>
                                @endif
                                <li><a class="dropdown-item hover-lift" href="#"><i class="fas fa-user-circle me-2"></i>Hồ sơ</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger hover-lift" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt me-2"></i>Đăng xuất
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link animate-fade-up delay-600 hover-lift" href="{{ route('login.form') }}">
                                <i class="fas fa-sign-in-alt me-1"></i>Đăng nhập
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-gradient ms-2 animate-pulse" href="{{ route('auth.register.form') }}">
                                <i class="fas fa-user-plus me-1"></i>Đăng ký
                            </a>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h4 class="fw-bold mb-4">
                        <i class="fas fa-graduation-cap me-2"></i>EduLearn
                    </h4>
                    <p class="mb-4">Hệ thống giáo dục trực tuyến hàng đầu với sứ mệnh mang lại tri thức chất lượng cho mọi người.</p>
                    <div class="social-icons">
                        <a href="#" class="me-2 hover-lift"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="me-2 hover-lift"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="me-2 hover-lift"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" class="hover-lift"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 mb-4">
                    <h5 class="fw-bold mb-4">Liên kết</h5>
                    <div class="footer-links d-flex flex-column">
                        <a href="{{ route('home') }}" class="mb-2 hover-lift">Trang chủ</a>
                        <a href="{{ route('home') }}#courses" class="mb-2 hover-lift">Khóa học</a>
                        <a href="{{ route('home') }}#teachers" class="mb-2 hover-lift">Giảng viên</a>
                        <a href="{{ route('home') }}#about" class="mb-2 hover-lift">Về chúng tôi</a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 mb-4">
                    <h5 class="fw-bold mb-4">Khóa học</h5>
                    <div class="footer-links d-flex flex-column">
                        <a href="#" class="mb-2 hover-lift">Lập trình & Công nghệ</a>
                        <a href="#" class="mb-2 hover-lift">Kinh doanh & Marketing</a>
                        <a href="#" class="mb-2 hover-lift">Ngoại ngữ</a>
                        <a href="#" class="mb-2 hover-lift">Kỹ năng mềm</a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 mb-4">
                    <h5 class="fw-bold mb-4">Liên hệ</h5>
                    <div class="footer-links">
                        <p class="mb-3 hover-lift">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            123 Đường Giáo Dục, Quận 1, TP.HCM
                        </p>
                        <p class="mb-3 hover-lift">
                            <i class="fas fa-phone me-2"></i>
                            (028) 1234 5678
                        </p>
                        <p class="mb-0 hover-lift">
                            <i class="fas fa-envelope me-2"></i>
                            info@edulearn.edu.vn
                        </p>
                    </div>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center">
                <p class="mb-0">&copy; {{ date('Y') }} EduLearn. Tất cả các quyền được bảo lưu.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Main Script -->
    <script>
        // Navbar scroll effect nâng cao
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar-main');
            const scrolled = window.pageYOffset;
            
            if (scrolled > 50) {
                navbar.classList.add('scrolled');
                // Thêm hiệu ứng blur động
                navbar.style.backdropFilter = `blur(${Math.min(25, 15 + (scrolled * 0.01))}px) saturate(180%)`;
                // Thêm hiệu ứng gradient background
                navbar.style.background = `rgba(255, 255, 255, ${Math.min(0.98, 0.92 + (scrolled * 0.001))}) !important`;
            } else {
                navbar.classList.remove('scrolled');
                navbar.style.backdropFilter = 'blur(15px) saturate(180%)';
                navbar.style.background = 'rgba(255, 255, 255, 0.92) !important';
            }
            
            // Hiệu ứng cho các nav-link
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                if (scrolled > 100) {
                    link.style.transform = 'translateY(0)';
                }
            });
        });

        // Smooth scroll for navigation links
        document.addEventListener('DOMContentLoaded', function() {
            // Smooth scroll for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    const href = this.getAttribute('href');
                    
                    // Only handle anchor links, not empty ones
                    if (href !== '#') {
                        e.preventDefault();
                        const targetElement = document.querySelector(href);
                        if (targetElement) {
                            window.scrollTo({
                                top: targetElement.offsetTop - 80,
                                behavior: 'smooth'
                            });
                            
                            // Thêm hiệu ứng active cho nav-link tương ứng
                            document.querySelectorAll('.nav-link').forEach(link => {
                                link.classList.remove('active');
                            });
                            this.classList.add('active');
                        }
                    }
                });
            });

            // Auto-hide alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });

            // Hiệu ứng hover cho tất cả các element có class hover-lift
            document.querySelectorAll('.hover-lift').forEach(element => {
                element.addEventListener('mousemove', function(e) {
                    if (!this.classList.contains('navbar-brand') && !this.classList.contains('btn-gradient')) {
                        this.style.transform = 'translateY(-3px) scale(1.02)';
                        this.style.boxShadow = 'var(--shadow-lg)';
                    }
                });
                
                element.addEventListener('mouseleave', function() {
                    if (!this.classList.contains('navbar-brand') && !this.classList.contains('btn-gradient')) {
                        this.style.transform = 'translateY(0) scale(1)';
                        this.style.boxShadow = 'var(--shadow-md)';
                    }
                });
            });

            // Hiệu ứng ripple cho các button
            document.querySelectorAll('.btn-gradient, .btn-light, .btn-outline-light').forEach(button => {
                button.addEventListener('click', function(e) {
                    const ripple = document.createElement('span');
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;
                    
                    ripple.style.width = ripple.style.height = size + 'px';
                    ripple.style.left = x + 'px';
                    ripple.style.top = y + 'px';
                    ripple.classList.add('ripple-effect');
                    
                    this.appendChild(ripple);
                    
                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                });
            });

            // Thêm CSS cho ripple effect
            const style = document.createElement('style');
            style.textContent = `
                .ripple-effect {
                    position: absolute;
                    border-radius: 50%;
                    background: rgba(255, 255, 255, 0.6);
                    transform: scale(0);
                    animation: ripple-animation 0.6s linear;
                }
                
                @keyframes ripple-animation {
                    to {
                        transform: scale(4);
                        opacity: 0;
                    }
                }
                
                .btn-gradient, .btn-light, .btn-outline-light {
                    position: relative;
                    overflow: hidden;
                }
            `;
            document.head.appendChild(style);
        });
    </script>
    
    <!-- Additional Scripts -->
    @yield('scripts')
</body>
</html>