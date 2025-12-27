<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Untitled Document</title>
</head>

<body>
			<div class="update-form-container">
				<h2>Xóa sản phẩm: <?PHP echo $row['tensp']; ?>?</h2>
				<form method="post" id="userUpdateForm">
					
					<div class="form-group">
						<label for="MaSP">Mã sản phẩm</label>
						<input type="text" id="MaSP" name="MaSP" value="<?PHP echo $masp; ?>" readonly>
					</div>
					
					<div class="form-actions">
						<input name="save-sp-delete" type="submit" class="btn update-btn-primary" value="Xác nhận xóa" />
						<a href="admin_index.php" class="btn update-btn-secondary" >Hủy</a>
					</div>
				</form>
			</div>
	<?PHP
	
//		==============================	HÀM DELETE	 ==============================
			if (isset($_POST['save-sp-delete']))
			{
				$query = "DELETE FROM `san_pham` WHERE MaSP = '$masp'";
//					echo $query;

				$result = mysqli_query($kn -> con, $query)
				  or die("Lỗi DTB");

				if ($result) {
					echo "<script>alert('Xóa sản phẩm thành công!');</script>";
					echo "<meta http-equiv='refresh' content='0; url=admin_index.php' />";
				} 
				else {
					echo "Lỗi cập nhật dữ liệu: " . mysqli_error($kn -> con);
				}
			}
	?>
</body>
</html>