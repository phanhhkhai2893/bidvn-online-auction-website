<?php
// ----------------------------------------------------
// PHẦN LOGIC (INIT, ĐĂNG XUẤT, LOAD AVATAR) - GIỮ NGUYÊN
// ----------------------------------------------------
if (session_status() == PHP_SESSION_NONE) { session_start(); }

// --- XỬ LÝ ĐĂNG XUẤT ---
if (isset($_POST['dangxuat'])) {
    
    // 2. Xóa session cũ và cookies
    $_SESSION = array();
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
    }
    session_destroy();

    // 3. Khởi động session mới và gán thông báo
    session_start();
    $_SESSION['toast_status'] = $logout_message; 

    // 4. Redirect về trang chủ
	echo "<meta http-equiv='refresh' content='0; url=index.php' />";
    exit();
}

// --- XỬ LÝ CHUYỂN TRANG ADMIN ---
if (isset($_POST['toAdminSite'])) {
    $hoten = isset($_SESSION['tennd']) ? $_SESSION['tennd'] : '';
    header('Location: admin_index.php?hoten=' . urlencode($hoten));
    exit();
}

// --- LOGIC LOAD AVATAR VÀ DỮ LIỆU USER (ROW) ---
$avata_src = "assets/imgs/avatar/default_user.png"; 
$row = null; 

if (isset($_SESSION['mand'])) {
    $MaND_real = $_SESSION['mand'];
    
    if (isset($kn) && $kn->con) { 
        $sql = "SELECT * FROM nguoi_dung WHERE MaND = ?";
        $stmt = $kn->con->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param("s", $MaND_real);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                
                // XỬ LÝ TÌM ẢNH
                if (!empty($row["avarta"])) {
                    $allowed_extensions = ['.jpg', '.jpeg', '.png', '.gif'];
                    foreach ($allowed_extensions as $ext) {
                        $check_path = "assets/imgs/avatar/" . $row["avarta"] . $ext;
                        if (file_exists($check_path)) {
                            $avata_src = $check_path; 
                            break; 
                        }
                    }
                }
            }
            $stmt->close();
        }
    }
}
?>

<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        // Giả định màu chủ đạo (Vàng) là màu nền header
                        brand: '#FBC02D', 
                        brandHover: '#F9A825',
                        brandDark: '#333333', // Màu nền nút Đăng nhập
                    }
                }
            }
        }
    </script>
</head>

<body>

<header class="bg-brand shadow-none sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">

            <div class="flex-shrink-0 flex items-center bg-white p-3 pr-4 rounded-xl shadow-md">
                <a href="index.php" class="text-3xl font-extrabold text-gray-800 tracking-tighter flex items-center gap-2 group">
                    <i class="fas fa-gavel text-brand transform -rotate-12 group-hover:rotate-0 transition-transform duration-300"></i>
                    <span>BID<span class="text-brand">VN</span></span>
                </a>
            </div>

            <nav class="md:flex space-x-8">
                <a href="#" class="text-gray-900 hover:text-white font-semibold px-1 py-2 text-sm uppercase tracking-wide transition-colors">Đấu Giá</a>
                <a href="#danhmuc" class="text-gray-900 hover:text-white font-semibold px-1 py-2 text-sm uppercase tracking-wide transition-colors">Danh Mục</a>
                <a href="#sanpham" class="text-gray-900 hover:text-white font-semibold px-1 py-2 text-sm uppercase tracking-wide transition-colors">Sản Phẩm</a>
                <a href="#gioithieu" class="text-gray-900 hover:text-white font-semibold px-1 py-2 text-sm uppercase tracking-wide transition-colors">Giới Thiệu</a>
                <a href="#lienhe" class="text-gray-900 hover:text-white font-semibold px-1 py-2 text-sm uppercase tracking-wide transition-colors">Liên Hệ</a>
            </nav>

            <div class="flex items-center space-x-4">
                
                <?php if (isset($_SESSION['tennd'])): ?>
                    <div class="hidden sm:flex items-center space-x-4 border-r border-gray-900/20 pr-4 mr-1">
                        <a href="#" class="text-gray-800 hover:text-red-500 transition-colors relative group" title="Yêu thích">
                            <i class="fas fa-heart text-xl"></i>
                        </a>
                        <a href="giohang.php" class="text-gray-800 hover:text-brand transition-colors relative group" title="Giỏ hàng">
                            <i class="fas fa-shopping-cart text-xl"></i>
                            <span class="absolute -top-2 -right-2 bg-red-600 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">2</span>
                        </a>
                        <a href="#" class="text-gray-800 hover:text-blue-500 transition-colors relative" title="Thông báo">
                            <i class="fas fa-bell text-xl"></i>
                            <span class="absolute top-0 right-0 block h-2 w-2 rounded-full ring-2 ring-brand bg-red-400 transform translate-x-1/2 -translate-y-1/2"></span>
                        </a>
                    </div>
                    
                    <div class="relative group">

						<div class="bg-white rounded-full p-2 shadow-lg transition duration-300 hover:shadow-xl hover:ring-2 hover:ring-brand/50">
							<button class="flex items-center focus:outline-none">

								<div class="h-10 w-10 rounded-full ring-2 ring-gray-200 bg-gray-100 overflow-hidden shadow-sm mr-3 group-hover:ring-brand-DEFAULT transition-all">
									<img src="<?php echo $avata_src; ?>?v=<?php echo time(); ?>" alt="Avatar" class="h-full w-full object-cover">
								</div>

								<div class="lg:block text-left">
									<p class="text-m font-bold text-gray-800 group-hover:text-brand-DEFAULT transition-colors truncate max-w-[150px]">
										<?php echo htmlspecialchars($_SESSION['tennd']); ?>
									</p>
								</div>

								<i class="fas fa-chevron-down text-gray-500 text-xs ml-2 group-hover:text-brand-DEFAULT transition-colors"></i>
							</button>
						</div>
						<div class="absolute right-0 mt-4 w-56 bg-white rounded-xl shadow-2xl py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform origin-top-right z-50 border border-gray-100">

							<?php if (isset($_SESSION['role'])): // Mở khối IF lớn ?>

								<?php if ($_SESSION['role'] == "Admin"): ?>
									<form action="" method="post">
										<button name="toAdminSite" type="submit" class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-yellow-50 hover:text-brand transition-colors flex items-center">
											<i class="fas fa-tachometer-alt w-6 text-center mr-2"></i> Dashboard
										</button>
									</form>
								<?php elseif ($_SESSION['role'] == "Người bán"): ?>
									<a href="#" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-yellow-50 hover:text-brand transition-colors flex items-center">
										<i class="fas fa-clipboard-list w-6 text-center mr-2"></i> Quản lý tin đăng
									</a>
									<a href="#" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-yellow-50 hover:text-brand transition-colors flex items-center">
										<i class="fas fa-plus-circle w-6 text-center mr-2"></i> Đăng tin mới
									</a>
								<?php elseif ($_SESSION['role'] == "Người mua"): ?>
									<a href="giohang.php" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-yellow-50 hover:text-brand transition-colors flex items-center">
										<i class="fas fa-shopping-cart w-6 text-center mr-2"></i> Giỏ hàng
									</a>
									<a href="#" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-yellow-50 hover:text-brand transition-colors flex items-center">
										<i class="fas fa-history w-6 text-center mr-2"></i> Lịch sử đấu giá
									</a>
								<?php endif; ?> <div class="border-t border-gray-100 my-1"></div>

								<a href="thongtincanhan.php" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-yellow-50 hover:text-brand transition-colors flex items-center">
									<i class="fas fa-user-cog w-6 text-center mr-2"></i> Hồ sơ cá nhân
								</a>

								<div class="border-t border-gray-100 my-1"></div>

								<form action="" method="post">
									<button name="dangxuat" type="submit" class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors flex items-center">
										<i class="fas fa-sign-out-alt w-6 text-center mr-2"></i> Đăng xuất
									</button>
								</form>

							<?php endif; ?> </div>
							</div>
						</div>
					</div>

                <?php else: ?>
                    <div class="flex items-center space-x-3">
                        <button id="register-btn" class="text-gray-900 font-bold text-sm px-4 py-2 transition-colors hover:text-white">
                            Đăng ký
                        </button>
                        <button id="login-btn" class="bg-brandDark text-white font-bold text-sm px-5 py-2.5 rounded-lg hover:bg-gray-700 shadow-md transition-all">
                            Đăng nhập
                        </button>
                    </div>
                <?php endif; ?>

                <button class="md:hidden text-gray-900 hover:text-white focus:outline-none ml-2">
                    <i class="fas fa-bars text-2xl"></i>
                </button>

            </div>
        </div>
    </div>
</header>

<div class="banner"></div>  
<script type="text/javascript" src="../../js/dangnhap.js"></script>

</body>
</html>