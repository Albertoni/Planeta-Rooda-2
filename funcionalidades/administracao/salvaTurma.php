<?php 
require('../../cfg.php');
require('../../bd.php');
require('../../planeta.class.php');
require('../../terreno.class.php');


$q = new conexao();

$nomeTurma = $q->sanitizaString($_POST['turma']);
$descricao = $q->sanitizaString($_POST['descricao']);

$idProfResponsavel = (int) $_POST['idProfResponsavel'];
$aparenciaPlaneta = (int) $_POST['tipoTerreno'];

$novoPlaneta = new Planeta("",$aparenciaPlaneta,0,0,0);

$q->solicitar("INSERT INTO Planetas
					(Nome, Aparencia, EhVisitante, IdTerrenoPrincipal, IdTerrenoPatio);
				VALUES
					('',$aparenciaPlaneta,'0','0','0')");
				
$idPlaneta = $q->ultimoId();

$q->solicitar("INSERT INTO Turmas 
					(nomeTurma, profResponsavel, descricao, serie, Escola, chat_id,idPlaneta)
				VALUES
					('$nomeTurma', '$idProfResponsavel','$descricao','0','0','0',$idPlaneta)");

$codTurma = $q->ultimoId();

$alunos = explode(';', $_POST['ids_alunos']);

$numeroAlunos = sizeof($alunos);

$parteDinamica = array();
for($i=0; $i<$numeroAlunos; $i++){
	$codUsuario = $q->sanitizaString($alunos[$i]);
	$parteDinamica[$i] = "('$codTurma', '$codUsuario', 16)";
}

$q->solicitar("INSERT INTO TurmasUsuario(codTurma, codUsuario, associacao)
				VALUES".implode(',', $parteDinamica));

//$terrenoPrincipal = new Terreno("Terreno da turma $nomeTurma", );
				