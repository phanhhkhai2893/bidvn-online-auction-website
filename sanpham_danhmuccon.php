<?PHP
  session_start();
  include('assets/php/database/db_connection.php');
  $kn = new db_connection();

//	if (isset($_GET['page']))
//	{
//		$pages = array("index", "sanpham_searched", "sanpham_detail");
//		
//		if (in_array($_GET['page'], $pages))
//		{
//			$_page = $_GET['page'];
//		}
//		else
//		{
//			$_page = "index";
//		}
//	}
//	else
//	{
//		$_page = "index";
//	}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
  <title>Untitled Document</title>
  <link rel="stylesheet" href="assets/css/style.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="icon" href="assets/imgs/logo/bidvn.ico" type="image/x-icon">
</head>

<body>
  <!--  ==========  HEADER  ==========  -->
  <?PHP include('assets/php/sections/header.php') ?>
  <div class="banner"></div>  
  <?PHP include('assets/php/sections/search_box.php') ?>
  
  <div id="body">
    <div class="container">
      <!--  ==========  DANH MỤC  ==========  -->
      <?PHP include('assets/php/sections/danhmuc_con.php') ?>

      <!--  ==========  SẢN PHẨM  ==========  -->
      <?PHP include('assets/php/sections/products_danhmuccon.php') ?>

    </div>
  </div>
  
  <!--  ==========  FOOTER  ==========  -->
  <?PHP include('assets/php/sections/footer.php') ?>
</body>
</html>