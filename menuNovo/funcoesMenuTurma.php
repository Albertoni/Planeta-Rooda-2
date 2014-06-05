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

function trocaNivel($userId, $turma, $nivel){
	$q = new conexao();
	$q->solicitar("UPDATE TurmasUsuario SET associacao = $nivel WHERE codUsuario = $userId AND codTurma = $turma");
	
	return $q->erro;
}

function imprimeFuncionalidadesAcessiveis($turma){
	$q = new conexao(); global $tabela_controleFuncionalidades;
	
	$q->solicitar("SELECT * FROM $tabela_controleFuncionalidades WHERE codTurma=$turma");
	
	$lista = array(
		'biblioteca' => 'Biblioteca',
		'blog' => 'Blog',
		'forum' => 'Forum',
		'planetaArte' => 'Planeta Arte',
		'aulas' => 'Planeta Aulas',
		'planetaPergunta' => 'Planeta Pergunta',
		'planetaPlayer' => 'Planeta Player',
		'portfolio' => 'Portfolio'
	);
	
	foreach($lista as $nomeBD => $nomeApresentavel){
		if($q->resultado[$nomeBD] == 'h'){ // está habilitado
			echo "				<li><a onclick=\"irFuncionalidade('$nomeBD', $turma)\">$nomeApresentavel</a>\n";
		}
	}
}
