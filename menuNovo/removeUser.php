<?php
session_start();
require('funcoesMenuTurma.php');

$turma = (int) $_POST['turma'];
$userId = (int) $_POST['userId'];

if(isProfessor($_SESSION['SS_usuario_id'], $turma)){
	$erro = removeUser($userId, $turma);
	
	if($erro == ""){
		echo "OK";
	}else{
		echo "Um erro ocorreu. Por favor recarregue a página e tente novamente.";
	}
}else{
	die("Você não tem permissões para fazer isso.");
}
