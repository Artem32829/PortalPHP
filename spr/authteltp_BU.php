<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Справочник для техподдержки</title>
  <link rel="stylesheet" href="img/auth.css">
    <link rel="apple-touch-icon" sizes="180x180" href="/img/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/img//favicon-16x16.png">
</head>

<body>

  <?php
  session_start(); //Запускаем сессии
  /**
   * Класс для авторизации
   * @author дизайн студия ox2.ru
   */
  class AuthClass
  {
    /**
     * Проверяет, авторизован пользователь или нет
     * Возвращает true если авторизован, иначе false
     * @return boolean
     */
    public function isAuth()
    {
      if (isset($_SESSION["is_auth"])) { //Если сессия существует
        return $_SESSION["is_auth"]; //Возвращаем значение переменной сессии is_auth (хранит true если авторизован, false если не авторизован)
      } else return false; //Пользователь не авторизован, т.к. переменная is_auth не создана
    }

    /**
     * Авторизация пользователя
     * @param string $login
     // * @param string $passwors
     */
    public function auth()
    {
      //проверяем авторизацию в функции login()
      if (login() != false) {
        $_SESSION["is_auth"] = true; //Делаем пользователя авторизованным
        return true;
      } else { //Логин и пароль не подошел
        return false;
      }
    }

    /**
     * Метод возвращает логин авторизованного пользователя
     */
    public function getLogin()
    {
      if ($this->isAuth()) { //Если пользователь авторизован
        return $_SESSION["fullname"]; //Возвращаем логин, который записан в сессию
      }
    }

    public function out()
    {
      $_SESSION = array(); //Очищаем сессию
      session_destroy(); //Уничтожаем
    }
  } /* class AuthClass ends */

  $auth = new AuthClass();

  if (!empty($_POST['username']) && !empty($_POST['password']))  //Если логин и пароль были введены
  {
    $auth->auth(); //проверяем авторизацию
  }

  if (isset($_GET["is_exit"])) { //Если нажата кнопка выхода
    if ($_GET["is_exit"] == 1) {
      $auth->out(); //Выходим
      header("Location: authteltp.php?is_exit=0"); //Редирект после выхода
    }
  }

  if ($auth->isAuth()) { // Если пользователь авторизован
    /* Здесь выполняется код при успешной авторизации пользователя */
    //header("Location: /pravt.php"); //перенаправляем его на главную страницу скрипта Asterisk CDR Viewer
   
    include('menu.php');
    echo'<div class="btn btn-link"><img src="/img/add_user.png" width="20" height="20"><a href="/teladd.php?page=main"> Добавить нового сотрудника в справочник </a></div>';	
    echo'<div class="btn btn-link"><img src="/img/edit_podr.png" width="20" height="20"><a href="/otdel.php?page=main"> Редактировать структуру </a></div>';
	echo'<div class="btn btn-link"><img src="/img/stat.png" width="20" height="20"><a href="stat.php?page=main"> Статистика</a></div>';
	echo'<div class="btn btn-link"><img src="/img/OS.png" width="20" height="20"><a href="/os.php"> Поиск по ОС</a></div>';
	
    //
    $stmtf = 'SELECT * FROM FILIAL';
    $sthf = ibase_query($dbh, $stmtf);
    echo '<form action="/authteltp.php" id="form-select-depart" >  
	  <select name="otdel" id="otdel" class="form-control">';

    if (empty($_GET["otdel"])) {
      echo '	<option value="" disabled selected >Поиск по подразделению</option>';
    }
    if (empty($_GET["otdel"]) === false) {
      echo '	<option value="" disabled selected >Установлен фильтр по подразделению</option>';
    }

    $t = array();
    while ($rowf = ibase_fetch_object($sthf)) {
      $t[$rowf->NAME] = $rowf->NAME;
      echo '<optgroup label="' . $rowf->NAME . '">';
      $stmto = 'SELECT * FROM OTDEL where filial=' . intval($rowf->ID);
      $t[$rowf->NAME] = array();
      $stho = ibase_query($dbh, $stmto);
      while ($rowo = ibase_fetch_object($stho)) {
        $t[$rowf->NAME][$rowo->ID] = $rowo->NAME;
        $v = $rowo->ID;
        if (strlen($t[$rowf->NAME][$rowo->ID]) > 0)
          echo '<option value="' . $v . '">' . $t[$rowf->NAME][$rowo->ID] . '</option>';
      }
      echo '</optgroup>';
    }
    echo '
</select>
</form> ';
    //
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
    $stmt2 = 'SELECT s.id, s.fio, s.mobile, s.tel, s.short_tel, s.e_mail, s.job, o.name, f.name as FIL, s.ip  FROM SPISOK  s inner join otdel o on o.id=s.otdel_id inner join filial f on f.id=o.filial where s.otdel_id<>282' . (empty($_GET['otdel']) ? '' : 'AND s.otdel_id=' .  intval($_GET['otdel'])) . 'order by s.id';
    $sth2 = ibase_query($dbh, $stmt2);
    while ($row2 = ibase_fetch_object($sth2)) {
      echo '<tr>
<td><tt>' . $row2->FIO . '</tt></td>
<td style="text-align: center"><tt>' . $row2->SHORT_TEL . '</tt></td>
<td><tt>' . $row2->TEL . '</tt></td>
<td><tt>' . $row2->MOBILE . '</tt></td>
<td><tt><a href="mailto:' . $row2->E_MAIL . '"  rel="noopener noreferrer">' . $row2->E_MAIL . '</a></tt></td>	
<td><tt><i>' . $row2->FIL . ' </i><b>/ ' . $row2->NAME . '</b></tt></td>
<td><tt><div class="ip" data-ip = '.$row2->ID.'><span>'.$row2->IP.'</span></td><td align="center"></tt>';
if ($row2->IP != '') {
  echo '<img class="clipboard" src="/img/copy.png" width="20" height="20" alt=". $row2->IP .">';
}
 // echo '<button class="btn"> ... </button>
 echo'</td></div></td>
  <td align="center" >
<a href="/teledit.php?tel-id='.$row2->ID.'"><img src="/img/edit_user.png" width="20" height="20" alt="Изменить"></a>
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

  } else { //Если не авторизован, показываем форму ввода логина и пароля
  ?>

    <link rel="stylesheet" type="text/css" href="hd.css" />
    <link rel="stylesheet" href="css/bootstrap.min.css">

    <div class="login-page">
      <div class="container">

        <form class="login-form" method="post" action="authteltp.php">
          <br><a href="index.php?page=main"><img src="/img/logo-svg.svg" alt="Страховая компания «Белнефтестрах»" title="Страховая компания «Белнефтестрах»"></a><br><br>
          <p>Просмотр доступен только отделу информационных технологий и развитя</p>
          <label for="username"></label><input class="form-control" id="username" type="text" name="username" placeholder="Имя пользователя" />
          <label for="password"></label><input class="form-control" id="password" type="password" name="password" placeholder="Пароль" />
          <input class="btn btn-primary" type="submit" value="Войти" name="submit" />
          <?php if (!empty($login_error)) {
            echo "<div style=" . "color:red;" . ">" . $login_error . "</div>";
          }  ?>
        </form>
        <br><br><a class=link href="help\PC\\Как узнать имя пользователя.pdf" class="product" target="_blank">Как узнать имя пользователя</a></br>
      </div>
    </div>
  <?php } ?>

  <?php
  function editIP() {

  }

  //Проверяем данные пользователя, используя LDAP
  function login()
  {
    require_once "ldap.php"; // Конфиг для подключения к ldap
    global $login_error; // Сюда запишем текст ошибки, если авторизация не пройдена
    $username = $_POST['username'];
    $login = $_POST['username'] . $domain;
    $password = $_POST['password'];
    //подсоединяемся к LDAP серверу
    $ldap = ldap_connect($ldaphost, $ldapport) or die('Cannot connect to LDAP Server.');
    //Включаем LDAP протокол версии 3
    ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3) or die('Unable to set LDAP protocol version');
    //Отключаем обработку рефералов для ldap v3
    ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0) or die('Unable to set LDAP OPT REFERRALS');

    if ($ldap) /* Получаем данные из AD */ {
      // Пытаемся войти в LDAP при помощи введенных логина и пароля
      $bind = ldap_bind($ldap, $login, $password);
      if ($bind) //Привязка LDAP прошла успешно!
      {
        // Проверим, является ли пользователь членом указанной группы и не отключен ли он.
        $result = ldap_search($ldap, $base, "(&(memberOf=" . $memberofTP . ")(" . $filter . $username . ")(!(userAccountControl:1.2.840.113556.1.4.803:=2)))");
        if (!$result) {
          $login_error = 'Ошибка обращения к LDAP.';
          return false;
        }
        // Получаем количество результатов предыдущей проверки
        $result_ent = ldap_get_entries($ldap, $result);
        if (!$result_ent) {
          $login_error = 'Результатов проверки получить не удалось';
          return false;
        }
      } else {
        $login_error = 'Вы ввели неправильный <br>логин или пароль';
        return false;
      }
      ldap_close($ldap); /* Закрываем соединение */
    }

    /* Смотрим результаты */
    // Если пользователь найден, т.е. результатов больше 0 (1 должен быть)
    if ($result_ent['count'] != 0) {
      // тут код в случае если авторизации пройдена
      $fullname = $result_ent[0]["displayname"][0]; //полное имя пользователя
      $_SESSION["fullname"] = $fullname; //сохраняем в переменной сессии для отображения
      return true;
      exit;
    } else {
      $login_error = 'К сожалению, вам доступ закрыт.';
      return false;
    }
  }
  ?>
</body>

</html>