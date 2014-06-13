<?php
/**
 * Esta página faz parte da implementção da funcionalidade "Importar Alunos de Outra Turma", delimitada na ata de 28/05 da seguinte maneira.
 * Sua tarefa consiste apenas em retornar uma lista de alunos da turma que foi clicada.
 */
require_once("../../cfg.php");
require_once("../../bd.php");
require_once("../../funcoes_aux.php");
require_once("../../usuarios.class.php");
require_once("verificaPermissoesAdministracao.php");

$user = usuario_sessao();
if($user === false){die("Você não está logado");}
validaPermissaoAcesso($user->getId());

$consulta = new conexao();
$idTurmaSelecionadaSanitizado=(int) $_GET['idTurma'];

$consulta->solicitar("SELECT * FROM TurmasUsuario JOIN usuarios ON codUsuario=usuario_id
                                                WHERE codTurma='$idTurmaSelecionadaSanitizado' AND associacao=".NIVELALUNO);

$superArray = array();

for($i=0; $i < ($consulta->registros); $i++){ // for precisa do -1 porque o ultimo não pode ter virgula
    $superArray[] = array(
        'idUsuario' => $consulta->resultado['usuario_id'],
        'nome' => $consulta->resultado['usuario_nome'],
        'email' => $consulta->resultado['usuario_email'],
        'login' => $consulta->resultado['usuario_login']
     );
    $consulta->proximo();
}

echo json_encode($superArray);