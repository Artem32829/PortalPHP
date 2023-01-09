<?php
//ini_set('error_reporting', E_ALL);
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);



function file_get_contents_curl($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Устанавливаем параметр, чтобы curl возвращал данные, вместо того, чтобы выводить их в браузер.
    curl_setopt($ch, CURLOPT_URL, $url);
    $data = curl_exec($ch);
    curl_close($ch);
    return json_decode($data);
}

if ((!isset($_POST['ORGNAME']))and(!isset($_POST['UNP'])) ) {
	include 'menu.php';
    echo '
<div class="flex-container">

<div class="form-control div-okrugl">
<h6>Поиск организации по наименованию (РБ)</h6>
<form action="" method="POST"><br>
	<input class="form-control" type="text" name="ORGNAME" id=inp1 placeholder="Введите наименование" /><br>
    <input class="btn btn-primary" type="submit" value="Поиск"><br>
</form></div>
</div>';
    echo '<div class="spinner"></div>';
    echo '<div class="load"></div>';
		include 'footer.php';
}

if (isset($_POST['ORGNAME'])) {
    echo '<div class="form-control div-okrugl">
    <table class="table table-bordered table-striped" id="table">
    <tbody id="myTable">';

    // Получение кратких сведений о субъекте хозяйствования
    $json = file_get_contents_curl('http://egr.gov.by/api/v2/egr/getShortInfoByRegName/' . $_POST['ORGNAME']);
    if (count($json) > 1)
        echo '<input class="form-control" id="myInput" type="text" placeholder="Фильтр по найденым организациям" style="margin-top:1px; margin-bottom:1px;">';
    foreach ($json as $record) {
        // Получение наименования юридического лица
        $json_name = file_get_contents_curl('http://egr.gov.by/api/v2/egr/getJurNamesByRegNum/' . $record->ngrn);
        $vnaimb = '';
        $vnb = '';
        foreach ($json_name as $record_name) {
            $vnaimb = $record_name->vnaimb;
            $vnb = $record_name->vnb;
        }

        echo '
    <tr><td>
    <div class="form-control div-okrugl">
    <table class="table table-bordered table-striped"  style="text-align:left;">
    <tr>
    <td style="width:30%;"><b>УНП</b></td>
    <td style="width:70%;">' . $record->ngrn . '</td>
    </tr>
    <tr>
    <td><b>Полное наименование</b></td>
    <td>' . $record->vnaim . '</td>
    </tr>
    <tr>
    <td><b>Сокращенное наименование</b></td>
    <td>' . $record->vn . '</td>
    </tr>
    <td><b>Полное наименование на белорусском</b></td>
    <td>' . $vnaimb . '</td>
    </tr>
    <tr>
    <td><b>Сокращенное наименование на белорусском</b></td>
    <td>' . $vnb . '</td>
    </tr>
    <tr>
    <td><b>Дата регистрации</b></td>
    <td>' . $record->dfrom . '</td>
    </tr>
    <tr>
    <td><b>Статус</b></td>
    <td>' . $record->nsi00219->vnsostk . '</td>
    </tr>
    </table>
    </div>
    </td></tr>';
    }
    echo '
    </div></tbody></table></div>';
} 

?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script type="text/javascript" src="load.js"></script>