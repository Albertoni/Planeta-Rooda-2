<?php
session_start();
//arquivos necessários para o funcionamento
require_once("../cfg.php");
require_once("../bd.php");
require_once("../funcoes_aux.php");

$deve_filtrar_mensagens = $_POST["deve_filtrar_mensagens"];
$id_chat = $_POST["identificacao"];
$idUltimaFalaRecebida = $_POST['idUltimaFalaRecebida'];
$id_personagem_online = $_SESSION['SS_personagem_id'];

$dataAtualServidor = strtotime("-5 seconds"); 
$dataAtualServidor = date("Y-m-d H:i:s", $dataAtualServidor);

$conexao_personagens = new conexao();
$conexao_chat = new conexao();
$conexao_erros = new conexao();

$dados = '';

//Atualizar a tabela de chats em que o usuário está, incluindo este.
$conexao_chat->solicitar("SELECT *
						  FROM ChatsUsuarios
						  WHERE usuario_id = $id_personagem_online
							AND chat_id = $id_chat");
if($conexao_chat->registros != 0){
	$conexao_chat->solicitar("UPDATE ChatsUsuarios
							  SET data = now()
							  WHERE usuario_id = $id_personagem_online
								AND chat_id = $id_chat");						
} else {
	$conexao_chat->solicitar("INSERT INTO ChatsUsuarios (usuario_id, chat_id, data)
							  VALUES ($id_personagem_online, $id_chat, now())");
}
$conexao_chat->solicitar("DELETE FROM ChatsUsuarios
						  WHERE usuario_id = $id_personagem_online
							AND data < '$dataAtualServidor'");

//Verificar se o chat existe.
$conexao_erros->solicitar("SELECT * 
						FROM Chats 
						WHERE id = $id_chat");
$houveErro = false;
if(0 == $conexao_erros->registros){
	$erro = '3';
	$houveErro = true;
} 

if($houveErro == false){
	if($deve_filtrar_mensagens == 'false'){
		if($idUltimaFalaRecebida != "" and $idUltimaFalaRecebida != "undefined"){
			$conexao_chat->solicitar("SELECT * 
									FROM falas_personagens 
									WHERE chat_id = $id_chat
										AND id_fala > $idUltimaFalaRecebida
									ORDER BY id_fala ASC");
		} else {
			$conexao_chat->solicitar("SELECT * 
									FROM falas_personagens 
									WHERE chat_id = $id_chat
										AND data > '$dataAtualServidor'
									ORDER BY id_fala ASC");
		}
	} else {
		if($idUltimaFalaRecebida != "" and $idUltimaFalaRecebida != "undefined"){
			$conexao_chat->solicitar("SELECT * 
									FROM falas_personagens 
									WHERE chat_id = $id_chat
										AND id_fala > $idUltimaFalaRecebida
										AND id_personagem = $id_personagem_online
									ORDER BY id_fala ASC");
		} else {
			$conexao_chat->solicitar("SELECT * 
									FROM falas_personagens 
									WHERE chat_id = $id_chat
										AND data > '$dataAtualServidor'
										AND id_personagem = $id_personagem_online
									ORDER BY id_fala ASC");
		}
		
	}
	
	$idUltimaFala = $idUltimaFalaRecebida;

	for($registroAtual=0; $registroAtual < $conexao_chat->registros; $registroAtual++){
		$fala = $conexao_chat->resultado['texto_fala'];
		$id_autor = $conexao_chat->resultado['id_personagem'];
		$conexao_personagens->solicitar("SELECT personagem_nome 
										FROM personagens 
										WHERE personagem_id = $id_autor");
		$nome_autor	= $conexao_personagens->resultado['personagem_nome'];
		
		$dados .= '&fala'.$registroAtual.		'='.$fala;
		$dados .= '&autor_fala'.$registroAtual.		'='.$nome_autor;
		
		$idUltimaFala = $conexao_chat->resultado['id_fala'];
		
		$conexao_chat->proximo();
	}
	$falas_encontradas = $conexao_chat->registros;
	
	$erro = '0';
	$dados .= '&numero_falas='.$falas_encontradas;
	$dados .= '&idUltimaFala='.$idUltimaFala;
}
$dados .= '&dataAtualServidor='.$dataAtualServidor;
$dados .= '&erro='.$erro;
echo $dados;
?>