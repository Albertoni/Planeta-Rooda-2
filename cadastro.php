<?php
	require_once("cfg.php");
	require_once("bd.php");
	require_once("funcoes_aux.php");
	
	$data="";
	$erro=0;
	
	global $tabela_usuarios;
	global $tabela_personagens;
	global $tabela_terrenos;
	global $tabela_nivel_permissoes;
	
	/**************************************************************************************************************************************************
	* ATENÇÃO: Comentários de 18/05/12 - sexta-feira - 16h.
	* 	Comentei os gotos E seu label, pois impediam o cadastro.
	* 	Mesmo comentando os gotos, o label ainda fazia com que este php não funcionasse.
	* Diogo.
	***************************************************************************************************************************************************/

// AVISO IMPORTANTE: TODOS OS GOTOS LEVAM PARA O MESMO LUGAR, BEM NO FIM DO ARQUIVO.
// AVISO DOIS: GOTOS SÓ EXISTEM DENTRO DE IFS E SE LOCALIZAM BEM NO FIM DA LINHA
	
	$registrar = new conexao();		//Conexão para os registros no Bd - Guto - 10.05.10
	if($registrar->erro != "") {
		$data .= '{ "valor":"1", "texto":"Erro no servidor"}'; //goto gambi;
		$erro = 1;
	}
	
	
	/*--------------------------------------------------------------------------
	*	Confirmação do formulário de cadastro de clientes e correção do mesmo - Vinadé - 10.05.10
	*	É necessário criar o grupo e o terreno padrão para cada usuário, além de inserí-lo 
	*	na tabela de personagens e de usuários - Guto - 11.05.10
	--------------------------------------------------------------------------*/
	$login		= $registrar->sanitizaString($_POST["criar_apelido"]);
	$usuario	= $registrar->sanitizaString($_POST['nome_completo']);
	$email		= $registrar->sanitizaString($_POST['email']);
	$nivel		= $registrar->sanitizaString($_POST['nivel']);
	$sexo		= $registrar->sanitizaString($_POST['sexo']);
	$password = crypt($_POST['criar_senha'], "$2y$07$".gen_salt(22));
	//$password	= md5($_POST['criar_senha']);// insert evil code to steal passwords here
	
	
	$registrar->solicitar("SELECT * FROM $tabela_usuarios WHERE usuario_login='$login'");
	if ($registrar->registros != 0) {
		$data .= '{ "valor":"1", "texto":"Este nome de usuário já existe"}'; //goto gambi;
		$erro = 1;
	}
	
	//Checa se o usuário não tá mandando nível bugado pra fazer algo malvado com o sistema. funcoes_aux.php
	if (!nivel_existe($nivel)){
		$data .= '{ "valor":"1", "texto":"Nível inválido"}'; //goto gambi;
		$erro = 1;
	}
/*
	if($erro != 1){
		$registrar->solicitar("INSERT INTO Chats (nome) VALUES ('$usuario')");
		$idChat = $registrar->ultimo_id();
		if ($registrar->erro != ""){
			$data .= '{ "valor":"1", "texto":"Ocorreu um erro na entrada dos dados, código cadastro_chat, valor '.$usuario.' - '.$registrar->erro.'"}'; //goto gambi;
			$erro = 1;
		}
	}
*/
	if($erro != 1){
		//Primeiro grava o usuário na tabela personagens, definindo o id da tabela de usuários - Guto - 10.05.10
		$registrar->solicitar("INSERT INTO $tabela_personagens (personagem_nome, personagem_avatar_1, chat_id)
								VALUES ('$usuario', '$sexo', 0)"); // chat_id = 0 por que isso é legado do flash
		if($registrar->erro != ""){
			$data .= '{ "valor":"1", "texto":"Ocorreu um erro na entrada dos dados, código 1. Detalhes:'.$registrar->erro.'"}'; //goto gambi;
			$erro = 1;
		}
		$personagem_id  = $registrar->ultimo_id();
		//O nome poderá ser mudado na ferramenta de administração pelo usuário - Guto - 10.05.10
		$grupo_nome = "Sistema de ".$usuario;
	}
		// 
		//$registrar->solicitar("INSERT INTO $tabela_terrenos (terreno_grupo_id) VALUES ('$grupo_id')");
		//if($registrar->erro != "") {
		//	$data .= '{ "valor":"1", "texto":"Ocorreu um erro na entrada dos dados, código 6"}'; goto gambi;
		//}
	
		// CRIAÇÃO DO USUARIO
	
	if($erro != 1){
		$registrar->solicitar("INSERT INTO $tabela_usuarios
		(usuario_data_criacao,usuario_nome,usuario_login,usuario_senha,usuario_email,usuario_personagem_id)
		VALUES (now(),'$usuario','$login','$password','$email','$personagem_id')");
		if($registrar->erro != "") {
			$data .= '{ "valor":"1", "texto":"Ocorreu um erro na entrada dos dados, código 3. Detalhes:'.$registrar->erro.'"}'; //goto gambi;
			$erro = 1;
		}
		$usuario_id = $registrar->ultimo_id();
	}
	
	if($erro != 1){
		$registrar->solicitar("INSERT INTO $tabela_terrenos (terreno_nome, terreno_solo) VALUES ('$usuario', '6')");
		if($registrar->erro != "") {
			$data .= '{ "valor":"1", "texto":"Ocorreu um erro na entrada dos dados, código 7. Detalhes:'.$registrar->erro.'"}'; //goto gambi;
			$erro = 1;
		}
		$quarto_id = $registrar->ultimo_id();
	}
	
	if($erro != 1){
		$registrar->solicitar("UPDATE $tabela_usuarios SET quarto_id = $quarto_id WHERE usuario_id='$usuario_id'");
		if($registrar->erro != "") {
			$data .= '{ "valor":"1", "texto":"Ocorreu um erro na entrada dos dados, código 8. Detalhes:'.$registrar->erro.'"}'; //goto gambi;
			$erro = 1;
		}
	}
	
	if($erro != 1){
		global $nivelVisitante;
		if ($nivel != $nivelVisitante) {
			$registrar->solicitar("UPDATE $tabela_usuarios SET usuario_troca_nivel=$nivel WHERE usuario_id = $usuario_id");
			if($registrar->erro != "") {
				$data .= '{ "valor":"1", "texto":"Ocorreu um erro na entrada dos dados, código 5. Detalhes:'.$registrar->erro.'"}'; //goto gambi;
			}
		
			$data .= '{ "valor":"0", "texto":"Cadastro efetuado"}';
			$registrar->solicitar("SELECT * FROM $tabela_nivel_permissoes WHERE nivel='$nivel'");
			$nomeNivel = $registrar->resultado['nivel_nome'];;
			$assunto = "Pedido de nível";
			$mensagem = "O usuário $usuario\n";
			$mensagem .= "solicitou o pedido de nível de $nomeNivel\n";
			$enviar = comum_enviar_email($email_administrador, $assunto, $mensagem, $email);
		}else{
			$data .= '{ "valor":"0", "texto":"Cadastro efetuado"}';
		}
	}
	
	
	//gambi:
	header('Content-type: application/json; charset="utf-8"', true);
	echo '{"mensagem":'.$data.'}';

?>
