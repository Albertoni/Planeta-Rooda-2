<?php
require_once("../../cfg.php");
require_once("../../bd.php");
require_once("../../funcoes_aux.php");
require_once("sistema_forum.php");
require_once("../../arquivo.class.php");
$user = usuario_sessao();

if($user === false){
	die("Voce tem que estar logado para acessar essa pagina. Favor entrar novamente no sistema.");
}

$idMensagem	= isset($_POST['idMensagem']) ? (int) $_POST['idMensagem'] : 0;
$mensagem = new mensagem($idMensagem);
$idTopico	= $mensagem->getIdTopico();
$idTurma	= $mensagem->getIdTurma();
$conteudo	= $_POST['msg_conteudo'];
$idMensagemRespondida = isset($_POST['mensagemRespondida']) (int) $_POST['mensagemRespondida'];

$perm = checa_permissoes(TIPOFORUM, $idTurma);
if($perm === false){
	die('{"erro":"Funcionalidade desabilitada para a sua turma."}');
}
if (!usuarioPertenceTurma($user,$idTurma)) {
	die('{"erro":"Voc&ecirc; n&atilde;o est&aacute; nesta turma."}');
}

if($idMensagem != -1){ // editando
	$mensagem = new mensagem($idMensagem);
	$idTurma = $mensagem->getIdTurma();
	if($user->podeAcessar($perm['forum_editarResposta'], $idTurma)){
		if($user->podeAcessar($perm['forum_excluirAnexos'], $turma)){
			print_r($_POST['deletarAnexo']);
		} else {
			echo "nao pode";
		}
		$mensagem->setTexto($conteudo);
		$mensagem->salvar();
	}else{
		die('{"erro":"Voc&ecirc; n&atilde;o pode editar mensagens nesta turma."}');
	}
}else{ // criando
	if($user->podeAcessar($perm['forum_responderTopico'], $idTurma)){
		$mensagem = new mensagem(0, $idTopico, $_SESSION['SS_usuario_id'], $conteudo, $idMensagemRespondida);
		$mensagem->salvar();
	}else{
		die('{"erro":"Voc&ecirc; n&atilde;o responder mensagens nesta turma."}');
	}
}
$mensagemResposta = new mensagem();
$mensagemResposta->carregar($mensagem->getId());
if (isset($_FILES['arquivo'])) {
	try {
		$arquivo = new Arquivo();
		$arquivo->setArquivo($_FILES['arquivo']);
		$arquivo->setIdUsuario($user->getId());
		$arquivo->salvar();
		$mensagemResposta->addAnexo($arquivo);
	} catch (Exception $e) {
		die('{"erro":"'.$e->getMessage().'"}');
	}
}
echo json_encode($mensagemResposta->toJson());