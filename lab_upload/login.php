<?php
session_start();
$username=$_POST['username'];
$userpass=$_POST['userpass'];
$logout=$_GET['logout'];
if($username&&$userpass&&!$logout){
require("config/config2.php");
$sql="select * from user where username='".$username."' && userpass=SHA1('".$userpass."') limit 1";
$result=mysql_query($sql);
   if(@mysql_num_rows($result)!=0){
       $row=mysql_fetch_array($result);
       session_unset();//ɾ���Ự
       session_destroy();
	   $_SESSION["lab_user_id"]=$row[0];
	   setcookie("lab_user_id",$row[0],(time()+28800),"/");//����8��Сʱ
       header("location: lab_home.php");//session��cookie��û�м�¼
   }
}
if($logout){
   //���ͻ���cookie ����Ϊ��ȥʱ�䣬������
   setcookie("lab_user_id",$_COOKIE['lab_user_id'],time()-604800,"/");
   //ɾ���Ự
   session_unset();
   session_destroy();
}
if($_COOKIE['lab_user_id']){
       header("location: lab_home.php");//session��cookie��û�м�¼
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>ʵ����ҵ�ύϵͳ</title>
<style>
</style>
</head>

<body>
<div style="margin:100px auto; width:400px; height:200px;" id="loginDialog" align="center">
<form action="login.php" method="post">
<p>�û���:<input type="text" name="username"/></p>
<p>��&nbsp;&nbsp;��:<input type="password" name="userpass"/></p>
      <div style="clear:both;"></div>
<p><input type="submit" name="login" value="��¼"/></p>
</form>
</div>
</body>
</html>
