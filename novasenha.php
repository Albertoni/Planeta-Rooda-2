<?php
	require_once("cfg.php");
	require_once("bd.php");
	require_once("funcoes_aux.php");

	$data="";
	$pesquisa1 = new conexao();
	$email = $_POST['email'];
	$email = $pesquisa1->sanitizaString($email);

	
	if($pesquisa1->erro != "") 
	{ 
		$data .= '{ "erro":"1", "texto":"Houve um erro no banco de dados"}';
	}else{
		$pesquisa1->solicitar("select * from $tabela_usuarios WHERE usuario_email = '$email' LIMIT 1");
		if	(($pesquisa1->erro!= "")||($pesquisa1->registros != 1)){
			$data .= '{ "erro":"1", "texto":"Houve um erro no banco de dados"}';
		}else{
			$usuarioLogin = $pesquisa1->resultado['usuario_login'];
			$usuario_id = $pesquisa1->resultado['usuario_id'];
			$length = 8;
			$passwordrand = '';
			$pattern = "1234567890abcdefghijklmnopqrstuvwxyz";
			$tamanhoPattern = (strlen($pattern) - 1); // -1 porque começa em 1 e arrays começam em 0

			for($i=0;$i<$length;$i++){
				$passwordrand .= $pattern{rand(0,$tamanhoPattern)};
			}
			$usuario = new Usuario();
			$usuario->openUsuario($usuario_id);
			$usuario->setPassword($passwordrand);
			//$passwordrand2 = crypt($passwordrand, "$2y$07$".gen_salt(22));

			$assunto = "Nova senha para o Planeta 2";
			$mensagem = "Foi solicitada uma nova senha para o usuário '$usuarioLogin' no Planeta 2.\n";
			$mensagem .= "Nova senha: $passwordrand\n\n";
			$mensagem .= "Para continuar com a senha antiga, basta ignorar este email.\n";
			
			$enviar = comum_enviar_email($email, $assunto, $mensagem, $email_administrador);
			
			if (!$enviar){
				$data .= '{ "erro":"1", "texto":"Houve um erro no envio da senha para seu email"}';
			}else{
				$data .= '{ "erro":"0", "texto":"Sua nova senha foi enviada para seu email"}';
			}
		}
	}
	echo '{"mensagem":'.$data.'}';