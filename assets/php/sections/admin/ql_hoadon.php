<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Untitled Document</title>
</head>

<body>
	<div class="crud-buttons-container">
		<?php
			$query = "SELECT SoHD, CAST(REPLACE(SoHD, 'HD', '') AS SIGNED) AS ma_so FROM hoa_don ORDER BY ma_so DESC LIMIT 1";

			// Thực hiện truy vấn (Giả định $kn->con là đối tượng kết nối mysqli)
			$result = mysqli_query($kn->con, $query);

			if ($result && mysqli_num_rows($result) > 0) {
				$row = mysqli_fetch_assoc($result);
				$lastMa = $row['SoHD']; 

				$lastNumber = (int) str_replace('HD', '', $lastMa);

				$newNumber = $lastNumber + 1; 

			} else {
				$newNumber = 1; 
			}

			$newMa = 'HD' . $newNumber;
		?>
		<a href="admin_index.php?sohd=<?PHP echo $newMa; ?>&&act=hd_insert" class="crud-btns btn-primary" id="user_createBtn">
			<i class="fas fa-plus"></i> Thêm hóa đơn mới
		</a>
	</div>

	<div class="table-container">
		<table class="data-table">
			<!-- HEADER BẢNG -->
			<thead>
				<tr>
					<th class="">Số hóa đơn</th>
					<th class="">Nội dung</th>
					<th class="">Ngày lập</th>
					<th class="">Phương thức thanh toán</th>
					<th class="">Mã người dùng</th>
					<th class="" style="text-align: center" >Chức năng</th>
				</tr>
			</thead>
			<!-- BODY BẢNG (Dữ liệu mẫu) -->
			<tbody>
				<?PHP
					$query = "SELECT * FROM hoa_don";
					$result = mysqli_query($kn -> con, $query)
					  or die("Lỗi DTB");
					while ($row = mysqli_fetch_array($result))
					{
				?>
						<tr id="tb-row" class="user-row" data-sohd="<?PHP echo $row['SoHD']; ?>" >
							<td><?PHP echo $row['SoHD'] ?></td>
							<td><?PHP echo $row['noidung'] ?></td>
							<td><?PHP echo $row['ngaylap'] ?></td>
							<td><?PHP echo $row['phuongthuctt'] ?></td>
							<td><?PHP echo $row['MaND'] ?></td>
							<td>
								<a href="admin_index.php?sohd=<?PHP echo $row['SoHD']; ?>&&act=hd_read" class="crud-btns btn-success" id="user_readBtn">
									<i class="fas fa-search"></i> Xem
								</a>
								<a href="admin_index.php?sohd=<?PHP echo $row['SoHD']; ?>&&act=hd_update" class="crud-btns btn-warning" id="user_updateBtn">
									<i class="fas fa-edit"></i> Chỉnh sửa
								</a>
								<a href="admin_index.php?sohd=<?PHP echo $row['SoHD']; ?>&&act=hd_delete" class="crud-btns btn-danger" id="user_deleteBtn">
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