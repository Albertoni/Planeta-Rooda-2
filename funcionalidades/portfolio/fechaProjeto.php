<?php

require_once("../../cfg.php");
require_once("../../bd.php");
require_once("../../funcoes_aux.php");

global $tabela_portfolioProjetos;
$consulta= new conexao();
$id = (int) $_GET['id'];
$consulta->solicitar("UPDATE $tabela_portfolioProjetos SET emAndamento = 0 WHERE id='$id'"); // SR. HACKER FAVOR IGNORAR AS VULNERABILIDADES DESTA PÁGINA MUITO OBRIGADO
if ($consulta->erro == ""){
	echo "ok";
}
?>
