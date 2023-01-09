<?php
include ('menu.php');
$tel_id = intval($_GET['tel-id']);
//Сохранение 

if (isset($_POST['edit_fio'])){ 
if ($_POST['edit_bd']==''){
$edit_query ="UPDATE SPISOK SET FIO = '".$_POST['edit_fio']."',
							TEL = '".$_POST['edit_tel']."',
							MOBILE = '".$_POST['edit_mobile']."',
							E_MAIL = '".$_POST['edit_e_mail']."',
							JOB = '".$_POST['edit_job']."',
							IP = '".$_POST['edit_ip']."',
							OTDEL = '".$_POST['edit_otdel']."'
WHERE (ID = ".$tel_id.")";	} else
{
$edit_query ="UPDATE SPISOK SET FIO = '".$_POST['edit_fio']."',
							TEL = '".$_POST['edit_tel']."',
							MOBILE = '".$_POST['edit_mobile']."',
							E_MAIL = '".$_POST['edit_e_mail']."',
							JOB = '".$_POST['edit_job']."',
							IP = '".$_POST['edit_ip']."',
							BIRTH_DATE = '".$_POST['edit_bd']."',
							OTDEL = '".$_POST['edit_otdel']."'
WHERE (ID = ".$tel_id.")";		
}	
$mysqli->query($edit_query);
}

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
echo'<div class="Box1">';
echo'<form method="post" action="teledit.php?tel-id='.$tel_id.'">';

$res = $mysqli->query("SELECT * FROM SPISOK WHERE id=".$tel_id);
foreach ($res as $row2) 
{
echo'
<label><strong>Фамилия Имя Отчество</strong></label>
<input class="form-control" type="text" size="50" name="edit_fio" value="'.$row2['FIO'].'" ><br> 
<label><strong>Отдел</strong></label>
<input class="form-control" type="text" size="50" name="edit_otdel" value="'.$row2['OTDEL'].'" ><br>
<label><strong>Городской номер</strong></label>
<input class="form-control" type="text" size="50" name="edit_tel" value="'.$row2['TEL'].'" ><br>
<label><strong>Мобильный номер</strong></label>
<input class="form-control" type="text" size="50" name="edit_mobile" value="'.$row2['MOBILE'].'" ><br>
<label><strong>Электронная почта</strong></label>
<input class="form-control" type="text" size="50" name="edit_e_mail" value="'.$row2['E_MAIL'].'" ><br>
<label><strong>Должность</strong></label>
<input class="form-control" type="text" size="50" name="edit_job" value="'.$row2['JOB'].'" ><br>
<label><strong>Дата рождения</strong></label>
<input class="form-control" type="date" size="50" name="edit_bd" value="'.$row2['BIRTH_DATE'].'" ><br>
<label><strong>IP</strong></label>
<input class="form-control" type="text" size="50" name="edit_ip" value="'.$row2['IP'].'" ><br>';
}
echo'<input class="btn btn-success" type="submit" value="Сохранить" ><strong>  </strong>';
echo'<a class="btn btn-danger" href="/authteltp.php?page=main"> Назад </a>';
echo'</form>';
echo'</div>';
$mysqli->close();
/*
<select name="edit_otdel" class="form-control">';

$stmt_otd = 'select otdel.id as ID, otdel.name as NAME 
from otdel
where otdel.filial in (
SELECT filial.id
FROM spisok
inner join OTDEL on otdel.id=spisok.otdel_id
inner join filial on filial.id=otdel.filial
WHERE spisok.id ='.$row->ID.') and(otdel.name is not null) order by otdel.name';
$sth_otd = ibase_query($dbh, $stmt_otd);
while ($row_otd = ibase_fetch_object($sth_otd)) 
{if ($row_otd->ID==$row->OTDEL_ID){echo'<option selected value="'.$row_otd->ID.'">'.$row_otd->NAME.'</option>';}
 else 					  {echo'<option value="'.$row_otd->ID.'">'.$row_otd->NAME.'</option>';}}	 
echo'</select><br>
*/

?>
