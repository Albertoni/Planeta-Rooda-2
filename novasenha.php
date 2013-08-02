<?php
	require_once("cfg.php");
	require_once("bd.php");
	require_once("funcoes_aux.php");

	$data="";
	$email = $_POST['email'];			  
	$email = addslashes($email);

	$pesquisa1 = new conexao($BD_host1,$BD_base1,$BD_user1,$BD_pass1);
	if($pesquisa1->erro != "") 
	{ 
		$data .= '{ "erro":"1", "texto":"Houve um erro no banco de dados"}';
	}else{
		$pesquisa1->solicitar("select * from $tabela_usuarios WHERE usuario_email = '$email' LIMIT 1");
		if	(($pesquisa1->erro!= "")||($pesquisa1->registros != 1))
		{ 
			$data .= '{ "erro":"1", "texto":"Houve um erro no banco de dados"}';
		}else{
			$usuario = $pesquisa1->resultado['usuario_login'];
			$usuario_id = $pesquisa1->resultado['usuario_id'];
			$length = 8;
			$passwordrand = '';
			$pattern = "1234567890abcdefghijklmnopqrstuvwxyz";
			for($i=0;$i<$length;$i++){
				$passwordrand .= $pattern{rand(0,35)};
			}
			$passwordrand2 = md5($passwordrand);

			$assunto = "Nova senha para o Planeta 2";
			$mensagem = "Foi solicitada uma nova senha para o usuário '$usuario' no Planeta 2.\n";
			$mensagem .= "Nova senha: $passwordrand\n\n";
			$mensagem .= "Para continuar com a senha antiga, basta ignorar este email.\n";
			
			$enviar = comum_enviar_email($email, $assunto, $mensagem, $email_administrador);
			
			if (!$enviar){
				$data .= '{ "erro":"1", "texto":"Houve um erro no envio da senha para seu email"}';
			}else{
				$data .= '{ "erro":"0", "texto":"Sua nova senha foi enviada para seu email"}';
				$pesquisa1->solicitar("update $tabela_usuarios set usuario_nova_senha='$passwordrand2' where usuario_id = '$usuario_id'");
			}
		}
	}
	echo '{"mensagem":'.$data.'}';	

?>