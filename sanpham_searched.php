<?PHP
  session_start();
  include('assets/php/database/db_connection.php');
  $kn = new db_connection();

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
	<title>Tìm kiếm sản phẩm</title>
	<link rel="stylesheet" href="assets/css/style.css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<link rel="icon" href="assets/imgs/logo/bidvn.ico" type="image/x-icon">
</head>

<body>
  <!--  ==========  HEADER  ==========  -->
  <?PHP include('assets/php/sections/header.php') ?>
  <?PHP include('assets/php/sections/search_box.php') ?>
  
  <div id="body">
    <div class="container">

      <!--  ==========  DANH MỤC  ==========  -->
      <?PHP include('assets/php/sections/index_danhmuc.php') ?>

      <!--  ==========  DANH MỤC  ==========  -->
      <?PHP include('assets/php/sections/products_searched.php') ?>
    </div>
  </div>
  
  <!--  ==========  FOOTER  ==========  -->
  <?PHP include('assets/php/sections/footer.php') ?>
</body>
</html>