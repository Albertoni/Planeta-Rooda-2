<?php
require_once("../../cfg.php");
require_once("../../bd.php");
require_once("../../funcoes_aux.php");
require_once("../../usuarios.class.php");

session_start();

$turma = is_numeric($_GET['turma']) ? $_GET['turma'] : die("Um identificador de turma inv&aacute;lido foi enviado para essa p&aacute;gina.");

$perm = checa_permissoes(TIPOPORTFOLIO, $turma);
if($perm == false){
	die("Desculpe, mas os Projetos est&atilde;o desabilitados para esta turma.");
}

// TODO: Consertar o fato que o cara pode hackear o form pra usar a id de uma
//		turma na qual ele tem permissões para postar e bypassar isso.

// CONSERTADO! @ line 43

/* PS: A todos que verem isso, have an e-cookie:
    _.:::::._
  .:::'_|_':::.
 /::' --|-- '::\
|:" .---"---. ':|
|: ( H A C K ) :|
|:: `-------' ::|
 \:::.......:::/
  ':::::::::::'
     `'"""'`
*/

global $tabela_portfolioPosts; global $tabela_portfolioProjetos;
$consulta = new conexao();

$text_post		= $consulta->sanitizaString($_POST['text']);
$titulo_post	= $consulta->sanitizaString($_POST['titulo_post']);
$tags_post		= $consulta->sanitizaString($_POST['tags_post']);
$projeto_id		= $consulta->sanitizaString($_POST['projeto_id']);
$post_id		= $consulta->sanitizaString($_POST['post_id']);
$update			= $consulta->sanitizaString($_POST['update']);

$consulta->solicitar("SELECT turma FROM $tabela_portfolioProjetos WHERE id = $projeto_id");
if($turma != $consulta->resultado['turma']){
	die("A identifica&ccedil;&atilde;o de turma passada para essa pagina n&atilde;o corresponde com a identifica&ccedil;&atilde;o de turma que o projeto tem. Isso &eacute; um erro.");
}

if ($update == 1 and is_numeric($post_id)){

	if(!$_SESSION['user']->podeAcessar($perm['portfolio_editarPost'], $turma)){
		die("Desculpe, voce nao pode editar posts nessa turma.");
	}

	$consulta->solicitar("UPDATE $tabela_portfolioPosts
						SET titulo='$titulo_post', tags='$tags_post', texto='$text_post', dataUltMod=NOW()
						WHERE projeto_id='$projeto_id' AND id='$post_id';");

}else{

	if(!$_SESSION['user']->podeAcessar($perm['portfolio_inserirPost'], $turma)){
		die("Desculpe, voce nao pode inserir posts nessa turma.");
	}

	$consulta->solicitar("INSERT INTO $tabela_portfolioPosts
						(projeto_id,	titulo,			tags,		texto,			dataCriacao) VALUES
						('$projeto_id',	'$titulo_post','$tags_post','$text_post',	NOW());");
}

magic_redirect("portfolio_projeto.php?projeto_id=$projeto_id&turma=$turma");
?>
