<?php

function Zip($source, $destination) {
	if (!extension_loaded('zip') || !file_exists($source)) {
		return false; 
	}
	$zip = new ZipArchive();
	if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
		return false;
	}
	$source = str_replace('\\', '/', realpath($source));
	if (is_dir($source) === true) {
		$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);
		foreach ($files as $file) {
			$file = str_replace('\\', '/', $file);
			// Ignore "." and ".." folders
			if( in_array(substr($file, strrpos($file, '/')+1), array('.', '..')) ) {
				continue;
			}
			$file = realpath($file);
			if (is_dir($file) === true)  {
				$zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
			} else if (is_file($file) === true) {
				$zip->addFile($file, str_replace($source . '/', '', $file));
			}
		}
	} else if (is_file($source) === true) {
		$zip->addFile($file, str_replace($source . '/', '', $file));
	}
	$zip->close();
	return $destination;
}

$p=htmlspecialchars($_GET["path"]);//'prikaz/2021/';
$n='./tmpdwnld/'.htmlspecialchars($_GET["zipname"]).'.zip';
Zip($p,$n);
//echo '<a class= link href="'.$n.'" class="product">Скачать</a><br>';

header('Content-Type: application/zip');
header('Content-Disposition: attachment; filename='.htmlspecialchars($_GET["zipname"]).'.zip');
readfile($n);

?>


















