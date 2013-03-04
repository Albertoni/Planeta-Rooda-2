<?php
session_start();
//arquivos necessários para o funcionamento
require_once("../cfg.php");
require_once("../bd.php");
require("../funcoes_aux.php");

/*---------------------------------------------------
*	Procura o usuário e envia o convite.
---------------------------------------------------*/
$dataAtual = strtotime("-5 seconds"); 
$dataAtual = date("Y-m-d H:i:s", $dataAtual);

$nomeUsuarioDestino = $_POST["nomeUsuarioDestino"];
$idChat = $_POST["idChat"];
$nomeChat = $_POST["nomeChat"];

$idPersonagemOrigem = $_SESSION['SS_personagem_id'];
$conexao_personagem = new conexao();
$conexao_personagem->solicitar("SELECT *
								FROM personagens
								WHERE personagem_id = $idPersonagemOrigem");
$nomeUsuarioOrigem = $conexao_personagem->resultado['personagem_nome'];

$conexao_usuario = new conexao();
$conexao_usuario->solicitar("SELECT * 
							FROM personagens
							WHERE personagem_nome = '$nomeUsuarioDestino'");

$houveErro = false;
if(0 < $conexao_usuario->registros){
	$idPersonagemUsuario = $conexao_usuario->resultado['personagem_id'];
	$idChatPersonagem = $conexao_usuario->resultado['chat_id'];
	$conexao_verificar_chats_usuario = new conexao();
	$conexao_verificar_chats_usuario->solicitar("SELECT *
												FROM ChatsUsuarios
												WHERE usuario_id = $idPersonagemUsuario
													AND chat_id = $idChat");
	if(0 < $conexao_verificar_chats_usuario->registros){
		$erro = '7';
		$houveErro = true;											
	} else {
		$conexao_verificar_chats_usuario->solicitar("SELECT *
													FROM ChatsUsuarios
													WHERE usuario_id = $idPersonagemUsuario
														AND chat_id != $idChatPersonagem");
		if($conexao_verificar_chats_usuario->registros == 4){
			$erro = '5';
			$houveErro = true;								
		}
	}
}
						
if($conexao_usuario->registros == 0 and !$houveErro){
	$erro = '1';
} else if($conexao_usuario->resultado['personagem_ultimo_acesso'] < $dataAtual and !$houveErro){
	$erro = '2';
} else if(!$houveErro){ //Não houve erro. Enviar mensagem de convite ao usuário.
	$conexao_falas_personagem = new conexao();

	$id_chat = $conexao_usuario->resultado['chat_id'];
	$idPersonagemUsuario = $conexao_usuario->resultado['personagem_id'];
	if($conexao_usuario->resultado['personagem_avatar_1'] == 1){
		$mensagem = 'Você foi convidada por '.$nomeUsuarioOrigem.' para entrar no chat '.$nomeChat.'.';	
	} else {
		$mensagem = 'Você foi convidado por '.$nomeUsuarioOrigem.' para entrar no chat '.$nomeChat.'.';	
	}
	$mensagem = utf8_encode($mensagem);
	$conexao_falas_personagem->solicitar("INSERT INTO falas_personagens (texto_fala, data, chat_id)
										VALUES ('$mensagem', now(), $id_chat)");
	$conexao_falas_personagem->solicitar("SELECT *
										  FROM ChatsUsuariosConvite
										  WHERE usuario_id = $idPersonagemUsuario
											  AND chat_id = $idChat");
	if($conexao_falas_personagem->registros == 0){
		$conexao_falas_personagem->solicitar("INSERT INTO ChatsUsuariosConvite (usuario_id, chat_id)
											  VALUES ($idPersonagemUsuario, $idChat)");
	}
	$erro = '0';	
}

$dados = '';
$dados .= '&nomeUsuario='.$nomeUsuarioDestino;
$dados .= '&nomeChat='.$nomeChat;
$dados .= '&erro='.$erro;
echo $dados;
?>
