<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Untitled Document</title>
</head>

<body>
	<div class="crud-buttons-container">
		<?php
			$query = "SELECT MaDMCon, CAST(REPLACE(MaDMCon, 'DMC', '') AS SIGNED) AS ma_so FROM danh_muc_con ORDER BY ma_so DESC LIMIT 1";

			// Thực hiện truy vấn (Giả định $kn->con là đối tượng kết nối mysqli)
			$result = mysqli_query($kn->con, $query);

			if ($result && mysqli_num_rows($result) > 0) {
				$row = mysqli_fetch_assoc($result);
				$lastMa = $row['MaDMCon']; 

				$lastNumber = (int) str_replace('DMC', '', $lastMa);

				$newNumber = $lastNumber + 1; 

			} else {
				$newNumber = 1; 
			}

			$newMa = 'DMC' . $newNumber;
		?>
		<a href="admin_index.php?madmc=<?PHP echo $newMa; ?>&&act=dmc_insert" class="crud-btns btn-primary" id="user_createBtn">
			<i class="fas fa-plus"></i> Thêm danh mục con
		</a>
	</div>

	<div class="table-container">
		<table class="data-table">
			<!-- HEADER BẢNG -->
			<thead>
				<tr>
					<th class="">Mã danh mục con</th>
					<th class="">Mã danh mục</th>
					<th class="">Tên danh mục con</th>
					<th class="">Mô tả</th>
					<th class="" style="text-align: center" >Chức năng</th>
				</tr>
			</thead>
			<!-- BODY BẢNG (Dữ liệu mẫu) -->
			<tbody>
				<?PHP
					$query = "SELECT * FROM danh_muc_con";
					$result = mysqli_query($kn -> con, $query)
					  or die("Lỗi DTB");
					while ($row = mysqli_fetch_array($result))
					{
				?>
						<tr id="tb-row" class="user-row" data-madmcon="<?PHP echo $row['MaDMCon']; ?>" >
							<td class=""><?PHP echo $row['MaDMCon'] ?></td>
							<td class=""><?PHP echo $row['MaDM'] ?></td>
							<td class=""><?PHP echo $row['tendm'] ?></td>
							<td class=""><?PHP echo $row['mota'] ?></td>
							<td>
								<a href="admin_index.php?madmc=<?PHP echo $row['MaDMCon']; ?>&&act=dmc_read" class="crud-btns btn-success" id="dm_readBtn">
									<i class="fas fa-search"></i> Xem
								</a>
								<a href="admin_index.php?madmc=<?PHP echo $row['MaDMCon']; ?>&&act=dmc_update" class="crud-btns btn-warning" id="dm_updateBtn">
									<i class="fas fa-edit"></i> Chỉnh sửa
								</a>
								<a href="admin_index.php?madmc=<?PHP echo $row['MaDMCon']; ?>&&act=dmc_delete" class="crud-btns btn-danger" id="dm_deleteBtn">
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
</body>
</html>