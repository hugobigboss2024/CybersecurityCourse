<meta charset='utf-8'>
<?php
include "../inc/dblink.inc.php"//将数据库连接的文件包含到此文件中
?>
 
<?php
if(isset($_POST['userSubmit'])){
    $userName=$_COOKIE['name'];
    $tmp_path=$_FILES['userFile']['tmp_name'];
    $path=dirname(__FILE__)."\\images\\".$_FILES['userFile']['name'];
    if(move_uploaded_file($tmp_path,$path)){
        $path=mysqli_real_escape_string($link,$path);
        $dbpath = "images/".$_FILES['userFile']['name'];
        $sql="update users set photo='".$dbpath."'where name='".$userName."'";
        if($results=mysqli_query($link,$sql)){
            echo "图片上传成功，<a href='./index.php'>返回个人中心</a>";
        }else{
            die("sql语句有误");
        }
 
    }else{
        echo "图片上传失败";
    }
}else{
    $html=<<<HTML
    <form 
    method="post"
    enctype="multipart/form-data"
    >
    <input type="file" name="userFile"><br/>
    <input type="submit" name="userSubmit" value="提交">
 
    </form>
HTML;
    echo "$html";
}
?>
 
<?php
mysqli_close($link);
?>