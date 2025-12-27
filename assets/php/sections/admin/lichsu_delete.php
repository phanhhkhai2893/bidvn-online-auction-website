<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Untitled Document</title>
</head>

<body>
			<div class="update-form-container">
				<h2>Xóa lịch sử?</h2>
				<form method="post" id="userUpdateForm">
					
					<div class="form-group">
						<label for="MaDM">Mã lịch sử</label>
						<input type="text" id="MaDM" name="MaDM" value="<?PHP echo $mals; ?>" readonly>
					</div>
					
					<div class="form-actions">
						<input name="save-ls-delete" type="submit" class="btn update-btn-primary" value="Xác nhận xóa" />
						<a href="admin_index.php" class="btn update-btn-secondary" >Hủy</a>
					</div>
				</form>
			</div>
	<?PHP
	
//		==============================	HÀM DELETE	 ==============================
			if (isset($_POST['save-ls-delete']))
			{
				$query = "DELETE FROM `lich_su_dau_gia` WHERE MaLS = '$mals'";
//					echo $query;

				$result = mysqli_query($kn -> con, $query)
				  or die("Lỗi DTB");

				if ($result) {
					echo "<meta http-equiv='refresh' content='0; url=admin_index.php' />";
				} 
				else {
					echo "Lỗi cập nhật dữ liệu: " . mysqli_error($kn -> con);
				}
			}
	?>
</body>
</html>