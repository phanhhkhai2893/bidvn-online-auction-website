<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Untitled Document</title>
</head>

<body>
	<div class="update-form-container">
		<h2>Chỉnh sửa thông tin người dùng: <?PHP echo $row['hoten']; ?></h2>
		<form method="post" id="userUpdateForm">

			<div class="form-group">
				<label for="MaND">Mã người dùng</label>
				<input type="text" id="MaND" name="MaND" value="<?PHP echo $row['MaND']; ?>" readonly>
			</div>

			<div class="form-group">
				<label for="username">Username</label>
				<input type="text" id="username" name="username" value="<?PHP echo $row['username']; ?>">
			</div>

			<div class="form-group">
				<label for="username">Password</label>
				<input type="text" id="password" name="password" value="<?PHP echo $row['password']; ?>">
			</div>

			<div class="form-group">
				<label for="hoten">Họ Tên</label>
				<input type="text" id="hoten" name="hoten" value="<?PHP echo $row['hoten']; ?>">
			</div>

			<div class="form-group">
				<label for="email">Email</label>
				<input type="email" id="email" name="email" value="<?PHP echo $row['email']; ?>">
			</div>

			<div class="form-group">
				<label for="sdt">SĐT</label>
				<input type="text" id="sdt" name="sdt" value="<?PHP echo $row['sdt']; ?>">
			</div>

			<div class="form-group">
				<label for="diachi">Địa chỉ</label>
				<input type="text" id="diachi" name="diachi" value="<?PHP echo $row['diachi']; ?>">
			</div>

			<div class="form-group">
				<label for="phanquyen">Phân quyền</label>
				<select id="phanquyen" name="phanquyen">
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
				<select id="trangthai" name="trangthai">
					<option value="Hoạt động" <?PHP echo ($row['trangthai'] == 'Hoạt động') ? 'selected' : ''; ?>>Hoạt động</option>
					<option value="Khóa" <?PHP echo ($row['trangthai'] == 'Khóa') ? 'selected' : ''; ?>>Khóa</option>
				</select>
			</div>

			<div class="form-actions">
				<input href="" name="save-user-update" type="submit" class="btn update-btn-primary" value="Lưu thay đổi"/>
				<a href="admin_index.php" class="btn update-btn-secondary" >Hủy</a>
			</div>
		</form>
	</div>
	
	<?PHP
	
//		==============================	HÀM UPDATE	 ==============================
		if (isset($_POST['save-user-update']))
		{
			$username_safe = mysqli_real_escape_string($kn -> con, $_POST['username']);
			$password_safe = mysqli_real_escape_string($kn -> con, $_POST['password']);
			$hoten_safe = mysqli_real_escape_string($kn -> con, $_POST['hoten']);
			$email_safe = mysqli_real_escape_string($kn -> con, $_POST['email']);
			$sdt_safe = mysqli_real_escape_string($kn -> con, $_POST['sdt']);
			$diachi_safe = mysqli_real_escape_string($kn -> con, $_POST['diachi']);
			$phanquyen_safe = mysqli_real_escape_string($kn -> con, $_POST['phanquyen']);
			$trangthai_safe = mysqli_real_escape_string($kn -> con, $_POST['trangthai']);
			$mand_safe = mysqli_real_escape_string($kn -> con, $_POST['MaND']);

			$query = "UPDATE `nguoi_dung` SET 
				`username` = '$username_safe', 
				`password` = '$password_safe', 
				`hoten` = '$hoten_safe', 
				`email` = '$email_safe', 
				`sdt` = '$sdt_safe', 
				`diachi` = '$diachi_safe', 
				`phanquyen` = '$phanquyen_safe', 
				`trangthai` = '$trangthai_safe' 
				WHERE `MaND` = '$mand_safe'";

			$result = mysqli_query($kn -> con, $query)
			  or die("Lỗi DTB");

			if ($result) {
            	echo "<script>alert('Sửa người dùng thành công!');</script>";
				echo "<meta http-equiv='refresh' content='0; url=admin_index.php' />";
			} 
			else {
				echo "Lỗi cập nhật dữ liệu: " . mysqli_error($kn -> con);
			}
		}
	?>
</body>
</html>