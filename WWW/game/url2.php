<!DOCTYPE htmL PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/
xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content= "text/html; charset=utf-8" />
<title>URL request</title>
</head>
<body bgcolor="3399FF">
<div style="margin-top:70px;color:#FFF;font-size:23px;text-align:center"> hello </font><br>
<font size="3" color= "#FFFF00">
<?php
    $date=$_GET['date'];
    //连接数据库，主机，用户名，密码，数据库
    $con=mysqli_connect("localhost","root","root","game");
    error_reporting(E_ALL);

    if(!$con)
    {//连接失败会输出error+错误代码
        die( "error:".mysqli_connect_error());
    }
    //数据库查询语句
    $sql = "select * from news where datestr=?";
    echo $sql;
    echo "<br>";
    //查询结果保存在$res对象中
    $stmt=mysqli_prepare($con, $sql);
    //var_ _dump($res);
    //把$res转换成索引数组以便输出到页面
	mysqli_stmt_bind_param($stmt,"s",$date);
	mysqli_stmt_execute($stmt);
	 $re =mysqli_stmt_bind_result($stmt,$title,$content,$datestr,$id);
    
	 if($re) 
	  {
		  while ( mysqli_stmt_fetch ( $stmt )) {
				  echo $title;
                  echo "<br>";
				  echo $content;
                  echo "<br>";
				  echo $datestr;
                  echo "<br>";
				  echo $id;
                  echo "<br><br>";
          }
	  
	  } 
	  else 
	  {
		  
		   echo "没有查询到数据!";
            
	  }
	
   ?>
</font>
</div>
</body>
</html>