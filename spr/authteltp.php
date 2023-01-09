<?php
include ('menu.php');
echo'<div class="btn btn-link"><img src="/img/add_user.png" width="20" height="20"><a href="/teladd.php?page=main"> Добавить нового сотрудника в справочник </a></div>';	
//echo'<div class="btn btn-link"><img src="/img/edit_podr.png" width="20" height="20"><a href="/otdel.php?page=main"> Редактировать структуру </a></div>';
echo'<div class="btn btn-link"><img src="/img/stat.png" width="20" height="20"><a href="stat.php?page=main"> Статистика</a></div>';
//echo'<div class="btn btn-link"><img src="/img/OS.png" width="20" height="20"><a href="/os.php"> Поиск по ОС</a></div>';

echo '<input class="form-control" id="myInput" type="text" placeholder="&nbspПоиск по ФИО, номеру или e-mail">';
echo '<table class="table table-bordered table-striped">';
echo '<thead>';
echo '<tr>
<th style="text-align: center"><tt>ФИО</tt></th>
<th style="text-align: center"><tt>Внутренний</tt></th>
<th style="text-align: center"><tt>Полный</tt></th>
<th style="text-align: center"><tt>Мобильный</tt></th>
<th style="text-align: center"><tt>E-mail</tt></th>
<th style="text-align: center"><tt>Филиал/Отдел</tt></th>
<th colspan="3" style="text-align: center"><tt>IP</tt></th>
<!-- <th style="text-align: center"> </th> -->
</tr>';
    echo '</thead>';
    echo '<tbody id="myTable">';
	

$res = $mysqli->query("SELECT * FROM SPISOK");
foreach ($res as $row2) 
{
echo '<tr>
<td><tt>' . $row2['FIO'] . '</tt></td>
<td><tt>' . $row2['TEL'] . '</tt></td>
<td><tt>' . $row2['MOBILE'] . '</tt></td>
<td><tt><a href="mailto:' . $row2['E_MAIL'] . '"  rel="noopener noreferrer">' . $row2['E_MAIL'] . '</a></tt></td>	
<td><tt><b>/ ' . $row2['OTDEL'] . '</b></tt></td>
<td><tt><div class="ip" data-ip = '.$row2['IP'].'><span>'.$row2['IP'].'</span></td><td align="center"></tt>';
if ($row2['IP'] != '') {
  echo '<img class="clipboard" src="/img/copy.png" width="20" height="20" alt=". $row2["IP"] .">';
}
 // echo '<button class="btn"> ... </button>
 echo'</td></div></td>
  <td align="center" >
<a href="/teledit.php?tel-id='.$row2['ID'].'"><img src="/img/edit_user.png" width="20" height="20" alt="Изменить"></a>
  </td>
  </tr>';
}
    echo '</tbody>';
    echo "</table>";
    //~ таблицаработников
    echo '</div>';

//Всплывающая форма
    echo '<div class="modal">
<div class="modal__dialog">
  <div class="modal__content">
    <form action="#">
      <div data-close class="modal__close">&times;</div>
      <div class="modal__title">Изменение ip-адреса</div>
      <input required placeholder="IP-адрес" name="ip" type="text" class="modal__input">
      <button class="btn btn_dark">Сохранить</button>
    </form>
  </div>
</div>
</div>';

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

  $("#otdel").on("change", function() {
    $("#form-select-depart").submit();
  });

  $(".ip").on("click", "button", function() {
    $(".modal").fadeToggle("fast");     
  })

  $(".btn-sm").click(function() {
    $(".modal").fadeToggle("fast"); 
  });

  $(".modal__close").click(function() {
    $(".modal").fadeToggle("fast"); 
  });

  $(document).keyup(function(e) {
    if (e.keyCode == 27 && $(".modal").css("display") != "none") {
      $(".modal").fadeToggle("fast");
    }
  });

  $(".clipboard").click(function() {
    let currentRow = $(this).closest("tr");
    let ip = currentRow.find(".ip span").text();
    copyToClipboard(ip);
    $("#myTooltip").html(ip)
    // alert("Скопировано: " + ip);
  })

  function copyToClipboard(text) {
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val(text).select();
    document.execCommand("copy");
    $temp.remove();
}

});
</script>';