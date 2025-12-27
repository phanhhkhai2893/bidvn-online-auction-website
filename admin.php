<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin Mới - BIDVN V2 (No Chart)</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
    /* Định nghĩa biến màu sắc theo yêu cầu */
    :root {
        --color-primary: #FFC107; /* Màu Vàng Chủ Đạo */
        --color-sidebar-bg: rgba(45, 45, 45, 0.9); /* Nền mờ ảo (Semi-transparent) */
        --color-content-bg: #2d2d2d;
        --color-card-bg: #3c3c3c;
        --color-text-light: #f5f5f5;
        --color-text-muted: #aaaaaa;
        --shadow-float: 0 8px 32px 0 rgba(0, 0, 0, 0.3); /* Bóng sâu cho hiệu ứng Glass */
        --border-radius: 12px;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 0;
        padding: 0;
        background-color: var(--color-content-bg);
        color: var(--color-text-light);
        display: flex;
        min-height: 100vh;
    }

    /* --- Sidebar Style: Glassmorphism/Tab Vertica --- */
    .sidebar {
        width: 240px; /* Rộng hơn một chút để thoáng */
        background-color: var(--color-sidebar-bg);
        /* Hiệu ứng Glass/Mờ ảo */
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border-right: 1px solid rgba(255, 255, 255, 0.05); 
        box-shadow: var(--shadow-float);
        
        padding: 20px 0;
        display: flex;
        flex-direction: column;
        transition: width 0.3s;
    }

    .sidebar-logo {
        text-align: center;
        color: var(--color-primary);
        font-size: 1.8em;
        font-weight: 700;
        letter-spacing: 1px;
        padding-bottom: 25px;
        margin: 0;
    }

    .menu-section {
        padding: 10px 20px;
        margin-bottom: 20px;
    }
    
    .section-title {
        color: var(--color-text-muted);
        font-size: 0.85em;
        text-transform: uppercase;
        margin-bottom: 8px;
        padding: 0 10px;
        font-weight: 600;
    }

    /* Phong cách Tab dọc (Vertical Tabs) */
    .sidebar-item {
        display: flex;
        align-items: center;
        padding: 10px 15px;
        margin: 5px 0;
        color: var(--color-text-light);
        text-decoration: none;
        cursor: pointer;
        transition: all 0.3s;
        border-radius: var(--border-radius); /* Bo tròn mục */
    }

    .sidebar-item i {
        margin-right: 15px;
        width: 20px;
        font-size: 1.1em;
    }

    /* Hiệu ứng Hover: Nổi bật gradient */
    .sidebar-item:hover {
        background-color: rgba(255, 193, 7, 0.1); /* Nền vàng mờ khi hover */
        color: var(--color-primary);
        box-shadow: 0 0 5px rgba(255, 193, 7, 0.5);
    }

    /* Hiệu ứng Active: Gradient viền nổi bật */
    .sidebar-item.active {
        color: #1e1e1e;
        font-weight: 600;
        /* Áp dụng Gradient Vàng làm nền */
        background: linear-gradient(to right, #FFC107, #FFDC7A);
        box-shadow: 0 4px 10px rgba(255, 193, 7, 0.4);
    }
    
    .sidebar-item.active i {
        color: #1e1e1e;
    }

    .admin-profile {
        margin-top: auto;
        padding: 20px;
        color: var(--color-text-muted);
        border-top: 1px solid rgba(255, 255, 255, 0.05);
        font-size: 0.9em;
    }
    .admin-profile i {
        color: var(--color-primary);
        margin-right: 5px;
    }
    
    /* --- Main Content & Dashboard (Giữ nguyên) --- */
    /* ... (Phần CSS cho Main Content, Header, Card, Chart Placeholder) ... */
    
    .main-content {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .header {
        background-color: var(--color-sidebar-bg);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        padding: 15px 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
        z-index: 10;
    }

    .logout-btn {
        background-color: var(--color-primary);
        color: #1e1e1e;
        border: none;
        padding: 8px 15px;
        border-radius: 5px;
        cursor: pointer;
        font-weight: bold;
        transition: background-color 0.2s;
    }

    .dashboard-body {
        padding: 30px;
        flex-grow: 1;
    }

    .card-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 25px;
        margin-bottom: 25px;
    }

    .card {
        background-color: var(--color-card-bg);
        border-radius: var(--border-radius);
        padding: 25px;
        box-shadow: var(--shadow-float);
        transition: transform 0.3s, box-shadow 0.3s;
        display: flex;
        flex-direction: column;
    }
    
    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.6);
    }

    .card-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-info h3 {
        margin: 0 0 5px 0;
        color: var(--color-primary);
        font-size: 1.2em;
    }

    .card-info p {
        margin: 0;
        font-size: 2em;
        font-weight: 600;
    }

    .card-icon {
        font-size: 3em;
        color: var(--color-primary);
        opacity: 0.7;
    }

    .chart-block {
        grid-column: span 2;
        min-height: 350px;
        padding: 25px;
    }
    
    .chart-placeholder {
        width: 100%;
        height: 100%;
        background-color: rgba(255, 255, 255, 0.05);
        border-radius: 5px;
        display: flex;
        justify-content: center;
        align-items: center;
        color: var(--color-text-muted);
        font-style: italic;
    }
    
    .activity-item {
        display: flex;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        font-size: 0.95em;
    }

    .activity-item:last-child {
        border-bottom: none;
    }

    .activity-item i {
        color: var(--color-primary);
        margin-right: 15px;
    }

    .activity-item span {
        color: var(--color-text-muted);
        margin-left: auto;
    }
</style>
</head>
<body>

    <div class="sidebar">
		<h2 class="sidebar-logo"><i class="fas fa-gavel"></i> BIDVN</h2>

		<div class="menu-section">
			<p class="section-title">Quản lý Dữ liệu</p>
			<a href="#" class="sidebar-item active">
				<i class="fas fa-tachometer-alt"></i> <span>Dashboard</span>
			</a>
			<a href="#" class="sidebar-item">
				<i class="fas fa-box-open"></i> <span>Sản Phẩm</span>
			</a>
			<a href="#" class="sidebar-item">
				<i class="fas fa-users"></i> <span>Người Dùng</span>
			</a>
			<a href="#" class="sidebar-item">
				<i class="fas fa-history"></i> <span>Lịch Sử Đấu Giá</span>
			</a>
			<a href="#" class="sidebar-item">
				<i class="fas fa-list"></i> <span>Danh Mục</span>
			</a>
		</div>

		<div class="menu-section">
			<p class="section-title">Giao dịch & Hệ thống</p>
			<a href="#" class="sidebar-item">
				<i class="fas fa-file-invoice-dollar"></i> <span>Hóa Đơn</span>
			</a>
			<a href="#" class="sidebar-item">
				<i class="fas fa-chart-line"></i> <span>Thống Kê</span>
			</a>
			<a href="#" class="sidebar-item">
				<i class="fas fa-cog"></i> <span>Cài Đặt</span>
			</a>
		</div>

		<div class="admin-profile">
			<p><i class="fas fa-user-circle"></i> Phạm Hoàng Huy Khôi</p>
		</div>
	</div>

    <div class="main-content">
        <div class="header">
            <h1 style="color: var(--color-text-light); margin: 0; font-size: 1.5em;">Dashboard Chính</h1>
            <button class="logout-btn"><i class="fas fa-sign-out-alt"></i> Đăng xuất</button>
        </div>

        <div class="dashboard-body">
            
            <div class="card-container">
                <div class="card" style="border-left: 5px solid #4CAF50;">
                    <div class="card-info">
                        <div><h3>Tổng Người Dùng</h3><p>12,450</p></div>
                        <i class="fas fa-users card-icon"></i>
                    </div>
                </div>

                <div class="card" style="border-left: 5px solid #2196F3;">
                    <div class="card-info">
                        <div><h3>Sản Phẩm Đang ĐG</h3><p>325</p></div>
                        <i class="fas fa-gavel card-icon"></i>
                    </div>
                </div>

                <div class="card" style="border-left: 5px solid var(--color-primary);">
                    <div class="card-info">
                        <div><h3>Doanh Thu (Tháng)</h3><p>5.7 Tỷ</p></div>
                        <i class="fas fa-money-bill-wave card-icon"></i>
                    </div>
                </div>
            </div>

            <div class="card chart-block">
                <h3>Thống Kê Doanh Thu 6 Tháng Gần Nhất</h3>
                <div class="chart-placeholder">
                    [Khu vực này sẽ là biểu đồ đường khi bạn kích hoạt Chart.js]
                </div>
            </div>
            
            <div class="card chart-block" style="grid-column: span 1;">
                <h3>Tỷ Lệ Sản Phẩm theo Danh Mục</h3>
                <div class="chart-placeholder">
                    [Khu vực này sẽ là biểu đồ tròn khi bạn kích hoạt Chart.js]
                </div>
            </div>
            
            <div class="card" style="grid-column: span 1;">
                <h3>Hoạt Động Gần Đây</h3>
                <div class="activity-list">
                    <div class="activity-item"><i class="fas fa-check-circle"></i>Duyệt hóa đơn #INV-00987.<span>2 phút</span></div>
                    <div class="activity-item"><i class="fas fa-plus-circle"></i>Thêm SP: "Đồng hồ cổ".<span>1 giờ</span></div>
                    <div class="activity-item"><i class="fas fa-exclamation-triangle"></i>Giao dịch lỗi #TRX-105.<span>4 giờ</span></div>
                    <div class="activity-item"><i class="fas fa-user-plus"></i>User mới: Nguyễn Văn B.<span>Hôm qua</span></div>
                </div>
            </div>

        </div>
    </div>

    <script>
        // --- Chức năng Submenu (Thư mục con) ---
        document.querySelectorAll('.menu-header').forEach(header => {
            header.addEventListener('click', () => {
                const submenuId = header.getAttribute('data-submenu');
                const submenu = document.getElementById(submenuId);
                
                // Đóng/Mở Submenu
                submenu.classList.toggle('open');
                header.classList.toggle('active');
            });
        });
    </script>
</body>
</html>