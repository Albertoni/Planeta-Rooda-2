<?php
require_once("cfg.php");
require_once("bd.php");
//sempre que usar este arquivo, inclua ele por ultimo.

//Para chamar quando for preciso verificar se um usuario é professor para determinar se ele pode ver a página.
function validaPermissaoAcesso($umId){
    if(!verificaSeProfessor($umId)){ die("Você não tem permissão para acessar esta página.");}
}

//Verifica se o usuario é professor em alguma turma, retornando true se for.
function verificaSeProfessor($umId){
    $q = new conexao();
    $q->solicitar("SELECT associacao FROM TurmasUsuario WHERE codUsuario = '$umId'");

    $temPermissao = false;

    for($i=0;$i<$q->registros;$i++){
        if($q->resultado['associacao']==4){ $temPermissao = true;}
        $q->proximo();
    }

    return $temPermissao;
}
