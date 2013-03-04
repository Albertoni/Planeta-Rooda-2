<?php
	session_start();
	//arquivos necessários para o funcionamento
	require_once("../cfg.php");
	require_once("../bd.php");
	require("../funcoes_aux.php");
	/*---------------------------------------------------
	*	Apaga um objeto do BD. - Diogo - 28.07.11
	---------------------------------------------------*/
	$id							= $_POST["ident"];
	$terreno_id					= $_POST["terreno_id"];
	$autor						= $_POST["autor"];
	
	$bd = new conexao();
    $bd->solicitar("DELETE FROM $tabela_objetos WHERE objeto_id = $id");
	$statusTerreno = new conexao($BD_host1,$BD_base1,$BD_user1,$BD_pass1);
	$statusTerreno->solicitar("UPDATE `$tabela_terrenos` SET terreno_status='edicao', terreno_id_autor='$autor' WHERE terreno_id=$terreno_id");
	
	if($bd->erro == ""){// and $statusTerreno->erro == ""){
		$erro = '0';
	} else {
		$erro = '10';
	}
	
	$dados_exportar = '&erro='.$erro;
	echo "$dados_exportar";
?>