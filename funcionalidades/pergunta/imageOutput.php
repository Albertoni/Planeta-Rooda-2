<?php
	require_once("../../cfg.php");
	require_once("../../bd.php");
	
	$id = $_GET['id'];
	$fonte_mensagem_erro = "../../fonte_erros.ttf";
	
	
	// COMENTE ISSO ANTES DE DEBUGAR, MALANDRO!
////////////////////////////////////////////////////////////////////////////////
	error_reporting(0);
////////////////////////////////////////////////////////////////////////////////
	
	
	
	if (is_numeric($id) == false){
		die('RAAAAAAAAAA, pegadinha do Mallandro!'); // Sabe SQL injection?
	}

	$consulta = new conexao();
	$consulta->connect();
	$consulta->solicitar("SELECT arquivo FROM $tabela_arquivos WHERE arquivo_id = '$id'");

	// MAGIA!!!
	$image = imagecreatefromstring($consulta->resultado['arquivo']);
	
	error_reporting(E_ALL);
	// Cuspindo a imagem. Sim, PNG.
	header('Content-type: image/png');
	imagepng($image);
	imagedestroy($image); // free(ram);
?>
