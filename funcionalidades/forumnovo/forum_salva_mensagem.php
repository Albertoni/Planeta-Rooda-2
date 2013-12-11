<?php
require_once("../../cfg.php");
require_once("../../bd.php");
require_once("../../funcoes_aux.php");
require_once("sistema_forum.php");

$user = usuario_sessao();

if($user === false){
	die("Voce tem que estar logado para acessar essa pagina. Favor entrar novamente no sistema.");
}

$idMensagem	= ((isset($_POST['idMensagem']) and is_numeric($_POST['idMensagem'])) ? $_POST['idMensagem'] : -1);

$idTopico	= (int) $_POST['idTopico'];
$idTurma	= (int) $_POST['turma'];
$conteudo	= $_POST['msg_conteudo'];
$idMensagemRespondida = (int) $_POST['mensagemRespondida'];

$permissoes = checa_permissoes(TIPOFORUM, $idTurma);
if($permissoes === false){
	die("Funcionalidade desabilitada para a sua turma.");
}

if($idMensagem != -1){ // editando
	$mensagem = new mensagem($idMensagem);
	$mensagem->setTexto($conteudo);
	$mensagem->salvar();
}else{ // criando
	$mensagem = new mensagem(0, $idTopico, $_SESSION['SS_usuario_id'], $conteudo, $idMensagemRespondida);
	$mensagem->salvar();
}


$mensagemResposta = new mensagem();
$mensagemResposta->carregar($mensagem->getId());
echo json_encode($mensagemResposta->toJson());