<?php/** Mostra o �cone da tabela IconesPlanetas com a id $_GET['id'].*/	require_once("cfg.php");	require_once("bd.php");	$conexao = new conexao();	$conexao->solicitar("SELECT *						 FROM IconesPlanetas						 WHERE id = ".$_GET['id']);	$blobby = $conexao->resultado['imagem'];	header("Content-type: image/jpeg");	echo $blobby;?>