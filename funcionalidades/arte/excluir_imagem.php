<?php
	require("../../cfg.php");
	require("../../bd.php");
	require("../../funcoes_aux.php");
	require("../../usuarios.class.php");
	require("comentario.class.php");
	require("desenho.class.php");

	$user = usuario_sessao();
	$id = (int) (isset($_POST['desenho']) && is_numeric($_POST['desenho'])) ? $_POST['desenho'] : die("");

	
	if ($id != 0){
		$DESENHO = new Desenho($id);
		$DESENHO->excluir();
	}
?>
