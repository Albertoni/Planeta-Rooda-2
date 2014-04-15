<?php
require_once("ArquivosPost.class.php");
require_once("portfolio.class.php");
$idPost = isset($_GET['p']) ? (int) $_GET['p'] : 0;
$idArquivo = isset($_GET['a']) ? (int) $_GET['a'] : 0;

$usuario = usuario_sessao();
if (!$usuario) {
	// não está logado
	echo "Voc&ecirc; n&atilde.o est&aacute; autenticado.";
	exit;
}

$post = new post($idPost);
$idProjeto = $post->getIdProjeto();

$projeto = new projeto($idProjeto);
$idTurma = (int) $projeto->getTurma();

if (!$usuario->pertenceTurma($idTurma)) {
	echo "Voc&ecirc; n&atilde.o tem permiss&atilde;o para acessar este recurso.";
	exit;
}

$perm = checa_permissoes(TIPOPORTFOLIO, $idTurma);
if ($perm === false) {
	echo "Funcionalidade desabilitada nesta turma.";
	exit;
}

$arquivo = new ArquivosPost($idPost, $idArquivo);
if ($arquivo->getId()) {
	$arquivo->baixar();
} else {
	echo "Arquivo n&atilde;o encontrado, a=$idArquivo, p=$idPost";
}