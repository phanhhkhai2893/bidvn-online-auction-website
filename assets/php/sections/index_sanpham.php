<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sản phẩm đấu giá</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: '#FBC02D', // Vàng
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gray-100 font-sans antialiased text-gray-900">

    <div id="sanpham" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
        <div class="bg-white rounded-3xl shadow-[0_20px_60px_-15px_rgba(0,0,0,0.1)] border border-gray-100 md:p-10 relative overflow-hidden">
            
            <div class="absolute top-0 right-0 w-96 h-96 bg-yellow-50 rounded-full blur-3xl opacity-60 -mr-20 -mt-20 pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 w-80 h-80 bg-gray-50 rounded-full blur-3xl opacity-60 -ml-20 -mb-20 pointer-events-none"></div>

            <div class="relative z-10">

                <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 pb-4 border-b border-dashed border-gray-200">
                    <div>
                        <span class="text-brand font-bold tracking-widest text-xs uppercase mb-1 block">Cơ hội sở hữu</span>
                        <h3 class="text-3xl font-extrabold text-gray-900 flex items-center">
                            <i class="fas fa-fire text-red-500 mr-3 animate-pulse"></i>
                            Sản phẩm đang đấu giá
                        </h3>
                    </div>
                    <a href="#" class="group flex items-center text-sm font-semibold text-gray-500 hover:text-brand transition-colors mt-4 md:mt-0 bg-gray-50 px-4 py-2 rounded-full hover:bg-yellow-50">
                        Xem tất cả sản phẩm
                        <i class="fas fa-arrow-right ml-2 transform group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    <?PHP
                        // QUERY SQL
                        $query = "SELECT 
                                    sp.*, 
                                    nd.hoten 
                                FROM san_pham sp 
                                JOIN nguoi_dung nd ON sp.MaND = nd.MaND 
                                WHERE sp.trangthai IN ('Đang đấu giá', 'Sắp diễn ra')
                                ORDER BY sp.thoigian_ketthuc ASC";
                        
                        $result = mysqli_query($kn->con, $query) or die("Lỗi truy vấn: " . mysqli_error($kn->con));

                        while ($row = mysqli_fetch_array($result))
                        {
                            // XỬ LÝ ẢNH
                            $img_path = "assets/imgs/product/default_product.png"; // 1. Ảnh mặc định

							if (!empty($row['hinhanh'])) {
								// Kiểm tra xem tên trong DB đã có sẵn đuôi file chưa (vd: img_SP1.jpg)
								if (strpos($row['hinhanh'], '.') !== false) {
									// TRƯỜNG HỢP 1: DB Đã lưu cả đuôi (ví dụ: img_SP1.jpg)
									$check_path = "assets/imgs/product/" . $row['hinhanh'];
									if (file_exists($check_path)) {
										$img_path = $check_path;
									}
								} else {
									// TRƯỜNG HỢP 2: DB Chỉ lưu tên (ví dụ: img_SP1) -> Phải đi dò đuôi
									$allowed_extensions = ['.jpg', '.png', '.jpeg', '.gif', '.webp'];

									foreach ($allowed_extensions as $ext) {
										// Thử ghép tên với từng đuôi: assets/imgs/sanpham/img_SP1.jpg ...
										$check_path = "assets/imgs/product/" . $row['hinhanh'] . $ext;

										if (file_exists($check_path)) {
											$img_path = $check_path;
											break; // Tìm thấy file tồn tại thì lấy luôn và thoát vòng lặp
										}
									}
								}
							}

							// Lúc này biến $img_path đã sẵn sàng để echo vào thẻ <img>

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
                           class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-[0_10px_40px_-10px_rgba(0,0,0,0.1)] hover:border-brand/30 transition-all duration-300 overflow-hidden flex flex-col h-full relative transform hover:-translate-y-1">
                            
                            <?php echo $badge; ?>
                            
                            <div class="h-52 w-full overflow-hidden bg-gray-50 relative flex items-center justify-center">
                                <img class="w-full h-full object-contain transform group-hover:scale-105 transition-transform duration-500" 
                                     src="<?PHP echo $img_path; ?>?v=<?php echo time(); ?>" 
                                     alt="<?PHP echo $row['tensp']; ?>" />
                            </div>
                            
                            <div class="p-5 flex flex-col flex-grow">
                                <h3 class="text-lg font-bold text-gray-800 group-hover:text-brand transition-colors line-clamp-2 mb-2 leading-tight min-h-[3rem]" title="<?PHP echo $row['tensp']; ?>">
                                    <?PHP echo $row['tensp']; ?>
                                </h3>
                                
                                <p class="text-xs text-gray-500 mb-4 line-clamp-2 leading-relaxed">
                                    <?PHP echo $row['mota']; ?>
                                </p>
                                
                                <div class="mt-auto pt-4 border-t border-dashed border-gray-100">
                                    <div class="flex justify-between items-end">
                                        <div>
                                            <p class="text-[10px] uppercase font-bold text-gray-400 mb-0.5">Giá cao nhất</p>
                                            <p class="text-xl font-extrabold text-red-600 font-mono">
                                                <?PHP echo number_format($row['gia_hientai'], 0, '', '.'); ?><span class="text-sm align-top">đ</span>
                                            </p>
                                        </div>
                                        <div class="w-8 h-8 rounded-full bg-brand/10 text-brand flex items-center justify-center group-hover:bg-brand group-hover:text-white transition-colors">
                                            <i class="fas fa-gavel text-sm"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            </a>
                    <?PHP
                        }
                    ?>
                </div>

            </div>
        </div>

    </div>

</body>
</html>