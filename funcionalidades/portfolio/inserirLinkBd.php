<?php
require_once("../../cfg.php");
require_once("../../bd.php");
require_once("../../funcoes_aux.php");


//*********************************************************
//Esta inserindo no BD apenas o endereco do link, funcionalidade_id e funcionalidade_tipo. 
//Corrigir no futuro
//*********************************************************

global $tabela_links;
$endereco = $_POST['newLink'];
if (strpos($endereco, 'http://') !== 0){
	$endereco = "http://".$endereco;
}
$funcionalidade_tipo = TIPOPORTFOLIO;
$funcionalidade_id = $_POST['projeto_id'];

$consulta = new conexao();
$consulta->solicitar("INSERT INTO $tabela_links 
					(  endereco, funcionalidade_tipo, funcionalidade_id) VALUES
					('$endereco','$funcionalidade_tipo','$funcionalidade_id' );
					");



echo "<script type=\"text/javascript\">document.location.href=\"portfolio_projeto.php?projeto_id=".$funcionalidade_id."\";</script>";

?>