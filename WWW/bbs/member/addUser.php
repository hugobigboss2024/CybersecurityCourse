<meta charset="utf-8">
<?php
include "../inc/dblink.inc.php"//将数据库连接的文件包含到此文件中
?>

<?php
//var_dump($_POST);
if(isset($_POST['userSubmit'])){
    $userName=mysqli_real_escape_string($link,$_POST['userName']);
    $userPass1=mysqli_real_escape_string($link,$_POST['userPass1']);
    $userPass2=mysqli_real_escape_string($link,$_POST['userPass2']);
    if((bool)($userName) && (bool)($userPass1) && (bool)($userPass2)){
        // 查询数据库中是否有此用户，若无则新增，若有则要求重新输入
        $sql1="select * from users where name='".$userName."'";
        if(!$results1=mysqli_query($link,$sql1)){
            die("SQL语句有误");
        }else{
            if(!mysqli_num_rows($results1)){//非空往数据库中增加
                if($userPass1===$userPass2){
                    $sql2="insert into users(name, password) values('".$userName."', md5('".$userPass1."'))";
                    if(!$results2=mysqli_query($link,$sql2)){
                        die("SQL语句有误");
                    }else{
                        echo "注册成功，<a href='./login.php'>请登录<a>";
                    }
                }else{
                    echo "两次密码输入不一致，<a href='./register.php'>请重新注册<a>";
                }
            }else{
                echo "用户名已存在，<a href='./register.php'>请重新注册<a>";
            }
        }
        $results1=mysqli_query($link,$sql1);
    }else{
        echo "账号或密码不能为空, <a href='./register.php'>请重新注册<a>";
    }
}else{
    header("Location:./register.php");
}
//var_dump((bool)($userName));
//var_dump((bool)($userPass1));
//var_dump((bool)($userPass2));
?>

<?php
mysqli_close($link);
?>
