<?php

	session_start();

	require("../../cfg.php");
	require("../../bd.php");
	require("../../funcoes_aux.php");
	require("../../usuarios.class.php");
	require("desenho.class.php");

	header('Expires: 0');
	header('Pragma: no-cache');	

	$user_id = $_SESSION['SS_usuario_id'];
	if ( isset($_POST['imagem']) ){
		$id		= $_POST['id'];
		$imagem	= $_POST['imagem'];

		if ( isset($_SESSION['arte_img_'.$id]) ){
			$_SESSION['arte_img_'.$id] = $_SESSION['arte_img_'.$id].$imagem;
		}else{
			$_SESSION['arte_img_'.$id] = $imagem;
			echo "humm";
		}

		if (strlen($_SESSION['arte_img_'.$id]) >= $_SESSION['arte_tamanho_'.$id]){

			$DESENHO = new Desenho($id);
			$DESENHO->desenho = $_SESSION['arte_img_'.$id];
			$DESENHO->salvar();

			unset($_SESSION['arte_img_'.$id]);
			unset($_SESSION['arte_tamanho_'.$id]);

			echo strlen($DESENHO->desenho)."\n";				// o envio do arquivo acabou
		}else{
			echo "0";				// não terminou o envio
		}
	}else{

		$id			= $_POST['id'];
		$tamanho		= $_POST['tamanho'];
		$titulo		= $_POST['titulo'];
		$turma		= $_POST['turma'];
		$existente	= $_POST['existente'];
		
		if ($existente == 0){	// novo desenho
			$DESENHO = new Desenho(0, $user_id, $turma, "", $titulo);
		}else{				// desenho já existente
			$DESENHO = new Desenho($id);
			$DESENHO->desenho = "";
			$DESENHO->titulo = $titulo;
		}
		$DESENHO->salvar();

		$id = $DESENHO->id;
		$_SESSION['arte_tamanho_'.$id] = $tamanho;
		$_SESSION['arte_img_'.$id] = "";

		echo $DESENHO->id;
	}
?>
