<meta charset="utf-8">
<?php
include "../inc/dblink.inc.php"//将数据库连接的文件包含到此文件中
?>

<?php
//var_dump($_POST);
if(isset($_COOKIE['name'])) {
    if (isset($_POST['userSubmit'])) {
        $userName = $_COOKIE['name'];
        $userPass1 = $_POST['userPass1'];
        $userPass2 = $_POST['userPass2'];
        if ((bool)($userName) && (bool)($userPass1) && (bool)($userPass2)) {
            if ($userPass1 === $userPass2) {
                $sql2 = "update users set password=md5('" . $userPass1 . "') where name='" . $userName . "'";
                if (!$results2 = mysqli_query($link, $sql2)) {
                    die("SQL语句有误");
                } else {
                    echo "修改成功，<a href='./index.php'>回到首页<a>";
                }
            } else {
                echo "两次密码输入不一致，<a href='./updatePwd.php'>请重新输入<a>";
            }


            $results1 = mysqli_query($link, $sql1);
        } else {
            echo "账号或密码不能为空, <a href='./updatePwd.php'>请重新输入<a>";
        }
    }
}else{
    echo "<a href='./login.php'>请登录</a>";
}
//var_dump((bool)($userName));
//var_dump((bool)($userPass1));
//var_dump((bool)($userPass2));
?>

<?php
mysqli_close($link);
?>
