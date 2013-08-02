<?php
if(isset($_POST['codVideo']) and is_numeric($_POST['codVideo'])){
	if ( !(isset($_POST['codTurma']) and is_numeric($_POST['codTurma']))){
		die("dados necessarios para efetuar deleção não foram enviados com sucesso");
	}
	
	session_start();
	
	require_once("../../cfg.php");
	require_once("../../bd.php");
	require_once("../../funcoes_aux.php");
	require_once("../../usuarios.class.php");
	
	$idVideo = $_POST['codVideo'];
	$codTurma = $_POST['codTurma'];
	
	$permissoes = checa_permissoes(TIPOPLAYER, $codTurma);
	if ($permissoes === false){die("Funcionalidade desabilitada para a sua turma.");}

	$user = new Usuario();
	$user->openUsuario($_SESSION['SS_usuario_id']);
	if (!$user->podeAcessar($permissoes['player_deletarVideos'], $codTurma)){
		die("Ops, voc&ecirc; n&atilde;o tem permiss&atilde;o para deletar um video.");
	}
	
	$q = new conexao();
	$q2 = new conexao();
	
	$q->solicitarSI("DELETE FROM $tabela_playerVideos WHERE id=$idVideo");
	$q2->solicitarSI("DELETE FROM $tabela_playerComentarios WHERE id_video=$idVideo");
	
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
}else{
	echo "dados necessarios para efetuar deleção não foram enviados com sucesso";
}
?>
