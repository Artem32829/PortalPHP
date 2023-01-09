<?php
include ('menu.php');

echo '<h6>Бланки заявок</h6><div class="div-okrugl">';

    $files = scandir('help/blank');
    sort($files);
    foreach($files as $file){
	if ($file != "." && $file != "..")
	{ $ext = pathinfo($file, PATHINFO_EXTENSION);
      echo'<img style="margin-left: 0px; margin-top: 10px" src="/img//'.$ext.'.svg" width="32" height="32"><a style="margin-left: 10px;" class= link href="help\blank\\'.$file.'" class="product">'.$file.'</a><br>';    }
	}
	echo '</div>';
include ('footer.php');
?>
