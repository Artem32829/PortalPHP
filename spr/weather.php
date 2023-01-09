<?php
//ini_set('error_reporting', E_ALL);
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
include 'menu.php';

echo '<h6 style="margin-bottom:2px">Прогноз погоды</h6>';
if (isset($_POST['city'])) {
    $value = $_POST['city'];
} else {
    $value = 625144;
}

if (!$city) {
    echo '
<form method="post">
<select name="city" id="city" onchange="this.form.submit()" class="form-control">';

    if ($value == 625144) {
        echo '<option selected value="625144">Минск</option>';
    } else {
        echo '<option value="625144">Минск</option>';
    }
    if ($value == 629634) {
        echo '<option selected value="629634">Брест</option>';
    } else {
        echo '<option value="629634">Брест</option>';
    }
    if ($value == 630429) {
        echo '<option selected value="630429">Барановичи</option>';
    } else {
        echo '<option value="630429">Барановичи</option>';
    }
    if ($value == 620127) {
        echo '<option selected value="620127">Витебск</option>';
    } else {
        echo '<option value="620127">Витебск</option>';
    }
    if ($value == 627907) {
        echo '<option selected value="627907">Гомель</option>';
    } else {
        echo '<option value="627907">Гомель</option>';
    }
    if ($value == 627904) {
        echo '<option selected value="627904">Гродно</option>';
    } else {
        echo '<option value="627904">Гродно</option>';
    }
    if ($value == 625665) {
        echo '<option selected value="625665">Могилёв</option>';
    } else {
        echo '<option value="625665">Могилёв</option>';
    }
    if ($value == 625324) {
        echo '<option selected value="625324">Мозырь</option>';
    } else {
        echo '<option value="625324">Мозырь</option>';
    }
    if ($value == 624784) {
        echo '<option selected value="624784">Новополоцк</option>';
    } else {
        echo '<option value="624784">Новополоцк</option>';
    }
    if ($value == 623549) {
        echo '<option selected value="623549">Пинск</option>';
    } else {
        echo '<option value="623549">Пинск</option>';
    }
    if ($value == 622428) {
        echo '<option selected value="622428">Солигорск</option>';
    } else {
        echo '<option value="622428">Солигорск</option>';
    }
    echo '
</select>
</form>';
}




 echo '<div class="wea" id="wea" style="display:none;">';


$apiKey = "71b2ba0149959f0768f3548e3276ab14";
$cityId = $value; //"625144";
$apiUrl = "http://api.openweathermap.org/data/2.5/forecast?id=" . $cityId . "&lang=ru&units=metric&APPID=" . $apiKey . "&cnt=33";
$crequest = curl_init();
//http://api.openweathermap.org/data/2.5/weather?id=625144&lang=ru&units=metric&APPID=71b2ba0149959f0768f3548e3276ab14
curl_setopt($crequest, CURLOPT_HEADER, 0);
curl_setopt($crequest, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($crequest, CURLOPT_URL, $apiUrl);
curl_setopt($crequest, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($crequest, CURLOPT_VERBOSE, 0);
curl_setopt($crequest, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($crequest);
curl_close($crequest);
$data = json_decode($response);
$currentTime = time();
$ar_we = array();
$ar_we[] = ['time', 'Температура'];
$i = 0;
$date_seg = new DateTime(); // Сейчас
$date_seg_month = $date_seg->format('m');
$date_seg_day = $date_seg->format('d');
$date_seg_format = date('d.m.Y H:i');
// Вывод месяца на русском
$monthes = array(1 => 'Января', 2 => 'Февраля', 3 => 'Марта', 4 => 'Апреля', 5 => 'Мая', 6 => 'Июня', 7 => 'Июля', 8 => 'Августа', 9 => 'Сентября', 10 => 'Октября', 11 => 'Ноября', 12 => 'Декабря');
// Вывод дня недели
$days = array('Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота');
$cityname = $data->city->name;
$iday = 0;
$flag = 0;
echo '<div class="flex-container">';
///////////////////////////////////////////////$s_wea
$s_wea = '<hr><div class="flex-container" style="font-family: GothamPro-Medium,Helvetica,Arial,sans-serif;" >'; //<tr><th>втораятаблица</th></tr>';
foreach ($data->list as $el) {
    $date = new DateTime($el->dt_txt, new DateTimeZone('UTC'));
    $date->setTimezone(new DateTimeZone('Europe/Minsk'));
    $datef = date_format($date, 'H:i');
    $date_day = date_format($date, 'd');
    if ($date_day == $date_seg_day) {
        $s_wea = $s_wea . '<div>
					<h3 style="font-size: 1rem;">' . $datef . '</h3>
					<h3 style="font-size: 1rem;">' . round($el->main->temp) . '°C</h3>
					<img src="img/wea/' . $el->weather[0]->icon . '@2x.png" width="70" height="70">
					<h3 style="font-size: 1rem;">' . $el->weather[0]->description . '</h3>
				</div>';
    }
}
$s_wea = $s_wea . '</div>';
////////////////////////////////////////////
foreach ($data->list as $el) {
    $date = new DateTime($el->dt_txt, new DateTimeZone('UTC'));
    $date->setTimezone(new DateTimeZone('Europe/Minsk'));
    $datef = date_format($date, 'd.m H:i');
    $daten = date_format($date, 'w');
    $date_day = date_format($date, 'd');
    $date_month = date_format($date, 'm');
    $date_hour = date_format($date, 'H');
    $data_diff_day = date_diff($date_seg, $date)->format('%a');
    $data_diff_hour = $data_diff_day * 24 + date_diff($date_seg, $date)->format('%h');
    //style=""
    if ($i < 1) {
        echo '
<div class="div-okrugl" style="text-align: left;">
<h3><b>' . $cityname . '</b></h3>
<h3>' . $days[(date('w', strtotime($date_seg_format)))] . '<br>' . $date_seg_day . ' ' . $monthes[(date('n', strtotime($date_seg_format)))] . ' ' . '</h3><hr>

<div class="flex-container" style="font-family: GothamPro-Medium,Helvetica,Arial,sans-serif;">
	<div>
	<img style="display:inline;" src="img/wea/' . $el->weather[0]->icon . '@2x.png" width="100" height="100">
	<h1 style="display:inline;">' . round($el->main->temp) . '°C</h1>
	</div>

	<div style="text-align: left;">
	<h3 style="font-size: 1rem;">' . $el->weather[0]->description . '</h3>
	<h3 style="font-size: 1rem;">ощущается как ' . round($el->main->feels_like) . '°C</h3>
	<h3 style="font-size: 1rem;">влажность: ' . $el->main->humidity . '%</h3>
	<h3 style="font-size: 1rem;" style="display:inline;">ветер: ' . round($el->wind->speed) . ' м/с</h3> <img  style="transform: rotate(' . $el->wind->deg . 'deg);display:inline;" src="/img/arrow.png" width="32" height="32">
	</div>
</div>
' . $s_wea . '</div>';
    }

    if ($date_day != $date_seg_day) {
        if (($date_hour == '00') and ($iday < 3)) {
            echo '
		<div class="div-okrugl">
		<h3 style="font-size: 1.5rem;">' .
            $days[$daten] . ', ' .
            $date->format('d.m.Y') . '</h3>
		<hr>';

            echo '<h3 style="font-size: 1rem;"><strong>Температура</strong></br>ночью: ' . round($el->main->temp) . '°C</br>';
        }
        if (($date_hour == '06') and ($iday < 3)) {
            echo 'утром: ' . round($el->main->temp_min) . '°C</br>';
        }
        if (($date_hour == '12') and ($iday < 3)) {
            echo '<strong>днём: ' . round($el->main->temp_min) . '°C</strong></br>';
        }
        if (($date_hour == '18') and ($iday < 3)) {
            echo 'вечером: ' . round($el->main->temp_min) . '°C</h3><hr>
		<h3 style="font-size: 1rem;">' . $el->weather[0]->description . '<br>
		<img src="img/wea/' . $el->weather[0]->icon . '@2x.png" width="100" height="100"></h3><hr>
		<h3 style="font-size: 1rem;">Атмосферное давление: <br>' . round($el->main->pressure / 1.333) . ' мм рт.ст.<br></h3>
		<h3 style="font-size: 1rem;">Влажность: ' . $el->main->humidity . '%</td></tr>
		<h3 style="font-size: 1rem;">Скорость ветра: ' . round($el->wind->speed) . ' м/с
		<img style="transform: rotate(' . $el->wind->deg . 'deg);" src="/img/arrow.png" width="32" height="32"></h3>
		</div>';
            $iday++;
        }
    }
    $ar_we[] = [$datef, $el->main->temp_max];
    $i = $i + 1;
}
$jsonTable = json_encode($ar_we); //echo $jsonTable;
echo '</div></div>';
echo "<script>showForm();</script>";
include 'footer.php';

?>

<script>
function showForm() {
  document.querySelector('#wea').style.display='block';
}
</script>