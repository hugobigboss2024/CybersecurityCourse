<meta charset="utf-8">
<?php
if(isset($_COOKIE['name'])) {
?>
<html>
<head>
    <meta charset="utf-8">
    <title>  注册----今日论坛</title>
 
</head>
<body>
    <h1>今日论坛BBS</h1>
    <form 
        action="./updatePwd.php"
        method="post"
    >
        密码：<input type="password" name="userPass1"><br/>
        确认密码：<input type="password" name="userPass2"><br/>
        <input type="submit" name="userSubmit"  value="修改">
    </form>
    <hr/>
</body>
</html>
<?php
}else{
    echo "<a href='./login.php'>请登录</a>";
}
?>