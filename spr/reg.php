<?php

//header('Content-type: text/plain');

$regexp = "/(,OU=)/ui";
$line = '$$$$$';

$match = preg_split($regexp, $line);
foreach ($match as $m) {
echo '<p>' . $m .  '</p>';
}

echo '<p>' . $match[1] .  '</p>';

print_r(  (int)(ini_get('upload_max_filesize')).' / ');;
print_r( (int)(ini_get('post_max_size')).' / '  );
print_r( (int)(ini_get('memory_limit')).' / ' );
print_r(  php_info().' / ' );

/*
$fax_serch = [];
if (preg_match($regexp_fax_search,$m,$fax_serch)==1){
$fax = [];
preg_match($regexp_fax,$m,$fax);
print_r("FAX {$fax[0]}\n");}
else {
print_r("TEL ".$m."\n");}
 */
