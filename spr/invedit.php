<?php
include ('menu.php');
$inv_id = intval($_GET['inv-id']);
//Сохранение 

if (isset($_POST['edit_invnumber'])){ 
if ($_POST['edit_bd']==''){
$edit_query ="UPDATE minsk1 SET InvNumber = '".$_POST['edit_invnumber']."',
							NamePC = '".$_POST['edit_namepc']."',
							OC = '".$_POST['edit_oc']."',
							RAM = '".$_POST['edit_ram']."',
							DISK = '".$_POST['edit_disk']."',
							InstallProgram = '".$_POST['edit_innstprogramm']."',
							InstalKasp = '".$_POST['edit_instalkasp']."'
WHERE (InvNumber = ".$inv_id.")";	} else
{
$edit_query ="UPDATE minsk1 SET InvNumber = '".$_POST['edit_invnumber']."',
                            NamePC = '".$_POST['edit_namepc']."',
                            OC = '".$_POST['edit_oc']."',
                            RAM = '".$_POST['edit_ram']."',
                            DISK = '".$_POST['edit_disk']."',
                            InstallProgram = '".$_POST['edit_innstprogramm']."',
                            InstalKasp = '".$_POST['edit_instalkasp']."'
WHERE (InvNumber = ".$inv_id.")";		
}	
$mysqli->query($edit_query);
}

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
echo'<div class="Box1">';
echo'<form method="post" action="invedit.php?inv-id='.$inv_id.'">';

$res = $mysqli->query("SELECT * FROM minsk1 WHERE InvNumber=".$inv_id);
foreach ($res as $row2) 
{
echo'
<label><strong>Фамилия Имя Отчество</strong></label>
<input class="form-control" type="text" size="50" name="edit_invnumber" value="'.$row2['InvNumber'].'" ><br> 
<label><strong>Отдел</strong></label>
<input class="form-control" type="text" size="50" name="edit_namepc" value="'.$row2['NamePC'].'" ><br>
<label><strong>Городской номер</strong></label>
<input class="form-control" type="text" size="50" name="edit_oc" value="'.$row2['OC'].'" ><br>
<label><strong>Мобильный номер</strong></label>
<input class="form-control" type="text" size="50" name="edit_ram" value="'.$row2['RAM'].'" ><br>
<label><strong>Электронная почта</strong></label>
<input class="form-control" type="text" size="50" name="edit_disk" value="'.$row2['DISK'].'" ><br>
<label><strong>Должность</strong></label>
<input class="form-control" type="text" size="50" name="edit_innstprogramm" value="'.$row2['InstallProgram'].'" ><br>
<label><strong>Дата рождения</strong></label>
<input class="form-control" type="date" size="50" name="edit_instalkasp" value="'.$row2['InstallKasp'].'" ><br>
<label><strong>IP</strong></label>';

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
