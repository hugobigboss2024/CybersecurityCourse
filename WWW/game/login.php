<0xa0><?php
    //连接数据库，主机，用户名，密码，数据库
	header("Content-type: text/html; charset=utf-8");
    $con=mysqli_connect("localhost","root","root","game");
    if(!$con)
    {//连接失败会输出error+错误代码
        die( "error:".mysqli_connect_error());
    }
	#mysql_select_db("game",$con);
    //把用户在Index.html输入的账号和密码保存在$user和$pass2个变量中
    $user=$_GET['user'];
    $pass=$_GET['pass'];
    #数据库查询语句
    $sql="select * from user where username = '$user' and password = '$pass'";
    #echo $sql;
    echo "<br>";
    //查询结果保存在$res对象中
    $res=mysqli_query($con,$sql);
    //var_ _dump($res);
    //把$res转换成索引数组以便输出到页面
    $row=mysqli_fetch_array( $res);
    do {
        if(!is_null($row))
        {
            for($i=0;$i<count($row);$i++)
            {
                echo $row[$i];
                echo "<br>";
            }
        }
        else
        {
            echo "用户名或密码错误!";
            break;
        }
    }
    while( $row=mysqli_fetch_array($res));
 ?>
</font>
</div>
</body>
</html>