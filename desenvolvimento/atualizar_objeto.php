<?php
	session_start();
	//arquivos necessários para o funcionamento
	require_once("../cfg.php");
	require_once("../bd.php");
	require_once("../funcoes_aux.php");
	/*---------------------------------------------------
	*	Altera informações de um objeto presente no BD. - Diogo - 18.07.11
	---------------------------------------------------*/
	$id							= $_POST["ident"];
	$frame						= $_POST["numFrame"];
    $terreno_posicao_x    		= $_POST["terreno_posicao_x"];
    $terreno_posicao_y    		= $_POST["terreno_posicao_y"];
	$terreno_id					= $_POST["terreno_id"];
	$autor						= $_POST["autor"];
		
    $bd = new conexao();
    $bd->solicitar("UPDATE $tabela_objetos SET objeto_frame=$frame,objeto_link='$link_frame',objeto_terreno_posicao_x=$terreno_posicao_x, objeto_terreno_posicao_y=$terreno_posicao_y WHERE objeto_id = $id");

	$statusTerreno = new conexao($BD_host1,$BD_base1,$BD_user1,$BD_pass1);
	$statusTerreno->solicitar("UPDATE `$tabela_terrenos` SET terreno_status='edicao', terreno_id_autor='$autor' WHERE terreno_id=$terreno_id");
	
	if($bd->erro == ""){// and $statusTerreno->erro == ""){
		$erro = '0';
	} else {
		$erro = '9';
	}
	
	$dados_exportar = '&erro='.$erro;
	echo "$dados_exportar";
?>