<?php
	session_start();

	require("../../cfg.php");
	require("../../bd.php");
	require("../../funcoes_aux.php");
	require("../../usuarios.class.php");
	require("comentario.class.php");
	require("desenho.class.php");

	$user_id = $_SESSION['SS_usuario_id'];
	$existente = isset($_POST['existente'])?$_POST['existente']:false;
	$id = isset($_POST['id'])?$_POST['id']:0;
	$img = $_POST['imagem'];
	$turma = isset($_POST['turma'])?$_POST['turma']:0;
	$titulo = $_POST['titulo'];
	$tags = isset($_POST['tags'])?$_POST['tags']:0;

	if ($existente == 0){ // novo desenho
		$DESENHO = new Desenho(0, $user_id, $turma, $img, $titulo, $tags);
	}else{		   // desenho já existente
		$DESENHO = new Desenho($id);
		$DESENHO->desenho = $img;
		$DESENHO->titulo = $titulo;
	}
	$DESENHO->salvar();
	
	echo $DESENHO->id;
?>
