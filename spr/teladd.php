<?php
include ('menu.php');
//Сохранение 
if (isset($_POST['edit_fio'])){ 
$edit_query ="INSERT INTO SPISOK (FIO, TEL, MOBILE, E_MAIL, JOB, IP, OTDEL)
  values ('".$_POST['edit_fio']."',
		  '".$_POST['edit_tel']."',
		  '".$_POST['edit_mobile']."',
		  '".$_POST['edit_e_mail']."',
		  '".$_POST['edit_job']."',
		  '".$_POST['edit_ip']."',
		  '".$_POST['edit_otdel']."')";
$mysqli->query($edit_query);
}

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
echo'<h6>Добавление нового сотрудника в справочник</h6>';
echo'<div class="flex-container"><div class="div-okrugl form-control">';
echo'<form  method="post" action="teladd.php?page=main">';
echo'
<p><strong>Фамилия Имя Отчество</strong></p>
<input class="form-control" type="text" size="50" name="edit_fio" placeholder = "Иванов Иван Иванович" ><br> 
<p><strong>Городской номер</strong></p>
<input class="form-control" type="text" size="50" name="edit_tel" placeholder="(0163)664-65-65" ><br>
<p><strong>Мобильный номер</strong></p>
<input class="form-control" type="text" size="50" name="edit_mobile" placeholder="(029)189-76-54" ><br>
<p><strong>Электронная почта</strong></p>
<input class="form-control" type="text" size="50" name="edit_e_mail" placeholder="I.Ivanov@bns.by" ><br> </div><div class="div-okrugl form-control">
<p><strong>Должность</strong></p>
<input class="form-control" type="text" size="50" name="edit_job" placeholder="Специалист" ><br>
<p><strong>Дата рождения</strong></p>
<input class="form-control" type="date" size="50" name="edit_bd"><br>
<p><strong>Отдел</strong></p>
<input class="form-control" type="text" size="50" name="edit_otdel" placeholder="IT" ><br>
<p><strong>IP (можно не заполнять)</strong></p>
<input class="form-control" type="text" size="50" name="edit_ip" placeholder="192.168.1.1" ><br>';
//

echo'<br><input class="btn btn-success" type="submit" value="Добавить" ><strong>    </strong>';
echo'<a class="btn btn-danger" href="/authteltp.php?page=main"> Назад </a>';
echo'</form>';
echo'</div>';
include ('footer.php');
$mysqli->close();
?>