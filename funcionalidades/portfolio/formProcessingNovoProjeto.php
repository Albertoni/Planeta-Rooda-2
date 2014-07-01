<?php
require_once("../../cfg.php");
require_once("../../bd.php");
require_once("../../funcoes_aux.php");
require_once("../../usuarios.class.php");
require("portfolio.class.php");
print_r($_POST);

$user = usuario_sessao();
if($user === false){
	die("Voce precisa estar logado para criar um projeto.");
}

$turma = is_numeric($_POST['turma']) ? $_POST['turma'] : die("Um identificador de turma inv&aacute;lido foi enviado para essa p&aacute;gina.");

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
$titulo		=$_POST['titulo_projeto'];
$tags		= explode(';', $_POST['tags_projeto']);
$dataInicio	=DateTime::createFromFormat('d/m/Y', $_POST['data_inicio_projeto'])->format('Y-m-d H:i:s');
$dataEncerramento=DateTime::createFromFormat('d/m/Y', $_POST['data_encerramento_projeto'])->format('Y-m-d H:i:s');
$donos		=explode(';', $_POST['owner_ids']);
$turma		=$_POST['turma'];

if(isset($_POST['idProjetoEmEdicao']) && $_POST['idProjetoEmEdicao']!=0){
    $projeto = new projeto($_POST['idProjetoEmEdicao']);

    $projeto->setTitulo($titulo);
    $projeto->setTags($tags);
    $projeto->setDataCriacao($dataInicio);
    $projeto->setDataEncerramento($dataEncerramento);
    $projeto->setOwnersIds($donos);

    echo $projeto->salvar();
}
else{
    $projeto = new projeto(0, $titulo, $tags, $dataInicio, $dataEncerramento, $donos, $turma);
    echo $projeto->salvar();
}
print_r($projeto);
//magic_redirect("portfolio.php?turma=$turma");