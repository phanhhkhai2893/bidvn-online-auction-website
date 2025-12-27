<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Untitled Document</title>
</head>

<body>
			<div class="update-form-container">
				<h2>Xóa người dùng: <?PHP echo $row['hoten']; ?>?</h2>
				<form method="post" id="userUpdateForm">
					
					<div class="form-group">
						<label for="MaND">Mã người dùng</label>
						<input type="text" id="MaND" name="MaND" value="<?PHP echo $mand; ?>" readonly>
					</div>
					
					<div class="form-actions">
						<input name="save-user-delete" type="submit" class="btn update-btn-primary" value="Xác nhận xóa" />
						<a href="admin_index.php" class="btn update-btn-secondary" >Hủy</a>
					</div>
				</form>
			</div>
	<?PHP
	
//		==============================	HÀM DELETE	 ==============================
			if (isset($_POST['save-user-delete']))
			{
				$query = "DELETE FROM `nguoi_dung` WHERE MaND = '$mand'";
//					echo $query;

				$result = mysqli_query($kn -> con, $query)
				  or die("Lỗi DTB");

				if ($result) {
            		echo "<script>alert('Xóa người dùng thành công!');</script>";
					echo "<meta http-equiv='refresh' content='0; url=admin_index.php' />";
				} 
				else {
					echo "Lỗi cập nhật dữ liệu: " . mysqli_error($kn -> con);
				}
			}
	?>
</body>
</html>