<?php
//sempre que usar este arquivo, garantir que ele é incluido por ultimo

//Para chamar quando for preciso verificar se um usuario é professor para determinar se ele pode ver a página.
function validaPermissaoAcesso($umId){
    if(!verificaSeProfessor($umId) && !verificaSeAdministrador($umId)){
        die("<b>Acesso negado.<br><a href=\"../../tela_inicial_geral.php\">Retornar para a tela inicial.</a></b>");}
}

//Verifica se o usuario é professor em alguma turma, retornando true se for.
function verificaSeProfessor($umId){
    $q = new conexao();
    $umIdSanitizado = (int) $umId;
    $q->solicitar("SELECT associacao FROM TurmasUsuario WHERE codUsuario = '$umIdSanitizado'");

    $temPermissao = false;

    for($i=0;$i<$q->registros;$i++){
        if($q->resultado['associacao']==4){ $temPermissao = true;}
        $q->proximo();
    }

    return $temPermissao;
}

//Verifica se o usuario é administrador, retornando true se for.
function verificaSeAdministrador($umId){
    $q = new conexao();
    $umIdSanitizado = (int) $umId;
    $q->solicitar("SELECT usuario_nivel FROM usuarios WHERE usuario_id = '$umIdSanitizado'");

    $temPermissao = false;

    for($i=0;$i<$q->registros;$i++){
        if($q->resultado['usuario_nivel']==NIVELADMIN){ $temPermissao = true;}
        $q->proximo();
    }

    return $temPermissao;
}
