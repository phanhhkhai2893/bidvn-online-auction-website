
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Untitled Document</title>
<link rel="stylesheet" href="../../../css/admin_style.css">
</head>

<body>
	<div class="update-form-container">
		<h2>Chi tiết thông tin danh mục con: <?PHP echo $row['tendm']; ?></h2>
		<form method="post" id="userUpdateForm">

			<div class="form-group">
				<label for="MaDM">Mã danh mục con</label>
				<input type="text" id="MaDM" name="MaDM" value="<?PHP echo $row['MaDM']; ?>" readonly>
			</div>

			<div class="form-group">
				<label for="tendm">Tên danh mục</label>
				<input type="text" id="tendm" name="tendm" value="<?PHP echo $row['tendm']; ?>" readonly>
			</div>

			<div class="form-group">
				<label for="mota">Mô tả</label>
				<input type="text" id="mota" name="mota" value="<?PHP echo $row['mota']; ?>" readonly>
			</div>

			<div class="form-group">
				<label for="tendm">Hình ảnh</label>
				<input type="text" id="tendm" name="tendm" value="<?PHP echo $row['hinhanh']; ?>" readonly>
			</div>

			<div class="form-group">
				<label for="tendm">Mã danh mục</label>
				<input type="text" id="tendm" name="tendm" value="<?PHP echo $row['MaDM']; ?>" readonly>
			</div>
			
			<div class="form-actions">
				<a href="admin_index.php" type="button" class="btn update-btn-secondary" >Quay lại</a>
			</div>
		</form>
	</div>
	
</body>
</html>