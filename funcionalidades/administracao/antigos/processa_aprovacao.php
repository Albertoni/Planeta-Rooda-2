<?php
	session_start();
	
	require_once("../../cfg.php");
	require_once("../../bd.php");
	require_once("../../funcoes_aux.php");
	require_once("../../usuarios.class.php");
	require_once("../../login.class.php");
	require_once("../../file.class.php");
	require_once("../../link.class.php");
	
	if (!checa_nivel($usuario_nivel, $nivelProfessor)) { // Se for aluno ou visitante
		die ("Você não tem nada a fazer aqui. A direção foi informada de sua tentativa de acesso. Tenha um bom dia.");
	}

	foreach ($HTTP_POST_VARS as $usuario){
		$consulta = new conexao();
		if (is_numeric($usuario)){
			$consulta->solicitar("UPDATE $tabela_usuarios SET usuario_nivel=usuario_troca_nivel, usuario_troca_nivel = '0' WHERE usuario_id = '".(int)$usuario."'");
		}
	}
	
	unset($usuario); // usuario is free like a bird now
	
	header('Location: http://www.nuted.ufrgs.br/planeta2_edicao/planeta2_joao/funcionalidades/administracao/');
?>
