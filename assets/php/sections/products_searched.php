<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kết quả tìm kiếm</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: '#FBC02D', // Vàng chủ đạo
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gray-100 font-sans antialiased text-gray-900">

    <div id="sanpham" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
        <div class="bg-white rounded-3xl shadow-[0_20px_60px_-15px_rgba(0,0,0,0.1)] border border-gray-100 md:p-10 p-6 relative overflow-hidden min-h-[500px]">
            
            <div class="absolute top-0 right-0 w-96 h-96 bg-yellow-50 rounded-full blur-3xl opacity-60 -mr-20 -mt-20 pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 w-80 h-80 bg-gray-50 rounded-full blur-3xl opacity-60 -ml-20 -mb-20 pointer-events-none"></div>

            <div class="relative z-10">

                <?PHP
                    $key = "";
                    $count = 0;
                    if (isset($_GET['keyword']) && $_GET['keyword'] != null) {
                        $key = $_GET['keyword'];
                        $search_term = "%" . $key . "%";
                        
                        // Cấu hình Query (Sử dụng Prepared Statement để bảo mật)
                        // LƯU Ý: Đã chỉnh lại tên bảng thành 'danh_muc' theo logic file cũ của bạn
                        $sql = "SELECT 
									sp.MaSP, 
									sp.tensp, 
									sp.mota, 
									sp.gia_hientai, 
									sp.hinhanh, 
									sp.trangthai, 
									sp.thoigian_ketthuc, 
									dm.tendm, 
									nd.hoten 
								FROM san_pham sp 
								JOIN danh_muc_con dm ON sp.MaDMCon = dm.MaDMCon 
								JOIN nguoi_dung nd ON sp.MaND = nd.MaND 
								WHERE 
									(
										sp.tensp LIKE ? 
										OR sp.mota LIKE ? 
										OR dm.tendm LIKE ?
									) 
									AND 
									(
										sp.trangthai = 'Đang đấu giá' 
										OR sp.trangthai = 'Sắp diễn ra'
									) 
								ORDER BY sp.thoigian_ketthuc ASC;";

                        $stmt = mysqli_prepare($kn->con, $sql);
                        
                        if (!$stmt) {
                             die("<div class='text-red-500'>Lỗi SQL: " . mysqli_error($kn->con) . "</div>");
                        }
                        
                        mysqli_stmt_bind_param($stmt, "sss", $search_term, $search_term, $search_term);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);
                        $count = mysqli_num_rows($result);
                    }
                ?>

                <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 pb-4 border-b border-dashed border-gray-200">
                    <div>
                        <span class="text-brand font-bold tracking-widest text-xs uppercase mb-1 block">
                            Kết quả tìm kiếm
                        </span>
                        <h3 class="text-3xl font-extrabold text-gray-900 flex items-center">
                            <i class="fas fa-search text-gray-400 mr-3 text-2xl"></i>
                            "<?php echo htmlspecialchars($key); ?>"
                        </h3>
                        <p class="text-sm text-gray-500 mt-2">Tìm thấy <span class="font-bold text-gray-800"><?php echo $count; ?></span> sản phẩm phù hợp</p>
                    </div>
                    
                    <a href="index.php" class="group flex items-center text-sm font-semibold text-gray-500 hover:text-brand transition-colors mt-4 md:mt-0 bg-gray-50 px-4 py-2 rounded-full hover:bg-yellow-50">
                        Quay lại trang chủ
                        <i class="fas fa-arrow-right ml-2 transform group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    <?PHP
                    if ($count == 0) {
                        echo '<div class="col-span-full flex flex-col items-center justify-center py-12 opacity-50">
                                <i class="fas fa-box-open text-6xl text-gray-300 mb-4"></i>
                                <p class="text-lg text-gray-500">Không tìm thấy sản phẩm nào.</p>
                              </div>';
                    } else {
                        while ($row = mysqli_fetch_array($result)) {
                            
                            // 1. XỬ LÝ ẢNH (Logic dò đuôi file)
                            $img_path = "assets/imgs/product/default_product.png";
                            if (!empty($row['hinhanh'])) {
                                // Kiểm tra nếu DB đã lưu đuôi file
                                if (strpos($row['hinhanh'], '.') !== false) {
                                    $check_path = "assets/imgs/product/" . $row['hinhanh'];
                                    if (file_exists($check_path)) $img_path = $check_path;
                                } else {
                                    // Nếu chưa có đuôi, dò tìm
                                    $exts = ['.jpg', '.png', '.jpeg', '.gif', '.webp'];
                                    foreach ($exts as $ext) {
                                        $check_path = "assets/imgs/product/" . $row['hinhanh'] . $ext;
                                        if (file_exists($check_path)) {
                                            $img_path = $check_path;
                                            break;
                                        }
                                    }
                                }
                            }

                            // 2. XỬ LÝ BADGE TRẠNG THÁI (Style mới)
                            $badge = "";
                            if ($row['trangthai'] == 'Sắp diễn ra') {
                                $badge = '<span class="absolute top-3 right-3 bg-blue-600 text-white text-[10px] font-bold px-2 py-1 rounded-md shadow-lg z-10 uppercase tracking-wide">Sắp diễn ra</span>';
                            } else {
                                // Mặc định là đang đấu giá
                                $badge = '<span class="absolute top-3 right-3 bg-gradient-to-r from-red-500 to-red-600 text-white text-[10px] font-bold px-2 py-1 rounded-md shadow-lg z-10 uppercase tracking-wide flex items-center"><span class="w-1.5 h-1.5 bg-white rounded-full animate-ping mr-1.5"></span>Đang đấu giá</span>';
                            }
                    ?>
                        <a href="sanpham_detail.php?masp=<?PHP echo $row['MaSP']; ?>" 
                           class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-[0_10px_40px_-10px_rgba(0,0,0,0.1)] hover:border-brand/30 transition-all duration-300 overflow-hidden flex flex-col h-full relative transform hover:-translate-y-1">
                            
                            <?php echo $badge; ?>
                            
                            <div class="h-52 w-full overflow-hidden bg-gray-50 relative flex items-center justify-center p-4">
                                <img class="w-full h-full object-contain transform group-hover:scale-105 transition-transform duration-500 mix-blend-multiply" 
                                     src="<?PHP echo $img_path; ?>" 
                                     alt="<?PHP echo $row['tensp']; ?>" />
                            </div>
                            
                            <div class="p-5 flex flex-col flex-grow">
                                <span class="text-[10px] font-semibold text-gray-400 uppercase mb-1"><?PHP echo $row['tendm']; ?></span>

                                <h3 class="text-lg font-bold text-gray-800 group-hover:text-brand transition-colors line-clamp-2 mb-2 leading-tight min-h-[3rem]" title="<?PHP echo $row['tensp']; ?>">
                                    <?PHP echo $row['tensp']; ?>
                                </h3>
                                
                                <p class="text-xs text-gray-500 mb-4 line-clamp-2 leading-relaxed h-9">
                                    <?PHP echo mb_substr($row['mota'], 0, 70, 'UTF-8') . '...'; ?>
                                </p>
                                
                                <div class="mt-auto pt-4 border-t border-dashed border-gray-100">
                                    <div class="flex justify-between items-end">
                                        <div>
                                            <p class="text-[10px] uppercase font-bold text-gray-400 mb-0.5">Giá hiện tại</p>
                                            <p class="text-xl font-extrabold text-red-600 font-mono">
                                                <?PHP echo number_format($row['gia_hientai'], 0, '', '.'); ?><span class="text-sm align-top">đ</span>
                                            </p>
                                        </div>
                                        <div class="w-8 h-8 rounded-full bg-brand/10 text-brand flex items-center justify-center group-hover:bg-brand group-hover:text-white transition-colors">
                                            <i class="fas fa-gavel text-sm"></i>
                                        </div>
                                    </div>
                                    <div class="mt-2 text-[10px] text-gray-400 flex items-center">
                                        <i class="fas fa-user-circle mr-1"></i> <?PHP echo $row['hoten']; ?>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <?PHP
                        } // End while
                    } // End else
                    ?>
                </div> </div>
        </div>
    </div>

</body>
</html>