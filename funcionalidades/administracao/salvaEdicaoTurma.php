<?php 
require('../../cfg.php');
require('../../bd.php');
require('../../turma.class.php');
require('../../planeta.class.php');
require('../../funcoes_aux.php'); 

//estabelece a conexão com DB
$q = new conexao();

//recolhe os forms passados pelo editaTurma.php nas variáveis a seguir
$codTurmaEditada = $_POST['turmaLista'];
$novoNomeTurma = isset($_POST['novoNomeTurma']);
$novaDescricaoTurma = isset($_POST['novaDescricao']);
$novoProfessorResponsavel = isset($_POST['ids_professores']);

if(isset($_POST['novaAparencia']))
	$novaAparenciaPlaneta = true;
else $novaAparenciaPlaneta = false;

//recupera os dados da Turmas a ser editada
$turmaEmEdicao = new Turma();
$turmaEmEdicao->openTurma($codTurmaEditada);
//recupera os dados do Planeta pertencente a Turma a ser editada
$planetaEmEdicao = new Planeta();
$planetaEmEdicao->abrir($turmaEmEdicao->getIdPlaneta());

//Se foi marcada alguma checkbox definindo um novo professor responsável...
if($_POST['ids_professores']!=""){
    $idProfessor = $_POST['ids_professores'];
    //...seta a o atributo correspondente na turma...
    $turmaEmEdicao->setProfessorResponsavel($idProfessor);
    //...verifica se há um registro na tabela turmasUsuario que corresponda a relação daquele professor com a turma que está sendo ediada...
    $q->solicitar("SELECT * FROM TurmasUsuario WHERE codTurma=$codTurmaEditada AND codUsuario=$idProfessor");
    //...se houver, então atualiza a associacao para NIVELPROFESSOR...
    if($q->registros!=0){
        $q->solicitar("UPDATE TurmasUsuario SET
                            associcao = ".NIVELPROFESSOR."
                            WHERE codTurma='$codTurmaEditada' AND codUsuario='$idProfessor'");
    }
    //...senão, insere uma entrada na tabela correspondente a esta relação.
    else{
        $q->solicitar("INSERT INTO TurmasUsuario (codTurma,codUsuario,associacao)
                                VALUES(
                                        '$codTurmaEditada',
                                        '$idProfessor',".
                                        NIVELPROFESSOR.")");
    }
}
//se foi dado um valor ao campo novo nome turma, então seta o atributo nome para o novo nome
if($novoNomeTurma AND $_POST['novoNomeTurma']!=""){
	$turmaEmEdicao->setNomeTurma($_POST['novoNomeTurma']);
	$planetaEmEdicao->setNome($_POST['novoNomeTurma']);
}
//se foi dado um valor ao campo nova descricao, então seta o atributo descricao para a nova descricao
if($novaDescricaoTurma  AND $_POST['novaDescricao']!="")
	$turmaEmEdicao->setDescricao($_POST['novaDescricao']);
//se foi selecionada uma nova aparencia para o planeta, então seta $novaAparenciaPlaneta para a aparencia já existente.
if($novaAparenciaPlaneta)
	$planetaEmEdicao->setAparencia($_POST['novaAparencia']);
//Atualiza no DB a edição feita.
$planetaEmEdicao->salvar();
$turmaEmEdicao->salvar();



magic_redirect("listaFuncionalidadesAdministracao.php");


	
	