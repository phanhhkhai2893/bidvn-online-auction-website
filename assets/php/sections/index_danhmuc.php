<!doctype html>
<html lang="vi">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Danh mục sản phẩm</title>
<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    brand: '#FBC02D', // Màu vàng thương hiệu
                }
            }
        }
    }
</script>
</head>

<body class="bg-[#FFFDE7]"> 

    <div id="danhmuc" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        <div class="bg-white rounded-2xl shadow-xl border border-white/50 p-6 md:p-10 relative overflow-hidden">
            
            <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-yellow-100 rounded-full blur-3xl opacity-50"></div>
            <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-40 h-40 bg-blue-50 rounded-full blur-3xl opacity-50"></div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-y-8 gap-x-4 relative z-10">
                <?PHP
                    // Query sắp xếp: Khác nằm cuối
                    $query = "SELECT * FROM danh_muc ORDER BY (tendm = 'Khác'), MaDM;";
                    $result = mysqli_query($kn->con, $query) or die("Lỗi DTB");

                    while ($row = mysqli_fetch_array($result)) {
                        $ma = $row['MaDM'];

                        // Xử lý ảnh (Giữ nguyên logic của bạn)
                        $folder = "assets/imgs/category/";
                        $path_jpg = $folder . $ma . ".jpg";
                        $path_png = $folder . $ma . ".png";

                        if (file_exists($path_jpg)) { $hinh_anh = $path_jpg; } 
                        elseif (file_exists($path_png)) { $hinh_anh = $path_png; } 
                        else { $hinh_anh = $folder . "default.png"; }
                ?>
                    <a href="sanpham_danhmuc.php?madm=<?PHP echo $ma; ?>" 
                       class="group flex flex-col items-center justify-start cursor-pointer transition-all duration-300 hover:-translate-y-1">
                        
                        <div class="w-24 h-24 overflow-hidden rounded-full bg-white border border-gray-100 flex items-center justify-center mb-4 shadow-sm group-hover:shadow-md group-hover:border-brand group-hover:bg-yellow-50 transition-all duration-300">
                            <img class="w-13 object-contain p-3 transition-transform duration-500 group-hover:scale-110" 
                                 src="<?PHP echo $hinh_anh; ?>?v=<?php echo time(); ?>" 
                                 alt="<?PHP echo $row['tendm']; ?>" />
                        </div>
                        
                        <p class="text-sm font-semibold text-gray-600 text-center px-2 group-hover:text-brand transition-colors duration-300 line-clamp-2">
                            <?PHP echo $row['tendm']; ?>
                        </p>
                    </a>
                <?PHP
                    }
                ?>
            </div>
            
        </div>
        </div>

</body>
</html>