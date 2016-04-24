<?php
 preg_match_all("/[0-9]+/","1a3",$arr);
echo print_r($arr);
echo $arr[0][0];
?>