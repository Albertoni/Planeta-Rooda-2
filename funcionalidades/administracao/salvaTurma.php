<?php 
require('../../cfg.php');
require('../../bd.php');
require('../../turma.class.php');
require('../../planeta.class.php');
require('../../terreno.class.php');
require('../../funcoes_aux.php'); 

$q = new conexao();

$nomeTurma = $_POST['turma'];
$descricao = $_POST['descricao']; 

$idProfResponsavel = $_POST['idProfResponsavel'];
$aparenciaPlaneta = $_POST['tipoTerreno'];

$novoTerrenoPrincipal = new Terreno(0,0,false); //cria o terrenoPrincipal a ser atribuido ao novoPlaneta.
$novoTerrenoPatio = new Terreno(0,0,true); //cria o terrenoPatio a ser atribuido ao novoPlaneta.

$novoTerrenoPrincipal->salvar();
$novoTerrenoPatio->salvar();

$novoPlaneta = new Planeta($aparenciaPlaneta,0,$novoTerrenoPrincipal->getId(),$novoTerrenoPatio->getId());
$novoPlaneta->salvar();

$novaTurma = new Turma($nomeTurma,$idProfResponsavel,$descricao,0,0,0,$novoPlaneta->getId());
$novaTurma->salvar();
				
magic_redirect("listaFuncionalidadesAdministracao.php");