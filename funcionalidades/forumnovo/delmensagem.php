<?php
require_once("../../cfg.php");
require_once("../../bd.php");
require_once("../../funcoes_aux.php");
require_once("sistema_forum.php");

$user = usuario_sessao();

if($user === false){
	die("Voce tem que estar logado para acessar essa pagina. Favor entrar novamente no sistema.");
}

$idMensagem = ($_POST['idMensagem']) and is_numeric($_POST['idMensagem']) ? $_POST['idMensagem'] : die("Erro desconhecido, por favor recarregue a pagina tente novamente.");
$idTurma = ($_POST['turma']) and is_numeric($_POST['turma']) ? $_POST['turma'] : die("Erro desconhecido, por favor recarregue a pagina tente novamente.");

$permissoes = checa_permissoes(TIPOFORUM, $idTurma);
if($permissoes === false){
	die("Funcionalidade desabilitada para a sua turma.");
}

if($user->podeAcessar($permissoes['forum_excluirResposta'], $idTurma)){
	$q = new conexao();
	$q->solicitar("DELETE FROM ForumMensagem WHERE idMensagem = $idMensagem");

	if($q->erro == ""){
		echo "ok";
	}else{
		die("Aconteceu um erro com o banco de dados, por favor volte e tente novamente.");
	}
}else{
	die("Voce nao tem permissoes para deletar mensagens");
}