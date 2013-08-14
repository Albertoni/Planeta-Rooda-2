<?php
require_once("../../cfg.php");
require_once("../../bd.php");
require_once("../../funcoes_aux.php");
require_once("sistema_forum.php");

$user = usuario_sessao();

if($user === false){
	die("Voce tem que estar logado para acessar essa pagina. Favor entrar novamente no sistema.");
}

$idTopico	= (int) $_POST['idTopico'];
$idTurma	= (int) $_POST['turma'];
$conteudo	= $_POST['msg_conteudo'];
$idMensagemRespondida = (int) $_POST['mensagemRespondida'];

$permissoes = checa_permissoes(TIPOFORUM, $idTurma);
if($permissoes === false){
	die("Funcionalidade desabilitada para a sua turma.");
}

$mensagem = new mensagem(0, $idTopico, $_SESSION['SS_usuario_id'], $conteudo, $idMensagemRespondida);
$mensagem->salvar();
echo json_encode($mensagem->toJson());