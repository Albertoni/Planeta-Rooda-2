<?php
/* Tentar manter este arquivo o menor possivel, tendo apenas o codigo que diferencia uma funcionalidade da outra */
require_once("../../comentarios.class.php");
/*---------------------------------------------------------------
	PARA PORTAR PARA OUTRA FUNCIONALIDADE, MUDAR SOMENTE ABAIXO
------------------------------------------ --------------------*/
// tabela onde ficam os comentarios dessa funcionalidade
Comentario::$tabelaBD = "blogComentarios";
// para verificação de permissões: identificador da funcionalidade e nome das permissoes
define('FUNCIONALIDADE',      TIPOBLOG);
define('PERM_COMENT_VER',     'blog_verComentarios');
define('PERM_COMENT_INSERIR', 'blog_inserirComentarios');
define('PERM_COMENT_EXCLUIR', 'blog_excluirComentarios');

require_once("blog.class.php");
// recebe o id do recurso que pode ter comentarios e retorna o titulo
function tituloDaRef($idRef) {
	$post = new Post();
	$post->open($idRef);
	return $post->getTitle();
}
// recebe o id do recurso que pode ter comentarios e retorna o id do usuario dono/autor do recurso
function usuarioDaRef($idRef) {
	$post = new Post();
	$post->open($idRef);
	return (int) $post->getAuthor()->getId();
}
// recebe o id do recurso que pode ter comentarios e retorna o id da turma que ele pertence
function turmaDaRef($idRef) {
	$bd = new conexao();
	$bd->solicitar(
		"SELECT bb.Turma as turma FROM blogposts as bp 
			INNER JOIN blogblogs as bb
			ON bb.id = bp.BlogId
		WHERE bp.id = $idRef"
	);
	return (int) $bd->resultado['turma'];
}
/*---------------------------------------------------------------
	NAO MUDAR NADA ABAIXO SE FOR APENAS PORTAR
------------------------------------------ --------------------*/
require_once("../../comentarios.json.php");
?>