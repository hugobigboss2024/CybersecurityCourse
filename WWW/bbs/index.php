<?php
include "./inc/dblink.inc.php"//将数据库连接的文件包含到此文件中
?>

<html>
<head>
    <meta charset="utf-8">
    <title>  首页----今日论坛</title>

</head>
<body>
    <h1>今日论坛BBS</h1>
    <?php
    if(isset($_COOKIE['name'])){
        echo "欢迎来到今日论坛BBS，".$_COOKIE['name']."<br/>";
        echo "<a href='./member/index.php'>个人中心</a> ";
        echo "<a href='./member/logout.php'>注销</a> ";
    }else{
       echo " <a href='./member/register.php'>注册</a> ";
        echo "<a href='./member/login.php'>登录</a> ";
    }
    echo "<a href='./addMessage.php'>我要留言</a>";
    ?>
    <hr/>
    <h3>留言板</h3>
    <?php
    $sql="select * from messages";
    if($results=mysqli_query($link,$sql)){
        if(mysqli_num_rows($results)>0){
            echo "<table border=2>";
            echo "<tr><td>ID</td><td>AUTHOR</td><td>TITLE</td></tr>";
            while($result=mysqli_fetch_assoc($results)){
                echo "<tr><td>{$result['id']}</td><td>{$result['uname']}</td>
                <td><a href='showmessage.php?id={$result['id']}' target='_blank'>{$result['title']}</a></td></tr>";
            }
            echo "</table>";
        }else{
            echo "暂无留言内容";
        }
    }else{
        echo mysqli_error($link);
    }
    ?>
</body>
</html>

<?php
mysqli_close($link);
?>
