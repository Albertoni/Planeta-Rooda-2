<?php
	session_start();

	require("../../cfg.php");
	require("../../bd.php");
	require("../../funcoes_aux.php");
	require("../../usuarios.class.php");
	require("comentario.class.php");
	require("desenho.class.php");

	$user_id = $_SESSION['SS_usuario_id'];
	$id = (int) (isset($_POST['desenho']) && is_numeric($_POST['desenho'])) ? $_POST['desenho'] : 0;

	
	if ($id != 0){
		$DESENHO = new Desenho($id);
		$DESENHO->excluir();
	}
?>
