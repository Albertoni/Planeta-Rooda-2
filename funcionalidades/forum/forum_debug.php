<?php
	session_start();
	
	require("../../cfg.php");
	require("../../bd.php");
	require("../../funcoes_aux.php");
	require("verifica_user.php");
	require("sistema_forum.php");
	require("visualizacao_forum.php");

	
	$consulta = new conexao();
	$consulta->solicitar("select * from $tabela_forum where forum_id = '60'");
	print_r($consulta->registros);
	
	echo "\n";
	
	$consulta->solicitar("select COUNT(*) from $tabela_forum where forum_id = '60'");
	print_r($consulta);
	/*
	// Isso tava aqui antes de mim, não sei pra que serve. ~ João, 10/05/11

	$pagina = (isset($_GET['pagina']))? $_GET['pagina'] : 1;
	if ($VERIFICA_USER_ERRO_ID == 0) {
		$FORUM = new forum($FORUM_ID);
		$FORUM->configBD($BD_host1,$BD_base1,$BD_user1,$BD_pass1,$tabela_forum,$tabela_usuarios);
		$FORUM->topicos($pagina);
		
		$paginas = array();
		$paginas = $FORUM->paginas($pagina,10);
	}
	echo $FORUM_ID.' '.$SISTEMA;
	*/
?>
