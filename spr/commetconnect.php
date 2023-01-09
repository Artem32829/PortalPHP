<?php 
// Подключаемся к комет серверу с логином и паролем для демо доступа (получить свои данные для подключения можно после регистрации на comet-server.ru )
// Логин 15
// Пароль lPXBFPqNg3f661JcegBY0N0dPXqUBdHXqj2cHf04PZgLHxT6z55e20ozojvMRvB8
// База данных CometQL_v1
$link = mysqli_connect("app.comet-server.ru", "3953", "DWgNVkhRUsfdget5AA2gYDDk7o4RLcheZZCq13c65pmvBNXzWCubmwy0YSZa5OOs", "CometQL_v1");

// Отправляем сообщение в канал
mysqli_query (  $link, "INSERT INTO pipes_messages (name, event, message)VALUES('SimplePhpChat', '', '".mysqli_real_escape_string($link,htmlspecialchars($_GET['text']))."' );" );