<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Untitled Document</title>
</head>

<body>
	<div class="crud-buttons-container">
		<?php
			$query = "SELECT MaND, CAST(REPLACE(MaND, 'ND', '') AS SIGNED) AS ma_so FROM nguoi_dung ORDER BY ma_so DESC LIMIT 1";

			// Thực hiện truy vấn (Giả định $kn->con là đối tượng kết nối mysqli)
			$result = mysqli_query($kn->con, $query);

			if ($result && mysqli_num_rows($result) > 0) {
				$row = mysqli_fetch_assoc($result);
				$lastMaND = $row['MaND']; 

				$lastNumber = (int) str_replace('ND', '', $lastMaND);

				$newNumber = $lastNumber + 1; 

			} else {
				$newNumber = 1; 
			}

			$newMaND = 'ND' . $newNumber;
		?>
		<a href="admin_index.php?mand=<?PHP echo $newMaND; ?>&&act=nd_insert" class="crud-btns btn-primary" id="user_createBtn">
			<i class="fas fa-plus"></i> Thêm người dùng mới
		</a>
	</div>

	<div class="table-container">
		<table class="data-table">
			<!-- HEADER BẢNG -->
			<thead>
				<tr>
					<th class="col-hoten">Họ Tên</th>
					<th class="col-email">Email</th>
					<th class="col-sdt">SĐT</th>
					<th class="col-diachi">Địa chỉ</th>
					<th class="col-phanquyen">Phân quyền</th>
					<th class="col-trangthai">Trạng thái</th>
					<th class="" style="text-align: center" >Chức năng</th>
				</tr>
			</thead>
			<!-- BODY BẢNG (Dữ liệu mẫu) -->
			<tbody>
				<?PHP
					$query = "SELECT * FROM nguoi_dung";
					$result = mysqli_query($kn -> con, $query)
					  or die("Lỗi DTB");
					while ($row = mysqli_fetch_array($result))
					{
				?>
						<tr id="tb-row" class="user-row" data-mand="<?PHP echo $row['MaND']; ?>" >
							<td class="col-hoten"><?PHP echo $row['hoten'] ?></td>
							<td class="col-email"><?PHP echo $row['email'] ?></td>
							<td class="col-sdt"><?PHP echo $row['sdt'] ?></td>
							<td class="col-diachi"><?PHP echo $row['diachi'] ?></td>
							<td class="col-phanquyen"><?PHP echo $row['phanquyen'] ?></td>
							<td class="col-trangthai"><?PHP echo $row['trangthai'] ?></td>
							<td>
								<a href="admin_index.php?mand=<?PHP echo $row['MaND']; ?>&&act=nd_read" class="crud-btns btn-success" id="user_readBtn">
									<i class="fas fa-search"></i> Xem
								</a>
								<a href="admin_index.php?mand=<?PHP echo $row['MaND']; ?>&&act=nd_update" class="crud-btns btn-warning" id="user_updateBtn">
									<i class="fas fa-edit"></i> Chỉnh sửa
								</a>
								<a href="admin_index.php?mand=<?PHP echo $row['MaND']; ?>&&act=nd_delete" class="crud-btns btn-danger" id="user_deleteBtn">
									<i class="fas fa-trash-alt"></i> Xóa
								</a>
							</td>
						</tr>
						<tr>
							<td colspan="11" class="separator-row"></td>
						</tr>
				<?PHP
					}			
				?>
			</tbody>
		</table>
	</div>
	
<script type="text/javascript">
	
</script>
</body>
</html>