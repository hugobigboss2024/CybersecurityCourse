<?php
    include "../inc/dblink.inc.php"//将数据库连接的文件包含到此文件中
?>

<html>
<head>
    <meta charset='utf-8'>
    <title>今日论坛--个人中心</title>
</head>
<body>

    <h1>今日论坛--个人中心</h1>
    <?php
    if(isset($_COOKIE['name'])){
        $userName=$_COOKIE['name'];
        $sql="select * from users where name='". $userName."'";
        if($results=mysqli_query($link,$sql)){
            if(mysqli_num_rows($results)>0){
                $result=mysqli_fetch_assoc($results);
                echo "<hr/>";
                echo "欢迎来到您的个人中心，".$_COOKIE['name'] ."！ <a href='../index.php'>返回首页</a> ";
                echo "<a href='./logout.php'>注销</a><br/>";
                echo "<hr/>";
                echo "<h3>个人信息</h3>";
                echo "帐号名：".$_COOKIE['name'] ."<br/>";
                echo "您的头像是<img src='".$result['photo']."'/> ";
                echo "<a href='./updatePhoto.php'>修改头像</a> <br/>";
                echo "<a href='./cgpwd.php'>修改密码</a> <br/>";
                echo "帐户余额：".$result['money']." <span style='color:red;'>请联系管理员</span>";
            }else{
                die("该用户不存在");
            }
        }else{
            die("sql语句有误");
        }
    }else{
        echo "<a href='./login.php'>请登录</a>";
    }
    ?>
</body>
</html>

<?php
    mysqli_close($link);
?>
