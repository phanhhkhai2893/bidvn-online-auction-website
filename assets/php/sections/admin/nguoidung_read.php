
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Untitled Document</title>
<link rel="stylesheet" href="../../../css/admin_style.css">
</head>

<body>
	<div class="update-form-container">
		<h2>Chi tiết thông tin người dùng: <?PHP echo $row['hoten']; ?></h2>
		<form method="post" id="userUpdateForm">

			<div class="form-group">
				<label for="MaND">Mã người dùng</label>
				<input type="text" id="MaND" name="MaND" value="<?PHP echo $row['MaND']; ?>" readonly>
			</div>

			<div class="form-group">
				<label for="username">Username</label>
				<input type="text" id="username" name="username" value="<?PHP echo $row['username']; ?>" readonly>
			</div>

			<div class="form-group">
				<label for="username">Password</label>
				<input type="text" id="password" name="password" value="<?PHP echo $row['password']; ?>" readonly>
			</div>

			<div class="form-group">
				<label for="hoten">Họ Tên</label>
				<input type="text" id="hoten" name="hoten" value="<?PHP echo $row['hoten']; ?>" readonly>
			</div>

			<div class="form-group">
				<label for="email">Email</label>
				<input type="email" id="email" name="email" value="<?PHP echo $row['email']; ?>" readonly>
			</div>

			<div class="form-group">
				<label for="sdt">SĐT</label>
				<input type="text" id="sdt" name="sdt" value="<?PHP echo $row['sdt']; ?>" readonly>
			</div>

			<div class="form-group">
				<label for="diachi">Địa chỉ</label>
				<input type="text" id="diachi" name="diachi" value="<?PHP echo $row['diachi']; ?>" readonly>
			</div>

			<div class="form-group">
				<label for="phanquyen">Phân quyền</label>
				<select id="phanquyen" name="phanquyen" disabled>
					<option value="admin" <?PHP echo ($row['phanquyen'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
					<option value="Người bán" <?PHP echo ($row['phanquyen'] == 'Người bán') ? 'selected' : ''; ?>>Người bán</option>
					<option value="Người mua" <?PHP echo ($row['phanquyen'] == 'Người mua') ? 'selected' : ''; ?>>Người mua</option>
				</select>
			</div>

			<div class="form-group">
				<label for="ngaydk">Ngày đăng ký</label>
				<input type="text" id="ngaydk" name="ngaydk" value="<?PHP echo $row['ngaydangky']; ?>" readonly>
			</div>

			<div class="form-group">
				<label for="trangthai">Trạng thái</label>
				<select id="trangthai" name="trangthai" disabled>
					<option value="Hoạt động" <?PHP echo ($row['trangthai'] == 'Hoạt động') ? 'selected' : ''; ?>>Hoạt động</option>
					<option value="Khóa" <?PHP echo ($row['trangthai'] == 'Khóa') ? 'selected' : ''; ?>>Khóa</option>
				</select>
			</div>

			<div class="form-actions">
				<a href="admin_index.php" type="button" class="btn update-btn-secondary" >Quay lại</a>
			</div>
		</form>
	</div>
	
</body>
</html>