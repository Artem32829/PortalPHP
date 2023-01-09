<html>
<head>
  <title>Результат загрузки файла</title>
</head>
<body>
<?php
   if($_FILES["filename"]["size"] > 50*1024*1024)
   {
     echo ("Размер файла превышает 50 мегабайт");
     exit;
   }      
   if(is_uploaded_file($_FILES["filename"]["tmp_name"]))
   { 
     move_uploaded_file($_FILES["filename"]["tmp_name"], "prikaz/2022/".$_FILES["filename"]["name"]);
	 echo("Файл(ы) заружен(ы)"); 
   } else {
      echo("Ошибка загрузки файла");
   }
?>
</body>
</html>