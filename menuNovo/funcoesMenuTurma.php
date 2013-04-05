<?php
require_once("../bd.php");
require_once("../cfg.php");

function getUserLevel($userId, $turma){
	$q = new conexao();
	$q->solicitar("SELECT associacao FROM TurmasUsuario WHERE codUsuario = $userId AND codTurma = $turma");
	
	return $q->resultado['associacao'];
}

function isProfessor($userId, $turma){
	return (getUserLevel($userId, $turma) == 4);
}

function removeUser($userId, $turma){
	$q = new conexao();
	$q->solicitar("DELETE FROM TurmasUsuario WHERE codUsuario = $userId AND codTurma = $turma");
	
	return $q->erro;
}
