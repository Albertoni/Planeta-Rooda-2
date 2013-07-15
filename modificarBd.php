<?php
// !!!DEPRECATED
require("cfg.php");
require("bd.php");
require("funcoes_aux.php");

$consulta = new conexao();
$i = $consulta->solicitar($_POST['consulta']);


echo "$i";


?>