<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript">
function loadXMLDoc(url){
    var xmlhttp;
	if(window.XMLHttpRequest){
	    xmlhttp=new XMLHttpRequest();
	}
	else{
	    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function(){
	    if(xmlhttp.readyState==4&&xmlhttp.status==200){
		    document.getElementById("mytxt").innerHTML=xmlhttp.responseText;
		}
	}
	xmlhttp.open("GET",url,true);
	xmlhttp.send();
}

</script>
</head>

<body id="mytxt">
<?php
$url=$_GET['url'];
echo "<script>loadXMLDoc('".$url."');</script>";
?>
</body>
</html>
