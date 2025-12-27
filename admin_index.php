<?PHP
	session_start();
	include('assets/php/database/db_connection.php');
	$kn = new db_connection();
//				$_SESSION = array();
//
//				if (ini_get("session.use_cookies")) {
//					$params = session_get_cookie_params();
//					setcookie(session_name(), '', time() - 42000,
//						$params["path"], $params["domain"],
//						$params["secure"], $params["httponly"]
//					);
//				}
//
//				session_destroy();
	unset($_SESSION['SignInError']);
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>ADMIN</title>
<link rel="stylesheet" href="assets/css/admin_style.css" />
<link rel="stylesheet" href="assets/css/thongkeND.css">
<link rel="stylesheet" href="assets/css/thongkeDG.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
	<!-- Sidebar (Thanh Điều Hướng Bên Trái) -->
	<?PHP include('assets/php/sections/admin/sidebar.php'); ?>
    <!-- Main Content Area - Phần còn lại -->
    <div id="main-content">
        <!-- Header Top Bar -->
        <header>
            <h1 id="main-title">Dashboard Chính</h1>
            
            <!-- Nút Đăng xuất -->
			<form action="" method="post">
				<a href="#" class="logout-btn">
					<i class="fa fa-sign-out" aria-hidden="true"></i>
					<input style="border: none; background-color: rgba(255, 255, 255, 0); color: #fff; cursor: pointer;" name="dangxuat" type="submit" value="Đăng xuất" />
				</a>
			</form>
        </header>
		<?PHP
			if (isset($_POST['dangxuat']))
			{
				$_SESSION = array();

				if (ini_get("session.use_cookies")) {
					$params = session_get_cookie_params();
					setcookie(session_name(), '', time() - 42000,
					$params["path"], $params["domain"],
					$params["secure"], $params["httponly"]
					);
				}

				session_destroy();
				echo "<meta http-equiv='refresh' content='0; url=index.php' />";
			}
		?>
        <main>
            <div id="content-area" class="">
				<?PHP 
				if (isset($_GET['view']))
				{
					$currentView = $_GET['view'];
					$_SESSION['currentView'] = $currentView;
				}
				else
				{
					if (!isset($_SESSION['currentView']))
					{
						$_SESSION['currentView'] = 'dashboard';
					}
				}
				
				switch($_SESSION['currentView'])
				{
					case 'dashboard':
						echo '<div class="empty-state">
								<h2>Chào mừng bạn đến với trang quản trị!</h2>
								<p>Hãy chọn một chức năng ở sidebar bên trái.</p>
							</div>';
						break;
						
// Quản lý người dùng
					case 'qlND':
// 		==========	READ
						if(isset($_GET['mand']) && (isset($_GET['act']) && $_GET['act'] == 'nd_read'))
						{
							$mand = mysqli_real_escape_string($kn->con, $_GET['mand']);
							$query = "SELECT * FROM nguoi_dung WHERE MaND = '$mand'";
							$tbltk = mysqli_query($kn -> con, $query);
							if($tbltk && mysqli_num_rows($tbltk) > 0)
							{
								$row = mysqli_fetch_array($tbltk);
								include('assets/php/sections/admin/nguoidung_read.php');
							}
						}
// 		==========	CREATE
						else if(isset($_GET['mand']) && (isset($_GET['act']) && $_GET['act'] == 'nd_insert'))
						{
							$mand = mysqli_real_escape_string($kn->con, $_GET['mand']);
							include('assets/php/sections/admin/nguoidung_insert.php');
						}
// 		==========	UPDATE
						else if(isset($_GET['mand']) && (isset($_GET['act']) && $_GET['act'] == 'nd_update'))
						{
							$mand = mysqli_real_escape_string($kn->con, $_GET['mand']);
							$query = "SELECT * FROM nguoi_dung WHERE MaND = '$mand'";
							$tbltk = mysqli_query($kn -> con, $query);
							if($tbltk && mysqli_num_rows($tbltk) > 0)
							{
								$row = mysqli_fetch_array($tbltk);
								include('assets/php/sections/admin/nguoidung_update.php');
							}
						}
// 		==========	DELETE
						else if(isset($_GET['mand']) && (isset($_GET['act']) && $_GET['act'] == 'nd_delete'))
						{
							$mand = mysqli_real_escape_string($kn->con, $_GET['mand']);
							$query = "SELECT * FROM nguoi_dung WHERE MaND = '$mand'";
							$tbltk = mysqli_query($kn -> con, $query);
							if($tbltk && mysqli_num_rows($tbltk) > 0)
							{
								$row = mysqli_fetch_array($tbltk);
								include('assets/php/sections/admin/nguoidung_delete.php');
							}
						}
						else
						{
							include('assets/php/sections/admin/ql_nguoidung.php');
						}
						break;
						
// Quản lý sản phẩm
					case 'qlSP':
// 		==========	READ
						if(isset($_GET['masp']) && (isset($_GET['act']) && $_GET['act'] == 'sp_read'))
						{
							$masp = mysqli_real_escape_string($kn->con, $_GET['masp']);
							$query = "SELECT * FROM san_pham WHERE MaSP = '$masp'";
							$tbltk = mysqli_query($kn -> con, $query);
							if($tbltk && mysqli_num_rows($tbltk) > 0)
							{
								$row = mysqli_fetch_array($tbltk);
								include('assets/php/sections/admin/sanpham_read.php');
							}
						}
// 		==========	CREATE
						else if(isset($_GET['masp']) && (isset($_GET['act']) && $_GET['act'] == 'sp_insert'))
						{
							$masp = mysqli_real_escape_string($kn->con, $_GET['masp']);
							include('assets/php/sections/admin/sanpham_insert.php');
						}
// 		==========	UPDATE
						else if(isset($_GET['masp']) && (isset($_GET['act']) && $_GET['act'] == 'sp_update'))
						{
							$masp = mysqli_real_escape_string($kn->con, $_GET['masp']);
							$query = "SELECT * FROM san_pham WHERE MaSP = '$masp'";
							$tbltk = mysqli_query($kn -> con, $query);
							if($tbltk && mysqli_num_rows($tbltk) > 0)
							{
								$row = mysqli_fetch_array($tbltk);
								include('assets/php/sections/admin/sanpham_update.php');
							}
						}
// 		==========	DELETE
						else if(isset($_GET['masp']) && (isset($_GET['act']) && $_GET['act'] == 'sp_delete'))
						{
							$masp = mysqli_real_escape_string($kn->con, $_GET['masp']);
							$query = "SELECT * FROM san_pham WHERE MaSP = '$masp'";
							$tbltk = mysqli_query($kn -> con, $query);
							if($tbltk && mysqli_num_rows($tbltk) > 0)
							{
								$row = mysqli_fetch_array($tbltk);
								include('assets/php/sections/admin/sanpham_delete.php');
							}
						}
						else
						{
							include('assets/php/sections/admin/ql_sanpham.php');
						}
						break;
						
// Quản lý danh mục
					case 'qlDM':
// 		==========	READ
						if(isset($_GET['madm']) && (isset($_GET['act']) && $_GET['act'] == 'dm_read'))
						{
							$madm = mysqli_real_escape_string($kn->con, $_GET['madm']);
							$query = "SELECT * FROM danh_muc WHERE MaDM = '$madm'";
							$tbltk = mysqli_query($kn -> con, $query);
							if($tbltk && mysqli_num_rows($tbltk) > 0)
							{
								$row = mysqli_fetch_array($tbltk);
								include('assets/php/sections/admin/danhmuc_read.php');
							}
						}
// 		==========	CREATE
						else if(isset($_GET['madm']) && (isset($_GET['act']) && $_GET['act'] == 'dm_insert'))
						{
							$madm = mysqli_real_escape_string($kn->con, $_GET['madm']);
							include('assets/php/sections/admin/danhmuc_insert.php');
						}
// 		==========	UPDATE
						else if(isset($_GET['madm']) && (isset($_GET['act']) && $_GET['act'] == 'dm_update'))
						{
							$madm = mysqli_real_escape_string($kn->con, $_GET['madm']);
							$query = "SELECT * FROM danh_muc WHERE MaDM = '$madm'";
							$tbltk = mysqli_query($kn -> con, $query);
							if($tbltk && mysqli_num_rows($tbltk) > 0)
							{
								$row = mysqli_fetch_array($tbltk);
								include('assets/php/sections/admin/danhmuc_update.php');
							}
						}
// 		==========	DELETE
						else if(isset($_GET['madm']) && (isset($_GET['act']) && $_GET['act'] == 'dm_delete'))
						{
							$madm = mysqli_real_escape_string($kn->con, $_GET['madm']);
							$query = "SELECT * FROM danh_muc WHERE MaDM = '$madm'";
							$tbltk = mysqli_query($kn -> con, $query);
							if($tbltk && mysqli_num_rows($tbltk) > 0)
							{
								$row = mysqli_fetch_array($tbltk);
								include('assets/php/sections/admin/danhmuc_delete.php');
							}
						}
						else
						{
							include('assets/php/sections/admin/ql_danhmuc.php');
						}
						break;
						
// Quản lý danh mục con
					case 'qlDMC':
// 		==========	READ
						if(isset($_GET['madmc']) && (isset($_GET['act']) && $_GET['act'] == 'dmc_read'))
						{
							$madmc = mysqli_real_escape_string($kn->con, $_GET['madmc']);
							$query = "SELECT * FROM danh_muc_con WHERE MaDMCon = '$madmc'";
							$tbltk = mysqli_query($kn -> con, $query);
							if($tbltk && mysqli_num_rows($tbltk) > 0)
							{
								$row = mysqli_fetch_array($tbltk);
								include('assets/php/sections/admin/danhmuccon_read.php');
							}
						}
// 		==========	CREATE
						else if(isset($_GET['madmc']) && (isset($_GET['act']) && $_GET['act'] == 'dmc_insert'))
						{
							$madmc = mysqli_real_escape_string($kn->con, $_GET['madmc']);
							include('assets/php/sections/admin/danhmuccon_insert.php');
						}
// 		==========	UPDATE
						else if(isset($_GET['madmc']) && (isset($_GET['act']) && $_GET['act'] == 'dmc_update'))
						{
							$madmc = mysqli_real_escape_string($kn->con, $_GET['madmc']);
							$query = "SELECT * FROM danh_muc_con WHERE MaDMCon = '$madmc'";
							$tbltk = mysqli_query($kn -> con, $query);
							if($tbltk && mysqli_num_rows($tbltk) > 0)
							{
								$row = mysqli_fetch_array($tbltk);
								include('assets/php/sections/admin/danhmuccon_update.php');
							}
						}
// 		==========	DELETE
						else if(isset($_GET['madmc']) && (isset($_GET['act']) && $_GET['act'] == 'dmc_delete'))
						{
							$madmc = mysqli_real_escape_string($kn->con, $_GET['madmc']);
							$query = "SELECT * FROM danh_muc_con WHERE MaDMCon = '$madmc'";
							$tbltk = mysqli_query($kn -> con, $query);
							if($tbltk && mysqli_num_rows($tbltk) > 0)
							{
								$row = mysqli_fetch_array($tbltk);
								include('assets/php/sections/admin/danhmuccon_delete.php');
							}
						}
						else
						{
							include('assets/php/sections/admin/ql_danhmuccon.php');
						}
						break;
						
// Quản lý lịch sử đấu giá
					case 'qlLS':
// 		==========	READ
						if(isset($_GET['mals']) && (isset($_GET['act']) && $_GET['act'] == 'ls_read'))
						{
							$mals = mysqli_real_escape_string($kn->con, $_GET['mals']);
							$query = "SELECT * FROM lich_su_dau_gia WHERE MaLS = '$mals'";
							$tbltk = mysqli_query($kn -> con, $query);
							if($tbltk && mysqli_num_rows($tbltk) > 0)
							{
								$row = mysqli_fetch_array($tbltk);
								include('assets/php/sections/admin/lichsu_read.php');
							}
						}
// 		==========	CREATE
						else if(isset($_GET['mals']) && (isset($_GET['act']) && $_GET['act'] == 'ls_insert'))
						{
							$mals = mysqli_real_escape_string($kn->con, $_GET['mals']);
							include('assets/php/sections/admin/lichsu_insert.php');
						}
// 		==========	UPDATE
						else if(isset($_GET['mals']) && (isset($_GET['act']) && $_GET['act'] == 'ls_update'))
						{
							$mals = mysqli_real_escape_string($kn->con, $_GET['mals']);
							$query = "SELECT * FROM lich_su_dau_gia WHERE MaLS = '$mals'";
							$tbltk = mysqli_query($kn -> con, $query);
							if($tbltk && mysqli_num_rows($tbltk) > 0)
							{
								$row = mysqli_fetch_array($tbltk);
								include('assets/php/sections/admin/lichsu_update.php');
							}
						}
// 		==========	DELETE
						else if(isset($_GET['mals']) && (isset($_GET['act']) && $_GET['act'] == 'ls_delete'))
						{
							$mals = mysqli_real_escape_string($kn->con, $_GET['mals']);
							$query = "SELECT * FROM lich_su_dau_gia WHERE MaLS = '$mals'";
							$tbltk = mysqli_query($kn -> con, $query);
							if($tbltk && mysqli_num_rows($tbltk) > 0)
							{
								$row = mysqli_fetch_array($tbltk);
								include('assets/php/sections/admin/lichsu_delete.php');
							}
						}
						else
						{
							include('assets/php/sections/admin/ql_lichsu.php');
						}
						break;
						
// Quản lý hóa đơn
					case 'qlHD':
// 		==========	READ
						if(isset($_GET['mals']) && (isset($_GET['act']) && $_GET['act'] == 'ls_read'))
						{
							$mals = mysqli_real_escape_string($kn->con, $_GET['mals']);
							$query = "SELECT * FROM lich_su_dau_gia WHERE MaLS = '$mals'";
							$tbltk = mysqli_query($kn -> con, $query);
							if($tbltk && mysqli_num_rows($tbltk) > 0)
							{
								$row = mysqli_fetch_array($tbltk);
								include('assets/php/sections/admin/lichsu_read.php');
							}
						}
// 		==========	CREATE
						else if(isset($_GET['mals']) && (isset($_GET['act']) && $_GET['act'] == 'ls_insert'))
						{
							$mals = mysqli_real_escape_string($kn->con, $_GET['mals']);
							include('assets/php/sections/admin/lichsu_insert.php');
						}
// 		==========	UPDATE
						else if(isset($_GET['mals']) && (isset($_GET['act']) && $_GET['act'] == 'ls_update'))
						{
							$mals = mysqli_real_escape_string($kn->con, $_GET['mals']);
							$query = "SELECT * FROM lich_su_dau_gia WHERE MaLS = '$mals'";
							$tbltk = mysqli_query($kn -> con, $query);
							if($tbltk && mysqli_num_rows($tbltk) > 0)
							{
								$row = mysqli_fetch_array($tbltk);
								include('assets/php/sections/admin/lichsu_update.php');
							}
						}
// 		==========	DELETE
						else if(isset($_GET['mals']) && (isset($_GET['act']) && $_GET['act'] == 'ls_delete'))
						{
							$mals = mysqli_real_escape_string($kn->con, $_GET['mals']);
							$query = "SELECT * FROM lich_su_dau_gia WHERE MaLS = '$mals'";
							$tbltk = mysqli_query($kn -> con, $query);
							if($tbltk && mysqli_num_rows($tbltk) > 0)
							{
								$row = mysqli_fetch_array($tbltk);
								include('assets/php/sections/admin/lichsu_delete.php');
							}
						}
						else
						{
							include('assets/php/sections/admin/ql_hoadon.php');
						}
						break;
						
// Thống kê người dùng
					case 'tkND':
						include('assets/php/sections/admin/tk_nguoidung.php');
						break;
						
// Thống kê người dùng
					case 'tkDG':
						include('assets/php/sections/admin/tk_daugia.php');
						break;
				}
				?>
            </div>
        </main>
    </div>
	
	<div id="toast-ui-container" class="toast-ui-container">
    </div>
		
	<script type="text/javascript">
		const viewTitle = document.getElementById('main-title');
//		Các Views Quản Lý
		const dashboardView = document.getElementById('dashboardView');
		const nguoidungView = document.getElementById('nguoidungView');
		const sanphamView = document.getElementById('sanphamView');
		const lichsuView = document.getElementById('lichsuView');
		const hoadonView = document.getElementById('hoadonView');
		const cthoadonView = document.getElementById('cthoadonView');
		const danhmucView = document.getElementById('danhmucView');
		const danhmucconView = document.getElementById('danhmucconView');
		const thongbaoView = document.getElementById('thongbaoView');
		const sliderView = document.getElementById('sliderView');
		const giohangView = document.getElementById('giohangView');
		const dshaView = document.getElementById('dshaView');
		const dsytView = document.getElementById('dsytView');
//		VIEW THỐNG KÊ
		const tkndView = document.getElementById('tkndView');
		const tkdgView = document.getElementById('tkdgView');
		
//		Biến chứa View hiện tại = $_SESSION['view']
		const currentView = '<?PHP echo $_SESSION['currentView']; ?>';

//		SwitchCase load View hiện tại lên content-area
		switch(currentView)
		{
			case 'dashboard':
				removeActiveClass();
				dashboardView.classList.add('active');
				break;
				
			case 'qlND':
				removeActiveClass();
				nguoidungView.classList.add('active');
				viewTitle.textContent = "Quản Lý Người Dùng";
				break;
				
			case 'qlSP':
				removeActiveClass();
				sanphamView.classList.add('active');
				viewTitle.textContent = "Quản Lý Sản Phẩm";
				break;
				
			case 'qlLS':
				removeActiveClass();
				lichsuView.classList.add('active');
				viewTitle.textContent = "Quản Lý Lịch Sử Đấu Giá";
				break;
				
			case 'qlHD':
				removeActiveClass();
				hoadonView.classList.add('active');
				viewTitle.textContent = "Quản Lý Hóa Đơn";
				break;
				
			case 'qlCTHD':
				removeActiveClass();
				cthoadonView.classList.add('active');
				viewTitle.textContent = "Quản Lý Chi Tiết Hóa Đơn";
				break;
				
			case 'qlDM':
				removeActiveClass();
				danhmucView.classList.add('active');
				viewTitle.textContent = "Quản Lý Danh Mục";
				break;
				
			case 'qlDMC':
				removeActiveClass();
				danhmucconView.classList.add('active');
				viewTitle.textContent = "Quản Lý Danh Mục Con";
				break;
				
			case 'qlTB':
				removeActiveClass();
				thongbaoView.classList.add('active');
				viewTitle.textContent = "Quản Lý Thông Báo";
				break;
				
			case 'qlSD':
				removeActiveClass();
				sliderView.classList.add('active');
				viewTitle.textContent = "Quản Lý Slider Hình Ảnh";
				break;
				
			case 'qlGH':
				removeActiveClass();
				giohangView.classList.add('active');
				viewTitle.textContent = "Quản Lý Giỏ Hàng";
				break;
				
			case 'qlDSHA':
				removeActiveClass();
				dshaView.classList.add('active');
				viewTitle.textContent = "Quản Lý Danh Sách Hình Ảnh";
				break;
				
			case 'qlDSYT':
				removeActiveClass();
				dsytView.classList.add('active');
				viewTitle.textContent = "Quản Lý Danh Sách Yêu Thích";
				break;
				
			case 'tkND':
				removeActiveClass();
				tkndView.classList.add('active');
				viewTitle.textContent = "Thống Kê Người Dùng";
				break;
				
			case 'tkDG':
				removeActiveClass();
				tkdgView.classList.add('active');
				viewTitle.textContent = "Thống Kê Đấu Giá";
				break;
		}
		
		function removeActiveClass() {
			const sidebarItems = document.querySelectorAll('.sidebar-item');

			sidebarItems.forEach(item => {
				item.classList.remove('active');
			});
		}
	</script>
</body>
</html>