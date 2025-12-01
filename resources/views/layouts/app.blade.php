<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Hệ thống Giáo dục')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #4a6cf7;
            --primary-dark: #3a57e1;
            --secondary: #6c757d;
            --success: #28a745;
            --warning: #ffc107;
            --danger: #dc3545;
            --light: #f8f9fa;
            --dark: #343a40;
            --gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --shadow: 0 5px 15px rgba(0,0,0,0.08);
            --transition: all 0.3s ease;
        }
        
        * {
            font-family: 'Inter', sans-serif;
        }
        
        body {
            background-color: #f8fafc;
            padding-top: 76px; /* Height of fixed navbar */
        }
        
        /* Navbar Styles */
        .navbar-main {
            background: white !important;
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
            padding: 1rem 0;
            transition: var(--transition);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }
        
        .navbar-main.scrolled {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 24px;
            color: var(--primary) !important;
        }
        
        .nav-link {
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            color: var(--dark) !important;
            transition: var(--transition);
        }
        
        .nav-link:hover,
        .nav-link.active {
            color: var(--primary) !important;
        }
        
        .nav-link.active {
            font-weight: 600;
        }
        
        /* Dropdown Menu */
        .dropdown-menu {
            border: none;
            box-shadow: var(--shadow);
            border-radius: 10px;
            padding: 0.5rem;
        }
        
        .dropdown-item {
            padding: 0.75rem 1rem;
            border-radius: 5px;
            transition: var(--transition);
        }
        
        .dropdown-item:hover {
            background-color: var(--primary);
            color: white;
        }
        
        /* Buttons */
        .btn-primary {
            background: var(--primary);
            border: none;
            padding: 0.5rem 1.5rem;
            font-weight: 500;
            transition: var(--transition);
        }
        
        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(74, 108, 247, 0.3);
        }
        
        .btn-outline-primary {
            border: 2px solid var(--primary);
            color: var(--primary);
            font-weight: 500;
            padding: 0.5rem 1.5rem;
            transition: var(--transition);
        }
        
        .btn-outline-primary:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-2px);
        }
        
        .btn-gradient {
            background: var(--gradient);
            border: none;
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            transition: var(--transition);
        }
        
        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
            color: white;
        }
        
        /* Main Content */
        main {
            min-height: calc(100vh - 200px);
        }
        
        /* Footer */
        .footer {
            background: var(--dark);
            color: white;
            margin-top: auto;
        }
        
        .footer-links a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .footer-links a:hover {
            color: white;
        }
        
        .social-icons a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255,255,255,0.1);
            color: white;
            transition: var(--transition);
        }
        
        .social-icons a:hover {
            background: var(--primary);
            transform: translateY(-3px);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            body {
                padding-top: 66px;
            }
            
            .navbar-nav .nav-link {
                padding: 0.75rem 1rem !important;
            }
            
            .dropdown-menu {
                border: 1px solid rgba(0,0,0,0.1);
                margin-top: 0.5rem;
            }
        }
    </style>
    
    @yield('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-main navbar-light">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-graduation-cap me-2"></i>EduLearn
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" 
                           href="{{ route('home') }}">Trang chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}#courses">Khóa học</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}#teachers">Giảng viên</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}#about">Về chúng tôi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}#contact">Liên hệ</a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <div class="me-2">
                                    <div class="user-avatar-sm">
                                        {{ strtoupper(substr(Auth::user()->hoten, 0, 1)) }}
                                    </div>
                                </div>
                                <span>{{ Auth::user()->hoten }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                @if(Auth::user()->loai_taikhoan == 'sinhvien')
                                    <li><a class="dropdown-item" href="{{ route('sinhvien.dashboard') }}"><i class="fas fa-tachometer-alt me-2"></i>Bảng điều khiển</a></li>
                                    <li><a class="dropdown-item" href="{{ route('sinhvien.khoahoc') }}"><i class="fas fa-book me-2"></i>Khóa học của tôi</a></li>
                                @elseif(Auth::user()->loai_taikhoan == 'giangvien')
                                    <li><a class="dropdown-item" href="{{ route('giangvien.dashboard') }}"><i class="fas fa-tachometer-alt me-2"></i>Bảng điều khiển</a></li>
                                    <li><a class="dropdown-item" href="{{ route('giangvien.lophoc.index') }}"><i class="fas fa-users me-2"></i>Lớp học</a></li>
                                @elseif(Auth::user()->loai_taikhoan == 'admin')
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="fas fa-tachometer-alt me-2"></i>Quản trị</a></li>
                                @endif
                                <li><a class="dropdown-item" href="#"><i class="fas fa-user-circle me-2"></i>Hồ sơ</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="{{ route('logout') }}"
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
                            <a class="nav-link" href="{{ route('login.form') }}">
                                <i class="fas fa-sign-in-alt me-1"></i>Đăng nhập
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-gradient ms-2" href="{{ route('auth.register.form') }}">
                                <i class="fas fa-user-plus me-1"></i>Đăng ký
                            </a>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="py-4">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h4 class="fw-bold mb-4">
                        <i class="fas fa-graduation-cap me-2"></i>EduLearn
                    </h4>
                    <p class="mb-4">Hệ thống giáo dục trực tuyến hàng đầu với sứ mệnh mang lại tri thức chất lượng cho mọi người.</p>
                    <div class="social-icons">
                        <a href="#" class="me-2"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="me-2"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="me-2"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 mb-4">
                    <h5 class="fw-bold mb-4">Liên kết</h5>
                    <div class="footer-links d-flex flex-column">
                        <a href="{{ route('home') }}" class="mb-2">Trang chủ</a>
                        <a href="{{ route('home') }}#courses" class="mb-2">Khóa học</a>
                        <a href="{{ route('home') }}#teachers" class="mb-2">Giảng viên</a>
                        <a href="{{ route('home') }}#about" class="mb-2">Về chúng tôi</a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 mb-4">
                    <h5 class="fw-bold mb-4">Khóa học</h5>
                    <div class="footer-links d-flex flex-column">
                        <a href="#" class="mb-2">Lập trình & Công nghệ</a>
                        <a href="#" class="mb-2">Kinh doanh & Marketing</a>
                        <a href="#" class="mb-2">Ngoại ngữ</a>
                        <a href="#" class="mb-2">Kỹ năng mềm</a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 mb-4">
                    <h5 class="fw-bold mb-4">Liên hệ</h5>
                    <div class="footer-links">
                        <p class="mb-3">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            123 Đường Giáo Dục, Quận 1, TP.HCM
                        </p>
                        <p class="mb-3">
                            <i class="fas fa-phone me-2"></i>
                            (028) 1234 5678
                        </p>
                        <p class="mb-0">
                            <i class="fas fa-envelope me-2"></i>
                            info@edulearn.edu.vn
                        </p>
                    </div>
                </div>
            </div>
            <hr class="my-4" style="border-color: rgba(255,255,255,0.1);">
            <div class="text-center">
                <p class="mb-0">&copy; {{ date('Y') }} EduLearn. Tất cả các quyền được bảo lưu.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar-main');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
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
        });
    </script>
    
    @yield('scripts')
</body>
</html>