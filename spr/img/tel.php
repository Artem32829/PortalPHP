<?php
$host = "192.168.29.250:D:/db/bnsbase.gdb";
$username="SYSDBA";
$password="31332";
$charset="UTF8";
$dbh = ibase_connect($host, $username, $password, $charset);
include ('menu.php');

$drvyh=0;
$drseg=0;
if (date("N")==1) 
{
$stmt3 = "
select spisok.fio,spisok.e_mail, spisok.job, otdel.name,
substring(100+extract(day from spisok.birth_date) from 2 for 2)||'.'||substring(100+extract(month from spisok.birth_date) from 2 for 2) as dr
from spisok inner join otdel on otdel.id = spisok.otdel_id
where dateadd(year, extract(year from current_date) - extract(year from spisok.birth_date), spisok.birth_date) between dateadd (-2 day to current_date) and dateadd(-1 day to current_date)
order by dr, spisok.fio";
$sth3 = ibase_query($dbh, $stmt3);
$a=0;
while ($row3 = ibase_fetch_object($sth3)) 	
{
if ($a < 1)  
{
	$drvyh =1;
	echo '<details style="background: #d3b87b;"><summary> Дни рождения </summary>';
	echo '<div class="Box3"><b>На выходных день рождения был у:</b><br>';
}
echo $row3->FIO.'<a href = "mailto:'.$row3->E_MAIL.'?subject=Поздравление&body=C%20Днём%20рождения"><img  src="/img//mail.png" width="25" height="20" alt="Поздравить" ></a><br>'; 
$a=1;}	
};




$stmt2 = "
select spisok.fio,spisok.e_mail, spisok.job, otdel.name,
substring(100+extract(day from spisok.birth_date) from 2 for 2)||'.'||substring(100+extract(month from spisok.birth_date) from 2 for 2) as dr
from spisok inner join otdel on otdel.id = spisok.otdel_id
where dateadd(year, extract(year from current_date) - extract(year from spisok.birth_date), spisok.birth_date) between current_date and dateadd(0 day to current_date)
order by dr, spisok.fio";
$sth2 = ibase_query($dbh, $stmt2);
$a=0;
while ($row2 = ibase_fetch_object($sth2)) 	
{
if ($drvyh+$a<1) {echo '<details><summary  style="background: #F2F9FF;font-family: GothamPro-Regular,Helvetica,Arial,sans-serif;  padding: 5px 19px; "> Дни рождения </summary><div class="Box3">';}	
if ($a < 1)  {$drseg=1; echo '<b>Сегодня день рождения отмечают:</b><br>';}
echo $row2->FIO.'<a style="margin: 0 0 7px; color: #6f6f6f;font-size: 12px; line-height: 1.4;" href = "mailto:'.$row2->E_MAIL.'?subject=Поздравление&body=C%20Днём%20рождения"><img  src="/img//mail.png" width="25" height="20" alt="Поздравить" ></a><br>'; 
$a=1;}
if ($drseg+$drvyh>0){echo '</div></details>';}

//style=font-family: GothamPro-Regular,Helvetica,Arial,sans-serif;"
$stmtf = 'SELECT * FROM FILIAL';
$sthf = ibase_query($dbh, $stmtf);
echo '<form action="/index.php" id="form-select-depart" >  
	  <select style="font-size: 14px; line-height: 1.4;color: #6f6f6f;" name="otdel" id="otdel" class="form-control">';
	
if (empty($_GET["otdel"])) {echo '	<option  value="" disabled selected >Поиск по подразделению</option>';}	
if (empty($_GET["otdel"]) === false) { echo '	<option value="" disabled selected >Установлен фильтр по подразделению</option>';}		
  
$t = array();
while ($rowf = ibase_fetch_object($sthf))
{
	$t[$rowf->NAME] = $rowf->NAME;	
echo '<optgroup label="'.$rowf->NAME.'">';	
	$stmto = 'SELECT * FROM OTDEL where filial=' . intval($rowf->ID);
	$t[$rowf->NAME] = array();
	$stho = ibase_query($dbh, $stmto);
	while ($rowo = ibase_fetch_object($stho)) 
	{
		$t[$rowf->NAME][$rowo->ID] = $rowo->NAME;	
		$v=$rowo->ID;
		 if (strlen($t[$rowf->NAME][$rowo->ID])>0)
echo '<option value="'.$v.'">'.$t[$rowf->NAME][$rowo->ID].'</option>';
	}
echo '</optgroup>';	
}
echo'
</select>
</form> ';
//

echo '<input style="font-size: 14px; line-height: 1.4;"  class="form-control" id="myInput" type="text" placeholder="&nbspПоиск по ФИО, номеру или e-mail">';
echo '<table id="table" class="table table-bordered table-striped" data-cols-width="1,40,20,25,20,25,40,50">';
echo '<thead>';
echo '<tr>
<th style="width:5%;text-align: center">Фото</th>
<th style="width:40%;text-align: center">Фамилия Имя Отчество</th>
<th style="width:15;text-align: center">Внутренний</th>
<th style="width:15%;text-align: center">Полный</th>
<th style="width:15%;text-align: center">Мобильный</th>
<th style="width:5%;text-align: center">E-mail</th>
<th style="width:5%;text-align: center">Должность</th>
<th style="width:5%;text-align: center">Филиал/Отдел</th>
</tr>';
echo '</thead>';
echo '<tbody id="myTable">';
$stmt2 ='SELECT s.id, s.fio, s.mobile, s.tel, s.short_tel, s.e_mail, s.job, o.name, f.name as FIL FROM SPISOK  s inner join otdel o on o.id=s.otdel_id inner join filial f on f.id=o.filial where s.otdel_id<>282' . (empty($_GET['otdel']) ? '' : 'AND s.otdel_id=' .  intval($_GET['otdel'])) . 'order by s.id';
$sth2 = ibase_query($dbh, $stmt2);
while ($row2 = ibase_fetch_object($sth2)) 
{echo '<tr>';


$filename = 'img/photo/'.$row2->ID.'.jpg';
if (file_exists($filename)) { echo '<td align="center"><a href='.$filename.'><img style="border-radius: 4px;" src='.$filename.' width="70"/></a></td>'; } 
else { 
echo '<td align="center"><a href = "mailto:s.antonchik@bns.by?subject=Фото%20для%20загрузки%20на%20портал&body=Загрузите%20фото%20'.$row2->FIO.'(id='.$row2->ID.')%20на%20портал"><img style="border-radius: 4px;" src="/img//666.jpg" width="70"  alt="Отправить фото "'.$row2->ID.' ></a></td>';}//
echo '<td style="margin: 0 0 8px; max-width: 225px;font-family: GothamPro-Medium,Helvetica,Arial,sans-serif;
	  font-weight: 400; font-style: normal; color: #6f6f6f; font-size: 14px; line-height: 1.6;">'.$row2->FIO.'</td>
<td style="text-align: center; color: #6f6f6f;margin: 0 0 7px; font-size: 14px; line-height: 1.7;">'.$row2->SHORT_TEL.'</td>
<td style="margin: 0 0 7px; color: #6f6f6f;font-size: 14px; line-height: 1.4;">'.$row2->TEL.'</td>
<td style="margin: 0 0 7px; color: #6f6f6f;font-size: 14px; line-height: 1.4;">'.$row2->MOBILE.'</td>
<td><a style="margin: 0 0 7px; font-size: 12px; line-height: 1.4;" href="mailto:'.$row2->E_MAIL.'"  rel="noopener noreferrer">'.$row2->E_MAIL.'</a></td>	

<td style="margin: 0 0 7px; color: #6f6f6f;font-size: 14px; line-height: 1.4;">'.$row2->JOB.'</td>
<td style="margin: 0 0 7px; color: #6f6f6f;font-size: 14px; line-height: 1.4;"><i>'.$row2->FIL.' </i> / '.$row2->NAME.'</td>
</tr>';}
echo '</tbody>';
echo "</table>";
echo '<button id="btnExport" class="btn btn-primary">Сохранить в Excel</button>';
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

echo '
<script>
$(document).ready(function(){
    $("#btnExport").click(function() {
        let table = document.getElementsByTagName("table");
        TableToExcel.convert(table[0], { // html code may contain multiple tables so here we are refering to 1st table tag
           name: `Справочник.xlsx`, // fileName you could use any name
           sheet: {
              name: "Справочник БНС" // sheetName
           }
        });
    });
});
</script>';


echo '</body>';
echo '</html>';
ibase_free_result($sth);
ibase_close($dbh);
?>


