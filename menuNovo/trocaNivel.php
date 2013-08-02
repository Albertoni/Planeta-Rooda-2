<?php
session_start();
require_once('funcoesMenuTurma.php');

$turma = (int) $_POST['turma'];
$userId = (int) $_POST['userId'];
$nivel = (int) $_POST['nivel'];

if(isProfessor($_SESSION['SS_usuario_id'], $turma)){
	$erro = trocaNivel($userId, $turma, $nivel);
	
	if($erro == ""){
		echo "OK";
	}else{
		echo "Um erro ocorreu. Por favor recarregue a página e tente novamente.";
	}
}else{
	die("Você não tem permissões para fazer isso.");
}
