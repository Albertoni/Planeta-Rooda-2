<?php 
require('../../cfg.php');
require('../../bd.php');
require('../../turma.class.php');
require('../../planeta.class.php');
require('../../terreno.class.php');

$q = new conexao();

$nomeTurma = $q->sanitizaString($_POST['turma']); //O Método salvar() irá sanitizar novamente, isso levará a algum problema ao sanitizar duas vezes uma mesma string??
$descricao = $q->sanitizaString($_POST['descricao']); //O Método salvar() irá sanitizar novamente, isso levará a algum problema ao sanitizar duas vezes uma mesma string??

$idProfResponsavel = (int) $_POST['idProfResponsavel'];
$aparenciaPlaneta = (int) $_POST['tipoTerreno'];

$novoTerrenoPrincipal = new Terreno("",0,0,false); //cria o terrenoPrincipal a ser atribuido ao novoPlaneta.
$novoTerrenoPatio = new Terreno("",0,0,true); //cria o terrenoPatio a ser atribuido ao novoPlaneta.

$novoTerrenoPrincipal->salvar();
$novoTerrenoPatio->salvar();

$novoPlaneta = new Planeta("",$aparenciaPlaneta,0,$novoTerrenoPrincipal->getId(),$novoTerrenoPatio->getId());
$novoPlaneta->salvar();

/* Este trecho agora deve ser feito pela chamada do método salvar da classe Planeta.
$q->solicitar("INSERT INTO Planetas
					(Nome, Aparencia, EhVisitante, IdTerrenoPrincipal, IdTerrenoPatio);
				VALUES
					('',$aparenciaPlaneta,'0','0','0')");
*/
				
//$idPlaneta = $q->ultimoId(); Ainda é necessário?

$novaTurma = new Turma($nomeTurma,$idProfResponsavel,$descricao,0,0,0,$novoPlaneta->getId());
$novaTurma->salvar();

/*  Este trecho agora deve ser feito pela chamada do método salvar da classe turma.
$q->solicitar("INSERT INTO Turmas 
					(nomeTurma, profResponsavel, descricao, serie, Escola, chat_id,idPlaneta)
				VALUES
					('$nomeTurma', '$idProfResponsavel','$descricao','0','0','0',$idPlaneta)");
*/

//$codTurma = $q->ultimoId(); Ainda é necessário?

$alunos = explode(';', $_POST['ids_alunos']);

$numeroAlunos = sizeof($alunos);

$parteDinamica = array();
for($i=0; $i<$numeroAlunos; $i++){
	$codUsuario = $q->sanitizaString($alunos[$i]);
	$parteDinamica[$i] = "('$novaTurma->getId()', '$codUsuario', 16)";//$novaTurma->getId() subistituindo $codTurma
}

$q->solicitar("INSERT INTO TurmasUsuario(codTurma, codUsuario, associacao)
				VALUES".implode(',', $parteDinamica));
				