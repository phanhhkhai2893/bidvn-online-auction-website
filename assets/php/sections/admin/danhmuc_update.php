
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Untitled Document</title>
<link rel="stylesheet" href="../../../css/admin_style.css">
</head>

<body>
	<div class="update-form-container">
		<h2>Chỉnh sửa thông tin danh mục: <?PHP echo $row['tensp']; ?></h2>
		<form method="post" id="userUpdateForm">

			<div class="form-group">
				<label for="MaDM">Mã danh mục</label>
				<input type="text" id="MaDM" name="MaDM" value="<?PHP echo $row['MaDM']; ?>" readonly>
			</div>

			<div class="form-group">
				<label for="tendm">Tên danh mục</label>
				<input type="text" id="tendm" name="tendm" value="<?PHP echo $row['tendm']; ?>" >
			</div>

			<div class="form-group">
				<label for="mota">Mô tả</label>
				<input type="text" id="mota" name="mota" value="<?PHP echo $row['mota']; ?>" >
			</div>
			
			<div class="form-actions">
				<input href="" name="save-dm-update" type="submit" class="btn update-btn-primary" value="Lưu sản phẩm"/>
				<a href="admin_index.php" type="button" class="btn update-btn-secondary" >Quay lại</a>
			</div>
		</form>
	</div>
	<?PHP
		if (isset($_POST['save-dm-update']))
		{
			$madm = mysqli_real_escape_string($kn->con, $row['MaDM']);
			$tendm_safe             = mysqli_real_escape_string($kn->con, $_POST['tendm']);
			$mota_safe              = mysqli_real_escape_string($kn->con, $_POST['mota']);
			
			$query = "UPDATE `danh_muc` SET
				`tendm`= '$tendm_safe', 
				`mota` = '$mota_safe'
				WHERE `MaDM` = '$madm';
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