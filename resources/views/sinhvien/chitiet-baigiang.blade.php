@extends('layouts.app')

@section('title', $baiGiang->tieu_de)

@section('styles')
@vite(['resources/css/sinhvien.css'])

@endsection

@section('content')
<div class="lesson-page-wrapper">
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay" style="display: none;">
        <div class="loading-spinner"></div>
    </div>

    <div class="lesson-container-fluid">
        <!-- Sidebar -->
        <div class="lesson-sidebar" id="lessonSidebar">
            <div class="sidebar-header">
                <button class="sidebar-toggle-btn" id="sidebarToggleDesktop">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <a href="{{ route('sinhvien.lophoc.baigiang', $lopHoc->ma_lop) }}" 
                       class="text-white text-decoration-none hover-lift">
                        <i class="fas fa-arrow-left me-2"></i>
                    </a>
                    <h6 class="mb-0 fw-bold">Nội dung khóa học</h6>
                </div>
                <h5 class="mb-0 text-truncate">{{ $lopHoc->ten_lop }}</h5>
            </div>

            <div class="course-info">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <small class="text-muted">Tiến độ học tập</small>
                    <small class="fw-bold text-primary">25%</small>
                </div>

                <div class="course-progress">
                    <div class="progress-bar" style="width: 25%"></div>
                </div>

                <div class="d-flex justify-content-between">
                    <small class="text-muted">Đã học: 4/16 bài</small>
                    <small class="text-muted">{{ $baiGiangs->count() }} bài</small>
                </div>
            </div>

            <div class="lesson-list">
                @foreach($baiGiangs as $index => $bg)
                <a href="{{ route('sinhvien.lophoc.baigiang.chitiet', ['ma_lop' => $lopHoc->ma_lop, 'id' => $bg->id]) }}" 
                   class="lesson-item hover-lift {{ $bg->id == $baiGiang->id ? 'active' : '' }} {{ $index < 4 ? 'completed' : '' }} text-decoration-none">
                    <div class="lesson-number">{{ $bg->thu_tu }}</div>
                    <div class="lesson-content">
                        <div class="lesson-title">{{ $bg->tieu_de }}</div>
                        <div class="lesson-meta">
                            <span class="lesson-duration">
                                <i class="fas fa-clock me-1"></i>
                                @switch($bg->loai_bai_giang)
                                    @case('video') 15 phút @break
                                    @case('document') 20 phút @break
                                    @case('presentation') 25 phút @break
                                    @default 10 phút
                                @endswitch
                            </span>
                            <span class="lesson-type">
                                <i class="fas {{ $bg->getIcon() }} me-1"></i>
                                {{ $bg->getLoaiText() }}
                            </span>
                        </div>
                    </div>
                    <div class="lesson-complete">
                        @if($index < 4)
                        <i class="fas fa-check fa-xs"></i>
                        @endif
                    </div>
                </a>
                @endforeach
            </div>

            <div class="mt-auto p-3 border-top">
                <a href="{{ route('sinhvien.lophoc.baigiang', $lopHoc->ma_lop) }}" 
                   class="btn btn-outline-primary w-100 hover-lift d-flex align-items-center justify-content-center">
                    <i class="fas fa-list me-2"></i>
                    <span>Xem tất cả bài học</span>
                </a>
            </div>
        </div>

        <!-- External Toggle Button -->
        <button class="sidebar-toggle-modern" id="sidebarToggleModern">
            <i class="fas fa-chevron-left"></i>
        </button>

        <!-- MAIN CONTENT -->
        <div class="lesson-main-wrapper">
            <div class="lesson-main">
                <div class="lesson-body">
                    <!-- Compact Lesson Container -->
                    <div class="lesson-content-container">
                        <div class="lesson-card">
                            {{-- Description --}}
                            @if($baiGiang->mo_ta)
                            <div class="mb-4 animate-fade-up">
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <div class="section-icon bg-primary-light text-primary p-2 rounded-circle">
                                        <i class="fas fa-info-circle"></i>
                                    </div>
                                    <div>
                                        <h4 class="mb-1">Mô tả bài học</h4>
                                        <p class="text-muted mb-0 small">Tổng quan về nội dung</p>
                                    </div>
                                </div>
                                <div class="p-3 bg-light rounded">
                                    <p class="lead mb-0">{{ $baiGiang->mo_ta }}</p>
                                </div>
                            </div>
                            @endif

                            {{-- Media --}}
                            <div class="compact-media-container animate-fade-up delay-100">
                                <div class="compact-media-header">
                                    <div>
                                        <h5 class="mb-1">{{ $baiGiang->tieu_de }}</h5>
                                        <p class="text-muted mb-0 small">
                                            <i class="fas {{ $baiGiang->getIcon() }} me-1"></i>
                                            {{ $baiGiang->getLoaiText() }}
                                            • 
                                            @switch($baiGiang->loai_bai_giang)
                                                @case('video') 15 phút @break
                                                @case('document') 20 phút @break
                                                @case('presentation') 25 phút @break
                                                @default 10 phút
                                            @endswitch
                                        </p>
                                    </div>

                                    <div class="media-controls">
                                        <button class="btn btn-outline-primary btn-sm hover-lift" id="toggleFullscreen">
                                            <i class="fas fa-expand me-1"></i>Toàn màn hình
                                        </button>
                                        <button class="media-size-toggle" id="toggleMediaSize" title="Phóng to/thu nhỏ">
                                            <i class="fas fa-chevron-down"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="compact-media-content" id="mediaContent">
                                    {{-- YouTube Video --}}
                                    @if($baiGiang->loai_bai_giang == 'video' && $baiGiang->url_video)
                                        @php
                                            $youtubeId = $baiGiang->getYouTubeId();
                                        @endphp
                                        
                                        @if($youtubeId)
                                        <div class="compact-video-container">
                                            <iframe 
                                                src="https://www.youtube.com/embed/{{ $youtubeId }}?rel=0&modestbranding=1&showinfo=0&autoplay=0"
                                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                allowfullscreen>
                                            </iframe>
                                        </div>
                                        @else
                                        <div class="alert alert-warning p-4 text-center m-3">
                                            <i class="fas fa-exclamation-triangle fa-lg mb-2"></i>
                                            <h6>Không thể nhận diện video YouTube</h6>
                                            <p class="mb-0 small">Vui lòng kiểm tra lại đường dẫn video.</p>
                                        </div>
                                        @endif

                                    {{-- Local Video --}}
                                    @elseif($baiGiang->loai_bai_giang == 'video')
                                        <div class="compact-video-container">
                                            @if(Storage::exists($baiGiang->duong_dan_file))
                                            <video controls class="w-100" id="lessonVideo">
                                                <source src="{{ Storage::url($baiGiang->duong_dan_file) }}" type="video/mp4">
                                                Trình duyệt của bạn không hỗ trợ thẻ video.
                                            </video>
                                            @else
                                            <div class="alert alert-warning p-4 text-center m-3">
                                                <i class="fas fa-video-slash fa-lg mb-2"></i>
                                                <h6>Video không tồn tại</h6>
                                                <p class="mb-0 small">Vui lòng liên hệ giảng viên.</p>
                                            </div>
                                            @endif
                                        </div>

                                    {{-- PDF File --}}
                                    @elseif($baiGiang->loai_bai_giang == 'pdf' && $baiGiang->duong_dan_file)
                                        @if(Storage::exists($baiGiang->duong_dan_file))
                                        <iframe 
                                            src="{{ Storage::url($baiGiang->duong_dan_file) }}#toolbar=0&navpanes=0&scrollbar=0"
                                            class="compact-pdf-viewer"
                                            allowfullscreen>
                                        </iframe>
                                        @else
                                        <div class="alert alert-warning p-4 text-center m-3">
                                            <i class="fas fa-file-pdf fa-lg mb-2"></i>
                                            <h6>File PDF không tồn tại</h6>
                                            <p class="mb-0 small">Vui lòng liên hệ giảng viên.</p>
                                        </div>
                                        @endif

                                    {{-- Office Documents --}}
                                    @elseif(in_array($baiGiang->loai_bai_giang, ['document', 'presentation']) && $baiGiang->duong_dan_file)
                                        @if(Storage::exists($baiGiang->duong_dan_file))
                                        <iframe 
                                            src="https://view.officeapps.live.com/op/embed.aspx?src={{ urlencode(asset(Storage::url($baiGiang->duong_dan_file))) }}"
                                            class="compact-office-viewer"
                                            frameborder="0"
                                            allowfullscreen>
                                        </iframe>
                                        @else
                                        <div class="alert alert-warning p-4 text-center m-3">
                                            <i class="fas fa-file-alt fa-lg mb-2"></i>
                                            <h6>File tài liệu không tồn tại</h6>
                                            <p class="mb-0 small">Vui lòng liên hệ giảng viên.</p>
                                        </div>
                                        @endif

                                    {{-- Fallback --}}
                                    @else
                                        <div class="text-center py-4">
                                            <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                                            <h5>Nội dung đang cập nhật</h5>
                                            @if($baiGiang->duong_dan_file)
                                            <a href="{{ route('sinhvien.lophoc.baigiang.download', [$lopHoc->ma_lop, $baiGiang->id]) }}"
                                               class="btn btn-primary mt-2 hover-lift btn-sm">
                                                <i class="fas fa-download me-2"></i>Tải tài liệu
                                            </a>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Resources --}}
                            @if($baiGiang->duong_dan_file && Storage::exists($baiGiang->duong_dan_file))
                            <div class="mt-4 animate-fade-up delay-200">
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <div class="section-icon bg-primary-light text-primary p-2 rounded-circle">
                                        <i class="fas fa-book"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-1">Tài liệu học tập</h5>
                                        <p class="text-muted mb-0 small">Các tài liệu bổ trợ</p>
                                    </div>
                                </div>

                                <div class="compact-resource-grid">
                                    <a href="{{ route('sinhvien.lophoc.baigiang.download', [$lopHoc->ma_lop, $baiGiang->id]) }}" 
                                       class="compact-resource-card hover-lift" target="_blank">
                                        <div class="resource-icon text-primary">
                                            @php
                                                $extension = strtolower(pathinfo($baiGiang->duong_dan_file, PATHINFO_EXTENSION));
                                                $icon = match($extension) {
                                                    'pdf' => 'fa-file-pdf',
                                                    'doc', 'docx' => 'fa-file-word',
                                                    'ppt', 'pptx' => 'fa-file-powerpoint',
                                                    'xls', 'xlsx' => 'fa-file-excel',
                                                    default => 'fa-file-alt'
                                                };
                                            @endphp
                                            <i class="fas {{ $icon }} fa-lg"></i>
                                        </div>
                                        <div class="resource-info flex-grow-1">
                                            <h6 class="fw-bold mb-1">Tài liệu chính</h6>
                                            <p class="text-muted small mb-0">
                                                {{ strtoupper($extension) }} • 
                                                @php
                                                    try {
                                                        $fileSize = Storage::size($baiGiang->duong_dan_file);
                                                        $fileSizeMB = round($fileSize / 1024 / 1024, 2);
                                                        echo $fileSizeMB . 'MB';
                                                    } catch (\Exception $e) {
                                                        echo 'N/A';
                                                    }
                                                @endphp
                                            </p>
                                        </div>
                                        <i class="fas fa-download text-primary"></i>
                                    </a>
                                </div>
                            </div>
                            @endif
                        </div>

                        {{-- Navigation Controls --}}
                        <div class="compact-navigation-controls mt-4">
                            @php
                                $prevLesson = $baiGiangs->where('thu_tu', '<', $baiGiang->thu_tu)->last();
                                $nextLesson = $baiGiangs->where('thu_tu', '>', $baiGiang->thu_tu)->first();
                            @endphp

                            @if($prevLesson)
                            <a href="{{ route('sinhvien.lophoc.baigiang.chitiet', ['ma_lop' => $lopHoc->ma_lop, 'id' => $prevLesson->id]) }}" 
                               class="nav-button btn btn-outline-primary hover-lift" id="prevLessonBtn">
                                <i class="fas fa-arrow-left me-2"></i>
                                Bài trước: {{ Str::limit($prevLesson->tieu_de, 30) }}
                            </a>
                            @else
                            <div></div>
                            @endif

                            @if($nextLesson)
                            <a href="{{ route('sinhvien.lophoc.baigiang.chitiet', ['ma_lop' => $lopHoc->ma_lop, 'id' => $nextLesson->id]) }}" 
                               class="nav-button btn btn-primary hover-lift" id="nextLessonBtn">
                                Bài tiếp theo: {{ Str::limit($nextLesson->tieu_de, 30) }}
                                <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                            @else
                            <div class="text-center">
                                <button class="btn btn-success hover-lift">
                                    <i class="fas fa-check-circle me-2"></i>Hoàn thành khóa học
                                </button>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('lessonSidebar');
    const toggleDesktop = document.getElementById('sidebarToggleDesktop');
    const toggleModern = document.getElementById('sidebarToggleModern');
    const toggleMediaSize = document.getElementById('toggleMediaSize');
    const mediaContent = document.getElementById('mediaContent');
    const fullscreenBtn = document.getElementById('toggleFullscreen');
    const prevLessonBtn = document.getElementById('prevLessonBtn');
    const nextLessonBtn = document.getElementById('nextLessonBtn');
    const loadingOverlay = document.getElementById('loadingOverlay');
    
    // Biến lưu trạng thái phóng to media
    let isMediaExpanded = false;
    
    // Toggle media size
    if (toggleMediaSize && mediaContent) {
        toggleMediaSize.addEventListener('click', function() {
            isMediaExpanded = !isMediaExpanded;
            
            if (isMediaExpanded) {
                // Phóng to
                mediaContent.classList.add('expanded');
                this.classList.add('expanded');
                this.title = 'Thu nhỏ';
                this.querySelector('i').className = 'fas fa-chevron-up';
                
                // Tăng chiều cao viewer
                const pdfViewer = mediaContent.querySelector('.compact-pdf-viewer');
                const officeViewer = mediaContent.querySelector('.compact-office-viewer');
                
                if (pdfViewer) pdfViewer.style.height = '650px';
                if (officeViewer) officeViewer.style.height = '650px';
                
                // Scroll to media content
                setTimeout(() => {
                    mediaContent.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }, 300);
                
            } else {
                // Thu nhỏ
                mediaContent.classList.remove('expanded');
                this.classList.remove('expanded');
                this.title = 'Phóng to';
                this.querySelector('i').className = 'fas fa-chevron-down';
                
                // Khôi phục chiều cao mặc định
                const pdfViewer = mediaContent.querySelector('.compact-pdf-viewer');
                const officeViewer = mediaContent.querySelector('.compact-office-viewer');
                
                if (pdfViewer) pdfViewer.style.height = '600px';
                if (officeViewer) officeViewer.style.height = '600px';
            }
            
            // Lưu trạng thái vào localStorage
            localStorage.setItem('mediaExpanded', isMediaExpanded);
        });
        
        // Khôi phục trạng thái từ localStorage
        const savedMediaState = localStorage.getItem('mediaExpanded');
        if (savedMediaState === 'true') {
            isMediaExpanded = true;
            mediaContent.classList.add('expanded');
            toggleMediaSize.classList.add('expanded');
            toggleMediaSize.title = 'Thu nhỏ';
            toggleMediaSize.querySelector('i').className = 'fas fa-chevron-up';
            
            const pdfViewer = mediaContent.querySelector('.compact-pdf-viewer');
            const officeViewer = mediaContent.querySelector('.compact-office-viewer');
            if (pdfViewer) pdfViewer.style.height = '650px';
            if (officeViewer) officeViewer.style.height = '650px';
        }
    }
    
    // Toggle sidebar function
    function toggleSidebar() {
        sidebar.classList.toggle('collapsed');
        
        // Cập nhật icon
        const icon = toggleModern.querySelector('i');
        if (sidebar.classList.contains('collapsed')) {
            icon.className = 'fas fa-chevron-right';
            toggleModern.style.left = '15px';
        } else {
            icon.className = 'fas fa-chevron-left';
            toggleModern.style.left = '15px';
        }
        
        // Lưu trạng thái
        localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
        
        // Thêm/remove class cho body
        document.body.classList.toggle('sidebar-closed');
        
        // Adjust main content width
        const mainWrapper = document.querySelector('.lesson-main-wrapper');
        if (mainWrapper) {
            if (sidebar.classList.contains('collapsed')) {
                mainWrapper.style.marginLeft = '70px';
            } else {
                mainWrapper.style.marginLeft = '320px';
            }
        }
    }
    
    // Xử lý toggle nút modern
    if (toggleModern) {
        toggleModern.addEventListener('click', function(e) {
            e.stopPropagation();
            toggleSidebar();
        });
    }
    
    // Xử lý toggle nút desktop
    if (toggleDesktop) {
        toggleDesktop.addEventListener('click', function(e) {
            e.stopPropagation();
            toggleSidebar();
        });
    }
    
    // Fullscreen functionality
    if (fullscreenBtn) {
        fullscreenBtn.addEventListener('click', function() {
            const mediaContent = document.querySelector('.compact-media-content');
            if (!document.fullscreenElement) {
                if (mediaContent.requestFullscreen) {
                    mediaContent.requestFullscreen();
                }
                this.innerHTML = '<i class="fas fa-compress me-1"></i>Thoát toàn màn hình';
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                }
                this.innerHTML = '<i class="fas fa-expand me-1"></i>Toàn màn hình';
            }
        });
        
        // Update button when exiting fullscreen
        document.addEventListener('fullscreenchange', function() {
            if (!document.fullscreenElement) {
                fullscreenBtn.innerHTML = '<i class="fas fa-expand me-1"></i>Toàn màn hình';
            }
        });
    }
    
    // Restore sidebar state
    function restoreSidebarState() {
        if (localStorage.getItem('sidebarCollapsed') === 'true' && window.innerWidth >= 992) {
            sidebar.classList.add('collapsed');
            if (toggleModern) {
                toggleModern.querySelector('i').className = 'fas fa-chevron-right';
            }
            
            // Adjust main content
            const mainWrapper = document.querySelector('.lesson-main-wrapper');
            if (mainWrapper) {
                mainWrapper.style.marginLeft = '70px';
            }
        }
    }
    
    restoreSidebarState();
    
    // Navigation with loading state
    [prevLessonBtn, nextLessonBtn].forEach(btn => {
        if (btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                loadingOverlay.style.display = 'flex';
                
                // Get URL from href
                const url = this.getAttribute('href');
                
                // Small delay to show loading
                setTimeout(() => {
                    window.location.href = url;
                }, 300);
            });
        }
    });
    
    // Hide loading when page is ready
    window.addEventListener('load', function() {
        setTimeout(() => {
            if (loadingOverlay) {
                loadingOverlay.style.display = 'none';
            }
        }, 500);
    });
    
    // Auto-scroll to active lesson
    const activeLesson = document.querySelector('.lesson-item.active');
    if (activeLesson) {
        setTimeout(() => {
            activeLesson.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }, 500);
    }
    
    // Fix video progress saving
    const video = document.getElementById('lessonVideo');
    if (video) {
        video.addEventListener('timeupdate', function() {
            localStorage.setItem(`videoProgress_${window.location.pathname}`, video.currentTime);
        });
        
        const savedTime = localStorage.getItem(`videoProgress_${window.location.pathname}`);
        if (savedTime) {
            video.currentTime = parseFloat(savedTime);
        }
    }
    
    // Handle window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth < 992) {
            // On mobile, hide modern toggle
            if (toggleModern) {
                toggleModern.style.display = 'none';
            }
            
            // Reset sidebar
            sidebar.classList.remove('collapsed');
            const mainWrapper = document.querySelector('.lesson-main-wrapper');
            if (mainWrapper) {
                mainWrapper.style.marginLeft = '0';
            }
        } else {
            // On desktop, show modern toggle
            if (toggleModern) {
                toggleModern.style.display = 'flex';
                updateToggleButtonPosition();
            }
        }
    });
    
    // Update toggle button position based on sidebar state
    function updateToggleButtonPosition() {
        if (window.innerWidth >= 992 && toggleModern) {
            if (sidebar.classList.contains('collapsed')) {
                toggleModern.style.left = '15px';
            } else {
                toggleModern.style.left = '15px';
            }
        }
    }
    
    // Initialize
    updateToggleButtonPosition();
    
    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(event) {
        if (window.innerWidth < 992 && 
            sidebar && 
            !sidebar.contains(event.target) && 
            sidebar.classList.contains('active')) {
            sidebar.classList.remove('active');
        }
    });
    
    // Adjust footer z-index
    const footer = document.querySelector('.footer');
    if (footer) {
        footer.style.position = 'relative';
        footer.style.zIndex = '900';
    }
});
</script>
@endsection