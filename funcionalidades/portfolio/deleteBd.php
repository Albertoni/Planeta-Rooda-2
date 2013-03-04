<?php

require("../../cfg.php");
require("../../bd.php");
require("../../funcoes_aux.php");
require("../../usuarios.class.php");

session_start();
header('Content-Type: text/html; charset=utf-8');

if (!isset($_SESSION['SS_usuario_nivel_sistema'])) // if not logged in
	die('<script>alert("Voce precisa estar logado para acessar essa pagina. Favor voltar.");</script>');

$turma = is_numeric($_GET['turma']) ? $_GET['turma'] : die('<script>alert("A id da turma precisa estar declarada");</script>');

$perm = checa_permissoes(TIPOPORTFOLIO, $turma);
if($perm == false){
	die('<script>alert("Desculpe, mas o Portfolio esta desabilitado para esta turma. Agora, me explique, pelo amor de deus, o que voce queria fazer, deletando um portfolio de uma turma SEM PORTFOLIOS.");</script>');
}

if($_SESSION['user']->podeAcessar($perm['portfolio_excluirPost'], $turma)){
	$consulta= new conexao();
	$id = mysql_real_escape_string($_GET['id']);
	$tabela = mysql_real_escape_string($_GET['tabela']);
	$coluna = mysql_real_escape_string($_GET['coluna']);
	$consulta->solicitar("DELETE FROM $tabela WHERE $coluna = $id");
	if ($consulta->erro != ""){
		echo '<script>alert("Ocorreu o seguinte erro na comunicação com o banco de dados, por favor envie esse texto para os desenvolvedores:'+$consulta->erro+'");</script>';
	}
}else{
	echo '<script>alert("Você não tem permissão para deletar posts nessa turma.");</script>';
}
?>
