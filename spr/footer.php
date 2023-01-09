<?php
//echo '<img type="button" class="btn-dr" data-bs-toggle="modal" data-bs-target="#modal" src="img/hb.png" width="32" height="32">';	 
echo '<img class="btn-theme" src="img/dl.png" width="32" height="32">';
echo '<a href=weather.php><img type="button" class="btn-weather" src="img/wea.png" width="32" height="32"></a>';	
echo '<a href=currencies.php><img type="button" class="btn-val" src="img/val.png" width="32" height="32"></a>';

echo '</body>';
echo '</html>';


echo '
<script>
// Выбираем кнопку
const btn = document.querySelector(".btn-theme");

// Слушаем нажатия на кнопку 
btn.addEventListener("click", function() {
  // Переключаем класс .dark-theme
  document.body.classList.toggle("dark-theme");
  
  // По умолчанию зададим светлую тему
  let theme = "light";
  // Если установлен класс .dark-theme...
  if (document.body.classList.contains("dark-theme")) {
    // ...тогда установим темную тему
    theme = "dark";
  }
  // Сохраняем выбор в cookie
  document.cookie = "theme=" + theme;
});
</script>
<script type="text/javascript" src="newyear.js"></script>';

