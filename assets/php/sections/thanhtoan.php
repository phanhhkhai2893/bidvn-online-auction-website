<?php
// ----------------------------------------------------
// PHẦN 1: INIT & BẢO MẬT (FIXED DB CONNECTION)
// ----------------------------------------------------

// Khởi tạo các biến SESSION an toàn
$MaND_real = isset($_SESSION['mand']) ? $_SESSION['mand'] : null;
$tennd = isset($_SESSION['tennd']) ? $_SESSION['tennd'] : '';

// --- KIỂM TRA ĐĂNG NHẬP CHẶT CHẼ ---
//if (!$MaND_real) {
//    echo "<script>alert('Vui lòng đăng nhập để thanh toán!'); window.location.href='index.php';</script>";
//    exit();
//}

// 2. Lấy thông tin người dùng (SECURE SELECT)
$sql_user = "SELECT email, sdt, diachi FROM nguoi_dung WHERE MaND = ?";
$stmt_user = $kn->con->prepare($sql_user);
$stmt_user->bind_param("s", $MaND_real);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
$row_user = $result_user->fetch_assoc();
$stmt_user->close();

// 3. Lấy dữ liệu từ Giỏ Hàng (SECURE SELECT)
$sql_cart = "SELECT MaSP, thanhtien FROM gio_hang WHERE MaND = ?";
$stmt_cart = $kn->con->prepare($sql_cart);
$stmt_cart->bind_param("s", $MaND_real);
$stmt_cart->execute();
$result_cart = $stmt_cart->get_result();

$tong_tien_hien_thi = 0;
$so_luong_sp = 0;
$cart_items = []; 

while ($row_cart = $result_cart->fetch_assoc()) {
    $soluong_item = 1; 
    $tong_tien_hien_thi += $row_cart['thanhtien'];
    $so_luong_sp += $soluong_item;
    $cart_items[] = ['MaSP' => $row_cart['MaSP'], 'soluong' => $soluong_item, 'thanhtien' => $row_cart['thanhtien']];
}
$stmt_cart->close();

// Nếu giỏ hàng trống thì đá về trang chủ
if ($so_luong_sp == 0) {
    echo "<script>alert('Giỏ hàng trống! Hãy đấu giá sản phẩm trước.'); window.location.href='index.php';</script>";
    exit();
}

// -----------------------------------------------------------------
// --- 4. XỬ LÝ KHI NGƯỜI DÙNG NHẤN NÚT HOÀN TẤT ĐẶT HÀNG (POST) ---
// -----------------------------------------------------------------
if (isset($_POST['btn_thanhtoan'])) {
    
    // 1. Tạo Mã Hóa Đơn Tự Động (HD + Số)
    $query_hd = "SELECT SoHD FROM hoa_don ORDER BY CAST(SUBSTRING(SoHD, 3) AS UNSIGNED) DESC LIMIT 1";
    $result_hd = mysqli_query($kn->con, $query_hd); 
    
    if (mysqli_num_rows($result_hd) > 0) {
        $row_hd = mysqli_fetch_assoc($result_hd);
        $lastNumber = (int)substr($row_hd['SoHD'], 2); 
        $newNumber = $lastNumber + 1; 
    } else {
        $newNumber = 1; 
    }
    $newMaHD = 'HD' . $newNumber;

    // 2. Lấy dữ liệu từ Form
    $pt_thanhtoan = $_POST['payment_method'];
    $ghi_chu = $_POST['notes'];
    $dia_chi_giao = $_POST['address'] . ", " . $_POST['city'];
    $noidung = "Thanh toán đơn hàng " . $newMaHD . ". Ghi chú: " . $ghi_chu;
    
    date_default_timezone_set('Asia/Ho_Chi_Minh'); 
    $ngaylap = date('Y-m-d H:i:s');

    // 3. INSERT vào bảng HOA_DON (SECURE INSERT)
    $sql_insert_hd = "INSERT INTO hoa_don (SoHD, noidung, ngaylap, phuongthuctt, MaND) VALUES (?, ?, ?, ?, ?)";
    $stmt_insert_hd = $kn->con->prepare($sql_insert_hd);
    
    if ($stmt_insert_hd) {
        $stmt_insert_hd->bind_param("sssss", $newMaHD, $noidung, $ngaylap, $pt_thanhtoan, $MaND_real);
        $insert_success = $stmt_insert_hd->execute();
        $stmt_insert_hd->close();

        if ($insert_success) {
            
            // 4. INSERT vào bảng CHI_TIET_HOA_DON (SECURE INSERT LOOP)
            $sql_ct = "INSERT INTO chi_tiet_hoa_don (soluong, thanhtien, SoHD, MaSP) VALUES (?, ?, ?, ?)";
            $stmt_ct = $kn->con->prepare($sql_ct);
            
            if ($stmt_ct) {
                foreach ($cart_items as $item) {
                    $masp = $item['MaSP'];
                    $soluong = $item['soluong'];
                    $thanhtien = $item['thanhtien'];
                    
                    $stmt_ct->bind_param("dsss", $soluong, $thanhtien, $newMaHD, $masp);
                    $stmt_ct->execute(); 
                }
                $stmt_ct->close();
            } else {
                $_SESSION['checkout_error'] = "Lỗi chuẩn bị truy vấn chi tiết hóa đơn.";
                header("Location: thanhtoan_index.php"); exit();
            }

            // 5. Xóa giỏ hàng của user sau khi đã tạo hóa đơn xong (SECURE DELETE)
            $sql_del_cart = "DELETE FROM gio_hang WHERE MaND = ?";
            $stmt_del_cart = $kn->con->prepare($sql_del_cart);
            $stmt_del_cart->bind_param("s", $MaND_real);
            $stmt_del_cart->execute();
            $stmt_del_cart->close();

            // 6. Thông báo và chuyển trang (THÀNH CÔNG)
            echo "<script>
                    alert('Thanh toán thành công! Mã đơn: $newMaHD. Đang chuyển về trang chủ.');
                    window.location.href = 'index.php';
                  </script>";
            exit();
            
        } else {
            $_SESSION['checkout_error'] = "Lỗi khi chèn dữ liệu hóa đơn chính: " . $kn->con->error;
            header("Location: thanhtoan_index.php"); exit();
        }
    } else {
        $_SESSION['checkout_error'] = "Lỗi chuẩn bị truy vấn hóa đơn chính.";
        header("Location: thanhtoan_index.php"); exit();
    }
}
?>

<!doctype html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Thanh Toán - BIDVN</title>
<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    brand: {
                        DEFAULT: '#FBC02D',
                        dark: '#F9A825',
                        hover: '#CA8A04',
                    }
                }
            }
        }
    }
</script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="assets/css/style.css?v=<?php echo time(); ?>" />
<link rel="icon" href="assets/imgs/logo/bidvn.ico" type="image/x-icon">
</head>

<body class="bg-gray-100 font-sans antialiased">
    
    <div id="body" class="py-10">
        <div class="checkout-container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <form action="" method="POST" id="checkout-form" class="grid grid-cols-1 lg:grid-cols-5 gap-8">
                
                <div class="lg:col-span-3 space-y-6">
                    
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2"><i class="fas fa-user-circle mr-2 text-brand-DEFAULT"></i> Thông Tin Liên Hệ</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-group">
                                <label for="name" class="block text-sm font-semibold mb-1">Họ và Tên (*)</label>
                                <input type="text" id="name" name="name" value="<?PHP echo htmlspecialchars($tennd); ?>" required class="w-full p-3 border border-gray-300 rounded-lg focus:border-brand-DEFAULT focus:ring-brand-DEFAULT">
                            </div>
                            <div class="form-group">
                                <label for="email" class="block text-sm font-semibold mb-1">Email (*)</label>
                                <input type="email" id="email" name="email" value="<?PHP echo htmlspecialchars($row_user['email']); ?>" required class="w-full p-3 border border-gray-300 rounded-lg focus:border-brand-DEFAULT focus:ring-brand-DEFAULT">
                            </div>
                            <div class="form-group md:col-span-2">
                                <label for="phone" class="block text-sm font-semibold mb-1">Số Điện Thoại (*)</label>
                                <input type="tel" id="phone" name="phone" value="<?PHP echo htmlspecialchars($row_user['sdt']); ?>" required class="w-full p-3 border border-gray-300 rounded-lg focus:border-brand-DEFAULT focus:ring-brand-DEFAULT">
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2"><i class="fas fa-map-marker-alt mr-2 text-brand-DEFAULT"></i> Địa Chỉ Giao Hàng</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-group md:col-span-2">
                                <label for="address" class="block text-sm font-semibold mb-1">Địa chỉ chi tiết (*)</label>
                                <input type="text" id="address" name="address" value="<?PHP echo htmlspecialchars($row_user['diachi']); ?>" required class="w-full p-3 border border-gray-300 rounded-lg focus:border-brand-DEFAULT focus:ring-brand-DEFAULT"/>
                            </div>
                            <div class="form-group">
                                <label for="city" class="block text-sm font-semibold mb-1">Tỉnh/Thành phố (*)</label>
                                <select id="city" name="city" required class="w-full p-3 border border-gray-300 rounded-lg focus:border-brand-DEFAULT focus:ring-brand-DEFAULT">
                                    <option value="" selected>Chọn Tỉnh/Thành phố</option>
                                    <option value="HCM">TP. Hồ Chí Minh</option>
                                    <option value="HN">Hà Nội</option>
                                    <option value="DN">Đà Nẵng</option>
                                    <option value="Khác">Khác</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="notes" class="block text-sm font-semibold mb-1">Ghi chú (Tùy chọn)</label>
                                <input type="text" id="notes" name="notes" placeholder="Ví dụ: Giao ngoài giờ hành chính" class="w-full p-3 border border-gray-300 rounded-lg focus:border-brand-DEFAULT focus:ring-brand-DEFAULT">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-2 space-y-6">
                    
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2"><i class="fas fa-credit-card mr-2 text-brand-DEFAULT"></i> Phương Thức Thanh Toán</h2>
                        
                        <input type="hidden" name="payment_method" id="payment_method_input" value="COD">

                        <div class="space-y-3">
                            <div class="payment-option border border-brand-DEFAULT bg-yellow-50 shadow-sm p-3 rounded-lg flex items-center gap-3 cursor-pointer transition duration-150" data-value="COD">
                                <i class="fas fa-money-bill-wave text-brand-DEFAULT text-lg"></i>
                                <span class="font-semibold">Thanh toán khi nhận hàng (COD)</span>
                            </div>
                            
                            <div class="payment-option border border-gray-300 p-3 rounded-lg flex items-center gap-3 cursor-pointer transition duration-150" data-value="ChuyenKhoan">
                                <i class="fas fa-university text-gray-500 text-lg"></i>
                                <span>Chuyển khoản Ngân hàng</span>
                            </div>
                            
                            <div class="payment-option border border-gray-300 p-3 rounded-lg flex items-center gap-3 cursor-pointer transition duration-150" data-value="ViDienTu">
                                <i class="fas fa-wallet text-gray-500 text-lg"></i>
                                <span>Ví điện tử (Momo, ZaloPay...)</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-xl shadow-md p-6 sticky top-5">
                        <h2 class="text-xl font-bold mb-4 border-b pb-2"><i class="fas fa-receipt mr-2 text-brand-DEFAULT"></i> Tóm Tắt Đơn Hàng</h2>
                        
                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Tổng sản phẩm</span>
                                <span class="font-semibold"><?PHP echo $so_luong_sp; ?> món</span>
                            </div>

                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Tạm tính</span>
                                <span><?PHP echo number_format($tong_tien_hien_thi, 0, '', '.'); ?> đ</span>
                            </div>

                            <div class="flex justify-between text-sm font-semibold text-green-600">
                                <span class="text-gray-600">Phí vận chuyển</span>
                                <span>Miễn phí</span>
                            </div>
                        </div>
                        
                        <div class="flex justify-between items-center pt-4 border-t border-gray-300">
                            <span class="text-xl font-extrabold text-gray-800">Tổng Thanh Toán</span>
                            <span class="text-2xl font-extrabold text-red-600">
                                <?PHP echo number_format($tong_tien_hien_thi, 0, '', '.'); ?> ₫
                            </span>
                        </div>
                        
                        <p class="text-xs text-gray-500 text-center mt-3">Bằng việc nhấn nút, bạn đồng ý với các điều khoản của BIDVN.</p>
                        
                        <button type="submit" name="btn_thanhtoan" form="checkout-form" class="w-full p-4 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl text-lg transition-colors shadow-lg mt-4">
                            <i class="fas fa-check-circle mr-2"></i> HOÀN TẤT ĐẶT HÀNG
                        </button>
                    </div>
                </div>
            </form> 
        </div>
    </div>
    
    <?PHP include('assets/php/sections/footer.php') ?>
    
    <script>
        // JS xử lý chọn phương thức thanh toán
        document.addEventListener('DOMContentLoaded', function() {
            const paymentOptions = document.querySelectorAll('.payment-option');
            const paymentInput = document.getElementById('payment_method_input');
            
            // Hàm xử lý hover/click
            paymentOptions.forEach(option => {
                option.addEventListener('click', function() {
                    // Xóa style cũ khỏi tất cả
                    paymentOptions.forEach(o => {
                        o.classList.remove('border-brand-DEFAULT', 'bg-yellow-50', 'shadow-sm');
                        o.classList.add('border-gray-300');
                        // Reset icon color
                        const icon = o.querySelector('i');
                        if (icon) {
                             icon.classList.remove('text-brand-DEFAULT');
                             icon.classList.add('text-gray-500');
                        }
                    });
                    
                    // Áp dụng style cho lựa chọn mới
                    this.classList.remove('border-gray-300');
                    this.classList.add('border-brand-DEFAULT', 'bg-yellow-50', 'shadow-sm');
                    
                    // Update hidden input value
                    const value = this.getAttribute('data-value');
                    paymentInput.value = value;

                    // Update icon color for selected option
                    const icon = this.querySelector('i');
                    if (icon) {
                        icon.classList.remove('text-gray-500');
                        icon.classList.add('text-brand-DEFAULT');
                    }
                });
            });

            // Tự động kích hoạt style cho COD khi load trang
            const defaultSelected = document.querySelector('.payment-option[data-value="COD"]');
            if (defaultSelected) {
                defaultSelected.classList.remove('border-gray-300');
                defaultSelected.classList.add('border-brand-DEFAULT', 'bg-yellow-50', 'shadow-sm');
                const icon = defaultSelected.querySelector('i');
                if (icon) {
                    icon.classList.remove('text-gray-500');
                    icon.classList.add('text-brand-DEFAULT');
                }
            }
        });
    </script>
</body>
</html>