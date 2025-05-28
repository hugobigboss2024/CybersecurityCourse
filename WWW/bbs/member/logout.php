<meta charset="utf-8">
<?php
if(setcookie('name',$_COOKIE['name'],time()-3600,"/bbs")){
    //注意cookie的路径，不同路径的cookie认为是两条cookie
    echo "注销成功，<a href='../index.php'>返回首页</a>";
}else{
    die("error");
}
?>