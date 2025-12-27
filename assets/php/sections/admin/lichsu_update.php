
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Untitled Document</title>
<link rel="stylesheet" href="../../../css/admin_style.css">
</head>

<body>
	<div class="update-form-container">
		<h2>Chỉnh sửa thông tin lịch sử</h2>
		<form method="post" id="userUpdateForm">

			<div class="form-group">
				<label for="MaLS">Mã lịch sử</label>
				<input type="text" id="MaLS" name="MaLS" value="<?PHP echo $row['MaLS']; ?>" readonly>
			</div>

			<div class="form-group">
				<label for="gia_hientai">Giá hiện tại</label>
				<input type="text" id="gia_hientai" name="gia_hientai" value="<?PHP echo $row['gia_hientai']; ?>">
			</div>

			<div class="form-group">
				<label for="thoigian_dau">Thời gian</label>
				<input type="date" id="thoigian_dau" name="thoigian_dau" value="<?PHP echo $row['thoigian_dau']; ?>">
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
				<label for="MaSP">Mã sản phẩm</label>
				<select id="MaSP" name="MaSP">
					<?PHP
						$query = "SELECT * FROM san_pham";
						$result = mysqli_query($kn -> con, $query)
						  or die("Lỗi DTB");
						while ($row1 = mysqli_fetch_array($result))
						{
					?>
							<option value="<?PHP echo $row1['MaSP']; ?>" <?PHP echo ($row1['MaSP'] == $row['MaSP']) ? 'selected' : ''; ?>><?PHP echo $row1['tensp']; ?></option>
					<?PHP
						}
					?>
				</select>
			</div>

			<div class="form-actions">
				<input href="" name="save-ls-update" type="submit" class="btn update-btn-primary" value="Lưu lịch sử"/>
				<a href="admin_index.php" type="button" class="btn update-btn-secondary" >Quay lại</a>
			</div>
		</form>
	</div>
	<?PHP
		if (isset($_POST['save-ls-update']))
		{
			$mals_safe = mysqli_real_escape_string($kn->con, $_POST['MaLS']); 
			$gia_hientai_ls_safe = mysqli_real_escape_string($kn->con, $_POST['gia_hientai']); 
			$thoigian_dau_safe = mysqli_real_escape_string($kn->con, $_POST['thoigian_dau']); 
			$mand_ls_safe = mysqli_real_escape_string($kn->con, $_POST['MaND']);
			$masp_ls_safe = mysqli_real_escape_string($kn->con, $_POST['MaSP']);

			// 2. TẠO CÂU LỆNH INSERT

			// Giả định tên bảng là san_pham và cấu trúc cột tương ứng
			$query = "UPDATE `lich_su_dau_gia` SET
								`gia_hientai` = $gia_hientai_ls_safe,
								`thoigian_dau` = '$thoigian_dau_safe',
								`MaND` = '$mand_ls_safe',
								`MaSP` = '$masp_ls_safe'
								WHERE `MaLS` = '$mals_safe';
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