<?php
$dbHost="127.0.0.1";
$dbUser="root";
$dbPass="root";
$dbName="jrlt";
$link=mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);//建立数据库连接
if(!$link){
    die(mysqli_connect_error());//捕获数据库连接时的错误信息
}
mysqli_set_charset($link,"utf-8");
?>