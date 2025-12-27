<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Untitled Document</title>
</head>

<body>
	<?PHP
			date_default_timezone_set('Asia/Ho_Chi_Minh'); 
			$ngay_dang_ky_hien_tai = date('Y-m-d H:i:s');
	?>
<!--	==============================	FORM INSERT	 ==============================		-->
			<div class="update-form-container">
				<h2>Thêm người dùng mới</h2>
				<form method="post" id="userUpdateForm">

					<div class="form-group">
						<label for="MaND">Mã người dùng</label>
						<input type="text" id="MaND" name="MaND" value="<?PHP echo $mand; ?>" readonly>
					</div>

					<div class="form-group">
						<label for="username">Username</label>
						<input type="text" id="username" name="username" value="">
					</div>

					<div class="form-group">
						<label for="username">Password</label>
						<input type="text" id="password" name="password" value="">
					</div>

					<div class="form-group">
						<label for="hoten">Họ Tên</label>
						<input type="text" id="hoten" name="hoten" value="">
					</div>

					<div class="form-group">
						<label for="email">Email</label>
						<input type="email" id="email" name="email" value="">
					</div>

					<div class="form-group">
						<label for="sdt">SĐT</label>
						<input type="text" id="sdt" name="sdt" value="">
					</div>

					<div class="form-group">
						<label for="diachi">Địa chỉ</label>
						<input type="text" id="diachi" name="diachi" value="">
					</div>

					<div class="form-group">
						<label for="phanquyen">Phân quyền</label>
						<select id="phanquyen" name="phanquyen">
							<option value="admin">Admin</option>
							<option value="Người bán">Người bán</option>
							<option value="Người mua">Người mua</option>
						</select>
					</div>

					<div class="form-group">
						<label for="ngaydk">Ngày đăng ký</label>
						<input type="text" id="ngaydk" name="ngaydk" value="<?PHP echo $ngay_dang_ky_hien_tai; ?>" readonly>
					</div>

					<div class="form-group">
						<label for="trangthai">Trạng thái</label>
						<select id="trangthai" name="trangthai">
							<option value="Hoạt động">Hoạt động</option>
							<option value="Khóa">Khóa</option>
						</select>
					</div>

					<div class="form-actions">
						<input href="" name="save-user-create" type="submit" class="btn update-btn-primary" value="Thêm người dùng"/>
						<a href="admin_index.php" class="btn update-btn-secondary" >Hủy</a>
					</div>
				</form>
			</div>
	<?PHP
	
//		==============================	HÀM INSERT	 ==============================
		if (isset($_POST['save-user-create']))
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
			$ngay_dang_ky_hien_tai = mysqli_real_escape_string($kn -> con, $_POST['ngaydk']);

			$query = "INSERT INTO `nguoi_dung` 
			  (
				`MaND`, 
				`username`, 
				`password`, 
				`hoten`, 
				`email`, 
				`sdt`, 
				`diachi`, 
				`avarta`, 
				`phanquyen`, 
				`trangthai`,
				`ngaydangky`  
			  ) 
			  VALUES 
			  (
				'$mand_safe', 
				'$username_safe', 
				'$password_safe', 
				N'$hoten_safe', 
				'$email_safe', 
				'$sdt_safe', 
				N'$diachi_safe', 
				N'default_user', 
				N'$phanquyen_safe', 
				N'$trangthai_safe',
				'$ngay_dang_ky_hien_tai'
			  )";

			$result = mysqli_query($kn -> con, $query)
			  or die("Lỗi DTB");

			if ($result) {
            	echo "<script>alert('Thêm người dùng thành công!');</script>";
				echo "<meta http-equiv='refresh' content='0; url=admin_index.php' />";
			} 
			else {
				echo "Lỗi cập nhật dữ liệu: " . mysqli_error($kn -> con);
			}
		}
	?>
</body>
</html>