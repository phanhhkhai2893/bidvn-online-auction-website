<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Thêm sản phẩm mới</title>
</head>

<body>
    <?PHP
        // Xử lý khi form được submit
        if (isset($_POST['save-sp-create']))
        {
            // 1. Lấy dữ liệu Text
            $masp_post           = $_POST['MaSP'];
            $tensp               = $_POST['tensp'];
            $mota                = $_POST['mota'];
            $gia_khoidiem        = $_POST['gia_khoidiem'];
            $gia_hientai         = $_POST['gia_hientai']; // Thường giá hiện tại = giá khởi điểm lúc tạo
            $buocgia             = $_POST['buocgia'];
            
            // Xử lý datetime: HTML5 input trả về "2023-11-25T14:30", MySQL cần "2023-11-25 14:30:00"
            $thoigian_batdau     = str_replace("T", " ", $_POST['thoigian_batdau']) . ":00";
            $thoigian_ketthuc    = str_replace("T", " ", $_POST['thoigian_ketthuc']) . ":00";
            
            $trangthai           = $_POST['trangthai'];
            $mand                = $_POST['MaND'];
            $madmcon             = $_POST['MaDMCon'];
            
            // 2. Xử lý UPLOAD ẢNH (Quan trọng)
            $hinhanh_name = "default_product"; // Mặc định rỗng
            
            if (isset($_FILES['hinhanh_upload']) && $_FILES['hinhanh_upload']['error'] == 0) {
                $target_dir = "assets/imgs/product/"; // Thư mục lưu ảnh
                
                // Lấy đuôi file (jpg, png...)
                $imageFileType = strtolower(pathinfo($_FILES["hinhanh_upload"]["name"], PATHINFO_EXTENSION));
                
                // Đặt tên file mới ngắn gọn để vừa varchar(30)
                // Ví dụ: SP123456.jpg
                $new_filename = $masp_post . "." . $imageFileType;
                $target_file = $target_dir . $new_filename;

                // Kiểm tra đuôi file hợp lệ
                $allowed = array("jpg", "jpeg", "png", "gif", "webp");
                if(in_array($imageFileType, $allowed)) {
                    if (move_uploaded_file($_FILES["hinhanh_upload"]["tmp_name"], $target_file)) {
                        $hinhanh_name = $new_filename; // Lưu tên file vào biến để insert DB
                    } else {
                        echo "<script>alert('Lỗi: Không thể upload file ảnh.');</script>";
                    }
                } else {
                    echo "<script>alert('Lỗi: Chỉ chấp nhận file ảnh (JPG, PNG, GIF).');</script>";
                }
            }

            // 3. INSERT DATABASE
            // Cấu trúc bảng: MaSP, tensp, mota, gia_khoidiem, gia_hientai, buocgia, thoigian_batdau, thoigian_ketthuc, trangthai, hinhanh, MaND, MaDMCon
            $sql = "INSERT INTO san_pham (
                        MaSP, tensp, mota, 
                        gia_khoidiem, gia_hientai, buocgia, 
                        thoigian_batdau, thoigian_ketthuc, 
                        trangthai, hinhanh, MaND, MaDMCon
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = mysqli_prepare($kn->con, $sql);

            if ($stmt) {
                // Bind tham số: 
                // s (string): MaSP, tensp, mota, dates, trangthai, hinhanh, MaND, MaDMCon
                // d (double/float): gia_khoidiem, gia_hientai, buocgia
                // Thứ tự: s s s d d d s s s s s s
                mysqli_stmt_bind_param($stmt, "sssdddssssss", 
                    $masp_post, 
                    $tensp, 
                    $mota, 
                    $gia_khoidiem, 
                    $gia_hientai, 
                    $buocgia, 
                    $thoigian_batdau, 
                    $thoigian_ketthuc, 
                    $trangthai,
                    $hinhanh_name, // Tên file ảnh (ví dụ: SP123456.jpg)
                    $mand, 
                    $madmcon
                );

                if (mysqli_stmt_execute($stmt)) {
                    echo "<script>alert('Thêm sản phẩm thành công!'); window.location.href='admin_index.php';</script>";
                } else {
                    echo "Lỗi thực thi: " . mysqli_error($kn->con);
                }
                mysqli_stmt_close($stmt);
            } else {
                echo "Lỗi Prepare SQL: " . mysqli_error($kn->con);
            }
        }
    ?>

<div class="update-form-container">
        <h2>Thêm sản phẩm đấu giá</h2>
        
        <form method="post" id="userUpdateForm" enctype="multipart/form-data">

            <div class="form-group">
                <label for="MaSP">Mã sản phẩm (Tự động)</label>
                <input type="text" id="MaSP" name="MaSP" value="<?PHP echo $masp; ?>" readonly style="background-color: #eee; cursor: not-allowed;">
            </div>

            <div class="form-group">
                <label for="tensp">Tên sản phẩm</label>
                <input type="text" id="tensp" name="tensp" required maxlength="30">
            </div>
            
            <div class="form-group">
                <label for="hinhanh_upload">Hình ảnh sản phẩm</label>
                <input type="file" id="hinhanh_upload" name="hinhanh_upload" accept="image/*" required>
                <small style="color: gray;">Chọn ảnh JPG, PNG, WEBP...</small>
            </div> 

            <div class="form-group">
                <label for="mota">Mô tả chi tiết</label>
                <textarea id="mota" name="mota" rows="3" style="width:100%;" maxlength="1000"></textarea>
            </div>

            <div class="form-group">
                <label for="gia_khoidiem">Giá khởi điểm</label>
                <input type="number" id="gia_khoidiem" name="gia_khoidiem" required min="0">
            </div>

            <div class="form-group">
                <label for="gia_hientai">Giá hiện tại (Thường bằng giá KĐ)</label>
                <input type="number" id="gia_hientai" name="gia_hientai" required min="0">
            </div>

            <div class="form-group">
                <label for="buocgia">Bước giá (VNĐ)</label>
                <input type="number" id="buocgia" name="buocgia" required min="1000">
            </div>

            <div class="form-group">
                <label for="thoigian_batdau">Thời gian bắt đầu</label>
                <input type="datetime-local" id="thoigian_batdau" name="thoigian_batdau" required>
            </div>

            <div class="form-group">
                <label for="thoigian_ketthuc">Thời gian kết thúc</label>
                <input type="datetime-local" id="thoigian_ketthuc" name="thoigian_ketthuc" required>
            </div>

            <div class="form-group">
                <label for="trangthai">Trạng thái</label>
                <select id="trangthai" name="trangthai">
                    <option value="Sắp diễn ra" selected>Sắp diễn ra</option>
                    <option value="Đang đấu giá">Đang đấu giá</option>
                </select>
            </div>

            <div class="form-group">
                <label for="MaND">Người đăng bán (Admin/User)</label>
                <select id="MaND" name="MaND">
                    <?PHP
                        // Lấy danh sách người dùng để gắn chủ sở hữu
                        $query = "SELECT MaND, hoten FROM nguoi_dung";
                        $result = mysqli_query($kn -> con, $query);
                        if($result) {
                            while ($row1 = mysqli_fetch_array($result)) {
                                echo '<option value="'.$row1['MaND'].'">'.$row1['hoten'].' ('.$row1['MaND'].')</option>';
                            }
                        }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="MaDMCon">Danh mục sản phẩm</label>
                <select id="MaDMCon" name="MaDMCon">
                    <?PHP
                        // Lấy danh mục con
                        $query = "SELECT MaDMCon, tendm FROM danh_muc_con";
                        $result = mysqli_query($kn -> con, $query);
                        if($result) {
                            while ($row1 = mysqli_fetch_array($result)) {
                                echo '<option value="'.$row1['MaDMCon'].'">'.$row1['tendm'].'</option>';
                            }
                        }
                    ?>
                </select>
            </div>

            <div class="form-actions" style="margin-top: 20px;">
                <input name="save-sp-create" type="submit" class="btn update-btn-primary" value="Thêm sản phẩm" style="padding: 10px 20px; background: blue; color: white; border: none; cursor: pointer;"/>
                <a href="admin_index.php" class="btn update-btn-secondary" style="margin-left: 10px; text-decoration: none; color: black;">Hủy</a>
            </div>
        </form>
    </div>
</body>
</html>