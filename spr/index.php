<?php
//include ('newyear.php');
include ('menu.php');

echo '<input class="form-control" id="myInput" type="text" placeholder="Поиск по ФИО, номеру или e-mail" style="margin-top:1px; margin-bottom:1px;">';
echo '<table id="table" class="table table-bordered table-striped">';
echo '<thead>';
echo '<tr>
<th style="width:5%;text-align: center">Фото</th>
<th style="width:20%;text-align: center">Фамилия Имя Отчество</th>
<th style="width:13%;text-align: center">Полный</th>
<th style="width:13%;text-align: center">Мобильный</th>
<th style="width:10%;text-align: center">E-mail</th>
<th style="width:12%;text-align: center">Должность</th>
<th style="width:12%;text-align: center">Отдел</th>
</tr>';
echo '</thead>';
echo '<tbody id="myTable">';

$res = $mysqli->query("SELECT * FROM SPISOK");
foreach ($res as $row2) 
{echo '<tr>';


$filename = 'img/photo/'.$row2['ID'].'.jpg';
if (file_exists($filename)) { echo '<td align="center"><a href='.$filename.'><img src='.$filename.' width="70"/></a></td>'; } 
else { 
echo '<td align="center"><a href = "mailto:s.antonchik@bns.by?subject=Фото%20для%20загрузки%20на%20портал&body=Загрузите%20фото%20'.$row2['FIO'].'(id='.$row2['ID'].')%20на%20портал"><img src="/img//666.jpg" width="70"  alt="Отправить фото "'.$row2['ID'].' ></a></td>';}//

echo '<td>'.$row2['FIO'].'</td>';

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~	TEL
echo '<td style="text-align:center">';
$regexp = "/(\\s\\|\\s)/ui";
$regexp_fax_search = "/ф/ui";
$regexp_fax = "/[\\(\\)\\-0-9]{3,15}/ui";

$match=preg_split($regexp, $row2['TEL']);
foreach ($match as $m) {
	$fax_serch = [];
	if (preg_match($regexp_fax_search,$m,$fax_serch)==1){ 
		$fax = [];
		preg_match($regexp_fax,$m,$fax);
		echo '<img  src="/img//fax.png" width="20" height="20" title = "Телефон/факс">';
		echo "{$fax[0]}<br>";
		}
	else {
		if (strlen($m)>2){
			echo '<img src="/img//tel.png" width="12" height="18" title = "Телефон">';}
		echo $m."<br>";
		}
}
echo '</td>';
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~	MOBILE
echo '<td style="text-align:center">';
$regexp = "/(\\s\\|\\s)/ui";
$regexp_fax_search = "/ф/ui";
$regexp_fax = "/[\\(\\)\\-0-9]{3,15}/ui";

$match=preg_split($regexp, $row2['MOBILE']);
foreach ($match as $m) {
	$fax_serch = [];
	if (preg_match($regexp_fax_search,$m,$fax_serch)==1){ 
		$fax = [];
		preg_match($regexp_fax,$m,$fax);
		echo '<img  src="/img//fax.png" width="20" height="20" alt="Факс">';
		echo "{$fax[0]}<br>";
		}
	else {
		if (strlen($m)>2){
			echo '<img  src="/img//mobile.png" width="18" height="18" title = "Мобильный телефон">';}
		echo $m."<br>";
		}
}
echo '</td>';
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~


echo'<td><a href="mailto:'.$row2['E_MAIL'].'"  rel="noopener noreferrer">'.$row2['E_MAIL'].'</a></td>	

<td style="font-size: 13px; line-height: 1.4;">'.$row2['JOB'].'</td>
<td style="font-size: 13px; line-height: 1.4;">'.$row2['OTDEL'].'</td>
</tr>';}
echo '</tbody>';
echo "</table>";
echo '<button id="btnExport" class="btn btn-secondary">Сохранить в Excel</button>';
//~ таблицаработников
echo '</div>';	

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
  $("#otdel").on("change", function() 
  {
    $("#form-select-depart").submit();
  });
});
</script>';

include ('footer.php');
echo '
<script>
$(document).ready(function(){
    $("#btnExport").click(function() {
        let table = document.getElementsByTagName("table");
        TableToExcel.convert(table[table.length-1], { // html code may contain multiple tables so here we are refering to 1st table tag
           name: `Справочник.xlsx`, // fileName you could use any name
           sheet: {
              name: "Справочник БНС" // sheetName
           }
        });
    });
});
</script>';


$mysqli->close();
?>