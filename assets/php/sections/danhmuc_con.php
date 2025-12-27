<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Danh mục con</title>
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    brand: {
                        light: '#FFF9C4', // yellow-100
                        DEFAULT: '#FBC02D', // Màu vàng đậm như mẫu
                        dark: '#F9A825',  // yellow-800
                    }
                }
            }
        }
    }
</script>
</head>

<body class="bg-[#FFFDE7]"> <div id="danhmuc" class="py-5">
        <div class="container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="bg-white rounded-2xl shadow-xl border border-white/50 p-6 md:p-10 relative overflow-hidden">
                
                <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-yellow-100 rounded-full blur-3xl opacity-50"></div>
                <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-40 h-40 bg-blue-50 rounded-full blur-3xl opacity-50"></div>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-y-8 gap-x-4 relative z-10">
                    <?PHP
                    if (isset($_GET['madm']))
                    {
                        $madm = $_GET['madm'];
                        
                        // Truy vấn lấy danh mục con
                        $sql = "SELECT * FROM danh_muc_con WHERE MaDM = ?";
                        
                        if ($kn && $kn->con) {
                            $stmt = $kn->con->prepare($sql);
                            if ($stmt) {
                                $stmt->bind_param("s", $madm);
                                $stmt->execute();
                                $result = $stmt->get_result();

                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc())
                                    {
                                        $ma = $row['MaDMCon'];
                                        
                                        // --- XỬ LÝ ẢNH (Logic cũ nhưng áp dụng cho giao diện mới) ---
                                        $img_src = "assets/imgs/sub-category/default.png"; 
                                        $allowed_extensions = ['.jpg', '.png', '.jpeg', '.gif', '.webp'];

                                        if (!empty($ma)) {
                                            foreach ($allowed_extensions as $ext) {
                                                // Nếu tên file là "cat_DMC..." thì dùng dòng dưới
                                                // $check_path = "assets/imgs/sub-category/cat_" . $ma . $ext;
                                                
                                                // Nếu tên file là "DMC..." thì dùng dòng dưới
                                                $check_path = "assets/imgs/sub-category/" . $ma . $ext;
                                                
                                                if (file_exists($check_path)) {
                                                    $img_src = $check_path;
                                                    break; 
                                                }
                                            }
                                        }
                    ?>
                        <a href="sanpham_danhmuccon.php?madm=<?PHP echo $ma; ?>" 
                           class="group flex flex-col items-center justify-start cursor-pointer transition-all duration-300 hover:-translate-y-1">
                            
                            <div class="w-24 h-24 overflow-hidden rounded-full bg-white border border-gray-100 flex items-center justify-center mb-4 shadow-sm group-hover:shadow-md group-hover:border-brand-DEFAULT group-hover:bg-yellow-50 transition-all duration-300">
                                <img class="h-16 w-16 object-contain p-1 transition-transform duration-500 group-hover:scale-110" 
                                     src="<?PHP echo $img_src; ?>" 
                                     alt="<?PHP echo $row['tendm']; ?>" />
                            </div>
                            
                            <p class="text-sm font-semibold text-gray-600 text-center px-2 group-hover:text-brand-DEFAULT transition-colors duration-300 line-clamp-2">
                                <?PHP echo $row['tendm']; ?>
                            </p>
                        </a>
                    <?PHP
                                    } // End While
                                } else {
                                    echo '<div class="col-span-full text-center text-gray-400 py-10 italic">Chưa có danh mục con nào.</div>';
                                }
                                $stmt->close();
                            }
                        }
                    }
                    ?>
                </div> </div> </div>
    </div>
</body>
</html>