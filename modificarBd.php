<?php
// !!!DEPRECATED
require_once("cfg.php");
require_once("bd.php");
require_once("funcoes_aux.php");

$consulta = new conexao();
$i = $consulta->solicitar($_POST['consulta']);


echo "$i";


?>