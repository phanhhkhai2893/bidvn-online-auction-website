<?PHP
    // Kiểm tra session an toàn
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    include('assets/php/database/db_connection.php');
    $kn = new db_connection();
?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Sản phẩm theo danh mục</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            light: '#FEF08A', 
                            DEFAULT: '#FACC15', 
                            dark: '#EAB308', 
                            hover: '#CA8A04', 
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="assets/css/style.css?v=<?php echo time(); ?>" />
    
    <link rel="icon" href="assets/imgs/logo/bidvn.ico" type="image/x-icon">
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>

<body class="bg-gray-50 text-gray-800">

  <?PHP include('assets/php/sections/header.php') ?>
  
  <?PHP include('assets/php/sections/search_box.php') ?>
   
  <div id="body" class="py-4">
    <div class="container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    
      <div class="mb-4">
          <?PHP include('assets/php/sections/danhmuc_con.php') ?>
      </div>

      <div>
          <?PHP include('assets/php/sections/products_danhmuc.php') ?>
      </div>

    </div>
  </div>
   
  <?PHP include('assets/php/sections/footer.php') ?>
  
</body>
</html>