
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Untitled Document</title>
<link rel="stylesheet" href="../../../css/admin_style.css">
</head>

<body>
	<div class="update-form-container">
		<h2>Chỉnh sửa thông tin sản phẩm: <?PHP echo $row['tensp']; ?></h2>
		<form method="post" id="userUpdateForm">

			<div class="form-group">
				<label for="MaSP">Mã sản phẩm</label>
				<input type="text" id="MaSP" name="MaSP" value="<?PHP echo $row['MaSP']; ?>" readonly>
			</div>

			<div class="form-group">
				<label for="tensp">Tên sản phẩm</label>
				<input type="text" id="tensp" name="tensp" value="<?PHP echo $row['tensp']; ?>" >
			</div>

			<div class="form-group">
				<label for="mota">Mô tả</label>
				<input type="text" id="mota" name="mota" value="<?PHP echo $row['mota']; ?>" >
			</div>

			<div class="form-group">
				<label for="gia_khoidiem">Giá khởi điểm</label>
				<input type="text" id="gia_khoidiem" name="gia_khoidiem" value="<?PHP echo $row['gia_khoidiem']; ?>" >
			</div>

			<div class="form-group">
				<label for="gia_hientai">Giá hiện tại</label>
				<input type="text" id="gia_hientai" name="gia_hientai" value="<?PHP echo $row['gia_hientai']; ?>" >
			</div>

			<div class="form-group">
				<label for="buocgia">Bước giá</label>
				<input type="text" id="buocgia" name="buocgia" value="<?PHP echo $row['buocgia']; ?>" >
			</div>

			<div class="form-group">
				<label for="thoigian_batdau">Thời gian bắt đầu</label>
				<input type="text" id="thoigian_batdau" name="thoigian_batdau" value="<?PHP echo $row['thoigian_batdau']; ?>" >
			</div>

			<div class="form-group">
				<label for="thoigian_ketthuc">Thời gian kết thúc</label>
				<input type="text" id="thoigian_ketthuc" name="thoigian_ketthuc" value="<?PHP echo $row['thoigian_ketthuc']; ?>" >
			</div>

			<div class="form-group">
				<label for="trangthai">Trạng thái</label>
				<select id="trangthai" name="trangthai" >
					<option value="Đang đấu giá" <?PHP echo ($row['trangthai'] == 'Đang đấu giá') ? 'selected' : ''; ?>>Đang đấu giá</option>
					<option value="Chưa bắt đầu" <?PHP echo ($row['trangthai'] == 'Chưa bắt đầu') ? 'selected' : ''; ?>>Chưa bắt đầu</option>
				</select>
			</div>

			<div class="form-group">
				<label for="MaDSHA">Mã danh sách hình ảnh</label>
				<select id="MaDSHA" name="MaDSHA">
					<?PHP
						$query = "SELECT * FROM danh_sach_hinh_anh GROUP BY MaDSHA";
						$result = mysqli_query($kn -> con, $query)
						  or die("Lỗi DTB");
						while ($row1 = mysqli_fetch_array($result))
						{
					?>
							<option value="<?PHP echo $row1['MaDSHA']; ?>"><?PHP echo $row1['MaDSHA']; ?></option>
					<?PHP
						}
					?>
				</select>
			</div>

			<div class="form-group">
				<label for="MaND">Mã người dùng</label>
				<select id="MaND" name="MaND">
					<?PHP
						$query = "SELECT * FROM nguoi_dung";
						$result = mysqli_query($kn -> con, $query)
						  or die("Lỗi DTB");
						while ($row1 = mysqli_fetch_array($result))
						{
					?>
							<option value="<?PHP echo $row1['MaND']; ?>" <?PHP echo ($row1['MaND'] == $row['MaND']) ? 'selected' : ''; ?>><?PHP echo $row1['hoten']; ?></option>
					<?PHP
						}
					?>
				</select>
			</div>

			<div class="form-group">
				<label for="MaDM">Mã danh mục</label>
				<select id="MaDM" name="MaDM">
					<?PHP
						$query = "SELECT * FROM danh_muc";
						$result = mysqli_query($kn -> con, $query)
						  or die("Lỗi DTB");
						while ($row1 = mysqli_fetch_array($result))
						{
					?>
							<option value="<?PHP echo $row1['MaDM']; ?>"><?PHP echo $row1['tendm']; ?></option>
					<?PHP
						}
					?>
				</select>
			</div>

			<div class="form-group">
				<label for="MaDMCon">Mã danh mục con</label>
				<select id="MaDMCon" name="MaDMCon">
					<?PHP
						$query = "SELECT * FROM danh_muc_con";
						$result = mysqli_query($kn -> con, $query)
						  or die("Lỗi DTB");
						while ($row1 = mysqli_fetch_array($result))
						{
					?>
							<option value="<?PHP echo $row1['MaDMCon']; ?>"><?PHP echo $row1['tendm']; ?></option>
					<?PHP
						}
					?>
				</select>
			</div>

			<div class="form-actions">
				<input href="" name="save-sp-update" type="submit" class="btn update-btn-primary" value="Lưu sản phẩm"/>
				<a href="admin_index.php" type="button" class="btn update-btn-secondary" >Quay lại</a>
			</div>
		</form>
	</div>
	<?PHP
		if (isset($_POST['save-sp-update']))
		{
			$masp = mysqli_real_escape_string($kn->con, $row['MaSP']);
			$tensp_safe             = mysqli_real_escape_string($kn->con, $_POST['tensp']);
			$mota_safe              = mysqli_real_escape_string($kn->con, $_POST['mota']);
			$gia_khoidiem_safe      = mysqli_real_escape_string($kn->con, $_POST['gia_khoidiem']);
			$gia_hientai_safe       = mysqli_real_escape_string($kn->con, $_POST['gia_hientai']); 
			$buocgia_safe           = mysqli_real_escape_string($kn->con, $_POST['buocgia']);
			$thoigian_batdau_safe   = mysqli_real_escape_string($kn->con, $_POST['thoigian_batdau']); 
			$thoigian_ketthuc_safe  = mysqli_real_escape_string($kn->con, $_POST['thoigian_ketthuc']);
			$trangthai_safe         = mysqli_real_escape_string($kn->con, $_POST['trangthai']);
			$madsha_safe            = mysqli_real_escape_string($kn->con, $_POST['MaDSHA']); 
			$mand_safe              = mysqli_real_escape_string($kn->con, $_POST['MaND']);
			$madm_safe              = mysqli_real_escape_string($kn->con, $_POST['MaDM']);
			$madmcon_safe           = mysqli_real_escape_string($kn->con, $_POST['MaDMCon']);

			// 2. TẠO CÂU LỆNH INSERT

			// Giả định tên bảng là san_pham và cấu trúc cột tương ứng
			$query = "UPDATE `san_pham` SET
				`tensp`= '$tensp_safe', 
				`mota` = '$mota_safe', 
				`gia_khoidiem` = '$gia_khoidiem_safe', 
				`gia_hientai` = '$gia_hientai_safe', 
				`buocgia` = '$buocgia_safe', 
				`thoigian_batdau` = '$thoigian_batdau_safe', 
				`thoigian_ketthuc` = '$thoigian_ketthuc_safe', 
				`trangthai` = '$trangthai_safe',
				`MaDSHA` = '$madsha_safe', 
				`MaND` = '$mand_safe', 
				`MaDM` = '$madm_safe', 
				`MaDMCon` = '$madmcon_safe'
				WHERE `MaSP` = '$masp';
			";
//echo $query;
			$result = mysqli_query($kn->con, $query)
				or die("Lỗi DTB: " . mysqli_error($kn->con)); // Nên hiển thị lỗi chi tiết để debug

			if ($result) {
				// Chuyển hướng về trang chính sau khi thêm thành công
				echo "<meta http-equiv='refresh' content='0; url=admin_index.php' />";
			} else {
				// In ra lỗi chi tiết nếu không chuyển hướng được
				echo "Lỗi chèn dữ liệu: " . mysqli_error($kn->con);
			}
		}
	?>
</body>
</html>