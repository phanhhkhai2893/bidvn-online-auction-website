<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Untitled Document</title>
</head>

<body>
<?PHP
//	TỔNG SỐ NGƯỜI DÙNG
	$query = "SELECT COUNT(*) AS tong FROM nguoi_dung";

	$result = mysqli_query($kn->con, $query);

	if (mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_assoc($result); 

		$slND = $row['tong']; 
	} else {
		$slND = 0;
	}
	
//	TỔNG SỐ NGƯỜI DÙNG HOẠT ĐỘNG TRONG 30 NGÀY
	$query = "SELECT COUNT(DISTINCT MaND) AS tong
			FROM lich_su_dau_gia 
			WHERE thoigian_dau >= DATE_SUB(NOW(), INTERVAL 30 DAY)";

	$result = mysqli_query($kn->con, $query);

	if (mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_assoc($result); 

		$slHD = $row['tong']; 
	} else {
		$slHD = 0;
	}
	
//	TỔNG SỐ NGƯỜI DÙNG MỚI TRONG 7 NGÀY
	$query = "SELECT COUNT(DISTINCT MaND) AS tong
			FROM nguoi_dung 
			WHERE ngaydangky >= DATE_SUB(NOW(), INTERVAL 7 DAY)";

	$result = mysqli_query($kn->con, $query);

	if (mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_assoc($result); 

		$slNew = $row['tong']; 
	} else {
		$slNew = 0;
	}
	
// BIỂU ĐỒ NGƯỜI DÙNG 3 THÁNG
$query = "SELECT 
            DATE_FORMAT(ngaydangky, '%Y-%m') AS registration_month,
            COUNT(MaND) AS new_user_count
        FROM 
            nguoi_dung
        WHERE 
            ngaydangky >= DATE_SUB(NOW(), INTERVAL 6 MONTH) -- Lấy rộng ra 6 tháng cho dễ có data
        GROUP BY 
            registration_month
        ORDER BY 
            registration_month ASC;";

$result = mysqli_query($kn->con, $query);

$labels = []; 
$counts = []; 

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $labels[] = "Tháng " . date('m/Y', strtotime($row['registration_month'])); // Format lại ngày cho đẹp
        $counts[] = (int)$row['new_user_count'];
    }
} 

// [DEBUG] NẾU KHÔNG CÓ DỮ LIỆU -> TẠO DỮ LIỆU GIẢ ĐỂ TEST BIỂU ĐỒ
if (empty($counts)) {
    $labels = ['Tháng 10', 'Tháng 11', 'Tháng 12'];
    $counts = [0, 0, 0]; // Hoặc [5, 10, 15] để test hiển thị
}

$chart_data = [
    'labels' => $labels,
    'data' => $counts
];

// Dùng JSON_NUMERIC_CHECK để đảm bảo số là số, không bị biến thành chuỗi
$chart_data_json = json_encode($chart_data, JSON_NUMERIC_CHECK);
//	BIỂU ĐỒ TỈ LỆ THAM GIA ĐẤU GIÁ
	$query_ratio = "SELECT 
						COUNT(T1.MaND) AS total_users,
						COUNT(T2.MaND) AS total_bidders
					FROM 
						nguoi_dung T1
					LEFT JOIN 
						(SELECT DISTINCT MaND FROM lich_su_dau_gia) T2 
					ON 
						T1.MaND = T2.MaND";

	$result_ratio = mysqli_query($kn->con, $query_ratio);

	$row_ratio = mysqli_fetch_assoc($result_ratio);

	$total_users = (int)$row_ratio['total_users'];
	$total_bidders = (int)$row_ratio['total_bidders'];

	// Tính toán nhóm chưa đặt giá
	$non_bidders = $total_users - $total_bidders;

	// Chuẩn bị dữ liệu cho biểu đồ tròn (Doughnut Chart)
	$ratio_data = [
		'labels' => ['Người dùng tham gia', 'Người dùng không tham gia'],
		'data' => [$total_bidders, $non_bidders]
	];

	$ratio_data_json = json_encode($ratio_data);
?>
	<div id="main-content">
        <div class="kpi-cards-container">
            <div class="kpi-card">
                <div class="kpi-icon">👥</div>
                <div class="kpi-details">
                    <div class="kpi-value"><?PHP echo $slND; ?></div>
                    <div class="kpi-label">Tổng số Người dùng</div>
                </div>
            </div>
            <div class="kpi-card active-users">
                <div class="kpi-icon">🟢</div>
                <div class="kpi-details">
                    <div class="kpi-value"><?PHP echo $slHD; ?></div>
                    <div class="kpi-label">Hoạt động (30 ngày)</div>
                </div>
            </div>
            <div class="kpi-card new-users">
                <div class="kpi-icon">🆕</div>
                <div class="kpi-details">
                    <div class="kpi-value">+<?PHP echo $slNew; ?></div>
                    <div class="kpi-label">Người dùng Mới (Tuần này)</div>
                </div>
            </div>
            <div class="kpi-card bidder-users">
                <div class="kpi-icon">🔨</div>
                <div class="kpi-details">
                    <div class="kpi-value"><?PHP echo $slHD; ?></div>
                    <div class="kpi-label">Người dùng Đặt giá (30 ngày)</div>
                </div>
            </div>
        </div>

        <div class="charts-container">
            <div class="chart-box">
                <h3 class="chart-title">Tăng trưởng Người dùng Mới (3 tháng)</h3>
                <canvas id="userGrowthChart" style="max-height: 350px;"></canvas>
            </div>
            <div class="chart-box small-chart">
                <h3 class="chart-title">Tỷ lệ Tham gia Đấu giá</h3>
                <canvas id="participationChart" style="max-height: 350px;"></canvas>
            </div>
        </div>

        <div class="detail-table-container">
            <h3 class="table-title">Thống kê Hành vi Người dùng Hàng đầu</h3>
            <table>
                <thead>
                    <tr>
                        <th>Hạng</th>
                        <th>Tên Người dùng</th>
                        <th>Lượt Đặt giá</th>
                        <th>Tổng Chi tiêu</th>
                    </tr>
                </thead>
                <tbody>
					<?PHP
						$query = "SELECT
									nd.hoten AS 'Họ Tên',
									COUNT(ls.MaND) AS ldg,
									IFNULL(SUM(cthd.thanhtien), 0) AS tct
								FROM
									nguoi_dung nd
								LEFT JOIN
									lich_su_dau_gia ls ON nd.MaND = ls.MaND
								LEFT JOIN
									hoa_don hd ON nd.MaND = hd.MaND
								LEFT JOIN
									chi_tiet_hoa_don cthd ON hd.SoHD = cthd.SoHD
								GROUP BY
									nd.MaND, nd.hoten
								HAVING
									COUNT(ls.MaND) > 0 OR IFNULL(SUM(cthd.thanhtien), 0) > 0
								ORDER BY
									tct DESC, ldg DESC
								LIMIT 5;";
						$result = mysqli_query($kn -> con, $query)
						  or die("Lỗi DTB");
						$i = 0;
						while ($row = mysqli_fetch_array($result))
						{
							$i++;
					?>
							<tr>
								<td style="text-align: center"><?PHP echo $i; ?></td>
								<td><?PHP echo $row['Họ Tên']; ?></td>
								<td style="text-align: center"><?PHP echo $row['ldg']; ?></td>
								<td><?PHP echo number_format($row['tct'], 0, '', '.') . "đ" ?></td>
							</tr>
					<?PHP
						}
					?>
                </tbody>
            </table>
        </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    // Nhận dữ liệu từ PHP an toàn hơn
    const phpChartData = <?php echo $chart_data_json ?: '{"labels":[],"data":[]}'; ?>;
    const phpRatioData = <?php echo $ratio_data_json ?: '{"labels":[],"data":[]}'; ?>;

    // Debug: Bật F12 tab Console để xem dữ liệu có qua được không
    console.log('Line Chart Data:', phpChartData);

    document.addEventListener('DOMContentLoaded', function() {
        
        // --- 1. BIỂU ĐỒ ĐƯỜNG (LINE CHART) ---
        const userGrowthCanvas = document.getElementById('userGrowthChart');
        if (userGrowthCanvas) {
            const userGrowthCtx = userGrowthCanvas.getContext('2d');
            
            new Chart(userGrowthCtx, {
                type: 'line',
                data: {
                    labels: phpChartData.labels, 
                    datasets: [{
                        label: 'Người dùng Mới',
                        data: phpChartData.data,
                        borderColor: '#FBC02D', // Màu vàng Brand
                        backgroundColor: 'rgba(251, 192, 45, 0.2)', // Màu nền mờ
                        tension: 0.4, // Đường cong mềm mại
                        fill: true,
                        borderWidth: 3,
                        pointRadius: 6, // [QUAN TRỌNG] Kích thước chấm tròn (để thấy được nếu chỉ có 1 điểm)
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#FBC02D',
                        pointBorderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.parsed.y + ' người dùng';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1 } // Chỉ hiện số nguyên (người dùng không thể là 1.5)
                        }
                    }
                }
            });
        }

        // --- 2. BIỂU ĐỒ TRÒN (DOUGHNUT) ---
        const participationCanvas = document.getElementById('participationChart');
        if (participationCanvas) {
            const participationCtx = participationCanvas.getContext('2d');

            new Chart(participationCtx, {
                type: 'doughnut',
                data: {
                    labels: phpRatioData.labels,
                    datasets: [{
                        data: phpRatioData.data,
                        backgroundColor: [
                            '#FBC02D', // Màu vàng
                            '#E0E0E0'  // Màu xám (đã sửa lỗi dấu chấm phẩy thừa)
                        ],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' }
                    }
                }
            });
        }
    });
</script>
</body>
</html>