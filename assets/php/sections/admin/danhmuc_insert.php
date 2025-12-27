<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Untitled Document</title>
</head>

<body>
<!--	==============================	FORM INSERT	 ==============================		-->
			<div class="update-form-container">
				<h2>Thêm danh mục mới</h2>
				<form method="post" id="userUpdateForm">

					<div class="form-group">
						<label for="MaDM">Mã danh mục</label>
						<input type="text" id="MaDM" name="MaDM" value="<?PHP echo $madm; ?>" readonly>
					</div>

					<div class="form-group">
						<label for="tendm">Tên danh mục</label>
						<input type="text" id="tendm" name="tendm">
					</div>

					<div class="form-group">
						<label for="mota">Mô tả</label>
						<input type="text" id="mota" name="mota">
					</div>
					
					<div class="form-actions">
						<input href="" name="save-dm-create" type="submit" class="btn update-btn-primary" value="Thêm sản phẩm"/>
						<a href="admin_index.php" class="btn update-btn-secondary" >Hủy</a>
					</div>
				</form>
			</div>
	<?PHP
	
//		==============================	HÀM INSERT	 ==============================
		if (isset($_POST['save-dm-create']))
		{
			$tendm_safe             = mysqli_real_escape_string($kn->con, $_POST['tendm']);
			$mota_safe              = mysqli_real_escape_string($kn->con, $_POST['mota']);

			$query = "INSERT INTO `danh_muc` (
				`MaDM`, 
				`tendm`, 
				`mota`
			) VALUES (
				'$madm', 
				N'$tendm_safe', 
				N'$mota_safe'
			)";
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