<?php
// KIỂM TRA NẾU ĐÂY LÀ YÊU CẦU AJAX ĐỂ LẤY GỢI Ý
if (isset($_GET['is_ajax_request']) && $_GET['is_ajax_request'] === 'true' && isset($_GET['key'])) {
    if (ob_get_level() > 0) {
        ob_clean();
    }
    
    $key = $_GET['key']; 
    $stmt = mysqli_prepare($kn->con, "
        SELECT 
            sp.MaSP as 'masp',
            sp.MaDSHA as 'madsha',
            sp.tensp as 'tensp',
            sp.gia_hientai as 'gia',
            dsha.hinhanh as 'hinh'
        FROM 
            san_pham sp
        JOIN danh_muc dm ON sp.MaDM = dm.MaDM
        JOIN nguoi_dung nd ON sp.MaND = nd.MaND
        JOIN danh_sach_hinh_anh dsha ON dsha.MaDSHA = sp.MaDSHA
        WHERE 
            sp.tensp LIKE ? OR sp.mota LIKE ? OR dm.tendm LIKE ?
        GROUP BY sp.MaSP
        ORDER BY sp.tensp DESC, sp.gia_hientai DESC
        LIMIT 5
    ");

    // Tạo biến cho binding
    $search_term = "%" . $key . "%";
    
    // Binding tham số
    // 'sss' nghĩa là 3 tham số là kiểu chuỗi (string)
    mysqli_stmt_bind_param($stmt, "sss", $search_term, $search_term, $search_term);

    // Thực thi
    mysqli_stmt_execute($stmt);

    // Lấy kết quả
    $result = mysqli_stmt_get_result($stmt);
    
    // Logic vòng lặp while vẫn giữ nguyên:
    $html_output = '';
    while ($row = mysqli_fetch_array($result))
    {
        $html_output .= '
            <a class="suggest_product_containter" href="sanpham_detail.php?masp=' . $row['masp'] . '">
                <div class="suggest_product_img">
                    <img class="image-placeholder" src="assets/imgs/product/' . $row['hinh'] . '" alt="' . $row['tensp'] . '" />
                </div>
                <div class="suggest_product_text">
                    <h3 class="product-name">' . $row['tensp'] . '</h3>
                    <p class="product-price">' . number_format($row['gia'], 0, '', '.') . 'đ</p>
                </div>
            </a>';
    }
    
    // Đóng statement
    mysqli_stmt_close($stmt);
    
    if (mysqli_num_rows($result) === 0) {
        $html_output = '<div style="padding: 10px; text-align: center;">Không tìm thấy sản phẩm nào.</div>';
    }

    // Gửi kết quả và dừng script
    echo $html_output;
    exit;
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
	<style>
		.suggestion_searchbox {
			background-color: #fff;
			border-radius: 12px;
  			box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
		}
		
		.suggest_product_containter {
			display: flex;
			padding: 20px;
			z-index: 1;
		}
		
		.suggestion_searchbox.hidden {
			display: none;
		}
		
		.suggest_product_img {
			content: "";
			background-color: #ccc;
			width: 100px;
			height: 100px;
			border-radius: 8px;
		}
		
		.suggest_product_img > img {
			width: 100%;
			height: 100px;
		}
		
		.suggest_product_text {
			margin-left: 10px;
		}
	</style>
</head>

<body>
	
<?PHP
	if (isset($_POST['tk']))
	{
		$keyword = $_POST['key'];
		echo "<meta http-equiv='refresh' content='0; url=sanpham_searched.php?keyword=$keyword' />";
	}
?>
	
  <form id="" method="post" action="" class="search" style="">
     <i class="fa fa-search" aria-hidden="true"></i>
    <input id="searchInput" style="z-index: 2;" name="key" type="text" placeholder="Tìm sản phẩm...">
    <input name="tk" type="submit" value="Tìm kiếm">
	  
	<div id="js_suggestion_searchbox" class="suggestion_searchbox hidden"></div>
  </form>
	
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        const inputElement = document.getElementById('searchInput');
        const suggestionBox = document.getElementById('js_suggestion_searchbox');
        let timeoutId; 

        ffunction fetchSuggestions(keyword) {
        // Lấy đường dẫn cơ bản của script hiện tại (ví dụ: 'index.php')
			const pathname = window.location.pathname;

			// Tạo URLSearchParams để xử lý các tham số
			const params = new URLSearchParams({
				is_ajax_request: 'true',
				key: keyword
			});

			// Xây dựng URL AJAX:
			const ajaxUrl = pathname + '?' + params.toString();

			fetch(ajaxUrl)
                .then(response => response.text())
                .then(html => {
                    suggestionBox.innerHTML = html;
                    suggestionBox.classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Lỗi khi fetch gợi ý:', error);
                });
        }

        inputElement.addEventListener('input', function() {
            const inputText = inputElement.value.trim();
            clearTimeout(timeoutId);

            if (inputText.length === 0) {
                suggestionBox.classList.add('hidden');
                suggestionBox.innerHTML = '';
                return; 
            }

            // Debounce: Chờ 300ms trước khi gửi request
            timeoutId = setTimeout(() => {
                fetchSuggestions(inputText);
            }, 300);
        });
        
        // Ẩn hộp gợi ý khi click ra ngoài
        document.addEventListener('click', function(event) {
            if (!inputElement.contains(event.target) && !suggestionBox.contains(event.target)) {
                suggestionBox.classList.add('hidden');
            }
        });
    });
</script>
</body>
</html>