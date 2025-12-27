<?PHP
// ----------------------------------------------------
// PHẦN 1: KHỞI TẠO & XỬ LÝ POST (BẮT BUỘC ĐẶT TRÊN CÙNG FILE)
// ----------------------------------------------------
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Giả sử file db_connection.php đã được include ở đây (hoặc file cha)
// include('assets/php/database/db_connection.php'); 
// $kn = new db_connection(); 

$masp = isset($_GET['masp']) ? $_GET['masp'] : null;

// --- LOGIC XỬ LÝ TRẢ GIÁ (POST) ---
if (isset($_POST['tragia']) && $masp) {
    
    // 1. TRUY VẤN DỮ LIỆU SẢN PHẨM HIỆN TẠI ĐỂ VALIDATE
    $sql_validate = "SELECT gia_hientai, buocgia, gia_khoidiem, trangthai, thoigian_ketthuc FROM san_pham WHERE MaSP = ?";
    $stmt_validate = $kn->con->prepare($sql_validate);
    $stmt_validate->bind_param("s", $masp);
    $stmt_validate->execute();
    $row_validation = $stmt_validate->get_result()->fetch_assoc();
    $stmt_validate->close();
    
    // LẤY BIẾN VÀ VALIDATE
    $gia_thau_moi = (float) $_POST['gia_thau']; 
    $gia_hien_tai = (float) $row_validation['gia_hientai'];
    $buoc_gia = (float) $row_validation['buocgia'];
    $ghmand = isset($_SESSION['mand']) ? $_SESSION['mand'] : null;
    $ghmasp = $masp; 
    $error = null;
    
    // 2. VALIDATION LOGIC
    if (empty($ghmand)) {
		echo "<script>alert('Vui lòng đăng nhập để đấu giá!');</script>";
    } else if (!isset($_SESSION['role']) || $_SESSION['role'] != "Người mua") {
		echo "<script>alert('Chỉ người mua được phép tham gia đấu giá!');</script>";
    } else if ($gia_thau_moi < ($gia_hien_tai + $buoc_gia)) {
        $min_bid = number_format($gia_hien_tai + $buoc_gia, 0, '', '.');
		echo "<script>alert('Giá thầu tối thiểu phải là: $min_bid đ!');</script>";
    } else if (strtotime($row_validation['thoigian_ketthuc']) < time()) {
		echo "<script>alert('Đấu giá đã kết thúc! Không thể đặt giá!');</script>";
    }

    // 3. THỰC HIỆN UPDATE VÀ LOG
    if (!$error) {
        $thoi_gian_hien_tai = date('Y-m-d H:i:s'); 
        $new_end_time = $row_validation['thoigian_ketthuc']; // Giữ nguyên mặc định

        // --- ANTI-SNIPING: GIA HẠN THỜI GIAN ---
        $time_remaining = strtotime($row_validation['thoigian_ketthuc']) - time();
        $extension_time_seconds = 30 * 60; // 30 phút = 1800 giây
        
        if ($time_remaining < $extension_time_seconds) {
            $new_end_time = date('Y-m-d H:i:s', time() + $extension_time_seconds);
            
            // UPDATE thời gian kết thúc trong DB
            $sql_extend = "UPDATE san_pham SET thoigian_ketthuc = ? WHERE MaSP = ?";
            $stmt_extend = $kn->con->prepare($sql_extend);
            $stmt_extend->bind_param("ss", $new_end_time, $ghmasp);
            $stmt_extend->execute();
            $stmt_extend->close();
			
            echo "<script>alert('Đặt giá thành công! Đấu giá được gia hạn thêm 30 phút.');</script>";
        } else {
            echo "<script>alert('Đặt giá thành công!');</script>";
        }
        // --- END ANTI-SNIPING ---

        $sql_update_gia = "UPDATE san_pham SET gia_hientai = ? WHERE MaSP = ?";
        $stmt_update = $kn->con->prepare($sql_update_gia);
        $sql_log = "INSERT INTO lich_su_dau_gia (gia_hientai, thoigian_dau, MaND, MaSP) VALUES (?, ?, ?, ?)";
        $stmt_log = $kn->con->prepare($sql_log);

        if ($stmt_update && $stmt_log) {
            $stmt_update->bind_param("ds", $gia_thau_moi, $ghmasp);
            $update_success = $stmt_update->execute();

            $stmt_log->bind_param("dsss", $gia_thau_moi, $thoi_gian_hien_tai, $ghmand, $ghmasp);
            $log_success = $stmt_log->execute();

            if ($update_success && $log_success) {
                // REDIRECT THÀNH CÔNG
                $_SESSION['bid_success'] = $success_message;
				echo '<meta http-equiv="refresh" content="0; url=sanpham_detail.php?masp=' .$ghmasp .'" />';
                exit();
            } else {
                $_SESSION['bid_error'] = 'Lỗi CSDL khi ghi lịch sử đấu giá hoặc cập nhật giá!';
                header("Location: sanpham_detail.php?masp=$ghmasp");
                exit();
            }
        }
    } else {
        // Nếu có lỗi validation, REDIRECT về trang cũ với thông báo lỗi
        $_SESSION['bid_error'] = $error;
        header("Location: sanpham_detail.php?masp=$ghmasp");
        exit();
    }
}

// --- 4. HOÀN TẤT ĐẤU GIÁ VÀ CHUYỂN TRẠNG THÁI (ƯU TIÊN CHẠY ĐƯỢC) ---
if ($masp) {
    // Cấu hình báo lỗi SQL và Múi giờ để debug ngay nếu chết code
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    date_default_timezone_set('Asia/Ho_Chi_Minh');

    try {
        // 1. Lấy thông tin sản phẩm
        $sql_final = "SELECT gia_hientai, trangthai, thoigian_ketthuc FROM san_pham WHERE MaSP = ?";
        $stmt_final = $kn->con->prepare($sql_final);
        $stmt_final->bind_param("s", $masp);
        $stmt_final->execute();
        $final_data = $stmt_final->get_result()->fetch_assoc();
        $stmt_final->close();

        // 2. Logic: Nếu Đang đấu giá VÀ Hết giờ => Xử lý ngay
        if ($final_data && trim($final_data['trangthai']) == 'Đang đấu giá' && strtotime($final_data['thoigian_ketthuc']) <= time()) {
            
            // [BƯỚC 1]: Tìm người thắng (Người trả giá cao nhất)
            $sql_winner = "SELECT MaND, gia_hientai FROM lich_su_dau_gia WHERE MaSP = ? ORDER BY gia_hientai DESC LIMIT 1";
            $stmt_winner = $kn->con->prepare($sql_winner);
            $stmt_winner->bind_param("s", $masp);
            $stmt_winner->execute();
            $winner_row = $stmt_winner->get_result()->fetch_assoc();
            $stmt_winner->close();

            // [BƯỚC 2]: QUAN TRỌNG - THÊM VÀO GIỎ HÀNG NGAY
            if ($winner_row) {
                $winner_id = $winner_row['MaND'];
                $price     = $winner_row['gia_hientai'];

                // Kiểm tra nhanh xem đã có trong giỏ chưa để tránh lỗi trùng khóa (Duplicate entry)
                $check_cart = $kn->con->prepare("SELECT MaSP FROM gio_hang WHERE MaSP = ? AND MaND = ?");
                $check_cart->bind_param("ss", $masp, $winner_id);
                $check_cart->execute();
                
                if ($check_cart->get_result()->num_rows == 0) {
                    // Chưa có thì Thêm mới. 
                    // LƯU Ý: Mình để mặc định SoLuong = 1. Kiểm tra lại DB của bạn xem cột ThanhTien hay GiaTien
                    $insert_cart = $kn->con->prepare("INSERT INTO gio_hang (MaND, MaSP, ThanhTien) VALUES (?, ?, ?)");
                    $insert_cart->bind_param("ssd", $winner_id, $masp, $price);
                    $insert_cart->execute();
                    $insert_cart->close();
                }
                $check_cart->close();
            }

            // [BƯỚC 3]: Cập nhật trạng thái thành 'Kết thúc'
            // Chạy đến đây nghĩa là bước trên đã xong (hoặc không có người thắng), giờ khóa sản phẩm lại.
            $update_status = $kn->con->prepare("UPDATE san_pham SET trangthai = 'Kết thúc' WHERE MaSP = ?");
            $update_status->bind_param("s", $masp);
            $update_status->execute();
            $update_status->close();

            // [BƯỚC 4]: F5 lại trang để hiển thị kết quả
            echo '<meta http-equiv="refresh" content="0; url=sanpham_detail.php?masp=' .$masp .'&status=finalized" />';
            exit();
        }

    } catch (Exception $e) {
        // Nếu lỗi, hiện rõ ra màn hình để biết đường sửa (Ví dụ: Sai tên cột gio_hang)
        echo "<div style='background: red; color: white; padding: 20px; font-weight: bold; text-align: center;'>";
        echo "LỖI XỬ LÝ KẾT THÚC: " . $e->getMessage();
        echo "</div>";
        exit(); // Dừng trang web lại để bạn đọc lỗi
    }
}
// --- 5. LOGIC LẤY DỮ LIỆU ĐỂ HIỂN THỊ TRANG (GET REQUEST) ---

$row = null;
$highest_bidder = null; 
$user_is_highest = false;

if ($masp) {
    // 1. TRUY VẤN DỮ LIỆU SẢN PHẨM CHÍNH
    $sql_display = "SELECT sp.*, dmc.tendm, nd.hoten, nd.diachi 
                    FROM san_pham sp 
                    INNER JOIN danh_muc_con dmc ON sp.MaDMCon = dmc.MaDMCon 
                    INNER JOIN nguoi_dung nd ON sp.MaND = nd.MaND 
                    WHERE sp.MaSP = ?";
    $stmt_display = $kn->con->prepare($sql_display);
    if ($stmt_display) {
        $stmt_display->bind_param("s", $masp);
        $stmt_display->execute();
        $row = $stmt_display->get_result()->fetch_assoc();
        $stmt_display->close();
    }
    
    // 2. TRUY VẤN NGƯỜI ĐẤU GIÁ CAO NHẤT (Chỉ khi sản phẩm tồn tại)
    if ($row) {
        $current_max_price = $row['gia_hientai'];
        $current_user_id = isset($_SESSION['mand']) ? $_SESSION['mand'] : '';
        
        if ($current_max_price > $row['gia_khoidiem']) {
            $sql_bidder = "
                SELECT 
                    nd.hoten AS ten_nguoi_dau, 
                    lsdg.MaND AS ma_nguoi_dau
                FROM lich_su_dau_gia lsdg
                JOIN nguoi_dung nd ON lsdg.MaND = nd.MaND
                WHERE 
                    lsdg.MaSP = ? AND lsdg.gia_hientai = ?
                ORDER BY 
                    lsdg.thoigian_dau DESC 
                LIMIT 1
            ";

            $stmt_bidder = $kn->con->prepare($sql_bidder);
            if ($stmt_bidder) {
                $stmt_bidder->bind_param("sd", $masp, $current_max_price);
                $stmt_bidder->execute();
                $highest_bidder = $stmt_bidder->get_result()->fetch_assoc();
                $stmt_bidder->close();

                if ($highest_bidder && $highest_bidder['ma_nguoi_dau'] == $current_user_id) {
                    $user_is_highest = true;
                }
            }
        }
    }
}
// Xử lý ảnh và thời gian cho hiển thị (Cần phải chạy sau khi $row được load)
$img_path = "assets/imgs/product/default_product.png";
if ($row) {
    if (!empty($row['hinhanh'])) {
        if (strpos($row['hinhanh'], '.') !== false) {
            $check_path = "assets/imgs/product/" . $row['hinhanh'];
            if (file_exists($check_path)) $img_path = $check_path;
        } else {
            $exts = ['.jpg', '.png', '.jpeg', '.webp'];
            foreach ($exts as $ext) {
                $check_path = "assets/imgs/product/" . $row['hinhanh'] . $ext;
                if (file_exists($check_path)) {
                    $img_path = $check_path;
                    break;
                }
            }
        }
    }
    $endTime = $row['thoigian_ketthuc'];
} else {
    $endTime = date('Y-m-d H:i:s'); // Gán giá trị mặc định để JS không bị lỗi
}

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Chi tiết sản phẩm</title>
</head>

<body>
    
    <?PHP
    // --- HIỂN THỊ TOAST DỰA TRÊN KẾT QUẢ SESSION SAU KHI REDIRECT ---
    if (isset($_SESSION['bid_success'])) {
        echo "<script>document.addEventListener('DOMContentLoaded', function() { showToast('".$_SESSION['bid_success']."', 'success', 4000); });</script>";
        unset($_SESSION['bid_success']);
    } else if (isset($_SESSION['bid_error'])) {
        echo "<script>document.addEventListener('DOMContentLoaded', function() { showToast('".$_SESSION['bid_error']."', 'error', 5000); });</script>";
        unset($_SESSION['bid_error']);
    }
    
    // Xử lý lỗi không tìm thấy sản phẩm
    if (!$row) {
        echo "<div class='p-10 text-center text-red-500 font-bold'>Không tìm thấy sản phẩm hoặc sản phẩm đã bị xóa!</div>";
    } else {
        // --- Bắt đầu HTML chi tiết ---
    ?>
    <div class="detail-container py-8 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    
        <nav class="text-sm font-medium text-gray-500 mb-6 flex flex-wrap gap-2 items-center">
            <a href="index.php" class="hover:text-brand-DEFAULT transition">Trang chủ</a> 
            <span>/</span> 
            <a href="#" class="hover:text-brand-DEFAULT transition"><?PHP echo $row['tendm']; ?></a> 
            <span>/</span> 
            <span class="text-gray-900 font-semibold truncate max-w-[200px]"><?PHP echo $row['tensp']; ?></span>
        </nav>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-10">
            
            <div class="md:col-span-6 lg:col-span-7">
                <div class="main-image bg-gray-50 rounded-2xl overflow-hidden border border-gray-100 flex items-center justify-center h-[450px] relative group shadow-md">
                    <img src="<?PHP echo $img_path; ?>?v=<?php echo time(); ?>" 
                        alt="<?PHP echo $row['tensp']; ?>" 
                        class="max-w-full max-h-full object-contain transform group-hover:scale-105 transition duration-500">
                    
                    <span class="absolute top-4 left-4 bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide shadow-md animate-pulse">
                        <?PHP echo $row['trangthai']; ?>
                    </span>
                </div>
                
                <div class="thumbnail-bar mt-4 flex gap-3 overflow-x-auto pb-2">
                    <img class="w-20 h-20 object-cover rounded-lg border-2 border-brand-DEFAULT cursor-pointer p-1" src="<?PHP echo $img_path; ?>" alt="">
                </div>
            </div>

            <div class="detail-info md:col-span-6 lg:col-span-5 space-y-6">
                <h1 class="text-4xl font-extrabold text-gray-900 leading-tight">
                    <?PHP echo $row['tensp']; ?>
                </h1>
                
                <div class="flex flex-wrap items-center gap-x-6 gap-y-2 text-sm text-gray-500 border-b border-dashed border-gray-200 pb-4">
                    <div class="flex items-center">
                        <i class="fas fa-user-tag text-lg mr-2 text-red-500"></i>
                        <span class="font-semibold text-gray-800">Người đấu giá cao nhất:</span>
                        <span class="font-bold text-gray-900 ml-1 truncate">
                            <?php 
                                if ($user_is_highest) {
                                    echo "Bạn (".$highest_bidder['ten_nguoi_dau'].")"; 
                                } else if ($highest_bidder) {
                                    // Ẩn tên người đấu giá nếu đấu giá đang diễn ra (quy tắc bảo mật)
                                    if ($row['trangthai'] == 'Đang đấu giá') {
                                        echo "Ẩn danh (Mã: ". substr($highest_bidder['ma_nguoi_dau'], -3) . ")";
                                    } else {
                                        echo $highest_bidder['ten_nguoi_dau']; 
                                    }
                                } else {
                                    echo "Chưa có";
                                }
                            ?>
                        </span>
                        <?php if ($user_is_highest): ?>
                        <i class="fas fa-crown text-brand-DEFAULT ml-2"></i>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="flex flex-wrap items-center gap-x-6 gap-y-2 text-sm text-gray-500 border-b border-dashed border-gray-200 pb-4">
                    <div class="flex items-center">
                        <i class="fas fa-user-circle text-lg mr-2 text-brand-DEFAULT"></i>
                        Người bán: <span class="font-semibold text-gray-800 ml-1"><?PHP echo $row['hoten']; ?></span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-map-marker-alt text-lg mr-2 text-brand-DEFAULT"></i>
                        <span class="truncate max-w-[200px]"><?PHP echo $row['diachi']; ?></span>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-500">Giá hiện tại</span>
                        <span class="text-sm text-gray-400 line-through">Khởi điểm: <?PHP echo number_format($row['gia_khoidiem'], 0, '', '.'); ?>đ</span>
                    </div>
                    <div class="text-4xl font-extrabold text-red-600 font-mono tracking-tight">
                        <span class="current-price-display">
                            <?PHP echo number_format($row['gia_hientai'], 0, '', '.'); ?>
                        </span> 
                        <span class="text-2xl text-red-400">₫</span>
                    </div>
                    <div class="mt-2 text-sm text-gray-500 flex items-center">
                        <i class="fas fa-gavel mr-2 text-brand-DEFAULT"></i>
                        Bước giá tối thiểu: <span class="font-bold text-gray-800 ml-1">+<?PHP echo number_format($row['buocgia'], 0, '', '.'); ?>đ</span>
                    </div>
                </div>

                <div class="countdown-area bg-brand-DEFAULT/10 p-4 rounded-xl border border-brand-DEFAULT/20">
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-widest mb-3 flex items-center">
                        <i class="fas fa-clock mr-2 text-brand-DEFAULT"></i> Thời gian còn lại
                    </h3>
                    <div id="auction-countdown" class="grid grid-cols-4 gap-3 text-center" data-endtime="<?PHP echo $endTime; ?>" data-status="<?PHP echo $row['trangthai']; ?>" >
                        <div class="bg-gray-800 text-white rounded-lg p-2 shadow-lg">
                            <span id="days" class="block text-2xl font-bold font-mono">00</span>
                            <span class="text-[10px] uppercase text-gray-400">Ngày</span>
                        </div>
                        <div class="bg-gray-800 text-white rounded-lg p-2 shadow-lg">
                            <span id="hours" class="block text-2xl font-bold font-mono">00</span>
                            <span class="text-[10px] uppercase text-gray-400">Giờ</span>
                        </div>
                        <div class="bg-gray-800 text-white rounded-lg p-2 shadow-lg">
                            <span id="minutes" class="block text-2xl font-bold font-mono">00</span>
                            <span class="text-[10px] uppercase text-gray-400">Phút</span>
                        </div>
                        <div class="bg-brand-DEFAULT text-gray-900 rounded-lg p-2 shadow-lg shadow-brand-DEFAULT/40">
                            <span id="seconds" class="block text-2xl font-bold font-mono">00</span>
                            <span class="text-[10px] uppercase font-bold">Giây</span>
                        </div>
                    </div>
                    <div id="countdown-message" class="text-red-600 font-bold mt-3 text-center hidden">Đã kết thúc!</div>
                </div>

                <form action="sanpham_detail.php?masp=<?php echo $row['MaSP']; ?>" method="post" class="mt-8 space-y-3">
                    
                    <input type="hidden" name="masp" value="<?php echo $row['MaSP']; ?>"> 
                    
                    <div class="flex gap-3">
                        <input type="number" name="gia_thau" id="gia_thau_input" 
						placeholder="<?php echo number_format($row['gia_hientai'] + $row['buocgia'], 0, '', '.'); ?> (Giá tối thiểu)" 
						min="<?php echo $row['gia_hientai'] + $row['buocgia']; ?>"
						step="<?php echo $row['buocgia']; ?>"  required 
						class="flex-1 border border-gray-300 rounded-xl px-4 py-3 text-lg focus:ring-brand-DEFAULT focus:border-brand-DEFAULT shadow-inner">

                        <button name="tragia" type="submit" 
                                class="bg-brand-DEFAULT hover:bg-brand-dark text-gray-900 font-bold text-lg py-3 px-6 rounded-xl shadow-lg hover:shadow-brand-DEFAULT/40 transition transform hover:-translate-y-1 flex items-center justify-center gap-2">
                            <i class="fas fa-hand-holding-usd"></i>
                            Trả giá
                        </button>
                    </div>

                    <p class="text-xs text-center text-gray-500 pt-1">
                        Bước giá tối thiểu: **+<?PHP echo number_format($row['buocgia'], 0, '', '.'); ?>đ**
                    </p>
                </form>
            </div>
        </div>

        <div class="mt-12 border-t border-gray-200 pt-8">
            <h3 class="text-xl font-bold text-gray-900 mb-4 border-l-4 border-brand-DEFAULT pl-3">Mô tả sản phẩm</h3>
            <div class="prose max-w-none text-gray-600 leading-relaxed bg-gray-50 p-6 rounded-xl border border-gray-100">
                <?PHP echo nl2br($row['mota']); ?>
            </div>
        </div>
    </div>
    <?php } ?>

	<script>
    document.addEventListener('DOMContentLoaded', function() {
        const timerDisplay = document.getElementById('auction-countdown');
        if (!timerDisplay) return;
        
        const endTimeStr = timerDisplay.getAttribute('data-endtime');
        const currentStatus = timerDisplay.getAttribute('data-status'); // Lấy trạng thái
        const msg = document.getElementById("countdown-message");

        // [FIX LOOP] KIỂM TRA TRẠNG THÁI TRƯỚC
        // Nếu PHP đã cập nhật là "Kết thúc" rồi thì JS không cần làm gì cả.
        if (currentStatus === 'Kết thúc') {
            timerDisplay.classList.add('hidden');
            msg.classList.remove('hidden');
            msg.innerText = "Đã kết thúc!";
            return; // Dừng code tại đây, không chạy setInterval bên dưới
        }

        const countDownDate = new Date(endTimeStr).getTime();

        const x = setInterval(function() {
            const now = new Date().getTime();
            const distance = countDownDate - now;

            // KHI HẾT GIỜ (distance < 0)
            if (distance < 0) {
                clearInterval(x);
                
                // 1. Ẩn đồng hồ
                timerDisplay.classList.add('hidden');
                
                // 2. Hiện thông báo đang xử lý
                msg.classList.remove('hidden');
                msg.innerHTML = '<i class="fas fa-sync fa-spin"></i> Đang xử lý kết quả đấu giá...';
                msg.classList.add("text-blue-600");

                // 3. TỰ ĐỘNG F5 (Chỉ chạy khi trạng thái chưa phải là Kết thúc)
                setTimeout(function() {
                    window.location.reload(); 
                }, 1500);
                
                return;
            }

            // Tính toán hiển thị thời gian
            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            document.getElementById("days").innerText = days < 10 ? "0" + days : days;
            document.getElementById("hours").innerText = hours < 10 ? "0" + hours : hours;
            document.getElementById("minutes").innerText = minutes < 10 ? "0" + minutes : minutes;
            document.getElementById("seconds").innerText = seconds < 10 ? "0" + seconds : seconds;
        }, 1000);
    });
    
    // Hàm hiển thị Toast Notification (Giữ nguyên)
    function showToast(message, type = 'info', duration = 3000) {
        const container = document.getElementById('toast-ui-container');
        if (!container) return; 

        const toast = document.createElement('div');
        toast.classList.add('toast-ui-message', `toast-ui-${type}`);

        let iconClass = 'fa-info-circle';
        switch(type) {
            case 'success': iconClass = 'fa-check-circle'; break;
            case 'error':   iconClass = 'fa-times-circle'; break;
            case 'warning': iconClass = 'fa-exclamation-triangle'; break;
        }

        toast.innerHTML = `<i class="fas ${iconClass} text-lg"></i> <span>${message}</span>`;
        container.appendChild(toast);

        requestAnimationFrame(() => {
            toast.classList.add('show');
        });

        const hideTimeout = setTimeout(() => {
            hideToast(toast);
        }, duration);

        toast.addEventListener('click', () => {
            clearTimeout(hideTimeout);
            hideToast(toast);
        });

        function hideToast(el) {
            el.classList.remove('show');
            el.addEventListener('transitionend', () => el.remove());
        }
    }
</script>

</body>
</html>