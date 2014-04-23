<?php 
require('../../cfg.php');
require('../../bd.php');


$q = new conexao();

$nomeTurma = $q->sanitizaString($_POST['turma']);
$descricao = $q->sanitizaString($_POST['descricao']);

$q->solicitar("INSERT INTO Turmas (nomeTurma, profResponsavel, descricao, serie, Escola, chat_id)				VALUES('$nomeTurma', '$idProfResponsavel','$descricao','0','0','0' )");

$codTurma = $q->ultimoId();

$alunos = explode(';', $_POST['ids_alunos']);

$numeroAlunos = sizeof($alunos);

$parteDinamica = array();
for($i=0; $i<$numeroAlunos; $i++){
	$codUsuario = $alunos[$i];
	$parteDinamica[$i] = "('$codTurma', '$codUsuario', 16)";
}

$q->solicitar("INSERT INTO TurmasUsuario(codTurma, codUsuario, associacao)
				VALUES".implode(',', $parteDinamica));