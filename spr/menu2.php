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
<html>
  <head>
    <meta charset="utf-8">
    <title>Информационный портал ASCOM GROUP"</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="main.css">
	<link rel="stylesheet" href="bns.css">	
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
echo "<body class=$themeClass>";
echo'
<div class="container">  
<a style="margin-left: 0px;" href="index.php?page=main"><img src="/img/logo2.png" style="width:150px" alt="ASCOM GROUP" title="ASCOM GROUP" style="width: 350px;"></a><br>
<ul id="navbar">
      <li><a href="index.php?page=main">Главное меню</a>        
      </li>      
      <li><a href="minsk1.php?page=main">Минск-1</a>        
      </li>  	  
	  
	  <li><a href="minsk2.php?page=main">Минск-2</a>
        	
      </li> 


	<li><a href="novopolock.php?page=main">Новополоцк</a>	
	</li>

	<li><a href="weather.php?page=main">ЦА</a>	
	</li>

    </ul>';
