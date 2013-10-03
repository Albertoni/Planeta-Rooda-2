<?php
require_once("../../cfg.php");
require_once("../../bd.php");
require_once("../../funcoes_aux.php");
require_once("sistema_forum.php");

$user = usuario_sessao();

if($user === false){
	die("Voce tem que estar logado para acessar essa pagina. Favor entrar novamente no sistema.");
}

$idTopico	= (isset($_POST['idTopico']) and is_numeric($_POST['idTopico'])) ? $_POST['idTopico'] : die("Erro desconhecido, por favor recarregue a pagina tente novamente1.");
$idTurma	= (isset($_POST['turma']) and is_numeric($_POST['turma'])) ? $_POST['turma'] : die("Erro desconhecido, por favor recarregue a pagina tente novamente2.");

$permissoes = checa_permissoes(TIPOFORUM, $idTurma);
if($permissoes === false){
	die("Funcionalidade desabilitada para a sua turma.");
}

if($user->podeAcessar($permissoes['forum_excluirTopico'], $idTurma)){
	$q = new conexao();
	$q->solicitar("DELETE FROM ForumTopico WHERE idTopico = $idTopico");

	if($q->erro != ""){
		die("Aconteceu um erro com o banco de dados, por favor volte e tente novamente.");
	}

	$q->solicitar("DELETE FROM ForumMensagem WHERE idTopico = $idTopico");

	if($q->erro == ""){
		echo "ok";
	}else{
		die("Aconteceu um erro com o banco de dados, por favor volte e tente novamente.");
	}
}else{
	die("Voce nao tem permissoes para deletar topicos");
}