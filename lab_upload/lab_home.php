<?php
session_start();
if(!isset($_SESSION["lab_user_id"])){ //判断当前会话变量是否注册
   if(!isset($_COOKIE["lab_user_id"]))//查cookie
   {
     header("location: login.php");//session和cookie都没有记录
   }
}
//已经注册session 则使用当前session
// 系统允许上传文件的类型.
$allow_types=array("jpg","gif","png","zip","rar","txt","doc","docx","bmp","mp3","mp4","7z","avi","ico","torrent");
$error="";
$success="";
$error1="";
$class_name_1="";//标记
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>实验作业提交系统</title>
<link rel="stylesheet" type="text/css" href="css/jquery-ui.css" />
<style>
td.alt{
border:2px solid #333333;
font-size:16px;
}
td.not_alt{
border:2px solid #ffffff;
font-size:16px;

}
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
th.thh{
font-size:18px;}
.intro_message{
color:#FF3333;
}
table.class_build td{
border-bottom:2px solid #333333;
}
table.student_list td{
border-bottom:1px solid #999999;
}
#work_table_head{
cursor:pointer;
}
tbody#work_table{
display:none;
}
/*以下为和时间选择器相关的代码*/
body{  font:12px/18px Tahoma, Helvetica, Arial, Verdana, "\5b8b\4f53", sans-serif;}

.ui-timepicker-div .ui-widget-header { margin-bottom: 8px;}
.ui-timepicker-div dl { text-align: left; }
.ui-timepicker-div dl dt { height: 25px; margin-bottom: -25px; }
.ui-timepicker-div dl dd { margin: 0 10px 10px 65px; }
.ui-timepicker-div td { font-size: 90%; }
.ui-tpicker-grid-label { background: none; border: none; margin: 0; padding: 0; }
.ui_tpicker_hour_label,.ui_tpicker_minute_label,.ui_tpicker_second_label,.ui_tpicker_millisec_label,.ui_tpicker_time_label{padding-left:20px}
</style>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="js/jquery-ui.js"></script>
<script type="text/javascript" src="js/jquery-ui-slide.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript">
$(function(){
	$('#example_1').datetimepicker();
	});

</script>
</head>
<body>
<p align="center"><font size="+3">实验作业提交系统</font><a href="login.php?logout=1">注销</a></p>
<?php
require("config/config2.php");
$sql="select * from user where user_id='".$_COOKIE['lab_user_id']."' limit 1";
$result=mysql_query($sql);
if(@mysql_num_rows($result)!=0){
    $row=mysql_fetch_array($result);
}
if($row[3]==1){
	echo "<p align='center'>老师：".$row[5]."</p>";
	$tab=$_GET['tab'];
	$class_id=$_GET['class_id'];
	if(!$tab) $tab=1;
	if($tab==2){
	/**********************************建立班级*************************/
		$add_class=$_POST['add_class'];
		$class_name=$_POST['class_name'];
		if(isset($add_class)&&$class_name!=""){
			if(strlen($class_name)>60||strlen($class_name)<7){
					echo "<script language='javascript'>";
					echo "window.location.href='lab_home.php?tab=2&wrong=1'";
					echo "</script>";
			}
			else{
				$sql="select * from class where class_name='".$class_name."'";
				$result=mysql_query($sql);
				if(@mysql_num_rows($result)!=0){//class_name是否存在 存在
					echo "<script language='javascript'>";
					echo "window.location.href='lab_home.php?tab=2&wrong=2'";
					echo "</script>";
				}
				else{//class_name是否存在 不存在
					$sql="insert into class(user_id_build,class_name) values('".$_COOKIE['lab_user_id']."','".$class_name."')";
					if(mysql_query($sql,$link)){
						 $url=mysql_insert_id();
						 $sql="update class set class_url='down/".$url."/' where class_id=".$url;
						 mysql_query($sql,$link);
						 $sql="update user set user_class=CONCAT(user_class,CHAR(59),'".$url."') where user_id=".$_COOKIE['lab_user_id'];
						 if(mysql_query($sql,$link)){
						     //建立路径文件
							  /*************创建目录*************/
							  if (!is_dir('down/'.$url)){
								  mkdir ('down/'.$url); 
								   //echo '创建文件夹成功';
								  if(!is_file('down/'.$url.'/'.$url.'.txt')){ 
								  $myfile=fopen('down/'.$url.'/'.$url.'.txt', "w") or die("Unable to open file!");
								  fwrite($myfile, $class_name.'<b>'.$_COOKIE['lab_user_id'].'</b>');
								   //echo '创建文件test.txt成功';
								   } 
								   else {
								   echo '需创建的文件test.txt已经存在,wrong001';
								   }
			
							   } 
							   else{
								   if(!is_file('down/'.$url.'/'.$url.'.txt')){ 
								   $myfile=fopen('down/'.$url.'/'.$url.'.txt', "w") or die("Unable to open file!");
								   fwrite($myfile, $class_name.'<b>'.$_COOKIE['lab_user_id'].'</b>');
								   //echo '创建文件test.txt成功';
								   } 
									else {
									echo 'down/'.$url.'/'.$url.'.txt';
									  echo '需创建的文件test.txt已经存在,wrong002';
								   }
			
							   }
							  /*************创建目录*************/

						 }
						 else{
							echo "<script language='javascript'>";
							echo "window.location.href='lab_home.php?tab=2&wrong=4'";
							echo "</script>";
						 }
                    }
                    else{
						echo "<script language='javascript'>";
						echo "window.location.href='lab_home.php?tab=2&wrong=3'";
						echo "</script>";
					}
				}

			}
		}
	}
    /**********************************发布作业*************************/
    if($tab==3){
	    $work_name=$_POST['work_name'];
		$add_work=$_POST['add_work'];
		if($_SESSION["work_name"]!=$work_name){
		$_SESSION["work_name"]=$work_name;
		$dead_time=$_POST['dead_time'];
		$release_work_wrong=0;
		if(isset($add_work)&&$work_name!=""&&$dead_time!=""){
		     if(strlen($dead_time)!=16){
				echo "<script language='javascript'>";
				echo "window.location.href='lab_home.php?tab=3&wrong=10&class_id=".$class_id."'";
				echo "</script>";
			 }
			 if(strlen($work_name)>60){
				echo "<script language='javascript'>";
				echo "window.location.href='lab_home.php?tab=3&wrong=11&class_id=".$class_id."'";
				echo "</script>";
			 }
			 else{
			     $sql="select class_url from class where class_id=".$class_id;
				 $result=mysql_query($sql);
				 if(@mysql_num_rows($result)!=0){
				     $row=mysql_fetch_array($result);
					 $sql="insert into work(class_id,work_name,work_url,dead_time) values(".$class_id.",'".$work_name."','".$row[0]."','".$dead_time."')";
					 if(mysql_query($sql)){
						 $url2=mysql_insert_id();
						 $sql="update work set work_url=CONCAT(work_url,CHAR(22),'".$url2."'),work_txt='down/".$class_id."/".$url2."/readme.txt' where work_id=".$url2;
						 if(mysql_query($sql)){
						      $work_txt=$_POST['work_txt'];
							  /*************创建目录*************/
							  if (!is_dir('down/'.$class_id.'/'.$url2)){
								  mkdir ('down/'.$class_id.'/'.$url2); 
								   //echo '创建文件夹成功';
								  if($work_txt!=""){
									  if(!is_file('down/'.$class_id.'/'.$url2.'/readme.txt')){ 
									  $myfile=fopen('down/'.$class_id.'/'.$url2.'/readme.txt', "w") or die("Unable to open file!");
									  fwrite($myfile, $work_txt);
								   //echo '创建文件test.txt成功';
									   } 
									   else {
											$release_work_wrong=4;
									   }
								  }
								  else{
								  		$error1.="<b>提醒：</b> 未填写readme.txt <br />";
								  }
								   /**************************上传附件*****************************/

// 单个文件限制
$max_file_size="5120";

// 所有文件限制
$max_combined_size="20000";

// 上传数量
$file_uploads="8";

// 网站名称
$websitename="n";

// 完整的网址和目录，最后用http://localhost/hbyun/test_test/zlfjscxz_upload/index.php/
$full_url="down/".$class_id."/".$url2."/";

// 系统上存储路径最后用/.
$folder="./down/".$class_id."/".$url2."/";

// 随机文件梦? true=使用 (推荐选择这个), false=使用原来文件名
// 随机文件名可以防止重复文件，如使用false有相同的文件无法上传.
$random_name=true;

// 只有使用完整的服务器路径踩设置这个。一般请留空。用/结束.
$fullpath="";

/*
//================================================================================
* ! ATTENTION !
//================================================================================
: Don't edit below this line.
*/

// Initialize variables
$password_hash=md5($password);
$display_message="";
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
	$cln_filename_find=array("/\.[^\.]+$/", "/[^\d\w\s-]/", "/\s\s+/", "/[-]+/", "/[_]+/");
	$cln_filename_repl=array("", ""," ", "-", "_");
	$string=preg_replace($cln_filename_find, $cln_filename_repl, $string);
	return trim($string);
}

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
					$file_name[$i]=cln_file_name($_FILES['file']['name'][$i]);
				}
	
				// Check for blank file name
				if(str_replace(" ", "", $file_name[$i])=="") {
					
					$error.= "<b>提醒：</b> ".$_FILES['file']['name'][$i]." 文件名为空白无法上传。<br />";
				
				//Check if the file type uploaded is a valid file type. 
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
					
					If(move_uploaded_file($_FILES['file']['tmp_name'][$i],$folder.$file_name[$i].".".$file_ext[$i])) {
						
						$success.="附件名称：
                        <a.".$file_ext[$i]."\" target=\"_blank\">".$_FILES['file']['name'][$i]."</a><br />";
                        
						$success.="附件连接：
                        <a class=\"am-badge am-badge-success\" href=\"".$full_url.$file_name[$i].".".$file_ext[$i]."\" target=\"_blank\">".$full_url.$file_name[$i].".".$file_ext[$i]."</a>
                        <br />";
						$sql="update work set work_detail='".$full_url.$file_name[$i].".".$file_ext[$i]."' where work_id=".$url2;
						 if(mysql_query($sql)){}
						 else{
						    $error.="<b>提醒:</b> ".$_FILES['file']['name'][$i]." 添加到数据库失败！<br />";
						 }

					} else {
						$error.="<b>提醒:</b> ".$_FILES['file']['name'][$i]." 可能由于网络原因上传失败请重试！<br />";
					}
					
				}
							
			} // If Files
		
		} // For
		
	} // Else Total Size
	
	if(($error=="") AND ($success=="")) {
		$error.="<b>提醒：</b> 未选择附件 <br />";
	}
	$display_message=$success.$error.$error1;

} // $_POST AND !$password_form
								   /**************************上传附件*****************************/
			
							   } 
							  /*************创建目录*************/
						 }
						 else
							 $release_work_wrong=3;
					 }
					 else{
						 $release_work_wrong=1;
					 }
				 }
				 else
				 		$release_work_wrong=2;
				 if($release_work_wrong!=0){
					echo "<script language='javascript'>";
					echo "window.location.href='lab_home.php?tab=3&work_wrong=".$release_work_wrong."&class_id=".$class_id."'";
					echo "</script>";
				 }

			 }
		     
		}
		else if(isset($add_work)){
			echo "<script language='javascript'>";
			echo "window.location.href='lab_home.php?tab=3&wrong=9&class_id=".$class_id."'";
			echo "</script>";
		}
		}//session防止重复添加
		else if($work_name==""){
		    if(isset($add_work)){
				echo "<script language='javascript'>";
				echo "window.location.href='lab_home.php?tab=3&wrong=9&class_id=".$class_id."'";
				echo "</script>";

			}
		}
		else{
			echo "<script language='javascript'>";
			echo "window.location.href='lab_home.php?tab=3&wrong=12&class_id=".$class_id."'";
			echo "</script>";
		}
    }
	/**********************************添加学生*************************/
    if($tab==4){
	    $add_student=$_POST['add_student'];
		$id1=$_POST['id1'];
		$id2=$_POST['id2'];		
		//class_id

		function Check_Username_number($user_name){
			$UserNameChars="^[0-9]{4,30}$";
			if(!ereg("$UserNameChars",$user_name)) $username_wrong=10;
			else $username_wrong=0;
			return $username_wrong;
		}
		if(isset($add_student)&&$id1!=""&&$id2!=""&&$class_id!=""){
		    $username_wrong1=Check_Username_number($id1);
			$username_wrong2=Check_Username_number($id2);
	        if($id2-$id1>=0&&$id2-$id1<21&&$username_wrong1==0&&$username_wrong2==0){
				$a=$id1;$b=$id2;$id=$class_id.";";
				$i=0;
					 for($a;$a<=$b;$a=$a+1){
						//查看是否存在该学号
						$sql="select count(*) from user where username='".$a."'";
						$result=mysql_query($sql);
						$row=mysql_fetch_array($result);
						if($row[0]!=0){	//存在
							$sql="update user set user_class = CONCAT(user_class,CHAR(59),'".$id."') where username='".$a."'";
								if(mysql_query($sql,$link)){
								}
								else{
										echo "<script language='javascript'>";
										echo "window.location.href='lab_home.php?tab=4&wrong=7&class_id=".$class_id."'";
										echo "</script>";
										break;
					
								}
			
						}
						else{	//不存在
							$sql="insert into user(username,userpass,user_class) values('".$a."',SHA1('".$a."'),CONCAT(user_class,CHAR(59),'".$id."'))";
							if(mysql_query($sql,$link)){
							    $i=$i+1;
							}
							else{
									echo "<script language='javascript'>";
									echo "window.location.href='lab_home.php?tab=4&wrong=8&class_id=".$class_id."'";
									echo "</script>";
									break;
				
							}
						}
					 }//for END
					 if($a==$b+1){
					     $sql="update class set class_member_amount=class_member_amount+".($i)." where class_id='".$class_id."'";
						 mysql_query($sql,$link);
					 }
			}
			else{
				echo "<script language='javascript'>";
				echo "window.location.href='lab_home.php?tab=4&wrong=6&class_id=".$class_id."'";
				echo "</script>";
			}
		}
		else if(isset($add_student)){
			echo "<script language='javascript'>";
			echo "window.location.href='lab_home.php?tab=4&wrong=5&class_id=".$class_id."'";
			echo "</script>";
		}
	}
?>
<div style="margin:0 auto; width:1000px; ">
<table>
<tr>
<td class="<?php if($tab==1) echo "alt";else echo "not_alt";?>"><a href="lab_home.php?tab=1">概览</a></td>
<td class="<?php if($tab==2) echo "alt";else echo "not_alt";?>"><a href="lab_home.php?tab=2">建立班级</a></td>
<td class="<?php if($tab==3) echo "alt";else echo "not_alt";?>"><a href="lab_home.php?tab=3">发布作业</a></td>
<td class="<?php if($tab==4) echo "alt";else echo "not_alt";?>"><a href="lab_home.php?tab=4">添加学生</a></td>
<td class="<?php if($tab==5) echo "alt";else echo "not_alt";?>"><a href="lab_home.php?tab=5">作业提交情况</a></td>
</tr>
</table>
<!--**********************************概览************************************-->
<?php if($tab==1){?>
<table width="800px" style="margin:0 auto">
<thead><tr><th class="thh" colspan="2" align="center">概览</th></tr></thead>
<tbody><tr><td>1</td><td>2</td></tr></tbody>
</table>
<?php
}
?>
<!--**********************************建立班级************************************-->
<?php if($tab==2){?>
<table width="800px" style="margin:0 auto">
<thead><tr><th class="thh" colspan="2" align="center">建立班级</th></tr></thead>
<tbody><tr><td align="right" width="370px" class="intro_message">请输入班级名称，并点击“建立班级”即可创建班级</td>
<td>
<form action="lab_home.php?tab=2" method="post">
<input type="text" name="class_name"/><input type="submit" name="add_class" value="建立班级"/>
</form>
</td>
</tr></tbody>
</table>

<table width="800px" style="margin:20px auto" class="class_build" cellpadding="0" cellspacing="0">
<thead><tr><th class="thh" colspan="4" align="center">已建立的班级</th></tr></thead>
<tbody>
<tr><td>班级名</td><td>班级路径</td><td width="80px">人数(人)</td><td></td></tr>
<?php 
    $sql="select class_id,class_name,class_url,class_member_amount from class where user_id_build=".$_COOKIE['lab_user_id'];
    $result=mysql_query($sql);
    if(@mysql_num_rows($result)!=0){
    while($row=mysql_fetch_array($result)){
?>
<tr><td><?php echo $row[1];?></td><td><?php echo $row[2];?></td><td><?php echo $row[3];?></td><td><a  href="lab_home.php?tab=4&class_id=<?php echo $row[0];?>">添加学生</a></td></tr>
<?php
	}
}
?>
</tbody>
</table>

<?php
}
?>
<!--**********************************发布作业************************************-->
<?php if($tab==3){
?>
<table width="800px" style="margin:0px auto;" cellpadding="0" cellspacing="0">
<thead><tr><th class="thh" colspan="3" align="center" ><span onclick="release()" id="work_table_head">发布作业<font size="-2" color="#FF3300">（点击）</font></span></th></tr></thead>
<tbody  id="work_table" <?php if($class_id!="") echo "style='display:table'";?>>
<tr>
<td colspan="2" align="left" >
<select id="class_name" name="class_name" style="color:#FF3333; font-weight:bold;" onchange="ChangeURL(3)">
<option selected="selected"></option>
<?php
	$sql="select * from class where user_id_build=".$_COOKIE['lab_user_id'];
	$result=mysql_query($sql);
	if(@mysql_num_rows($result)){
		while($row2=mysql_fetch_array($result)){
?>
		    <option value="<?php echo $row2[0];?>" <?php if($row2[0]==$class_id) echo "selected='selected'";?> style="color:#000000;" ><?php echo $row2[2];?></option>
<?php
        }
	}

?>
</select>
</td><!--下拉框-->
<td width="200px">
发布者:<?php if($class_id!=""){echo $row[1];}?>
</td>
</tr>
<form action="lab_home.php?tab=3&class_id=<?php echo $class_id;?>" method="post" enctype="multipart/form-data" name="phuploader">
<tr bgcolor="#33CCCC" height="35px">
<td>作业名称：<input type="text" name="work_name"/></td><td>截止日期：<input type="text" id="example_1" name="dead_time" readonly="true"  onfocus="example_1.blur()"/></td><td></td>
</tr>
<tr>
<td colspan="2" rowspan="3" height="400px" style="padding-left:10px">readme.txt<textarea name="work_txt" style="height:350px; width:500px;"></textarea></td><td valign="top">
<br/>添加附件：
<input type="file" name="file[]" size="30" />
<input type="hidden" name="submit" value="true" />
<font color="#FF3300" style="font-weight:bold"><?php echo implode($allow_types, ", ");?></font></td>
</tr>
<tr>
<td></td>
</tr>
<tr><td height="150px" align="center" valign="middle"><input type="submit" value="发布作业" name="add_work" style="width:80px; height:60px"/></td></tr>
</form>
<tr><td colspan="3">
<?php
if($_GET['wrong']==""&&$_GET['work_wrong']==0&&isset($add_work))
echo "<b>作业发布成功！</b><br/>";
echo $display_message;
?></td></tr>
</tbody>
</table>
<table width="800px" style="margin:20px auto" class="class_build" cellpadding="0" cellspacing="0">
<thead><tr><th class="thh" colspan="7" align="center">已发布的作业</th></tr></thead>
<tbody>
<?php
$sql="select work.class_id,work_name,dead_time,work_done_amount,work_url,work_txt,work_detail,class_name from work,class where work.class_id=class.class_id and user_id_build=".$_COOKIE['lab_user_id']." order by work.class_id";
$result=mysql_query($sql);
?>
<tr bgcolor="#33CCCC"><td width="15px" style="color:#FF3300; font-weight:bold"></td><td>作业名</td><td>截止日期</td><td width="80px">完成人数(人)</td><td>作业路径</td><td>readme.txt</td><td>附件</td></tr>
<?php
if(@mysql_num_rows($result)!=0){
    while($row=mysql_fetch_array($result)){
	   if($class_name_1!=$row[7]){
	       $class_name_1=$row[7];
		   echo "<tr><td colspan='7'  style='color:#FF3300;font-weight:bold'>".$row[7]."</td></tr>";
	   }
	   echo "<tr><td></td><td>".$row[1]."</td><td>".$row[2]."</td><td width='80px'>".$row[3]."</td><td>".$row[4]."</td><td><a href='txt.php?url=".$row[5]."' target='_blank'>".$row[5]."</a></td><td><a href='".$row[6]."' target='_blank'>".$row[6]."</a></td></tr>
"; 
	}
}
?>
</tbody><a charset="gb2312">
</table>

<?php
}
?>
<!--**********************************添加学生************************************-->
<?php if($tab==4){
if($class_id!=""){
	$sql="select class_name,class_member_amount from class where class_id=".$class_id;
	$result=mysql_query($sql);
	if(@mysql_num_rows($result)){
		$row=mysql_fetch_array($result);
	}
}
?>
<table width="800px" style="margin:0px auto;" cellpadding="0" cellspacing="0">
<thead><tr><th class="thh" colspan="3" align="center">添加学生</th></tr></thead>
<tbody>
<tr>
<td colspan="2" align="left" >
<select id="class_name" name="class_name" style="color:#FF3333; font-weight:bold;" onchange="ChangeURL(4)">
<option selected="selected"></option>
<?php
	$sql="select * from class where user_id_build=".$_COOKIE['lab_user_id'];
	$result=mysql_query($sql);
	if(@mysql_num_rows($result)){
		while($row2=mysql_fetch_array($result)){
?>
		    <option value="<?php echo $row2[0];?>" <?php if($row2[0]==$class_id) echo "selected='selected'";?> style="color:#000000;" ><?php echo $row2[2];?></option>
<?php
        }
	}

?>
</select>
</td><!--下拉框-->
<td width="200px">
现有人数:<?php if($class_id!=""){echo $row[1];}?>
</td>
</tr>
<tr bgcolor="#00FF66" height="35px">
<form action="lab_home.php?tab=4&class_id=<?php echo $class_id;?>" method="post">
<td>起始学号：<input type="text" name="id1"/></td><td>终止学号：<input type="text" name="id2"/></td><td><input type="submit" value="批量添加" name="add_student"/></td>
</form>
</tr></tbody>
</table>
<?php
if($class_id!=""){
?>
<table width="800px" style="margin:20px auto" cellpadding="0" cellspacing="0" class="student_list">
<thead>
<tr><th style="font-size:14px; text-align:left">学号</th><th style="font-size:14px;text-align:left">姓名</th></tr>
</thead>
<tbody>
<?php
$class=";".$class_id.";";
$sql="select * from user where user_class like '%".$class."%' and user_type='0'";
$result=mysql_query($sql);
if(@mysql_num_rows($result)!=0){
    while($row=mysql_fetch_array($result)){
	    echo "<tr><td>$row[1]</td><td>$row[5]</td></tr>"; 
	}
}
}
?>
</tbody>
</table>
<?php 
}
?>
</div>
<?php
}
else{//判断身份，以上都是老师的界面
/*echo "<script>window.location.href='student.php?name=".$row[5]."&username=".$row[1]."'</script>";*/
echo "<script>window.location.href='student.php'</script>";

}
?>

<?php
//****************************输出提示信息**************************************
$wrong=$_GET['wrong'];
switch($wrong){
case 1:	echo "<script language='javascript'>alert(' 班级名称长度不合法！ ');</script>";break;
case 2:	echo "<script language='javascript'>alert(' 班级名称已被使用！ ');</script>";break;
case 3:	echo "<script language='javascript'>alert(' 班级插入数据库失败！01 ');</script>";break;
case 4:	echo "<script language='javascript'>alert(' 班级插入数据库失败！02 ');</script>";break;
case 5:	echo "<script language='javascript'>alert(' 请选择班级，并填写完整学号区间！ ');</script>";break;
case 6:	echo "<script language='javascript'>alert(' 批量添加学生失败\\r 学号只可为数字且长度为4到30位之间 ,一次至多只能添加20个学生 ');</script>";break;
case 7:	echo "<script language='javascript'>alert(' 批量添加学生失败\\r 未知错误01 ');</script>";break;
case 8:	echo "<script language='javascript'>alert(' 批量添加学生失败\\r 未知错误02 ');</script>";break;
case 9:	echo "<script language='javascript'>alert(' 请选择班级\\r 并填写作业名称和截止时间 ');</script>";break;
case 10:echo "<script language='javascript'>alert(' 请选择作业截止时间，并勿修改。 ');</script>";break;
case 11:echo "<script language='javascript'>alert(' 作业名称过长，勿超过20个中文或60个英文。 ');</script>";break;
case 12:echo "<script language='javascript'>alert(' 请勿重复添加。 ');</script>";break;
default: break;
}
$work_wrong=$_GET['work_wrong'];
switch($work_wrong){
case 1:	echo "<script language='javascript'>alert(' 发布作业wrong01！ ');</script>";break;
case 2:	echo "<script language='javascript'>alert(' 发布作业wrong02！ ');</script>";break;
case 3:	echo "<script language='javascript'>alert(' 发布作业wrong03！ ');</script>";break;
case 4:	echo "<script language='javascript'>alert(' 发布作业wrong04！ ');</script>";break;
default: break;
}

?>

<script language="javascript" type="text/javascript">
function ChangeURL(tab){
var myselect=document.getElementById("class_name");
var index=myselect.selectedIndex;
var url=myselect.options[index].value;
if(url!="")
window.location.href="lab_home.php?tab="+tab+"&class_id="+url;
}
function release(){
var element=document.getElementById("work_table");
if(element.style.display!="table")
element.style.display="table";
else
element.style.display="none";
}
</script>

</body>
</html>

