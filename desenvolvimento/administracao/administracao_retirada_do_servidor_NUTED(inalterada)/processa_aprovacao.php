<?php
	session_start();
	
	require("../../cfg.php");
	require("../../bd.php");
	require("../../funcoes_aux.php");
	require("../../usuarios.class.php");
    require("../../login.class.php");	
	require("../../file.class.php");
	require("../../link.class.php");

	foreach ($HTTP_POST_VARS as $usuario){
		$consulta = new conexao();
		if (is_numeric($usuario)){
			$consulta->solicitar("UPDATE $tabela_usuarios SET usuario_nivel=usuario_troca_nivel, usuario_troca_nivel = '0' WHERE usuario_id = '".(int)$usuario."'");
		}
	}
	$consulta->fechar();
	unset($usuario); // usuario is free like a bird now
	
	header('Location: http://www.nuted.ufrgs.br/planeta2_edicao/planeta2_joao/funcionalidades/administracao/');
?>