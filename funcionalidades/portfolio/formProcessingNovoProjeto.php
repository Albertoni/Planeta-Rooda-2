<?php
require("../../cfg.php");
require("../../bd.php");
require("../../funcoes_aux.php");
require("../../usuarios.class.php");

session_start();

print_r($_POST);

$turma = is_numeric($_POST['turma']) ? $_POST['turma'] : die("Um identificador de turma inv&aacute;lido foi enviado para essa p&aacute;gina.");

global $tabela_portfolioProjetos;
$permissoes = checa_permissoes(TIPOPORTFOLIO, $turma);
if($permissoes === false){
	die("Sua turma nao tem permissoes para isso.");
}
$luser = new Usuario();
$luser->openUsuario($_SESSION['SS_usuario_id']);
if(!$luser->podeAcessar($permissoes['portfolio_inserirPost'], $turma)){
	die("Voce nao tem permissoes para isso.");
}

$consulta = new conexao();

$usuario_id		=	$_SESSION['SS_usuario_id']									or die("Nao sei como voce chegou aqui sem estar logado, mas parabens.");
$titulo			=	mysql_real_escape_string($_POST['titulo_projeto'])			or die("Um titulo &eacute; necessario para criar um projeto.");
$descricao		=	mysql_real_escape_string($_POST['descricao_projeto']);
$conteudos		=	mysql_real_escape_string($_POST['conteudos_projeto']);
$objetivos		=	mysql_real_escape_string($_POST['objetivos_projeto'])		or die("&Eacute; necessario um objetivo para criar um projeto.");
$metodologia	=	mysql_real_escape_string($_POST['metodologia_projeto']);
$publicoAlvo	=	mysql_real_escape_string($_POST['publicoAlvo_projeto']);
$autor			=	mysql_real_escape_string($_POST['autor_projeto'])			or die("O nome do autor &eacute; necessario para criar um projeto.");
$tags			=	mysql_real_escape_string($_POST['tags_projeto']);
$text			=	mysql_real_escape_string($_POST['text']);

$consulta->solicitar("INSERT INTO $tabela_portfolioProjetos
(titulo,		autor,		descricao,		objetivos,		conteudosAbordados,		metodologia,		publicoAlvo,		tags,		dataCriacao,	owner_id,		turma) VALUES
('$titulo',		'$autor',	'$descricao',	'$objetivos',	'$conteudos',			'$metodologia',		'$publicoAlvo',		'$tags',	NOW(),			$usuario_id,	$turma)");

$projeto_id = mysql_insert_id();

$consulta->solicitar("INSERT INTO $tabela_portfolioPosts
(projeto_id,		titulo,			tags,		texto,		user_id,		dataCriacao) VALUES
('$projeto_id',		'$titulo',		'$tags',	'$text',	$usuario_id,	NOW());");

magic_redirect("portfolio.php?turma=$turma");

?>
