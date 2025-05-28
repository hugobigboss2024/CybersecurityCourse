<?php
include "../inc/dblink.inc.php"//将数据库连接的文件包含到此文件中
?>
<html>
<head>
    <meta charset="utf-8">
    <title>  登录----今日论坛</title>

</head>
<body>
    <h1>今日论坛BBS</h1>
    <?php
    if(isset($_POST['userSubmit'])){
        if($_POST['vcode']==$_COOKIE['vcode']){
            $userName=$_POST['userName'];
            $userPass=$_POST['userPass'];
            $sql="select * from users where name='".$userName."' && password='".md5($userPass)."'";
            if($results=mysqli_query($link,$sql)){
                if(mysqli_num_rows($results)>0){
                    setcookie('name',$userName,time()+3600*24, "/bbs");
                    //注意cookie的路径，不同路径的cookie认为是两条cookie
                    echo "登录成功，返回<a href='../index.php'>首页</a>或<a href='./index.php'>个人中心</a>";
                }else{
                    echo "用户名或密码错误，<a href='./login.php'>请重新登录</a>";
                }
            }else{
                die("sql语句有误");
            }

        }else{
            echo "验证码错误，<a href='./login.php'>请重新登录</a>";
        }
    }else{
        $html=<<<HTML
       <form 
        method="post">
        用户名：<input type="text" name="userName"><br/>
        密码：<input type="password" name="userPass"><br/>
        验证码：<input type="text" name="vcode"> 
        <iframe src= "./vcode.php" width="100" height=30 frameboder="0"></iframe><br>
        <input type="submit" name="userSubmit"  value="登录">
    </form>
HTML;
        echo $html;
    }
    ?>
    <hr/>
</body>
</html>

<?php
mysqli_close($link);
?>
