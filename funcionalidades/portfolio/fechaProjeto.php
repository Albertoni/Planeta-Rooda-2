<?php
require_once("../../cfg.php");
require_once("../../bd.php");
require_once("../../funcoes_aux.php");

$user = usuario_sessao();
if($user === false){die("Você não está logado!");}

global $tabela_portfolioProjetos;
$consulta= new conexao();
$id = (int) $_GET['id'];

// Pega-se a turma do projeto para conferir se o cara é professor e pode mexer no status dos projetos
$consulta->solicitar("SELECT turma FROM $tabela_portfolioProjetos WHERE id='$id'");

if(!($user->getNivel($consulta->resultado['turma']) == NIVELPROFESSOR)){
	die("Você não é um professor nessa turma.");
}

if (isset($_GET['encerrar'])) {
	$emAndamento = 0;
}else if(isset($_GET['reativar'])) {
	$emAndamento = 1;
}else{
	die("Ação não definida no pedido feito ao servidor.");
}

$consulta->solicitar("UPDATE $tabela_portfolioProjetos SET emAndamento = $emAndamento WHERE id='$id'"); // SR. HACKER FAVOR IGNORAR AS VULNERABILIDADES DESTA PÁGINA MUITO OBRIGADO
if ($consulta->erro == ""){
	echo "ok";
}
