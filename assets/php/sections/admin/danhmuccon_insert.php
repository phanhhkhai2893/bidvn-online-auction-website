<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Untitled Document</title>
</head>

<body>
<!--	==============================	FORM INSERT	 ==============================		-->
			<div class="update-form-container">
				<h2>Thêm danh mục con mới</h2>
				<form method="post" id="userUpdateForm">

					<div class="form-group">
						<label for="MaDMCon">Mã danh mục con</label>
						<input type="text" id="MaDMCon" name="MaDMCon" value="<?PHP echo $madmc; ?>" readonly>
					</div>

					<div class="form-group">
						<label for="tendmc">Tên danh mục con</label>
						<input type="text" id="tendmc" name="tendmc">
					</div>

					<div class="form-group">
						<label for="mota">Mô tả</label>
						<input type="text" id="mota" name="mota">
					</div>

					<div class="form-group">
						<label for="hinhanh">Hình ảnh</label>
						<input type="text" id="hinhanh" name="hinhanh">
					</div>

					<div class="form-group">
						<label for="MaDM">Danh mục</label>
						<select id="MaDM" name="MaDM">
							<?PHP
								$query = "SELECT * FROM danh_muc GROUP BY MaDM";
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
					
					<div class="form-actions">
						<input href="" name="save-dmc-create" type="submit" class="btn update-btn-primary" value="Thêm sản phẩm"/>
						<a href="admin_index.php" class="btn update-btn-secondary" >Hủy</a>
					</div>
				</form>
			</div>
	<?PHP
	
//		==============================	HÀM INSERT	 ==============================
		if (isset($_POST['save-dmc-create']))
		{
			$tendmc_safe             = mysqli_real_escape_string($kn->con, $_POST['tendmc']);
			$mota_safe              = mysqli_real_escape_string($kn->con, $_POST['mota']);
			$hinhanh_safe              = mysqli_real_escape_string($kn->con, $_POST['hinhanh']);
			$MaDM_safe              = mysqli_real_escape_string($kn->con, $_POST['MaDM']);

			$query = "INSERT INTO `danh_muc_con` (
				`MaDMCon`, 
				`tendm`, 
				`mota`,
				`hinhanh`,
				`MaDM`
			) VALUES (
				'$madmc', 
				N'$tendmc_safe', 
				N'$mota_safe', 
				N'$hinhanh_safe', 
				N'$MaDM_safe'
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