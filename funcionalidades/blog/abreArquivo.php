<?php
require_once("ArquivosPost.class.php");
require_once("blog.class.php");
$idPost = isset($_GET['p']) ? (int) $_GET['p'] : 0;
$idArquivo = isset($_GET['a']) ? (int) $_GET['a'] : 0;

$usuario = usuario_sessao();
if (!$usuario) {
	// não está logado
	echo "Voc&ecirc; n&atilde.o est&aacute; autenticado.";
	exit;
}

$bd = new conexao();
$bd->solicitar(
	"SELECT bb.Turma as turma FROM blogposts as bp 
		INNER JOIN blogblogs as bb
		ON bb.id = bp.BlogId
	WHERE bp.id = $idPost"
);
$idTurma = (int) $bd->resultado['turma'];

if (!$usuario->pertenceTurma($idTurma)) {
	echo "Voc&ecirc; n&atilde.o tem permiss&atilde;o para acessar este recurso.";
	exit;
}

$perm = checa_permissoes(TIPOBLOG, $idTurma);
if ($perm === false) {
	echo "Funcionalidade desabilitada nesta turma.";
	exit;
}

$arquivo = new ArquivosPost($idPost, $idArquivo);
if ($arquivo->getId()) {
	$arquivo->baixar();
} else {
	echo "Arquivo não encontrado, a=$idArquivo, p=$idPost";
}