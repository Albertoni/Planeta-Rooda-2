<?php

require("../../cfg.php");
require("../../bd.php");
require("../../funcoes_aux.php");

global $tabela_portfolioProjetos;
$consulta= new conexao();
$id = mysql_real_escape_string($_GET['id']);
$consulta->solicitar("UPDATE $tabela_portfolioProjetos SET emAndamento = 0 WHERE id='$id'"); // SR. HACKER FAVOR IGNORAR AS VULNERABILIDADES DESTA PÁGINA MUITO OBRIGADO
if ($consulta->erro == ""){
	echo "ok";
}
?>
