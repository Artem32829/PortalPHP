<?php
if(isset($_POST['username']) && isset($_POST['password'])){
    $adServer = "ldap://bns.local";
    $ldap = ldap_connect($adServer);
    $username = $_POST['username'];
    $password = $_POST['password'];
    $ldaprdn = 'bns\\' . $username;
    ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
    ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);
    $bind = @ldap_bind($ldap, $ldaprdn, $password);
    if ($bind) {
        $filter="(sAMAccountName=$username)";
        $result = ldap_search($ldap,"dc=bns,dc=local",$filter);
        ldap_sort($ldap,$result,"sn");
    $info = ldap_get_entries($ldap, $result);
    for ($i=0; $i<$info["count"]; $i++)
        {
            if($info['count'] > 1)
                break;
include ('menu.php');
$usr=$info[$i]['displayname'][0];
$usr2='$$$$';
$usr3='$$$$';
//echo "<h2>{$usr}</h2>";
     if (($usr==$usr2) or($usr==$usr3))
	 {

	
echo' <p style="margin-top: 0px;margin-bottom: 0px;padding: 12px 40px;text-align: left;font-family: GothamPro-Medium,Helvetica,Arial,sans-serif;font-weight: 400;
		font-style: normal;color: #fff;font-size: 22px;line-height: 1.2;background: #69A5D7;" >Загрузить приказ за 2022 год</p><br>
      <form action="upload.php" method="post" enctype="multipart/form-data">
      <input  type="file" name="filename"><br> 
      <input class="btn btn-sm btn-success" type="submit" value="Загрузить"><br>
      </form> 	 
	 ';}
	 else {		 
		 echo '<b>Загрузка недоступна. Свяжитесь с секретарём приемной руководителя Ермолик Е.И.</b><a href = "mailto:E.Ermolik@bns.by?subject=Загрузить%20на%20сайт&body=Загрузить%20на%20сайт"><img  src="/img//mail.png" width="20" height="15" alt="Поздравить" ></a><br>'; 
		 
		 
		 
		 }

echo '</body>';    
echo '</html>';          	   
 $userDn = $info[$i]["distinguishedname"][0]; 
        }
        @ldap_close($ldap);
    } else {
echo '
<link rel="stylesheet" type="text/css" href="hd.css" /> 
<link rel="stylesheet" href="bootstrap.min.css"> 
<div class="container">  
<form action="prav_sync.php">
<br><a href="index.php?page=main"><img src="/img/logo-svg.svg" alt="Страховая компания «Белнефтестрах»" title="Страховая компания «Белнефтестрах»"></a><br><br> 
<p>Ошибка входа! Указано неправильное имя или пароль</p>	
<button class="btn btn-primary">Назад</button>
</form>  
</div>';
    }
}else{
?>
 <link rel="stylesheet" type="text/css" href="hd.css" /> 
 <link rel="stylesheet" href="bootstrap.min.css">
 
<div class="container">  
<br><a href="index.php?page=main"><img src="/img/logo-svg.svg" alt="Страховая компания «Белнефтестрах»" title="Страховая компания «Белнефтестрах»"></a><br><br> 
	<p>Загрузка новых приказов доступна только секретарю приемной руководителя</p>
	<h2>Вход</h2>	
    <form action="#" method="POST">
    <label for="username"></label><input class="form-control" id="username" type="text" name="username" placeholder="Имя пользователя" />     
	<label for="password"></label><input class="form-control" id="password" type="password" name="password" placeholder="Пароль"/> 	
    <input  class="btn btn-primary" type="submit" value="Войти" name="submit" />
    </form>
</div>
<?php }
 ?> 
