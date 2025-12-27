<?PHP
  session_start();
  include('assets/php/database/db_connection.php');
  $kn = new db_connection();

unset($_SESSION['SignInError']);

if (isset($_SESSION['mand']))
{
	$MaND_real = $_SESSION['mand'];
}

//$_SESSION = array();
//
//if (ini_get("session.use_cookies")) {
//	$params = session_get_cookie_params();
//	setcookie(session_name(), '', time() - 42000,
//	$params["path"], $params["domain"],
//	$params["secure"], $params["httponly"]
//	);
//}
//
//session_destroy();
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8"/>
  <title>Trang Chủ - BIDVN</title>
  <link rel="stylesheet" href="assets/css/style.css?v=1">
  <link rel="stylesheet" href="assets/css/toast.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="icon" href="assets/imgs/logo/bidvn.ico" type="image/x-icon">
</head>

<body>
	
  <!--  ==========  HEADER  ==========  -->
  <?PHP include('assets/php/sections/header.php'); ?>
  <?PHP include('assets/php/sections/search_box.php') ?>

  <!--  ==========  BODY  ==========  -->
  <div id="body">
    <div class="container">
		
<!--
		<div id="toast-container"></div>
		<button onclick="showSimpleToast('Đã có 10 đơn hàng mới trong ngày!', 'Cảnh báo Đơn hàng')">
			Thử Toast Có Tiêu đề
		</button>
		<script type="text/javascript" src="assets/js/toast.js"></script>
-->
      <!--  ==========  SLIDER  ==========  -->
      <?PHP include('assets/php/sections/index_slider.php') ?>

      <!--  ==========  DANH MỤC  ==========  -->
      <?PHP include('assets/php/sections/index_danhmuc.php') ?>

      <!--  ==========  SẢN PHẨM  ==========  -->
      <?PHP include('assets/php/sections/index_sanpham.php') ?>
		
      <!--  ==========  GIỚI THIỆU  ==========  -->
      <?PHP include('assets/php/sections/index_gioithieu.php') ?>
    </div>
  </div>

  <!--  ==========  ĐĂNG NHẬP  ==========  -->
  <?PHP include('assets/php/sections/dangnhap.php') ?>

  <!--  ==========  FOOTER  ==========  -->
  <?PHP include('assets/php/sections/footer.php') ?>
	
  <!--  ==========  POPUP  ==========  -->
<!--
<div id="popup-banner" class="modal">
    <div id="popup-banner-img" class="modal-popup">
        
        <span id="close-popup" class="close-popup-btn">&times;</span>
        
            <img 
                src="assets/imgs/popup-banner/POPUP.png" 
                alt="Chương trình Khuyến mãi Banner"
            >
    </div>
</div>
-->

  <!--  ==========  JS  ==========  -->
<?PHP
//	if (isset($_SESSION['tennd']) && (isset($_SESSION['SignInError']) && $_SESSION['SignInError'] == true))
//	{
//		echo '<script type="text/javascript">';
//		echo "	const errorInput = document.getElementById('login-email');";
//		echo "	const errorMsg = document.getElementById('login-error-message');";
//		echo "	errorInput.classList.add('signinerror');";
//		echo "	errorMsg.classList.remove('hidden');";
//		echo "	errorInput.value = 'test';";
//		echo "	errorInput.focus();";
//		echo '</script>';
//	}
?>
</body>
</html>