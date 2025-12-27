<?PHP
  session_start();
  include('assets/php/database/db_connection.php');
  $kn = new db_connection();
//				$_SESSION = array();
//
//				if (ini_get("session.use_cookies")) {
//					$params = session_get_cookie_params();
//					setcookie(session_name(), '', time() - 42000,
//						$params["path"], $params["domain"],
//						$params["secure"], $params["httponly"]
//					);
//				}
//
//				session_destroy();
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Thanh Toán</title>
  <link rel="stylesheet" href="assets/css/style.css" />
  <link rel="stylesheet" href="assets/css/giohang.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="icon" href="assets/imgs/logo/bidvn.ico" type="image/x-icon">
</head>

<body>
	
  <!--  ==========  HEADER  ==========  -->
<?PHP include('assets/php/sections/header.php') ?>
	
<div id="body">
	
	<?PHP include('assets/php/sections/thanhtoan.php'); ?>
</div>
	
	
	<div id="toast-ui-container" class="toast-ui-container">
    </div>
	
  <!--  ==========  JS  ==========  -->
<script type="text/javascript">
	
		function showToast(message, type = 'info', duration = 3000) {
			const container = document.getElementById('toast-ui-container');
			if (!container) return;

			// 1. Tạo phần tử Toast
			const toast = document.createElement('div');
			toast.classList.add('toast-ui-message', `toast-ui-${type}`);

			// Chọn icon dựa trên loại
			let iconClass = '';
			switch(type) {
				case 'success':
					iconClass = 'fas fa-check-circle';
					break;
				case 'error':
					iconClass = 'fas fa-times-circle';
					break;
				case 'warning':
					iconClass = 'fas fa-exclamation-triangle';
					break;
				case 'info':
				default:
					iconClass = 'fas fa-info-circle';
					break;
			}

			// Thêm icon và nội dung
			toast.innerHTML = `<i class="${iconClass}"></i> ${message}`;

			// 2. Thêm vào container và hiển thị
			container.appendChild(toast);

			// Sử dụng setTimeout để đảm bảo transition hoạt động
			setTimeout(() => {
				toast.classList.add('show');
			}, 10);

			// 3. Tự động xóa sau duration
			const hideTimeout = setTimeout(() => {
				// Bắt đầu hiệu ứng ẩn
				toast.classList.remove('show');

				// Đợi transition kết thúc rồi xóa khỏi DOM
				toast.addEventListener('transitionend', () => {
					toast.remove();
				});

			}, duration);

			// Tùy chọn: Xóa toast khi click vào
			toast.addEventListener('click', () => {
				clearTimeout(hideTimeout);
				toast.classList.remove('show');
				toast.addEventListener('transitionend', () => {
					toast.remove();
				});
			});
		}
</script>
</body>
</html>