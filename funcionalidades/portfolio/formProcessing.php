<?php
require_once("../../cfg.php");
require_once("../../bd.php");
require_once("../../funcoes_aux.php");
require_once("../../usuarios.class.php");
require_once ('portfolio.class.php');

$user = usuario_sessao();

$turma = is_numeric($_POST['turma']) ? $_POST['turma'] : die("Um identificador de turma inv&aacute;lido foi enviado para essa p&aacute;gina.");
$idProjeto = is_numeric($_POST['projeto_id']) ? $_POST['projeto_id'] : die("Um identificador de projeto inv&aacute;lido foi enviado para essa p&aacute;gina.");

$perm = checa_permissoes(TIPOPORTFOLIO, $turma);
if($perm == false){
	die("Desculpe, mas os Projetos est&atilde;o desabilitados para esta turma.");
}

$projeto = new projeto($idProjeto);

$donos = $projeto->getOwners();

if(!in_array($user->getId(), $donos)){
	die("Voc&ecirc; n&atilde;o est&aacute; nesse projeto e n&atilde;o pode postar nele.");
}

// Isso parece uma checagem desnecessária, mas se não for por isso pode-se passar uma ID de turma na qual se tem permissões de postar, para postar em uma turma na qual você não tem permissões para postar.
if($turma != $projeto->getTurma()){
	die("A identifica&ccedil;&atilde;o de turma passada para essa pagina n&atilde;o corresponde com a identifica&ccedil;&atilde;o de turma que o projeto tem. Isso &eacute; um erro.");
}

if ($_POST['update'] == 1){
	$post = new post($_POST['post_id']);

	if($post === false){ // post com aquele id não existe
		die("Erro: Aparentemente voc&ecirc; tentou editar um post que n&atilde;o existe.");
	}
	
	// Nao tem como (ou porque) trocar de id de projeto e turma aqui
	$post->setTitulo($_POST['titulo']);
	$post->setTexto($_POST['text']);
	$post->setTags($_POST['tags']);

	echo $post->salvar();
}else{
	$dados = array(
		'id' => $_POST['post_id'],
		'projeto_id' => $idProjeto,
		'user_id' => $user->getId(),
		'titulo' => $_POST['titulo'],
		'texto' => $_POST['text'],
		'tags' => $_POST['tags']
		);

	$post = new post(0, $dados);
	echo $post->salvar();
}


magic_redirect("portfolio_projeto.php?projeto_id=".$idProjeto."&turma=".$turma);