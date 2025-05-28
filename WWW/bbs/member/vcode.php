<?php
$str="";
for($i=0;$i<5;$i++){
    $str.=chr(rand(97,122));
    setcookie("vcode",$str);
}
    echo "<div style='background-color:red'>$str</div>";
?>
