<?php

include ('menu.php');
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
$db_host = "$$";
$db_user = "$$"; // Логин БД
$db_password = "$$!"; // Пароль БД
$db_base = "glpi"; // Имя БД
$mysqli = new mysqli($db_host, $db_user, $db_password, $db_base);
$mysqli->set_charset('utf8');
$temp = 0;

echo '



<div>
<h6>Инвентаризация</h6>
<table class="antivirus">
<tr>
    <th rowspan="2" class="first">Антивирус</th>
    <td></td>    
    <td ></td>
  </tr>  
</table>

<table class="processor">
<tr>
    <th rowspan="2" class="first">Процессор</th>
    <td>asf</td>    
    <td >asf</td>
  </tr> 
</table>

<table class="OP">
<tr>
    <th rowspan="2" class="first">Оперативная память</th>
    <td>asf</td>    
    <td >asf</td>
  </tr> 
</table>

<table class="RAM">
<tr>
    <th rowspan="2" class="first">Жесткий диск</th>
    <td>asf</td>    
    <td >asf</td>
  </tr> 
</table>
<input  type="text" placeholder="&nbspИнвентарный номер">  <input class="btn btn-success" type="submit" value="Поиск">
</div>';

echo '<input class="form-control" id="myInput" type="text" placeholder="&nbspПоиск по ФИО или ИНВ №">';
// таблица работников
echo '<div class="form-control" style="overflow: auto;">';
echo '<table class="table table-bordered table-striped" >';
echo '<thead  style="text-align: center">';
echo '
<tr>
<td rowspan="3" class="first">Название антивируса</td>

<td>Процессор</td>
<td>Опертивная память</td>
<th>Размер ОП</th>
<th>Жесткий диск</th>
<th>Емкость</th>
</tr>';
echo '</thead>';
echo '<tbody id="myTable">';
$query="
select glpi.glpi_computers.id as 'ID', glpi.glpi_computers.`name` as 'Имя ПК'
from glpi.glpi_computers
";

$res = $mysqli->query($query);
foreach ($res as $row2) 
{
if($row2['Имя ПК'] == 'PC3385')
{
    $id_pc = $row2['ID'];
        $name_pc = $row2['Имя ПК'];
        //print_r($name_pc);
}
}


//sql запрос выборка антивирус
$queryanti="
SELECT glpi.glpi_computerantiviruses.`name`, glpi.glpi_computerantiviruses.is_active
FROM glpi.glpi_computerantiviruses 
WHERE glpi.glpi_computerantiviruses.id = $id_pc
";
$res2 = $mysqli->query($queryanti);
{
    foreach ($res2 as $result) 
    {
        
    }
}


//sql запрос выборка id процессора
$queryprocid ="
select deviceprocessors_id from
glpi_items_deviceprocessors as idev
LEFT JOIN glpi_computers as comp ON idev.items_id = comp.id
where comp.`name` = '$name_pc'
";
$res3 = $mysqli->query($queryprocid);
    foreach ($res3 as $resultprocid) 
    {       
        $id_processor = $resultprocid['deviceprocessors_id'];	
        	
    }

//sql запрос выборка процессора по id



$querymemoryid ="
select glpi_items_devicememories.devicememories_id, size from
glpi_items_devicememories
LEFT JOIN glpi_computers  ON glpi_items_devicememories.items_id = glpi_computers.id
where glpi_computers.`name` = '$name_pc'
";
$res8 = $mysqli->query($querymemoryid);
    foreach ($res8 as $resultmemoryid) 
    {       
        $id_memorys = $resultmemoryid['devicememories_id'];
        $size_memorys = $resultmemoryid['size'];  
    }

     
        $querymemory ="
    select designation
from glpi_devicememories 
where id = $id_memorys
    ";
    $res9 = $mysqli->query($querymemory);
foreach ($res9 as $resultmemory) 
{
    


            

                    

}


$queryharddiskid = "
select deviceharddrives_id, capacity from
glpi_items_deviceharddrives as idev
LEFT JOIN glpi_computers as comp ON idev.items_id = comp.id
where comp.`name` = '$name_pc'
";
$res5 = $mysqli->query($queryharddiskid); {
    foreach ($res5 as $resultdiskid) {
        $id_deviceharddisk = $resultdiskid['deviceharddrives_id'];
        $capasitydisk = $resultdiskid;
    }
}

$queryproc = "
select designation 
from glpi_deviceprocessors 
where id = $id_processor
";
$res4 = $mysqli->query($queryproc); 
foreach ($res4 as $resultproc) {

}

$queryharddisk = "
            select designation from
            glpi_deviceharddrives
            WHERE id = $id_deviceharddisk
            ";
                    $res6 = $mysqli->query($queryharddisk); {
                        foreach ($res6 as $resultdisk) {

            
                    
                        }

                    }

                    echo '<tr style="margin: 0 0 7px; color: #6f6f6f;font-size: 14px; line-height: 1.4;">

                    <td>' . $result . '</td>
                    <td>' . implode($resultproc) . '</td>
                    <td>' . implode($resultmemory) . '</td>
                    <td>' . $size_memorys . '</td>
                    <td>' . implode($resultdisk) . '</td>
                    <td>' . implode($capasitydisk) . '</td></tr>';




echo '</tbody>';
echo "</table>";
echo "</div>";
include ('footer.php');
$mysqli->close();
echo '
<script>
$(document).ready(function()
{
  $("#myInput").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#myTable tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});
</script>';
