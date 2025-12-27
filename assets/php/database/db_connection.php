<?PHP date_default_timezone_set('Asia/Ho_Chi_Minh');
  class db_connection
  {
    public $con, $rs;
    
    function __construct()
    {
      $this -> con = mysqli_connect("localhost", "root", "");
      if (!$this -> con)
      {
        die("Lỗi kết nối: " . mysqli_errno($this -> con) . " - " . mysqli_error($this -> con));
      }
      else
      {
        if (!mysqli_select_db($this -> con, 'bidvn_database'))
        {
          die("Lỗi DTB: " . mysqli_errno($this -> con) . " - " . mysqli_error($this -> con));
        }
        else
        {
          mysqli_query($this -> con, "set names 'utf8'");
        }
      }
    }
	  
    function truyvan_sql($sql)
	{
		if(!$this->rs=mysqli_query($this->con,$sql))
		   die("loi co truy van du lieu ".mysqli_errno($this->con). "- ".mysqli_error($this->con) );	
		else
			return $this->rs;
	}
    
    function close_conn()
    {
      mysqli_close($this -> con);
    }
  }
?>