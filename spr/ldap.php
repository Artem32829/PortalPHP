<?php
//IP адрес сервера AD
$ldaphost = "$$$";
//Порт
$ldapport = "389";
//Путь к группе в которой должна быть учетка сотрудника,
//что бы пройти аутентификацию.
$memberof = "CN=pravtarif,CN=Users,DC=bns,DC=local";
$memberofTP = "CN=OTRS-AGENTS,CN=Users,DC=bns,DC=local";
$memberofDOOR = "CN=BOSS_DOOR,CN=Users,DC=bns,DC=local";
$memberofLLLL = "CN=Videocam,CN=Users,DC=bns,DC=local";
//Откуда начинаем искать
$base = "dc=bns,dc=local";
//Фильтр по которому будем аутентифицировать пользователя
$filter = "sAMAccountName=";
//Ваш домен, обязательно с собакой впереди.
$domain = "@bns.local";
?>