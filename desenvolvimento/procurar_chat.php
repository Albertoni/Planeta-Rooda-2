<?php
session_start();
//arquivos necessários para o funcionamento
require_once("../cfg.php");
require_once("../bd.php");
require("../funcoes_aux.php");

/*---------------------------------------------------
*	Procura ID no banco de dados do chat cujo nome foi passado.
---------------------------------------------------*/
$nomeChat = $_POST["nomeChat"];
$tipoChat = $_POST["tipoChat"];

$conexao_chat = new conexao();
$conexao_chat->solicitar("SELECT * 
						FROM Chats
						WHERE nome = '$nomeChat'");

$idChat = $conexao_chat->resultado['id'];

$erro = '0';
if($conexao_chat->registros == 0){
	$erro = '3';
} else if($tipoChat == 1){
	$idPersonagemOrigem = $_SESSION['SS_personagem_id'];
	$conexao_personagem = new conexao();
	$conexao_personagem->solicitar("SELECT *
									FROM personagens
									WHERE personagem_id = $idPersonagemOrigem");
	$nomeUsuarioOrigem = $conexao_personagem->resultado['personagem_nome'];
	
	$mensagem = 'O usuário '.$nomeUsuarioOrigem.' entrou no chat '.$nomeChat.'.';
	$mensagem = utf8_encode($mensagem);
	$conexao_chat->solicitar("INSERT INTO falas_personagens (texto_fala, data, chat_id)
							VALUES ('$mensagem', now(), $idChat)");
	$erro = '0';	
}

$dados = '';
$dados .= '&nomeChat='.$nomeChat;
$dados .= '&idChat='.$idChat;
$dados .= '&erro='.$erro;
echo $dados;
?>
