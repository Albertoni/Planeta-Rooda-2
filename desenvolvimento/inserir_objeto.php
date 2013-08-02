<?php
	session_start();
	//arquivos necessários para o funcionamento
	require_once("../cfg.php");
	require_once("../bd.php");
	require_once("../funcoes_aux.php");
	/*---------------------------------------------------
	*	Insere um novo objeto no BD e retorna seu ID. - Diogo - 20.07.11
	---------------------------------------------------*/
	
	$encontrarMaiorID = new conexao();
	$encontrarMaiorID->solicitar("SELECT MAX(objeto_id)+1 FROM $tabela_objetos");
	
	$obj_id 			 = $encontrarMaiorID->resultado['MAX(objeto_id)+1'];
	$obj_terrreno_id     = $_POST["obj_terrreno_id"];
	$obj_movieclip 		 = $_POST["obj_movieclip"];
	$obj_frame 			 = $_POST["obj_frame"];
	$obj_terreno_pos_x   = $_POST["obj_terreno_pos_x"];
	$obj_terreno_pos_y   = $_POST["obj_terreno_pos_y"];
	$autor			     = $_POST["autor"];
	
	$bd = new conexao();
	$bd->solicitar("INSERT INTO $tabela_objetos (objeto_id, objeto_terreno_id, objeto_movieclip, objeto_frame, objeto_terreno_posicao_x, objeto_terreno_posicao_y) VALUES ($obj_id,$obj_terrreno_id,'$obj_movieclip',$obj_frame,$obj_terreno_pos_x,$obj_terreno_pos_y)");
	$statusTerreno = new conexao($BD_host1,$BD_base1,$BD_user1,$BD_pass1);
	$statusTerreno->solicitar("UPDATE `$tabela_terrenos` SET terreno_status='edicao', terreno_id_autor='$autor' WHERE terreno_id=$obj_terrreno_id");
	
	if($bd->erro == ""){// and $statusTerreno->erro == ""){
		$erro = '0';
	} else {
		$erro = '8';
	}
	
	$dados_exportar = '';
	$dados_exportar.= "&novo_id=".$obj_id;
	$dados_exportar= utf8_encode($dados_exportar);
	$dados_exportar .= '&erro='.$erro;
	echo "$dados_exportar";
?>