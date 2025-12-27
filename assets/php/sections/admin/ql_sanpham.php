<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Untitled Document</title>
</head>

<body>
	<div class="crud-buttons-container">
		<?php
			$query = "SELECT MaSP, CAST(REPLACE(MaSP, 'SP', '') AS SIGNED) AS ma_so FROM san_pham ORDER BY ma_so DESC LIMIT 1";

			// Thực hiện truy vấn (Giả định $kn->con là đối tượng kết nối mysqli)
			$result = mysqli_query($kn->con, $query);

			if ($result && mysqli_num_rows($result) > 0) {
				$row = mysqli_fetch_assoc($result);
				$lastMaSP = $row['MaSP']; 

				$lastNumber = (int) str_replace('SP', '', $lastMaSP);

				$newNumber = $lastNumber + 1; 

			} else {
				$newNumber = 1; 
			}

			$newMaSP = 'SP' . $newNumber;
		?>
		<a href="admin_index.php?masp=<?PHP echo $newMaSP; ?>&&act=sp_insert" class="crud-btns btn-primary" id="user_createBtn">
			<i class="fas fa-plus"></i> Thêm sản phẩm mới
		</a>
	</div>

	<div class="table-container">
		<table class="data-table">
			<!-- HEADER BẢNG -->
			<thead>
				<tr>
					<th class="">Tên sản phẩm</th>
					<th class="">Mô tả</th>
					<th class="">Giá khởi điểm</th>
					<th class="">Giá hiện tại</th>
					<th class="">Bước giá</th>
					<th class="">Trạng thái</th>
					<th class="" style="text-align: center" >Chức năng</th>
				</tr>
			</thead>
			<!-- BODY BẢNG (Dữ liệu mẫu) -->
			<tbody>
				<?PHP
					$query = "SELECT * FROM san_pham";
					$result = mysqli_query($kn -> con, $query)
					  or die("Lỗi DTB");
					while ($row = mysqli_fetch_array($result))
					{
				?>
						<tr id="tb-row" class="user-row" data-masp="<?PHP echo $row['MaSP']; ?>" >
							<td><?PHP echo $row['tensp'] ?></td>
							<td style="max-width: 200px;"><?PHP echo $row['mota'] ?></td>
							<td><?PHP echo $row['gia_khoidiem'] ?></td>
							<td><?PHP echo $row['gia_hientai'] ?></td>
							<td><?PHP echo $row['buocgia'] ?></td>
							<td><?PHP echo $row['trangthai'] ?></td>
							<td>
								<a href="admin_index.php?masp=<?PHP echo $row['MaSP']; ?>&&act=sp_read" class="crud-btns btn-success" id="user_readBtn">
									<i class="fas fa-search"></i> Xem
								</a>
								<a href="admin_index.php?masp=<?PHP echo $row['MaSP']; ?>&&act=sp_update" class="crud-btns btn-warning" id="user_updateBtn">
									<i class="fas fa-edit"></i> Chỉnh sửa
								</a>
								<a href="admin_index.php?masp=<?PHP echo $row['MaSP']; ?>&&act=sp_delete" class="crud-btns btn-danger" id="user_deleteBtn">
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