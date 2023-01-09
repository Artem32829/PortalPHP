<?php
$kol_byn = 0;
$kol_usd = 0;
$kol_eur = 0;
$kol_rub = 0;

if ($_POST) {
    if (isset($_POST['datakursa'])) {$datakursa = $_POST['datakursa'];}

    if (isset($_POST['byn'])) {$datakursa = date("Y-m-d");
        $kol_byn = $_POST['byn'];
        $kol_usd = 0;
        $kol_eur = 0;
        $kol_rub = 0;}
    if (isset($_POST['usd'])) {$datakursa = date("Y-m-d");
        $kol_byn = 0;
        $kol_usd = $_POST['usd'];
        $kol_eur = 0;
        $kol_rub = 0;}
    if (isset($_POST['eur'])) {$datakursa = date("Y-m-d");
        $kol_byn = 0;
        $kol_usd = 0;
        $kol_eur = $_POST['eur'];
        $kol_rub = 0;}
    if (isset($_POST['rub'])) {$datakursa = date("Y-m-d");
        $kol_byn = 0;
        $kol_usd = 0;
        $kol_eur = 0;
        $kol_rub = $_POST['rub'];}
} else { $datakursa = date("Y-m-d");
    $kol_usd = 10;}

include 'menu.php';

$apiUrl = 'https://www.nbrb.by/api/exrates/rates?ondate=' . $datakursa . '&periodicity=0';
$crequest = curl_init();

curl_setopt($crequest, CURLOPT_HEADER, 0);
curl_setopt($crequest, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($crequest, CURLOPT_URL, $apiUrl);
curl_setopt($crequest, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($crequest, CURLOPT_VERBOSE, 0);
curl_setopt($crequest, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($crequest);

curl_close($crequest);
$data = json_decode($response);
//print_r($data);
$kurs_usd = $data[7]->Cur_OfficialRate;
$kurs_eur = $data[9]->Cur_OfficialRate;
$kurs_rub = $data[21]->Cur_OfficialRate / 100;

if ($kol_byn != 0) {$kol_usd = $kol_byn / $kurs_usd;
    $kol_eur = $kol_byn / $kurs_eur;
    $kol_rub = $kol_byn / $kurs_rub;}
if ($kol_usd != 0) {$kol_byn = $kol_usd * $kurs_usd;
    $kol_eur = $kol_usd * $kurs_usd / $kurs_eur;
    $kol_rub = $kol_usd * $kurs_usd / $kurs_rub;}
if ($kol_eur != 0) {$kol_byn = $kol_eur * $kurs_eur;
    $kol_usd = $kol_eur * $kurs_eur / $kurs_usd;
    $kol_rub = $kol_eur * $kurs_eur / $kurs_rub;}
if ($kol_rub != 0) {$kol_byn = $kol_rub * $kurs_rub;
    $kol_usd = $kol_rub * $kurs_rub / $kurs_usd;
    $kol_eur = $kol_rub * $kurs_rub / $kurs_eur;}

echo '
<div class="flex-container">
<div class="form-control div-okrugl">
<h5 id="kd">Курсы валют на ' . $datakursa . '</h5>
<form action="" method="POST" id="form">
<input type="date" name="datakursa" class="form-control" id="datakursa" value=' . $datakursa . ' max=' . date("Y-m-d") . ' min="2010-01-01"><br/>
</form>
<table class="table table-bordered table-striped" style="font-size:22px;    font-family: GothamPro-Medium, Helvetica, Arial, sans-serif;">
<tr>
	<th>Валюта</th>
	<th>Код</th>
	<th>Курс</th>
</tr>';
$s_usd = '';
$s_eur = '';
$s_rub = '';
$s_uah = '';
$s_pln = '';
foreach ($data as $el) {
    if ($el->Cur_Abbreviation == "USD") {$s_usd = '<tr><td align="left">' . $el->Cur_Scale . ' ' . $el->Cur_Name . '</td><td>' . $el->Cur_Abbreviation . '</td><td>' . number_format($el->Cur_OfficialRate, 4, ',', '') . '</td></tr>';}
    if ($el->Cur_Abbreviation == "EUR") {$s_eur = '<tr><td align="left">' . $el->Cur_Scale . ' ' . $el->Cur_Name . '</td><td>' . $el->Cur_Abbreviation . '</td><td>' . number_format($el->Cur_OfficialRate, 4, ',', ''). '</td></tr>';}
    if ($el->Cur_Abbreviation == "RUB") {$s_rub = '<tr><td align="left">' . $el->Cur_Scale . ' ' . $el->Cur_Name . '</td><td>' . $el->Cur_Abbreviation . '</td><td>' . number_format($el->Cur_OfficialRate, 4, ',', '') . '</td></tr>';}
    if ($el->Cur_Abbreviation == "UAH") {$s_uah = '<tr><td align="left">' . $el->Cur_Scale . ' ' . $el->Cur_Name . '</td><td>' . $el->Cur_Abbreviation . '</td><td>' . number_format($el->Cur_OfficialRate, 4, ',', '') . '</td></tr>';}
    if ($el->Cur_Abbreviation == "PLN") {$s_pln = '<tr><td align="left">' . $el->Cur_Scale . ' ' . $el->Cur_Name . '</td><td>' . $el->Cur_Abbreviation . '</td><td>' . number_format($el->Cur_OfficialRate, 4, ',', '') . '</td></tr>';}
}
echo $s_usd . $s_eur . $s_rub . $s_uah . $s_pln;
echo '
</table>
<details>
<summary>Все валюты</summary>
<input class="form-control" id="myInput" type="text" placeholder="Поиск" style="margin-top:1px; margin-bottom:1px;">
<table id="table" class="table table-bordered table-striped" style="font-size:22px;    font-family: GothamPro-Medium, Helvetica, Arial, sans-serif;">
<tr>
	<th><b>Валюта</b></th>
	<th><b>Код</b></th>
	<th><b>Курс</b></th>
</tr>
<tbody id="myTable">
';

foreach ($data as $el) {
    echo '
<tr>
	<td align="left">' . $el->Cur_Scale . ' ' . $el->Cur_Name . '</td>
	<td>' . $el->Cur_Abbreviation . '</td>
	<td>' . number_format($el->Cur_OfficialRate, 4, ',', '') . '</td>
</tr>';
}

echo '
</tbody>
</table>
</details>
</div>

<div class="form-control div-okrugl">
<h5 id="kd">Конвертер валют</h5></br>
<div style="font-size: 2rem;">
<form action="" method="POST" id="form_byn" >
	<img src="img/byn.png"/><label style="margin-left: 10px;">BYN</label>
	<input class="div-okrugl" type="text" name="byn" id="byn" value=' . str_replace(",0000", "", number_format($kol_byn, 4, ',', '')) . ' style="border-radius: .25rem;"></br></br>
</form>
<form action="" method="POST" id="form_usd">
	<img src="img/usd.png"/><label style="margin-left: 10px;">USD</label>
	<input class="div-okrugl" type="text" name="usd" id="usd" value=' . str_replace(",0000", "", number_format($kol_usd, 4, ',', '')) . ' style="border-radius: .25rem;"></br></br>
</form>
<form action="" method="POST" id="form_eur">
	<img src="img/eur.png"/><label style="margin-left: 10px;">EUR</label>
	<input class="div-okrugl" type="text" name="eur" id="eur" value=' . str_replace(",0000", "", number_format($kol_eur, 4, ',', '')) . ' style="border-radius: .25rem;"></br></br>
</form>
<form action="" method="POST" id="form_rub">
	<img src="img/rub.png"/><label style="margin-left: 10px;">RUB</label>
	<input class="div-okrugl" type="text" name="rub" id="rub" value=' . str_replace(",0000", "", number_format($kol_rub, 4, ',', '')) . ' style="border-radius: .25rem;"></br></br>
</form>
</div>

</div>
</div>
';



echo '
<script>
$(document).ready(function()
{
  $("#myInput").on("keyup", function() 
  {
    var value = $(this).val().toLowerCase();
    $("#myTable tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });  
});
</script>';

include 'footer.php';
?>

<script>
window.onload = function() {
    document.getElementById('datakursa').addEventListener('change', function(event) {
		document.getElementById("form").submit();
		});

	  document.getElementById('byn').addEventListener('change', function(event) {
		document.getElementById("form_byn").submit();
		});
	  document.getElementById('usd').addEventListener('change', function(event) {
		document.getElementById("form_usd").submit();
		});
	  document.getElementById('eur').addEventListener('change', function(event) {
		document.getElementById("form_eur").submit();
		});
	  document.getElementById('rub').addEventListener('change', function(event) {
		document.getElementById("form_rub").submit();
		});
}
</script>
