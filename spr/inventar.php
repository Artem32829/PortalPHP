<?php
include ('menu.php');

echo '
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Обходной лист</title> 
<link rel="stylesheet" href="checkboxcss.css">
<link rel="stylesheet" href="buttoncss.css">

<style>
#container {
  display: flex;
}
</style>

</head>';
echo '

<body>  
<form action="mail_send.php"  method="POST">
<fieldset class="form-control">
<legend>Обходной лист</legend>
<div id="container">
<input class="form-control" name="myInputname" type="text" placeholder="ФИО сотрудника" style="margin-top:1px; margin-bottom:1px;">
<input class="form-control" name="position" type="text" placeholder="Подразделение" style="margin-top:1px; margin-bottom:1px;">
<select name="filial" class="form-control">
<option>Центральный аппарат</option>
<option>Минск-1</option>
<option>Минск-2</option>
<option>Пинск</option>
<option>Мозырь</option>
<option>Новополоцк</option>
<option>Брест</option>
<option>Гомель</option>
<option>Годно</option>
</select>
</div>
    
<div class="form-control">
<div class="form-control">
<p>Удалить учетные записи из:</p>
    <div>
      <input type="checkbox" id="certus" name="certus" value="yes">
      <label for="scales">Certus+</label>
    </div>
    <div>
      <input type="checkbox" id="domen" name="domen" value="yes">
      <label for="horns">Доменная учетная запись</label>
    </div>
    
    <div>
      <input type="checkbox" id="directum" name="directum" value="yes">
      <label for="horns">Directum</label>
    </div>
    <div>
      <input type="checkbox" id="galaxy" name="galaxy" value="yes">
      <label for="horns">Галактика</label>
    </div>
    <div>
      <input type="checkbox" id="dms" name="dms" value="yes">
      <label for="horns">ДМС</label>
    </div>
    </div>
    <br>
    <div class="form-control">
    <p>Прочее:</p>
    <div id="container">
    <div>
      <input type="checkbox" id="card" name="card">
      <label for="horns">Карта-пропуск</label>
    </div>
    <input  name="numbercard" type="text" placeholder="Номер карты"    style="margin-top:1px; margin-left:10px;">
    </div>    
    <div>
      <input type="checkbox" id="rule" name="rule">
      <label for="horns">Правила страхования</label>
    </div>
    </div>
    </div>
    <br>
    <a><button    id="btnSend" class="custom-btn btn-16"  >Отправить обходной лист</button></a>
</fieldset>
</form>
 </body>';
 
 ?>

   
   





