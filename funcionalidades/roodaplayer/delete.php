<?php
if(!(isset($_POST['codVideo'])) or !(is_numeric($_POST['codVideo']))){
	die("dados necessarios para efetuar deleção não foram enviados com sucesso");
}
if(!(isset($_POST['codTurma'])) or !(is_numeric($_POST['codTurma']))){
	die("dados necessarios para efetuar deleção não foram enviados com sucesso");
}

require_once("../../cfg.php");
require_once("../../bd.php");
require_once("../../funcoes_aux.php");
require_once("../../usuarios.class.php");

$user = usuario_sessao();

$idVideo = (int) $_POST['codVideo'];
$codTurma = (int) $_POST['codTurma'];

$q = new conexao();
$q->solicitarSI("SELECT * FROM $tabela_playerVideos WHERE id='$idVideo' AND turma='$codTurma'");
if($q->registros == 0){
	die("A id de video enviada não corresponde à id de turma enviada.");
}

$permissoes = checa_permissoes(TIPOPLAYER, $codTurma);
if ($permissoes === false){die("Funcionalidade desabilitada para a sua turma.");}
if (!$user->podeAcessar($permissoes['player_deletarVideos'], $codTurma)){
	die("Ops, voc&ecirc; n&atilde;o tem permiss&atilde;o para deletar um video.");
}


$q2 = new conexao();

$q->solicitarSI("DELETE FROM $tabela_playerVideos WHERE id='$idVideo'");
$q2->solicitarSI("DELETE FROM $tabela_playerComentarios WHERE idRef='$idVideo'");

$deuErro = false;
if($q->erro != ""){
	echo $q->erro;
	$deuErro = true;
}
if ($q2->erro != ""){
	echo $q2->erro;
	$deuErro = true;
}
if(!$deuErro){
	echo "del-ok";
}
