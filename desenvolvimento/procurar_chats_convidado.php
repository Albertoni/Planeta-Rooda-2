<?php
session_start();
//arquivos necessários para o funcionamento
require_once("../cfg.php");
require_once("../bd.php");
require_once("../funcoes_aux.php");

/*
* Procura nomes de chats para os quais o usuário foi convidado.
*/
$id_personagem_online = $_SESSION['SS_personagem_id'];

$conexao_convites = new conexao();
$conexao_convites->solicitar("SELECT C.nome
							  FROM ChatsUsuariosConvite as CC, Chats AS C
							  WHERE CC.usuario_id = $id_personagem_online
								AND CC.chat_id = C.id");
$numeroConvites = $conexao_convites->registros;

$dados = '';
for($indice=0; $indice < $numeroConvites; $indice++){
	$dados .= '&nomeChat'.$indice.'='.$conexao_convites->resultado['nome'];
	$conexao_convites->proximo();
}

$erro = '0';
$dados .= '&numeroChatsRecebidos='.$numeroConvites;
$dados .= '&erro='.$erro;
echo $dados;
?>