<?php
session_start();
//arquivos necessários para o funcionamento
require_once("../cfg.php");
require_once("../bd.php");
require_once("../funcoes_aux.php");

/*---------------------------------------------------
*	Salva uma fala do personagem online.
---------------------------------------------------*/
$id_chat			= $_POST["id_chat"];
$ha_autor			= $_POST["ha_autor"];
$mensagem    		= $_POST["mensagem"];
$id_personagem 		= $_SESSION['SS_personagem_id'];

$dataAtualServidor = strtotime("-5 seconds"); 
$dataAtualServidor = date("Y-m-d H:i:s", $dataAtualServidor);
$houveErro = false;

//Verificar o caso de ser mensagem privada e o destino estar offline.
$conexao_erros = new conexao();
$conexao_erros->solicitar("SELECT * 
						FROM personagens 
						WHERE chat_id = $id_chat");
if(0 < $conexao_erros->registros){
	if($conexao_erros->resultado['personagem_ultimo_acesso'] < $dataAtualServidor){
		$houveErro = true;
		$erro = '2';
	}							
} 

if($houveErro == false){
	$conexao_falas_personagem = new conexao();
	if($ha_autor == 'true'){
		$conexao_falas_personagem->solicitar("INSERT INTO falas_personagens (id_personagem, texto_fala, data, chat_id)
											VALUES ($id_personagem, '$mensagem', now(), $id_chat)");
	} else {
		$conexao_falas_personagem->solicitar("INSERT INTO falas_personagens (texto_fala, data, chat_id)
											VALUES ('$mensagem', now(), $id_chat)");
	}
	$erro = '0';
}

$dados = '&erro='.$erro;
echo $dados;
?>
