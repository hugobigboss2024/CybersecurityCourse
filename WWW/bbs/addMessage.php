<?php
include "./inc/dblink.inc.php"//将数据库连接的文件包含到此文件中
?>
<html>
<head>
<meta charset = "utf-8">
<title>留言论坛</title>
</head>
<body>
<?php
if(isset($_COOKIE['name'])){
    $html=<<<HTML
    <form 
    method="post">
    标题：<input type="text" name="userTitle"><br/>
    留言内容：<br/>
    <textarea name="userContent"></textarea>
    <input type="submit" name="userSubmit" value="提交">
    </form>
HTML;
    echo $html."<br>";
    if(isset($_POST['userSubmit']) && isset($_POST['userTitle'])){
        $userName=$_COOKIE['name'];
//        $title=mysqli_real_escape_string($link,$_POST['userTitle']) ;//将提交的文本进行转义
//        $content=mysqli_real_escape_string($link,$_POST['userContent']);
        $title=$_POST['userTitle'] ;
        $content=$_POST['userContent'];
        $sql="INSERT INTO `messages`( `uname`, `title`, `content`) VALUES 
        ('".$userName."','".$title."','".$content."')";
        if($results=mysqli_query($link,$sql)){
            echo "留言成功，<a href='./index.php'>返回首页</a>";
        }else{
            echo mysqli_error($link);
        }
    }else{
        echo "请提交";
    }
}else{
    echo "您还未登录，<a href='./member/login.php'>请登录</a>";
}

?>
</body>
</html>
<?php
mysqli_close($link);
?>
