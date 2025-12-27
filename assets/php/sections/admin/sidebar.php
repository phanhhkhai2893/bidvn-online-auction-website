<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Untitled Document</title>
</head>

<body>
    <div id="sidebar">
        <!-- Logo/Tiêu đề -->
        <div class="logo-area">
			<a href="index.php">
				<div class="logo-area-img"></div>
			</a>
        </div>

        <!-- Menu điều hướng chính -->
        <nav>
            
            <!-- Chức năng 1 (Dashboard - Mặc định active) -->
            <a id="dashboardView" href="admin_index.php?view=<?PHP echo 'dashboard'; ?>" data-function="Dashboard" class="sidebar-item active">
                <i class="fa fa-table" aria-hidden="true"></i>
                Dashboard Chính
            </a>

            <span class="menu-group-title">Quản Lý Dữ Liệu</span>
            
            <!-- Các Chức năng khác -->
            <a id="nguoidungView" href="admin_index.php?view=<?PHP echo 'qlND'; ?>" data-function="Users" class="sidebar-item">
                <i class="fa fa-user" aria-hidden="true"></i>
                Người Dùng
            </a>
            <a id="sanphamView" href="admin_index.php?view=<?PHP echo 'qlSP'; ?>" data-function="Products" class="sidebar-item">
                <i class="fa fa-cubes" aria-hidden="true"></i>
                Sản Phẩm
            </a>
            <a id="lichsuView" href="admin_index.php?view=<?PHP echo 'qlLS'; ?>" data-function="Orders" class="sidebar-item">
                <i class="fa fa-history" aria-hidden="true"></i>
				Lịch Sử Đấu Giá
            </a>
            <a id="hoadonView" href="admin_index.php?view=<?PHP echo 'qlHD'; ?>" data-function="Orders" class="sidebar-item">
                <i class="fa fa-file" aria-hidden="true"></i>
				Hóa Đơn
            </a>
            <a id="danhmucView" href="admin_index.php?view=<?PHP echo 'qlDM'; ?>" data-function="Orders" class="sidebar-item">
                <i class="fa fa-bars" aria-hidden="true"></i>
				Danh Mục
            </a>
            <a id="danhmucconView" href="admin_index.php?view=<?PHP echo 'qlDMC'; ?>" data-function="Orders" class="sidebar-item">
                <i class="fa fa-bars" aria-hidden="true"></i>
				Danh Mục Con
            </a>
<!--
            <a id="sliderView" href="admin_index.php?view=<?PHP echo 'qlSD'; ?>" data-function="Orders" class="sidebar-item">
                <i class="fa fa-camera" aria-hidden="true"></i>
				Slider
            </a>
            <a id="giohangView" href="admin_index.php?view=<?PHP echo 'qlGH'; ?>" data-function="Orders" class="sidebar-item">
                <i class="fa fa-shopping-cart" aria-hidden="true"></i>
				Giỏ Hàng
            </a>
-->
            
            <span class="menu-group-title">Hệ Thống</span>

            <a id="tkndView" href="admin_index.php?view=<?PHP echo 'tkND'; ?>" data-function="Settings" class="sidebar-item">
                <i class="fa fa-users" aria-hidden="true"></i>
				Thống Kê Người Dùng
            </a>
            <a id="tkdgView" href="admin_index.php?view=<?PHP echo 'tkDG'; ?>" data-function="Logs" class="sidebar-item">
                <i class="fa fa-tasks" aria-hidden="true"></i>
				Thống Kê Đấu Giá
            </a>
        </nav>

        <!-- Thông tin User Admin -->
        <div class="admin-info">
            <div class="avatar">AD</div>
            <div class="details">
                <p>
					<?PHP
						if ($_SESSION['role'] == "Admin")
						{
							$hoten = $_SESSION['tennd'];
							echo $hoten;
						}
					?>
				</p>
                <p>Quản trị viên</p>
            </div>
        </div>
    </div>
</body>
</html>