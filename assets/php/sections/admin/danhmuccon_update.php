
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Untitled Document</title>
<link rel="stylesheet" href="../../../css/admin_style.css">
</head>

<body>
	<div class="update-form-container">
		<h2>Chỉnh sửa thông tin danh mục con: <?PHP echo $row['tendm']; ?></h2>
		<form method="post" id="userUpdateForm">

			<div class="form-group">
				<label for="MaDM">Mã danh mục con</label>
				<input type="text" id="MaDM" name="MaDM" value="<?PHP echo $row['MaDMCon']; ?>" readonly>
			</div>

			<div class="form-group">
				<label for="tendm">Tên danh mục</label>
				<input type="text" id="tendm" name="tendm" value="<?PHP echo $row['tendm']; ?>" >
			</div>

			<div class="form-group">
				<label for="mota">Mô tả</label>
				<input type="text" id="mota" name="mota" value="<?PHP echo $row['mota']; ?>" >
			</div>

			<div class="form-group">
				<label for="hinhanh">Hình ảnh</label>
				<input type="text" id="hinhanh" name="hinhanh" value="<?PHP echo $row['hinhanh']; ?>" >
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
							<option value="<?PHP echo $row1['MaDM']; ?>" <?PHP echo ($row1['MaDM'] == $row['MaDM']) ? 'selected' : ''; ?>><?PHP echo $row1['tendm']; ?></option>
					<?PHP
						}
					?>
				</select>
			</div>
			
			<div class="form-actions">
				<input href="" name="save-dmc-update" type="submit" class="btn update-btn-primary" value="Lưu danh mục"/>
				<a href="admin_index.php" type="button" class="btn update-btn-secondary" >Quay lại</a>
			</div>
		</form>
	</div>
	<?PHP
		if (isset($_POST['save-dmc-update']))
		{
			$madmc = mysqli_real_escape_string($kn->con, $row['MaDMCon']);
			$tendm_safe             = mysqli_real_escape_string($kn->con, $_POST['tendm']);
			$mota_safe              = mysqli_real_escape_string($kn->con, $_POST['mota']);
			$hinhanh_safe              = mysqli_real_escape_string($kn->con, $_POST['hinhanh']);
			$MaDM_safe              = mysqli_real_escape_string($kn->con, $_POST['MaDM']);
			
			$query = "UPDATE `danh_muc_con` SET
				`tendm`= '$tendm_safe', 
				`mota` = '$mota_safe',
				`hinhanh` = '$hinhanh_safe',
				`MaDM` = '$MaDM_safe'
				WHERE `MaDMCon` = '$madmc';
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