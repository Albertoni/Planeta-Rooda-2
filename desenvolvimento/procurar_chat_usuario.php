<?php
session_start();
//arquivos necessários para o funcionamento
require_once("../cfg.php");
require_once("../bd.php");
require_once("../funcoes_aux.php");

/*---------------------------------------------------
*	Procura ID no banco de dados o chat do usuário cujo nome foi passado.
---------------------------------------------------*/
$dataAtual = strtotime("-5 seconds");
$dataAtual = date("Y-m-d H:i:s", $dataAtual);
$nomeUsuarioChat = $_POST["nomeUsuarioChat"];

$conexao_chat_usuario = new conexao();
$conexao_chat_usuario->solicitar("SELECT * 
								FROM personagens
								WHERE personagem_nome = '$nomeUsuarioChat'");

$nomeUsuarioDestino = $conexao_chat_usuario->resultado['personagem_nome'];
$idChatUsuarioDestino = $conexao_chat_usuario->resultado['chat_id'];; 

if($conexao_chat_usuario->registros == 0){
	$erro = '1';
} else if($conexao_chat_usuario->resultado['personagem_ultimo_acesso'] < $dataAtual){
	$erro = '2';
} else {
	$erro = '0';	
}

$dados = '';
$dados .= '&nomeUsuarioDestino='.$nomeUsuarioDestino;
$dados .= '&idChatUsuarioDestino='.$idChatUsuarioDestino;
$dados .= '&erro='.$erro;
echo $dados;
?>
