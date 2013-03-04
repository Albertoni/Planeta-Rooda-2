<?php
session_start();
//arquivos necess�rios para o funcionamento
require_once("../cfg.php");
require_once("../bd.php");
require("../funcoes_aux.php");

/*
* Notifica um chat do tipo "Amigo" que um usu�rio o deixou.
* Na pr�tica: verifica se ainda h� usu�rios no chat e o exclui caso n�o haja.
*/
$chat_id = $_POST['chat_id'];
$id_personagem_online = $_SESSION['SS_personagem_id'];

$conexao_notificacao = new conexao();
$conexao_notificacao->solicitar("DELETE	FROM ChatsUsuarios
								WHERE chat_id = $chat_id
									AND usuario_id = $id_personagem_online");
$conexao_notificacao->solicitar("SELECT *
								FROM ChatsUsuarios
								WHERE chat_id = $chat_id");
if($conexao_notificacao->registros == 0){ //N�o h� ningu�m no chat.
	$conexao_notificacao->solicitar("DELETE	FROM Chats
									WHERE id = $chat_id");
	$conexao_notificacao->solicitar("DELETE	FROM ChatsUsuariosConvite
									WHERE chat_id = $chat_id");
}

?>