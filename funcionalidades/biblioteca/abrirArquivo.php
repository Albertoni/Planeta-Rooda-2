<?php
	require_once("../cfg.php");		

$dir=$_SERVER['PATH_INFO'];
//$dir=urldecode($dir);
$caminho=$file_dir . $dir;
header('Content-Disposition: attachment; filename="' . basename($caminho) .'"');
header('Expires: 0');
//header('Pragma: no-cache');

@readfile($caminho);
?>