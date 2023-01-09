<?php
include ('menu.php');

echo '<h6>Приказы и распоряжения</h6>';

echo'<details><summary>Приказы за 2022 год</summary><ul class="list-group">';
$files = scandir('prikaz/2022');
sort($files);
foreach($files as $file){if ($file != "." && $file != "..")
{$ext = pathinfo($file, PATHINFO_EXTENSION); 	
echo'<li><img src="/img//'.$ext.'.svg" width="32" height="32"><a style="margin-left: 10px" class= link href="prikaz\2022\\'.$file.'" target="_blank" class="product">'.$file.'</a><br></li>'; }}
echo'</ul></details> ';

echo'<details><summary>Приказы за 2021 год</summary><ul class="list-group">';
$files = scandir('prikaz/2021');
sort($files);
foreach($files as $file){if ($file != "." && $file != "..")
{$ext = pathinfo($file, PATHINFO_EXTENSION); 	
echo'<li><img src="/img//'.$ext.'.svg" width="32" height="32"><a style="margin-left: 10px" class= link href="prikaz\2021\\'.$file.'" target="_blank" class="product">'.$file.'</a><br></li>'; }}
echo'</ul></details> ';

echo'<details><summary>Приказы за 2020 год</summary><ul class="list-group">';
$files = scandir('prikaz/2020');
sort($files);
foreach($files as $file){if ($file != "." && $file != "..")
{$ext = pathinfo($file, PATHINFO_EXTENSION); 	
echo'<li><img src="/img//'.$ext.'.svg" width="32" height="32"><a style="margin-left: 10px" class= link href="prikaz\2020\\'.$file.'" target="_blank" class="product">'.$file.'</a><br></li>'; }}
echo'</ul></details> ';

echo'<div class="btn btn-link"><img src="/img/load.png" width="32" height="32"><a href="Load_prikaz.php?page=main">Загрузить</a></div>';



include ('footer.php');
?>
