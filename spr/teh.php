<?php
include ('menu.php');

echo '<h6>Полезная информация для технической поддержки</h6>';
echo'<table class="table table-bordered table-striped">
<thead>
<tr>
<td style="text-align: center" colspan="3"><b>Контактная информация техподдержки</b></td>
</tr>
</thead>
<tbody>    
<tr>
<td style="width:33%">Телефон</td>
<td style="width:33%">E-mail</td>
<td style="width:33%">Время работы</td>
</tr>
<tr>
<td>+375291990768</td>
<td><a href="mailto:911@bns.by">911@bns.by</a></td>
<td>пн-чт(08:30-17:15) пт(08:30-16:00)</td>
</tr>
</tbody>
</table>';

echo'<h4><b>Программное обеспечение</b></h4>';
echo'<details><summary>Программное обеспечение</summary><ul>';
$files = scandir('soft');
sort($files);
foreach($files as $file){if ($file != "." && $file != "..")
echo'<li><a class= link href="soft\\'.$file.'" class="product">'.$file.'</a><br></li>'; }
echo'</ul></details> '; 


echo'<br><h4><b>Полезные ссылки</b></h4>';
echo'<details><summary>Порталы технической поддержки пользователей и справочной информации</summary><ul>';
echo'<li><a href="https://app.guardsaas.com/" target="_blank">Интернет-сервис службы технической поддержки ООО Топсофт</a></li>';
echo'<li><a href="https://globocenter.by/ords/f?p=100:2" target="_blank">Система работы службы поддержки ЧУП Глобо-Центр</a></li>';
echo'<li><a href="https://support.nces.by/" target="_blank">Система технической поддержки НЦЭУ</a></li>';
echo'<li><a href="https://ilex.by/" target="_blank">Портал правовой информации ilex</a></li>';
echo'<li><a href="https://nces.by/pki/service/ul/ul_izdanie_sok/" target="_blank">Порядок издания сертификата юридического лица НЦЭУ</a></li>';
echo'<li><a href="https://app.guardsaas.com/" target="_blank">Система учёта рабочего времени GuardSaaS</a></li>';
echo'<li><a href="http://192.168.29.230/index.php?page=login" target="_blank">phpIPAM - Система управления IP-адресами</a></li>';
echo'<li><a href="http://10.230.4.254/index.php" target="_blank">Zabbix - система мониторинга</a></li>';
echo'<li><a href="http://10.230.4.254:3000/login" target="_blank">Grafana - веб-приложение для аналитики и интерактивной визуализации </a></li>';
echo'<li><a href="/2048/2048.html" target="_blank">2048</a></li>';
echo'</ul></details> ';

echo'<details><summary>Программное обеспечение для работы с ЭЦП</summary><ul>';
echo'<li><a href="https://www.avest.by/crypto/download/avpki/AvPKISetup(bel).zip" target="_blank">Avest - комплект абонента для носителей AvToken или AvPass</a></li>'; 
echo'<li><a href="https://www.avest.by/crypto/download/avpki/AvPKISetup(bign).zip" target="_blank">Avest - комплект абонента для носителей AvBign</a></li>'; 
echo'<li><a href="https://store.nces.by/index.php/login" target="_blank">store.nces.by - облачное хранилище НЦЭУ</a></li>'; 
echo'<li><a href="https://www.ssf.gov.by/ru/po-fonda-ru/" target="_blank">ФСЗН - Программа «Ввод ДПУ»</a></li>'; 
echo'</ul></details> ';

echo'<details><summary>Клиент-банки</summary><ul>';
echo'<li><a href="https://icb.asb.by/Login/Index?ReturnUrl=%2F" target="_blank">Беларусбанк</a></li>'; 
echo'<li><a href="https://ibank.belinvestbank.by/signin" target="_blank">Белинвестбанк</a></li>'; 
echo'<li><a href="https://corporate.bgpb.by/" target="_blank">Белгазпромбанк</a></li>'; 
echo'<li><a href="https://i25-client.belapb.by/signin" target="_blank">Белагропромбанк</a></li>'; 
echo'<li><a href="https://ibank.bankdabrabyt.by/" target="_blank">Дабрабыт</a></li>'; 
echo'<li><a href="https://sbbol.bps-sberbank.by/login" target="_blank">Сбербанк</a></li>'; 
echo'<li><a href="https://www.ibank.priorbank.by/" target="_blank">Приорбанк</a></li>'; 
echo'<li><a href="https://ib.tb.by/" target="_blank">Технобанк</a></li>'; 

echo'</ul></details> ';

echo'<details><summary>Порталы государственных организаций</summary><ul>';
echo'<li><a href="https://portal.gov.by/PortalGovBy" target="_blank">Единый портал электронных услуг</a></li>';
echo'<li><a href="https://account.gov.by/" target="_blank">Новая версия Единого портала электронных услуг</a></li>';
echo'<li><a href="http://portal.nalog.gov.by/web/nalog/office" target="_blank">Личный кабинет плательщика МНС</a></li>';
echo'<li><a href="http://vat.gov.by/mainPage/" target="_blank">Портал для работы с электронными счетами-фактурами.</a></li>';
echo'<li><a href="http://e-respondent.belstat.gov.by/belstat/" target="_blank">Электронный респондент Online</a></li>';
echo'<li><a href="https://www.nbrb.by/veb-portal-registracii-valyutnyh-dogovorov" target="_blank">Веб-портал регистрации валютных договоров НБРБ</a></li>';
echo'<li><a href="http://portal2.ssf.gov.by/mainPage/" target="_blank">Корпоротивный портал ФСЗН</a></li>';
echo'<li><a href="https://report.bgs.by/" target="_blank">Система представления отчетов Белгосстраха</a></li>';
echo'<li><a href="https://abon.belneftekhim.by" target="_blank">Система приема-передачи данных Абонент Белнефтехима</a></li>';
echo'<li><a href="https://app.edn.by/web" target="_blank">ЭДиН - облачный сервис обмена электронными документами</a></li>';
echo'</ul></details> ';

echo'<br><h4><b>Драйверы для принтеров</b></h4>';
echo'<details><summary>Canon</summary><ul>';
$files = scandir('drpr/Canon');
sort($files);
foreach($files as $file){if ($file != "." && $file != "..")
echo'<li><a class= link href="drpr\\Canon\\'.$file.'" class="product">'.$file.'</a><br></li>'; }
echo'</ul></details> ';

echo'<details><summary>Epson</summary><ul>';
$files = scandir('drpr/Epson');
sort($files);
foreach($files as $file){if ($file != "." && $file != "..")
echo'<li><a class= link href="drpr\\Epson\\'.$file.'" class="product">'.$file.'</a><br></li>'; }
echo'</ul></details> ';

echo'<details><summary>HP</summary><ul>';
$files = scandir('drpr/HP');
sort($files);
foreach($files as $file){if ($file != "." && $file != "..")
echo'<li><a class= link href="drpr\\HP\\'.$file.'" class="product">'.$file.'</a><br></li>'; }
echo'</ul></details> ';

echo'<details><summary>Lexmark</summary><ul>';
$files = scandir('drpr/Lexmark');
sort($files);
foreach($files as $file){if ($file != "." && $file != "..")
echo'<li><a class= link href="drpr\\Lexmark\\'.$file.'" class="product">'.$file.'</a><br></li>'; }
echo'</ul></details> ';

echo'<details><summary>Samsung</summary><ul>';
$files = scandir('drpr/Samsung');
sort($files);
foreach($files as $file){if ($file != "." && $file != "..")
echo'<li><a class= link href="drpr\\Samsung\\'.$file.'" class="product">'.$file.'</a><br></li>'; }
echo'</ul></details> ';

include ('footer.php');
