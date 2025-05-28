<?php
include "./inc/dblink.inc.php"//将数据库连接的文件包含到此文件中
?>
 
<html>
<head>
<meta charset = "utf-8">
<title>留言论坛</title>
</head>
<body>
<h1>留言内容</h1><a href = './index.php'>返回首页</a><hr />
<?php
if(isset($_GET['id'])){
    $id=$_GET['id'];
    $sql="select * from messages where id=".$id;
    if($results=mysqli_multi_query($link,$sql)){
        $result=mysqli_store_result($link);
		$row = mysqli_fetch_row($result);
        echo $row[1].":".$row[2]."<hr/>";
        echo $row[3];
    }else{
        echo mysqli_error($link);
    }
}else{
    echo "id error";
}
?>
</body>
</html>
<?php
mysqli_close($link);
?>