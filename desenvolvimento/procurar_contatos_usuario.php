<?php
session_start();
//arquivos necessários para o funcionamento
require_once("../cfg.php");
require_once("../bd.php");
require_once("../funcoes_aux.php");

/*
* Procura nomes de contatos do usuário que está logado.
*/
$chat_id = $_POST['chat_id'];

$id_personagem_online = $_SESSION['SS_personagem_id'];
$conexao_contatos = new conexao();
$conexao_contatos->solicitar("SELECT usuario_nome
							  FROM usuarios AS U, ChatsUsuarios AS C
							  WHERE U.usuario_personagem_id = C.usuario_id
								AND C.usuario_id != $id_personagem_online 
								AND C.chat_id = $chat_id");
$numeroContatosUsuario = $conexao_contatos->registros;

$dados = '';
for($indice=0; $indice < $numeroContatosUsuario; $indice++){
	$dados .= '&nomeContatos'.$indice.'='.$conexao_contatos->resultado['usuario_nome'];
	$conexao_contatos->proximo();
}

$erro = '0';
$dados .= '&numeroContatosRecebidos='.$numeroContatosUsuario;
$dados .= '&erro='.$erro;
echo $dados;
?>