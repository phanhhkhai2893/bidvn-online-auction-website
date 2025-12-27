<?PHP
    // Kiểm tra session an toàn
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

	include('assets/php/database/db_connection.php');
	$kn = new db_connection();

    // Xử lý biến Session an toàn (Tránh lỗi khi chưa đăng nhập)
    if (isset($_SESSION['SignInError'])) {
        unset($_SESSION['SignInError']);
    }
    
    // Nếu chưa đăng nhập thì gán null, không báo lỗi
    $MaND_real = isset($_SESSION['mand']) ? $_SESSION['mand'] : null;
?>

<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết sản phẩm - BIDVN</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            light: '#FFF9C4', 
                            DEFAULT: '#FBC02D', 
                            dark: '#F9A825', 
                        }
                    }
                }
            }
        }
    </script>

    <link rel="stylesheet" href="assets/css/style.css?v=<?php echo time(); ?>" />
    <link rel="stylesheet" href="assets/css/product_detail.css?v=<?php echo time(); ?>">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="icon" href="assets/imgs/logo/bidvn.ico" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        
        /* CSS cho Toast Notification */
        .toast-ui-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .toast-ui-message {
            background: #fff;
            padding: 12px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            font-weight: 500;
            color: #333;
            border-left: 4px solid #ccc;
            transform: translateX(100%);
            opacity: 0;
            transition: all 0.3s ease-in-out;
            cursor: pointer;
        }
        .toast-ui-message.show {
            transform: translateX(0);
            opacity: 1;
        }
        .toast-ui-success { border-color: #10B981; }
        .toast-ui-success i { color: #10B981; }
        
        .toast-ui-error { border-color: #EF4444; }
        .toast-ui-error i { color: #EF4444; }
        
        .toast-ui-warning { border-color: #F59E0B; }
        .toast-ui-warning i { color: #F59E0B; }
        
        .toast-ui-info { border-color: #3B82F6; }
        .toast-ui-info i { color: #3B82F6; }
    </style>
</head>

<body class="bg-gray-50 text-gray-800">
  
  <?PHP include('assets/php/sections/header.php') ?>
	
  <div id="body" class="py-8">
    <div class="container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    
      <div class="bg-white rounded-2xl shadow-sm p-6 mb-8">
          <?PHP include('assets/php/sections/product_detail.php') ?>
      </div>

      <div class="mt-12">
          <?PHP include('assets/php/sections/index_sanpham.php') ?>
      </div>
      
    </div>
  </div>
   
  <?PHP include('assets/php/sections/footer.php') ?>
    
  <div id="toast-ui-container" class="toast-ui-container"></div>
    
  <script type="text/javascript">
    function showToast(message, type = 'info', duration = 3000) {
        const container = document.getElementById('toast-ui-container');
        if (!container) return;

        const toast = document.createElement('div');
        toast.classList.add('toast-ui-message', `toast-ui-${type}`);

        let iconClass = 'fa-info-circle';
        switch(type) {
            case 'success': iconClass = 'fa-check-circle'; break;
            case 'error':   iconClass = 'fa-times-circle'; break;
            case 'warning': iconClass = 'fa-exclamation-triangle'; break;
        }

        toast.innerHTML = `<i class="fas ${iconClass} text-lg"></i> <span>${message}</span>`;
        container.appendChild(toast);

        // Animation show
        requestAnimationFrame(() => {
            toast.classList.add('show');
        });

        // Auto hide
        const hideTimeout = setTimeout(() => {
            hideToast(toast);
        }, duration);

        // Click to hide
        toast.addEventListener('click', () => {
            clearTimeout(hideTimeout);
            hideToast(toast);
        });
        
        function hideToast(el) {
            el.classList.remove('show');
            el.addEventListener('transitionend', () => el.remove());
        }
    }
  </script>
</body>
</html>