<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sản phẩm</title>
    </head>

<body class="bg-gray-100 font-sans antialiased text-gray-900">

    <div id="sanpham" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-6 relative overflow-hidden">
            
            <div class="absolute top-0 right-0 w-96 h-96 bg-yellow-50 rounded-full blur-3xl opacity-60 -mr-20 -mt-20 pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 w-80 h-80 bg-gray-50 rounded-full blur-3xl opacity-60 -ml-20 -mb-20 pointer-events-none"></div>

            <div class="relative z-10">
                <?PHP
                  if (isset($_GET['madm']))
                  {
                    $madm = $_GET['madm'];

                    // 1. Lấy tên danh mục cha
                    $sql_cat = "SELECT * FROM danh_muc WHERE MaDM = ?";
                    $stmt_cat = $kn->con->prepare($sql_cat);
                    $stmt_cat->bind_param("s", $madm);
                    $stmt_cat->execute();
                    $res_cat = $stmt_cat->get_result();
                    $row_cat = $res_cat->fetch_assoc();
                    
                    if ($row_cat) {
                ?>
                    <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 pb-4 border-b border-dashed border-gray-200">
                        <div>
                            <span class="text-brand-DEFAULT font-bold tracking-widest text-xs uppercase mb-1 block">Danh mục</span>
                            <h3 class="text-2xl md:text-3xl font-extrabold text-gray-900 flex items-center">
                                <?php echo $row_cat['tendm']; ?>
                            </h3>
                        </div>
                        <div class="hidden md:block h-1 w-20 bg-brand-DEFAULT rounded-full"></div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        
                        <?PHP
                            // 2. Truy vấn sản phẩm
                            $sql_pro = "SELECT sp.*, nd.hoten 
                                        FROM san_pham sp
                                        INNER JOIN nguoi_dung nd ON sp.MaND = nd.MaND
                                        INNER JOIN danh_muc_con dmc ON sp.MaDMCon = dmc.MaDMCon
                                        WHERE dmc.MaDM = ?
                                		AND sp.trangthai IN ('Đang đấu giá', 'Sắp diễn ra')";
                            
                            $stmt_pro = $kn->con->prepare($sql_pro);
                            $stmt_pro->bind_param("s", $madm);
                            $stmt_pro->execute();
                            $result = $stmt_pro->get_result();

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc())
                                {
                                    // XỬ LÝ ẢNH
                                    $img_path = "assets/imgs/product/default_product.png";
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

                                    // XỬ LÝ BADGE
                                    $badge = "";
									if ($row['trangthai'] == 'Sắp diễn ra') {
										$badge = '<span class="absolute top-3 right-3 bg-blue-600 text-white text-[10px] font-bold px-2 py-1 rounded-md shadow-lg z-10 uppercase tracking-wide">Sắp diễn ra</span>';
									} else if ($row['trangthai'] == 'Đang đấu giá') {
										$badge = '<span class="absolute top-3 right-3 bg-gradient-to-r from-red-500 to-red-600 text-white text-[10px] font-bold px-2 py-1 rounded-md shadow-lg z-10 uppercase tracking-wide flex items-center"><span class="w-1.5 h-1.5 bg-white rounded-full animate-ping mr-1.5"></span>Đang đấu giá</span>';
									} else {
										$badge = '<span class="absolute top-3 right-3 bg-gradient-to-r from-red-500 to-red-600 text-white text-[10px] font-bold px-2 py-1 rounded-md shadow-lg z-10 uppercase tracking-wide flex items-center"><span class="w-1.5 h-1.5 bg-white rounded-full animate-ping mr-1.5"></span>Kết thúc</span>';
									}
                        ?>
                            <a href="sanpham_detail.php?masp=<?PHP echo $row['MaSP']; ?>" 
                               class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-[0_10px_40px_-10px_rgba(0,0,0,0.1)] hover:border-brand-DEFAULT/50 transition-all duration-300 overflow-hidden flex flex-col h-full relative transform hover:-translate-y-1">
                                
                                <?php echo $badge; ?>
                                
                                <div class="h-52 w-full overflow-hidden bg-gray-50 relative flex items-center justify-center p-4">
                                    <img src="<?PHP echo $img_path; ?>?v=<?php echo time(); ?>" 
                                         alt="<?PHP echo $row['tensp']; ?>" 
                                         class="w-full h-full object-contain transform group-hover:scale-105 transition-transform duration-500">
                                </div>
                                
                                <div class="p-5 flex-1 flex flex-col justify-between">
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-800 group-hover:text-brand-DEFAULT transition-colors line-clamp-2 mb-2 leading-tight min-h-[3.5rem]" title="<?PHP echo $row['tensp']; ?>">
                                            <?PHP echo $row['tensp']; ?>
                                        </h3>
                                        <p class="text-xs text-gray-500 mb-4 line-clamp-2 leading-relaxed h-8">
                                            <?PHP echo $row['mota']; ?>
                                        </p>
                                    </div>
                                    
                                    <div class="mt-auto pt-4 border-t border-dashed border-gray-100">
                                        <div class="flex justify-between items-end">
                                            <div>
                                                <p class="text-[10px] uppercase font-bold text-gray-400 mb-0.5">Giá cao nhất</p>
                                                <p class="text-xl font-extrabold text-red-600 font-mono">
                                                    <?PHP echo number_format($row['gia_hientai'], 0, '', '.'); ?><span class="text-sm align-top">đ</span>
                                                </p>
                                            </div>
                                            <div class="w-8 h-8 rounded-full bg-brand-light text-brand-dark flex items-center justify-center group-hover:bg-brand-DEFAULT group-hover:text-white transition-colors shadow-sm">
                                                <i class="fas fa-gavel text-sm"></i>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-3 flex items-center text-xs text-gray-400">
                                            <i class="fas fa-user-circle mr-1.5 text-gray-300 text-sm"></i>
                                            <span class="truncate max-w-[150px]"><?PHP echo $row['hoten']; ?></span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        <?PHP
                                } // End while
                            } else {
                                echo '<div class="col-span-full flex flex-col items-center justify-center py-16">
                                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4 text-gray-300 text-2xl">
                                            <i class="fas fa-box-open"></i>
                                        </div>
                                        <p class="text-gray-500 font-medium">Chưa có sản phẩm nào trong danh mục này.</p>
                                      </div>';
                            }
                        ?>
                    </div> <?php
                    } else {
                        echo '<div class="text-center py-20 text-red-500 font-bold text-xl">Danh mục không tồn tại!</div>';
                    }
                  }
                ?>
            </div>
        </div>
    </div>

</body>
</html>