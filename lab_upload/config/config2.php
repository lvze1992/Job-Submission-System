<?php
require_once("config.php");
$link=mysql_connect($db_host,$db_user,$db_pass)or die(mysql_error());
mysql_select_db($db_name,$link);
mysql_query("SET NAMES UTF8");
?>
