<?php
	session_start();

	require_once("../../cfg.php");
	require_once("../../bd.php");
	require_once("../../funcoes_aux.php");
	require_once("../../usuarios.class.php");
	require_once("comentario.class.php");
	require_once("desenho.class.php");

	$user_id = $_SESSION['SS_usuario_id'];
	$id = isset($_POST['desenho'])?$_POST['desenho']:0;

	
	if ($id != 0){
		$DESENHO = new Desenho($id);
		$DESENHO->excluir();
	}
?>
