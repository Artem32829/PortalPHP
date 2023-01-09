<?php
//ini_set('error_reporting', E_ALL);
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
// Параметры для подключения
$db_host = "$$$";
$db_user = "$$$"; // Логин БД
$db_password = "$$$"; // Пароль БД
$db_base = 'info'; // Имя БД
$mysqli = new mysqli($db_host, $db_user, $db_password, $db_base);
$mysqli->set_charset('utf8');
include ('statistics.php');
$themeClass = '';
if (!empty($_COOKIE['theme']) && $_COOKIE['theme'] == 'dark') {
  $themeClass = 'dark-theme';
}


echo '
<!DOCTYPE html>
<html>h
  <head>
    <meta charset="utf-8">
    <title>Информационный портал ASCOM GROUP"</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="main.css">
	<link rel="stylesheet" href="bns.css">	
  <link rel="stylesheet" href="newyear.css">

	<script src="tableToExcel.js"></script>	
    <script src="jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
	<script src="js/loader.js"></script>
	<script src="js/bootstrap.bundle.js"></script>
	<script src="//cdn.jsdelivr.net/npm/details-polyfill@1/index.min.js" async></script>
    <link rel="apple-touch-icon" sizes="180x180" href="/img/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/img//favicon-16x16.png">    	
 </head>'; 

include ('newyear.php');
echo "<body class=$themeClass>";
echo'


<div class="container"> 

<a style="margin-left: 0px;" href="index.php?page=main"><img src="/img/logo22.png" style="width:150px" alt="ASCOM GROUP" title="ASCOM GROUP" style="width: 350px;"></a><br> 
<ul id="navbar">
      <li><a href="index.php?page=main">Телефонный справочник</a>
        <ul >
			<li><img src="/img/teltp.png" width="24" height="24"><a style="display: inline;" href="authteltp.php?page=main">Справочник ТП</a></li> 
			<li><img src="/img/birthday.png" width="24" height="24"><a style="display: inline;" href="dr.php?page=main">Дни рождения</a></li> 
      <li><a style="display: inline;" href="inventar.php?page=main">Обходной лист</a></li>
      <li><a style="display: inline;" href="chat.php?page=main">Чат</a></li>
         </ul>
      </li>      
      <li><a href="prikazall.php?page=main">Приказы и документация</a>
        <ul>			  
		  	<li><img src="/img/boss.png" width="24" height="24"><a style="display: inline;" href="dov.php?page=main">Доверенности</a></li> 
			<li><img src="/img/document.png" width="24" height="24"><a style="display: inline;" href="helpz.php?page=main">Заявления</a></li> 
			<li><img src="/img/inc.png" width="24" height="24"><a style="display: inline;" href="helpblank.php?page=main">Бланки заявок</a></li> 
        </ul>
      </li>
	  	  
	  
	  <li><a href="teh.php?page=main">Техническая поддержка</a>
        <ul>       		  
		  <li><img src="/img/manual.png" width="24" height="24"><a style="display: inline;" href="helpPC.php?page=main">Инструкции ПК</a></li>
		  <li><img src="/img/load.png" width="24" height="24"><a style="display: inline;" href="cloud.php?page=main">Обмен файлами</a></li>	  
        </ul>		
      </li> 


	<li><a href="currencies.php?page=main">Курсы валют</a>	
	</li>

	<li><a href="weather.php?page=main">Погода</a>	
	</li>

    </ul>';
