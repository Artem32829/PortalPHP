<?php

// LDAP переменные
$ldaphost = "$$$$";  // Ваш сервер ldap
$ldapport = 389;                 // Порт вашего сервера ldap

// Соединение с LDAP
$ldapconn = ldap_connect($ldaphost, $ldapport)
          or die("Невозможно соединиться с $ldaphost");