<?php
// !SQLINJECTION
session_start();
//arquivos necessários para o funcionamento
require_once("../cfg.php");
require_once("../bd.php");
require("../funcoes_aux.php");

$action    = $_GET['action'];
if(!$action)
	$action     = $_POST['action'];
    
switch ($action) {
	case "verificarEdicao":
		/*---------------------------------------------------
		*	Retorna um booleano indicando se o terreno foi editado recentemente.
		---------------------------------------------------*/  
		$terreno_id_recebido = $POST['terreno_id'];

		$bd = new conexao($BD_host1,$BD_base1,$BD_user1,$BD_pass1);    
		$bd->solicitar("SELECT terreno_status FROM `$tabela_terrenos` WHERE terreno_id='$terreno_id_recebido'");
		
		if($bd->resultado['terreno_status'] == 'edicao')	{$dados_exportar.= "&editadoRecentementePHP=true";}
		else												{$dados_exportar.= "&editadoRecentementePHP=false";}
		
		$dados_exportar = utf8_encode($dados_exportar);

		echo "$dados_exportar";
	break;
}



?>
