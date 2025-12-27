<?php
// ----------------------------------------------------
// PHẦN LOGIC XỬ LÝ FORM VÀ BẢO MẬT
// ----------------------------------------------------

// --- LOGIC ĐĂNG NHẬP (POST) ---
if (isset($_POST['dangnhap'])) {
    $ten = $_POST['tenDN'];
    $mk = $_POST['pass'];
    
    // FIX: Chuyển sang Prepared Statements (Bảo mật)
    $sql = "SELECT MaND, hoten, phanquyen FROM nguoi_dung WHERE username = ? AND password = ?";
    $stmt = $kn->con->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("ss", $ten, $mk);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $_SESSION['role'] = $user['phanquyen'];
            $_SESSION['tennd'] = $user['hoten'];
            $_SESSION['mand'] = $user['MaND'];
            
            echo "<script>alert('Đăng nhập thành công!');</script>";
            
            echo "<meta http-equiv='refresh' content='0; url=index.php' />";
            exit();
        } else {
            echo "<script>alert('Tên đăng nhập hoặc mật khẩu chưa đúng!');</script>";
			
            $_SESSION['login_username'] = $ten;
			
            echo "<meta http-equiv='refresh' content='0; url=index.php?show=login' />";
            exit();
        }
    }
}

// --- LOGIC ĐĂNG KÝ (POST) ---
if (isset($_POST['dangky'])) {
    $ten = $_POST['dk-hoten'];
    $usn = $_POST['dk-usn'];
    $mk = $_POST['dk-pass'];
    $cfmk = $_POST['dk-confirm-pass'];
    $role = 'Người mua'; // Thiết lập mặc định
    
    // Khai báo các trường rỗng
    $email = ''; $sdt = ''; $diachi = ''; $avarta = 'default_user'; $trangthai = 'Hoạt động';

    if ($mk !== $cfmk) {
		echo "<script>alert('Xác nhận mật khẩu không khớp!');</script>";
		echo "<meta http-equiv='refresh' content='0; url=index.php?show=register' />";
        exit();
    }
    
    // 1. Lấy ID tiếp theo
    $sql_count = "SELECT COUNT(MaND) as total FROM nguoi_dung";
    $res_count = mysqli_query($kn->con, $sql_count);
    $total_users = mysqli_fetch_assoc($res_count)['total'];
    $mand = "ND" . ($total_users + 1);

    // 2. INSERT SECURELY
    $sql = "
        INSERT INTO nguoi_dung (MaND, username, password, hoten, email, sdt, diachi, avarta, phanquyen, ngaydangky, trangthai) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?)
    ";
    $stmt = $kn->con->prepare($sql);
    
    if ($stmt) {
        // Bind 11 tham số (sssssssssss)
        $stmt->bind_param("ssssssssss", $mand, $usn, $mk, $ten, $email, $sdt, $diachi, $avarta, $role, $trangthai);
        
        if ($stmt->execute()) {
			echo "<script>alert('Đăng ký thành công!');</script>";
			echo "<meta http-equiv='refresh' content='0; url=index.php?show=login' />";
        } else {
			echo "<script>alert('Đăng ký thất bại. Username có thể đã tồn tại!');</script>";
			echo "<meta http-equiv='refresh' content='0; url=index.php?show=register' />";
        }
        $stmt->close();
        exit();
    }
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Đăng nhập / Đăng ký</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
/* Đảm bảo CSS cho Toast và Modal ở đây hoặc ở file style.css */
.toast-ui-container { position: fixed; top: 20px; right: 20px; z-index: 9999; display: flex; flex-direction: column; gap: 10px; }
.toast-ui-message { background: #fff; padding: 12px 20px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); display: flex; align-items: center; gap: 10px; font-size: 14px; color: #333; border-left: 4px solid #ccc; transform: translateX(100%); opacity: 0; transition: all 0.3s ease-in-out; }
.toast-ui-message.show { transform: translateX(0); opacity: 1; }
.toast-ui-success { border-color: #10B981; color: #10B981; } 
.toast-ui-error { border-color: #EF4444; color: #EF4444; } 
.toast-ui-warning { border-color: #F59E0B; color: #F59E0B; } 
.hidden { display: none !important; }
</style>
</head>

<body>
    <div id="toast-ui-container" class="toast-ui-container"></div>
    <div id="auth-modal" class="modal hidden"> <div class="modal-content">
            <form method="post" id="login-form" class="auth-form">
                <h2>Đăng nhập</h2>
                
                <div id="login-error-message" class="error-box p-3 bg-red-100 text-red-700 rounded-lg text-sm hidden">
                    <i class="fa fa-exclamation-triangle" aria-hidden="true"></i><p class="inline ml-2">Tên đăng nhập hoặc mật khẩu chưa đúng, vui lòng kiểm tra lại!</p>
                </div>
                
                <label for="login-email">Tên đăng nhập:</label>
                <input name='tenDN' type="text" id="login-email" value="<?php echo isset($_SESSION['login_username']) ? htmlspecialchars($_SESSION['login_username']) : ''; unset($_SESSION['login_username']); ?>" required>
                
                <label for="login-password">Mật khẩu:</label>
                <input name='pass' type="password" id="login-password" required>
                
                <button name='dangnhap' type="submit" >Đăng nhập</button>
                
                <div class="separator-text"><span>Hoặc</span></div>
                <button onClick="window.location.href='<?php echo $_SERVER['PHP_SELF']; ?>?show=register';" class="signup-btn" type="button" >Đăng ký</button>
            </form>

            <form method="post" id="register-form" class="auth-form hidden">
                <h2>Đăng ký tài khoản mới</h2>
                
                <label for="register-name">Họ tên:</label>
                <input type="text" name="dk-hoten" id="register-name" required>
                
                <label for="register-usn">Username:</label>
                <input type="text" name="dk-usn" id="register-usn" required>
                
                <label for="register-password">Mật khẩu:</label>
                <input type="password" name="dk-pass" id="register-password" required>
                
                <label for="register-confirm-pass">Xác nhận mật khẩu:</label>
                <input type="password" name="dk-confirm-pass" id="register-confirm-pass" required>
                
                <input type="hidden" name="dk-role" value="Người mua">
                
                <button name="dangky" type="submit">Đăng ký</button>
                
                <div class="separator-text"><span>Hoặc</span></div>
                <button onClick="window.location.href='<?php echo $_SERVER['PHP_SELF']; ?>?show=login';" class="signup-btn" type="button" >Đăng nhập</button>
            </form>
        </div>
    </div>

    <script>
		
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

        toast.innerHTML = `<i class="fas ${iconClass}"></i> <span>${message}</span>`;
        container.appendChild(toast);

        setTimeout(() => { toast.classList.add('show'); }, 10);

        const hideTimeout = setTimeout(() => {
            hideToast(toast);
        }, duration);

        toast.addEventListener('click', () => {
            clearTimeout(hideTimeout);
            hideToast(toast);
        });

        function hideToast(el) {
            el.classList.remove('show');
            el.addEventListener('transitionend', () => el.remove());
        }
    }
		
	document.addEventListener('DOMContentLoaded', function() {
		const loginForm = document.getElementById('login-form');
		const registerForm = document.getElementById('register-form');
		const urlParams = new URLSearchParams(window.location.search);

		// --- LOGIC SWITCH FORM ---
		if (urlParams.get('show') === 'register') {
			loginForm.classList.add('hidden');
			registerForm.classList.remove('hidden');
		} else {
			loginForm.classList.remove('hidden');
			registerForm.classList.add('hidden');
		}

		// --- HIỂN THỊ TOAST TỪ SESSION ---
		<?php if (isset($_SESSION['toast_status'])): ?>
			showToast('<?php echo $_SESSION['toast_status']['message']; ?>', '<?php echo $_SESSION['toast_status']['type']; ?>', 4000);
			<?php unset($_SESSION['toast_status']); ?>
		<?php endif; ?>
	});
    </script>
	
	<script type="text/javascript" src="assets/js/dangnhap.js"></script>
</body>
</html>