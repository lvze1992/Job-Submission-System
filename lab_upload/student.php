<?php
session_start();
if(!isset($_SESSION["lab_user_id"])){ //判断当前会话变量是否注册
   if(!isset($_COOKIE["lab_user_id"]))//查cookie
   {
     header("location: login.php");//session和cookie都没有记录
   }
}
require("config/config2.php");
/******************************修改密码，修改名字**************************************/
$error="";
$success="";
$display_message="";
// 系统允许上传文件的类型.
$allow_types=array("jpg","gif","png","zip","rar","txt","doc","docx","bmp","mp3","mp4","7z","avi","ico","torrent");

if($_SERVER['REQUEST_METHOD']=='POST'){
    if(isset($_POST['change_user_name'])&&$_POST['user_name']!=""){
	    if($_SESSION['user_name']!=$_POST['user_name']){
		    $_SESSION['user_name']=$_POST['user_name'];
			$sql="update user set user_reallname='".$_POST['user_name']."' where user_id='".$_COOKIE['lab_user_id']."' limit 1";
			if(mysql_query($sql)){
				$success.="<b>修改姓名成功：</b>".$_POST['user_name']."<br/>";
			}
			else{
				$error.="<b>提示：</b>修改姓名失败，请刷新重试<br/>";
			}
		}
		else{
				$error.="<b>提示：</b>请勿重新提交！<br/>";
		}
	}
	if(isset($_POST['change_user_pass'])&&$_POST['old_pass']!=""&&$_POST['new_pass']!=""){
		if($_SESSION['new_pass']!=$_POST['new_pass']){
			$_SESSION['new_pass']=$_POST['new_pass'];
	
			$sql="select user_id from user where userpass=SHA1('".$_POST['old_pass']."') and user_id='".$_COOKIE['lab_user_id']."' limit 1";
			$result=mysql_query($sql);
			if(@mysql_num_rows($result)!=0){
				$sql="update user set userpass=SHA1('".$_POST['new_pass']."') where user_id='".$_COOKIE['lab_user_id']."' limit 1";
				if(mysql_query($sql)){
					$success.="<b>修改密码成功，请记住你的新密码：</b>".$_POST['new_pass']."<br/>";
				}
				else{
					$error.="<b>提示：</b>修改密码失败，请刷新重试<br/>";
				}
			}
			else{
				$error.="<b>提示：</b>原密码错误！请重新输入<br/>";
			}
		}
		else{
				$error.="<b>提示：</b>请勿重新提交！<br/>";
		}

	}
	$display_message=$success.$error;
	if(isset($_POST['submit_button'])){
	    $work_id=$_GET['work_id'];
		$work_submit=$_GET['work_submit'];
		$sql="select work_url from work where work_id=".$work_id;
		$result=mysql_query($sql);
		if(@mysql_num_rows($result)!=0){
		    $row=mysql_fetch_array($result);
		/***************************上传作业*****************************************/

// 单个文件限制
$max_file_size="5120";

// 所有文件限制
$max_combined_size="20000";

// 上传数量
$file_uploads="8";

// 网站名称
$websitename="n";
preg_match_all("/[0-9]+/",$row[0],$arr);//为何直接写入地址出错？？？？？
// 完整的网址和目录，最后用http://localhost/hbyun/test_test/zlfjscxz_upload/index.php/
$full_url="down/".$arr[0][0]."/".$arr[0][1]."/";

// 系统上存储路径最后用/.
$folder="./down/".$arr[0][0]."/".$arr[0][1]."/";

// 随机文件梦? true=使用 (推荐选择这个), false=使用原来文件名
// 随机文件名可以防止重复文件，如使用false有相同的文件无法上传.
$random_name=false;


// 只有使用完整的服务器路径踩设置这个。一般请留空。用/结束.
$fullpath="";

//设置一个密码，访问者必须输入正确的密码才可以上传.

/*
//================================================================================
* ! ATTENTION !
//================================================================================
: Don't edit below this line.
*/

// Initialize variables
$password_hash=md5($password);
$error="";
$success="";
$file_ext=array();
$password_form="";

// Function to get the extension a file.
function get_ext($key) { 
	$key=strtolower(substr(strrchr($key, "."), 1));
	$key=str_replace("jpeg","jpg",$key);
	return $key;
}

// Filename security cleaning. Do not modify.
function cln_file_name($string) {
	$cln_filename_find=array("/\.[^\.]+$/", "/\s\s+/", "/[-]+/", "/[_]+/");
	$cln_filename_repl=array(""," ", "-", "_");
	$string=preg_replace($cln_filename_find, $cln_filename_repl, $string);
	return trim($string);
}

// If a password is set, they must login to upload files.
// Dont allow submit if $password_form has been populated
if(($_POST['submit']==true)) {

	//Tally the size of all the files uploaded, check if it's over the ammount.	
	if(array_sum($_FILES['file']['size']) > $max_combined_size*1024) {
		
		$error.="<b>提醒：</b> 您上传的文件过大系统无法支持。<br />";

	// Loop though, verify and upload files.
	} else {

		// Loop through all the files.
		For($i=0; $i <= $file_uploads-1; $i++) {
			
			// If a file actually exists in this key
			if($_FILES['file']['name'][$i]) {

				//Get the file extension
				$file_ext[$i]=get_ext($_FILES['file']['name'][$i]);
				
				// Randomize file names
				if($random_name){
					$file_name[$i]=time()+rand(0,100000);
				} else {
				    $rand=time()+rand(0,100000);
					$file_name[$i]=cln_file_name($_FILES['file']['name'][$i])."_".$rand;
				}
	
				// Check for blank file name
				if(str_replace(" ", "", $file_name[$i])=="") {
					
					$error.= "<b>提醒：</b> ".$_FILES['file']['name'][$i]." 文件名为空白无法上传。<br />";
				
				//Check if the file type uploaded is a valid file type. 
				}
				else if($file_name[$i]!=$_GET['username']."_".$_GET['name']."_".$_GET['work_name']."_".$rand){
				    $error.= "<b>提醒：</b> ".$_FILES['file']['name'][$i]." 文件名不规范<br/>应为 <b>".$_GET['username']."_".$_GET['name']."_".$_GET['work_name']."【复制】【请确认已修改姓名】<b><br />";
				}
				else if(!in_array($file_ext[$i], $allow_types)) {
								
					$error.= "<b>提醒：</b> ".$_FILES['file']['name'][$i]." 您上传的文件类型系统无法识别。<br />";
								
				//Check the size of each file
				} else if($_FILES['file']['size'][$i] > ($max_file_size*1024)) {
					
					$error.= "<b>提醒：</b> ".$_FILES['file']['name'][$i]." 您上传的文件过大！<br />";
					
				// Check if the file already exists on the server..
				} else if(file_exists($folder.$file_name[$i].".".$file_ext[$i])) {
	
					$error.= "<b>提醒：</b> ".$_FILES['file']['name'][$i]." 上传错误，您上传的文件已经存在。<br />";
					
				}else{
					$nameTMP = iconv('UTF-8', 'GBK', $file_name[$i]);
/************************************************删除过去提交的作业********************************************************/
                    $sql="select workdone_url from workdone where user_id=".$_COOKIE["lab_user_id"]." and work_id=".$work_id;
					$result=mysql_query($sql);
					if(@mysql_num_rows($result)!=0){
					    $row=mysql_fetch_array($result);
						$delfile = iconv('UTF-8', 'GBK', $row[0]);
						if (!unlink($delfile))
						  {
						  $error.= "<b>提醒：</b> 旧文件".$delfile."删除失败，".$row[0]."但新文件成功上传。<br />";
						  }
						  else{
						  $success.="更新成功！<br/>";
						  }
					}
/************************************************删除过去提交的作业********************************************************/					
					If(move_uploaded_file($_FILES['file']['tmp_name'][$i],$folder.$nameTMP.".".$file_ext[$i])) {
						$success.="名称：
                        <a.".$file_ext[$i]."\" target=\"_blank\">".$_FILES['file']['name'][$i]."</a><br />";
						$success.="连接：
                        <a class=\"am-badge am-badge-success\" href=\"".$full_url.$file_name[$i].".".$file_ext[$i]."\" target=\"_blank\">".$full_url.$file_name[$i].".".$file_ext[$i]."</a><br />";
/************************************************作业提交写入数据库********************************************************/
						if($_GET['work_submit']=="submit"){
                            $sql="insert into workdone(user_id,work_id,work_done,workdone_url) values(".$_COOKIE["lab_user_id"].",".$work_id.",1,'".$full_url.$file_name[$i].".".$file_ext[$i]."')";
							if(!mysql_query($sql))
							    $error.="<b>提醒:</b> ".$_FILES['file']['name'][$i]." 异常错误，未写入数据库！<br />";
						    $sql="update work set work_done_amount=work_done_amount+1 where work_id=".$work_id;
							if(!mysql_query($sql))
							    $error.="<b>提醒:</b> ".$_FILES['file']['name'][$i]." 异常错误，未写入数据库！<br />";

						}
						else if($_GET['work_submit']=="update"){
						    $sql="update workdone set work_done=work_done+1,workdone_url='".$full_url.$file_name[$i].".".$file_ext[$i]."' where user_id=".$_COOKIE["lab_user_id"]." and work_id=".$work_id;
							if(!mysql_query($sql))
							    $error.="<b>提醒:</b> ".$_FILES['file']['name'][$i]." 异常错误，未写入数据库！<br />";
						}
/************************************************作业提交写入数据库********************************************************/
						
						
					} else {
						$error.="<b>提醒:</b> ".$_FILES['file']['name'][$i]." 可能由于网络原因上传失败请重试！<br />";
					}
					
				}
							
			} // If Files
		
		} // For
		
	} // Else Total Size
	
	if(($error=="") AND ($success=="")) {
		$error.="<b>提醒：</b> 请按规则上传您的文件 <br />";
	}

} // $_POST AND !$password_form
		/***************************上传作业*****************************************/
		}//作业存在路径
		else{
		   $error="服务器异常"; 
		}
		$display_message=$success.$error;

	}
}
/******************************修改密码，修改名字END**************************************/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>实验作业提交系统</title>
<style>
body{  font:12px/18px Tahoma, Helvetica, Arial, Verdana, "\5b8b\4f53", sans-serif; margin:0; padding:0;}
a:link {
color:#003366;text-decoration:none
}     /* 未被访问的链接     蓝色 */
a:visited {
color: #003366;text-decoration:none;
}  /* 已被访问过的链接   蓝色 */
a:hover {
color: #003366;text-decoration:none;
}    /* 鼠标悬浮在上的链接 蓝色 */
a:active {
color: #003366;text-decoration:none;
}   /* 鼠标点中激活链接   蓝色 */
table#user_infor_table{
width:700px;
margin:0 auto;
}
td.user_infor_td{
width:100px;
height:50px;
border-bottom:1px solid #CCCCCC;
font-size:16px;
font-family:Arial, Helvetica, sans-serif;
text-align:center;
}
td.user_infor_td_bottom{
width:100px;
height:50px;
font-size:16px;
font-family:Arial, Helvetica, sans-serif;
text-align:center;

}

td#user_infor_td_2{
width:150px;
border:1px solid #CCCCCC;

}
#message{
background-color:#00FF66;
display:block;
width:300px;
text-align:center;
margin:0 auto;
}
table#user_work_table{
width:900px;
margin:10px auto;
}
table#user_work_table th{
background-color:#FFCC66;}
table#user_work_table td{
text-align:center;
}
table#user_work_table td{
border-bottom:1px solid #999999;
}
div#submit_div_bg{
background-image:url(img/bg.png);
top:0px;
left:0px;
width:100%;
height:100%;
z-index:2;
position:fixed;
margin:0 auto;
}  
div#submit_div{
width:100%;
height:450px;
z-index:3;
position:fixed;
top:100px;
}  
div#submit_div_container{
background-color:#FFFFFF;
height:450px;
width:850px;
margin:0 auto;
}
div#close_div{
background-image:url(img/close.png);
height:25px;
width:25px;
float:right;
}
table#submit_table{
background-color:#FFFFFF;
float:left;
width:825px;
clear:right;
}

</style>
</head>

<body>
<?php
/******************************提交作业**************************************/
$work_submit=$_GET['work_submit'];
if($work_submit){
    echo "<div id='submit_div_bg'></div>";
	echo "<div id='submit_div'><div id='submit_div_container'>";
	$sql="select work_name,work_done_amount,dead_time from work where work_id=".$_GET['work_id']." limit 1";
	$result=mysql_query($sql);
	$row=mysql_fetch_array($result);
	//echo date(,strtotime($row[2]));
?>
<a href="student.php"><div id="close_div"></div></a>
<form action="student.php?work_submit=<?php echo $_GET['work_submit'];?>&work_id=<?php echo $_GET['work_id'];?>&name=<?php echo $_GET['name'];?>&username=<?php echo $_GET['username'];?>&work_name=<?php echo $row[0];?>" method="post" enctype="multipart/form-data" name="phuploader">
<table id="submit_table" cellpadding="0" cellspacing="0">
<tr><td width="25px"></td><td width="100px" style="color:#888888">作业名称：</td><td><?php echo $row[0];?></td></tr>
<tr><td></td><td style="color:#888888">截止日期：</td>
<td>
<?php
	echo $row[2]."<font color='#FF3300' style='font-weight:bold'>"; 
	$now_time=strtotime("+8 Hours");
	$past_time=strtotime($row[2]);
	if(($past_time-$now_time)/60/60/24>1) 
	    echo "（余".intval(($past_time-$now_time)/60/60/24)."天）</font>";
	else if(($past_time-$now_time)/60/60>1)
		echo "（余".intval(($past_time-$now_time)/60/60)."小时）</font>";
	else if(($past_time-$now_time)/60>1)
	    echo "（余".intval(($past_time-$now_time)/60)."分钟）</font>";
	else if(($past_time-$now_time)>1)
	    echo "（余".intval(($past_time-$now_time))."秒）</font>";
	else if(($past_time-$now_time)<0)
		echo "（已过期）</font>";
?>
</td></tr>
<tr><td></td><td style="color:#888888">已提交的人数：</td><td><?php echo $row[1];?></td></tr>
<tr>
<td></td>
<td style="color:#888888">选择作业文件：</td>
<td align="center">
<input type="file" name="file[]" size="30" />
<input type="hidden" name="submit" value="true" />
<font color='#FF3300' style='font-weight:bold'>请以学号+_姓名+_作业名称为名，如：1234_小明_<?php echo $row[0];?></font>
</td>
</tr>
<tr><td></td><td style="color:#888888">支持的文件类型：</td><td height="100px"><font style="font-weight:bold"><?php echo implode($allow_types, ", ");?></font><font color='#FF3300' style='font-weight:bold'> 选择的文件不可大于5M</font></td></tr>
<tr><td></td><td colspan="2" height="100px" bgcolor="#99FF66" style="padding-left:10px">
<?php 
if(isset($_POST['submit_button'])&&$error=="")
echo "<b>作业提交成功！</b><br/>";
echo $display_message;

?>
</td></tr>
<tr>
<?php
if($past_time-$now_time>0){
?>
<td colspan="3" height="100px" align="right">
<input type="submit" value="提交作业" name="submit_button" style="width:80px; height:60px; border:none; background-color:#99FF66; color:#333333; font-weight:bold;"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</td>
<?php
}
else{
?>
<td colspan="3" height="100px" align="right">
已过期，无法再提交作业。
</td>
<?php
}
?>
</tr>

</table>
</form>
<?php	
	echo "</div></div>";
}
/******************************提交作业**************************************/

?>
<p align="center"><font size="+3">实验作业提交系统</font><a href="login.php?logout=1">注销</a></p>
<?php
	if($name==""){
		$sql="select * from user where user_id='".$_COOKIE['lab_user_id']."' limit 1";
		$result=mysql_query($sql);
		if(@mysql_num_rows($result)!=0){
			$row=mysql_fetch_array($result);
			if($row[3]==1)
			    echo "<script>window.location.href='lab_home.php'</script>";
			$name=$row[5];
			$username=$row[1];
		}

	}
	echo "<p align='center'>姓名：".$name."<br/>学号：".$username."</p>";
if($name!=""){
?>
<div style="margin:0 auto; width:1000px; ">
<form action="" method="post">
<table cellpadding="0" cellspacing="0" id="user_infor_table">
<tr>
<td class="user_infor_td">班&nbsp;&nbsp;&nbsp;级:</td>
<td>
<?php 
$reault_arr=array();
$sql="select user_class from user where user_id=".$_COOKIE['lab_user_id'];
$result=mysql_query($sql);
$row2=mysql_fetch_array($result);
preg_match_all("/[0-9]+/",$row2[0],$arr);
$a=array_unique($arr[0]);
	$sql="select class_name from class where class_id in (".implode($a,",").")";
	$result=mysql_query($sql);
	while($row2=mysql_fetch_array($result))
	    array_push($reault_arr,$row2[0]);
	echo implode($reault_arr," ; ");
?>
</td><td rowspan="4" id="user_infor_td_2" align="center"><img  src="img/user.jpg" width="150px"/></td>
</tr>
<?php
?>
<tr>
<td class="user_infor_td">姓&nbsp;&nbsp;&nbsp;名:</td>
<td><input type="text"  class="user_infor_input" name="user_name" value="<?php echo $row[5];?>"/><input type="submit" name="change_user_name" value="修改名字"/></td>
</tr>
<tr>
<td class="user_infor_td">用户名:</td>
<td><?php echo $row[1];?></td>
</tr>
<tr>
<td class="user_infor_td_bottom">密&nbsp;&nbsp;&nbsp;码:</td>
<td>
<input type="text"  class="user_infor_input" name="old_pass" value="请输入原密码" onblur="if(this.value==''){this.value='请输入原密码';}" onfocus="if(this.value=='请输入原密码'){this.value='';this.style.color='#737e73';}"/>
<input type="text"  class="user_infor_input" name="new_pass" value="请输入新密码" onblur="if(this.value==''){this.value='请输入新密码';}" onfocus="if(this.value=='请输入新密码'){this.value='';this.style.color='#737e73';}"/>
<input type="submit" name="change_user_pass" value="修改密码"/>
</td>
</tr>

</table>
</form>
<span id="message">
<?php 
if(!isset($_POST['submit_button']))
echo $display_message;
?>
</span>
<table cellpadding="0" cellspacing="0" id="user_work_table">
<thead>
<tr><th>作业名称</th><th>截止日期</th><th>readme</th><th>附件</th><th>提交次数</th><th>操作</th><th>评分</th></tr>
<?php
$sql="select * from work where class_id in (".implode($a,",").") order by work_id desc";
$result=mysql_query($sql);
if(@mysql_num_rows($result)!=0){
    while($row=mysql_fetch_array($result)){
	    $sql="select work_done,grade from workdone where work_id=".$row[0]." and user_id=".$_COOKIE['lab_user_id'];
		$result2=mysql_query($sql);
		if(@mysql_num_rows($result2)!=0){
		    $row2=mysql_fetch_array($result2);
			$work_done=$row2[0];
			$grade=$row2[1];
			if($grade==NULL){
			    $work_submit="<a href='student.php?work_submit=update&work_id=".$row[0]."&name=".$name."&username=".$username."'>更新作业</a>";
				$grade="未评分";
			}
		    else
			    $work_submit="已阅，提交关闭";
		}
		else{
			$work_done="未提交";
			$grade="";
			$work_submit="<a href='student.php?work_submit=submit&work_id=".$row[0]."&name=".$name."&username=".$username."'>提交</a>";
		}
	    echo "<tr><td>$row[2]</td><td>$row[5]</td><td><a href='txt.php?url=".$row[6]."' target='_blank'>$row[6]</a></td><td><a href='".$row[7]."' target='_blank'>$row[7]</a></td><td>$work_done</td><td>$work_submit</td><td>$grade</td></tr>";	
	}
}

?>
</thead>
</table>
</div>
<?php
}
?>
</body>
</html>
