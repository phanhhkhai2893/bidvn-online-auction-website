
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Untitled Document</title>
<link rel="stylesheet" href="../../../css/admin_style.css">
</head>

<body>
	<div class="update-form-container">
		<h2>Chi tiết thông tin lịch sử</h2>
		<form method="post" id="userUpdateForm">

			<div class="form-group">
				<label for="MaLS">Mã lịch sử</label>
				<input type="text" id="MaLS" name="MaLS" value="<?PHP echo $row['MaLS']; ?>" readonly>
			</div>

			<div class="form-group">
				<label for="gia_hientai">Giá hiện tại</label>
				<input type="text" id="gia_hientai" name="gia_hientai" value="<?PHP echo $row['gia_hientai']; ?>" readonly>
			</div>

			<div class="form-group">
				<label for="thoigian_dau">Thời gian</label>
				<input type="text" id="thoigian_dau" name="thoigian_dau" value="<?PHP echo $row['thoigian_dau']; ?>" readonly>
			</div>

			<div class="form-group">
				<label for="MaND">Mã người dùng</label>
				<select id="MaND" name="MaND" disabled>
					<?PHP
						$query = "SELECT * FROM lich_su_dau_gia";
						$result = mysqli_query($kn -> con, $query)
						  or die("Lỗi DTB");
						while ($row1 = mysqli_fetch_array($result))
						{
					?>
							<option value="<?PHP echo $row1['MaND']; ?>"><?PHP echo $row1['MaND']; ?></option>
					<?PHP
						}
					?>
				</select>
			</div>

			<div class="form-group">
				<label for="MaSP">Mã sản phẩm</label>
				<select id="MaSP" name="MaSP" disabled>
					<?PHP
						$query = "SELECT * FROM lich_su_dau_gia";
						$result = mysqli_query($kn -> con, $query)
						  or die("Lỗi DTB");
						while ($row1 = mysqli_fetch_array($result))
						{
					?>
							<option value="<?PHP echo $row1['MaSP']; ?>"><?PHP echo $row1['MaSP']; ?></option>
					<?PHP
						}
					?>
				</select>
			</div>

			<div class="form-actions">
				<a href="admin_index.php" type="button" class="btn update-btn-secondary" >Quay lại</a>
			</div>
		</form>
	</div>
	
</body>
</html>