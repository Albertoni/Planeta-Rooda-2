<?php
	session_start();
	require_once("cfg.php");
	require_once("bd.php");
	require_once("funcoes_aux.php");
	require_once("usuarios.class.php");
	
	$pesquisa1 = new conexao();

	$login1 = $pesquisa1->sanitizaString($_POST['login1']);
	$password1 = md5($_POST['password1']);

	$objUsuario = new Usuario();
	$objUsuario->openUsuario($login1);
	if (!$objUsuario->getId()) {
		$data = '{ "valor":"1", "texto":"Usuário não cadastrado"}';
	}
	else if (!$objUsuario->checkPassword($_POST['password1'])) {
		$data = '{ "valor":"1", "texto":"Senha incorreta"}';
	}
	else {
		$idusuario = $objUsuario->getId();
		$codeusuario = $objUsuario->getNivelAbsoluto();
		$usuario = $objUsuario->getName();
		$login = $objUsuario->getUser();
		$email = $objUsuario->getEmail();
		$personagem_id = $objUsuario->getPersonagemId();
		/*
		@author Yuri Pelz Gossmann
		@date 2012-08-08 -> 2012-08-14
		INÍCIO
		*/
		$acessoPlaneta=new conexao();
		$agora=date('Y-m-d H:i:s');
		$acessoPlaneta->solicitarSI('SELECT personagem_terreno_id
									 FROM '.$tabela_personagens.'
									 WHERE personagem_id='.intval($personagem_id));
		$acessoPlaneta->solicitarSI('INSERT
									 INTO '.$tabela_acessos_planeta.' (id_usuario,id_terreno,funcionalidade,data_hora,duracao)
									 VALUES ('.intval($idusuario).','.$acessoPlaneta->resultado['personagem_terreno_id'].',"","'.$agora.'",0)');
		/*
		FIM
		*/

		//inicia variaveis de sessao
		$_SESSION['SS_usuario_id'] = $idusuario;
		$_SESSION['SS_usuario_nome'] = $usuario;
		$_SESSION['SS_usuario_nivel_sistema'] = $codeusuario;
		$_SESSION['SS_usuario_login'] = $login;
		$_SESSION['SS_usuario_email'] = $email;
		$_SESSION['SS_personagem_id'] = $personagem_id;
		$_SESSION['SS_turmas'] = array();
		
		$_SESSION['user'] = new Usuario();
		$_SESSION['user']->openUsuario($idusuario);
		
		// Pega-se as turmas do vivente
		global $tabela_turmasUsuario;
		$turmas = new conexao();
		$turmas->solicitar("SELECT codTurma FROM $tabela_turmasUsuario WHERE codUsuario = $idusuario");
		for($i=0; $i < $turmas->registros; $i++){
			$_SESSION['SS_turmas'][] = $turmas->resultado['codTurma'];
			$turmas->proximo();
		}
		
		/*
		//algum dia esse trecho devia ter tido algum uso
		if ($password1 === $npassword){
			$pesquisa1->solicitar("update $tabela_usuarios set usuario_nova_senha='usuario' , usuario_senha = '$npassword' where usuario_id = '$idusuario'");
		}*/
		
		$data = '{ "valor":"0", "texto":"tela_inicial_geral.php"}';
	}

	exit ('{"login":'.$data.'}');
