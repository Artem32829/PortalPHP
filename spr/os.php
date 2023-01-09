<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
function datagal($d) {
	if (sprintf('%02d',fmod($d,256)).'.'.sprintf('%02d',intdiv(fmod($d,65536),256)).'.'.intdiv($d,65536) != "00.00.0"){
	return sprintf('%02d',fmod($d,256)).'.'.sprintf('%02d',intdiv(fmod($d,65536),256)).'.'.intdiv($d,65536);}
	else
	{return "-";}
}
include('menu.php');

echo '
<h6>Поиск по инвентарным номерам</h6>
<div class="flex-container">
<div class="form-control div-okrugl">
<form action="os.php" method="POST">
    <p>Введите инвентарный номер:</p>
	<input class="form-control" type="text" name="invnum" /><br>
    <input class="btn btn-primary" type="submit" value="Поиск"><br><br>
</form></div>';

if (isset($_POST['invnum'])) {
	$serverName = "$$$$";
	$connectionInfo = array("Database" => "galaxy", "UID" => "info", "PWD" => "r7fQt3R7HO92");
	$conn = sqlsrv_connect($serverName, $connectionInfo);
	if ($conn === false) {
		die(print_r(sqlsrv_errors(), true));
	}
	$inv = preg_replace('/[^0-9]/ui', '', '00002095');
	$inv = preg_replace('/[^0-9]/ui', '', $_POST['invnum']);
	//$sql = "SELECT  [F\$INNUM],[F\$NAMEOS] FROM [T\$KATOS] where [F\$INNUM]='".$inv."'";			CONVERT(VARCHAR(600), SUBSTRING(X.M#Data, 4, 2000)) as MEMO
	$sql = "
SELECT K.[F\$INNUM],K.[F\$NAMEOS], K.[F\$ZAVNOM], K.[F\$GODV], K.[F\$DATV], P.[F\$NAME] as PNAME, M.[F\$NAME] as MNAME, S.[F\$STOIM] as STOIM, S.[F\$SUMIZN] as SUMIZN, S.[F\$SIZNM] as SIZNM,
SUBSTRING(X.M#Data, 4, 2000) as MEMO
FROM [T\$KATOS] AS K
left JOIN [T\$ALLMEMO] AS A ON ((K.F\$NREC=A.F\$CREC)and(A.F\$WTABLE=3000)and (A.F\$TIP=0))
left JOIN [XX\$Memo] AS X ON ((A.F\$NREC=X.M#NRec) and(x.[M#Code]=1477))
left JOIN [T\$KATPODR] AS P ON (P.F\$NREC=K.F\$CPODR)
left JOIN [T\$KATMOL] AS M ON (M.F\$NREC=K.F\$CMOL)
left JOIN [T\$SPKATOS] AS S ON (K.F\$NREC=S.F\$CKATOS)
where K.[F\$INNUM]='" . $inv . "'";

	$stmt = sqlsrv_query($conn, $sql);
	if ($stmt === false) {
		die(print_r(sqlsrv_errors(), true));
	}
	$result = [];
	while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
		$result[] = [
			'INNUM'  => $row['F$INNUM'],
			'NAMEOS' => $row['F$NAMEOS'],
			'ZAVNOM' => $row['F$ZAVNOM'],
			'MEMO' => $row['MEMO']
		];
		$mem = $row['MEMO'];
		echo '

<div class="form-control div-okrugl">
<table class="table table-bordered table-striped ">
	<tr>
	<td colspan="2"><b>Инв.№' . $row['F$INNUM'] . '</b></td>
	</tr>

	<tr style="text-align:left;">
	<td><b>Название</b></td>
	<td>' . $row['F$NAMEOS'] . '</td>
	</tr>

	<tr style="text-align:left;">
	<td><b>Заводской номер</b></td>
	<td>' . $row['F$ZAVNOM'] . '</td>
	</tr>

	<tr style="text-align:left;">
	<td><b>Дата ввода</b></td>
	<td>' . datagal($row['F$GODV']) . '</td>
	</tr>

	<tr style="text-align:left;">
	<td><b>Дата списания</b></td>
	<td>' . datagal($row['F$DATV']) . '</td>
	</tr>


	<tr style="text-align:left;">
	<td><b>Подразделение</b></td>
	<td>' . $row['PNAME'] . '</td>
	</tr>

	<tr style="text-align:left;">
	<td><b>МОЛ</b></td>
	<td>' . $row['MNAME'] . '</td>
	</tr>

	<tr style="text-align:left;">
	<td><b>Стоимость (остаточная) </b></td>
	<td>' . round($row['STOIM'],2) .' ('. round(round($row['STOIM'],2)-round($row['SUMIZN'],2)-round($row['SIZNM'],2),2).') бел. руб.</td>
	</tr>

</table>
<textarea class="form-control" id="myInput2" style="height:100px;">' . mb_convert_encoding($row['MEMO'], 'utf-8', 'cp866') . '</textarea><br>
</div>';
		$row['MEMO'];
	}
	sqlsrv_free_stmt($stmt);
}
echo '</div></div>';
include('footer.php');


/*
<p class="form-control">Инв.№' . $row['F$INNUM'] . ' (заводской №' . $row['F$ZAVNOM'] . ')</p>
<p class="form-control">Дата ввода: ' . datagal($row['F$GODV']) . '. Дата списания: ' . datagal($row['F$DATV']) . '</p>
<input class="form-control" type="text" value="' . $row['F$NAMEOS'] . '" id="myInput"><br>
<input class="form-control" type="text" value="' . $row['PNAME'] . '" id="myInput"><br>
<input class="form-control" type="text" value="' . $row['MNAME'] . '" id="myInput"><br>
<textarea class="form-control" id="myInput2" style="height:100px;">' . mb_convert_encoding($row['MEMO'], 'utf-8', 'cp866') . '</textarea><br>
<button class="btn btn-primary" onclick="myFunction()">Копировать название</button>
<button class="btn btn-primary" onclick="myFunction2()">Копировать описание</button><br>
echo '
<script>
  var text = document.querySelector("input");
  var output = document.querySelector("#length");
  var l = text.value.length;
  
  text.addEventListener("input", function() {output.textContent = text.value+text.value.length;});
  
  if (l < 3) text.addEventListener("input", function() {output.textContent = "rrrr";});
  
  function myFunction() {
  var copyText = document.getElementById("myInput");  
  copyText.select();
  document.execCommand("copy"); 
}

  function myFunction2() {
  var copyText = document.getElementById("myInput2");  
  copyText.select();
  document.execCommand("copy"); 
}  
</script>
';
*/