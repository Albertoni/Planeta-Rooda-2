<?php
session_start();

require_once("../../cfg.php");
require_once("../../bd.php");
require_once("../../funcoes_aux.php");
require_once("../../usuarios.class.php");
require_once("aula.class.php");

session_start();
$usuario = new Usuario();
$usuario->openUsuario($_SESSION['SS_usuario_id']);

if (!isset($_SESSION['SS_usuario_nivel_sistema'])) // if not logged in
	die("Voce precisa estar logado para acessar essa pagina. <a href=\"../../\">Favor voltar.</a>");

if (isset($_GET['turma']) and is_numeric($_GET['turma'])){
	$turma = $_GET['turma'];
}

$permissoes = checa_permissoes(TIPOAULA, $turma);
if ($permissoes === false){die("Funcionalidade desabilitada para a sua turma.");}

if(!$usuario->podeAcessar($permissoes['aulas_deletarAulas'], $turma)){
	$host	=	$_SERVER['HTTP_HOST'];
	$uri	=	rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	header("Location: http://$host$uri/planeta_aulas.php?turma=$turma");
}

if (isset($_GET['id'])){
	$a = new aula();
	if ($a->deletaAula($_GET['id'])){
		echo "<script>history.go(-1);</script>";
	}else{
		echo "<h2>Erro!!1 Favor clicar <a onclick=\"history.go(-1)\">aqui</a> para voltar e tentar novamente.</h2><br />ERRO: ".$a->getErro();
	}
}
?>
