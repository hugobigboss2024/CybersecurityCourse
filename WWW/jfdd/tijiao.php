<?
 session_start();
 $_SESSION['fsess']=($_SESSION['fsess'])?$_SESSION['fsess']:time();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>��ֹ���ظ��ύ</title>
<SCRIPT language=Javascript type=text/javascript>
<!--
//*****Javascript���ظ��ύ************
var frm_submit=false;   //��¼�ύ״̬
function check_form(fobj) {
 var error = 0;
    var error_message = "";
 if (fobj.formtext.value=="")
 {
  error_message = error_message + "formtext ����Ϊ��.\n";
  error = 1;
 }
 
 if (frm_submit==true) {
  error_message = error_message + "������Ѿ��ύ.\n�����ĵȴ������������������.\n\n";
  error=1;
 }
 
 if (error == 1) {
   alert(error_message);
   return false;
 } else {
   frm_submit=true;  //�ı��ύ״̬
   return true;
 }
}
-->
</script>
</head>
<body>
Javascript�ͷ������� ˫�ط�ֹ���ظ��ύ��ʾ
<br/>
<br/>
����ʱ�䣺<? echo date("Y-m-d H:i:s"); ?>
<br/>
<br/>
<?
if($_POST["faction"]=="submit"||$_GET["faction"]=="submit"){
 //�ύ����
 
 //*****�������˷��ظ��ύ*******************
 //���POST�����ı�����ʱ����SESSION����ı�����ʱ��
 //��ͬ��Ϊ�����ύ
 //����ͬ��Ϊ�ظ��ύ
 if($_SESSION["fsess"]==$_POST["fpsess"]){
  $_SESSION["fsess"]=time();
  echo  "�ύ���ݣ�<br/>\n";
  echo  $_POST["fpsess"]."<br/>\n";;
  echo  $_POST["formtext"];
  echo "</body></html>";
  exit;
 } else {
  echo  "�ظ��ύ���˳���������<br/>\n";
  echo "</body></html>";
  exit;
 }
} 
//$_SESSION["fsess"]=time();
?>
<form name="f_info" action="" method="post"  onSubmit="return check_form(this);">
<input name="fpsess" type="hidden" value="<? echo $_SESSION["fsess"]; ?>" />
<!-- ���������ʱ�� -->
<input name="faction" type="hidden" value="submit" />
<input name="formtext" id="formtext" type="text" value="" />
<input type="submit" value="�ύ" />
<input  type="reset" value="����" />
</form>
</body>
</html>
