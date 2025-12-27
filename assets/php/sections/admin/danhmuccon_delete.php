<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Untitled Document</title>
</head>

<body>
			<div class="update-form-container">
				<h2>Xóa danh mục con: <?PHP echo $row['tendm']; ?>?</h2>
				<form method="post" id="userUpdateForm">
					
					<div class="form-group">
						<label for="MaDM">Mã danh mục con</label>
						<input type="text" id="MaDM" name="MaDM" value="<?PHP echo $madmc; ?>" readonly>
					</div>
					
					<div class="form-actions">
						<input name="save-dmc-delete" type="submit" class="btn update-btn-primary" value="Xác nhận xóa" />
						<a href="admin_index.php" class="btn update-btn-secondary" >Hủy</a>
					</div>
				</form>
			</div>
	<?PHP
	
//		==============================	HÀM DELETE	 ==============================
			if (isset($_POST['save-dmc-delete']))
			{
				$query = "DELETE FROM `danh_muc_con` WHERE MaDMCon = '$madmc'";
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