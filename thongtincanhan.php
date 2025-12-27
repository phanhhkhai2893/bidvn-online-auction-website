<?PHP
    session_start();
    include('assets/php/database/db_connection.php');
    $kn = new db_connection();

    // --- PHẦN XỬ LÝ FORM (Đưa lên đầu để xử lý trước khi render HTML) ---
    if (isset($_POST["update-thongTin"])) {
        // 1. Lấy dữ liệu từ Form
        $hoten_new  = trim($_POST['hoten']);
        $sdt_new    = trim($_POST['sdt']);
        $email_new  = trim($_POST['email']);
        $diachi_new = trim($_POST['diachi']);
        $mand_current = $_SESSION['mand'];

        // --- NHIỆM VỤ 1: CẬP NHẬT THÔNG TIN CƠ BẢN ---
        // Luôn chạy query này để cập nhật text trước
        $sql_info = "UPDATE nguoi_dung SET hoten=?, sdt=?, email=?, diachi=? WHERE MaND=?";
        $stmt_info = $kn->con->prepare($sql_info);
        
        if ($stmt_info) {
            $stmt_info->bind_param("sssss", $hoten_new, $sdt_new, $email_new, $diachi_new, $mand_current);
            if (!$stmt_info->execute()) {
                echo "<script>alert('Lỗi khi cập nhật thông tin: " . $stmt_info->error . "');</script>";
            }
            $stmt_info->close();
        } else {
             echo "<script>alert('Lỗi kết nối CSDL (Info)!');</script>";
        }

        // --- NHIỆM VỤ 2: XỬ LÝ & CẬP NHẬT AVATAR (Chỉ chạy khi có file upload) ---
        if (isset($_FILES['avatar_file']) && $_FILES['avatar_file']['error'] == 0) {
            
            $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/bidvn/assets/imgs/avatar/";
            // Kiểm tra và tạo thư mục nếu chưa có
            if (!file_exists($target_dir)) {
				// 0777 nghĩa là cấp toàn quyền: Đọc, Ghi, Thực thi
				mkdir($target_dir, 0777, true);
			}

			// QUAN TRỌNG: Cố gắng cấp quyền ghi đè lên thư mục (kể cả khi đã tồn tại)
			chmod($target_dir, 0777);

            // Lấy tên file gốc và đuôi file
            $original_name = basename($_FILES["avatar_file"]["name"]);
            $imageFileType = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));
            
            // Danh sách đuôi file cho phép
            $allowed_types = array("jpg", "jpeg", "png", "gif");

            if (in_array($imageFileType, $allowed_types)) {
                // Tạo tên file mới không trùng: avatar_MAND_Timestamp
                // Lưu ý: Mình tạo tên KHÔNG CÓ ĐUÔI để lưu vào DB (khớp với logic hiển thị cũ của bạn)
                $filename_no_ext = "avatar_" . $mand_current . "_" . time();
                
                // Tên file đầy đủ để lưu vào ổ đĩa (Có đuôi)
                $target_file_on_disk = $target_dir . $filename_no_ext . "." . $imageFileType;

				echo $filename_no_ext . " and " . $target_file_on_disk;
                // Tiến hành di chuyển file vào thư mục
                if (move_uploaded_file($_FILES["avatar_file"]["tmp_name"], $target_file_on_disk)) {
                    
                    // Nếu upload thành công -> Cập nhật tên ảnh vào Database
                    // Chỉ cập nhật cột avarta
                    $sql_avatar = "UPDATE nguoi_dung SET avarta=? WHERE MaND=?";
                    $stmt_avatar = $kn->con->prepare($sql_avatar);
                    
                    if ($stmt_avatar) {
                        // Chỉ lưu tên file không đuôi vào DB (vì bên dưới bạn dùng loop để dò đuôi)
                        $stmt_avatar->bind_param("ss", $filename_no_ext, $mand_current);
                        if (!$stmt_avatar->execute()) {
                             echo "<script>alert('Lỗi cập nhật CSDL Avatar: " . $stmt_avatar->error . "');</script>";
                        }
                        $stmt_avatar->close();
                    }
                    
                } else {
                    echo "<script>alert('Không thể lưu file ảnh vào thư mục! Kiểm tra quyền ghi.');</script>";
                }
            } else {
                echo "<script>alert('Chỉ chấp nhận file ảnh JPG, JPEG, PNG, GIF!');</script>";
            }
        }

        // --- HOÀN TẤT: Refresh lại trang ---
        echo "<script>alert('Cập nhật hồ sơ thành công!'); window.location.href='thongtincanhan.php';</script>";
    }
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin cá nhân</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            light: '#FEF08A', 
                            DEFAULT: '#FACC15', 
                            dark: '#EAB308', 
                            hover: '#CA8A04', 
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        .bg-color-main { background-color: #ffc900; }
        .text-color-main { color: #ffc900; }
        @keyframes slideDownFade {
            from { opacity: 0; transform: translateY(-50px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .modal-animate { animation: slideDownFade 0.4s ease-out forwards; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased">

    <nav class="bg-brand-DEFAULT shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="logo">
                        <a href="index.php"></a>
                    </div>
                    <div class="hidden md:ml-6 md:flex md:space-x-8">
                        <a href="#" class="text-gray-900 hover:text-white px-3 py-2 rounded-md text-sm font-medium transition">Đấu Giá</a>
                        <a href="#" class="text-gray-900 hover:text-white px-3 py-2 rounded-md text-sm font-medium transition">Sản Phẩm</a>
                    </div>
                </div>
                <div class="flex items-center">
                    <button class="bg-white text-brand-dark px-4 py-2 rounded-full text-sm font-bold shadow hover:bg-gray-100 transition">
                        <a href="index.php">
                            <i class="fas fa-arrow-left mr-2"></i> Quay lại trang chủ
                        </a>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="lg:grid lg:grid-cols-12 lg:gap-8">
            
            <aside class="lg:col-span-3 mb-6 lg:mb-0">
                <nav class="space-y-1">
                    <a href="#" class="bg-white text-black border-l-4 border-brand-DEFAULT group flex items-center px-3 py-4 text-sm font-medium shadow-sm rounded-r-md" aria-current="page">
                        <span class="truncate font-bold text-lg">Hồ Sơ Cá Nhân</span>
                    </a>
                    <a href="assets/php/sections/dangxuat.php" onClick="signOut()" class="text-red-600 hover:bg-red-50 hover:text-red-700 group flex items-center px-3 py-3 text-sm font-medium rounded-md transition mt-8">
                        <i class="fas fa-sign-out-alt text-red-400 group-hover:text-red-500 mr-3 w-6 text-center text-lg"></i>
                        <span class="truncate">Đăng xuất</span>
                    </a>
                </nav>
            </aside>

<?PHP
            if (isset($_SESSION['mand'])) {
                $MaND_real = $_SESSION['mand'];
                $sql = "SELECT * FROM nguoi_dung WHERE MaND = ?";
                $stmt = $kn->con->prepare($sql);

                if ($stmt) {
                    $stmt->bind_param("s", $MaND_real);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                    } else {
                        $row = null;
                    }
                    $stmt->close();
                } else {
                    error_log("Lỗi chuẩn bị truy vấn: " . $kn->con->error);
                }
            }
            else
            {
                echo "<meta http-equiv='refresh' content='0; url=index.php' />";
                exit();
            }
            
            
    $avata_src = "assets/imgs/avatar/default_user.png";
    $allowed_extensions = ['.jpg', '.png', '.jpeg', '.gif'];
    // Logic hiển thị: Dò tìm file có tên trong DB + các đuôi phổ biến
    foreach ($allowed_extensions as $ext) {
        $check_path = "assets/imgs/avatar/" . $row["avarta"] . $ext;
        if (file_exists($check_path)) {
            $avata_src = $check_path; 
            break; 
        }
    }
?>
            <main class="lg:col-span-9 space-y-6">
                
                <form action="" method="POST" enctype="multipart/form-data">
                
                <div class="bg-white shadow overflow-hidden sm:rounded-lg relative mb-6">
                    <div class="h-32 bg-gradient-to-r from-brand-DEFAULT to-yellow-200"></div>
                    
                    <div class="px-4 py-5 sm:px-6 relative">
                        <div class="flex flex-col sm:flex-row items-center sm:items-end -mt-16 sm:-mt-20 mb-4">
                            <div class="relative">
                                <div class="h-32 w-32 rounded-full ring-4 ring-white bg-white overflow-hidden shadow-lg">
                                    <img id="avatar-preview" src="<?php echo $avata_src; ?>" alt="Avatar" class="h-full w-full object-cover">
                                </div>
                                
                                <button type="button" onclick="document.getElementById('file-upload').click()" 
                                        class="absolute bottom-2 right-2 bg-gray-800 text-white p-1 px-2 rounded-full hover:bg-gray-700 transition shadow-md" title="Đổi ảnh đại diện">
                                    <i class="fas fa-camera text-xs"></i>
                                </button>
                                
                                <input id="file-upload" name="avatar_file" type="file" class="hidden" accept="image/*" onchange="previewImage(this)">
                            </div>
                            
                            <div class="mt-4 sm:mt-0 sm:ml-6 text-center sm:text-left flex-1">
                                <h1 class="text-2xl font-bold text-gray-900">
                                    <?php echo $row["hoten"]; ?>
                                </h1>
                                <p class="text-sm font-medium text-gray-500">
                                    <?php echo "@" . $row["username"] . " (" . $row["phanquyen"] . ")"; ?>
                                </p>
                                <div class="mt-2 flex items-center justify-center sm:justify-start text-sm text-gray-500">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <span class="w-2 h-2 mr-1 bg-green-400 rounded-full"></span>
                                        <?php echo $row["trangthai"]; ?>
                                    </span>
                                    <span class="mx-2 text-gray-300">|</span>
                                    <i class="fas fa-calendar-alt mr-1 text-gray-400"></i> Tham gia: 
                                    <?php echo date("d/m/Y", strtotime($row['ngaydangky'])); ?>
                                </div>
                            </div>
                            
                            <div class="mt-4 sm:mt-0 flex space-x-3">
                                <button type="button" onclick="toggleModal('modal-password')" 
                                        class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none transition">
                                    <i class="fas fa-key mr-2"></i> Đổi mật khẩu
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow sm:rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 flex items-center mb-6 border-b pb-2">
                            <i class="fas fa-user-edit text-brand-dark mr-2"></i> Thông tin cá nhân
                        </h3>
                        
                            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                
                                <div class="sm:col-span-2">
                                    <label for="mand" class="block text-sm font-medium text-gray-700">Mã ND</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-id-card text-gray-400"></i>
                                        </div>
                                        <input type="text" name="mand" id="mand" value="<?php echo $row["MaND"]; ?>" disabled
                                            class="bg-gray-100 focus:ring-brand-DEFAULT focus:border-brand-DEFAULT block w-full pl-10 sm:text-sm border-gray-300 rounded-md text-gray-500 cursor-not-allowed py-2 border">
                                    </div>
                                </div>

                                <div class="sm:col-span-4">
                                    <label for="username" class="block text-sm font-medium text-gray-700">Tên đăng nhập</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-user text-gray-400"></i>
                                        </div>
                                        <input type="text" name="username" id="username" value="<?php echo $row["username"]; ?>" disabled
                                            class="bg-gray-100 focus:ring-brand-DEFAULT focus:border-brand-DEFAULT block w-full pl-10 sm:text-sm border-gray-300 rounded-md text-gray-500 cursor-not-allowed py-2 border">
                                    </div>
                                </div>

                                <div class="sm:col-span-3">
                                    <label for="hoten" class="block text-sm font-medium text-gray-700">Họ và tên</label>
                                    <div class="mt-1">
                                        <input type="text" name="hoten" id="hoten" value="<?php echo $row["hoten"]; ?>"
                                            class="shadow-sm focus:ring-brand-DEFAULT focus:border-brand-DEFAULT block w-full sm:text-sm border-gray-300 rounded-md py-2 px-3 border transition duration-150 ease-in-out hover:border-brand-hover">
                                    </div>
                                </div>

                                <div class="sm:col-span-3">
                                    <label for="sdt" class="block text-sm font-medium text-gray-700">Số điện thoại</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-phone text-gray-400"></i>
                                        </div>
                                        <input type="text" name="sdt" id="sdt" value="<?php echo $row["sdt"]; ?>"
                                            class="focus:ring-brand-DEFAULT focus:border-brand-DEFAULT block w-full pl-10 sm:text-sm border-gray-300 rounded-md py-2 border">
                                    </div>
                                </div>

                                <div class="sm:col-span-4">
                                    <label for="email" class="block text-sm font-medium text-gray-700">Địa chỉ Email</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-envelope text-gray-400"></i>
                                        </div>
                                        <input type="email" name="email" id="email" value="<?php echo $row["email"]; ?>"
                                            class="focus:ring-brand-DEFAULT focus:border-brand-DEFAULT block w-full pl-10 sm:text-sm border-gray-300 rounded-md py-2 border">
                                    </div>
                                </div>

                                <div class="sm:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Vai trò</label>
                                    <div class="mt-2">
                                        <?php
                                        $role = $row["phanquyen"];
                                        $badgeClass = '';
                                        $iconClass = '';
                                        switch ($role) {
                                            case 'Admin':
                                                $badgeClass = 'bg-purple-100 text-purple-800';
                                                $iconClass = 'fa-crown';
                                                break;
                                            case 'Người bán':
                                                $badgeClass = 'bg-blue-100 text-blue-800';
                                                $iconClass = 'fa-store';
                                                break;
                                            case 'Người mua':
                                            default:
                                                $badgeClass = 'bg-yellow-100 text-yellow-800';
                                                $iconClass = 'fa-shopping-cart';
                                                break;
                                        }
                                        ?>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium shadow-sm <?php echo $badgeClass; ?>">
                                            <i class="fas <?php echo $iconClass; ?> mr-1.5 text-xs"></i>
                                            <?php echo $role; ?>
                                        </span>
                                    </div>
                                </div>

                                <div class="sm:col-span-6">
                                    <label for="diachi" class="block text-sm font-medium text-gray-700">Địa chỉ giao hàng mặc định</label>
                                    <div class="mt-1">
                                        <textarea id="diachi" name="diachi" rows="3" class="shadow-sm focus:ring-brand-DEFAULT focus:border-brand-DEFAULT block w-full sm:text-sm border border-gray-300 rounded-md p-2"><?php echo $row["diachi"]; ?></textarea>
                                    </div>
                                    <p class="mt-2 text-sm text-gray-500">
                                        <?php
                                            if ($row["phanquyen"] == "Người mua")
                                            {
                                                echo "Địa chỉ này sẽ được dùng để giao sản phẩm khi bạn đấu giá thành công.";
                                            }
                                        ?>
                                    </p>
                                </div>
                            </div>

                            <div class="mt-8 border-t border-gray-200 pt-5">
                                <div class="flex justify-end">
                                    <button type="button" onClick="window.location.reload()" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-DEFAULT">
                                        Hủy bỏ
                                    </button>
                                    <button type="submit" name="update-thongTin" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-gray-900 bg-brand-DEFAULT hover:bg-brand-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-DEFAULT transition transform hover:-translate-y-0.5">
                                        <i class="fas fa-save mr-2 mt-0.5"></i> Lưu thay đổi
                                    </button>
                                </div>
                            </div>
                    </div>
                </div>
                
                </form> </main>
        </div>
    </div>

  <?PHP include('assets/php/sections/footer.php') ?>

<div id="modal-password" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">

            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="toggleModal('modal-password')"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="modal-animate inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">

                <form action="" method="POST">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-[#ffc900] bg-opacity-20 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="fas fa-lock text-[#ffc900]"></i>
                            </div>

                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Đổi mật khẩu</h3>
                                <div class="mt-4 space-y-4">

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Mật khẩu hiện tại</label>
                                        <input type="password" name="old_pass" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-[#ffc900] focus:border-[#ffc900] sm:text-sm">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Mật khẩu mới</label>
                                        <input type="password" name="new_pass" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-[#ffc900] focus:border-[#ffc900] sm:text-sm">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Xác nhận mật khẩu mới</label>
                                        <input type="password" name="confirm_pass" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-[#ffc900] focus:border-[#ffc900] sm:text-sm">
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" name="btn-change-pass" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#ffc900] text-base font-medium text-black hover:bg-opacity-80 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                            Lưu mật khẩu
                        </button>
                        <?php
                            // Xử lý khi nhấn nút "Lưu mật khẩu"
                            if (isset($_POST['btn-change-pass'])) {
                                $old_pass     = $_POST['old_pass'];
                                $new_pass     = $_POST['new_pass'];
                                $confirm_pass = $_POST['confirm_pass'];
                                $mand_current = $_SESSION['mand']; // Lấy ID người dùng từ session

                                if ($new_pass !== $confirm_pass) {
                                    echo "<script>alert('Mật khẩu mới và xác nhận mật khẩu không khớp!');</script>";
                                } else {
                                    if ($old_pass == $row['password']) { 

                                        $sql_update_pass = "UPDATE nguoi_dung SET password = ? WHERE MaND = ?";
                                        $stmt_pass = $kn->con->prepare($sql_update_pass);

                                        if ($stmt_pass) {
                                            $stmt_pass->bind_param("ss", $new_pass, $mand_current);
                                            if ($stmt_pass->execute()) {
                                                
                                                $_SESSION = array();

                                                if (ini_get("session.use_cookies")) {
                                                    $params = session_get_cookie_params();
                                                    setcookie(session_name(), '', time() - 42000,
                                                    $params["path"], $params["domain"],
                                                    $params["secure"], $params["httponly"]
                                                    );
                                                }

                                                session_destroy();
                                                
                                                echo "<script>alert('Đổi mật khẩu thành công! Vui lòng đăng nhập lại.'); 
                                                window.location.href='index.php';</script>";
                                            } else {
                                                echo "<script>alert('Lỗi cập nhật: " . $stmt_pass->error . "');</script>";
                                            }
                                            $stmt_pass->close();
                                        }
                                    } else {
                                        echo "<script>alert('Mật khẩu hiện tại không đúng!');</script>";
                                    }
                                }
                            }
                            ?>
                        <button type="button" onclick="toggleModal('modal-password')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Hủy
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    
<script>
    // Hàm xem trước ảnh
    function previewImage(input) {
        // In ra console để kiểm tra xem hàm có chạy không
        console.log("Đã chọn file:", input.files);

        // Kiểm tra xem có file nào được chọn không
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            // Khi đọc file xong thì gán link ảnh vào thẻ img
            reader.onload = function(e) {
                console.log("Đã đọc xong file, đang gán ảnh...");
                
                // Tìm thẻ img theo ID 'avatar-preview'
                var imgElement = document.getElementById('avatar-preview');
                
                if (imgElement) {
                    imgElement.src = e.target.result;
                } else {
                    alert("Lỗi: Không tìm thấy thẻ img có id='avatar-preview'");
                }
            }

            // Bắt đầu đọc file dưới dạng URL
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Các hàm modal cũ giữ nguyên
    function toggleModal(modalID){
        document.getElementById(modalID).classList.toggle("hidden");
        document.getElementById(modalID + "-backdrop").classList.toggle("hidden");
        document.getElementById(modalID).classList.toggle("flex");
        document.getElementById(modalID + "-backdrop").classList.toggle("flex");
    }

    window.toggleModal = function(modalID) {
        const modal = document.getElementById(modalID);
        modal.classList.toggle('hidden');
    }
</script>
</body>
</html>