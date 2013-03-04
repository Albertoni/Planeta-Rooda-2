<?php
session_start();
//arquivos necessários para o funcionamento
require_once("../cfg.php");
require_once("../bd.php");
require("../funcoes_aux.php");

$nomeChat = $_POST["nomeChat"];

$dados = '';
$houveErro = false;

$conexao_erros = new conexao();
$conexao_erros->solicitar("SELECT *
						FROM Chats
						WHERE nome = '$nomeChat'");
if(0 < $conexao_erros->registros){
	$erro = '4';	
	$houveErro = true;
}

if($houveErro == false){
	$conexao_chat = new conexao();
	$conexao_chat->solicitar("INSERT INTO Chats (nome) VALUES ('$nomeChat')");
	$conexao_chat->solicitar("SELECT * FROM Chats WHERE nome = '$nomeChat'");
	$idChat = $conexao_chat->resultado['id'];
	$erro = '0';
}
$dados.= '&nomeChat='.$nomeChat;
$dados.= '&idChat='.$idChat;
$dados .= '&erro='.$erro;
echo $dados;
?>