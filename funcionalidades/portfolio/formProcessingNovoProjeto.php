<?php
require_once("../../cfg.php");
require_once("../../bd.php");
require_once("../../funcoes_aux.php");
require_once("../../usuarios.class.php");
require("portfolio.class.php");

$user = usuario_sessao();
if($user === false){
	die("Voce precisa estar logado para criar um projeto.");
}

$turma = is_numeric($_POST['turma']) ? $_POST['turma'] : die("Um identificador de turma inv&aacute;lido foi enviado para essa p&aacute;gina.");

global $tabela_portfolioProjetos;
$permissoes = checa_permissoes(TIPOPORTFOLIO, $turma);
if($permissoes === false){
	die("Os Projetos est&atilde;o desabilitados para a sua turma.");
}

global $nivelProfessor;
if($user->getNivel($turma) != $nivelProfessor){
	die("Somente professores podem fazer isso, e voc&ecirc; n&atilde;o tem essa permiss&atilde;o.");
}

if(!$user->podeAcessar($permissoes['portfolio_inserirPost'], $turma)){
	die("Voce nao tem permissoes para isso.");
}

$usuario_id	=$user->getId();
$titulo		=($_POST['titulo_projeto'] or die("Um titulo &eacute; necessario para criar um projeto."));
$tags		=$_POST['tags_projeto'];
$text		=$_POST['text'];
$dataInicio	=$_POST['data_inicio_projeto'];
$dataEncerramento=$_POST['data_encerramento_projeto'];
$donos		=explode(';', $_POST['owner_ids']);

print_r($_POST);

$projeto = new projeto(0, $titulo, $tags, $dataInicio, $dataEncerramento, $donos);
$projeto->salvar();

//magic_redirect("portfolio.php?turma=$turma");

?>
