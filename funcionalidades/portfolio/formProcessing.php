<?php
require_once("../../cfg.php");
require_once("../../bd.php");
require_once("../../funcoes_aux.php");
require_once("../../usuarios.class.php");
require_once ('portfolio.class.php');

$user = usuario_sessao();

$turma = is_numeric($_POST['turma']) ? $_POST['turma'] : die("Um identificador de turma inv&aacute;lido foi enviado para essa p&aacute;gina.");

$perm = checa_permissoes(TIPOPORTFOLIO, $turma);
if($perm == false){
	die("Desculpe, mas os Projetos est&atilde;o desabilitados para esta turma.");
}

$consulta = new conexao();
$projeto_id_sanitizado = $consulta->sanitizaString($_POST['projeto_id']);

$consulta->solicitar("SELECT turma FROM $tabela_portfolioProjetos WHERE id = $projeto_id_sanitizado");
if($turma != $consulta->resultado['turma']){
	die("A identifica&ccedil;&atilde;o de turma passada para essa pagina n&atilde;o corresponde com a identifica&ccedil;&atilde;o de turma que o projeto tem. Isso &eacute; um erro.");
}

if ($_POST['update'] == 1){
	$post = new post($_POST['post_id']);
	
	$post->setTitulo($_POST['titulo']);
	$post->setTexto($_POST['text']);
	$post->setTags($_POST['tags']);

	echo $post->salvar();
}else{
	$dados = array(
		'id' => $_POST['post_id'],
		'projeto_id' => $_POST['projeto_id'],
		'user_id' => $user->getId(),
		'titulo' => $_POST['titulo'],
		'texto' => $_POST['text'],
		'tags' => $_POST['tags']
		);

	$post = new post(0, $dados);
	echo $post->salvar();
}


magic_redirect("portfolio_projeto.php?projeto_id=$projeto_id&turma=$turma");
?>
