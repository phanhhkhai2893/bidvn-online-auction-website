
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Untitled Document</title>
<link rel="stylesheet" href="../../../css/admin_style.css">
</head>

<body>
	<div class="update-form-container">
		<h2>Chi tiết thông tin sản phẩm: <?PHP echo $row['tensp']; ?></h2>
		<form method="post" id="userUpdateForm">

			<div class="form-group">
				<label for="MaSP">Mã sản phẩm</label>
				<input type="text" id="MaSP" name="MaSP" value="<?PHP echo $row['MaSP']; ?>" readonly>
			</div>

			<div class="form-group">
				<label for="tensp">Tên sản phẩm</label>
				<input type="text" id="tensp" name="tensp" value="<?PHP echo $row['tensp']; ?>" readonly>
			</div>

			<div class="form-group">
				<label for="mota">Mô tả</label>
				<input type="text" id="mota" name="mota" value="<?PHP echo $row['mota']; ?>" readonly>
			</div>

			<div class="form-group">
				<label for="gia_khoidiem">Giá khởi điểm</label>
				<input type="text" id="gia_khoidiem" name="gia_khoidiem" value="<?PHP echo $row['gia_khoidiem']; ?>" readonly>
			</div>

			<div class="form-group">
				<label for="gia_hientai">Giá hiện tại</label>
				<input type="email" id="gia_hientai" name="gia_hientai" value="<?PHP echo $row['gia_hientai']; ?>" readonly>
			</div>

			<div class="form-group">
				<label for="buocgia">Bước giá</label>
				<input type="text" id="buocgia" name="buocgia" value="<?PHP echo $row['buocgia']; ?>" readonly>
			</div>

			<div class="form-group">
				<label for="thoigian_batdau">Thời gian bắt đầu</label>
				<input type="text" id="thoigian_batdau" name="thoigian_batdau" value="<?PHP echo $row['thoigian_batdau']; ?>" readonly>
			</div>

			<div class="form-group">
				<label for="thoigian_ketthuc">Thời gian kết thúc</label>
				<input type="text" id="thoigian_ketthuc" name="thoigian_ketthuc" value="<?PHP echo $row['thoigian_ketthuc']; ?>" readonly>
			</div>

			<div class="form-group">
				<label for="trangthai">Trạng thái</label>
				<select id="trangthai" name="trangthai" disabled>
					<option value="Đang đấu giá" <?PHP echo ($row['trangthai'] == 'Đang đấu giá') ? 'selected' : ''; ?>>Đang đấu giá</option>
					<option value="Chưa bắt đầu" <?PHP echo ($row['trangthai'] == 'Chưa bắt đầu') ? 'selected' : ''; ?>>Chưa bắt đầu</option>
				</select>
			</div>

			<div class="form-group">
				<label for="MaDSHA">Mã danh sách hình ảnh</label>
				<select id="MaDSHA" name="MaDSHA" disabled>
					<?PHP
						$query = "SELECT * FROM san_pham";
						$result = mysqli_query($kn -> con, $query)
						  or die("Lỗi DTB");
						while ($row1 = mysqli_fetch_array($result))
						{
					?>
							<option value="<?PHP echo $row1['MaDSHA']; ?>"><?PHP echo $row1['MaDSHA']; ?></option>
					<?PHP
						}
					?>
				</select>
			</div>

			<div class="form-group">
				<label for="MaND">Mã người dùng</label>
				<select id="MaND" name="MaND" disabled>
					<?PHP
						$query = "SELECT * FROM san_pham";
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
				<label for="MaDM">Mã danh mục</label>
				<select id="MaDM" name="MaDM" disabled>
					<?PHP
						$query = "SELECT * FROM san_pham";
						$result = mysqli_query($kn -> con, $query)
						  or die("Lỗi DTB");
						while ($row1 = mysqli_fetch_array($result))
						{
					?>
							<option value="<?PHP echo $row1['MaDM']; ?>"><?PHP echo $row1['MaDM']; ?></option>
					<?PHP
						}
					?>
				</select>
			</div>

			<div class="form-group">
				<label for="MaDMCon">Mã danh mục con</label>
				<select id="MaDMCon" name="MaDMCon" disabled>
					<?PHP
						$query = "SELECT * FROM san_pham";
						$result = mysqli_query($kn -> con, $query)
						  or die("Lỗi DTB");
						while ($row1 = mysqli_fetch_array($result))
						{
					?>
							<option value="<?PHP echo $row1['MaDMCon']; ?>"><?PHP echo $row1['MaDMCon']; ?></option>
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