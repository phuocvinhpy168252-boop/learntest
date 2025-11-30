<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - Hệ thống Giáo dục')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom Admin CSS -->
    @vite(['resources/css/admin.css'])
</head>
<body class="admin-body">
    <!-- Sidebar -->
    <div class="admin-container">
        <nav class="admin-sidebar">
            <div class="sidebar-header">
                <div class="logo-container">
                    <i class="fas fa-graduation-cap logo-icon"></i>
                    <span class="logo-text">EduAdmin</span>
                </div>
                <button class="sidebar-toggle" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
            </div>

            <ul class="sidebar-nav">
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-chart-pie"></i>
                        <span class="nav-text">Tổng quan</span>
                    </a>
                </li>
            
                <!-- Quản lý sinh viên với dropdown -->
                <li class="nav-item has-submenu">
                    <a href="#" class="nav-link {{ request()->routeIs('admin.sinhvien.*') ? 'active' : '' }}">
                        <i class="fas fa-user-graduate"></i>
                        <span class="nav-text">Quản lý sinh viên</span>
                        <i class="fas fa-chevron-down dropdown-arrow"></i>
                    </a>
                    <ul class="submenu">
                        <li>
                            <a href="{{ route('admin.sinhvien.index') }}" class="submenu-link {{ request()->routeIs('admin.sinhvien.index') ? 'active' : '' }}">
                                <i class="fas fa-list me-2"></i>
                                Danh sách sinh viên
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.sinhvien.createSV') }}" 
                            class="submenu-link {{ request()->routeIs('admin.sinhvien.createSV') ? 'active' : '' }}">
                                <i class="fas fa-plus-circle me-2"></i>
                                Thêm sinh viên
                            </a>
                        </li>
                        <li>
                            <a href="#" class="submenu-link">
                                <i class="fas fa-chart-bar me-2"></i>
                                Thống kê sinh viên
                            </a>
                        </li>
                        <li>
                            <a href="#" class="submenu-link">
                                <i class="fas fa-file-export me-2"></i>
                                Xuất báo cáo
                            </a>
                        </li>
                    </ul>
                </li>
                            
                <!-- Quản lý giảng viên với dropdown -->
                <li class="nav-item has-submenu">
                    <a href="#" class="nav-link {{ request()->routeIs('admin.giangvien.*') ? 'active' : '' }}">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <span class="nav-text">Quản lý giảng viên</span>
                        <i class="fas fa-chevron-down dropdown-arrow"></i>
                    </a>
                    <ul class="submenu">
                        <li>
                            <a href="{{ route('admin.giangvien.index') }}" class="submenu-link {{ request()->routeIs('admin.giangvien.index') ? 'active' : '' }}">
                                <i class="fas fa-list me-2"></i>
                                Danh sách giảng viên
                            </a>
                        </li>
                    <li>
                        <a href="{{ route('admin.giangvien.createGV') }}" 
                        class="submenu-link {{ request()->routeIs('admin.giangvien.createGV') ? 'active' : '' }}">
                            <i class="fas fa-plus-circle me-2"></i>
                            Thêm giảng viên
                        </a>
                    </li>
                        <li>
                            <a href="#" class="submenu-link">
                                <i class="fas fa-chart-bar me-2"></i>
                                Thống kê giảng viên
                            </a>
                        </li>
                        <li>
                            <a href="#" class="submenu-link">
                                <i class="fas fa-file-export me-2"></i>
                                Xuất báo cáo
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- Quản lý môn học với dropdown -->
                <li class="nav-item has-submenu">
                    <a href="#" class="nav-link {{ request()->routeIs('admin.monhoc.*') ? 'active' : '' }}">
                        <i class="fas fa-book"></i>
                        <span class="nav-text">Quản lý Khóa Học</span>
                        <i class="fas fa-chevron-down dropdown-arrow"></i>
                    </a>
                    <ul class="submenu">
                        <li>
                            <a href="{{ route('admin.monhoc.index') }}" class="submenu-link {{ request()->routeIs('admin.monhoc.index') ? 'active' : '' }}">
                                <i class="fas fa-list me-2"></i>
                                Danh sách môn học
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.monhoc.create') }}" 
                            class="submenu-link {{ request()->routeIs('admin.monhoc.create') ? 'active' : '' }}">
                                <i class="fas fa-plus-circle me-2"></i>
                                Thêm môn học
                            </a>
                        </li>
                        <li>
                            <a href="#" class="submenu-link">
                                <i class="fas fa-chart-bar me-2"></i>
                                Thống kê môn học
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-book"></i>
                        <span class="nav-text">Khóa học</span>
                        <span class="badge bg-warning">12</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-file-invoice-dollar"></i>
                        <span class="nav-text">Hóa đơn</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-cog"></i>
                        <span class="nav-text">Cài đặt</span>
                    </a>
                </li>
            </ul>

            <div class="sidebar-footer">
                <div class="user-info">
                    <div class="user-avatar">
                        <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name ?? Auth::user()->hoten ?? 'Admin' }}&background=6366f1&color=fff" alt="Avatar">
                    </div>
                    <div class="user-details">
                        <div class="user-name">{{ Auth::user()->name ?? Auth::user()->hoten ?? 'Quản trị viên' }}</div>
                        <div class="user-role">Quản trị viên</div>
                    </div>
                </div>
                <a href="{{ route('logout') }}" class="logout-btn"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="nav-text">Đăng xuất</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                    @csrf
                </form>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="admin-main">
            <!-- Top Bar -->
            <header class="admin-header">
                <div class="header-left">
                    <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
                </div>
                <div class="header-right">
                    <div class="header-actions">
                        <button class="action-btn" data-bs-toggle="tooltip" title="Thông báo">
                            <i class="fas fa-bell"></i>
                            <span class="notification-badge">3</span>
                        </button>
                        <button class="action-btn" data-bs-toggle="tooltip" title="Tin nhắn">
                            <i class="fas fa-envelope"></i>
                            <span class="notification-badge">5</span>
                        </button>
                        <div class="dropdown">
                            <button class="action-btn profile-dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name ?? Auth::user()->hoten ?? 'Admin' }}&background=6366f1&color=fff" alt="Avatar" class="header-avatar">
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Hồ sơ</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Cài đặt</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt me-2"></i>Đăng xuất
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <div class="admin-content">
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom Admin JS -->
    @vite(['resources/js/admin.js'])

    <script>
        // JavaScript cho sidebar toggle và dropdown menu
        document.addEventListener('DOMContentLoaded', function() {
            // Sidebar toggle
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.querySelector('.admin-sidebar');
            const adminMain = document.querySelector('.admin-main');
            
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('collapsed');
                    adminMain.classList.toggle('sidebar-collapsed');
                });
            }

            // Dropdown menu functionality
            const dropdownItems = document.querySelectorAll('.nav-item.has-submenu');
            
            dropdownItems.forEach(item => {
                const link = item.querySelector('.nav-link');
                
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Đóng tất cả dropdown khác
                    dropdownItems.forEach(otherItem => {
                        if (otherItem !== item) {
                            otherItem.classList.remove('active');
                        }
                    });
                    
                    // Toggle dropdown hiện tại
                    item.classList.toggle('active');
                });
            });

            // Initialize Bootstrap tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Close dropdown khi click outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.nav-item.has-submenu')) {
                    dropdownItems.forEach(item => {
                        item.classList.remove('active');
                    });
                }
            });
        });

        // Auto hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
        });
    </script>

    <style>
        /* Additional styles for the fixed issues */
        .admin-sidebar.collapsed {
            width: var(--sidebar-collapsed);
        }

        .admin-sidebar.collapsed .nav-text,
        .admin-sidebar.collapsed .logo-text,
        .admin-sidebar.collapsed .user-details,
        .admin-sidebar.collapsed .logout-btn .nav-text {
            display: none;
        }

        .admin-sidebar.collapsed .sidebar-header {
            justify-content: center;
            padding: 1rem;
        }

        .admin-sidebar.collapsed .submenu {
            display: none !important;
        }

        .admin-main.sidebar-collapsed {
            margin-left: var(--sidebar-collapsed);
        }

        /* Ensure dropdown menus work properly */
        .dropdown-menu {
            border: none;
            box-shadow: var(--shadow);
            border-radius: var(--border-radius);
        }

        .dropdown-item {
            padding: 0.75rem 1rem;
            transition: var(--transition);
        }

        .dropdown-item:hover {
            background-color: var(--primary-light);
            color: white;
        }

        /* Responsive fixes */
        @media (max-width: 768px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }
            
            .admin-sidebar.mobile-open {
                transform: translateX(0);
            }
            
            .admin-main {
                margin-left: 0;
            }
        }
    </style>
</body>
</html>