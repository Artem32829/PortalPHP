<?php
//include ('menu.php');
$db_host = "$$$";
$db_user = "$$$"; // Логин БД
$db_password = "2341"; // Пароль БД
$db_base = 'info'; // Имя БД
$mysqli = new mysqli($db_host, $db_user, $db_password, $db_base);
$mysqli->set_charset('utf8');

echo '<h6>Дни рождения сотрудников</h6>';

// таблица работников
echo '<table class="table table-bordered table-striped" >';
echo '<thead  style="text-align: center">';
echo '<tr><th>ФИО</th><th>Должность</th><th>Отдел</th><th>Дата</th></tr>';
echo '</thead>';
echo '<tbody id="myTableDR">';
$query = "
SELECT FIO, JOB, OTDEL, DATE_FORMAT( BIRTH_DATE, '%d.%m' ) as DR 
FROM SPISOK
WHERE
DAYOFYEAR(BIRTH_DATE) >= DAYOFYEAR(CURDATE()) or
DAYOFYEAR(BIRTH_DATE) < DAYOFYEAR(CURDATE() + 14)
ORDER BY DR";
$res = $mysqli->query($query);
foreach ($res as $row2) 
{
echo '<tr style="margin: 0 0 7px; color: #6f6f6f;font-size: 14px; line-height: 1.4;"><td>'.$row2["FIO"].'</td><td>'.$row2["JOB"].'</td><td>'.$row2["OTDEL"].'</td><td style="text-align: center">'.$row2["DR"].'</td></tr>';
}
echo '</tbody>';
echo "</table>";

include ('footer.php');
$mysqli->close();