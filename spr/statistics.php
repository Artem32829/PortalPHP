<?php
// Параметры для подключения
$mysqli = new mysqli($db_host,$db_user,$db_password,$db_base);
$dt = new DateTime();
$mysqltime = $dt->format('Y-m-d H:i:s');
//Добавление
$qu="INSERT INTO `statistics` (`REMOTE_ADDR`,`HTTP_USER_AGENT`,`HTTP_COOKIE`,`SCRIPT_FILENAME`,`REQUEST_URI`,`SCRIPT_NAME`,`PHP_SELF`,`DT`)
 VALUES ('".
$_SERVER['REMOTE_ADDR']."', '".
$_SERVER['HTTP_USER_AGENT']."', '".
$_SERVER['HTTP_COOKIE']."', '".
$_SERVER['SCRIPT_FILENAME']."', '".
$_SERVER['REQUEST_URI']."', '".
$_SERVER['SCRIPT_NAME']."', '".
$_SERVER['PHP_SELF']."', '".
$mysqltime."' );";	
$result = $mysqli->query($qu);
//$mysqli->close();
?>