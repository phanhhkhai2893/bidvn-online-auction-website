<?PHP
    // --------------------------------------------------------------------
    // PHẦN 1: KHỞI TẠO BIẾN & QUERY MẶC ĐỊNH
    // --------------------------------------------------------------------
    
    // Khởi tạo biến mặc định
    $type = 'từ trước đến nay';

    // Query mặc định (Toàn thời gian)
    $queryLuotTongDauGia    = "SELECT COUNT(*) AS tong FROM lich_su_dau_gia";
    $queryTongSpDangDauGia  = "SELECT COUNT(*) AS tong FROM san_pham WHERE trangthai = N'Đang đấu giá'";
    $queryTongSpKetThuc     = "SELECT COUNT(*) AS tong FROM san_pham WHERE trangthai = N'Kết thúc' OR trangthai = N'Đã kết thúc'"; 
    $queryTongDoanhThu      = "SELECT SUM(thanhtien) AS tong FROM chi_tiet_hoa_don";

    // Query Bảng Xếp Hạng Giá
    $queryXhGiaSp = "SELECT sp.tensp, sp.gia_khoidiem as 'giakd', cthd.thanhtien as 'tt', nd.hoten 
                     FROM san_pham sp 
                     JOIN chi_tiet_hoa_don cthd ON sp.MaSP = cthd.MaSP
                     JOIN hoa_don h ON cthd.SoHD = h.SoHD
                     JOIN nguoi_dung nd ON h.MaND = nd.MaND
                     WHERE sp.trangthai LIKE '%Kết thúc%'
                     ORDER BY cthd.thanhtien DESC LIMIT 5";

    // Query Bảng Xếp Hạng Lượt Đấu Giá
    $queryXhLuotDg = "SELECT sp.TenSP as 'tensp', MAX(ls.gia_hientai) AS 'GiaThangCuoc', 
                             COUNT(ls.MaSP) AS 'LuotDat', COUNT(DISTINCT ls.MaND) AS 'NguoiThamGia'
                      FROM san_pham sp
                      JOIN lich_su_dau_gia ls ON ls.MaSP = sp.MaSP
                      WHERE sp.trangthai LIKE '%kết thúc%'
                      GROUP BY sp.MaSP, sp.TenSP
                      ORDER BY LuotDat DESC LIMIT 5";

    // --------------------------------------------------------------------
    // PHẦN 2: XỬ LÝ BỘ LỌC (KHI NGƯỜI DÙNG BẤM NÚT)
    // --------------------------------------------------------------------
    if (isset($_POST['thongke_button']))
    {
        // 1. THEO NGÀY
        if (isset($_POST['ngay_bat_dau']) && $_POST['ngay_bat_dau'] != null)
        {
            $ngay_raw = $_POST['ngay_bat_dau'];
            $ngay_safe = mysqli_real_escape_string($kn->con, $ngay_raw);
            $ngay_format = date('d/m/Y', strtotime($ngay_raw));
            $type = 'trong ngày ' . $ngay_format;

            $whereDate_LS = "WHERE DATE(thoigian_dau) = '$ngay_safe'";
            $whereDate_SP = "AND DATE(sp.thoigian_ketthuc) = '$ngay_safe'";
            $whereDate_HD = "AND DATE(hd.ngaylap) = '$ngay_safe'";

            $queryLuotTongDauGia = "SELECT COUNT(*) AS tong FROM lich_su_dau_gia $whereDate_LS";
            
            $queryTongSpDangDauGia = "SELECT COUNT(*) AS tong FROM san_pham sp 
                                      WHERE sp.trangthai = N'Đang đấu giá' 
                                      AND DATE(sp.thoigian_batdau) <= '$ngay_safe' 
                                      AND DATE(sp.thoigian_ketthuc) >= '$ngay_safe'";

            $queryTongSpKetThuc = "SELECT COUNT(*) AS tong FROM san_pham sp WHERE (trangthai = N'Kết thúc' OR trangthai = N'Đã kết thúc') $whereDate_SP";
            $queryTongDoanhThu = "SELECT SUM(ct.thanhtien) AS tong FROM chi_tiet_hoa_don ct, hoa_don hd WHERE ct.SoHD = hd.SoHD $whereDate_HD";

            $queryXhGiaSp = "SELECT sp.tensp, sp.gia_khoidiem as 'giakd', cthd.thanhtien as 'tt', nd.hoten 
                             FROM san_pham sp, chi_tiet_hoa_don cthd, hoa_don h, nguoi_dung nd
                             WHERE sp.MaSP = cthd.MaSP AND cthd.SoHD = h.SoHD AND h.MaND = nd.MaND
                             AND (sp.trangthai LIKE '%Kết thúc%')
                             AND DATE(h.ngaylap) = '$ngay_safe'
                             ORDER BY cthd.thanhtien DESC LIMIT 5";

            $queryXhLuotDg = "SELECT sp.TenSP as 'tensp', MAX(ls.gia_hientai) AS 'GiaThangCuoc', COUNT(ls.MaSP) AS 'LuotDat', COUNT(DISTINCT ls.MaND) AS 'NguoiThamGia'
                              FROM san_pham sp JOIN lich_su_dau_gia ls ON ls.MaSP = sp.MaSP
                              WHERE (sp.trangthai LIKE '%Kết thúc%')
                              AND DATE(sp.thoigian_ketthuc) = '$ngay_safe'
                              GROUP BY sp.MaSP, sp.TenSP ORDER BY LuotDat DESC LIMIT 5";
        }

        // 2. THEO THÁNG
        elseif ((isset($_POST['thang']) && $_POST['thang'] != "null") && (isset($_POST['nam']) && $_POST['nam'] != "null"))
        {
            $thang = (int)$_POST['thang'];
            $nam = (int)$_POST['nam'];
            $type = "trong tháng $thang năm $nam";

            $whereMonth_LS = "WHERE MONTH(thoigian_dau) = $thang AND YEAR(thoigian_dau) = $nam";
            $whereMonth_SP = "AND MONTH(sp.thoigian_ketthuc) = $thang AND YEAR(sp.thoigian_ketthuc) = $nam";
            $whereMonth_HD = "AND MONTH(hd.ngaylap) = $thang AND YEAR(hd.ngaylap) = $nam";

            $queryLuotTongDauGia = "SELECT COUNT(*) AS tong FROM lich_su_dau_gia $whereMonth_LS";
            
            $firstDate = "$nam-$thang-01";
            $queryTongSpDangDauGia = "SELECT COUNT(*) AS tong FROM san_pham sp 
                                      WHERE sp.trangthai = N'Đang đấu giá' 
                                      AND sp.thoigian_batdau <= LAST_DAY('$firstDate') 
                                      AND sp.thoigian_ketthuc >= '$firstDate'";

            $queryTongSpKetThuc = "SELECT COUNT(*) AS tong FROM san_pham sp WHERE (trangthai LIKE '%Kết thúc%') $whereMonth_SP";
            $queryTongDoanhThu = "SELECT SUM(ct.thanhtien) AS tong FROM chi_tiet_hoa_don ct, hoa_don hd WHERE ct.SoHD = hd.SoHD $whereMonth_HD";

            $queryXhGiaSp = "SELECT sp.tensp, sp.gia_khoidiem as 'giakd', cthd.thanhtien as 'tt', nd.hoten 
                             FROM san_pham sp, chi_tiet_hoa_don cthd, hoa_don h, nguoi_dung nd
                             WHERE sp.MaSP = cthd.MaSP AND cthd.SoHD = h.SoHD AND h.MaND = nd.MaND
                             AND (sp.trangthai LIKE '%Kết thúc%')
                             AND MONTH(h.ngaylap) = $thang AND YEAR(h.ngaylap) = $nam
                             ORDER BY cthd.thanhtien DESC LIMIT 5";

            $queryXhLuotDg = "SELECT sp.TenSP as 'tensp', MAX(ls.gia_hientai) AS 'GiaThangCuoc', COUNT(ls.MaSP) AS 'LuotDat', COUNT(DISTINCT ls.MaND) AS 'NguoiThamGia'
                              FROM san_pham sp JOIN lich_su_dau_gia ls ON ls.MaSP = sp.MaSP
                              WHERE (sp.trangthai LIKE '%Kết thúc%')
                              AND MONTH(sp.thoigian_ketthuc) = $thang AND YEAR(sp.thoigian_ketthuc) = $nam
                              GROUP BY sp.MaSP, sp.TenSP ORDER BY LuotDat DESC LIMIT 5";
        }

        // 3. THEO QUÝ
        elseif ((isset($_POST['quy']) && $_POST['quy'] != "null") && (isset($_POST['namq']) && $_POST['namq'] != "null"))
        {
            $quy = (int)$_POST['quy'];
            $namq = (int)$_POST['namq'];
            $type = "trong quý $quy năm $namq";
            
            $whereQuarter_LS = "WHERE QUARTER(thoigian_dau) = $quy AND YEAR(thoigian_dau) = $namq";
            
            $queryLuotTongDauGia = "SELECT COUNT(*) AS tong FROM lich_su_dau_gia $whereQuarter_LS";
            $queryTongSpDangDauGia = "SELECT COUNT(*) AS tong FROM san_pham sp WHERE sp.trangthai = N'Đang đấu giá' AND (YEAR(sp.thoigian_batdau) = $namq AND QUARTER(sp.thoigian_batdau) = $quy)";
            $queryTongSpKetThuc = "SELECT COUNT(*) AS tong FROM san_pham sp WHERE (trangthai LIKE '%Kết thúc%') AND QUARTER(sp.thoigian_ketthuc) = $quy AND YEAR(sp.thoigian_ketthuc) = $namq";
            $queryTongDoanhThu = "SELECT SUM(ct.thanhtien) AS tong FROM chi_tiet_hoa_don ct, hoa_don hd WHERE ct.SoHD = hd.SoHD AND QUARTER(hd.ngaylap) = $quy AND YEAR(hd.ngaylap) = $namq";
            
            $queryXhGiaSp = "SELECT sp.tensp, sp.gia_khoidiem as 'giakd', cthd.thanhtien as 'tt', nd.hoten 
                             FROM san_pham sp, chi_tiet_hoa_don cthd, hoa_don h, nguoi_dung nd
                             WHERE sp.MaSP = cthd.MaSP AND cthd.SoHD = h.SoHD AND h.MaND = nd.MaND
                             AND (sp.trangthai LIKE '%Kết thúc%')
                             AND QUARTER(h.ngaylap) = $quy AND YEAR(h.ngaylap) = $namq
                             ORDER BY cthd.thanhtien DESC LIMIT 5";

            $queryXhLuotDg = "SELECT sp.TenSP as 'tensp', MAX(ls.gia_hientai) AS 'GiaThangCuoc', COUNT(ls.MaSP) AS 'LuotDat', COUNT(DISTINCT ls.MaND) AS 'NguoiThamGia'
                              FROM san_pham sp JOIN lich_su_dau_gia ls ON ls.MaSP = sp.MaSP
                              WHERE (sp.trangthai LIKE '%Kết thúc%')
                              AND QUARTER(sp.thoigian_ketthuc) = $quy AND YEAR(sp.thoigian_ketthuc) = $namq
                              GROUP BY sp.MaSP, sp.TenSP ORDER BY LuotDat DESC LIMIT 5";
        }

        // 4. KHOẢNG NGÀY
        elseif ((isset($_POST['ngay_tu']) && $_POST['ngay_tu'] != "null") && (isset($_POST['ngay_den']) && $_POST['ngay_den'] != "null"))
        {
            $ngay_tu = mysqli_real_escape_string($kn->con, $_POST['ngay_tu']);
            $ngay_den = mysqli_real_escape_string($kn->con, $_POST['ngay_den']);
            $type = "từ ngày " . date('d/m/Y', strtotime($ngay_tu)) . " đến " . date('d/m/Y', strtotime($ngay_den));

            $queryLuotTongDauGia = "SELECT COUNT(*) AS tong FROM lich_su_dau_gia WHERE DATE(thoigian_dau) BETWEEN '$ngay_tu' AND '$ngay_den'";
            $queryTongSpDangDauGia = "SELECT COUNT(*) AS tong FROM san_pham sp WHERE sp.trangthai = N'Đang đấu giá' AND DATE(sp.thoigian_batdau) <= '$ngay_den' AND DATE(sp.thoigian_ketthuc) >= '$ngay_tu'";
            $queryTongSpKetThuc = "SELECT COUNT(*) AS tong FROM san_pham sp WHERE (trangthai LIKE '%Kết thúc%') AND DATE(sp.thoigian_ketthuc) BETWEEN '$ngay_tu' AND '$ngay_den'";
            $queryTongDoanhThu = "SELECT SUM(ct.thanhtien) AS tong FROM chi_tiet_hoa_don ct, hoa_don hd WHERE ct.SoHD = hd.SoHD AND DATE(hd.ngaylap) BETWEEN '$ngay_tu' AND '$ngay_den'";
            
            $queryXhGiaSp = "SELECT sp.tensp, sp.gia_khoidiem as 'giakd', cthd.thanhtien as 'tt', nd.hoten 
                             FROM san_pham sp, chi_tiet_hoa_don cthd, hoa_don h, nguoi_dung nd
                             WHERE sp.MaSP = cthd.MaSP AND cthd.SoHD = h.SoHD AND h.MaND = nd.MaND
                             AND (sp.trangthai LIKE '%Kết thúc%')
                             AND DATE(h.ngaylap) BETWEEN '$ngay_tu' AND '$ngay_den'
                             ORDER BY cthd.thanhtien DESC LIMIT 5";

            $queryXhLuotDg = "SELECT sp.TenSP as 'tensp', MAX(ls.gia_hientai) AS 'GiaThangCuoc', COUNT(ls.MaSP) AS 'LuotDat', COUNT(DISTINCT ls.MaND) AS 'NguoiThamGia'
                              FROM san_pham sp JOIN lich_su_dau_gia ls ON ls.MaSP = sp.MaSP
                              WHERE (sp.trangthai LIKE '%Kết thúc%')
                              AND DATE(sp.thoigian_ketthuc) BETWEEN '$ngay_tu' AND '$ngay_den'
                              GROUP BY sp.MaSP, sp.TenSP ORDER BY LuotDat DESC LIMIT 5";
        }
    }

    // --------------------------------------------------------------------
    // PHẦN 3: LẤY DỮ LIỆU CHO BIỂU ĐỒ (DỰA TRÊN QUERY ĐÃ LỌC)
    // --------------------------------------------------------------------
    
    // A. Biểu đồ Cột (Doanh thu Top 5)
    // Sử dụng $queryXhGiaSp đã được lọc theo ngày/tháng/quý ở trên
    $resultBar = mysqli_query($kn->con, $queryXhGiaSp);
    $barLabels = [];
    $barData = [];
    if ($resultBar && mysqli_num_rows($resultBar) > 0) {
        while ($row = mysqli_fetch_array($resultBar)) {
            $barLabels[] = $row['tensp']; 
            $barData[] = $row['tt'];
        }
    }
    // Chuyển sang JSON (Dùng JSON_UNESCAPED_UNICODE để giữ tiếng Việt đẹp)
    $barLabelsJSON = json_encode($barLabels, JSON_UNESCAPED_UNICODE);
    $barDataJSON = json_encode($barData);


    // B. Biểu đồ Tròn (Tỷ lệ trạng thái)
    // Query này lấy tổng quan, hoặc bạn có thể thêm bộ lọc ngày tháng vào đây nếu muốn
    $queryPie = "SELECT trangthai, COUNT(*) as solguong FROM san_pham GROUP BY trangthai";
    $resultPie = mysqli_query($kn->con, $queryPie);
    $pieLabels = [];
    $pieData = [];
    if ($resultPie && mysqli_num_rows($resultPie) > 0) {
        while ($row = mysqli_fetch_array($resultPie)) {
            $pieLabels[] = $row['trangthai'];
            $pieData[] = $row['solguong'];
        }
    }
    $pieLabelsJSON = json_encode($pieLabels, JSON_UNESCAPED_UNICODE);
    $pieDataJSON = json_encode($pieData);
?>

<!doctype html>
<html>
<head>
<meta charset="UTF-8">
    <link rel="stylesheet" href="../../../css/thongkeDG.css">
<title>Thống kê đấu giá</title>
</head>

<body>
    <div id="main-content">
        <form method="post" class="locThongKe-container">
            <label for="thongke_select">Thống kê theo&nbsp;</label>
            <?php
            $selected_option = isset($_POST['thongke_option']) ? $_POST['thongke_option'] : 'alltime';
            function check_selected($value, $current_selection) {
                if ($value === $current_selection) echo 'selected';
            }
            ?>
            <select name="thongke_option" id="thongke_select">
                <option value="alltime" <?php check_selected('alltime', $selected_option); ?>>Từ trước đến nay</option>
                <option value="day" <?php check_selected('day', $selected_option); ?>>Theo ngày</option>
                <option value="month" <?php check_selected('month', $selected_option); ?>>Theo tháng</option>
                <option value="3-month" <?php check_selected('3-month', $selected_option); ?>>Theo quý</option>
                <option value="custom" <?php check_selected('custom', $selected_option); ?>>Tự chọn</option>
            </select>

            <div id="date-input-block" class="thongke-input" style="display: none;">
                <label>Chọn ngày:&nbsp;</label><input type="date" name="ngay_bat_dau">
            </div>
            <div id="month-input-block" class="thongke-input" style="display: none;">
                <label>&nbsp;Chọn tháng&nbsp;</label>
                <select name="thang">
                    <option value="null">-- Chọn tháng --</option>
                    <?php for($i=1; $i<=12; $i++) echo "<option value='$i'>Tháng $i</option>"; ?>
                </select>
                <label>&nbsp;Chọn năm&nbsp;</label>
                <select name="nam">
                    <option value="null">-- Chọn năm --</option>
                    <?php for($i=2018; $i<=2030; $i++) echo "<option value='$i'>$i</option>"; ?>
                </select>
            </div>
            <div id="3-month-input-block" class="thongke-input" style="display: none;">
                <label>&nbsp;Chọn quý&nbsp;</label>
                <select name="quy">
                    <option value="null">-- Chọn quý --</option>
                    <?php for($i=1; $i<=4; $i++) echo "<option value='$i'>Quý $i</option>"; ?>
                </select>
                <label>&nbsp;Chọn năm&nbsp;</label>
                <select name="namq">
                    <option value="null">-- Chọn năm --</option>
                    <?php for($i=2018; $i<=2030; $i++) echo "<option value='$i'>$i</option>"; ?>
                </select>
            </div>
            <div id="custom-input-block" class="thongke-input" style="display: none;">
                <label>&nbsp;Từ ngày&nbsp;</label><input type="date" name="ngay_tu">
                <label>&nbsp;Đến ngày&nbsp;</label><input type="date" name="ngay_den">
            </div>
            
            <input type="submit" name="thongke_button" value="Thống kê">
        </form>
        
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const selectElement = document.getElementById('thongke_select');
                const inputBlocks = document.querySelectorAll('.thongke-input');
                function updateInputs() {
                    const selectedValue = selectElement.value;
                    inputBlocks.forEach(b => b.style.display = 'none');
                    let targetId = '';
                    if (selectedValue === 'day') targetId = 'date-input-block';
                    else if (selectedValue === 'month') targetId = 'month-input-block';
                    else if (selectedValue === '3-month') targetId = '3-month-input-block';
                    else if (selectedValue === 'custom') targetId = 'custom-input-block';
                    
                    if (targetId) document.getElementById(targetId).style.display = 'inline-block';
                }
                selectElement.addEventListener('change', updateInputs);
                updateInputs();
            });
        </script>
        
        <div id="thongke-alltime-view" class="thongke-view">
            <div class="thongke_container">
                <?PHP echo '<h3>Thống kê '. $type .'</h3>'; ?>
                
                <div class="kpi-cards-container">
                    <?PHP
                        // 1. TỔNG LƯỢT ĐẤU GIÁ
                        $result = mysqli_query($kn->con, $queryLuotTongDauGia);
                        $tongSP = ($result && mysqli_num_rows($result) > 0) ? mysqli_fetch_assoc($result)['tong'] : 0;
                    ?>
                    <div class="kpi-card">
                        <div class="kpi-icon"><img src="assets/imgs/others/auction.png" alt=""></div>
                        <div class="kpi-details">
                            <div class="kpi-value"><?PHP echo $tongSP; ?></div>
                            <div class="kpi-label">Tổng lượt Đấu giá</div>
                        </div>
                    </div>

                    <?PHP
                        // 2. ĐANG ĐẤU GIÁ
                        $result = mysqli_query($kn->con, $queryTongSpDangDauGia);
                        $spDangDG = ($result && mysqli_num_rows($result) > 0) ? mysqli_fetch_assoc($result)['tong'] : 0;
                    ?>
                    <div class="kpi-card active-users">
                        <div class="kpi-icon"><img src="assets/imgs/others/bidding.png" alt=""></div>
                        <div class="kpi-details">
                            <div class="kpi-value"><?PHP echo $spDangDG; ?></div>
                            <div class="kpi-label">Đang đấu giá</div>
                        </div>
                    </div>

                    <?PHP
                        // 3. ĐÃ KẾT THÚC
                        $result = mysqli_query($kn->con, $queryTongSpKetThuc);
                        $spKT = ($result && mysqli_num_rows($result) > 0) ? mysqli_fetch_assoc($result)['tong'] : 0;
                    ?>
                    <div class="kpi-card new-users">
                        <div class="kpi-icon"><img src="assets/imgs/others/bid.png" alt=""></div>
                        <div class="kpi-details">
                            <div class="kpi-value"><?PHP echo $spKT; ?></div>
                            <div class="kpi-label">Đã kết thúc</div>
                        </div>
                    </div>

                    <?PHP
                        // 4. DOANH THU
                        $result = mysqli_query($kn->con, $queryTongDoanhThu);
                        $doanhthu = ($result && mysqli_num_rows($result) > 0) ? mysqli_fetch_assoc($result)['tong'] : 0;
                    ?>
                    <div class="kpi-card bidder-users">
                        <div class="kpi-icon"><img src="assets/imgs/others/profit-up.png" alt=""></div>
                        <div class="kpi-details">
                            <div class="kpi-value"><?PHP echo number_format($doanhthu, 0, '', '.') . "đ" ?></div>
                            <div class="kpi-label">Doanh thu</div>
                        </div>
                    </div>
                </div>

                <div class="charts-container" style="display: flex; gap: 20px; margin-top: 30px; margin-bottom: 30px;">
                    <div class="chart-box" style="flex: 2; background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                        <h3 class="chart-title" style="margin-bottom: 15px; font-size: 18px; color: #333;">Top 5 Sản phẩm doanh thu cao nhất</h3>
                        <canvas id="revenueBarChart" style="max-height: 350px;"></canvas>
                    </div>
                    <div class="chart-box small-chart" style="flex: 1; background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                        <h3 class="chart-title" style="margin-bottom: 15px; font-size: 18px; color: #333;">Tỷ lệ trạng thái sản phẩm</h3>
                        <canvas id="statusPieChart" style="max-height: 350px;"></canvas>
                    </div>
                </div>

                <div class="detail-table-container">
                    <h3 class="table-title">Thống kê chi tiết doanh thu (Top 5)</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Hạng</th><th>Tên Sản Phẩm</th><th>Giá Khởi Điểm</th><th>Giá Thắng</th><th>Người Thắng</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?PHP
                                $result = mysqli_query($kn->con, $queryXhGiaSp);
                                $i = 0;
                                if($result) {
                                    while ($row = mysqli_fetch_array($result)) { $i++;
                            ?>
                                <tr>
                                    <td style="text-align: center"><?PHP echo $i; ?></td>
                                    <td><?PHP echo $row['tensp']; ?></td>
                                    <td><?PHP echo number_format($row['giakd'], 0, '', '.') . "đ" ?></td>
                                    <td><?PHP echo number_format($row['tt'], 0, '', '.') . "đ" ?></td>
                                    <td><?PHP echo $row['hoten']; ?></td>
                                </tr>
                            <?PHP }} ?>
                        </tbody>
                    </table>
                </div>

                <div class="detail-table-container">
                    <h3 class="table-title">Sản phẩm có lượt đấu giá cao nhất (Top 5)</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Hạng</th><th>Tên Sản Phẩm</th><th>Giá Thắng Cuộc</th><th>Tổng lượt đặt</th><th>Số người tham gia</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?PHP
                                $result = mysqli_query($kn->con, $queryXhLuotDg);
                                $i = 0;
                                if($result) {
                                    while ($row = mysqli_fetch_array($result)) { $i++;
                            ?>
                                <tr>
                                    <td style="text-align: center"><?PHP echo $i; ?></td>
                                    <td><?PHP echo $row['tensp']; ?></td>
                                    <td><?PHP echo number_format($row['GiaThangCuoc'], 0, '', '.') . "đ" ?></td>
                                    <td><?PHP echo $row['LuotDat']; ?></td>
                                    <td><?PHP echo $row['NguoiThamGia']; ?></td>
                                </tr>
                            <?PHP }} ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        const barLabels = <?php echo $barLabelsJSON ?: '[]'; ?>;
        const barData = <?php echo $barDataJSON ?: '[]'; ?>;
        const pieLabels = <?php echo $pieLabelsJSON ?: '[]'; ?>;
        const pieData = <?php echo $pieDataJSON ?: '[]'; ?>;

        document.addEventListener('DOMContentLoaded', function() {
            // Biểu đồ cột
            const revenueBarCanvas = document.getElementById('revenueBarChart');
            if (revenueBarCanvas) {
                new Chart(revenueBarCanvas.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: barLabels,
                        datasets: [{
                            label: 'Doanh thu',
                            data: barData,
                            backgroundColor: 'rgba(54, 162, 235, 0.6)',
                            borderColor: 'rgb(54, 162, 235)',
                            borderWidth: 1,
                            borderRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, ticks: { callback: v => v.toLocaleString('vi-VN') + 'đ' } }
                        }
                    }
                });
            }

            // Biểu đồ tròn
            const statusPieCanvas = document.getElementById('statusPieChart');
            if (statusPieCanvas) {
                new Chart(statusPieCanvas.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: pieLabels,
                        datasets: [{
                            data: pieData,
                            backgroundColor: ['#2ecc71', '#e74c3c', '#f1c40f', '#95a5a6', '#3498db'],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { position: 'bottom' } }
                    }
                });
            }
        });
    </script>
</body>
</html>