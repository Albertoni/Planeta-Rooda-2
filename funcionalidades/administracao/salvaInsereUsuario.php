<?php
require('../../cfg.php');
require('../../bd.php');
require('../../turma.class.php');
require('../../funcoes_aux.php');

$q = new conexao();



$associacao = $_POST['associacao'];
$userId = $_POST['userId'];
$codTurma = $_POST['turmaLista'];//para saber por qual turma o usuario acessou o sistema
$deOndeVem = $_POST['deOndeVem'];//para saber por qual menu o usuario inseriu alunos
$turma = new Turma("",0,"",0,0,0,0);
$turma->openTurma($codTurma);

$alunos = explode(';', $_POST['ids_alunos']);

$numeroAlunos = sizeof($alunos);

$parteDinamica = array();

for($i=0; $i<$numeroAlunos; $i++){
	$codUsuario = $q->sanitizaString($alunos[$i]);
    $usuario = new Usuario();
    $usuario->openUsuario($codUsuario);
    //Validação de que apenas o professor responsável da turma poderá adicionar outros professores na mesma.

    if($associacao == NIVELPROFESSOR){
        if($turma->getProfResponsavel() == $userId){
            $podeFazerAcao = true;
        }else{
            $podeFazerAcao = false;
        }
    }else{
        $podeFazerAcao = true;
    }

    if($podeFazerAcao){
        if($usuario->pertenceTurma($codTurma)){
            $q->solicitar("UPDATE TurmasUsuario
                            SET associacao='$associacao'
                              WHERE codTurma = '$codTurma' AND codUsuario = '$codUsuario'");
        }
        else{
         $q->solicitar("INSERT INTO TurmasUsuario(codTurma, codUsuario, associacao)
				  VALUES
				      ('$codTurma',
				      '$codUsuario',
				      '$associacao')");
        }
    }
}

magic_redirect("insereUsuario.php?turma=".$codTurma."&deOndeVem=".$deOndeVem);