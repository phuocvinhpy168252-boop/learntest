// Admin JavaScript với hiệu ứng nâng cao
document.addEventListener('DOMContentLoaded', function() {
    // Khởi tạo tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Toggle sidebar
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.querySelector('.admin-sidebar');
    const mainContent = document.querySelector('.admin-main');

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
            
            if (sidebar.classList.contains('collapsed')) {
                sidebar.style.width = 'var(--sidebar-collapsed)';
                mainContent.style.marginLeft = 'var(--sidebar-collapsed)';
            } else {
                sidebar.style.width = 'var(--sidebar-width)';
                mainContent.style.marginLeft = 'var(--sidebar-width)';
            }
        });
    }

    // Hiệu ứng số đếm cho thống kê
    const initCounterAnimation = () => {
        const counters = document.querySelectorAll('.counter');
        
        counters.forEach(counter => {
            const target = +counter.getAttribute('data-target');
            const duration = 2000;
            const step = target / (duration / 16);
            let current = 0;
            
            const updateCounter = () => {
                current += step;
                if (current < target) {
                    counter.textContent = Math.ceil(current).toLocaleString();
                    requestAnimationFrame(updateCounter);
                } else {
                    counter.textContent = target.toLocaleString();
                }
            };
            
            // Kích hoạt khi phần tử xuất hiện trong viewport
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        updateCounter();
                        observer.unobserve(entry.target);
                    }
                });
            });
            
            observer.observe(counter);
        });
    };

    // Hiệu ứng hover nâng cao cho cards
    const initCardHoverEffects = () => {
        const cards = document.querySelectorAll('.admin-card');
        
        cards.forEach(card => {
            card.addEventListener('mouseenter', function(e) {
                const rect = this.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                
                this.style.setProperty('--mouse-x', `${x}px`);
                this.style.setProperty('--mouse-y', `${y}px`);
                
                this.classList.add('hover-active');
            });
            
            card.addEventListener('mouseleave', function() {
                this.classList.remove('hover-active');
            });
        });
    };

    // Hiệu ứng cho buttons
    const initButtonEffects = () => {
        const buttons = document.querySelectorAll('.btn-admin');
        
        buttons.forEach(button => {
            button.addEventListener('mouseenter', function(e) {
                const x = e.pageX - this.offsetLeft;
                const y = e.pageY - this.offsetTop;
                
                this.style.setProperty('--x', `${x}px`);
                this.style.setProperty('--y', `${y}px`);
            });
            
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Tạo hiệu ứng ripple
                const ripple = document.createElement('span');
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;
                
                ripple.style.cssText = `
                    width: ${size}px;
                    height: ${size}px;
                    left: ${x}px;
                    top: ${y}px;
                    background: rgba(255, 255, 255, 0.5);
                    border-radius: 50%;
                    position: absolute;
                    animation: ripple 0.6s ease-out;
                `;
                
                this.appendChild(ripple);
                
                setTimeout(() => {
                    ripple.remove();
                }, 600);
            });
        });
    };

    // Chart initialization (giả lập)
    const initCharts = () => {
        // Giả lập biểu đồ - trong thực tế sẽ sử dụng Chart.js hoặc thư viện khác
        const chartContainers = document.querySelectorAll('.chart-placeholder');
        
        chartContainers.forEach(container => {
            // Thêm hiệu ứng loading
            container.innerHTML = '<div class="loading-spinner"></div>';
            
            setTimeout(() => {
                container.innerHTML = `
                    <div style="height: 300px; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #f8fafc, #e2e8f0); border-radius: 8px;">
                        <div class="text-center">
                            <i class="fas fa-chart-bar fa-3x text-primary mb-3"></i>
                            <p class="text-muted">Biểu đồ sẽ được tải ở đây</p>
                        </div>
                    </div>
                `;
            }, 1500);
        });
    };

    // Notification system
    const initNotifications = () => {
        const notificationButtons = document.querySelectorAll('.action-btn');
        
        notificationButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                // Hiệu ứng nhấp nháy
                this.style.transform = 'scale(0.9)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 150);
                
                // Giả lập thông báo
                if (this.querySelector('.fa-bell')) {
                    showAdminNotification('Bạn có 3 thông báo mới', 'info');
                }
            });
        });
    };

    // Dropdown Menu với Hover Effect
    const initDropdownMenus = () => {
        const dropdownItems = document.querySelectorAll('.nav-item.has-submenu');
        let timeout = null;
        
        dropdownItems.forEach(item => {
            const link = item.querySelector('.nav-link');
            const submenu = item.querySelector('.submenu');

            // Mouse enter - mở dropdown
            item.addEventListener('mouseenter', (e) => {
                clearTimeout(timeout);
                openDropdown(item);
            });

            // Mouse leave - đóng dropdown sau delay
            item.addEventListener('mouseleave', (e) => {
                timeout = setTimeout(() => {
                    closeDropdown(item);
                }, 300);
            });

            // Giữ dropdown mở khi hover vào submenu
            if (submenu) {
                submenu.addEventListener('mouseenter', (e) => {
                    clearTimeout(timeout);
                });

                submenu.addEventListener('mouseleave', (e) => {
                    timeout = setTimeout(() => {
                        closeDropdown(item);
                    }, 300);
                });
            }

            // Xử lý click trên mobile
            link.addEventListener('click', (e) => {
                if (window.innerWidth <= 768) {
                    e.preventDefault();
                    toggleDropdown(item);
                }
            });
        });

        // Đóng dropdown khi click ra ngoài
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.nav-item.has-submenu')) {
                closeAllDropdowns();
            }
        });

        // Đóng tất cả dropdown khi di chuột ra khỏi sidebar
        const sidebar = document.querySelector('.admin-sidebar');
        sidebar.addEventListener('mouseleave', (e) => {
            closeAllDropdowns();
        });

        function openDropdown(item) {
            closeAllDropdownsExcept(item);
            item.classList.add('active');
            animateDropdown(item, true);
        }

        function closeDropdown(item) {
            item.classList.remove('active');
            animateDropdown(item, false);
        }

        function closeAllDropdowns() {
            dropdownItems.forEach(item => {
                closeDropdown(item);
            });
        }

        function closeAllDropdownsExcept(exceptItem) {
            dropdownItems.forEach(item => {
                if (item !== exceptItem) {
                    closeDropdown(item);
                }
            });
        }

        function toggleDropdown(item) {
            if (item.classList.contains('active')) {
                closeDropdown(item);
            } else {
                openDropdown(item);
            }
        }

        function animateDropdown(item, opening) {
            const submenu = item.querySelector('.submenu');
            if (!submenu) return;

            if (opening) {
                submenu.style.display = 'block';
                const height = submenu.scrollHeight;
                submenu.style.maxHeight = '0';
                submenu.style.opacity = '0';
                
                requestAnimationFrame(() => {
                    submenu.style.transition = 'max-height 0.3s ease, opacity 0.3s ease';
                    submenu.style.maxHeight = height + 'px';
                    submenu.style.opacity = '1';
                });
            } else {
                submenu.style.maxHeight = '0';
                submenu.style.opacity = '0';
                
                setTimeout(() => {
                    submenu.style.display = 'none';
                    submenu.style.transition = '';
                }, 300);
            }
        }

        // Xử lý responsive
        function handleResize() {
            if (window.innerWidth > 768) {
                closeAllDropdowns();
            }
        }

        window.addEventListener('resize', handleResize);
    };

    // Hiển thị thông báo
    function showAdminNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        notification.style.cssText = `
            top: 90px;
            right: 20px;
            z-index: 1050;
            min-width: 300px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            border: none;
            border-radius: 12px;
            animation: slideInRight 0.3s ease;
        `;
        
        const icons = {
            info: 'fas fa-info-circle',
            success: 'fas fa-check-circle',
            warning: 'fas fa-exclamation-triangle',
            danger: 'fas fa-exclamation-circle'
        };
        
        notification.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="${icons[type]} me-2 text-${type}"></i>
                <span>${message}</span>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Tự động xóa sau 5 giây
        setTimeout(() => {
            if (notification.parentNode) {
                notification.style.animation = 'slideInRight 0.3s ease reverse';
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 300);
            }
        }, 5000);
    }

    // Khởi tạo tất cả hiệu ứng
    const initAllEffects = () => {
        initCounterAnimation();
        initCardHoverEffects();
        initButtonEffects();
        initCharts();
        initNotifications();
        initDropdownMenus(); // Thêm dropdown menus
        
        // Thêm CSS cho hiệu ứng ripple và dropdown
        const style = document.createElement('style');
        style.textContent = `
            @keyframes ripple {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
            
            .admin-card {
                position: relative;
                overflow: hidden;
            }
            
            .admin-card::before {
                content: '';
                position: absolute;
                top: var(--mouse-y, 50%);
                left: var(--mouse-x, 50%);
                width: 0;
                height: 0;
                background: radial-gradient(circle, rgba(99, 102, 241, 0.1) 0%, transparent 70%);
                transform: translate(-50%, -50%);
                transition: width 0.3s, height 0.3s;
            }
            
            .admin-card.hover-active::before {
                width: 300px;
                height: 300px;
            }
            
            .btn-admin {
                position: relative;
                overflow: hidden;
            }

            /* Dropdown Menu Styles với hover */
            .nav-item.has-submenu {
                position: relative;
            }

            .nav-item.has-submenu > .nav-link {
                display: flex;
                align-items: center;
                justify-content: space-between;
            }

            .dropdown-arrow {
                font-size: 0.8rem;
                transition: var(--transition);
                margin-left: auto;
            }

            .nav-item.has-submenu.active .dropdown-arrow {
                transform: rotate(180deg);
            }

            .submenu {
                list-style: none;
                padding: 0;
                margin: 0;
                background: rgba(255, 255, 255, 0.1);
                border-radius: 8px;
                margin: 0.5rem 1rem;
                overflow: hidden;
                max-height: 0;
                transition: max-height 0.3s ease, opacity 0.3s ease;
                opacity: 0;
                display: none;
            }

            .nav-item.has-submenu.active .submenu {
                display: block;
                opacity: 1;
            }

            .submenu-link {
                display: flex;
                align-items: center;
                padding: 0.75rem 1rem;
                color: rgba(255, 255, 255, 0.8);
                text-decoration: none;
                transition: var(--transition);
                font-size: 0.9rem;
                border-left: 3px solid transparent;
            }

            .submenu-link:hover {
                background: rgba(255, 255, 255, 0.1);
                color: white;
                border-left-color: var(--secondary-color);
                padding-left: 1.25rem;
            }

            .submenu-link.active {
                background: rgba(255, 255, 255, 0.15);
                color: white;
                border-left-color: var(--secondary-color);
            }

            .submenu-link i {
                width: 20px;
                font-size: 0.9rem;
            }

            /* Hiệu ứng hover cho menu cha khi submenu active */
            .nav-item.has-submenu.active > .nav-link {
                background: rgba(255, 255, 255, 0.2);
                color: white;
            }

            /* Responsive cho dropdown */
            @media (max-width: 768px) {
                .submenu {
                    margin: 0.5rem 0.5rem;
                }
                
                .submenu-link {
                    padding: 0.6rem 0.8rem;
                    font-size: 0.85rem;
                }
                
                /* Trên mobile, sử dụng click thay vì hover */
                .nav-item.has-submenu .submenu {
                    display: none !important;
                }
                
                .nav-item.has-submenu.active .submenu {
                    display: block !important;
                }
            }
        `;
        document.head.appendChild(style);
    };

    // Gọi hàm khởi tạo
    initAllEffects();

    // Demo: Hiển thị thông báo chào mừng
    setTimeout(() => {
        showAdminNotification('Chào mừng đến với trang quản trị!', 'success');
    }, 1000);
});