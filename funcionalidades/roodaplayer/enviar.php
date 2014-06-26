<?php
require_once("../../cfg.php");
require_once("../../bd.php");
require_once("../../funcoes_aux.php");
require_once("../../usuarios.class.php");
require_once("player_aux.php");

$codTurma = (isset($_POST['codTurma']) and is_numeric($_POST['codTurma'])) ? $_POST['codTurma'] : die('A id da turma não foi passada por alguma razão. Favor voltar e tentar novamente');

$permissoes = checa_permissoes(TIPOPLAYER, $codTurma);
if ($permissoes === false){die("Funcionalidade desabilitada para a sua turma.");}

$user = usuario_sessao();
//$user->openUsuario($_SESSION['SS_usuario_id']);
if (!$user->podeAcessar($permissoes['player_inserirVideos'], $codTurma)){
	die("Ops, voc&ecirc; n&atilde;o tem permiss&atilde;o para inserir um video.");
}

if(isset($_POST['nome']) and isset($_POST['link'])){
	$descricao = isset($_POST['descricao']) ? $_POST['descricao'] : "";
	$vid = new video(false, false, $_POST['nome'], $_POST['link'], $descricao, $_POST['codTurma']);
	
	if ($vid->temErro()){
		$head="Location:index.php?turma=$codTurma&erro=".urlencode($vid->getErro());
		header($head);
	}else{
		$head="Location:index.php?turma=$codTurma";
		header($head);
	}
}else{
	die("Ops, voc&ecirc; tentou acessar essa p&aacute;gina diretamente ou algum erro aconteceu, por favor tente novamente.");
}
?>
