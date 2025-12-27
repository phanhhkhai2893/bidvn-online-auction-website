<?php
// ----------------------------------------------------
// PHẦN 1: INIT & XỬ LÝ POST
// ----------------------------------------------------
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Giả sử $kn đã được định nghĩa ở file cha hoặc bạn đã include DB connection.
include('assets/php/database/db_connection.php');
$kn = new db_connection();

// Khởi tạo các biến SESSION an toàn
$MaND_real = isset($_SESSION['mand']) ? $_SESSION['mand'] : null;

// --- XỬ LÝ XÓA SẢN PHẨM KHỎI GIỎ HÀNG (POST) ---
if (isset($_POST['xoagh'])) {
    $maspGH = $_POST['xoagh']; 

    $queryXoaGH = "DELETE FROM gio_hang WHERE MaND = ? AND MaSP = ?";
    $stmt_delete = $kn->con->prepare($queryXoaGH);
    
    if ($stmt_delete) {
        $stmt_delete->bind_param("ss", $MaND_real, $maspGH); 
        $stmt_delete->execute();
        $stmt_delete->close();

        echo "<meta http-equiv='refresh' content='0; url=giohang.php' />";
        exit();
    } else {
        $error_message = "Lỗi chuẩn bị truy vấn SQL: " . $kn->con->error;
        echo "<script>alert('$error_message');</script>";
    }
}
?>
<!doctype html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Giỏ Hàng - BIDVN</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="assets/css/style.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-100 font-sans antialiased">
  <?PHP include('assets/php/sections/header.php') ?>
    
  <div id="body" class="py-10 mb-10">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8"> 
        <div class="bg-white rounded-xl shadow-lg border border-gray-200">
            <header class="p-6 border-b border-gray-200">
                <h1 class="text-3xl font-extrabold text-gray-800 flex items-center gap-3">
                    <i class="fas fa-shopping-cart text-brand-DEFAULT"></i> Giỏ Hàng
                </h1>
            </header>

            <div class="p-6">
                <div class="md:flex text-sm font-semibold text-gray-500 border-b pb-3 mb-4">
					<div class="w-[50%] uppercase tracking-wider pl-1">SẢN PHẨM THẮNG CUỘC</div>

					<div class="w-[20%] text-center uppercase tracking-wider">GIÁ ĐẤU</div>

					<div class="w-[20%] text-right uppercase tracking-wider pr-4">THÀNH TIỀN</div>

					<div class="w-[10%] text-center"></div>
				</div>
            
                <?PHP
                // --- 2. TRUY VẤN DANH SÁCH SẢN PHẨM ---
                if ($MaND_real) {
                    $query = "
                        SELECT
                            gh.MaSP,
                            sp.tensp,
                            sp.hinhanh,
                            dmc.tendm,
                            gh.thanhtien,
                            1 AS soluong, 
                            gh.thanhtien AS tongtien 
                        FROM
                            gio_hang gh
                        INNER JOIN 
                            san_pham sp ON gh.MaSP = sp.MaSP
                        LEFT JOIN 
                            danh_muc_con dmc ON sp.MaDMCon = dmc.MaDMCon
                        WHERE
                            gh.MaND = ?
                    ";
                    
                    $stmt_select = $kn->con->prepare($query);
                    if (!$stmt_select) { die("Lỗi chuẩn bị truy vấn SQL: " . $kn->con->error); }
                    
                    $stmt_select->bind_param("s", $MaND_real);
                    $execute_success = $stmt_select->execute();
                    if (!$execute_success) { die("Lỗi thực thi truy vấn: " . $stmt_select->error); }

                    $result = $stmt_select->get_result();

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc())
                        {
                            // --- XỬ LÝ ẢNH (Logic dò đuôi file) ---
                            $img_path = "assets/imgs/product/default_product.png";
                            if (!empty($row['hinhanh'])) {
                                $exts = ['.jpg', '.png', '.jpeg', '.webp'];
                                foreach ($exts as $ext) {
                                    $check_path = "assets/imgs/product/" . $row['hinhanh'] . $ext;
                                    if (file_exists($check_path)) {
                                        $img_path = $check_path;
                                        break;
                                    }
                                }
                            }
                ?>
                    <div data-maspGH="<?PHP echo $row['MaSP'] ?>" class="flex flex-wrap md:flex-nowrap items-center border-b border-gray-100 py-4 last:border-b-0 hover:bg-gray-50 transition-colors">

						<div class="w-full md:w-[50%] flex items-center p-2">
							<img src="<?PHP echo $img_path ?>" alt="<?PHP echo $row['tensp'] ?>" class="w-16 h-16 object-cover rounded-md mr-4 border border-gray-200">
							<div class="space-y-1">
								<p class="text-base font-semibold text-gray-900"><?PHP echo $row['tensp'] ?></p>
								<p class="text-xs text-gray-500">Danh mục: <?PHP echo $row['tendm'] ?></p>
							</div>
						</div>

						<div class="w-1/4 md:w-[20%] text-center text-sm font-medium text-gray-800">
							<?PHP echo number_format($row['thanhtien'], 0, '', '.') . "₫"; ?>
						</div>

						<div class="w-1/4 md:w-[20%] text-right font-bold text-red-600 pr-4">
							<?PHP echo number_format($row['tongtien'], 0, '', '.') . "₫" ?>
						</div>

						<form method="post" class="w-1/4 md:w-[10%] text-center">
							<input type="hidden" name="xoagh" value="<?PHP echo $row['MaSP']; ?>">
							<button type="submit" class="text-gray-400 hover:text-red-600 transition-colors">
								<i class="fas fa-trash-alt"></i>
							</button>
						</form>
					</div>
                <?PHP
                        }
                        $stmt_select->close();
                    } else {
                        echo '<div class="text-center py-10 text-gray-500 font-semibold">🛒 Giỏ hàng của bạn đang trống! Hãy tham gia đấu giá.</div>';
                    }
                } else {
                    echo '<div class="text-center py-10 text-brand-DEFAULT font-bold">Vui lòng đăng nhập để xem giỏ hàng.</div>';
                }
                ?>
            </div> <div class="bg-gray-50 p-6 rounded-b-xl border-t border-gray-200 flex justify-between items-center sticky bottom-0">
                <?PHP
                    // --- 3. TRUY VẤN TÓM TẮT ---
                    $query_summary = "SELECT 
                                        COUNT(MaSP) AS tongsp,
                                        SUM(thanhtien) AS tt
                                      FROM gio_hang WHERE MaND = ?";
                    
                    $stmt_summary = $kn->con->prepare($query_summary);
                    if ($MaND_real && $stmt_summary) {
                        $stmt_summary->bind_param("s", $MaND_real);
                        $stmt_summary->execute();
                        $result_summary = $stmt_summary->get_result();
                        $row_summary = $result_summary->fetch_assoc();
                        $stmt_summary->close();

                        $tong_sp = $row_summary['tongsp'];
                        $tong_tien = $row_summary['tt'];
                    } else {
                        $tong_sp = 0;
                        $tong_tien = 0;
                    }
                ?>
                <div class="text-sm font-semibold text-gray-600">
                    Tổng cộng (<span class="text-gray-800 font-bold"><?PHP echo $tong_sp ?></span> sản phẩm):
                </div>

                <div class="flex items-center space-x-4">
                    <span class="text-2xl font-extrabold text-red-600">
                        <?PHP echo number_format($tong_tien, 0, '', '.') . "₫" ?>
                    </span>
                    <a href="thanhtoan_index.php?tt=<?PHP echo $tong_tien; ?>" 
                       class="bg-[#ffc900] hover:opacity-80 text-gray-900 font-bold px-6 py-3 rounded-lg shadow-md transition transform hover:-translate-y-0.5">
                        MUA HÀNG
                    </a>
                </div>
            </div>

        </div>
    </div>
	</div>
  <?PHP include('assets/php/sections/footer.php') ?>
  <div id="toast-ui-container"></div>
</body>
</html>