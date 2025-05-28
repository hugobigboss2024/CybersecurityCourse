<!DOCTYPE htmL PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/
xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content= "text/html; charset=utf-8" />
<title>URL request</title>
</head>
<body bgcolor="3399FF">
<div style="margin-top:70px;color:#FFF;font-size:23px;text-align:center"> 游戏公告/新闻 </font><br>
<font size="3" color= "#FFFF00">
<?php
  
	 //error_reporting(0);

	
    $date=$_GET['date'];
    //连接数据库，主机，用户名，密码，数据库
    $con=mysqli_connect("localhost","root","root","game");
   

    if(!$con)
    {//连接失败会输出error+错误代码
        die( "error:".mysqli_connect_error());
    }
    //数据库查询语句
    $sql = "select * from news where datestr='$date' ";
    echo $sql;
    echo "<br>";
    //查询结果保存在$res对象中
    $res=mysqli_query($con, $sql);
    //var_ _dump($res);
    //把$res转换成索引数组以便输出到页面
	
    $row=mysqli_fetch_array($res);
    do {
        if(!is_null($row))
        {
            for($i=0;$i<count($row);$i++)
            {
				if(isset($row[$i])) {
					echo $row[$i];
					echo "<br>";
				}
            }
        }
        else
        {
            echo "没有查询到数据!";
            break;
        }
    }
    while( $row=mysqli_fetch_array($res)); 
	?>
</font>
</div>
</body>
</html>